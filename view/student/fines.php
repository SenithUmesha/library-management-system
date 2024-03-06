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
							<th>Student Name</th>
							<th>Book Title</th>
							<th>Fine Amount</th>
							<th>Issued Date</th>
							<th>Due Date</th>
							<th>Payment Status</th>
							<th>Paid Date</th>
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
						data: 'student_name',
						title: 'Student Name',
						searchable: true
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
						title: 'Payment Status',
						render: function(data, type, row) {
							var statusText = data === 'Paid' ? 'Paid' : 'Unpaid';
							var statusClass = data === 'Paid' ? 'text-success' : 'text-danger';

							return `<span class="${statusClass}">${statusText}</span>`;
						}
					},
					{
						data: 'paid_date',
						title: 'Paid Date',
						render: function(data) {
							return data ? data : '<center>-</center>';
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