<?php 
session_start();
// session_destroy();
// if (!(isset($_SESSION['auth']) && $_SESSION['auth'] === true)) {
// 	header("Location: admin.php?access=false");
// 	exit();
// }
// else {
// 
// }
if (isset($_SESSION['admin'])) {
     $admin = $_SESSION['admin'];
}

if (isset($_SESSION['student-name'])) {
     $student = $_SESSION['student-name'];
   
}
?>



<style>
    /* Custom styles for the navbar */
    .navbar {
        background-color: #333; /* Navbar background color */
        color: #fff; /* Navbar text color */
        border-radius: 0; /* Remove border-radius */
        width: 100%; /* Set width to 100% */
        margin: 0; /* Remove any margin */
        padding: 0; /* Remove any padding */
        position: fixed;
        top: 0; /* Align the navbar to the top */
        left: 0; /* Align the navbar to the left */
        z-index: 1;
    }

    .navbar-brand {
        color: #fff; /* Navbar brand text color */
    }

    .navbar-toggle {
        border: none; /* Remove border */
        margin-right: 15px; /* Add some margin for spacing */
    }

    .navbar-toggle .icon-bar {
        background-color: #fff; /* Navbar toggle icon color */
    }

    .navbar-nav > li > a {
        color: #fff; /* Navbar item text color */
        transition: color 0.3s; /* Smooth transition effect for color change */
    }

    .navbar-nav > li.active > a,
    .navbar-nav > li > a:active {
        background-color: #000; /* Background color for the active item and active states */
        padding: 15px 20px; /* Adjust padding */
    }

    .navbar-nav > li.active > a:hover {
        background-color: #000; /* Background color for the active item on hover */
    }

    .navbar-nav {
        padding-left: 0; /* Add padding to the left */
        margin-top: 15px;
    }

    .navbar-nav > li {
        margin-right: 20px; /* Add margin between navbar items */
        margin-left:40px;
    }

    .navbar-nav > li > a:hover {
        background:#000;
        padding: 15px 20px;
        text-decoration: none;
    }
</style>


<body>
<nav class="navbar" style="background-color: #333; color: #fff;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" onclick="toggleNavbar()">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar" style="background-color: #fff;"></span>
                <span class="icon-bar" style="background-color: #fff;"></span>
                <span class="icon-bar" style="background-color: #fff;"></span>
            </button>
            <a class="navbar-brand" href="#" style="color: #fff;">Library Management System</a>
        </div>

        <div class="collapse navbar-collapse collapsed" id="bs-example">

            <ul class="navbar-nav" style="list-style: none; padding-left: 0;">
                <?php if(isset($admin)) { ?>  
                    <li class="nav-item <?php echo $current_page == 'admin.php' ? 'active' : ''; ?>"><a href="admin.php" style="color: #fff;">Home</a></li>
                    <li class="nav-item <?php echo $current_page == 'bookstable.php' ? 'active' : ''; ?>"><a href="bookstable.php" style="color: #fff;">Books</a></li>
                    <li class="nav-item <?php echo $current_page == 'users.php' ? 'active' : ''; ?>"><a href="users.php" style="color: #fff;">Admins</a></li>
                    <li class="nav-item <?php echo $current_page == 'viewstudents.php' ? 'active' : ''; ?>"><a href="viewstudents.php" style="color: #fff;">Students</a></li>
                    <li class="nav-item <?php echo $current_page == 'borrowedbooks.php' ? 'active' : ''; ?>"><a href="borrowedbooks.php" style="color: #fff;">Borrow Books</a></li>
                    <li class="nav-item <?php echo $current_page == 'fines.php' ? 'active' : ''; ?>"><a href="fines.php" style="color: #fff;">Fines</a></li>
                    <!-- Add more navbar items with onclick attribute -->
                <?php } ?>
                <?php if(isset($student)) { ?>
                    <li class="nav-item active"><a href="studentportal.php" onclick="highlightNavItem(this)" style="color: #fff;">Home</a></li>
                    <li class="nav-item"><a href="profile.php" onclick="highlightNavItem(this)" style="color: #fff;">View Profile</a></li>
                    <li class="nav-item"><a href="borrow-student.php" onclick="highlightNavItem(this)" style="color: #fff;">Borrow Books</a></li>
                    <li class="nav-item"><a href="fine-student.php" onclick="highlightNavItem(this)" style="color: #fff;">Fines</a></li>
                    <!-- Add more navbar items with onclick attribute -->
                <?php } ?>
            </ul>
            <ul class="navbar-nav navbar-right" style="list-style: none; padding-left: 0;">
                <li><a href="logout.php" style="color: #fff;">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<script>
    function highlightNavItem(item) {
        // Remove 'active' class from all navbar items
        var navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(function(navItem) {
            navItem.classList.remove('active');
            navItem.firstElementChild.style.backgroundColor = ""; // Reset background color for all items
        });

        // Add 'active' class to the clicked navbar item
        item.parentNode.classList.add('active');

        // Change background color of the clicked navbar item
        item.style.backgroundColor = "#000";
    }

    // Initially highlight the "Home" navbar item
    document.addEventListener("DOMContentLoaded", function() {
        var homeNavItem = document.querySelector('.nav-item.active');
        if (homeNavItem) {
            homeNavItem.firstElementChild.style.backgroundColor = "#000";
        }
    });
</script>



</body>


