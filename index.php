<?php
// session_start();
// session_destroy();
// if (!(isset($_SESSION['auth']) && $_SESSION['auth'] === true)) {
// 	header("Location: admin.php?access=false");
// 	exit();
// }
// else {
// $admin = $_SESSION['admin'];
// }
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header.php";

// if(isset($_SESSION['admin'])){
// 	$admin = $_SESSION['admin'];
// 	// echo "Hello $user";
// }

if (isset($_POST['submit'])) {

  $news = sanitize(trim($_POST['news']));

  $sql = "INSERT into news (announcement) values ('$news')";

  $query = mysqli_query($conn, $sql);
  $error = false;

  if ($query) {
    $error = true;
  } else {
    echo "<script>alert('Not successful!! Try again.');
                    </script>";
  }
}

if (isset($_POST['UpDat'])) {
  $id = sanitize(trim($_POST['id']));
  $text = sanitize(trim($_POST['text']));

  $sql_up = "UPDATE news set announcement = '$text' where newsId = '$id'";
  $result = mysqli_query($conn, $sql_del);
  if ($result) {
    echo "<script>


                   alert('Update successful');

         </script>";
  }
}

if (isset($_POST['del'])) {

  $id = sanitize(trim($_POST['id']));

  $sql_del = "DELETE from news where newsId = $id";

  $result = mysqli_query($conn, $sql_del);
  if ($result) {
    //            echo "<script>

    //    var response = confirm('Would you like to delete the user');
    //    if (response == true) {
    //        alert('User was successfully deleted from the database');
    //            location.href ='admin.php';
    //    }

    //    else
    //        {
    //            alert('Could not delete user');
    //        }


    // </script>";
  }
}






?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">

  <link rel="stylesheet" type="text/css" href="flickity/flickity.css">
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
  <script type="text/javascript" src="flickity/flickity.js"></script>

  <title>Library Management</title>

</head>
<style>
  @font-face {
    font-family: Nunito;
    src: url(NunitoSans.ttf);
  }
  body{

    overflow:hidden;
  }

.carousel-cell {
      width: 100%; /* Ensure each cell takes up the full width */
      height: 100vh; /* Set a fixed height for the carousel cells */
      margin-right: 15px; /* Add some margin between cells */
    }

    .carousel-cell img {
      width: 100%; /* Ensure images fill the entire cell */
      height: 100%; /* Ensure images fill the entire cell */
      object-fit: cover; /* Prevent images from stretching */
    }

.carousel-container {
      height: 100vh; /* Set carousel height to fill the full height of the viewport */
    }


.slide_title_container {
    position: relative; /* Change position to relative */
    height: 100vh; /* Set the height to fill the viewport */
    justify-self: center;
    justify-items:center;
}

.title_lib {
    position: absolute;
   
    transform: translate(-50%, -50%); /* Center the title horizontally */
    font-size: 100px;
    color: white;
    z-index: 1000;
    text-align: center;
    font-family: Nunito;
    line-height: 1.7; /* Increase line height */
    text-transform: uppercase;
    text-shadow: 
        -2px -2px 0 #000,  
        2px -2px 0 #000,
        -2px 2px 0 #000,
        2px 2px 0 #000;
    animation: slideInLeft 1s forwards
}

@keyframes slideInLeft {
    from {
        transform: translateX(-100%);
    }
    to {
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}




</style>
<body>
  <div class="container">
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example">
            <span class="sr-only">:</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Library Management System</a>
        </div>
  
        <div class="collapse navbar-collapse" id="bs-example">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>

          </ul>
          <ul class="nav navbar-nav">
            <li><a href="login_new.php">Login</a></li>
          </ul>
        </div>
      </div>
    </nav>

  </div>

  <div class=slide_title_container>
  <h1 class="title_lib"><span>Welcome</br> to</span></br>
    <span>Library Management System</span></h1>
  <div>
   

    <div class="slider">
      <!-- <h1>Flickity - wrapAround</h1> -->

      <div class="carousel-container" id="carouselContainer">
      <div class="carousel" data-flickity='{ "autoPlay": true }' ;>

        <div class="carousel-cell" auto-play>
          <img src="images/2.jpg">
        </div>
        <div class="carousel-cell" auto-play>
          <img src="images/3.jpg">
        </div>

      </div>
    </div>


    </div>
  </div>

</div>


  </div>


  </div>
  </div>
  </div>



  <footer>
 
  </footer>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

<script>

  </script>
</html>