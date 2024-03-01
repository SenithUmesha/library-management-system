<?php
require '../includes/db_conn.php';
require '../util/functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch':
            fetchBook($conn);
            break;
        case 'fetch_all':
            fetchAllBooks($conn);
            break;
        case 'add':
            addBook($conn);
            break;
        case 'update':
            updateBook($conn);
            break;
        case 'delete':
            deleteBook($conn);
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

function fetchBook($conn)
{
    if (isset($_POST['bookNo'])) {
        $bookNo = $_POST['bookNo'];
        $sql = "SELECT * FROM books WHERE book_no = '$bookNo'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $bookData = mysqli_fetch_assoc($result);
            echo json_encode($bookData);
        } else {
            echo json_encode(['error' => 'Book not found']);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }

    mysqli_close($conn);
}

function addBook($conn)
{
    $newBookTitle = $_POST['newBookTitle'];
    $newAuthorName = $_POST['newAuthorName'];
    $newISBN = $_POST['newISBN'];
    $newNoOfCopies = $_POST['newNoOfCopies'];
    $newPublisher = $_POST['newPublisher'];
    $newCategories = $_POST['newCategories'];
    $newLanguage = $_POST['newLanguage'];
    $newDescription = $_POST['newDescription'];
    $newLocation = $_POST['newLocation'];

    $checkISBNQuery = "SELECT * FROM books WHERE isbn_no = '$newISBN'";
    $resultISBN = mysqli_query($conn, $checkISBNQuery);

    if (mysqli_num_rows($resultISBN) > 0) {
        echo json_encode(['error' => 'ISBN no. already exists']);
        return;
    }

    $currentDateTime = date('Y-m-d H:i:s');

    $sql = "INSERT INTO books (book_title, author_name, isbn_no, no_of_copies, publisher, categories, language, description, location, added_date, user_ratings) 
            VALUES ('$newBookTitle', '$newAuthorName', '$newISBN', '$newNoOfCopies', '$newPublisher', '$newCategories', '$newLanguage', '$newDescription', '$newLocation', '$currentDateTime', 0.0)";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['message' => 'Book added successfully']);
    } else {
        echo json_encode(['error' => 'Error adding new book']);
    }

    mysqli_close($conn);
}

function updateBook($conn)
{
    if (isset($_POST['bookNo'])) {
        $bookNo = $_POST['bookNo'];
        $updatedBookTitle = $_POST['updatedBookTitle'];
        $updatedAuthorName = $_POST['updatedAuthorName'];
        $updatedISBN = $_POST['updatedISBN'];
        $updatedNoOfCopies = $_POST['updatedNoOfCopies'];
        $updatedPublisher = $_POST['updatedPublisher'];
        $updatedCategories = $_POST['updatedCategories'];
        $updatedLanguage = $_POST['updatedLanguage'];
        $updatedDescription = $_POST['updatedDescription'];
        $updatedLocation = $_POST['updatedLocation'];

        $checkISBNQuery = "SELECT * FROM books WHERE isbn_no = '$updatedISBN' AND book_no != '$bookNo'";
        $resultISBN = mysqli_query($conn, $checkISBNQuery);

        if (mysqli_num_rows($resultISBN) > 0) {
            echo json_encode(['error' => 'ISBN no. already exists']);
            return;
        }

        $sql = "UPDATE books SET book_title = '$updatedBookTitle', author_name = '$updatedAuthorName', isbn_no = '$updatedISBN', no_of_copies = '$updatedNoOfCopies', publisher = '$updatedPublisher', categories = '$updatedCategories', language = '$updatedLanguage', description = '$updatedDescription', location = '$updatedLocation' WHERE book_no = '$bookNo'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(['message' => 'Book updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating book']);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function deleteBook($conn)
{
    if (isset($_POST['bookNo'])) {
        $bookNo = $_POST['bookNo'];
        $sql = "SELECT * FROM books WHERE book_no = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $bookNo);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $deleteSql = "DELETE FROM books WHERE book_no = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($deleteStmt, 'i', $bookNo);

            if (mysqli_stmt_execute($deleteStmt)) {
                echo json_encode(['success' => 'Book deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error deleting book']);
            }

            mysqli_stmt_close($deleteStmt);
        } else {
            echo json_encode(['error' => 'Book not found']);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}
