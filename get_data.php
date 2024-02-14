<?php
require 'includes/db-inc.php';

// Get offset and limit parameters
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

// Query to fetch data from the database
$sql = "SELECT * FROM books LIMIT $offset, $limit";
$result = mysqli_query($conn, $sql);

// Check if there are rows returned
if (mysqli_num_rows($result) > 0) {
    // Start building the HTML content
    $html = '';

    // Loop through each row and append it to the HTML content
    while ($row = mysqli_fetch_assoc($result)) {
        $html .= '<tr>';
        $html .= '<td>' . $row['bookId'] . '</td>';
        $html .= '<td>' . $row['bookTitle'] . '</td>';
        $html .= '<td>' . $row['author'] . '</td>';
        $html .= '<td>' . $row['ISBN'] . '</td>';
        $html .= '<td>' . $row['bookCopies'] . '</td>';
        $html .= '<td>' . $row['publisherName'] . '</td>';
        $html .= '<td>' . $row['available'] . '</td>';
        $html .= '<td>' . $row['categories'] . '</td>';
        $html .= '<td>' . $row['callNumber'] . '</td>';
        // Add delete button
        $html .= '<td>';
        $html .= '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">';
        $html .= '<input type="hidden" value="' . $row['bookId'] . '" name="id">';
        $html .= '<button name="del" type="submit" class="btn btn-warning" onclick="return Delete()">';
        $html .= '<img src="images/bin.png" alt="Delete">';
        $html .= '</button>';
        $html .= '</form>';
        $html .= '</td>';
        // Add edit button
        $html .= '<td><a href="edit.php?id=' . $row['bookId'] . '"><span class="glyphicon glyphicon-pencil"></span></a></td>';
        $html .= '</tr>';
    }

    // Output the HTML content
    echo $html;
} else {
    // No rows found, output a message
    echo '<tr><td colspan="11">No data found</td></tr>';
}

// Close the database connection
mysqli_close($conn);
?>
