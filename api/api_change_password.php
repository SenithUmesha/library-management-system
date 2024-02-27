<?php
require '../includes/db_conn.php';
require '../util/functions.php';
require '../util/snippet.php';

session_start();

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'changePassword':
            changePassword($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function changePassword($conn)
{
    $response = ['success' => false, 'message' => ''];

    $newPassword = sanitize(trim($_POST['newPassword']));
    $confirmPassword = sanitize(trim($_POST['confirmPassword']));

    if ($newPassword !== $confirmPassword) {
        $response['message'] = 'Passwords do not match';
    } else {
        $hashedPassword = hashPassword($newPassword);
        $userId = $_SESSION['id'];
        $accountType = isset($_SESSION['account_type']) ? $_SESSION['account_type'] : '';

        $tableName = ($accountType === 'admin') ? 'admins' : 'students';

        $sql = "UPDATE $tableName SET password = ?, last_accessed_date = CURRENT_TIMESTAMP WHERE {$accountType}_no = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'si', $hashedPassword, $userId);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $response['success'] = true;
                $response['message'] = 'Password changed successfully';

                if ($accountType === 'admin') {
                    $response['redirect'] = 'view/admin/students.php';
                } else {
                    $response['redirect'] = 'view/student/profile.php';
                }
            } else {
                $response['message'] = 'Error changing password';
            }

            mysqli_stmt_close($stmt);
        } else {
            $response['message'] = 'Error preparing statement';
        }
    }

    echo json_encode($response);
}
