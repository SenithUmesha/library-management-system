<?php
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header_new.php";

if(isset($_POST['del'])){

	$id = sanitize(trim($_POST['id']));
    // echo $id;

	$sql_del = "DELETE from admin where adminId = $id"; 
        $error = false;

	$result = mysqli_query($conn,$sql_del);
			if ($result)
			{
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
				<h4 style=" font-weight: bold;">| Admins</h4>
				<button type="button" class="btn btn-success" onclick="addRow(this)"><span class="bi-plus"></span>&nbsp;Admin</button>
			</div>
			<div style="margin-top:30px">
				<table id="students_table" class="table table-striped" style="width:100%">
					<thead>
                    <th>AdminID</th>
			  <th>AdminName</th>
			  <th>Password</th>
			  <th>Username</th>
			  <th>Email</th>
			   <th>Delete</th>
					</thead>
					<?php
					$sql = "SELECT * from admin";
					$query = mysqli_query($conn, $sql);
					$counter = 1;
					while ($row = mysqli_fetch_assoc($query)) {
					?>
	   <tbody>
	   <td> <?php echo $counter++ ?></td>
	   <td> <?php echo $row['adminName']?></td>
	   <td> <?php echo $row['password']?></td>
	   <td> <?php echo $row['username']?></td>	
	   <td> <?php echo $row['email']?></td>
	   <form method='post' action='users.php'>
	   <input type='hidden' value="<?php echo $row['adminId']; ?>" name='id'>
	   <td><button name="del" class="btn btn-danger"><span class="bi-trash"></span></button></td>
	   </form>
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