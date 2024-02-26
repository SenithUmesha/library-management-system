<?php
require '../../util/snippet.php';
require '../../includes/db_conn.php';
include '../header.php';

session_start();

if (isset($_POST['submit'])) {
	$id = trim($_POST['del_btn']);
	$sql = "DELETE from students where studentId = '$id' ";
	$query = mysqli_query($conn, $sql);

	if ($query) {
		echo "<script>alert('Student Deleted!')</script>";
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
	<div class="wrapper">
		<?php include  '../side_navbar.php'; ?>
		<div class="main p-3">
			<div class="container">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
					<h4 style="font-weight: bold;">| Students</h4>
					<button type="button" class="btn btn-success" onclick="addRow(this)"><span class="bi-plus"></span>&nbsp;Student</button>
				</div>
				<div style="margin-top:30px">
					<table id="students_table" class="table table-striped" style="width:100%">
						<thead>
							<th>Student No.</th>
							<th>Student Name</th>
							<th>Admission ID</th>
							<th>Username</th>
							<th>Email</th>
							<th>Class</th>
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
									<td><?php echo $row['student_no']; ?></td>
									<td><?php echo $row['student_name']; ?></td>
									<td><?php echo $row['admission_id']; ?></td>
									<td><?php echo $row['username']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td><?php echo $row['class']; ?></td>
									<td>
										<button class="btn btn-primary" onclick="openEditModal(<?php echo $row['student_no']; ?>)"><span class="bi-pencil">&nbsp;Edit</span></button>
										<button name="submit" class="btn btn-danger" onclick="confirmDelete(<?php echo $row['student_no']; ?>)"><span class="bi-trash">&nbsp;Delete</span></button>
									</td>
								</tr>
							</tbody>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal for Edit -->
	<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editModalLabel">Edit Student Data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="editForm">
						<div class="mb-3">
							<label for="editStudentName" class="form-label">Student Name</label>
							<input type="text" class="form-control" id="editStudentName" name="editStudentName">
						</div>
						<div class="mb-3">
							<label for="editAdmissionId" class="form-label">Admission ID</label>
							<input type="text" class="form-control" id="editAdmissionId" name="editAdmissionId">
						</div>
						<div class="mb-3">
							<label for="editUsername" class="form-label">Username</label>
							<input type="text" class="form-control" id="editUsername" name="editUsername">
						</div>
						<div class="mb-3">
							<label for="editEmail" class="form-label">Email</label>
							<input type="text" class="form-control" id="editEmail" name="editEmail">
						</div>
						<div class="mb-3">
							<label for="editClass" class="form-label">Class</label>
							<input type="text" class="form-control" id="editClass" name="editClass">
						</div>
						<button type="button" class="btn btn-primary" onclick="saveChanges()">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	<script>
		new DataTable('#students_table');

		function openEditModal(studentNo) {
			$.ajax({
				url: '../../api/api_students.php',
				type: 'POST',
				data: {
					action: 'edit',
					studentNo: studentNo
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					}

					document.getElementById('editStudentName').value = data.student_name;
					document.getElementById('editAdmissionId').value = data.admission_id;
					document.getElementById('editUsername').value = data.username;
					document.getElementById('editEmail').value = data.email;
					document.getElementById('editClass').value = data.class;

					var modal = new bootstrap.Modal(document.getElementById('editModal'));
					modal.show();
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error fetching student data');
				}
			});
		}

		function saveChanges() {
			var modal = new bootstrap.Modal(document.getElementById('editModal'));
			modal.hide();
		}

		function confirmDelete(studentId) {
			if (confirm('Are you sure you want to delete this student?')) {}
		}
	</script>
</body>