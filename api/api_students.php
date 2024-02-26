<?php
require '../includes/db_conn.php';
require '../util/functions.php';

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

    $randomNumber = generateRandomSixDigitNumber();
    $hashedPassword = hashPassword($randomNumber);

    $newStudentName = $_POST['newStudentName'];
    $newAdmissionId = $_POST['newAdmissionId'];
    $newUsername = $_POST['newUsername'];
    $newEmail = $_POST['newEmail'];
    $newClass = $_POST['newClass'];

    $sql = "INSERT INTO students (student_name, admission_id, username, email, class, password) 
            VALUES ('$newStudentName', '$newAdmissionId', '$newUsername', '$newEmail', '$newClass', '$hashedPassword')";

    if (mysqli_query($conn, $sql)) {
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
        $editAdmissionId = $_POST['updatedAdmissionId'];
        $editUsername = $_POST['updatedUsername'];
        $editEmail = $_POST['updatedEmail'];
        $editClass = $_POST['updatedClass'];

        $sql = "UPDATE students SET student_name = '$editStudentName', admission_id = '$editAdmissionId', 
                username = '$editUsername', email = '$editEmail', class = '$editClass' WHERE student_no = '$studentNo'";

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
