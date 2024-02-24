<?php
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header_new.php";

session_start();
if (isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
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
		<?php include "includes/side_navbar.php"; ?>
		<div class="main p-3">
			<div class="container">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
					<h4 style=" font-weight: bold;">| Fines</h4>
				</div>
				<div style="margin-top:30px">
					<table id="students_table" class="table table-striped" style="width:100%">
						<thead>
							<th>ID</th>
							<!-- <th>Member Name</th> -->
							<!-- <th>Matric Number</th> -->
							<th>Book Name</th>
							<th>Borrow date</th>
							<th>Return Date</th>
							<th>Overdue Charges</th>
						</thead>
						<?php
						$sql = "SELECT * FROM borrow where memberName = '$username'";
						$query = mysqli_query($conn, $sql);
						$counter = 1;
						while ($row = mysqli_fetch_assoc($query)) {
						?>
							<tbody>
								<tr>
									<td><?php echo $counter++; ?></td>
									<!-- <td><?php echo $row['memberName']; ?></td> -->
									<!-- <td><?php echo $row['matricNo']; ?></td> -->
									<td><?php echo $row['bookName']; ?></td>
									<td><?php echo $row['borrowDate']; ?></td>
									<td><?php echo $row['returnDate']; ?></td>
									<td><?php echo $row['fine']; ?></td>
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