<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Library Management</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="flickity/flickity.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example">
                <span class="sr-only">Toggle navigation</span>
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
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->

<!-- Slider -->
<div class="container slide">
    <div class="slider">
        <div class="carousel" data-flickity='{ "autoPlay": true }'>
            <div class="carousel-cell"><img src="images/2.jpg"></div>
            <div class="carousel-cell"><img src="images/3.jpg"></div>
        </div>
    </div>
</div>
<!-- End Slider -->

<!-- Announcements -->
<div class="container slide2">
    <div class="panel-heading">
        <div class="row">
            <h3 class="center-block">Announcements</h3>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SN</th>
                <th>Announcement</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql2 = "SELECT * from news";
            $query2 = mysqli_query($conn, $sql2);
            $counter = 1;
            while ($row = mysqli_fetch_array($query2)) {
            ?>
            <tr>
                <td><?php echo $counter++; ?></td>
                <td><?php echo $row['announcement']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<!-- End Announcements -->

<!-- Image Gallery -->
<div class="container-fluid slide3">
    <div class="container">
        <div class="row">
            <!-- Your image gallery code goes here -->
        </div>
    </div>
</div>
<!-- End Image Gallery -->

<footer>
    <p class="text-center">Library Management System</p>
</footer>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="flickity/flickity.js"></script>
</body>
</html>
