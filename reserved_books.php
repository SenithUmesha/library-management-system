<?php
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header_new.php";

if (isset($_POST['submit'])) {
    $id = trim($_POST['del_btn']);
    $sql = "DELETE from students where studentId = '$id' ";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo "<script>alert('Student Deleted!')</script>";
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

<body>
    <div class="wrapper">
        <?php include "includes/side_navbar2.php"; ?>
        <div class="main p-3">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
                    <h4 style=" font-weight: bold;">| Reserved Books</h4>
                    
                </div>
                <div style="margin-top:30px">
                    <table id="students_table" class="table table-striped" style="width:100%">
                        <thead>
                            <th>ID</th>
                            <th>Book ID</th>
                            <th>Book Name</th>
                            <th>Member Name</th>
                            <th>Matric Number</th>
                            <th>Reserved Date</th>
                            
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM reservations";
                            $query = mysqli_query($conn, $sql);
                            $counter = 1;
                            while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo $row['Book ID']; ?></td>
                                    <td><?php echo $row['Book Name']; ?></td>
                                    <td><?php echo $row['Member Name']; ?></td>
                                    <td><?php echo $row['Matric Number']; ?></td>
                                    <td><?php echo $row['Reserved Date']; ?></td>
									

                                </tr>
                            <?php } ?>
                        </tbody>
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
