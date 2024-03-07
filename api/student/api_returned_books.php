<?php
require '../../includes/db_conn.php';
require '../../util/functions.php';

session_start();

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch_all':
            fetchAllReturnedBooks($conn);
            break;
        case 'rate':
            updateBookRatings($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function updateBookRatings($conn)
{
    $userRating = $_POST['selectedRating'];
    $bookNo = $_POST['currentBookNo'];

    $getRatingSql = "SELECT user_ratings, no_of_ratings FROM books WHERE book_no = ?";
    $getRatingStmt = mysqli_prepare($conn, $getRatingSql);
    mysqli_stmt_bind_param($getRatingStmt, 'i', $bookNo);
    mysqli_stmt_execute($getRatingStmt);
    $resultRating = mysqli_stmt_get_result($getRatingStmt);

    if ($resultRating && mysqli_num_rows($resultRating) > 0) {
        $row = mysqli_fetch_assoc($resultRating);
        $currentUserRatings = $row['user_ratings'];
        $currentNoOfRatings = $row['no_of_ratings'];

        $newUserRatings = ($currentNoOfRatings * $currentUserRatings + $userRating) / ($currentNoOfRatings + 1);
        $newNoOfRatings = $currentNoOfRatings + 1;

        $updateRatingSql = "UPDATE books SET user_ratings = ?, no_of_ratings = ? WHERE book_no = ?";
        $updateRatingStmt = mysqli_prepare($conn, $updateRatingSql);
        mysqli_stmt_bind_param($updateRatingStmt, 'dsi', $newUserRatings, $newNoOfRatings, $bookNo);
        mysqli_stmt_execute($updateRatingStmt);

        $setRatedSql = "UPDATE returned_books SET is_rated = true WHERE book_no = ?";
        $setRatedStmt = mysqli_prepare($conn, $setRatedSql);
        mysqli_stmt_bind_param($setRatedStmt, 'i', $bookNo);
        mysqli_stmt_execute($setRatedStmt);

        echo json_encode(['newAverageRating' => $newUserRatings]);
    } else {
        $errorInfo = [
            'message' => 'Error updating book ratings',
            'mysqli_error' => mysqli_error($conn),
        ];

        echo json_encode(['error' => $errorInfo]);
    }

    mysqli_stmt_close($getRatingStmt);
    mysqli_stmt_close($updateRatingStmt);
    mysqli_stmt_close($setRatedStmt);
}

function fetchAllReturnedBooks($conn)
{
    if (isset($_SESSION['id'])) {
        $studentNo = $_SESSION['id'];

        $sql = "SELECT * FROM returned_books WHERE student_no = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $studentNo);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $returnedBookData = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $returnedBookData[] = $row;
            }

            echo json_encode(['data' => $returnedBookData]);
        } else {
            echo json_encode(['data' => []]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['error' => 'Student ID not found in session']);
    }

    mysqli_close($conn);
}
