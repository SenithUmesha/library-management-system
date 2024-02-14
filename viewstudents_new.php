<?php
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header_new.php";

if (isset($_POST['submit'])) {
	$id = trim($_POST['del_btn']);
	$sql = "DELETE from students where studentId = '$id' ";
	$query = mysqli_query($conn, $sql);

	if ($query) {
		echo "<script>alert('Student Deleted!')</script>";
	}
}
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
	body {
		padding: 0;
		margin: 0;
		width: 100%;
	}
</style>

<div class="wrapper"> <?php include "includes/side_navbar.php"; ?>
	<div class="main p-3">
		<div class="container">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
				<h4 style=" font-weight: bold;">| Students</h4>
				<button type="button" class="btn btn-success"><span class="bi-plus"></span>&nbsp;Student</button>
			</div>
			<div style="margin-top:30px">
				<table id="students_table" class="table table-striped" style="width:100%">
					<thead>
						<th>ID</th>
						<th>Student Name</th>
						<th>Matric No</th>
						<th>Email</th>
						<th>Department</th>
						<th>Username</th>
						<th>Password</th>
						<th>Actions</th>
					</thead>
					<?php
					$sql = "SELECT * FROM students";
					$query = mysqli_query($conn, $sql);
					$counter = 1;
					while ($row = mysqli_fetch_assoc($query)) {
					?>
						<tbody>
							<tr>
								<td><?php echo $counter++; ?></td>
								<td><?php echo $row['name']; ?></td>
								<td><?php echo $row['matric_no']; ?></td>
								<td><?php echo $row['email']; ?></td>
								<td><?php echo $row['dept']; ?></td>
								<td><?php echo $row['username']; ?></td>
								<td><?php echo $row['password']; ?></td>
								<td>
									<button type="button" class="btn btn-primary" onclick="deleteRow(this)"><span class="bi-pencil"></span></button>
									<button type="button" class="btn btn-danger" onclick="editRow(this)"><span class="bi-trash"></span></button>
								</td>
							</tr>

						</tbody>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
	new DataTable('#students_table');

	function editRow(button) {
		alert('Edit clicked');
	}

	function deleteRow(button) {
		alert('Delete clicked');
	}
</script>

</body>

</html>