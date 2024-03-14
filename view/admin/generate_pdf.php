<?php
require '../../includes/db_conn.php';
require_once '../../vendor/autoload.php';

if (isset($_POST['action']) && $_POST['action'] === 'students') {
    generateStudents($conn);
} else {
    echo 'Invalid action';
}
function generateStudents($conn)
{
    $sql = "SELECT * FROM students";
    $result = mysqli_query($conn, $sql);

    $studentsData = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentsData[] = $row;
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
                h1 {
                    text-align: center;
                    margin-bottom: 60px;
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
                <h1>Library Management System Report</h1>
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

    foreach ($studentsData as $student) {
        $html .= '
            <tr>
                <td>' . $student['student_no'] . '</td>
                <td>' . $student['student_name'] . '</td>
                <td>' . $student['admission_id'] . '</td>
                <td>' . $student['username'] . '</td>
                <td>' . $student['email'] . '</td>
                <td>' . $student['class'] . '</td>
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
    $pdfFilePath = '../../downloads/students_report.pdf';
    file_put_contents($pdfFilePath, $pdfOutput);

    echo $pdfFilePath;
}
