# Engineering notes — Library Management System

This document describes the project as it actually exists: a 2024 server-rendered PHP/MySQL application with AJAX-style endpoint scripts, session authentication, email notifications, PDF reports and separate admin/student workflows.

The public snapshot has received a focused security/repository cleanup, but it has intentionally **not** been converted into a framework app. The interesting part is seeing how the original workflow model works and where a more mature architecture would draw stronger boundaries.

## 1. System shape

```text
                         ┌────────────────────┐
                         │      browser       │
                         └─────────┬──────────┘
                                   │
                ┌──────────────────┴──────────────────┐
                │                                     │
        view/admin/*.php                     view/student/*.php
                │                                     │
                └──────────────────┬──────────────────┘
                                   │ AJAX / forms
                                   ▼
                         api/*.php endpoints
                                   │
            ┌──────────────────────┼──────────────────────┐
            │                      │                      │
          MySQL              Symfony Mailer             Dompdf
            │                      │                      │
     library state         welcome/reminder        generated reports
                           /availability mail
```

There is no router/controller/service abstraction in the framework sense. Each API file owns a small set of actions and talks to MySQL directly.

That keeps the original app approachable, but it also means authorization, validation, persistence and business rules can become mixed together.

## 2. Authentication and roles

The system has two account tables:

```text
admins
students
```

Both store `password_hash()` output rather than plaintext passwords. Login looks up the username using prepared statements, verifies the hash with `password_verify()`, then stores a small session identity:

```text
id
username
name
account_type = admin | student
```

The current public version regenerates the PHP session ID after successful authentication to reduce session-fixation risk.

### first-login password flow

New accounts are created with a temporary numeric password. Its hash is stored in the database while `last_accessed_date` remains `NULL`.

On first login:

```text
valid credentials
      │
      ▼
last_accessed_date is NULL?
      │
   yes│       no
      ▼        ▼
change password   role home
```

After a password change, the hash and `last_accessed_date` are updated.

For a real deployment I would avoid emailing passwords entirely and instead send a short-lived password-setup token.

## 3. Catalog model

The `books` table combines bibliographic information with copy availability and aggregate ratings:

```text
book_no
book_title
author_name
isbn_no
no_of_copies
publisher
categories
added_date
language
description
location
user_ratings
no_of_ratings
```

`no_of_copies` is being used as the available-stock count rather than representing individual physical copies.

That is fine for the scale of this project, but it cannot identify a specific copy/barcode or represent one damaged/lost copy independently.

A larger library model would normally split:

```text
books             physical_copies
-----             ---------------
id                id
isbn              book_id
title             barcode
...               status
                  shelf
```

## 4. Circulation as state transitions

The project uses separate tables to represent important moments in a book's lifecycle:

```text
books
borrowed_books
returned_books
reservations
fines
```

This makes the UI simple because each screen has a natural dataset, but it also means a single action can require coordinated writes across several tables.

### return flow

The return endpoint is the clearest example.

The current public version first derives the loan from the authenticated student's session and `borrowed_book_no`; it no longer accepts student identity, book title or due date as trusted POST data.

Then:

```text
verify loan belongs to signed-in student
              │
              ▼
       begin transaction
              │
              ├── overdue? -> insert fine
              ├── insert returned_books row
              ├── delete borrowed_books row
              └── increment books.no_of_copies
              │
              ▼
            commit
              │
              ▼
      check reservations
              │
              ├── mail succeeds -> delete reservation
              └── mail fails    -> leave reservation intact
```

The database transaction protects the core inventory/history mutation from partially completing.

Email is intentionally outside the transaction. SMTP is an external side effect: holding a database transaction open while waiting for mail would couple data integrity to network latency and mail-provider availability.

## 5. Fines

The historical rule is intentionally simple: if the due timestamp is earlier than the return timestamp, the return flow creates a fixed fine of `50.00`.

That is a project rule, not a generic library-policy recommendation.

A richer model would make fine policy configuration explicit:

```text
fine_policy
├── grace period
├── amount per day
├── maximum fine
└── exemptions
```

It would also protect against creating duplicate fines if a return operation were retried after an ambiguous failure.

## 6. Reservations

Reservations store the book, student and email snapshot needed by the original workflow.

When a returned book becomes available, the application checks reservations for that book and sends an availability email.

The public cleanup changed one important behavior: **a reservation is only deleted after the corresponding email succeeds**. If mail is not configured or delivery throws an error, the reservation stays in the database instead of silently disappearing.

