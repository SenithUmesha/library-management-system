<?php

require 'includes/db-inc.php';

// Receive data from AJAX request
$data = json_decode(file_get_contents('php://input'), true);

// Validation (adapt based on your data types)
if (!is_numeric($data['bookId'])) {
  error_log("Invalid book ID");
  die("Invalid book ID");
}

// Prepare SQL statement
$sql = "UPDATE books SET bookTitle = ?, author = ?, ISBN = ?, bookCopies = ?, publisherName = ?, available = ?, categories = ?, callNumber = ? WHERE bookId = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
  error_log("Prepare failed: " . mysqli_error($conn));
  die("Prepare failed: " . mysqli_error($conn));
}

// Bind parameters
mysqli_stmt_bind_param($stmt, "ssssssssi", $data['bookTitle'], $data['author'], $data['ISBN'], $data['bookCopies'], $data['publisherName'], $data['available'], $data['categories'], $data['callNumber'], $data['bookId']);

// Execute statement
if (!mysqli_stmt_execute($stmt)) {
  error_log("Update failed: " . mysqli_stmt_error($stmt));
  die("Update failed: " . mysqli_stmt_error($stmt));
}

// Respond with success message or error information
echo json_encode(["message" => "Data updated successfully"]);

// Close statement
mysqli_stmt_close($stmt);

// Close connection
mysqli_close($conn);

?>
