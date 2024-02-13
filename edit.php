<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "library_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_GET['id'])) {
    $bookId = $_GET['id'];
    
    // Prepare statement
    $sql = "SELECT * FROM Books WHERE bookId = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    // Bind parameter
    mysqli_stmt_bind_param($stmt, "i", $bookId);
    
    // Execute statement
    mysqli_stmt_execute($stmt);
    
    // Get result
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Error retrieving book information: " . mysqli_error($conn));
    }

    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Display the form with the book information for editing
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Book</title>
        </head>
        <body>
            <h2>Edit Book</h2>
            <form action="save_edit.php" method="post">
                <input type="hidden" name="bookId" value="<?php echo $row['bookId']; ?>">
                <label for="bookTitle">Book Title:</label>
                <input type="text" name="bookTitle" value="<?php echo $row['bookTitle']; ?>"><br><br>
                <label for="author">Author:</label>
                <input type="text" name="author" value="<?php echo $row['author']; ?>"><br><br>
                <label for="ISBN">ISBN:</label>
                <input type="text" name="ISBN" value="<?php echo $row['ISBN']; ?>"><br><br>
                <label for="bookCopies">Book Copies:</label>
                <input type="text" name="bookCopies" value="<?php echo $row['bookCopies']; ?>"><br><br>
                <label for="publisherName">Publisher Name:</label>
                <input type="text" name="publisherName" value="<?php echo $row['publisherName']; ?>"><br><br>
                <label for="available">Available:</label>
                <input type="text" name="available" value="<?php echo $row['available']; ?>"><br><br>
                <label for="categories">Categories:</label>
                <input type="text" name="categories" value="<?php echo $row['categories']; ?>"><br><br>
                <label for="callNumber">Call Number:</label>
                <input type="text" name="callNumber" value="<?php echo $row['callNumber']; ?>"><br><br>
                <input type="submit" value="Save Changes">
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Book not found.";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
