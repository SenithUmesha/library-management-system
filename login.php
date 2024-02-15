<?php
session_start();

// if ((isset($_SESSION['auth']) && $_SESSION['auth'] === true)) {
// 	header("Location: admin.php");
// 	exit();
// }

// 	if (isset($_GET['access'])) {
// 		$alert_user = true;
// 	}

require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header.php";

// Error check

// 					echo"<br>";
// 					echo mysqli_errno($conn);

if(isset($_POST['submit'])){
	$username = sanitize(trim($_POST['username']));
	$password = sanitize(trim($_POST['password']));

	$sql_admin = "SELECT * from admin where username = '$username' and  password = '$password' ";
	$query = mysqli_query($conn, $sql_admin);
	// echo mysqli_error($conn);
	if(mysqli_num_rows($query) > 0){

				while($row = mysqli_fetch_assoc($query)){
					$_SESSION['auth'] = true;
					$_SESSION['admin'] = $row['username'];
					}
					if ($_SESSION['auth'] === true) {
				header("Location: admin.php");
				exit();
					}
	}

		else{
			$sql_stud = "SELECT * from students where username='$username' and password = '$password'";
				$query = mysqli_query($conn, $sql_stud);
				$row = mysqli_fetch_assoc($query);
				if($row['username'] == $username && $row['password'] == $password){
					$_SESSION['student-username'] = $row['username'];
					$_SESSION['student-name'] = $row['name'];
					$_SESSION['student-matric'] = $row['matric_no'];
						header("Location:profile_new.php");
					}
					else {
						echo"<div class='alert alert-danger alert-dismissable'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
						<strong style='text-align: center'> Login Failed.  Please check your details.</strong>
				  </div>";
					}




			}


}


?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
	
body {
            background-image: url('images/notice3.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            padding: 0;

            margin: 0;
			width:100%;
         
        }

.container {
	margin-top: 20px;
	position: relative;
	width: 100%;
	max-width: 500px;
}

.container form {
	background-color: #fff;
	padding: 20px;
	border-radius: 10px;
	box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.container .form-control {
	margin-bottom: 20px;
}

.container .btn-primary {
	background-color: #9B3D12;
	border: none;
	border-radius: 5px;
	padding: 10px 20px;
	cursor: pointer;
	width: 100%;
}

.container .btn-primary:hover {
	background-color: #7a2d08;
}

.signupContainer {
	margin-top: 20px;
	position: relative;
	width: 100%;
	max-width: 500px;
	margin-left:550px;
	border-radius: 5px;
}


.signupContainer form {
	background-color: #fff;
	padding: 20px;
	border-radius: 10px;
	box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}


.signupContainer button {
	color: white;
	padding: 10px 20px;
	background-color: #9B3D12;
	width: 100%;
	border: none;
	border-radius: 5px;
	cursor: pointer;
	transition: background-color 0.3s;
}

.signupContainer button:hover {
	background-color: #7a2d08;
}

.signupContainer button i {
	margin-left: 5px;
}

</style>

<div class="container">
	<form class="form-horizontal" role="form" method="post" action="login.php" enctype="multipart/form-data">
		<p class="text-center">Login</p>
		<div class="form-group">
			<input type="text" class="form-control" name="username" placeholder="Enter your username" id="username" required>
		</div>
		<div class="form-group">
			<input type="password" class="form-control" placeholder="Enter your password" name="password" id="password" required>
		</div>
		<div class="form-group">
			<input type="submit" class="btn btn-primary" name="submit" value="Login">
		</div>
	</form>
</div>

<div class="signupContainer">
	<form>
	<div>
		<p class="text-center">Signup</p>
		<div class="SignupDescription">
			<span>Don't have an account ? Create one now !</span><br>
			<a href="register.php" class="btn btn-primary">Create Account</a>


		</div>
	</div>
</form>
</div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/sweetalert.min.js"> </script>

<?php if (isset($alert_user)) { ?>
	<script type="text/javascript">
		swal("Oops...", "You are not allowed to view this page directly...!", "error");
	</script>
<?php } ?>

</body>
</html>
