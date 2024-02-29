<?php
require '../includes/db_conn.php';
require '../util/functions.php';

require_once '../vendor/autoload.php';

 

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
    $sql = "SELECT * FROM fines";
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
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "SELECT * FROM fines WHERE id = '$id'";
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
    $newBookName = $_POST['newBookName'];
    $newBorrowdate = $_POST['newBorrowdate'];
    $newReturndate = $_POST['newReturndate'];
    $newoverduecharge = $_POST['newoverduecharge'];

    // Omitting the 'id' column since it's auto-increment
    $sql = "INSERT INTO fines (student_name, book_Name, borrow_date, return_date, overdue_charge) 
            VALUES ('$newStudentName', '$newBookName', '$newBorrowdate', '$newReturndate', '$newoverduecharge')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['message' => 'Fine added successfully']);
    } else {
        echo json_encode(['error' => 'Error adding new fine: ' . mysqli_error($conn)]);
    }

    mysqli_close($conn);
}



function updateStudent($conn)
{
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $editStudentName = $_POST['updatedStudentName'];
        $editBookName = $_POST['updatedBookName'];
        $editOverduecharge = $_POST['updatedOverdueCharge']; // Fixed variable name

        $sql = "UPDATE fines SET student_name = '$editStudentName', book_Name = '$editBookName', overdue_charge = '$editOverduecharge' WHERE id = '$id'";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(['message' => 'Fine updated successfully']);
        } else {
            echo json_encode(['error' => 'Error updating fine']);
        }

        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}

function deleteStudent($conn)
{
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
         
        $sql = "SELECT * FROM fines WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $deleteSql = "DELETE FROM fines WHERE id = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteSql);
            mysqli_stmt_bind_param($deleteStmt, 'i', $id);

            if (mysqli_stmt_execute($deleteStmt)) {
                echo json_encode(['success' => 'Fines deleted successfully']);
            } else {
                echo json_encode(['error' => 'Error deleting Fine']);
            }

            mysqli_stmt_close($deleteStmt);
        } else {
            echo json_encode(['error' => 'Fine not found']);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
}


