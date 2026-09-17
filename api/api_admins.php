<?php

require '../includes/db_conn.php';
require '../util/functions.php';
require_once '../vendor/autoload.php';
require_once '../includes/mailer.php';

header('Content-Type: application/json; charset=utf-8');
session_start();

if (($_SESSION['account_type'] ?? null) !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Admin access required']);
    exit;
}

$action = $_POST['action'] ?? '';

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
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or missing action']);
}

function fetchAllAdmins(mysqli $conn): void
{
    $result = mysqli_query(
        $conn,
        'SELECT admin_no, admin_name, username, email, last_accessed_date FROM admins ORDER BY admin_no DESC'
    );

    echo json_encode(['data' => $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : []]);
    mysqli_close($conn);
}

function fetchAdmin(mysqli $conn): void
{
    $adminNo = filter_input(INPUT_POST, 'adminNo', FILTER_VALIDATE_INT);

    if (!$adminNo) {
        http_response_code(422);
        echo json_encode(['error' => 'Invalid admin number']);
        return;
    }

    $stmt = mysqli_prepare(
        $conn,
        'SELECT admin_no, admin_name, username, email, last_accessed_date FROM admins WHERE admin_no = ?'
    );
    mysqli_stmt_bind_param($stmt, 'i', $adminNo);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);

    echo json_encode($admin ?: ['error' => 'Admin not found']);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function addAdmin(mysqli $conn): void
{
    $name = trim($_POST['newAdminName'] ?? '');
    $username = trim($_POST['newUsername'] ?? '');
    $email = trim($_POST['newEmail'] ?? '');

    if ($name === '' || $username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode(['error' => 'Name, username and a valid email are required']);
        return;
    }

    if (adminValueExists($conn, 'username', $username)) {
        echo json_encode(['error' => 'Username already exists']);
        return;
    }

    if (adminValueExists($conn, 'email', $email)) {
        echo json_encode(['error' => 'Email already exists']);
        return;
    }

    $temporaryPassword = (string) generateRandomSixDigitNumber();
    $hashedPassword = hashPassword($temporaryPassword);

    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO admins (admin_name, username, email, password, last_accessed_date) VALUES (?, ?, ?, ?, NULL)'
    );
    mysqli_stmt_bind_param($stmt, 'ssss', $name, $username, $email, $hashedPassword);

    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        echo json_encode(['error' => 'Error adding new admin']);
        mysqli_stmt_close($stmt);
        return;
    }

    $emailSent = sendWelcomeEmail($name, $username, $email, $temporaryPassword);
    echo json_encode([
        'message' => 'Admin added successfully',
        'emailSent' => $emailSent,
    ]);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function updateAdmin(mysqli $conn): void
{
    $adminNo = filter_input(INPUT_POST, 'adminNo', FILTER_VALIDATE_INT);
    $name = trim($_POST['updatedAdminName'] ?? '');

    if (!$adminNo || $name === '') {
        http_response_code(422);
        echo json_encode(['error' => 'Invalid admin update']);
        return;
    }

    $stmt = mysqli_prepare($conn, 'UPDATE admins SET admin_name = ? WHERE admin_no = ?');
    mysqli_stmt_bind_param($stmt, 'si', $name, $adminNo);

    echo json_encode(
        mysqli_stmt_execute($stmt)
            ? ['message' => 'Admin updated successfully']
            : ['error' => 'Error updating admin']
    );

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function deleteAdmin(mysqli $conn): void
{
    $adminNo = filter_input(INPUT_POST, 'adminNo', FILTER_VALIDATE_INT);

    if (!$adminNo) {
        http_response_code(422);
        echo json_encode(['error' => 'Invalid admin number']);
        return;
    }

    if ((int) ($_SESSION['id'] ?? 0) === $adminNo) {
        http_response_code(422);
        echo json_encode(['error' => 'You cannot delete the account you are currently using']);
        return;
    }

    $stmt = mysqli_prepare($conn, 'DELETE FROM admins WHERE admin_no = ?');
    mysqli_stmt_bind_param($stmt, 'i', $adminNo);
    mysqli_stmt_execute($stmt);

    echo json_encode(
        mysqli_stmt_affected_rows($stmt) > 0
            ? ['success' => 'Admin deleted successfully']
            : ['error' => 'Admin not found']
    );

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function adminValueExists(mysqli $conn, string $field, string $value): bool
{
    $allowedFields = ['username', 'email'];
    if (!in_array($field, $allowedFields, true)) {
        return true;
    }

    $stmt = mysqli_prepare($conn, "SELECT 1 FROM admins WHERE {$field} = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $value);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $exists = mysqli_num_rows($result) > 0;
    mysqli_stmt_close($stmt);

    return $exists;
}

function sendWelcomeEmail(string $adminName, string $username, string $adminEmail, string $password): bool
{
    $safeName = htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8');
    $safeUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $safePassword = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

    $html = <<<HTML
    <h2>Welcome to the Library Management System</h2>
    <p>Hi {$safeName},</p>
    <p>Your admin account has been created.</p>
    <p><strong>Username:</strong> {$safeUsername}<br><strong>Temporary password:</strong> {$safePassword}</p>
    <p>Please change the temporary password after your first sign-in.</p>
    HTML;

    return sendLibraryEmail($adminEmail, 'Your library admin account', $html);
}
