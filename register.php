<?php
session_start();
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header.php";

$registration_success = false;

if (isset($_POST['submit'])) {
    // Retrieve data from POST request
    $matric_no = sanitize(trim($_POST['matric_no']));
    $password = sanitize(trim($_POST['password']));
    $username = sanitize(trim($_POST['username']));
    $email = sanitize(trim($_POST['email']));
    $dept = sanitize(trim($_POST['dept']));
    $numOfBooks = intval($_POST['numOfBooks']);
    $moneyOwed = sanitize(trim($_POST['moneyOwed']));
    $photo = ''; // Handle file upload for photo
    $phoneNumber = sanitize(trim($_POST['phoneNumber']));
    $name = sanitize(trim($_POST['name']));

    // Insert data into the students table
    $sql = "INSERT INTO students (matric_no, password, username, email, dept, numOfBooks, moneyOwed, photo, phoneNumber, name) 
            VALUES ('$matric_no', '$password', '$username', '$email', '$dept', $numOfBooks, '$moneyOwed', '$photo', '$phoneNumber', '$name')";

    if (mysqli_query($conn, $sql)) {

        $registration_success = true;
        // Redirect to a success page or perform any other actions
        header("Location: login_new.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mb-4">Register Student</h2>
        <form method="post">
            <div class="form-group">
                <label>Matric Number:</label>
                <input type="text" class="form-control" name="matric_no" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label>Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label>Department:</label>
                <input type="text" class="form-control" name="dept" required>
            </div>
            <div class="form-group">
                <label>Number of Books:</label>
                <input type="number" class="form-control" name="numOfBooks" required>
            </div>
            <div class="form-group">
                <label>Money Owed:</label>
                <input type="text" class="form-control" name="moneyOwed" required>
            </div>
            <!-- Photo upload field -->
            <div class="form-group">
                <label>Photo:</label>
                <input type="file" class="form-control-file" name="photo">
            </div>
            <div class="form-group">
                <label>Phone Number:</label>
                <input type="tel" class="form-control" name="phoneNumber" required>
            </div>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>


    <!-- Registration Success Modal -->
    <?php if ($registration_success) : ?>
        <div class="modal fade" id="registrationSuccessModal" tabindex="-1" role="dialog" aria-labelledby="registrationSuccessModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationSuccessModalLabel">Registration Successful</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Your registration was successful. You can now login using your credentials.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#registrationSuccessModal').modal('show');
            });
        </script>
    <?php endif; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>