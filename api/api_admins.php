<?php
require '../includes/db_conn.php';
require '../util/functions.php';

require_once '../vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

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

    $sql = "INSERT INTO admins (admin_name, username, email, password, last_accessed_date) 
            VALUES ('$newAdminName', '$newUsername', '$newEmail', '$hashedPassword', NULL)";

    if (mysqli_query($conn, $sql)) {
        sendWelcomeEmail($newAdminName, $newUsername, $newEmail, $randomNumber);
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

function sendWelcomeEmail($adminName, $username, $adminEmail, $password)
{
    $transport = Transport::fromDsn('smtp://34senith@gmail.com:osfiefvsuqxjgmhv@smtp.gmail.com:587');

    $mailer = new Mailer($transport);

    $email = (new Email());

    $email->from('34senith@gmail.com');

    $email->to('' . $adminEmail);

    $email->subject('Welcome to Ananda College Library System!');

    $email->html('
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
    <h2 style="color: #333;">Welcome to Ananda College Library System</h2>
    <p style="color: #555;">Dear ' . $adminName . ',</p>
    <p style="color: #555;">We are delighted to welcome you to Ananda College Library System! Your admin account has been successfully created, and we are excited to have you as a member of our library community.</p>
    <div style="margin-top: 20px; padding: 10px; background-color: #f9f9f9; border-radius: 6px;" class="login-details">
    <p><strong>Username:</strong> ' . $username . '</p>
    <p><strong>Password:</strong> ' . $password . '</p>
    </div>
    <p style="color: #555; margin-top: 20px;">To access the library system, simply use the provided username and password at our Library Management System. Upon your first login, we recommend changing your password for security purposes.</p>
    <p style="color: #555;">If you have any questions or encounter any issues, feel free to reach out to our support team or visit the library in person.</p>
    <p style="color: #555; margin-top: 20px;">Happy reading!</p>
    <div style="margin-top: 20px; font-size: 12px; color: #777;" class="footer">
    <p>Best regards,<br>Library System Team<br>Ananda College</p>
    </div>
    </div>
    </body>
    </html>
    ');

    $mailer->send($email);
}
