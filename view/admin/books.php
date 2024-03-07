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
					<h4 style="font-weight: bold;">| Books</h4>
					<button class="btn btn-success" id="addbook" onclick="openAddModal()"><span class="bi-plus"></span>&nbsp;Book</button>
				</div>
				<div style="margin-top:30px; max-width: 100%; overflow-x: auto;">
					<table id="books_table" class="table table-striped" style="width:100%">
						<thead>
							<th>Book No.</th>
							<th>Book Title</th>
							<th>Author Name</th>
							<th>ISBN No.</th>
							<th>No. of Copies</th>
							<th>Publisher</th>
							<th>Availability</th>
							<th>Categories</th>
							<th>Added Date</th>
							<th>Language</th>
							<th>Description</th>
							<th>Location</th>
							<th>User Ratings</th>
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
					<h5 class="modal-title" id="addModalLabel">Add Book Data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="addForm" onsubmit="return validateAddForm()">
						<div class="mb-3">
							<label for="addBookTitle" class="form-label">Book Title</label>
							<input type="text" class="form-control" id="addBookTitle" name="addBookTitle" required>
						</div>
						<div class="mb-3">
							<label for="addAuthorName" class="form-label">Author Name</label>
							<input type="text" class="form-control" id="addAuthorName" name="addAuthorName" required>
						</div>
						<div class="mb-3">
							<label for="addISBN" class="form-label">ISBN No.</label>
							<input type="text" class="form-control" id="addISBN" name="addISBN" required>
						</div>
						<div class="mb-3">
							<label for="addNoOfCopies" class="form-label">No. of Copies</label>
							<input type="number" class="form-control" id="addNoOfCopies" name="addNoOfCopies" required>
						</div>
						<div class="mb-3">
							<label for="addPublisher" class="form-label">Publisher</label>
							<input type="text" class="form-control" id="addPublisher" name="addPublisher" required>
						</div>
						<div class="mb-3">
							<label for="addCategories" class="form-label">Categories</label>
							<input type="text" class="form-control" id="addCategories" name="addCategories" required>
						</div>
						<div class="mb-3">
							<label for="addLanguage" class="form-label">Language</label>
							<input type="text" class="form-control" id="addLanguage" name="addLanguage" required>
						</div>
						<div class="mb-3">
							<label for="addDescription" class="form-label">Description</label>
							<textarea class="form-control" id="addDescription" name="addDescription" required></textarea>
						</div>
						<div class="mb-3">
							<label for="addLocation" class="form-label">Location</label>
							<input type="text" class="form-control" id="addLocation" name="addLocation">
						</div>
						<button type="submit" class="btn btn-primary" id="addsavebook" onclick="saveAddChanges()">Save</button>
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
					<h5 class="modal-title" id="editModalLabel">Edit Book Data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="editForm" onsubmit="return validateEditForm()">
						<div class="mb-3">
							<label for="editBookTitle" class="form-label">Book Title</label>
							<input type="text" class="form-control" id="editBookTitle" name="editBookTitle" required>
						</div>
						<div class="mb-3">
							<label for="editAuthorName" class="form-label">Author Name</label>
							<input type="text" class="form-control" id="editAuthorName" name="editAuthorName" required>
						</div>
						<div class="mb-3">
							<label for="editISBN" class="form-label">ISBN No.</label>
							<input type="text" class="form-control" id="editISBN" name="editISBN" required>
						</div>
						<div class="mb-3">
							<label for="editNoOfCopies" class="form-label">No. of Copies</label>
							<input type="number" class="form-control" id="editNoOfCopies" name="editNoOfCopies" required>
						</div>
						<div class="mb-3">
							<label for="editPublisher" class="form-label">Publisher</label>
							<input type="text" class="form-control" id="editPublisher" name="editPublisher" required>
						</div>
						<div class="mb-3">
							<label for="editCategories" class="form-label">Categories</label>
							<input type="text" class="form-control" id="editCategories" name="editCategories" required>
						</div>
						<div class="mb-3">
							<label for="editAddedDate" class="form-label">Added Date</label>
							<input type="datetime-local" class="form-control" id="editAddedDate" name="editAddedDate" disabled>
						</div>
						<div class="mb-3">
							<label for="editLanguage" class="form-label">Language</label>
							<input type="text" class="form-control" id="editLanguage" name="editLanguage" required>
						</div>
						<div class="mb-3">
							<label for="editDescription" class="form-label">Description</label>
							<textarea class="form-control" id="editDescription" name="editDescription" required></textarea>
						</div>
						<div class="mb-3">
							<label for="editLocation" class="form-label">Location</label>
							<input type="text" class="form-control" id="editLocation" name="editLocation" required>
						</div>
						<div class="mb-3">
							<label for="editUserRatings" class="form-label">User Ratings</label>
							<input type="text" class="form-control" id="editUserRatings" name="editUserRatings" disabled>
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
		var currentBookNo;
		var dataTable;

		$(document).ready(function() {
			fetchAllBooks();
		});

		function fetchAllBooks() {
			dataTable = $('#books_table').DataTable({
				ajax: {
					url: '../../api/api_books.php',
					type: 'POST',
					data: {
						action: 'fetch_all'
					}
				},
				columns: [{
						data: 'book_no',
						title: 'Book No.'
					},
					{
						data: 'book_title',
						title: 'Book Title',
						searchable: true
					},
					{
						data: 'author_name',
						title: 'Author Name'
					},
					{
						data: 'isbn_no',
						title: 'ISBN No.'
					},
					{
						data: 'no_of_copies',
						title: 'No. of Copies'
					},
					{
						data: 'publisher',
						title: 'Publisher'
					},
					{
						data: 'no_of_copies',
						title: 'Availability',
						render: function(data, type, row) {
							var availability = data >= 1 ? 'Available' : 'Not Available';
							var statusClass = data >= 1 ? 'text-success' : 'text-danger';

							return `<span class="${statusClass}">${availability}</span>`;
						}
					},
					{
						data: 'categories',
						title: 'Categories'
					},
					{
						data: 'added_date',
						title: 'Added Date'
					},
					{
						data: 'language',
						title: 'Language'
					},
					{
						data: 'description',
						title: 'Description'
					},
					{
						data: 'location',
						title: 'Location'
					},
					{
						data: 'user_ratings',
						title: 'User Ratings'
					},
					{
						data: null,
						title: 'Actions',
						render: function(data, type, row) {
							return `
                                <button class="btn btn-primary" onclick="openEditModal(${row.book_no})">
                                    <span class="bi-pencil">&nbsp;Edit
                                </button>
                                <button name="submit" class="btn btn-danger" onclick="confirmDelete(${row.book_no})">
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
			var updatedBookTitle = document.getElementById('editBookTitle').value;
			var updatedAuthorName = document.getElementById('editAuthorName').value;
			var updatedISBN = document.getElementById('editISBN').value;
			var updatedNoOfCopies = document.getElementById('editNoOfCopies').value;
			var updatedPublisher = document.getElementById('editPublisher').value;
			var updatedCategories = document.getElementById('editCategories').value;
			var updatedLanguage = document.getElementById('editLanguage').value;
			var updatedDescription = document.getElementById('editDescription').value;
			var updatedLocation = document.getElementById('editLocation').value;

			if (!updatedBookTitle || !updatedAuthorName || !updatedISBN || !updatedNoOfCopies || !updatedPublisher || !updatedCategories || !updatedLanguage || !updatedDescription || !updatedLocation) {
				return false;
			}

			return true;
		}

		function validateAddForm() {
			var newBookTitle = document.getElementById('addBookTitle').value;
			var newAuthorName = document.getElementById('addAuthorName').value;
			var newISBN = document.getElementById('addISBN').value;
			var newNoOfCopies = document.getElementById('addNoOfCopies').value;
			var newPublisher = document.getElementById('addPublisher').value;
			var newCategories = document.getElementById('addCategories').value;
			var newLanguage = document.getElementById('addLanguage').value;
			var newDescription = document.getElementById('addDescription').value;
			var newLocation = document.getElementById('addLocation').value;

			if (!newBookTitle || !newAuthorName || !newISBN || !newNoOfCopies || !newPublisher ||
				!newCategories || !newLanguage || !newDescription || !newLocation) {
				return false;
			}

			return true;
		}

		function openAddModal() {
			var modal = new bootstrap.Modal(document.getElementById('addModal'));
			modal.show();
		}

		function saveAddChanges() {
			var newBookTitle = document.getElementById('addBookTitle').value;
			var newAuthorName = document.getElementById('addAuthorName').value;
			var newISBN = document.getElementById('addISBN').value;
			var newNoOfCopies = document.getElementById('addNoOfCopies').value;
			var newPublisher = document.getElementById('addPublisher').value;
			var newCategories = document.getElementById('addCategories').value;
			var newLanguage = document.getElementById('addLanguage').value;
			var newDescription = document.getElementById('addDescription').value;
			var newLocation = document.getElementById('addLocation').value;

			if (!validateAddForm()) {
				return;
			}

			$.ajax({
				url: '../../api/api_books.php',
				type: 'POST',
				data: {
					action: 'add',
					newBookTitle: newBookTitle,
					newAuthorName: newAuthorName,
					newISBN: newISBN,
					newNoOfCopies: newNoOfCopies,
					newPublisher: newPublisher,
					newCategories: newCategories,
					newLanguage: newLanguage,
					newDescription: newDescription,
					newLocation: newLocation,
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					}
					console.log('New book added successfully:', data);

					reloadDataTable();

					var modal = new bootstrap.Modal(document.getElementById('addModal'));
					modal.hide();
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error adding new book');
				}
			});
		}

		function openEditModal(bookNo) {
			currentBookNo = bookNo;

			$.ajax({
				url: '../../api/api_books.php',
				type: 'POST',
				data: {
					action: 'fetch',
					bookNo: bookNo
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					}

					document.getElementById('editBookTitle').value = data.book_title;
					document.getElementById('editAuthorName').value = data.author_name;
					document.getElementById('editISBN').value = data.isbn_no;
					document.getElementById('editNoOfCopies').value = data.no_of_copies;
					document.getElementById('editPublisher').value = data.publisher;
					document.getElementById('editCategories').value = data.categories;
					document.getElementById('editAddedDate').value = data.added_date;
					document.getElementById('editLanguage').value = data.language;
					document.getElementById('editDescription').value = data.description;
					document.getElementById('editLocation').value = data.location;
					document.getElementById('editUserRatings').value = data.user_ratings;

					var modal = new bootstrap.Modal(document.getElementById('editModal'));
					modal.show();
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error fetching book data');
				}
			});
		}

		function saveEditChanges() {
			var bookNo = currentBookNo;
			var updatedBookTitle = document.getElementById('editBookTitle').value;
			var updatedAuthorName = document.getElementById('editAuthorName').value;
			var updatedISBN = document.getElementById('editISBN').value;
			var updatedNoOfCopies = document.getElementById('editNoOfCopies').value;
			var updatedPublisher = document.getElementById('editPublisher').value;
			var updatedCategories = document.getElementById('editCategories').value;
			var updatedLanguage = document.getElementById('editLanguage').value;
			var updatedDescription = document.getElementById('editDescription').value;
			var updatedLocation = document.getElementById('editLocation').value;

			if (!validateEditForm()) {
				return;
			}

			$.ajax({
				url: '../../api/api_books.php',
				type: 'POST',
				data: {
					action: 'update',
					bookNo: bookNo,
					updatedBookTitle: updatedBookTitle,
					updatedAuthorName: updatedAuthorName,
					updatedISBN: updatedISBN,
					updatedNoOfCopies: updatedNoOfCopies,
					updatedPublisher: updatedPublisher,
					updatedCategories: updatedCategories,
					updatedLanguage: updatedLanguage,
					updatedDescription: updatedDescription,
					updatedLocation: updatedLocation
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

		function confirmDelete(bookNo) {
			if (confirm('Are you sure you want to delete this book?')) {
				$.ajax({
					url: '../../api/api_books.php',
					type: 'POST',
					data: {
						action: 'delete',
						bookNo: bookNo
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
	</script>
</body>