<?php

require __DIR__ . '/../includes/db_conn.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/mailer.php';

/**
 * CLI-friendly overdue reminder job.
 *
 * Run manually:
 *   php scripts/check_overdue_books.php
 *
 * In production this belongs behind cron / Task Scheduler rather than a web request.
 */

$currentDateTime = date('Y-m-d H:i:s');
$sql = <<<'SQL'
SELECT
    bb.borrowed_book_no,
    bb.book_title,
    bb.borrowed_date,
    bb.due_date,
    s.email
FROM borrowed_books bb
INNER JOIN students s ON s.student_no = bb.student_no
WHERE bb.due_date < ?
  AND bb.overdue_reminder IS NULL
ORDER BY bb.due_date ASC
SQL;

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $currentDateTime);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$found = mysqli_num_rows($result);
$sent = 0;

echo "Overdue loans found: {$found}" . PHP_EOL;

while ($loan = mysqli_fetch_assoc($result)) {
    if (!sendReminderEmail($loan['email'], $loan['book_title'], $loan['borrowed_date'], $loan['due_date'])) {
        echo "Skipped reminder for borrowed_book_no={$loan['borrowed_book_no']} (mail not configured or failed)." . PHP_EOL;
        continue;
    }

    $borrowedBookNo = (int) $loan['borrowed_book_no'];
    $markStmt = mysqli_prepare($conn, "UPDATE borrowed_books SET overdue_reminder = 'Sent' WHERE borrowed_book_no = ?");
    mysqli_stmt_bind_param($markStmt, 'i', $borrowedBookNo);
    mysqli_stmt_execute($markStmt);
    mysqli_stmt_close($markStmt);
    $sent++;
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

echo "Reminders sent: {$sent}" . PHP_EOL;

function sendReminderEmail(string $studentEmail, string $bookTitle, string $borrowedDate, string $dueDate): bool
{
    $safeTitle = htmlspecialchars($bookTitle, ENT_QUOTES, 'UTF-8');
    $safeBorrowedDate = htmlspecialchars($borrowedDate, ENT_QUOTES, 'UTF-8');
    $safeDueDate = htmlspecialchars($dueDate, ENT_QUOTES, 'UTF-8');

    $html = <<<HTML
    <h2>Library overdue reminder</h2>
    <p>The following borrowed book is now overdue:</p>
    <p><strong>Book:</strong> {$safeTitle}<br>
    <strong>Borrowed:</strong> {$safeBorrowedDate}<br>
    <strong>Due:</strong> {$safeDueDate}</p>
    <p>Please return the book or contact the library staff if you need assistance.</p>
    HTML;

    return sendLibraryEmail($studentEmail, 'Reminder: overdue library book', $html);
}
