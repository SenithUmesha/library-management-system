<?php

require '../../includes/db_conn.php';
require_once '../../vendor/autoload.php';
require_once '../../includes/mailer.php';

header('Content-Type: application/json; charset=utf-8');
session_start();

if (($_SESSION['account_type'] ?? null) !== 'student' || !isset($_SESSION['id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Student access required']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'return':
        returnBook($conn);
        break;
    case 'fetch_all':
        fetchAllBorrowedBooks($conn);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or missing action']);
}

function fetchAllBorrowedBooks(mysqli $conn): void
{
    $studentNo = (int) $_SESSION['id'];
    $stmt = mysqli_prepare($conn, 'SELECT * FROM borrowed_books WHERE student_no = ? ORDER BY borrowed_date DESC');
    mysqli_stmt_bind_param($stmt, 'i', $studentNo);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo json_encode(['data' => mysqli_fetch_all($result, MYSQLI_ASSOC)]);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function returnBook(mysqli $conn): void
{
    $studentNo = (int) $_SESSION['id'];
    $borrowedBookNo = filter_input(INPUT_POST, 'borrowedBookNo', FILTER_VALIDATE_INT);

    if (!$borrowedBookNo) {
        http_response_code(422);
        echo json_encode(['error' => 'Invalid borrowed-book number']);
        return;
    }

    $borrowedStmt = mysqli_prepare(
        $conn,
        'SELECT borrowed_book_no, book_no, book_title, student_no, student_name, borrowed_date, due_date FROM borrowed_books WHERE borrowed_book_no = ? AND student_no = ?'
    );
    mysqli_stmt_bind_param($borrowedStmt, 'ii', $borrowedBookNo, $studentNo);
    mysqli_stmt_execute($borrowedStmt);
    $borrowed = mysqli_fetch_assoc(mysqli_stmt_get_result($borrowedStmt));
    mysqli_stmt_close($borrowedStmt);

    if (!$borrowed) {
        http_response_code(404);
        echo json_encode(['error' => 'Borrowed book not found for this student']);
        return;
    }

    $bookNo = (int) $borrowed['book_no'];
    $bookTitle = $borrowed['book_title'];
    $studentName = $borrowed['student_name'];
    $dueDate = $borrowed['due_date'];
    $returnedAt = date('Y-m-d H:i:s');

    mysqli_begin_transaction($conn);

    try {
        if ($dueDate !== null && strtotime($dueDate) < time()) {
            $fineAmount = 50.00;
            $fineStmt = mysqli_prepare(
                $conn,
                "INSERT INTO fines (student_no, student_name, book_no, book_title, fine_amount, issued_date, due_date, payment_status, paid_date) VALUES (?, ?, ?, ?, ?, ?, ?, 'Unpaid', NULL)"
            );
            mysqli_stmt_bind_param($fineStmt, 'isisdss', $studentNo, $studentName, $bookNo, $bookTitle, $fineAmount, $returnedAt, $dueDate);
            mysqli_stmt_execute($fineStmt);
            mysqli_stmt_close($fineStmt);
        }

        $returnStmt = mysqli_prepare(
            $conn,
            'INSERT INTO returned_books (book_no, book_title, student_no, student_name, returned_date, is_rated) VALUES (?, ?, ?, ?, ?, 0)'
        );
        mysqli_stmt_bind_param($returnStmt, 'isiss', $bookNo, $bookTitle, $studentNo, $studentName, $returnedAt);
        mysqli_stmt_execute($returnStmt);
        mysqli_stmt_close($returnStmt);

        $deleteStmt = mysqli_prepare($conn, 'DELETE FROM borrowed_books WHERE borrowed_book_no = ? AND student_no = ?');
        mysqli_stmt_bind_param($deleteStmt, 'ii', $borrowedBookNo, $studentNo);
        mysqli_stmt_execute($deleteStmt);
        mysqli_stmt_close($deleteStmt);

        $stockStmt = mysqli_prepare($conn, 'UPDATE books SET no_of_copies = no_of_copies + 1 WHERE book_no = ?');
        mysqli_stmt_bind_param($stockStmt, 'i', $bookNo);
        mysqli_stmt_execute($stockStmt);
        mysqli_stmt_close($stockStmt);

        mysqli_commit($conn);
    } catch (Throwable $exception) {
        mysqli_rollback($conn);
        error_log('Book return failed: ' . $exception->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Error returning book']);
        return;
    }

    $notificationsSent = notifyReservations($conn, $bookNo);

    echo json_encode([
        'message' => 'Book returned successfully',
        'reservationNotificationsSent' => $notificationsSent,
    ]);

    mysqli_close($conn);
}

function notifyReservations(mysqli $conn, int $bookNo): int
{
    $stmt = mysqli_prepare(
        $conn,
        'SELECT reservation_no, email, book_title, reserved_date FROM reservations WHERE book_no = ? ORDER BY reserved_date ASC'
    );
    mysqli_stmt_bind_param($stmt, 'i', $bookNo);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $sent = 0;
    while ($reservation = mysqli_fetch_assoc($result)) {
        if (!sendReservationEmail($reservation['email'], $reservation['book_title'], $reservation['reserved_date'])) {
            continue;
        }

        $reservationNo = (int) $reservation['reservation_no'];
        $deleteStmt = mysqli_prepare($conn, 'DELETE FROM reservations WHERE reservation_no = ?');
        mysqli_stmt_bind_param($deleteStmt, 'i', $reservationNo);
        mysqli_stmt_execute($deleteStmt);
        mysqli_stmt_close($deleteStmt);
        $sent++;
    }

    mysqli_stmt_close($stmt);
    return $sent;
}

function sendReservationEmail(string $studentEmail, string $bookTitle, string $reservedDate): bool
{
    $safeTitle = htmlspecialchars($bookTitle, ENT_QUOTES, 'UTF-8');
    $safeReservedDate = htmlspecialchars($reservedDate, ENT_QUOTES, 'UTF-8');

    $html = <<<HTML
    <h2>Your reserved book is available</h2>
    <p>The book <strong>{$safeTitle}</strong> is available to borrow.</p>
    <p><strong>Reserved:</strong> {$safeReservedDate}</p>
    <p>Please contact the library if you need help with the reservation.</p>
    HTML;

    return sendLibraryEmail($studentEmail, 'Book availability notification', $html);
}
