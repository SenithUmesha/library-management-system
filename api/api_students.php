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
        fetchStudent($conn);
        break;
    case 'fetch_all':
        fetchAllStudents($conn);
        break;
    case 'add':
        addStudent($conn);
        break;
    case 'update':
        updateStudent($conn);
        break;
    case 'delete':
        deleteStudent($conn);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or missing action']);
}

function fetchAllStudents(mysqli $conn): void
{
    $result = mysqli_query(
        $conn,
        'SELECT student_no, admission_id, username, email, class, student_name, last_accessed_date FROM students ORDER BY student_no DESC'
    );

    echo json_encode(['data' => $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : []]);
    mysqli_close($conn);
}

function fetchStudent(mysqli $conn): void
{
    $studentNo = filter_input(INPUT_POST, 'studentNo', FILTER_VALIDATE_INT);

    if (!$studentNo) {
        http_response_code(422);
        echo json_encode(['error' => 'Invalid student number']);
        return;
    }

    $stmt = mysqli_prepare(
        $conn,
        'SELECT student_no, admission_id, username, email, class, student_name, last_accessed_date FROM students WHERE student_no = ?'
    );
    mysqli_stmt_bind_param($stmt, 'i', $studentNo);
    mysqli_stmt_execute($stmt);
    $student = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    echo json_encode($student ?: ['error' => 'Student not found']);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function addStudent(mysqli $conn): void
{
    $name = trim($_POST['newStudentName'] ?? '');
    $admissionId = filter_var($_POST['newAdmissionId'] ?? null, FILTER_VALIDATE_INT);
    $username = trim($_POST['newUsername'] ?? '');
    $email = trim($_POST['newEmail'] ?? '');
    $class = trim($_POST['newClass'] ?? '');

    if ($name === '' || !$admissionId || $username === '' || $class === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode(['error' => 'Name, admission ID, username, class and a valid email are required']);
        return;
    }

    foreach ([['username', $username], ['email', $email], ['admission_id', (string) $admissionId]] as [$field, $value]) {
        if (studentValueExists($conn, $field, $value)) {
            $label = $field === 'admission_id' ? 'Admission ID' : ucfirst($field);
            echo json_encode(['error' => $label . ' already exists']);
            return;
        }
    }

    $temporaryPassword = (string) generateRandomSixDigitNumber();
    $hashedPassword = hashPassword($temporaryPassword);

    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO students (student_name, admission_id, username, email, class, password, last_accessed_date) VALUES (?, ?, ?, ?, ?, ?, NULL)'
    );
    mysqli_stmt_bind_param($stmt, 'sissss', $name, $admissionId, $username, $email, $class, $hashedPassword);

    if (!mysqli_stmt_execute($stmt)) {
        http_response_code(500);
        echo json_encode(['error' => 'Error adding new student']);
        mysqli_stmt_close($stmt);
        return;
    }

    $emailSent = sendWelcomeEmail($name, $username, $email, $temporaryPassword);
    echo json_encode([
        'message' => 'Student added successfully',
        'emailSent' => $emailSent,
    ]);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function updateStudent(mysqli $conn): void
{
    $studentNo = filter_input(INPUT_POST, 'studentNo', FILTER_VALIDATE_INT);
    $name = trim($_POST['updatedStudentName'] ?? '');
    $class = trim($_POST['updatedClass'] ?? '');

    if (!$studentNo || $name === '' || $class === '') {
        http_response_code(422);
        echo json_encode(['error' => 'Invalid student update']);
        return;
    }

    $stmt = mysqli_prepare($conn, 'UPDATE students SET student_name = ?, class = ? WHERE student_no = ?');
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $class, $studentNo);

    echo json_encode(
        mysqli_stmt_execute($stmt)
            ? ['message' => 'Student updated successfully']
            : ['error' => 'Error updating student']
    );

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function deleteStudent(mysqli $conn): void
{
    $studentNo = filter_input(INPUT_POST, 'studentNo', FILTER_VALIDATE_INT);

    if (!$studentNo) {
        http_response_code(422);
        echo json_encode(['error' => 'Invalid student number']);
        return;
    }

    $stmt = mysqli_prepare($conn, 'DELETE FROM students WHERE student_no = ?');
    mysqli_stmt_bind_param($stmt, 'i', $studentNo);
    mysqli_stmt_execute($stmt);

    echo json_encode(
        mysqli_stmt_affected_rows($stmt) > 0
            ? ['success' => 'Student deleted successfully']
            : ['error' => 'Student not found']
    );

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function studentValueExists(mysqli $conn, string $field, string $value): bool
{
    $allowedFields = ['username', 'email', 'admission_id'];
    if (!in_array($field, $allowedFields, true)) {
        return true;
    }

    $stmt = mysqli_prepare($conn, "SELECT 1 FROM students WHERE {$field} = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $value);
    mysqli_stmt_execute($stmt);
    $exists = mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;
    mysqli_stmt_close($stmt);

    return $exists;
}

function sendWelcomeEmail(string $studentName, string $username, string $studentEmail, string $password): bool
{
    $safeName = htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8');
    $safeUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
    $safePassword = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

    $html = <<<HTML
    <h2>Welcome to the Library Management System</h2>
    <p>Hi {$safeName},</p>
    <p>Your student account has been created.</p>
    <p><strong>Username:</strong> {$safeUsername}<br><strong>Temporary password:</strong> {$safePassword}</p>
    <p>Please change the temporary password after your first sign-in.</p>
    HTML;

    return sendLibraryEmail($studentEmail, 'Your library student account', $html);
}
