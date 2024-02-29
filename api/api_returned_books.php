<?php
require '../includes/db_conn.php';
require '../util/functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch':
            fetchReturnedBook($conn);
            break;
        case 'fetch_all':
            fetchAllReturnedBooks($conn);
            break;
        case 'update':
            updateReturnedBook($conn);
            break;
        case 'delete':
            deleteReturnedBook($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function fetchAllReturnedBooks($conn)
{
    $sql = "SELECT * FROM returned_books";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $returnedBookData = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $returnedBookData[] = $row;
        }

        echo json_encode(['data' => $returnedBookData]);
    } else {
        echo json_encode(['data' => []]);
    }

    mysqli_close($conn);
}

function fetchReturnedBook($conn)
{
    if (isset($_POST['returnedBookNo'])) {
        $returnedBookNo = $_POST['returnedBookNo'];
        $sql = "SELECT * FROM returned_books WHERE returned_book_no = '$returnedBookNo'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $returnedBookData = mysqli_fetch_assoc($result);
            echo json_encode($returnedBookData);
        } else {
            echo json_encode(['error' => 'Returned book not found']);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }

    mysqli_close($conn);
}

function updateReturnedBook($conn)
{
    if (isset($_POST['returnedBookNo'])) {
        $returnedBookNo = $_POST['returnedBookNo'];
        $updatedReturnedDate = $_POST['updatedReturnedDate'];

        $sql = "UPDATE returned_books SET returned_date = '$updatedReturnedDate' WHERE returned_book_no = '$returnedBookNo'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(['message' => 'Returned book updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating returned book']);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function deleteReturnedBook($conn)
{
    if (isset($_POST['returnedBookNo'])) {
        $returnedBookNo = $_POST['returnedBookNo'];
        $sql = "SELECT * FROM returned_books WHERE returned_book_no = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $returnedBookNo);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $deleteSql = "DELETE FROM returned_books WHERE returned_book_no = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($deleteStmt, 'i', $returnedBookNo);

            if (mysqli_stmt_execute($deleteStmt)) {
                echo json_encode(['success' => 'Returned book deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error deleting returned book']);
            }

            mysqli_stmt_close($deleteStmt);
        } else {
            echo json_encode(['error' => 'Returned book not found']);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}
