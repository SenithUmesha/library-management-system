<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

/**
 * Send mail using credentials supplied by the runtime environment.
 *
 * Required environment variable:
 *   SMTP_DSN=smtp://user:password@smtp.example.com:587
 *
 * Optional:
 *   MAIL_FROM_ADDRESS=library@example.com
 *   MAIL_FROM_NAME=Library System
 */
function sendLibraryEmail(string $to, string $subject, string $html): bool
{
    $dsn = getenv('SMTP_DSN');

    if ($dsn === false || trim($dsn) === '') {
        error_log('Library mail skipped: SMTP_DSN is not configured.');
        return false;
    }

    $fromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'library@example.com';
    $fromName = getenv('MAIL_FROM_NAME') ?: 'Library System';

    try {
        $mailer = new Mailer(Transport::fromDsn($dsn));
        $email = (new Email())
            ->from(sprintf('%s <%s>', $fromName, $fromAddress))
            ->to($to)
            ->subject($subject)
            ->html($html);

        $mailer->send($email);
        return true;
    } catch (Throwable $exception) {
        error_log('Library mail failed: ' . $exception->getMessage());
        return false;
    }
}
