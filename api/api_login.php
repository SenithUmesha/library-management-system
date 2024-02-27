<?php
require '../includes/db_conn.php';
require '../util/functions.php';
require '../util/snippet.php';

session_start();

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'submit':
            login($conn);
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function login($conn)
{
    $response = ['success' => false, 'message' => ''];

    $username = sanitize(trim($_POST['username']));
    $password = sanitize(trim($_POST['password']));

    $sql_admin = "SELECT * FROM admins WHERE username = ?";
    $stmt_admin = mysqli_prepare($conn, $sql_admin);
    mysqli_stmt_bind_param($stmt_admin, "s", $username);
    mysqli_stmt_execute($stmt_admin);
    $result_admin = mysqli_stmt_get_result($stmt_admin);

    if ($row = mysqli_fetch_assoc($result_admin)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['id'] = $row['admin_no'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['name'] = $row['admin_name'];
            $_SESSION['account_type'] = "admin";

            if ($row['last_accessed_date'] === null) {
                $response['success'] = true;
                $response['changePassword'] = true;
                $response['redirect'] = 'view/change_password.php';
            } else {
                updateLastAccessedDate($conn, $_SESSION['id'], 'admins', 'admin');

                $response['success'] = true;
                $response['redirect'] = 'view/admin/students.php';
            }
        } else {
            $response['message'] = 'Login Failed. Please check your details.';
        }
    } else {
        $sql_student = "SELECT * FROM students WHERE username = ?";
        $stmt_student = mysqli_prepare($conn, $sql_student);
        mysqli_stmt_bind_param($stmt_student, "s", $username);
        mysqli_stmt_execute($stmt_student);
        $result_student = mysqli_stmt_get_result($stmt_student);

        if ($row = mysqli_fetch_assoc($result_student)) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['student_no'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['name'] = $row['student_name'];
                $_SESSION['account_type'] = "student";

                if ($row['last_accessed_date'] === null) {
                    $response['success'] = true;
                    $response['changePassword'] = true;
                } else {
                    updateLastAccessedDate($conn, $_SESSION['id'], 'students', 'student');

                    $response['success'] = true;
                    $response['redirect'] = 'view/student/profile.php';
                }
            } else {
                $response['message'] = 'Login Failed. Please check your details.';
            }
        } else {
            $response['message'] = 'Login Failed. Please check your details.';
        }
    }

    echo json_encode($response);
}

function updateLastAccessedDate($conn, $id, $userType, $user)
{
    $currentDateTime = date('Y-m-d H:i:s');
    $sql = "UPDATE $userType SET last_accessed_date = '$currentDateTime' WHERE {$user}_no = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}
