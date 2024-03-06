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
					<h4 style="font-weight: bold;">| Borrowed Books</h4>
				</div>
				<div style="margin-top:30px">
					<table id="borrowed_books_table" class="table table-striped" style="width:100%">
						<thead>
							<th>Borrowed Book No.</th>
							<th>Book No.</th>
							<th>Book Title</th>
							<th>Student No.</th>
							<th>Student Name</th>
							<th>Borrowed Date</th>
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
					<h5 class="modal-title" id="editModalLabel">Edit Borrowed Book Data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="editForm" onsubmit="return validateEditForm()">
						<div class="mb-3">
							<label for="editBookNo" class="form-label">Book No.</label>
							<input type="text" class="form-control" id="editBookNo" name="editBookNo" disabled>
						</div>
						<div class="mb-3">
							<label for="editBookTitle" class="form-label">Book Title</label>
							<input type="text" class="form-control" id="editBookTitle" name="editBookTitle" disabled>
						</div>
						<div class="mb-3">
							<label for="editStudentNo" class="form-label">Student No.</label>
							<input type="text" class="form-control" id="editStudentNo" name="editStudentNo" disabled>
						</div>
						<div class="mb-3">
							<label for="editStudentName" class="form-label">Student Name</label>
							<input type="text" class="form-control" id="editStudentName" name="editStudentName" disabled>
						</div>
						<div class="mb-3">
							<label for="editBorrowedDate" class="form-label">Borrowed Date</label>
							<input type="datetime-local" class="form-control" id="editBorrowedDate" name="editBorrowedDate" required>
						</div>
						<button type="submit" class="btn btn-primary" id="saveeditbtn" onclick="saveEditChanges()">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	<script>
		var currentBorrowedBookNo;
		var dataTable;

		$(document).ready(function() {
			fetchAllBorrowedBooks();
		});

		function fetchAllBorrowedBooks() {
			dataTable = $('#borrowed_books_table').DataTable({
				ajax: {
					url: '../../api/api_borrowed_books.php',
					type: 'POST',
					data: {
						action: 'fetch_all'
					}
				},
				columns: [{
						data: 'borrowed_book_no',
						title: 'Borrowed Book No.'
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
						data: 'student_no',
						title: 'Student No.'
					},
					{
						data: 'student_name',
						title: 'Student Name',
						searchable: true
					},
					{
						data: 'borrowed_date',
						title: 'Borrowed Date'
					},
					{
						data: null,
						title: 'Actions',
						render: function(data, type, row) {
							return `
                                <button class="btn btn-primary" id="editbookbtn" onclick="openEditModal(${row.borrowed_book_no})">
                                    <span class="bi-pencil">&nbsp;Edit
                                </button>
                                <button name="submit" class="btn btn-danger" id="deletebookbtn" onclick="confirmDelete(${row.borrowed_book_no})">
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
			var updatedBorrowedDate = document.getElementById('editBorrowedDate').value;

			if (!updatedBorrowedDate) {
				return false;
			}

			return true;
		}

		function openEditModal(borrowedBookNo) {
			currentBorrowedBookNo = borrowedBookNo;

			$.ajax({
				url: '../../api/api_borrowed_books.php',
				type: 'POST',
				data: {
					action: 'fetch',
					borrowedBookNo: borrowedBookNo
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					}

					document.getElementById('editBookNo').value = data.book_no;
					document.getElementById('editBookTitle').value = data.book_title;
					document.getElementById('editStudentNo').value = data.student_no;
					document.getElementById('editStudentName').value = data.student_name;
					document.getElementById('editBorrowedDate').value = data.borrowed_date;

					var modal = new bootstrap.Modal(document.getElementById('editModal'));
					modal.show();
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error fetching borrowed book data');
				}
			});
		}

		function saveEditChanges() {
			var borrowedBookNo = currentBorrowedBookNo;
			var updatedBorrowedDate = document.getElementById('editBorrowedDate').value;

			if (!validateEditForm()) {
				return;
			}

			$.ajax({
				url: '../../api/api_borrowed_books.php',
				type: 'POST',
				data: {
					action: 'update',
					borrowedBookNo: borrowedBookNo,
					updatedBorrowedDate: updatedBorrowedDate
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

		function confirmDelete(borrowedBookNo) {
			if (confirm('Are you sure you want to delete this borrowed book data?')) {
				$.ajax({
					url: '../../api/api_borrowed_books.php',
					type: 'POST',
					data: {
						action: 'delete',
						borrowedBookNo: borrowedBookNo
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