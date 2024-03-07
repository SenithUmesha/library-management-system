<?php
require '../../includes/db_conn.php';
require '../../util/functions.php';

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
}

function borrowBooks($conn)
{
}
