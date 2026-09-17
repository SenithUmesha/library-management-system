<?php
require '../includes/db_conn.php';

header('Content-Type: application/json; charset=utf-8');
session_start();

$action = $_POST['action'] ?? '';

if ($action !== 'submit') {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing action']);
    exit;
}

login($conn);

function login(mysqli $conn): void
{
    $response = ['success' => false, 'message' => 'Login failed. Please check your details.'];

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        http_response_code(422);
        echo json_encode($response);
        return;
    }

    $admin = findAccountByUsername($conn, 'admins', 'admin', $username);
    if ($admin && password_verify($password, $admin['password'])) {
        establishSession($admin['admin_no'], $admin['username'], $admin['admin_name'], 'admin');

        if ($admin['last_accessed_date'] === null) {
            $response = [
                'success' => true,
                'changePassword' => true,
                'redirect' => 'view/change_password.php',
            ];
        } else {
            updateLastAccessedDate($conn, (int) $admin['admin_no'], 'admins', 'admin_no');
            $response = [
                'success' => true,
                'redirect' => 'view/admin/students.php',
            ];
        }

        echo json_encode($response);
        return;
    }

    $student = findAccountByUsername($conn, 'students', 'student', $username);
    if ($student && password_verify($password, $student['password'])) {
        establishSession($student['student_no'], $student['username'], $student['student_name'], 'student');

        if ($student['last_accessed_date'] === null) {
            $response = [
                'success' => true,
                'changePassword' => true,
                'redirect' => 'view/change_password.php',
            ];
        } else {
            updateLastAccessedDate($conn, (int) $student['student_no'], 'students', 'student_no');
            $response = [
                'success' => true,
                'redirect' => 'view/student/profile.php',
            ];
        }
    }

    echo json_encode($response);
}

function findAccountByUsername(mysqli $conn, string $table, string $type, string $username): ?array
{
    $allowed = [
        'admins' => 'admin_no',
        'students' => 'student_no',
    ];

    if (!isset($allowed[$table])) {
        return null;
    }

    $stmt = mysqli_prepare($conn, "SELECT * FROM {$table} WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $account = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
    mysqli_stmt_close($stmt);

    return $account;
}

function establishSession(int $id, string $username, string $name, string $accountType): void
{
    session_regenerate_id(true);
    $_SESSION['id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['name'] = $name;
    $_SESSION['account_type'] = $accountType;
}

function updateLastAccessedDate(mysqli $conn, int $id, string $table, string $idColumn): void
{
    $allowed = [
        'admins' => 'admin_no',
        'students' => 'student_no',
    ];

    if (($allowed[$table] ?? null) !== $idColumn) {
        return;
    }

    $stmt = mysqli_prepare($conn, "UPDATE {$table} SET last_accessed_date = CURRENT_TIMESTAMP WHERE {$idColumn} = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
