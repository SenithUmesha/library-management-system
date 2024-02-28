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

<body>
	<div class="wrapper">
		<?php include  '../side_navbar.php'; ?>
		<div class="main p-3">
			<div class="container">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
					<h4 style=" font-weight: bold;">| Books</h4>
				</div>
				<div style="margin-top:30px">
					<table id="students_table" class="table table-striped" style="width:100%">
						<thead>
							<th>ID</th>
							<th>Book</th>
							<th>Available</th>
							<th>Actions</th>
						</thead>
						<?php
						$sql = "SELECT * FROM books";
						$query = mysqli_query($conn, $sql);
						$counter = 1;
						while ($row = mysqli_fetch_array($query)) {
							$_SESSION['book_Title'] = $row['bookTitle'];
						?>
							<tbody>
								<tr>
									<td><?php echo $counter++; ?></td>
									<td><?php echo $row['bookTitle']; ?></td>
									<td><?php echo $row['available']; ?></td>
									<td>
										<input type="hidden" class="book-id" value="<?php echo $row['bookId']; ?>">
										<input type="hidden" class="book-name" value="<?php echo $row['bookTitle']; ?>">
										<input type="hidden" class="purpose" value="show">
										<a href="lend-student.php" id="show" class="show-in">
											<button class="btn btn-success"><span class="bi-book"></span></button>
										</a>
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