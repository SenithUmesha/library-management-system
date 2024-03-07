<?php
require '../../util/snippet.php';
require '../../includes/db_conn.php';
include '../header.php';

session_start();

if (!isset($_SESSION['id']) || $_SESSION['id'] === null) {
    header("Location: ../../index.php");
    exit();
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
        <?php include  '../side_navbar.php'; ?>
        <div class="main p-3">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
                    <h4 style="font-weight: bold;">| Reservations</h4>
                </div>
                <div style="margin-top:30px">
                    <table id="reservations_table" class="table table-striped" style="width:100%">
                        <thead>
                            <th>Reservation No.</th>
                            <th>Book Title</th>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Reserved Date</th>
                            <th>Actions</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        var dataTable;

        $(document).ready(function() {
            fetchAllReservations();
        });

        function fetchAllReservations() {
            dataTable = $('#reservations_table').DataTable({
                ajax: {
                    url: '../../api/student/api_reservations.php',
                    type: 'POST',
                    data: {
                        action: 'fetch_all'
                    }
                },
                columns: [{
                        data: 'reservation_no',
                        title: 'Reservation No.'
                    },
                    {
                        data: 'book_title',
                        title: 'Book Title',
                        searchable: true
                    },
                    {
                        data: 'student_name',
                        title: 'Student Name'
                    },
                    {
                        data: 'email',
                        title: 'Email'
                    },
                    {
                        data: 'reserved_date',
                        title: 'Reserved Date'
                    },
                    {
                        data: null,
                        title: 'Actions',
                        render: function(data, type, row) {
                            return `
                                <button name="submit" class="btn btn-danger" onclick="confirmDelete(${row.reservation_no})">
                                    <span class="bi-trash">&nbsp;Delete
                                </button>`;
                        }
                    }
                ],
                lengthMenu: [8, 25, 50, 100],
                paging: true,
                pageLength: 8,
                pagingType: 'full_numbers'
            });
        }

        function reloadDataTable() {
            dataTable.ajax.reload();
        }

        function confirmDelete(reservationNo) {
            if (confirm('Are you sure you want to delete this reservation?')) {
                $.ajax({
                    url: '../../api/student/api_reservations.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        reservationNo: reservationNo
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        }
                        reloadDataTable();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        console.log('Response:', xhr.responseText);
                        alert('Error confirming delete');
                    }
                });
            }
        }
    </script>
</body>