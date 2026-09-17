-- Library Management System demo schema
-- Sanitized for the public repository: no production or personal records.
-- Tested against the table/column names used by the PHP application.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `library_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `library_db`;

DROP TABLE IF EXISTS `reservations`;
DROP TABLE IF EXISTS `fines`;
DROP TABLE IF EXISTS `returned_books`;
DROP TABLE IF EXISTS `borrowed_books`;
DROP TABLE IF EXISTS `books`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `admin_no` int NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(60) NOT NULL,
  `password` varchar(150) NOT NULL,
  `username` varchar(60) NOT NULL,
  `email` varchar(120) NOT NULL,
  `last_accessed_date` datetime DEFAULT NULL,
  PRIMARY KEY (`admin_no`),
  UNIQUE KEY `uq_admin_username` (`username`),
  UNIQUE KEY `uq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `students` (
  `student_no` int NOT NULL AUTO_INCREMENT,
  `admission_id` int NOT NULL,
  `password` varchar(150) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(120) NOT NULL,
  `class` varchar(60) NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `last_accessed_date` datetime DEFAULT NULL,
  PRIMARY KEY (`student_no`),
  UNIQUE KEY `uq_student_admission` (`admission_id`),
  UNIQUE KEY `uq_student_username` (`username`),
  UNIQUE KEY `uq_student_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `books` (
  `book_no` int NOT NULL AUTO_INCREMENT,
  `book_title` varchar(150) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `isbn_no` varchar(30) NOT NULL,
  `no_of_copies` int NOT NULL DEFAULT 0,
  `publisher` varchar(100) NOT NULL,
  `categories` varchar(60) NOT NULL,
  `added_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `language` varchar(40) NOT NULL,
  `description` varchar(255) NOT NULL,
  `location` varchar(80) NOT NULL,
  `user_ratings` double NOT NULL DEFAULT 0,
  `no_of_ratings` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`book_no`),
  UNIQUE KEY `uq_book_isbn` (`isbn_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `borrowed_books` (
  `borrowed_book_no` int NOT NULL AUTO_INCREMENT,
  `book_no` int NOT NULL,
  `book_title` varchar(150) NOT NULL,
  `student_no` int NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `borrowed_date` datetime NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `overdue_reminder` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`borrowed_book_no`),
  KEY `idx_borrowed_student` (`student_no`),
  KEY `idx_borrowed_book` (`book_no`),
  KEY `idx_borrowed_due` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `returned_books` (
  `returned_book_no` int NOT NULL AUTO_INCREMENT,
  `book_no` int NOT NULL,
  `book_title` varchar(150) NOT NULL,
  `student_no` int NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `returned_date` datetime NOT NULL,
  `is_rated` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`returned_book_no`),
  KEY `idx_returned_student` (`student_no`),
  KEY `idx_returned_book` (`book_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `fines` (
  `fine_no` int NOT NULL AUTO_INCREMENT,
  `student_no` int NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `book_no` int NOT NULL,
  `book_title` varchar(150) NOT NULL,
  `fine_amount` decimal(15,2) NOT NULL,
  `issued_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `payment_status` varchar(30) NOT NULL,
  `paid_date` datetime DEFAULT NULL,
  PRIMARY KEY (`fine_no`),
  KEY `idx_fine_student` (`student_no`),
  KEY `idx_fine_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reservations` (
  `reservation_no` int NOT NULL AUTO_INCREMENT,
  `book_no` int NOT NULL,
  `book_title` varchar(150) NOT NULL,
  `student_no` int NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `reserved_date` datetime NOT NULL,
  `email` varchar(120) NOT NULL,
  PRIMARY KEY (`reservation_no`),
  KEY `idx_reservation_book` (`book_no`),
  KEY `idx_reservation_student` (`student_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- Demo accounts
-- -------------------------------------------------------------------------
-- These are local-only sample credentials for the public project.
-- Admin:   admin / Admin123!
-- Student: student / Student123!
-- Both accounts start with last_accessed_date = NULL so the app's original
-- first-login flow asks for a password change.

INSERT INTO `admins`
  (`admin_no`, `admin_name`, `password`, `username`, `email`, `last_accessed_date`)
VALUES
  (1, 'Demo Admin', '$2y$12$cZBf55Al0mxhTQKtwPfP6uz6acMyfZIzAr3uaj4li9pYlaNpFHcbW', 'admin', 'admin@example.com', NULL);

INSERT INTO `students`
  (`student_no`, `admission_id`, `password`, `username`, `email`, `class`, `student_name`, `last_accessed_date`)
VALUES
  (1, 100001, '$2y$12$8n7to7V9rYI8e9xf3DFLq.yioHiPvhEfRcTN.6Hp3UXGFwI5uR5LO', 'student', 'student@example.com', 'Grade 11', 'Demo Student', NULL);

-- Small anonymous catalog so the screens are not empty after import.
INSERT INTO `books`
  (`book_no`, `book_title`, `author_name`, `isbn_no`, `no_of_copies`, `publisher`, `categories`, `added_date`, `language`, `description`, `location`, `user_ratings`, `no_of_ratings`)
VALUES
  (1, 'The Demo Novel', 'A. Reader', 'DEMO-ISBN-001', 4, 'Sample Press', 'Fiction', '2024-03-01 09:00:00', 'English', 'Sample fiction title for local development.', 'Section A', 4.0, 2),
  (2, 'Practical Science', 'B. Author', 'DEMO-ISBN-002', 2, 'Sample Press', 'Science', '2024-03-02 09:00:00', 'English', 'Sample science title for local development.', 'Section B', 0, 0),
  (3, 'A Short History', 'C. Writer', 'DEMO-ISBN-003', 1, 'Demo Publishing', 'History', '2024-03-03 09:00:00', 'English', 'Sample history title for local development.', 'Section C', 0, 0);

INSERT INTO `borrowed_books`
  (`borrowed_book_no`, `book_no`, `book_title`, `student_no`, `student_name`, `borrowed_date`, `due_date`, `overdue_reminder`)
VALUES
  (1, 1, 'The Demo Novel', 1, 'Demo Student', '2024-03-10 10:00:00', '2024-03-17 10:00:00', NULL);

INSERT INTO `returned_books`
  (`returned_book_no`, `book_no`, `book_title`, `student_no`, `student_name`, `returned_date`, `is_rated`)
VALUES
  (1, 2, 'Practical Science', 1, 'Demo Student', '2024-03-09 15:30:00', 0);

INSERT INTO `fines`
  (`fine_no`, `student_no`, `student_name`, `book_no`, `book_title`, `fine_amount`, `issued_date`, `due_date`, `payment_status`, `paid_date`)
VALUES
  (1, 1, 'Demo Student', 2, 'Practical Science', 50.00, '2024-03-09 15:30:00', '2024-03-08 15:30:00', 'Paid', '2024-03-09 16:00:00');

INSERT INTO `reservations`
  (`reservation_no`, `book_no`, `book_title`, `student_no`, `student_name`, `reserved_date`, `email`)
VALUES
  (1, 3, 'A Short History', 1, 'Demo Student', '2024-03-11 12:00:00', 'student@example.com');
