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
                            <th>Book No.</th>
                            <th>Book Title</th>
                            <th>Student No.</th>
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

    <!-- Modal for Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Reservation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" onsubmit="return validateEditForm()">
                        <div class="mb-3">
                            <label for="editReservationNo" class="form-label">Reservation No.</label>
                            <input type="text" class="form-control" id="editReservationNo" name="editReservationNo" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editBookNo" class="form-label">Book No.</label>
                            <input type="text" class="form-control" id="editBookNo" name="editBookNo" required>
                        </div>
                        <div class="mb-3">
                            <label for="editBookTitle" class="form-label">Book Title</label>
                            <input type="text" class="form-control" id="editBookTitle" name="editBookTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStudentNo" class="form-label">Student No.</label>
                            <input type="text" class="form-control" id="editStudentNo" name="editStudentNo" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editStudentName" class="form-label">Student Name</label>
                            <input type="text" class="form-control" id="editStudentName" name="editStudentName" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="text" class="form-control" id="editEmail" name="editEmail" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editReservedDate" class="form-label">Reserved Date</label>
                            <input type="text" class="form-control" id="editReservedDate" name="editReservedDate" required>
                        </div>
                        <button type="submit" class="btn btn-primary" onclick="saveEditChanges()">Save</button>
                    </form>
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
                        data: 'book_no',
                        title: 'Book No.',

                    },
                    {
                        data: 'book_title',
                        title: 'Book Title',
                        searchable: true
                    },
                    {
                        data: 'student_no',
                        title: 'Student No.'
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
                                <button class="btn btn-primary" onclick="openEditModal(${row.reservation_no})">
                                    <span class="bi-pencil">&nbsp;Edit
                                </button>
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

        function validateEditForm() {
            var updatedBookNo = document.getElementById('editBookNo').value;
            var updatedBookTitle = document.getElementById('editBookTitle').value;
            var updatedReservedDate = document.getElementById('editReservedDate').value;

            if (!updatedBookNo || !updatedBookTitle || !updatedReservedDate) {
                return false;
            }

            return true;
        }

        function openEditModal(reservationNo) {
            currentReservationNo = reservationNo;

            $.ajax({
                url: '../../api/api_reservations.php',
                type: 'POST',
                data: {
                    action: 'fetch',
                    reservationNo: reservationNo
                },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    document.getElementById('editReservationNo').value = data.reservation_no;
                    document.getElementById('editBookNo').value = data.book_no;
                    document.getElementById('editBookTitle').value = data.book_title;
                    document.getElementById('editStudentNo').value = data.student_no;
                    document.getElementById('editStudentName').value = data.student_name;
                    document.getElementById('editEmail').value = data.email;
                    document.getElementById('editReservedDate').value = data.reserved_date;

                    var modal = new bootstrap.Modal(document.getElementById('editModal'));
                    modal.show();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.log('Response:', xhr.responseText);
                    alert('Error fetching student data');
                }
            });
        }

        function saveEditChanges() {
            var reservationNo = currentReservationNo;
            var updatedBookNo = document.getElementById('editBookNo').value;
            var updatedBookTitle = document.getElementById('editBookTitle').value;
            var updatedStudentNo = document.getElementById('editStudentNo').value;
            var updatedStudentName = document.getElementById('editStudentName').value;
            var updatedEmail = document.getElementById('editEmail').value;
            var updatedReservedDate = document.getElementById('editReservedDate').value;

            if (!validateEditForm()) {
                return;
            }

            $.ajax({
                url: '../../api/api_reservations.php',
                type: 'POST',
                data: {
                    action: 'update',
                    reservationNo: reservationNo,
                    updatedBookNo: updatedBookNo,
                    updatedBookTitle: updatedBookTitle,
                    updatedStudentNo: updatedStudentNo,
                    updatedStudentName: updatedStudentName,
                    updatedEmail: updatedEmail,
                    updatedReservedDate: updatedReservedDate
                },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    console.log('Changes saved successfully:', data);

                    reloadDataTable();

                    var modal = new bootstrap.Modal(document.getElementById('editModal'));
                    modal.hide();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.log('Response:', xhr.responseText);
                    alert('Error saving changes');
                }
            });
        }

        function confirmDelete(reservationNo) {
            if (confirm('Are you sure you want to delete this reservation?')) {
                $.ajax({
                    url: '../../api/api_reservations.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        reservationNo: reservationNo
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                        } else if (data.success) {
                            reloadDataTable();
                        }
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