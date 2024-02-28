<?php
require '../../util/snippet.php';
require '../../includes/db_conn.php';
include '../header.php';

session_start();

if (isset($_POST['del'])) {
	$id = sanitize(trim($_POST['id']));
	$sql_del = "DELETE from books where BookId = $id";
	$error = false;
	$result = mysqli_query($conn, $sql_del);
	if ($result) {
		$error = true;
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
					<h4 style=" font-weight: bold;">| Books</h4>
					<button type="button" class="btn btn-success" onclick="addRow(this)"><span class="bi-plus"></span>&nbsp;Book</button>
				</div>
				<div style="margin-top:30px">
					<table id="students_table" class="table table-striped" style="width:100%">
						<thead>
							<th>Book ID</th>
							<th>Book Title</th>
							<th>Author</th>
							<th>ISBN</th>
							<th>Book Copies</th>
							<th>Publisher Name</th>
							<th id='able'>Available</th>
							<!-- <th>Categories</th> -->
							<!-- <th>Call Number</th> -->
							<th>Actions</th>
						</thead>
						<?php
						$sql = "SELECT * FROM books";
						$query = mysqli_query($conn, $sql);
						$counter = 1;
						while ($row = mysqli_fetch_assoc($query)) {
						?>
							<tbody>
								<tr>
									<td><?php echo $row['bookId']; ?></td>
									<td><?php echo $row['bookTitle']; ?></td>
									<td><?php echo $row['author']; ?></td>
									<td><?php echo $row['ISBN']; ?></td>
									<td><?php echo $row['bookCopies']; ?></td>
									<td><?php echo $row['publisherName']; ?></td>
									<td><?php echo $row['available']; ?></td>
									<!-- <td><?php echo $row['categories']; ?></td> -->
									<!-- <td><?php echo $row['callNumber']; ?></td> -->
									<td>
										<form action="view/admin/books.php" method="post">
											<input type="hidden" value="<?php echo $row['bookId']; ?>" name="id">
											<button class="btn btn-primary"><span class="bi-pencil"></span></button>
											<button name="del" class="btn btn-danger"><span class="bi-trash"></span></button>
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
	</script>

</body>