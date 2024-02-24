<?php
require 'includes/snippet.php';
require 'includes/db-inc.php';
include "includes/header_new.php";

session_start();

if (isset($_POST['submit'])) {
    $id = trim($_POST['del_btn']);
    $sql = "DELETE from students where studentId = '$id' ";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo "<script>alert('Student Deleted!')</script>";
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
        <?php include "includes/side_navbar.php"; ?>
        <div class="main p-3">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
                    <h4 style=" font-weight: bold;">| Reservations</h4>

                </div>
                <div style="margin-top:30px">
                    <table id="students_table" class="table table-striped" style="width:100%">
                        <thead>
                            <th>Reservation ID</th>
                            <th>Book ID</th>
                            <th>Book Name</th>
                            <th>Student Name</th>
                            <th>ISBN</th>
                            <th>Reserved Date</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM reservations";
                            $query = mysqli_query($conn, $sql);
                            $counter = 1;
                            while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?php echo $row['reservation_id']; ?></td>
                                    <td><?php echo $row['book_id']; ?></td>
                                    <td><?php echo $row['book_name']; ?></td>
                                    <td><?php echo $row['student_name']; ?></td>
                                    <td><?php echo $row['isbn']; ?></td>
                                    <td><?php echo $row['reserved_date']; ?></td>
                                    <td>
                                        <form action="returned_books.php" method="post" style="display: inline;">
                                            <input type="hidden" value="<?php echo $row['ID']; ?>" name="id">
                                            <button class="btn btn-primary" name="edit"><span class="bi-pencil"></span></button>
                                        </form>
                                        <form action="returned_books.php" method="post" style="display: inline;">
                                            <input type="hidden" value="<?php echo $row['ID']; ?>" name="id">
                                            <button name="del" class="btn btn-danger"><span class="bi-trash"></span></button>
                                        </form>
                                    </td>

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