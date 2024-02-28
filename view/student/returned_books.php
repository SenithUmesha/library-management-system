<?php
require '../../util/snippet.php';
require '../../includes/db_conn.php';
include '../header.php';
include 'chatbot.php';

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

    .rating {
        text-align: center;
        position: relative;
        display: inline-block;
    }

    .rating .star {
        display: inline-block;
        font-size: 2em;
        cursor: pointer;
        color: #ccc;
        transition: color 0.3s;
    }

    .rating .star.rated,
    .rating .star:hover {
        color: gold;
    }
</style>

<body>
    <div class="wrapper">
        <?php include  '../side_navbar.php'; ?>
        <div class="main p-3">
            <div class="container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
                    <h4 style="font-weight: bold;">| Returned Books</h4>
                </div>
                <div style="margin-top:30px">
                    <table id="students_table" class="table table-striped" style="width:100%">
                        <thead>
                            <th>ID</th>
                            <th>Book Name</th>
                            <th>Member Name</th>
                            <th>Matric Number</th>
                            <th>Returned Date</th>
                            <th>Rating</th>
                            <th>Add Rating</th>
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
                                    <td id="rating_<?php echo $row['ID']; ?>"><?php echo $row['Rating'] ? $row['Rating'] . ' stars' : 'Not rated'; ?></td>
                                    <td><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ratingModal<?php echo $row['ID']; ?>">Add Rating</button></td>
                                </tr>

                                <!-- Rating Modal -->
                                <div class="modal fade" id="ratingModal<?php echo $row['ID']; ?>" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="ratingModalLabel">Rate Book: <?php echo $row['Book Name']; ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="rating" id="rating_<?php echo $row['ID']; ?>">
                                                    <span class="star" data-rating="5">&#9733;</span>
                                                    <span class="star" data-rating="4">&#9733;</span>
                                                    <span class="star" data-rating="3">&#9733;</span>
                                                    <span class="star" data-rating="2">&#9733;</span>
                                                    <span class="star" data-rating="1">&#9733;</span>
                                                    <input type="hidden" name="book_id" class="book-id" value="<?php echo $row['ID']; ?>">
                                                    <input type="hidden" name="rating" class="rating-value" value="<?php echo $row['Rating']; ?>">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary" onclick="submitRating(<?php echo $row['ID']; ?>)">Submit Rating</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

        function submitRating(bookID) {
            var rating = $('#ratingModal' + bookID).find('.star.rated').length;
            $.ajax({
                url: 'update_rating.php',
                type: 'POST',
                data: {
                    bookID: bookID,
                    rating: rating
                },
                success: function(response) {
                    $('#rating_' + bookID).text(rating + ' stars');
                    alert("Rating updated successfully!");
                    location.reload();
                    $('#ratingModal' + bookID).modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert("An error occurred while updating the rating.");
                }
            });
        }
        // Add click event listeners to stars
        $('.star').click(function() {
            var rating = $(this).attr('data-rating');
            $(this).parent().find('.star').removeClass('rated');
            $(this).addClass('rated').prevAll().addClass('rated');
        });
    </script>
</body>