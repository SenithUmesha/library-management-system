<?php
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header_new.php";

if (isset($_POST['submit'])) {
    $username = sanitize(trim($_POST['username']));
    $password = sanitize(trim($_POST['password']));

    $sql_admin = "SELECT * from admin where username = '$username' and  password = '$password' ";
    $query = mysqli_query($conn, $sql_admin);

    if (mysqli_num_rows($query) > 0) {

        while ($row = mysqli_fetch_assoc($query)) {
            $_SESSION['auth'] = true;
            $_SESSION['admin'] = $row['username'];
        }
        if ($_SESSION['auth'] === true) {
            header("Location: viewstudents_new.php");
            exit();
        }
    } else {
        $sql_stud = "SELECT * from students where username='$username' and password = '$password'";
        $query = mysqli_query($conn, $sql_stud);
        $row = mysqli_fetch_assoc($query);
        if ($row['username'] == $username && $row['password'] == $password) {
            $_SESSION['student-username'] = $row['username']; // Set the 'student-username' session variable
            $_SESSION['student-name'] = $row['name'];
            $_SESSION['student-matric'] = $row['matric_no'];
            header("Location:reserved_books.php");
        } else {
            echo "<div class='alert alert-danger alert-dismissable'>
                        <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                        <strong style='text-align: center'> Login Failed.  Please check your details.</strong>
                  </div>";
        }
        
    }
}
?>

<style>
    body {
        padding: 0;
        margin: 0;
        width: 100%;
    }
</style>

<body>
    <div class="container" style="width: fit-content; padding-top: 80px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
            <h4 style=" font-weight: bold;">| Login</h4>
        </div>
        <div style="margin-top:30px">
            <form role="form" method="post" action="login_new.php" enctype="multipart/form-data">
                <div class="form-outline mb-4">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" id="username" required class="form-control" />

                </div>
                <div class="form-outline mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" required class="form-control" />
                </div>
                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-primary btn-block mb-4">Login</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

</body>

</html>