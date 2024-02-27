<?php
require '../includes/db_conn.php';
require '../util/functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch':
            fetchAdmin($conn);
            break;
        case 'fetch_all':
            fetchAllAdmins($conn);
            break;
        case 'add':
            addAdmin($conn);
            break;
        case 'update':
            updateAdmin($conn);
            break;
        case 'delete':
            deleteAdmin($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function fetchAllAdmins($conn)
{
    $sql = "SELECT * FROM admins";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $adminsData = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $adminsData[] = $row;
        }

        echo json_encode(['data' => $adminsData]);
    } else {
        echo json_encode(['data' => []]);
    }

    mysqli_close($conn);
}

function fetchAdmin($conn)
{
    if (isset($_POST['adminNo'])) {
        $adminNo = $_POST['adminNo'];
        $sql = "SELECT * FROM admins WHERE admin_no = '$adminNo'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $adminData = mysqli_fetch_assoc($result);
            echo json_encode($adminData);
        } else {
            echo json_encode(['error' => 'Admin not found']);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }

    mysqli_close($conn);
}

function addAdmin($conn)
{
    $newAdminName = $_POST['newAdminName'];
    $newUsername = $_POST['newUsername'];
    $newEmail = $_POST['newEmail'];

    $checkUsernameQuery = "SELECT * FROM admins WHERE username = '$newUsername'";
    $resultUsername = mysqli_query($conn, $checkUsernameQuery);

    $checkEmailQuery = "SELECT * FROM admins WHERE email = '$newEmail'";
    $resultEmail = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($resultUsername) > 0) {
        echo json_encode(['error' => 'Username already exists']);
        return;
    }

    if (mysqli_num_rows($resultEmail) > 0) {
        echo json_encode(['error' => 'Email already exists']);
        return;
    }

    $randomNumber = generateRandomSixDigitNumber();
    $hashedPassword = hashPassword($randomNumber);

    $sql = "INSERT INTO admins (admin_name, username, email, password) 
            VALUES ('$newAdminName', '$newUsername', '$newEmail', '$hashedPassword')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['message' => 'Admin added successfully']);
    } else {
        echo json_encode(['error' => 'Error adding new admin']);
    }

    mysqli_close($conn);
}

function updateAdmin($conn)
{
    if (isset($_POST['adminNo'])) {
        $adminNo = $_POST['adminNo'];
        $editAdminName = $_POST['updatedAdminName'];

        $sql = "UPDATE admins SET admin_name = '$editAdminName' WHERE admin_no = '$adminNo'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(['message' => 'Admin updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating admin']);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function deleteAdmin($conn)
{
    if (isset($_POST['adminNo'])) {
        $adminNo = $_POST['adminNo'];
        $sql = "SELECT * FROM admins WHERE admin_no = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $adminNo);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $deleteSql = "DELETE FROM admins WHERE admin_no = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($deleteStmt, 'i', $adminNo);

            if (mysqli_stmt_execute($deleteStmt)) {
                echo json_encode(['success' => 'Admin deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error deleting admin']);
            }

            mysqli_stmt_close($deleteStmt);
        } else {
            echo json_encode(['error' => 'Admin not found']);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}
