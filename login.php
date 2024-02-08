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
						header("Location:studentportal.php");
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



<div class="container">

	<div class="container  col-lg-9 col-md-11 col-sm-12 col-xs-12 col-lg-offset-2 col-md-offset-1 col-sm-offset-0 col-xs-offset-0  ">
		<div class="jumbotron login col-lg-10 col-md-11 col-sm-12 col-xs-12">
			<!-- <div class="alert alert-success alert-dismissable">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <strong>Warning!</strong> Better check yourself, you're not looking too good.
			</div> -->
			<p class="text-center" style="text-align: center">Please provide details</p>

			<div class="container">
				<form class="form-horizontal" role="form" method="post" action="login.php" enctype="multipart/form-data">
					<div class="form-group">
						<div class="col-sm-10">
							<input type="text" class="form-control" name="username" placeholder="Enter your username" id="username" required>
						</div>
					</div>
					<div class="form-group">

					</div>
					<div class="form-group">
						<div class="col-sm-10">
							<input type="password" class="form-control" placeholder="Enter your password" name="password" id="password" required>
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" class="btn btn-primary col-lg-4" name="submit" value="Login">


						</div>
					</div>

					</div>
				</form>
			</div>
		</div>
	</div>

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
