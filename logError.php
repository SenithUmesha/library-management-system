<?php

// Receive error data from AJAX request
$errorData = json_decode(file_get_contents('php://input'), true);

// Log error data to a file
$logFile = 'error.log'; // File path where you want to store error logs

// Format error message
$errorMsg = date('Y-m-d H:i:s') . ': ' . $errorData['error'] . PHP_EOL;

// Append error message to log file
file_put_contents($logFile, $errorMsg, FILE_APPEND);

// Respond with success message
echo json_encode(['message' => 'Error logged successfully']);

?>
