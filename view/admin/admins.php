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
					<h4 style="font-weight: bold;">| Admins</h4>
					<div>
						<button class="btn btn-primary" id="report" onclick="generatePDF()"><i class="bi bi-file-earmark-arrow-down"></i>&nbsp;Print</button>
						<button class="btn btn-success" onclick="openAddModal()"><span class="bi-plus"></span>&nbsp;Admin</button>
					</div>
				</div>
				<div style="margin-top:30px">
					<table id="admins_table" class="table table-striped" style="width:100%">
						<thead>
							<th>Admin No.</th>
							<th>Admin Name</th>
							<th>Username</th>
							<th>Email</th>
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
					<h5 class="modal-title" id="addModalLabel">Add Admin Data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="addForm" onsubmit="return validateAddForm()">
						<div class="mb-3">
							<label for="addAdminName" class="form-label">Admin Name</label>
							<input type="text" class="form-control" id="addAdminName" name="addAdminName" required>
						</div>
						<div class="mb-3">
							<label for="addUsername" class="form-label">Username</label>
							<input type="text" class="form-control" id="addUsername" name="addUsername" required>
						</div>
						<div class="mb-3">
							<label for="addEmail" class="form-label">Email</label>
							<input type="text" class="form-control" id="addEmail" name="addEmail" required>
						</div>
						<button type="submit" class="btn btn-primary" onclick="saveAddChanges()">Save</button>
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
					<h5 class="modal-title" id="editModalLabel">Edit Admin Data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="editForm" onsubmit="return validateEditForm()">
						<div class="mb-3">
							<label for="editAdminName" class="form-label">Admin Name</label>
							<input type="text" class="form-control" id="editAdminName" name="editAdminName" required>
						</div>
						<div class="mb-3">
							<label for="editUsername" class="form-label">Username</label>
							<input type="text" class="form-control" id="editUsername" name="editUsername" disabled>
						</div>
						<div class="mb-3">
							<label for="editEmail" class="form-label">Email</label>
							<input type="text" class="form-control" id="editEmail" name="editEmail" disabled>
						</div>
						<button type="submit" class="btn btn-primary" onclick="saveEditChanges()">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	<script>
		var currentAdminNo;
		var dataTable;

		$(document).ready(function() {
			fetchAllAdmins();
		});

		function fetchAllAdmins() {
			dataTable = $('#admins_table').DataTable({
				ajax: {
					url: '../../api/api_admins.php',
					type: 'POST',
					data: {
						action: 'fetch_all'
					}
				},
				columns: [{
						data: 'admin_no',
						title: 'Admin No.'
					},
					{
						data: 'admin_name',
						title: 'Admin Name',
						searchable: true
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
						data: null,
						title: 'Actions',
						render: function(data, type, row) {
							return `
                                <button class="btn btn-primary" onclick="openEditModal(${row.admin_no})">
                                    <span class="bi-pencil">&nbsp;Edit
                                </button>
                                <button name="submit" class="btn btn-danger" onclick="confirmDelete(${row.admin_no})">
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
			var updatedAdminName = document.getElementById('editAdminName').value;

			if (!updatedAdminName) {
				return false;
			}

			return true;
		}

		function validateAddForm() {
			var newAdminName = document.getElementById('addAdminName').value;
			var newUsername = document.getElementById('addUsername').value;
			var newEmail = document.getElementById('addEmail').value;

			if (!newAdminName || !newUsername || !newEmail) {
				return false;
			}

			return true;
		}

		function openAddModal() {
			var modal = new bootstrap.Modal(document.getElementById('addModal'));
			modal.show();
		}

		function saveAddChanges() {
			var newAdminName = document.getElementById('addAdminName').value;
			var newUsername = document.getElementById('addUsername').value;
			var newEmail = document.getElementById('addEmail').value;

			if (!validateAddForm()) {
				return;
			}

			$.ajax({
				url: '../../api/api_admins.php',
				type: 'POST',
				data: {
					action: 'add',
					newAdminName: newAdminName,
					newUsername: newUsername,
					newEmail: newEmail
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					}
					console.log('New admin added successfully:', data);

					reloadDataTable();

					var modal = new bootstrap.Modal(document.getElementById('addModal'));
					modal.hide();
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error adding new admin');
				}
			});
		}

		function openEditModal(adminNo) {
			currentAdminNo = adminNo;

			$.ajax({
				url: '../../api/api_admins.php',
				type: 'POST',
				data: {
					action: 'fetch',
					adminNo: adminNo
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					}

					document.getElementById('editAdminName').value = data.admin_name;
					document.getElementById('editUsername').value = data.username;
					document.getElementById('editEmail').value = data.email;

					var modal = new bootstrap.Modal(document.getElementById('editModal'));
					modal.show();
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error fetching admin data');
				}
			});
		}

		function saveEditChanges() {
			var adminNo = currentAdminNo;
			var updatedAdminName = document.getElementById('editAdminName').value;
			var updatedUsername = document.getElementById('editUsername').value;
			var updatedEmail = document.getElementById('editEmail').value;

			if (!validateEditForm()) {
				return;
			}

			$.ajax({
				url: '../../api/api_admins.php',
				type: 'POST',
				data: {
					action: 'update',
					adminNo: adminNo,
					updatedAdminName: updatedAdminName,
					updatedUsername: updatedUsername,
					updatedEmail: updatedEmail
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

		function confirmDelete(adminNo) {
			if (confirm('Are you sure you want to delete this admin?')) {
				$.ajax({
					url: '../../api/api_admins.php',
					type: 'POST',
					data: {
						action: 'delete',
						adminNo: adminNo
					},
					dataType: 'json',
					success: function(data) {
						if (data.error) {
							alert(data.error);
						}
						reloadDataTable();
					},
					error: function(xhr, status, error) {
						console.error('AJAX Error:', status, error);
						console.log('Response:', xhr.responseText);
						alert('Error confirming delete');
					}
				});
			}
		}

		function generatePDF() {
			$.ajax({
				url: 'generate_pdf.php',
				type: 'POST',
				data: {
					action: 'admins'
				},
				success: function(response) {
					window.open(response, '_blank');
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					alert('Error generating PDF: ' + error);
				}
			});
		}
	</script>
</body>