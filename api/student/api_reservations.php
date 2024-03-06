<?php
require '../../includes/db_conn.php';
require '../../util/functions.php';

session_start();

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch_all':
            fetchAllReservations($conn);
            break;
        case 'delete':
            deleteReservation($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function fetchAllReservations($conn)
{
    if (isset($_SESSION['id'])) {
        $studentNo = $_SESSION['id'];

        $sql = "SELECT * FROM reservations WHERE student_no = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $studentNo);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $reservationsData = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $reservationsData[] = $row;
            }

            echo json_encode(['data' => $reservationsData]);
        } else {
            echo json_encode(['data' => []]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['error' => 'Student ID not found in session']);
    }

    mysqli_close($conn);
}

function deleteReservation($conn)
{
    if (isset($_POST['reservationNo'])) {
        $reservationNo = $_POST['reservationNo'];
        $sql = "SELECT * FROM reservations WHERE reservation_no = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $reservationNo);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $deleteSql = "DELETE FROM reservations WHERE reservation_no = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($deleteStmt, 'i', $reservationNo);

            if (mysqli_stmt_execute($deleteStmt)) {
                echo json_encode(['success' => 'Reservation deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error deleting reservation']);
            }

            mysqli_stmt_close($deleteStmt);
        } else {
            echo json_encode(['error' => 'Reservation not found']);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}
