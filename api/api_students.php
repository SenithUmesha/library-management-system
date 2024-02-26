<?php
require '../includes/db_conn.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'fetch':
            fetchStudent($conn);
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
}

function addStudent($conn)
{
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
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function deleteStudent($conn)
{
}
