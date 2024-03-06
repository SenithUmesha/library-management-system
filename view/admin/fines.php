<?php
require '../../util/snippet.php';
require '../../includes/db_conn.php';
include '../header.php';

session_start();
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
					<h4 style="font-weight: bold;">| Fines</h4>
				</div>
				<div style="margin-top:30px">
					<table id="fines_table" class="table table-striped" style="width:100%">
						<thead>
							<th>Fine No.</th>
							<th>Student No.</th>
							<th>Student Name</th>
							<th>Book No.</th>
							<th>Book Title</th>
							<th>Fine Amount</th>
							<th>Issued Date</th>
							<th>Due Date</th>
							<th>Payment Status</th>
							<th>Paid Date</th>
							<th>Actions</th>
						</thead>
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
					<h5 class="modal-title" id="editModalLabel">Edit Fine Data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="editForm" onsubmit="return validateEditForm()">
						<div class="mb-3">
							<label for="editStudentNo" class="form-label">Student No.</label>
							<input type="text" class="form-control" id="editStudentNo" name="editStudentNo" disabled>
						</div>
						<div class="mb-3">
							<label for="editStudentName" class="form-label">Student Name</label>
							<input type="text" class="form-control" id="editStudentName" name="editStudentName" disabled>
						</div>
						<div class="mb-3">
							<label for="editBookNo" class="form-label">Book No.</label>
							<input type="text" class="form-control" id="editBookNo" name="editBookNo" disabled>
						</div>
						<div class="mb-3">
							<label for="editBookTitle" class="form-label">Book Title</label>
							<input type="text" class="form-control" id="editBookTitle" name="editBookTitle" disabled>
						</div>
						<div class="mb-3">
							<label for="editFineAmount" class="form-label">Fine Amount</label>
							<input type="text" class="form-control" id="editFineAmount" name="editFineAmount" required>
						</div>
						<div class="mb-3">
							<label for="editIssuedDate" class="form-label">Issued Date</label>
							<input type="text" class="form-control" id="editIssuedDate" name="editIssuedDate" disabled>
						</div>
						<div class="mb-3">
							<label for="editDueDate" class="form-label">Due Date</label>
							<input type="text" class="form-control" id="editDueDate" name="editDueDate" disabled>
						</div>
						<div class="mb-3">
							<label for="editPaymentStatus" class="form-label">Payment Status</label>
							<input type="text" class="form-control" id="editPaymentStatus" name="editPaymentStatus" disabled>
						</div>
						<div class="mb-3">
							<label for="editPaidDate" class="form-label">Paid Date</label>
							<input type="text" class="form-control" id="editPaidDate" name="editPaidDate" disabled>
						</div>
						<button type="submit" class="btn btn-primary" id="savebtn" onclick="saveEditChanges()">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div>


	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	<script>
		var currentFineNo;
		var dataTable;

		$(document).ready(function() {
			fetchAllFines();
		});

		function fetchAllFines() {
			dataTable = $('#fines_table').DataTable({
				ajax: {
					url: '../../api/api_fines.php',
					type: 'POST',
					data: {
						action: 'fetch_all'
					}
				},
				columns: [{
						data: 'fine_no',
						title: 'Fine No.'
					},
					{
						data: 'student_no',
						title: 'Student No.'
					},
					{
						data: 'student_name',
						title: 'Student Name',
						searchable: true
					},
					{
						data: 'book_no',
						title: 'Book No.'
					},
					{
						data: 'book_title',
						title: 'Book Title',
						searchable: true
					},
					{
						data: 'fine_amount',
						title: 'Fine Amount'
					},
					{
						data: 'issued_date',
						title: 'Issued Date'
					},
					{
						data: 'due_date',
						title: 'Due Date'
					},
					{
						data: 'payment_status',
						title: 'Payment Status'
					},
					{
						data: 'paid_date',
						title: 'Paid Date',
						render: function(data) {
							return data ? data : '<center>-</center>';
						}
					},
					{
						data: null,
						title: 'Actions',
						render: function(data, type, row) {
							var paidButton = row.payment_status !== 'Paid' ? `<button class="btn btn-primary" onclick="markPaid(${row.fine_no})"><span class="bi bi-currency-dollar">&nbsp;Paid</button>` : '';

							return `
                                <button class="btn btn-primary" id="openEditModelbtn" onclick="openEditModal(${row.fine_no})">
                                    <span class="bi-pencil">&nbsp;Edit
                                </button>
                                <button name="submit" class="btn btn-danger" id="deletebtn" onclick="confirmDelete(${row.fine_no})">
                                    <span class="bi-trash">&nbsp;Delete
                                </button>
								${paidButton}`;
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
			var updatedFineAmount = document.getElementById('editFineAmount').value;

			if (!updatedFineAmount) {
				return false;
			}

			return true;
		}

		function openEditModal(fineNo) {
			currentFineNo = fineNo;

			$.ajax({
				url: '../../api/api_fines.php',
				type: 'POST',
				data: {
					action: 'fetch',
					fineNo: fineNo
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					}

					document.getElementById('editStudentNo').value = data.student_no;
					document.getElementById('editStudentName').value = data.student_name;
					document.getElementById('editBookNo').value = data.book_no;
					document.getElementById('editBookTitle').value = data.book_title;
					document.getElementById('editFineAmount').value = data.fine_amount;
					document.getElementById('editIssuedDate').value = data.issued_date;
					document.getElementById('editDueDate').value = data.due_date;
					document.getElementById('editPaymentStatus').value = data.payment_status;
					document.getElementById('editPaidDate').value = data.paid_date;

					var modal = new bootstrap.Modal(document.getElementById('editModal'));
					modal.show();
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error fetching fine data');
				}
			});
		}

		function saveEditChanges() {
			var fineNo = currentFineNo;
			var updatedFineAmount = document.getElementById('editFineAmount').value;

			if (!validateEditForm()) {
				return;
			}

			$.ajax({
				url: '../../api/api_fines.php',
				type: 'POST',
				data: {
					action: 'update',
					fineNo: fineNo,
					updatedFineAmount: updatedFineAmount,
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

		function confirmDelete(fineNo) {
			if (confirm('Are you sure you want to delete this fine?')) {
				$.ajax({
					url: '../../api/api_fines.php',
					type: 'POST',
					data: {
						action: 'delete',
						fineNo: fineNo
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

		function markPaid(fineNo) {
			$.ajax({
				url: '../../api/api_fines.php',
				type: 'POST',
				data: {
					action: 'paid',
					fineNo: fineNo,
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					}
					console.log('Changes saved successfully:', data);

					reloadDataTable();
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error saving changes');
				}
			});
		}
	</script>
</body>