<?php
require '../../includes/db_conn.php';
require '../../util/functions.php';

session_start();

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch_all':
            fetchAllBooks($conn);
            break;
        case 'reserve':
            reserveBook($conn);
            break;
        case 'borrow':
            borrowBooks($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function fetchAllBooks($conn)
{
    $sql = "SELECT * FROM books";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $booksData = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $booksData[] = $row;
        }

        echo json_encode(['data' => $booksData]);
    } else {
        echo json_encode(['data' => []]);
    }

    mysqli_close($conn);
}

function reserveBook($conn)
{
    if (isset($_POST['bookNo'])) {
        $bookNo = $_POST['bookNo'];
        $bookTitle = $_POST['bookTitle'];
        $studentNo = $_SESSION['id'];

        $checkReservationSql = "SELECT * FROM reservations WHERE book_no = ? AND student_no = ?";
        $checkReservationStmt = mysqli_prepare($conn, $checkReservationSql);
        mysqli_stmt_bind_param($checkReservationStmt, 'ii', $bookNo, $studentNo);
        mysqli_stmt_execute($checkReservationStmt);
        $resultCheckReservation = mysqli_stmt_get_result($checkReservationStmt);

        if ($resultCheckReservation && mysqli_num_rows($resultCheckReservation) > 0) {
            echo json_encode(['error' => 'You have already reserved this book.']);
            mysqli_stmt_close($checkReservationStmt);
            return;
        }

        $getStudentInfoSql = "SELECT student_name, email FROM students WHERE student_no = ?";
        $getStudentInfoStmt = mysqli_prepare($conn, $getStudentInfoSql);
        mysqli_stmt_bind_param($getStudentInfoStmt, 'i', $studentNo);
        mysqli_stmt_execute($getStudentInfoStmt);
        $resultStudentInfo = mysqli_stmt_get_result($getStudentInfoStmt);

        if ($resultStudentInfo && $rowStudentInfo = mysqli_fetch_assoc($resultStudentInfo)) {
            $studentName = $rowStudentInfo['student_name'];
            $email = $rowStudentInfo['email'];

            $reservedDate = date('Y-m-d H:i:s');

            $insertReservationSql = "INSERT INTO reservations (book_no, book_title, student_no, student_name, reserved_date, email) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $insertReservationStmt = mysqli_prepare($conn, $insertReservationSql);

            mysqli_stmt_bind_param($insertReservationStmt, 'isisss', $bookNo, $bookTitle, $studentNo, $studentName, $reservedDate, $email);

            if (mysqli_stmt_execute($insertReservationStmt)) {
                echo json_encode(['message' => 'Book reserved successfully']);
            } else {
                echo json_encode(['error' => 'Error reserving book']);
            }
        } else {
            echo json_encode(['error' => 'Student information not found']);
        }

        mysqli_stmt_close($insertReservationStmt);
        mysqli_stmt_close($getStudentInfoStmt);
        mysqli_stmt_close($checkReservationStmt);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function borrowBooks($conn)
{
}
