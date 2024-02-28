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
                    <h4 style=" font-weight: bold;">| Returned Books</h4>

                </div>
                <div style="margin-top:30px">
                    <table id="students_table" class="table table-striped" style="width:100%">
                        <thead>
                            <th>ID</th>
                            <th>Book Name</th>
                            <th>Member Name</th>
                            <th>Matric Number</th>
                            <th>Returned Date</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM returned_books";
                            $query = mysqli_query($conn, $sql);
                            $counter = 1;
                            while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?php echo $counter++; ?></td>
                                    <td><?php echo $row['Book Name']; ?></td>
                                    <td><?php echo $row['Member Name']; ?></td>
                                    <td><?php echo $row['Matric Number']; ?></td>
                                    <td><?php echo $row['Returned Date']; ?></td>
                                    <td>
                                        <form action="view/admin/returned_books.php" method="post" style="display: inline;">
                                            <input type="hidden" value="<?php echo $row['ID']; ?>" name="id">
                                            <button class="btn btn-primary" name="edit"><span class="bi-pencil"></span></button>
                                        </form>
                                        <form action="view/admin/returned_books.php" method="post" style="display: inline;">
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