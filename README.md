# Library Management System 📚

> a PHP/MySQL school-library system that started as CRUD and somehow ended up with reservations, fines, reminder emails, PDF reports and a chatbot.

Built in 2024 for a school-library workflow, this project has two sides: an **admin portal** for running the catalog and circulation process, and a **student portal** for finding, borrowing, returning and rating books.

It is a learning/project snapshot rather than a production library platform, but it grew far enough beyond a basic CRUD demo to become a useful full-stack time capsule.

## what it does

### for library admins

- manage student and administrator accounts
- add, edit and remove books
- track available copies and library shelf/location data
- view active loans and returned books
- manage reservations
- review and mark fines
- generate PDF reports for the main datasets
- run an overdue-loan reminder job

### for students

- sign in with a library account
- browse the book catalog
- borrow available books
- reserve books when needed
- view active loans and return history
- return a borrowed book
- see fines
- rate returned books
- manage the account password/profile flow
- use the embedded **Dialogflow Messenger** library assistant

## the interesting bit: a book is a workflow

The project became more useful once a book stopped being just a row in `books`.

```text
book in catalog
      │
      ├── borrow ──> borrowed_books ──> return ──> returned_books
      │                   │                 │
      │                   │ overdue         ├── restore stock
      │                   └── fine           └── notify reservations
      │
      └── no copy available ──> reservation ──> availability email
```

That means one return can touch several pieces of state: the active loan disappears, return history is created, stock goes back up, an overdue fine may be issued, and waiting reservations can be notified.

The current public snapshot wraps the core return mutation in a database transaction so those state changes do not partially succeed.

## stack

`PHP` · `MySQL / MariaDB` · `Bootstrap` · `JavaScript / AJAX`

`Symfony Mailer` · `Dompdf` · `Dialogflow Messenger`

The project is intentionally still close to the original PHP structure. It has not been rewritten into Laravel or another framework just to make an old project look newer than it is.

## project shape

```text
browser
  │
  ├── view/admin/*       admin screens
  ├── view/student/*     student screens
  │
  ▼
api/*.php + api/student/*.php
  │
  ├── session authentication
  ├── circulation rules
  ├── password hashing
  ├── mail notifications
  └── report / workflow actions
  │
  ▼
MySQL / MariaDB
```

Supporting pieces live in:

```text
includes/       database + mail configuration
scripts/        overdue reminder job
downloads/      PDF report generation
database/       sanitized demo schema/data
util/           small shared helpers
```

For a deeper walkthrough of the data model, circulation flow, transactions, reminder job and security cleanup, see [`docs/engineering.md`](docs/engineering.md).

## running it locally

You need PHP, Composer, MySQL/MariaDB and a web server such as Apache/XAMPP.

```bash
composer install
```

Import:

```text
database/library_db.sql
```

The public SQL file contains **anonymous demo data only**. It creates `library_db` and gives you two local accounts:

```text
admin   / Admin123!
student / Student123!
```

Both are configured as first-login accounts, so the original workflow asks you to change the temporary password after signing in.

### environment configuration

Database and mail settings are read from environment variables. `.env.example` is a reference template; this plain PHP project does **not** automatically load `.env` files.

```text
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD

SMTP_DSN
MAIL_FROM_ADDRESS
MAIL_FROM_NAME
```

Outbound email is optional. Without `SMTP_DSN`, account creation and circulation still work, while mail delivery is skipped and logged instead of requiring a credential in source code.

## overdue reminders

The reminder script is meant to run as a scheduled CLI job:

```bash
php scripts/check_overdue_books.php
```

It finds overdue loans that have not been reminded yet, joins them to the corresponding student email, sends the notification, and marks the reminder as sent **only when delivery succeeds**.

A deployment could run this from cron or Windows Task Scheduler.

## reports

`downloads/generate_pdf.php` uses **Dompdf** to generate reports for students, admins, books, borrowed books, returned books, fines and reservations.

Generated PDFs are ignored by Git so running the app does not dirty the repository.

## public-repo cleanup

While revisiting the project, I cleaned up a few things that are easy to get wrong in an early PHP app:

- moved database settings out of source and into environment variables
- moved SMTP configuration out of source into one mail helper
- stopped API responses from exposing password hashes
- regenerated session IDs after successful authentication
- restricted the front controller to an allowlist instead of including arbitrary query-string paths
- bound student return actions to the authenticated session instead of trusting posted student identity
- made the multi-step return flow transactional
- switched key account-management queries to prepared statements
- replaced the original database dump with anonymous demo records
- ignored IDE files, dependencies, environment files and generated reports

There is still deliberately old-school code here. The point of the cleanup is to make the public snapshot safer and easier to understand, not pretend it was written with a modern framework architecture.

## if i rebuilt it now

I would keep the same core domain — catalog, circulation, reservations, fines and notifications — but move it toward explicit application services, role middleware, request validation, CSRF protection on every mutation, database foreign keys/constraints, queued mail, migrations instead of a SQL dump, integration tests around circulation rules, and a proper scheduler for background work.

The most important change would be making **borrow / reserve / return** first-class domain operations instead of spreading business rules across endpoint scripts.

## project status

Historical/learning project. Kept public because it shows a different side of the stuff I like building: not mobile UI this time, but workflows, state transitions, databases, background jobs and the unglamorous parts that make an app actually behave.
