<?php
require '../../util/snippet.php';
require '../../includes/db_conn.php';
include '../header.php';
include 'chatbot.php';

session_start();
?>

<style>
	body {
		padding: 0;
		margin: 0;
		width: 100%;
	}
</style>

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
					<button class="btn btn-success" onclick="openAddModal()"><span class="bi-plus"></span>&nbsp;Book</button>
				</div>
				<div style="margin-top:30px; max-width: 100%; overflow-x: auto;">
					<table id="books_table" class="table table-striped" style="width:100%">
						<thead>
							<th>Book No.</th>
							<th>Book Title</th>
							<th>Author Name</th>
							<th>ISBN No.</th>
							<th>Publisher</th>
							<th>Availability</th>
							<th>Categories</th>
							<th>Language</th>
							<th>Description</th>
							<th>Location</th>
							<th>Ratings</th>
							<th>Actions</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	<script>
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
						data: 'publisher',
						title: 'Publisher'
					},
					{
						data: 'availability',
						title: 'Availability'
					},
					{
						data: 'categories',
						title: 'Categories'
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
						title: 'Ratings'
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
	</script>
</body>