A production design would usually notify only the next reservation in a queue, give that student a hold window, and advance to the next person if the hold expires.

## 7. Email architecture

Originally, multiple endpoint files each constructed their own Symfony Mailer transport and embedded mail credentials directly in source.

The current snapshot centralizes delivery in:

```text
includes/mailer.php
```

Configuration comes from runtime environment variables:

```text
SMTP_DSN
MAIL_FROM_ADDRESS
MAIL_FROM_NAME
```

The helper returns `false` when SMTP is missing/fails, allowing callers to distinguish application success from notification success.

This is still synchronous mail. A modern system would put mail events onto a queue so a slow provider never makes an account or circulation request wait.

## 8. Overdue reminder job

`scripts/check_overdue_books.php` is designed as a CLI/scheduled job.

The current version performs one joined query:

```sql
borrowed_books
INNER JOIN students
    ON students.student_no = borrowed_books.student_no
```

filtered to:

```text
due_date < now
AND overdue_reminder IS NULL
```

For each overdue loan it attempts mail delivery, then marks `overdue_reminder = 'Sent'` only if sending succeeds.

This can be scheduled using cron or Windows Task Scheduler.

A production job would also benefit from:

- structured logging
- retry/backoff
- an attempt timestamp/count rather than one nullable flag
- idempotency around multiple workers
- a proper scheduler/queue abstraction

## 9. PDF reports

`downloads/generate_pdf.php` uses Dompdf to render HTML tables into reports for:

```text
students
admins
books
borrowed books
returned books
fines
reservations
```

The files are runtime output and are ignored by Git.

The current implementation is intentionally direct: query a table, build HTML, render PDF. A larger system would separate report queries/templates from HTTP handling and stream temporary output instead of leaving generated files on disk indefinitely.

## 10. Student assistant

The student UI includes Google's Dialogflow Messenger web component as a floating `Library Assistant`.

This should be understood as an integration rather than an in-repository conversational-AI implementation. The application embeds the Dialogflow client; the bot's intents/training configuration live outside this repository.

A production deployment should treat any public agent configuration and third-party data handling as part of its privacy/security review.

## 11. Public repository security cleanup

Several changes were made specifically because this repository is public.

### runtime credentials

Database and SMTP values now come from environment variables rather than literals committed in application files.

`.env.example` exists only as a reference. This project does not automatically parse it; environment variables need to be exported by the shell/web server or loaded by whatever deployment tooling is used.

### front-controller include

The old `index.php` accepted a query-string value and passed it directly to `include`.

That is now replaced with an explicit allowlist of public entry pages.

### API authorization

Admin account-management endpoints now require an authenticated `admin` session. Student circulation endpoints require an authenticated `student` session.

### sensitive fields

Account-list endpoints no longer return password hashes to the browser.

### SQL writes

Important account-management paths now use prepared statements instead of composing request values into SQL strings.

There are still legacy queries elsewhere in the project that should be migrated the same way before treating the application as production-ready.

### public seed data

The SQL file was rebuilt with anonymous `example.com` demo records. Real-looking historical account data is not needed to demonstrate the schema.

## 12. What remains intentionally legacy

This cleanup is not a claim that the project is production-hardened.

Important limitations still include:

- authorization checks are not centralized middleware
- not every legacy endpoint has been converted to prepared statements
- there is no application-wide CSRF abstraction comparable to modern frameworks
- data relationships are represented mostly by IDs without database foreign keys
- several workflows duplicate denormalized book/student names
- mail is synchronous
- endpoint files combine validation, policy and persistence
- PHP views and API endpoints are tightly coupled
- test coverage is minimal/nonexistent
- auditing and structured observability are absent

Those limitations are useful context: this repo shows a working domain growing past the point where raw endpoint scripts remain comfortable.

## 13. A modern version

I would keep the workflows and rebuild the boundaries.

```text
HTTP / UI
   │
role + CSRF middleware
   │
request validation
   │
application services
   ├── BorrowBook
   ├── ReturnBook
   ├── ReserveBook
   ├── PayFine
   └── CreateAccount
   │
domain + repositories
   │
MySQL
   │
transactional events
   ├── mail queue
   ├── reminders
   └── reports
```

The key design goal would be to make a circulation operation express its invariants in one place:

- a copy cannot be borrowed if none are available
- a loan belongs to one authenticated student
- returning a loan changes history and availability atomically
- reservation order is deterministic
- fines are idempotent
- notifications can retry independently

That is the part of this old project worth carrying forward: the domain stopped being simple CRUD, and the architecture needed to catch up with it.
