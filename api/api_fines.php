<?php
require '../includes/db_conn.php';
require '../util/functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch':
            fetchFine($conn);
            break;
        case 'fetch_all':
            fetchAllFines($conn);
            break;
        case 'update':
            updateFine($conn);
            break;
        case 'delete':
            deleteFine($conn);
            break;
        case 'paid':
            markPaid($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function markPaid($conn)
{
    if (isset($_POST['fineNo'])) {
        $fineNo = $_POST['fineNo'];
        $currentDateTime = date('Y-m-d H:i:s');

        $sql = "UPDATE fines SET payment_status = 'Paid' ,paid_date= '$currentDateTime'  WHERE fine_no = '$fineNo'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(['message' => 'Fine updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating fines']);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function fetchAllFines($conn)
{
    $sql = "SELECT * FROM fines";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $finesData = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $finesData[] = $row;
        }

        echo json_encode(['data' => $finesData]);
    } else {
        echo json_encode(['data' => []]);
    }

    mysqli_close($conn);
}

function fetchFine($conn)
{
    if (isset($_POST['fineNo'])) {
        $fineNo = $_POST['fineNo'];
        $sql = "SELECT * FROM fines WHERE fine_no = '$fineNo'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $finesData = mysqli_fetch_assoc($result);
            echo json_encode($finesData);
        } else {
            echo json_encode(['error' => 'Fine not found']);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }

    mysqli_close($conn);
}

function updateFine($conn)
{
    if (isset($_POST['fineNo'])) {
        $fineNo = $_POST['fineNo'];
        $updatedFineAmount = $_POST['updatedFineAmount'];

        $sql = "UPDATE fines SET fine_amount = '$updatedFineAmount' WHERE fine_no = '$fineNo'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(['message' => 'Fine updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating fines']);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function deleteFine($conn)
{
    if (isset($_POST['fineNo'])) {
        $fineNo = $_POST['fineNo'];
        $sql = "SELECT * FROM fines WHERE fine_no = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $fineNo);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $deleteSql = "DELETE FROM fines WHERE fine_no = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($deleteStmt, 'i', $fineNo);

            if (mysqli_stmt_execute($deleteStmt)) {
                echo json_encode(['success' => 'Fine deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error deleting fines']);
            }

            mysqli_stmt_close($deleteStmt);
        } else {
            echo json_encode(['error' => 'Fine not found']);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}
