<?php
require __DIR__ . '../../includes/db_conn.php';

require_once __DIR__ . '../../vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope LocalMachine

session_start();

checkDueBooks($conn);

function checkDueBooks($conn)
{
    echo ("<script>console.log('PHP: Started');</script>");

    $currentDateTime = date('Y-m-d H:i:s');

    $sql = "SELECT * FROM borrowed_books WHERE due_date < ? AND overdue_reminder IS NULL";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $currentDateTime);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    echo ("<script>console.log('Overdue Books: " . mysqli_num_rows($result) . "');</script>");

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bookTitle = $row['book_title'];
            $borrowedDate = $row['borrowed_date'];
            $dueDate = $row['due_date'];
            $studentNo = $row['student_no'];
            $bookNo = $row['book_no'];

            echo ("<script>console.log('Student No:" . $studentNo . "');</script>");

            $sql = "SELECT * FROM students WHERE student_no = ?";

            $stmt2 = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt2, 'i', $studentNo);
            mysqli_stmt_execute($stmt2);

            $result1 = mysqli_stmt_get_result($stmt2);

            if ($result1 && mysqli_num_rows($result1) > 0) {
                $row = mysqli_fetch_assoc($result1);
                $studentEmail = $row['email'];

                sendReminderEmail($studentEmail, $bookTitle, $borrowedDate, $dueDate);
                $markReminderSentSql = "UPDATE borrowed_books SET overdue_reminder = 'Sent' WHERE student_no = ? AND book_no = ?";
                $markReminderSentStmt = mysqli_prepare($conn, $markReminderSentSql);
                mysqli_stmt_bind_param($markReminderSentStmt, 'ii', $studentNo, $bookNo);
                mysqli_stmt_execute($markReminderSentStmt);
                mysqli_stmt_close($markReminderSentStmt);

                echo ("<script>console.log('PHP: Email Sent');</script>");
            }
        }
        mysqli_stmt_close($stmt2);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

function sendReminderEmail($studentEmail, $bookTitle, $borrowedDate, $dueDate)
{
    $transport = Transport::fromDsn('smtp://34senith@gmail.com:osfiefvsuqxjgmhv@smtp.gmail.com:587');

    $mailer = new Mailer($transport);

    $email = (new Email());

    $email->from('34senith@gmail.com');

    $email->to('' . $studentEmail);

    $email->subject('Reminder: Book Due Date');

    $email->html('
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4;">
<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
<h2 style="color: #333;">Reminder: Book Due Date</h2>
<p style="color: #555;">Dear Student,</p>
<p style="color: #555;">This is a reminder that the due date for the book "' . $bookTitle . '" is approaching. Please return the book to the library as soon as possible.</p>
<div style="margin-top: 20px; padding: 10px; background-color: #f9f9f9; border-radius: 6px;" class="book-details">
<p><strong>Book Title:</strong> ' . $bookTitle . '</p>
<p><strong>Borrowed Date:</strong> ' . $borrowedDate . '</p>
<p><strong>Due Date:</strong> ' . $dueDate . '</p>
</div>
<p style="color: #555; margin-top: 20px;">If you have any questions or need further assistance, please contact our library staff.</p>
<p style="color: #555;">Happy reading!</p>
<div style="margin-top: 20px; font-size: 12px; color: #777;" class="footer">
<p>Best regards,<br>Library System Team<br>Ananda College</p>
</div>
</div>
</body>
</html>
');

    $mailer->send($email);
}
