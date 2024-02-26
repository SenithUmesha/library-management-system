<?php
require  __DIR__ . '../../util/snippet.php';
require  __DIR__ . '../../util/db_conn.php';

session_start();

if (isset($_POST['submit'])) {
    $username = sanitize(trim($_POST['username']));
    $password = sanitize(trim($_POST['password']));

    // Admin Login
    $sql_admin = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt_admin = mysqli_prepare($conn, $sql_admin);
    mysqli_stmt_bind_param($stmt_admin, "ss", $username, $password);
    mysqli_stmt_execute($stmt_admin);
    $result_admin = mysqli_stmt_get_result($stmt_admin);

    if (mysqli_num_rows($result_admin) > 0) {
        $row = mysqli_fetch_assoc($result_admin);
        $_SESSION['username'] = $row['username'];
        $_SESSION['name'] = $row['admin_name'];
        $_SESSION['account_type'] = "admin";
        header("Location: admin/students.php");
        exit();
    } else {
        // Student Login
        $sql_student = "SELECT * FROM students WHERE username = ? AND password = ?";
        $stmt_student = mysqli_prepare($conn, $sql_student);
        mysqli_stmt_bind_param($stmt_student, "ss", $username, $password);
        mysqli_stmt_execute($stmt_student);
        $result_student = mysqli_stmt_get_result($stmt_student);

        if ($row = mysqli_fetch_assoc($result_student)) {
            $_SESSION['id'] = $row['student_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['name'] = $row['student_name'];
            $_SESSION['account_type'] = "student";
            header("Location: student/profile.php");
            exit();
        } else {
            echo '<script>alert("Login Failed. Please check your details.")</script>';
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
            <form role="form" method="post" action="view/login.php" enctype="multipart/form-data">
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
</body>

</html>