<?php 
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header.php"; 

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
		@font-face {
            font-family: 'Protest Revolution';
            src: url('fonts/ProtestRevolution-Regular.ttf') format('truetype');
        }
    body {
        background-image: url('images/notice3.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        padding: 0;
        margin: 0;
        width:100%;
		
    }
	.table td {
        font-weight: bold;
		
    }

	#addbooktxt{

		font-family: 'Protest Revolution', sans-serif;
		font-size:40px;
	}
</style>

<div class="container">
    <?php include "includes/nav.php"; ?>
    <!-- Navbar ends -->

    <!-- Info alert -->
	<div style="margin-top:70px; color: white; text-align: center; text-shadow: 2px 2px 4px #000000;font-size: 60px; " id="addbooktxt">
    <h1>Add Some Books Here</h1>
</div>


    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php if(isset($error)===true) { ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <strong>Record Deleted Successfully!</strong>
                </div>
                <?php } ?>
                <div class="row justify-content-center">
                    <a href="addbook.php">
                        <button class="btn btn-success col-lg-3 col-md-4 col-sm-11 col-xs-11 button" style="margin-bottom: 5px;margin-left: 15px;">
                            <span class="glyphicon glyphicon-plus-sign"></span> Add Book
                        </button>
                    </a>
                </div>
            </div>

            <!-- Table starts here -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>BookId</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Book Copies</th>
                        <th>Publisher Name</th>
                        <th>Available</th>
                        <th>Categories</th>
                        <th>Call Number</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(isset($_POST['search'])){
                        $text = sanitize(trim($_POST['text']));
                        $sql = "SELECT * FROM books where BookId = $text ";
                        $query = mysqli_query($conn, $sql);
                        while($row=mysqli_fetch_array($query)){ ?>
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
                                    <form method='post' action='bookstable.php'>
                                        <input type='hidden' value="<?php echo $row['bookId']; ?>" name='id'>
                                        <button name='del' type='submit' value='Delete' class='btn btn-warning' onclick='return Delete()'>DELETE</button>
                                    </form>
                                </td>
                            </tr>
                        <?php  }
                    } else {
                        $sql2 = "SELECT * from books";
                        $query2 = mysqli_query($conn, $sql2); 
                        $counter = 1;
                        while ($row = mysqli_fetch_array($query2)) { ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo $row['bookTitle']; ?></td>
                                <td><?php echo $row['author']; ?></td>
                                <td><?php echo $row['ISBN']; ?></td>   
                                <td><?php echo $row['bookCopies']; ?></td>
                                <td><?php echo $row['publisherName']; ?></td>
                                <td><?php echo $row['available']; ?></td>
                                <td><?php echo $row['categories']; ?></td>
                                <td><?php echo $row['callNumber']; ?></td>
                                <td>
                                    <form method='post' action='bookstable.php'>
                                        <input type='hidden' value="<?php echo $row['bookId']; ?>" name='id'>
                                        <button name='del' type='submit' value='Delete' class='btn btn-warning' onclick='return Delete()'>DELETE</button>
                                    </form>
                                </td>
                            </tr>
                        <?php }   
                    } 
                    ?>
                </tbody>
            </table>
            <!-- Table ends here -->
        </div>
    </div>
</div>

<!-- Modals for confirmation messages -->
<div class="modal fade" id="popUpWindow">
    <!-- Modal content goes here -->
</div>

<div class="modal fade" id="info">
    <!-- Modal content goes here -->
</div>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script> 
<script>
    function Delete() {
        return confirm('Would you like to delete the book?');
    }
</script>
