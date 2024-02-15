<?php
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header_new.php";

if(isset($_POST['del'])){
    $id = sanitize(trim($_POST['id']));
    $sql_del = "DELETE from books where BookId = $id"; 
    $error = false;
    $result = mysqli_query($conn,$sql_del);
    if ($result) {
        $error = true;
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
	body {
		padding: 0;
		margin: 0;
		width: 100%;
	}

</style>

<div class="wrapper"> 
	<?php include "includes/side_navbar.php"; ?>
	<div class="main p-3">
		<div class="container">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
				<h4 style=" font-weight: bold;">| Books</h4>
				<button type="button" class="btn btn-success" onclick="addRow(this)"><span class="bi-plus"></span>&nbsp;Book</button>
			</div>
			<div style="margin-top:30px">
				<table id="students_table" class="table table-striped" style="width:100%">
					<thead>
                        <th>BookId</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Book Copies</th>
                        <th>Publisher Name</th>
                        <th id='able'>Available</th>
                        <th>Categories</th>
                        <th>Call Number</th>
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
                                <td><?php echo $row['categories']; ?></td>
                                <td><?php echo $row['callNumber']; ?></td>
	
								<td>
									<form action="bookstable_new.php" method="post">
                                    <input type='hidden' value="<?php echo $row['bookId']; ?>" name='id'>
									
									<button class="btn btn-primary">
   									<a href="edit.php?id=<?php echo $row['bookId']; ?>">
       								<span class="bi bi-pencil"></span>
    								</a>
									</button>


										<button onclick='return Delete()' name="del" class="btn btn-danger"><span class="bi-trash"></span></button>
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

</html>