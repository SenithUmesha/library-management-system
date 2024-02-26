<?php
require '../../includes/snippet.php';
require '../../includes/db-inc.php';
include '../header.php';

session_start();

if (isset($_SESSION['name'])) {
    $name = $_SESSION['name'];
}


if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}
?>

<style>
    body {
        background-color: #f8f9fa;
        color: #333;
        padding: 0;
        margin: 0;
        width: 100%;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        margin-top: 60px;
    }

    .card {
        border: none;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #007bff;
        color: #fff;
        padding: 20px;
        border-radius: 5px 5px 0 0;
    }

    .card-title {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
    }

    .card-body {
        padding: 30px;
    }

    table {
        width: 100%;
    }

    th {
        width: 30%;
        text-align: left;
    }

    td {
        width: 70%;
        text-align: left;
    }

    th,
    td {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .name-highlight {
        color: #dc3545;
    }
</style>

<body>
    <div class="wrapper">
        <?php include  '../side_navbar.php'; ?>
        <div class="main p-3">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
                    <h4 style="font-weight: bold;">| Profile</h4>
                </div>
                <div style="margin-top:30px">
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <?php
                                $sql = "SELECT * FROM students WHERE username = '$username'";
                                $query = mysqli_query($conn, $sql);
                                if ($query && mysqli_num_rows($query) > 0) {
                                    while ($row = mysqli_fetch_assoc($query)) {
                                ?>
                                        <tbody>
                                            <tr>
                                                <th class="name-highlight">Student Name</th>
                                                <td><?php echo $row['student_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Username</th>
                                                <td><?php echo $row['username']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Admission ID</th>
                                                <td><?php echo $row['admission_id']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td><?php echo $row['email']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Class</th>
                                                <td><?php echo $row['class']; ?></td>
                                            </tr>
                                        </tbody>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>No data found</td></tr>";
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#students_table').DataTable();
        });
    </script>
</body>