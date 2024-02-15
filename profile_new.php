<?php 

require 'includes/db-inc.php';
session_start();
$student_name = $_SESSION['student-username'];

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            margin-top: 60px;
        }
        .card {
            border: none;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            border-radius: 5px 5px 0 0;
        }
        .card-title {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .card-body {
            padding: 30px;
        }
        table {
            width: 100%;
        }
        th {
            width: 30%;
            text-align: left;
        }
        td {
            width: 70%;
            text-align: left;
        }
        th, td {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .name-highlight {
            color: #dc3545; /* Change this to the desired color */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
    <?php include "includes/nav2.php"; ?>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Student Profile</h2>
            </div>
            <div class="card-body">
                <table class="table">
                    <?php 
                    $sql = "SELECT * from students where username = '$student_name'";
                    $query = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($query)) { ?>
                        <tbody> 
                            <tr> 
                                <th class="name-highlight">Name</th>
                                <td><?php echo $row['name']; ?></td>
                            </tr> 
                            <tr> 
                                <th>Matric No</th>
                                <td><?php echo $row['matric_no']; ?></td>
                            </tr> 
                            <tr> 
                                <th>Email</th>
                                <td><?php echo $row['email']; ?></td>
                            </tr>
                            <tr> 
                                <th>Department</th>
                                <td><?php echo $row['dept']; ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td><?php echo $row['phoneNumber']; ?></td>
                            </tr> 
                            <tr>
                                <th>Username</th>
                                <td><?php echo $row['username']; ?></td>
                            </tr> 
                            <tr>
                                <th>Password</th>
                                <td><?php echo $row['password']; ?></td>
                            </tr>  
                        </tbody> 
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
