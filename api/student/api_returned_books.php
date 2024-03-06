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
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
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
