<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "library_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $bookId = $_POST["bookId"];
    $bookTitle = $_POST["bookTitle"];
    $author = $_POST["author"];
    $ISBN = $_POST["ISBN"];
    $bookCopies = $_POST["bookCopies"];
    $publisherName = $_POST["publisherName"];
    $available = $_POST["available"];
    $categories = $_POST["categories"];
    $callNumber = $_POST["callNumber"];

    // Update the book information in the database
    $sql = "UPDATE Books SET
                bookTitle = '$bookTitle',
                author = '$author',
                ISBN = '$ISBN',
                bookCopies = '$bookCopies',
                publisherName = '$publisherName',
                available = '$available',
                categories = '$categories',
                callNumber = '$callNumber'
            WHERE
                bookId = $bookId";

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
