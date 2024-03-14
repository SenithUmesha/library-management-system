<?php
require '../includes/db_conn.php';
require '../util/functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch':
            fetchBorrowedBook($conn);
            break;
        case 'fetch_all':
            fetchAllBorrowedBooks($conn);
            break;
        case 'update':
            updateBorrowedBook($conn);
            break;
        case 'delete':
            deleteBorrowedBook($conn);
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
    $sql = "SELECT * FROM borrowed_books";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $borrowedBookData = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $borrowedBookData[] = $row;
        }

        echo json_encode(['data' => $borrowedBookData]);
    } else {
        echo json_encode(['data' => []]);
    }

    mysqli_close($conn);
}

function fetchBorrowedBook($conn)
{
    if (isset($_POST['borrowedBookNo'])) {
        $borrowedBookNo = $_POST['borrowedBookNo'];
        $sql = "SELECT * FROM borrowed_books WHERE borrowed_book_no = '$borrowedBookNo'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $borrowedBookData = mysqli_fetch_assoc($result);
            echo json_encode($borrowedBookData);
        } else {
            echo json_encode(['error' => 'Borrowed book not found']);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }

    mysqli_close($conn);
}

function updateBorrowedBook($conn)
{
    if (isset($_POST['borrowedBookNo'])) {
        $borrowedBookNo = $_POST['borrowedBookNo'];
        $updatedBorrowedDate = $_POST['updatedBorrowedDate'];
        $updatedDueDate = $_POST['updatedDueDate'];

        $sql = "UPDATE borrowed_books SET borrowed_date = '$updatedBorrowedDate', due_date = '$updatedDueDate' WHERE borrowed_book_no = '$borrowedBookNo'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(['message' => 'Borrowed book updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating borrowed book']);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function deleteBorrowedBook($conn)
{
    if (isset($_POST['borrowedBookNo'])) {
        $borrowedBookNo = $_POST['borrowedBookNo'];
        $sql = "SELECT * FROM borrowed_books WHERE borrowed_book_no = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $borrowedBookNo);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $deleteSql = "DELETE FROM borrowed_books WHERE borrowed_book_no = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($deleteStmt, 'i', $borrowedBookNo);

            if (mysqli_stmt_execute($deleteStmt)) {
                echo json_encode(['success' => 'Borrowed book deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error deleting borrowed book']);
            }

            mysqli_stmt_close($deleteStmt);
        } else {
            echo json_encode(['error' => 'Borrowed book not found']);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}
