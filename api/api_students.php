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
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function fetchAllStudents($conn)
{
    $sql = "SELECT * FROM students";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $studentsData = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $studentsData[] = $row;
        }

        echo json_encode(['data' => $studentsData]);
    } else {
        echo json_encode(['data' => []]);
    }

    mysqli_close($conn);
}

function fetchStudent($conn)
{
    if (isset($_POST['studentNo'])) {
        $studentNo = $_POST['studentNo'];
        $sql = "SELECT * FROM students WHERE student_no = '$studentNo'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $studentData = mysqli_fetch_assoc($result);
            echo json_encode($studentData);
        } else {
            echo json_encode(['error' => 'Student not found']);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }

    mysqli_close($conn);
}

function addStudent($conn)
{
    $newStudentName = $_POST['newStudentName'];
    $newAdmissionId = $_POST['newAdmissionId'];
    $newUsername = $_POST['newUsername'];
    $newEmail = $_POST['newEmail'];
    $newClass = $_POST['newClass'];

    $checkUsernameQuery = "SELECT * FROM students WHERE username = '$newUsername'";
    $resultUsername = mysqli_query($conn, $checkUsernameQuery);

    $checkEmailQuery = "SELECT * FROM students WHERE email = '$newEmail'";
    $resultEmail = mysqli_query($conn, $checkEmailQuery);

    $checkAdmissionIdQuery = "SELECT * FROM students WHERE admission_id = '$newAdmissionId'";
    $resultAdmissionId = mysqli_query($conn, $checkAdmissionIdQuery);

    if (mysqli_num_rows($resultUsername) > 0) {
        echo json_encode(['error' => 'Username already exists']);
        return;
    }

    if (mysqli_num_rows($resultEmail) > 0) {
        echo json_encode(['error' => 'Email already exists']);
        return;
    }

    if (mysqli_num_rows($resultAdmissionId) > 0) {
        echo json_encode(['error' => 'Admission ID already exists']);
        return;
    }

    $randomNumber = generateRandomSixDigitNumber();
    $hashedPassword = hashPassword($randomNumber);

    $sql = "INSERT INTO students (student_name, admission_id, username, email, class, password) 
            VALUES ('$newStudentName', '$newAdmissionId', '$newUsername', '$newEmail', '$newClass', '$hashedPassword')";

    if (mysqli_query($conn, $sql)) {
        sendWelcomeEmail($newStudentName, $newUsername, $newEmail, $randomNumber);
        echo json_encode(['message' => 'Student added successfully']);
    } else {
        echo json_encode(['error' => 'Error adding new student']);
    }

    mysqli_close($conn);
}

function updateStudent($conn)
{
    if (isset($_POST['studentNo'])) {
        $studentNo = $_POST['studentNo'];
        $editStudentName = $_POST['updatedStudentName'];
        $editClass = $_POST['updatedClass'];

        $sql = "UPDATE students SET student_name = '$editStudentName', class = '$editClass' WHERE student_no = '$studentNo'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(['message' => 'Student updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating student']);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function deleteStudent($conn)
{
    if (isset($_POST['studentNo'])) {
        $studentNo = $_POST['studentNo'];
        $sql = "SELECT * FROM students WHERE student_no = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $studentNo);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $deleteSql = "DELETE FROM students WHERE student_no = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($deleteStmt, 'i', $studentNo);

            if (mysqli_stmt_execute($deleteStmt)) {
                echo json_encode(['success' => 'Student deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error deleting student']);
            }

            mysqli_stmt_close($deleteStmt);
        } else {
            echo json_encode(['error' => 'Student not found']);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function sendWelcomeEmail($studentName, $username, $studentEmail, $password)
{
    $transport = Transport::fromDsn('smtp://34senith@gmail.com:osfiefvsuqxjgmhv@smtp.gmail.com:587');

    $mailer = new Mailer($transport);

    $email = (new Email());

    $email->from('34senith@gmail.com');

    $email->to('' . $studentEmail);

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
    <p style="color: #555;">Dear ' . $studentName . ',</p>
    <p style="color: #555;">We are delighted to welcome you to Ananda College Library System! Your student account has been successfully created, and we are excited to have you as a member of our library community.</p>
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
