<?php
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header_new.php";

$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
	body {
		padding: 0;
		margin: 0;
		width: 100%;
	}

	h4 {
		margin-top: 18px;
		font-weight: bold;
	}
</style>

<div class="wrapper"> <?php include "includes/side_navbar.php"; ?>
	<div class="main p-3">
		<h4>| Students</h4>
		<div class="container">
			<div style="margin-top:40px">
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
									<button type="button" class="btn btn-primary" onclick="deleteRow(this)">Edit</button>
									<button type="button" class="btn btn-danger" onclick="editRow(this)">Delete</button>
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