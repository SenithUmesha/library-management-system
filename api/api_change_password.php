<?php

require '../includes/db_conn.php';
require '../util/functions.php';

header('Content-Type: application/json; charset=utf-8');
session_start();

if (!isset($_SESSION['id'], $_SESSION['account_type']) || !in_array($_SESSION['account_type'], ['admin', 'student'], true)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Please sign in first']);
    exit;
}

if (($_POST['action'] ?? '') !== 'changePassword') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid or missing action']);
    exit;
}

$newPassword = $_POST['newPassword'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

if ($newPassword !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit;
}

if (strlen($newPassword) < 8) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
    exit;
}

$accountType = $_SESSION['account_type'];
$userId = (int) $_SESSION['id'];
$table = $accountType === 'admin' ? 'admins' : 'students';
$idColumn = $accountType === 'admin' ? 'admin_no' : 'student_no';
$hashedPassword = hashPassword($newPassword);

$stmt = mysqli_prepare(
    $conn,
    "UPDATE {$table} SET password = ?, last_accessed_date = CURRENT_TIMESTAMP WHERE {$idColumn} = ?"
);
mysqli_stmt_bind_param($stmt, 'si', $hashedPassword, $userId);
$success = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (!$success) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error changing password']);
    exit;
}

session_regenerate_id(true);

echo json_encode([
    'success' => true,
    'message' => 'Password changed successfully',
    'redirect' => $accountType === 'admin' ? 'view/admin/students.php' : 'view/student/profile.php',
]);
