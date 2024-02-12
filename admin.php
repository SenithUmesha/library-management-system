<?php
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header.php";

if (isset($_POST['submit'])) {
    $news = sanitize(trim($_POST['news']));
    $sql = "INSERT into news (announcement) values ('$news')";
    $query = mysqli_query($conn, $sql);
    if ($query) {
        // Redirect to the same page to prevent duplicate form submission
        header("Location: admin.php");
        exit(); // Make sure to exit after redirecting
    } else {
        echo "<script>alert('Not successful!! Try again.');</script>";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="flickity/flickity.css">
    <link rel="stylesheet" type="text/css" href="css/sweetalert.css">
    <script type="text/javascript" src="flickity/flickity.js"></script>
    <script type="text/javascript" src="sweetalert.min.js"></script>   
    <title>Library Management</title>
    <style>
		@font-face {
            font-family: 'Protest Revolution';
            src: url('fonts/ProtestRevolution-Regular.ttf') format('truetype');
        }
        body {
            background-image: url('images/notice3.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            padding: 0;
            margin: 0;
			width:100%;
        }
        .announcement-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding: 20px;
            margin-top: 20px;
        }
        .announcement {
            position: relative; /* Added position relative */
            flex: 0 0 auto;
            width: 600px;
            height: 400px;
            background-image: url('images/bullet.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            margin-right: 20px;
            padding: 10px;
            color: white;
            font-family: 'Protest Revolution', sans-serif;
        }
        .announcement h3 {
            text-align: center;
        }
        .announcement .delete-btn {
            position: absolute;
            top: 380px;
            right: 300px;
        }
        .add-announcement {
            margin-top: 20px;
            text-align: center;
        }
		.text-center{

			font-family: 'Protest Revolution', sans-serif;
			color:white;
			font-size: 60px; 
		
		

		}

		h1.text-center {
        margin-top: 80px; /* Adjust margin to create space between navbar and text */
        color: white; /* Ensure text color is visible against the background */
        font-family: 'Protest Revolution', sans-serif; /* Apply font family */
		font-size: 60px; 
		
    }

    </style>
</head>

<body>

    <div class="container-fluid">
	

	<?php include "includes/nav.php"; ?>
	<h1 class="text-center">Welcome !</h1>
      
	
    </div>
	
    <div class="announcement-container">
        <?php
  
		
        if(isset($_POST['submit'])){
            $news = sanitize(trim($_POST['news']));
            $sql = "INSERT into news (announcement) values ('$news')";
            $query = mysqli_query($conn,$sql);
            $error = false;
            if($query){
                $error = true;
            }
            else{
                echo "<script>alert('Not successful!! Try again.');</script>";
            }
        }

        if(isset($_POST['UpDat'])){
            $id = sanitize(trim($_POST['id']));
            $text = sanitize(trim($_POST['text']));
            $sql_up = "UPDATE news set announcement = '$text' where newsId = '$id'";
            echo mysqli_error($sql_up);
            $result = mysqli_query($conn,$sql_del);
            if ($result) {
                echo "<script>alert('Update successful');</script>";
            }
        }

        if(isset($_POST['del'])){
            $id = sanitize(trim($_POST['id']));
            $sql_del = "DELETE from news where newsId = $id";
            $result = mysqli_query($conn,$sql_del);
            if ($result) {
                //echo "<script>alert('User was successfully deleted from the database');</script>";
            }
        }

        $sql2 = "SELECT * FROM news";
        $query2 = mysqli_query($conn, $sql2);
        while ($row = mysqli_fetch_array($query2)) { ?>
            <div class="announcement">
                <h3><?php echo $row['announcement']; ?></h3>
                <form method='post' action='admin.php' class="delete-form">
                    <input type='hidden' value="<?php echo $row['newsId']; ?>" name='id'>
                    <button type='submit' name='del' class='btn btn-danger delete-btn'>Delete</button>
                </form>
            </div>
        <?php } ?>
    </div>

    <div class="add-announcement">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addAnnouncementModal">Add Announcement</button>
    </div>

    <!-- Add Announcement Modal -->
    <div class="modal fade" id="addAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAnnouncementModalLabel">Add Announcement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="admin.php" method="post">
                        <div class="form-group">
                            <label for="announcementText">Announcement Text</label>
                            <textarea class="form-control" id="announcementText" name="news" rows="3"></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
</body>
</html>
