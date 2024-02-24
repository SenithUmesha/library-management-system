<?php
require 'includes/db-inc.php';

if (isset($_POST['bookID'], $_POST['rating'])) {
    $bookID = $_POST['bookID'];
    $rating = $_POST['rating'];

    // Update the rating in the database
    $sql = "UPDATE returned_books SET Rating = '$rating' WHERE ID = '$bookID'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        // Rating updated successfully
        echo "success";
    } else {
        // Error occurred while updating rating
        echo "error";
    }
} else {
    // Invalid request
    echo "invalid_request";
}
?>
