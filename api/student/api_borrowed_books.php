<?php
require '../../includes/db_conn.php';
require '../../util/functions.php';

require_once '../../vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

session_start();

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'return':
            returnBook($conn);
            break;
        case 'fetch_all':
            fetchAllBorrowedBooks($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function fetchAllBorrowedBooks($conn)
{
    if (isset($_SESSION['id'])) {
        $studentNo = $_SESSION['id'];

        $sql = "SELECT * FROM borrowed_books WHERE student_no = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $studentNo);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $borrowedBookData = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $borrowedBookData[] = $row;
            }

            echo json_encode(['data' => $borrowedBookData]);
        } else {
            echo json_encode(['data' => []]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['error' => 'Student ID not found in session']);
    }

    mysqli_close($conn);
}

function returnBook($conn)
{
    $bookNo = $_POST['bookNo'];
    $bookTitle = $_POST['bookTitle'];
    $studentNo = $_POST['studentNo'];
    $studentName = $_POST['studentName'];
    $borrowedBookNo = $_POST['borrowedBookNo'];

    $currentDateTime = date('Y-m-d H:i:s');

    $returnSql = "INSERT INTO returned_books (book_no, book_title, student_no, student_name, returned_date) 
                  VALUES (?, ?, ?, ?, ?)";
    $returnStmt = mysqli_prepare($conn, $returnSql);
    mysqli_stmt_bind_param($returnStmt, 'isiss', $bookNo, $bookTitle, $studentNo, $studentName, $currentDateTime);

    $deleteSql = "DELETE FROM borrowed_books WHERE borrowed_book_no = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteSql);
    mysqli_stmt_bind_param($deleteStmt, 'i', $borrowedBookNo);

    $updateSql = "UPDATE books SET no_of_copies = no_of_copies + 1 WHERE book_no = ?";
    $updateStmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($updateStmt, 'i', $bookNo);

    $checkReservationsSql = "SELECT email, book_title, reserved_date, reservation_no FROM reservations WHERE book_no = ?";
    $checkReservationsStmt = mysqli_prepare($conn, $checkReservationsSql);
    mysqli_stmt_bind_param($checkReservationsStmt, 'i', $bookNo);

    $success = false;

    if (mysqli_stmt_execute($returnStmt) && mysqli_stmt_execute($deleteStmt) && mysqli_stmt_execute($updateStmt)) {
        mysqli_stmt_execute($checkReservationsStmt);
        $resultReservations = mysqli_stmt_get_result($checkReservationsStmt);

        if ($resultReservations && mysqli_num_rows($resultReservations) > 0) {
            $emails = [];

            while ($row = mysqli_fetch_assoc($resultReservations)) {
                $email = $row['email'];
                $bookTitle = $row['book_title'];
                $reservedDate = $row['reserved_date'];
                $reservationNo = $row['reservation_no'];

                sendEmail($email, $bookTitle, $reservedDate);

                $emails[] = $email;

                $reservationsToDelete[] = $reservationNo;
            }

            $deleteReservationsSql = "DELETE FROM reservations WHERE reservation_no = ?";
            $deleteReservationsStmt = mysqli_prepare($conn, $deleteReservationsSql);

            foreach ($reservationsToDelete as $reservationNo) {
                mysqli_stmt_bind_param($deleteReservationsStmt, 'i', $reservationNo);
                mysqli_stmt_execute($deleteReservationsStmt);
            }

            mysqli_stmt_close($deleteReservationsStmt);

            $success = true;
            echo json_encode(['message' => 'Book returned successfully and email sent']);
        } else {
            $success = true;
            echo json_encode(['message' => 'Book returned successfully']);
        }
    } else {
        echo json_encode(['error' => 'Error returning book']);
    }

    mysqli_stmt_close($returnStmt);
    mysqli_stmt_close($deleteStmt);
    mysqli_stmt_close($updateStmt);
    mysqli_stmt_close($checkReservationsStmt);

    mysqli_close($conn);

    return $success;
}

function sendEmail($studentEmail, $bookTitle, $reservedDate)
{
    $transport = Transport::fromDsn('smtp://34senith@gmail.com:osfiefvsuqxjgmhv@smtp.gmail.com:587');

    $mailer = new Mailer($transport);

    $email = (new Email());

    $email->from('34senith@gmail.com');

    $email->to('' . $studentEmail);

    $email->subject('Book Availability Notification');

    $email->html('
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4;">
<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
<h2 style="color: #333;">Book Availability Notification</h2>
<p style="color: #555;">Dear Student,</p>
<p style="color: #555;">Good news! The book you reserved at Ananda College Library System is now available.</p>
<div style="margin-top: 20px; padding: 10px; background-color: #f9f9f9; border-radius: 6px;" class="book-details">
<p><strong>Book Title:</strong> ' . $bookTitle . '</p>
<p><strong>Reserved Date:</strong> ' . $reservedDate . '</p>
</div>
<p style="color: #555; margin-top: 20px;">You can now borrow the book from the library. If you have any questions or need further assistance, please contact our library staff.</p>
<p style="color: #555;">Happy reading!</p>
<div style="margin-top: 20px; font-size: 12px; color: #777;" class="footer">
<p>Best regards,<br>Library System Team<br>Ananda College</p>
</div>
</div>
</body>
</html>
');

    $mailer->send($email);
}
