<?php
require '../includes/db_conn.php';
require '../util/functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch':
            fetchReservation($conn);
            break;
        case 'fetch_all':
            fetchAllReservations($conn);
            break;
        case 'update':
            updateReservation($conn);
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
    $sql = "SELECT * FROM reservations";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $reservationsData = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $reservationsData[] = $row;
        }

        echo json_encode(['data' => $reservationsData]);
    } else {
        echo json_encode(['data' => []]);
    }

    mysqli_close($conn);
}

function fetchReservation($conn)
{
    if (isset($_POST['reservationNo'])) {
        $reservationNo = $_POST['reservationNo'];
        $sql = "SELECT * FROM reservations WHERE reservation_no = '$reservationNo'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $reservationData = mysqli_fetch_assoc($result);
            echo json_encode($reservationData);
        } else {
            echo json_encode(['error' => 'Reservation not found']);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }

    mysqli_close($conn);
}

function updateReservation($conn)
{
    if (isset($_POST['reservationNo'])) {
        $reservationNo = $_POST['reservationNo'];
        $updatedBookNo = $_POST['updatedBookNo'];
        $updatedBookTitle = $_POST['updatedBookTitle'];
        $updatedReservedDate = $_POST['updatedReservedDate'];

        $sql = "UPDATE reservations SET book_no = '$updatedBookNo', book_title = '$updatedBookTitle', reserved_date = '$updatedReservedDate' WHERE reservation_no = '$reservationNo'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(['message' => 'Reservation updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating reservation']);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
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
