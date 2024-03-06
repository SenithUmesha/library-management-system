<?php
require '../../util/snippet.php';
require '../../includes/db_conn.php';
include '../header.php';

session_start();

if (!isset($_SESSION['id']) || $_SESSION['id'] === null) {
	header("Location: ../../index.php");
	exit();
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
					<button class="btn btn-success" id="addstudent" onclick="openAddModal()"><span class="bi-plus"></span>&nbsp;Student</button>
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
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal for Add -->
	<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addModalLabel">Add Student Data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="addForm" onsubmit="return validateAddForm()">
						<div class="mb-3">
							<label for="addStudentName" class="form-label">Student Name</label>
							<input type="text" class="form-control" id="addStudentName" name="addStudentName" required>
						</div>
						<div class="mb-3">
							<label for="addAdmissionId" class="form-label">Admission ID</label>
							<input type="text" class="form-control" id="addAdmissionId" name="addAdmissionId" pattern="\d{6}" title="Admission ID must be 6 digits" required>
						</div>
						<div class="mb-3">
							<label for="addUsername" class="form-label">Username</label>
							<input type="text" class="form-control" id="addUsername" name="addUsername" required>
						</div>
						<div class="mb-3">
							<label for="addEmail" class="form-label">Email</label>
							<input type="text" class="form-control" id="addEmail" name="addEmail" required>
						</div>
						<div class="mb-3">
							<label for="addClass" class="form-label">Class</label>
							<input type="text" class="form-control" id="addClass" name="addClass" required>
						</div>
						<button type="submit" class="btn btn-primary" id="addstudentsavebtn" onclick="saveAddChanges()">Save</button>
					</form>
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
					<form id="editForm" onsubmit="return validateEditForm()">
						<div class="mb-3">
							<label for="editStudentName" class="form-label">Student Name</label>
							<input type="text" class="form-control" id="editStudentName" name="editStudentName" required>
						</div>
						<div class="mb-3">
							<label for="editAdmissionId" class="form-label">Admission ID</label>
							<input type="text" class="form-control" id="editAdmissionId" name="editAdmissionId" disabled>
						</div>
						<div class="mb-3">
							<label for="editUsername" class="form-label">Username</label>
							<input type="text" class="form-control" id="editUsername" name="editUsername" disabled>
						</div>
						<div class="mb-3">
							<label for="editEmail" class="form-label">Email</label>
							<input type="text" class="form-control" id="editEmail" name="editEmail" disabled>
						</div>
						<div class="mb-3">
							<label for="editClass" class="form-label">Class</label>
							<input type="text" class="form-control" id="editClass" name="editClass" required>
						</div>
						<button type="submit" class="btn btn-primary" id="editedsavedata" onclick="saveEditChanges()">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	<script>
		var currentStudentNo;
		var dataTable;

		$(document).ready(function() {
			fetchAllStudents();
		});

		function fetchAllStudents() {
			dataTable = $('#students_table').DataTable({
				ajax: {
					url: '../../api/api_students.php',
					type: 'POST',
					data: {
						action: 'fetch_all'
					}
				},
				columns: [{
						data: 'student_no',
						title: 'Student No.'
					},
					{
						data: 'student_name',
						title: 'Student Name',
						searchable: true
					},
					{
						data: 'admission_id',
						title: 'Admission ID'
					},
					{
						data: 'username',
						title: 'Username'
					},
					{
						data: 'email',
						title: 'Email'
					},
					{
						data: 'class',
						title: 'Class'
					},
					{
						data: null,
						title: 'Actions',
						render: function(data, type, row) {
							return `
                                <button class="btn btn-primary" id="editdata" onclick="openEditModal(${row.student_no})">
                                    <span class="bi-pencil">&nbsp;Edit
                                </button>
                                <button name="submit" class="btn btn-danger" id="deletebtn" onclick="confirmDelete(${row.student_no})">
                                    <span class="bi-trash">&nbsp;Delete
                                </button>`;
						}
					}
				],
				lengthMenu: [8, 25, 50, 100],
				paging: true,
				pageLength: 8,
				pagingType: 'full_numbers'
			});
		}

		function reloadDataTable() {
			dataTable.ajax.reload();
		}

		function validateEditForm() {
			var updatedStudentName = document.getElementById('editStudentName').value;
			var updatedClass = document.getElementById('editClass').value;

			if (!updatedStudentName || !updatedClass) {
				return false;
			}

			return true;
		}

		function validateAddForm() {
			var newStudentName = document.getElementById('addStudentName').value;
			var newAdmissionId = document.getElementById('addAdmissionId').value;
			var newUsername = document.getElementById('addUsername').value;
			var newEmail = document.getElementById('addEmail').value;
			var newClass = document.getElementById('addClass').value;

			if (!newStudentName || !newAdmissionId || !newUsername || !newEmail || !newClass) {
				return false;
			}

			var sixDigitNumberRegex = /^\d{6}$/;

			if (!sixDigitNumberRegex.test(newAdmissionId)) {
				return false;
			}

			return true;
		}

		function openAddModal() {
			var modal = new bootstrap.Modal(document.getElementById('addModal'));
			modal.show();
		}

		function saveAddChanges() {
			var newStudentName = document.getElementById('addStudentName').value;
			var newAdmissionId = document.getElementById('addAdmissionId').value;
			var newUsername = document.getElementById('addUsername').value;
			var newEmail = document.getElementById('addEmail').value;
			var newClass = document.getElementById('addClass').value;

			if (!validateAddForm()) {
				return;
			}

			$.ajax({
				url: '../../api/api_students.php',
				type: 'POST',
				data: {
					action: 'add',
					newStudentName: newStudentName,
					newAdmissionId: newAdmissionId,
					newUsername: newUsername,
					newEmail: newEmail,
					newClass: newClass
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					} else {
						console.log('New student added successfully:', data);

						reloadDataTable();

						var modal = new bootstrap.Modal(document.getElementById('addModal'));
						modal.hide();
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error adding new student');
				}
			});
		}

		function openEditModal(studentNo) {
			currentStudentNo = studentNo;

			$.ajax({
				url: '../../api/api_students.php',
				type: 'POST',
				data: {
					action: 'fetch',
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

		function saveEditChanges() {
			var studentNo = currentStudentNo;
			var updatedStudentName = document.getElementById('editStudentName').value;
			var updatedAdmissionId = document.getElementById('editAdmissionId').value;
			var updatedUsername = document.getElementById('editUsername').value;
			var updatedEmail = document.getElementById('editEmail').value;
			var updatedClass = document.getElementById('editClass').value;

			if (!validateEditForm()) {
				return;
			}

			$.ajax({
				url: '../../api/api_students.php',
				type: 'POST',
				data: {
					action: 'update',
					studentNo: studentNo,
					updatedStudentName: updatedStudentName,
					updatedAdmissionId: updatedAdmissionId,
					updatedUsername: updatedUsername,
					updatedEmail: updatedEmail,
					updatedClass: updatedClass
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					}
					console.log('Changes saved successfully:', data);

					reloadDataTable();

					var modal = new bootstrap.Modal(document.getElementById('editModal'));
					modal.hide();
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error saving changes');
				}
			});
		}

		function confirmDelete(studentNo) {
			if (confirm('Are you sure you want to delete this student?')) {
				$.ajax({
					url: '../../api/api_students.php',
					type: 'POST',
					data: {
						action: 'delete',
						studentNo: studentNo
					},
					dataType: 'json',
					success: function(data) {
						if (data.error) {
							alert(data.error);
						} else {
							reloadDataTable();
						}
					},
					error: function(xhr, status, error) {
						console.error('AJAX Error:', status, error);
						console.log('Response:', xhr.responseText);
						alert('Error confirming delete');
					}
				});
			}
		}
	</script>
</body>