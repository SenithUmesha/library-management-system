<?php
require '../includes/db_conn.php';
require_once '../vendor/autoload.php';

if (isset($_POST['action']) && $_POST['action'] === 'students') {
    generateStudents($conn);
} else if ($_POST['action'] === 'admins') {
    generateAdmins($conn);
} else if ($_POST['action'] === 'books') {
    generateBooks($conn);
} else if ($_POST['action'] === 'borrowed_books') {
    generateBorrowedBooks($conn);
} else if ($_POST['action'] === 'returned_books') {
    generateReturnedBooks($conn);
} else if ($_POST['action'] === 'fines') {
    generateFines($conn);
} else if ($_POST['action'] === 'reservations') {
    generateReservations($conn);
} else {
    echo 'Invalid action';
}

function generateStudents($conn)
{
    $sql = "SELECT * FROM students";
    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    mysqli_close($conn);

    $html = '
    <html>
    <head>
        <title>Library Management System Report - Ananda College, Galle</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            h2 {
                text-align: center;
            }
            h4 {
                font-weight: normal;
                text-align: center;
                margin-bottom: 40px;
                margin-top: -10px;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>LMS Report - Students</h2>
            <h4>Ananda College, Galle</h4>
            <table>
                <thead>
                    <tr>
                            <th>Student No.</th>
                            <th>Student Name</th>
                            <th>Admission ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Class</th>
                        </tr>
                    </thead>
                    <tbody>';

    foreach ($data as $item) {
        $html .= '
            <tr>
                <td>' . $item['student_no'] . '</td>
                <td>' . $item['student_name'] . '</td>
                <td>' . $item['admission_id'] . '</td>
                <td>' . $item['username'] . '</td>
                <td>' . $item['email'] . '</td>
                <td>' . $item['class'] . '</td>
            </tr>';
    }

    $html .= '
                    </tbody>
                </table>
            </div>
        </body>
        </html>';

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $pdfFilePath = 'students_report.pdf';
    file_put_contents($pdfFilePath, $pdfOutput);

    echo '../../downloads/students_report.pdf';
}

function generateAdmins($conn)
{
    $sql = "SELECT * FROM admins";
    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    mysqli_close($conn);

    $html = '
        <html>
        <head>
            <title>Library Management System Report - Ananda College, Galle</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                h2 {
                    text-align: center;
                }
                h4 {
                    font-weight: normal;
                    text-align: center;
                    margin-bottom: 40px;
                    margin-top: -10px;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>LMS Report - Admins</h2>
                <h4>Ananda College, Galle</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Admin No.</th>
                            <th>Admin Name</th>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>';

    foreach ($data as $item) {
        $html .= '
            <tr>
                <td>' . $item['admin_no'] . '</td>
                <td>' . $item['admin_name'] . '</td>
                <td>' . $item['username'] . '</td>
                <td>' . $item['email'] . '</td>
            </tr>';
    }

    $html .= '
                    </tbody>
                </table>
            </div>
        </body>
        </html>';

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $pdfFilePath = 'admins_report.pdf';
    file_put_contents($pdfFilePath, $pdfOutput);

    echo '../../downloads/admins_report.pdf';
}

function generateBooks($conn)
{
    $sql = "SELECT * FROM books";
    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    mysqli_close($conn);

    $html = '
        <html>
        <head>
            <title>Library Management System Report - Ananda College, Galle</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                h2 {
                    text-align: center;
                }
                h4 {
                    font-weight: normal;
                    text-align: center;
                    margin-bottom: 40px;
                    margin-top: -10px;
                }
                .container {
                    max-width: 100%;
                    margin: 0 auto;
                    padding: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>LMS Report - Books</h2>
                <h4>Ananda College, Galle</h4>
                <table>
                    <thead>
                        <tr>
                        <th>Book No.</th>
                        <th>Book Title</th>
                        <th>Author Name</th>
                        <th>ISBN No.</th>
                        <th>No. of Copies</th>
                        <th>Publisher</th>
                        <th>Categories</th>
                        <th>Added Date</th>
                        <th>Language</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>User Ratings</th>
                        </tr>
                    </thead>
                    <tbody>';

    foreach ($data as $item) {
        $html .= '
            <tr>
                <td>' . $item['book_no'] . '</td>
                <td>' . $item['book_title'] . '</td>
                <td>' . $item['author_name'] . '</td>
                <td>' . $item['isbn_no'] . '</td>
                <td>' . $item['no_of_copies'] . '</td>
                <td>' . $item['publisher'] . '</td>
                <td>' . $item['categories'] . '</td>
                <td>' . $item['added_date'] . '</td>
                <td>' . $item['language'] . '</td>
                <td>' . $item['description'] . '</td>
                <td>' . $item['location'] . '</td>
                <td>' . $item['user_ratings'] . '</td>
            </tr>';
    }

    $html .= '
                    </tbody>
                </table>
            </div>
        </body>
        </html>';

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $pdfFilePath = 'books_report.pdf';
    file_put_contents($pdfFilePath, $pdfOutput);

    echo '../../downloads/books_report.pdf';
}

function generateBorrowedBooks($conn)
{
    $sql = "SELECT * FROM borrowed_books";
    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    mysqli_close($conn);

    $html = '
        <html>
        <head>
            <title>Library Management System Report - Ananda College, Galle</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                h2 {
                    text-align: center;
                }
                h4 {
                    font-weight: normal;
                    text-align: center;
                    margin-bottom: 40px;
                    margin-top: -10px;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>LMS Report - Borrowed Books</h2>
                <h4>Ananda College, Galle</h4>
                <table>
                    <thead>
                        <tr>
                        <th>Borrowed Book No.</th>
                        <th>Book No.</th>
                        <th>Book Title</th>
                        <th>Student No.</th>
                        <th>Student Name</th>
                        <th>Borrowed Date</th>
                        </tr>
                    </thead>
                    <tbody>';

    foreach ($data as $item) {
        $html .= '
            <tr>
                <td>' . $item['borrowed_book_no'] . '</td>
                <td>' . $item['book_no'] . '</td>
                <td>' . $item['book_title'] . '</td>
                <td>' . $item['student_no'] . '</td>
                <td>' . $item['student_name'] . '</td>
                <td>' . $item['borrowed_date'] . '</td>
            </tr>';
    }

    $html .= '
                    </tbody>
                </table>
            </div>
        </body>
        </html>';

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $pdfFilePath = 'borrowed_books_report.pdf';
    file_put_contents($pdfFilePath, $pdfOutput);

    echo '../../downloads/borrowed_books_report.pdf';
}

function generateReturnedBooks($conn)
{
    $sql = "SELECT * FROM returned_books";
    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    mysqli_close($conn);

    $html = '
        <html>
        <head>
            <title>Library Management System Report - Ananda College, Galle</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                h2 {
                    text-align: center;
                }
                h4 {
                    font-weight: normal;
                    text-align: center;
                    margin-bottom: 40px;
                    margin-top: -10px;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>LMS Report - Returned Books</h2>
                <h4>Ananda College, Galle</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Returned Book No.</th>
                            <th>Book No.</th>
                            <th>Book Title</th>
                            <th>Student No.</th>
                            <th>Student Name</th>
                            <th>Returned Date</th>
                        </tr>
                    </thead>
                    <tbody>';

    foreach ($data as $item) {
        $html .= '
            <tr>
                <td>' . $item['returned_book_no'] . '</td>
                <td>' . $item['book_no'] . '</td>
                <td>' . $item['book_title'] . '</td>
                <td>' . $item['student_no'] . '</td>
                <td>' . $item['student_name'] . '</td>
                <td>' . $item['returned_date'] . '</td>
            </tr>';
    }

    $html .= '
                    </tbody>
                </table>
            </div>
        </body>
        </html>';

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $pdfFilePath = 'returned_books_report.pdf';
    file_put_contents($pdfFilePath, $pdfOutput);

    echo '../../downloads/returned_books_report.pdf';
}

function generateFines($conn)
{
    $sql = "SELECT * FROM fines";
    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    mysqli_close($conn);

    $html = '
        <html>
        <head>
            <title>Library Management System Report - Ananda College, Galle</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                h2 {
                    text-align: center;
                }
                h4 {
                    font-weight: normal;
                    text-align: center;
                    margin-bottom: 40px;
                    margin-top: -10px;
                }
                .container {
                    max-width: 100%;
                    margin: 0 auto;
                    padding: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>LMS Report - Fines</h2>
                <h4>Ananda College, Galle</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Fine No.</th>
							<th>Student No.</th>
							<th>Student Name</th>
							<th>Book No.</th>
							<th>Book Title</th>
							<th>Fine Amount</th>
							<th>Issued Date</th>
							<th>Due Date</th>
							<th>Paid Date</th>
                        </tr>
                    </thead>
                    <tbody>';

    foreach ($data as $item) {
        $html .= '
            <tr>
                <td>' . $item['fine_no'] . '</td>
                <td>' . $item['student_no'] . '</td>
                <td>' . $item['student_name'] . '</td>
                <td>' . $item['book_no'] . '</td>
                <td>' . $item['book_title'] . '</td>
                <td>' . $item['fine_amount'] . '</td>
                <td>' . $item['issued_date'] . '</td>
                <td>' . $item['due_date'] . '</td>
                <td>' . $item['paid_date'] . '</td>
            </tr>';
    }

    $html .= '
                    </tbody>
                </table>
            </div>
        </body>
        </html>';

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $pdfFilePath = 'fines_report.pdf';
    file_put_contents($pdfFilePath, $pdfOutput);

    echo '../../downloads/fines_report.pdf';
}

function generateReservations($conn)
{
    $sql = "SELECT * FROM reservations";
    $result = mysqli_query($conn, $sql);

    $data = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    mysqli_close($conn);

    $html = '
        <html>
        <head>
            <title>Library Management System Report - Ananda College, Galle</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                }
                h2 {
                    text-align: center;
                }
                h4 {
                    font-weight: normal;
                    text-align: center;
                    margin-bottom: 40px;
                    margin-top: -10px;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>LMS Report - Reservations</h2>
                <h4>Ananda College, Galle</h4>
                <table>
                    <thead>
                        <tr>
                        <th>Reservation No.</th>
                        <th>Book No.</th>
                        <th>Book Title</th>
                        <th>Student No.</th>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Reserved Date</th>
                        </tr>
                    </thead>
                    <tbody>';

    foreach ($data as $item) {
        $html .= '
            <tr>
                <td>' . $item['reservation_no'] . '</td>
                <td>' . $item['book_no'] . '</td>
                <td>' . $item['book_title'] . '</td>
                <td>' . $item['student_no'] . '</td>
                <td>' . $item['student_name'] . '</td>
                <td>' . $item['email'] . '</td>
                <td>' . $item['reserved_date'] . '</td>
            </tr>';
    }

    $html .= '
                    </tbody>
                </table>
            </div>
        </body>
        </html>';

    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfOutput = $dompdf->output();
    $pdfFilePath = 'reservations_report.pdf';
    file_put_contents($pdfFilePath, $pdfOutput);

    echo '../../downloads/reservations_report.pdf';
}
