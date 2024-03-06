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

    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 900;
        display: none;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    #loading-spinner {
        border: 6px solid #f3f3f3;
        border-top: 6px solid #0e2238;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<body>
    <div class="wrapper">
        <?php include  '../side_navbar.php'; ?>
        <div class="main p-3">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
                    <h4 style="font-weight: bold;">| Borrowed Books</h4>
                </div>
                <div style="margin-top:30px">
                    <table id="borrowed_books_table" class="table table-striped" style="width:100%">
                        <thead>
                            <th>Borrowed Book No.</th>
                            <th>Book Title</th>
                            <th>Borrowed By</th>
                            <th>Borrowed Date</th>
                            <th>Actions</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for View Details -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Book Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="viewBookTitle" class="form-label">Book Title</label>
                        <input type="text" class="form-control" id="viewBookTitle" name="viewBookTitle" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="viewAuthorName" class="form-label">Author Name</label>
                        <input type="text" class="form-control" id="viewAuthorName" name="viewAuthorName" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="viewISBN" class="form-label">ISBN No.</label>
                        <input type="text" class="form-control" id="viewISBN" name="viewISBN" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="viewPublisher" class="form-label">Publisher</label>
                        <input type="text" class="form-control" id="viewPublisher" name="viewPublisher" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="viewCategories" class="form-label">Categories</label>
                        <input type="text" class="form-control" id="viewCategories" name="viewCategories" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="viewLanguage" class="form-label">Language</label>
                        <input type="text" class="form-control" id="viewLanguage" name="viewLanguage" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="viewDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="viewDescription" name="viewDescription" disabled></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="viewLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" id="viewLocation" name="viewLocation" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="viewUserRatings" class="form-label">Ratings</label>
                        <input type="text" class="form-control" id="viewUserRatings" name="viewUserRatings" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loading-overlay">
        <div id="loading-spinner"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
        var dataTable;

        $(document).ready(function() {
            fetchAllBorrowedBooks();
        });

        function fetchAllBorrowedBooks() {
            dataTable = $('#borrowed_books_table').DataTable({
                ajax: {
                    url: '../../api/student/api_borrowed_books.php',
                    type: 'POST',
                    data: {
                        action: 'fetch_all'
                    }
                },
                columns: [{
                        data: 'borrowed_book_no',
                        title: 'Borrowed Book No.'
                    },
                    {
                        data: 'book_title',
                        title: 'Book Title',
                        searchable: true
                    },
                    {
                        data: 'student_name',
                        title: 'Borrowed By',
                    },
                    {
                        data: 'borrowed_date',
                        title: 'Borrowed Date'
                    },
                    {
                        data: null,
                        title: 'Actions',
                        render: function(data, type, row) {
                            return `
            <button class="btn btn-primary" onclick="openViewModal(${row.book_no})">
                <i class="bi bi-question-lg"></i>&nbsp;Details
            </button>
            <button name="submit" class="btn btn-danger" onclick="returnBook(${row.book_no}, '${row.book_title}', '${row.student_no}', '${row.student_name}', '${row.borrowed_book_no}')">
                <i class="bi bi-arrow-return-right"></i>&nbsp;Return
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

        function openViewModal(bookNo) {
            $.ajax({
                url: '../../api/api_books.php',
                type: 'POST',
                data: {
                    action: 'fetch',
                    bookNo: bookNo
                },
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    document.getElementById('viewBookTitle').value = data.book_title;
                    document.getElementById('viewAuthorName').value = data.author_name;
                    document.getElementById('viewISBN').value = data.isbn_no;
                    document.getElementById('viewPublisher').value = data.publisher;
                    document.getElementById('viewCategories').value = data.categories;
                    document.getElementById('viewLanguage').value = data.language;
                    document.getElementById('viewDescription').value = data.description;
                    document.getElementById('viewLocation').value = data.location;
                    document.getElementById('viewUserRatings').value = data.user_ratings;

                    var modal = new bootstrap.Modal(document.getElementById('viewModal'));
                    modal.show();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.log('Response:', xhr.responseText);
                    alert('Error fetching book data');
                }
            });
        }

        function returnBook(bookNo, bookTitle, studentNo, studentName, borrowedBookNo) {
            showLoadingOverlay();
            $.ajax({
                url: '../../api/student/api_borrowed_books.php',
                type: 'POST',
                data: {
                    action: 'return',
                    bookNo: bookNo,
                    bookTitle: bookTitle,
                    studentNo: studentNo,
                    studentName: studentName,
                    borrowedBookNo: borrowedBookNo
                },
                dataType: 'json',
                success: function(data) {
                    hideLoadingOverlay();
                    if (data.error) {
                        alert(data.error);
                    }

                    reloadDataTable();
                },
                error: function(xhr, status, error) {
                    hideLoadingOverlay();
                    console.error('AJAX Error:', status, error);
                    console.log('Response:', xhr.responseText);
                    alert('Error returning book');
                }
            });
        }

        function showLoadingOverlay() {
            $('#loading-overlay').css('display', 'flex');
        }

        function hideLoadingOverlay() {
            $('#loading-overlay').css('display', 'none');
        }
    </script>
</body>