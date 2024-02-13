<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the JSON string sent from the client-side
    $rowDataJson = $_POST['rowData'];

    // Decode the JSON string to an associative array
    $rowData = json_decode($rowDataJson, true);

    // Check if JSON decoding was successful
    if ($rowData === null) {
        echo "Error decoding JSON data.";
    } else {
        // Ensure that the rowData array has at least 9 elements
        if (count($rowData) >= 9) {
            // Extract individual values from the rowData array
            $bookId = $rowData[0];
            $bookTitle = $rowData[1];
            $author = $rowData[2];
            $isbn = $rowData[3];
            $bookCopies = $rowData[4];
            $publisherName = $rowData[5];
            $available = $rowData[6];
            $categories = $rowData[7];
            $callNumber = $rowData[8];

            // Example: Update the database with the received data
            // Assuming you have a database connection already established
            require 'includes/db-inc.php'; // Include your database connection script

            // Corrected SQL query and added semicolon at the end of the require statement
            $sql = "UPDATE books SET bookTitle = ?, author = ?, ISBN = ?, bookCopies = ?, publisherName = ?, available = ?, categories = ?, callNumber = ? WHERE bookId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss", $bookTitle, $author, $isbn, $bookCopies, $publisherName, $available, $categories, $callNumber, $bookId);

            if ($stmt->execute()) {
                // Data saved successfully
                echo "Data saved successfully.";
            } else {
                // Error occurred
                echo "Error occurred while saving data.";
            }
        } else {
            // Handle the case where rowData array doesn't have enough elements
            echo "Invalid rowData array: Expected at least 9 elements.";
        }
    }
} else {
    // Invalid request method
    echo "Invalid request method.";
}
?>
