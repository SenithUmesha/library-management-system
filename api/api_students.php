<?php
require '../includes/db_conn.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            addStudent();
            break;
        case 'edit':
            editStudent($conn);
            break;
        case 'delete':
            deleteStudent();
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['error' => 'Action not specified']);
}

function addStudent()
{
    echo json_encode(['message' => 'Student added successfully']);
}

function editStudent($conn)
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

function deleteStudent()
{
    echo json_encode(['message' => 'Student deleted successfully']);
}
