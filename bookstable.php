<?php 
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header.php"; 

// Pagination
$results_per_page = 10;
$sql = "SELECT COUNT(*) AS total FROM books";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_records = $row['total'];
$total_pages = ceil($total_records / $results_per_page);

if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

$this_page_first_result = ($page - 1) * $results_per_page;

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <!-- Include any additional stylesheets or scripts here -->
</head>
<body>
<style>
    		@font-face {
            font-family: 'Protest Revolution';
            src: url('fonts/ProtestRevolution-Regular.ttf') format('truetype');
        }
        body {
            background-image: url('images/.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            padding: 0;
            margin: 0;
			width:100%;
         
        }



        .search-container {
        margin-bottom: 10px;
        position: relative;
        width: 100%;
        max-width: 400px; /* Adjust the max-width as needed */
    }

    /* Search icon styling */
    .search-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 10px;
        width: 20px; /* Adjust icon size as needed */
        height: auto;
    }

    /* Search input styling */
    #searchInput {
        padding-left: 35px; /* Ensure enough space for the icon */
        width: 100%;
        box-sizing: border-box; /* Ensure padding and border are included in the width */
        border: 1px solid #ccc; /* Add border style */
        border-radius: 5px; /* Add border radius for rounded corners */
        height: 40px; /* Adjust input height as needed */
        font-size: 16px; /* Adjust font size as needed */
    }



    .available-yes {
         
            background-image:url('images/yes.png');
            background-repeat:no-repeat;
            border-radius: 10px;
            padding: 20px 4px 5px;  /* Adjust padding as needed */
            width: 10px;
            background-position: center;
        }

        .available-no{

            background-image:url('images/cancel.png');
            background-repeat:no-repeat;
            border-radius: 10px;
            padding: 20px 4px 5px;  /* Adjust padding as needed */
            width: 10px;
            background-position: center;

        }

        table td{

            font-weight : bold;
        }

        /* Add margin to table cells */
.table-striped  {
    margin-right: 10px; /* Adjust the margin value as needed */
}

/* Add margin to table headers */
.table-striped th {
    margin-right: 100px; /* Adjust the margin value as needed */
}

.table-striped td,
.table-striped th {
    padding-right: 40px; /* Adjust the padding value as needed */
}


.table-striped {
    border-spacing: 20px; /* Adjust the gap value as needed */
}








    </style>
<div class="container">
    <?php include "includes/nav.php"; ?>
    <!-- Navbar ends -->

    <!-- Info alert -->
    <div style="margin-top:70px; color: black; text-align: center; text-shadow: 2px 2px 4px #000000;font-size: 60px; font-family:Protest Revolution"; id="addbooktxt">
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
            <table class="table table-striped" id="bookTable">
                <div class="search-container">
                    <img class="search-icon" src="images/searchIcon.png" alt="Search Icon">
                    <input type="search" id="searchInput" placeholder="Search by Title">
                </div>
                <thead>
                    <tr>
                        <th>BookId</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Book Copies</th>
                        <th>Publisher Name</th>
                        <th id='able'>Available</th>
                        <th>Categories</th>
                        <th>Call Number</th>
                        <th>Delete</th>
                        <th>Edit</th>
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
                                    <form method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                                        <input type='hidden' value="<?php echo $row['bookId']; ?>" name='id'>
                                        <button name='del' type='submit'  onclick='return Delete()'>
                                      
                                        </button>

                                    </form>
                                </td>
                            </tr>
                        <?php  }
                    } else {
                        $sql2 = "SELECT * from books LIMIT $this_page_first_result, $results_per_page";
                        $query2 = mysqli_query($conn, $sql2); 
                        $counter = 1;
                        while ($row = mysqli_fetch_array($query2)) { ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td class="editable" contenteditable="false"><?php echo $row['bookTitle']; ?></td>
                                <td class="editable" contenteditable="false"><?php echo $row['author']; ?></td>
                                <td class="editable" contenteditable="false"><?php echo $row['ISBN']; ?></td>   
                                <td class="editable" contenteditable="false"><?php echo $row['bookCopies']; ?></td>
                                <td class="editable" contenteditable="false"><?php echo $row['publisherName']; ?></td>
                                <td class="editable" contenteditable="false"><?php echo $row['available']; ?></td>
                                <td class="editable" contenteditable="false"><?php echo $row['categories']; ?></td>
                                <td class="editable" contenteditable="false"><?php echo $row['callNumber']; ?></td>
                                <td>
                                    <form method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                                        <input type='hidden' value="<?php echo $row['bookId']; ?>" name='id'>
                                        <button name='del' type='submit'  class='btn btn-warning' onclick='return Delete()'> 
                                        <img src='images/bin.png' alt='Delete'>
                                    </button>
                                    </form>
                                </td>
                                <td><a href='edit.php?id=<?php echo $row['bookId']; ?>'><span class="glyphicon glyphicon-pencil"></span></a></td>



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

<!-- Pagination -->
<div class="pagination">
  <?php
    // Check if the current page is greater than 1, and if so, add a "Previous" link
    if ($page > 1) {
      echo "<a href='?page=".($page - 1)."' class='btn btn-warning'>Previous</a>";
    }

    // Loop through the total number of pages and create links for each one
    for ($i = 1; $i <= $total_pages; $i++) {
      // If the current page is the same as the loop iteration, make the link active
      if ($i == $page) {
        echo "<a href='?page=".$i."' class='btn btn-secondary active'>".$i."</a>";
      } else {
        echo "<a href='?page=".$i."' class='btn btn-secondary'>".$i."</a>";
      }
    }

    // Check if the current page is less than the total number of pages, and if so, add a "Next" link
    if ($page < $total_pages) {
      echo "<a href='?page=".($page + 1)."' class='btn btn-warning'>Next</a>";
    }
  ?>
</div>




<!-- Modals for confirmation messages -->
<div class="modal fade" id="popUpWindow">
    <!-- Modal content goes here -->
</div>

<div class="modal fade" id="info">
    <!-- Modal content goes here -->
</div>

<script>
        function Delete() {
            return confirm('Would you like to delete the book?');
        }

        function Edit(button) {
            var row = button.parentNode.parentNode;
            var cells = row.getElementsByTagName("td");

            for (var i = 0; i < cells.length - 1; i++) { // excluding the last cell with the button
                cells[i].setAttribute("contenteditable", "true");
                cells[i].classList.add("editable");
            }
            button.textContent = "Save";
            // button.setAttribute("onclick", "Save(this)");
            button.name="save";
        }

        document.getElementById("searchInput").addEventListener("keyup", function() {
            let input = this.value.toLowerCase();
            let rows = document.getElementById("bookTable").getElementsByTagName("tbody")[0].getElementsByTagName("tr");

            for (let row of rows) {
                let title = row.getElementsByTagName("td")[1].textContent.toLowerCase();
                if (title.includes(input)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        });

        var availableCells = document.querySelectorAll("#bookTable tbody tr td:nth-child(7)");

        // Loop through each cell and modify its content and style
        availableCells.forEach(function(cell) {
            // Get the current content of the cell
            var currentContent = cell.textContent.trim();

            // Check if the content is 'YES'
            if (currentContent === 'YES') {
                // Remove the text content of the cell
                cell.textContent = '';
                // Add a class to the cell for styling
                cell.classList.add('available-yes');
            } else {
                cell.textContent='';
                cell.classList.add('available-no');
            }
        });
    </script>


</body>
</html>
