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
        var currentReservationNo;
        var dataTable;

        $(document).ready(function() {
            fetchAllReservations();
        });

        function fetchAllReservations() {
            dataTable = $('#reservations_table').DataTable({
                ajax: {
                    url: '../../api/api_reservations.php',
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
    </script>
</body>