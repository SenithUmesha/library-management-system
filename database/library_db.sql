-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 29, 2024 at 09:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_no` int(6) NOT NULL,
  `admin_name` varchar(60) NOT NULL,
  `password` varchar(150) NOT NULL,
  `username` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `last_accessed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_no`, `admin_name`, `password`, `username`, `email`, `last_accessed_date`) VALUES
(12, 'Admin', '$2y$10$dUvIWKuLHMmjjq6kNoyrdebme.Ci2tIwJ/ScniWrwVobhBBRbOZVe', 'admin', 'umesha.pms@gmail.com', '2024-02-29 14:35:42');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_no` int(11) NOT NULL,
  `book_title` varchar(150) NOT NULL,
  `author_name` varchar(60) NOT NULL,
  `isbn_no` varchar(30) NOT NULL,
  `no_of_copies` int(10) NOT NULL,
  `publisher` varchar(60) NOT NULL,
  `availability` varchar(20) NOT NULL,
  `categories` varchar(30) NOT NULL,
  `added_date` datetime NOT NULL,
  `language` varchar(20) NOT NULL,
  `description` varchar(200) NOT NULL,
  `book_condition` varchar(30) NOT NULL,
  `location` varchar(50) NOT NULL,
  `user_ratings` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_no`, `book_title`, `author_name`, `isbn_no`, `no_of_copies`, `publisher`, `availability`, `categories`, `added_date`, `language`, `description`, `book_condition`, `location`, `user_ratings`) VALUES
(8, 'Book 1', 'Author 1', 'ISBN001', 5, 'Publisher A', 'Available', 'Fiction', '2024-02-15 00:00:00', 'English', 'Description 1', 'Good', 'Library Section A', 4.5),
(9, 'Book 2', 'Author 2', 'ISBN002', 3, 'Publisher B', 'Available', 'Non-Fiction', '2024-02-16 00:00:00', 'English', 'Description 2', 'Excellent', 'Library Section B', 4.8),
(10, 'Book 3', 'Author 3', 'ISBN003', 7, 'Publisher C', 'Available', 'Science', '2024-02-17 00:00:00', 'English', 'Description 3', 'Fair', 'Library Section C', 3.2),
(11, 'Book 4', 'Author 4', 'ISBN020', 10, 'Publisher D', 'Available', 'Mystery', '2024-03-01 00:00:00', 'English', 'Description 20', 'Very Good', 'Library Section D', 4);

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `borrowed_book_no` int(11) NOT NULL,
  `book_no` int(11) NOT NULL,
  `book_title` varchar(150) NOT NULL,
  `student_no` int(11) NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `borrowed_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`borrowed_book_no`, `book_no`, `book_title`, `student_no`, `student_name`, `borrowed_date`) VALUES
(1, 11, 'Book 11', 0, 'Ivan Turner', '2024-02-17 00:00:00'),
(2, 12, 'Book 12', 0, 'Jasmine Lee', '2024-02-18 00:00:00'),
(3, 13, 'Book 13', 0, 'Kevin Harris', '2024-02-19 00:00:00'),
(4, 14, 'Book 14', 0, 'Linda Evans', '2024-02-20 05:00:00'),
(5, 15, 'Book 15', 0, 'Mark Robinson', '2024-02-21 00:00:00'),
(6, 16, 'Book 16', 0, 'Nancy King', '2024-02-22 00:00:00'),
(7, 17, 'Book 17', 0, 'Oscar Scott', '2024-02-23 00:00:00'),
(8, 18, 'Book 18', 0, 'Pamela Ward', '2024-02-24 00:00:00'),
(9, 19, 'Book 19', 0, 'Quincy Morris', '2024-02-25 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `fine_no` int(11) NOT NULL,
  `student_no` int(11) NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `book_no` int(11) NOT NULL,
  `book_title` varchar(50) NOT NULL,
  `fine_amount` decimal(15,2) NOT NULL,
  `issued_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `payment_status` varchar(30) NOT NULL,
  `paid_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fines`
--

INSERT INTO `fines` (`fine_no`, `student_no`, `student_name`, `book_no`, `book_title`, `fine_amount`, `issued_date`, `due_date`, `payment_status`, `paid_date`) VALUES
(2, 2, 'Jane Smith', 1, 'To Kill a Mockingbird', 7.80, '2024-02-10 00:00:00', '2024-03-05 00:00:00', 'Unpaid', NULL),
(3, 6, 'Robert Johnson', 5, '1984', 4.50, '2024-02-15 00:00:00', '2024-03-10 00:00:00', 'Paid', '2024-02-29 20:04:58'),
(4, 4, 'Emily Brown', 4, 'The Catcher in the Rye', 6.00, '2024-03-01 00:00:00', '2024-03-20 00:00:00', 'Paid', '2024-03-02 00:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_no` int(11) NOT NULL,
  `book_no` int(11) NOT NULL,
  `book_title` varchar(50) NOT NULL,
  `student_no` int(11) NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `reserved_date` datetime NOT NULL,
  `email` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_no`, `book_no`, `book_title`, `student_no`, `student_name`, `reserved_date`, `email`) VALUES
(1, 101, 'Book 1', 1001, 'Student 1', '2024-02-15 00:00:00', 'student1@example.com'),
(2, 102, 'Book 2', 1002, 'Student 2', '2024-02-16 00:00:00', 'student2@example.com'),
(3, 103, 'Book 3', 1003, 'Student 3', '2024-02-17 00:00:00', 'student3@example.com'),
(4, 104, 'Book 4', 1004, 'Student 4', '2024-02-18 00:00:00', 'student4@example.com'),
(5, 105, 'Book 5', 1005, 'Student 5', '2024-02-19 00:00:00', 'student5@example.com'),
(6, 106, 'Book 6', 1006, 'Student 6', '2024-02-20 00:00:00', 'student6@example.com'),
(7, 107, 'Book 7', 1007, 'Student 7', '2024-02-21 00:00:00', 'student7@example.com'),
(8, 108, 'Book 8', 1008, 'Student 8', '2024-02-22 00:00:00', 'student8@example.com'),
(9, 109, 'Book 9', 1009, 'Student 9', '2024-02-23 00:00:00', 'student9@example.com'),
(10, 110, 'Book 10', 1010, 'Student 10', '2024-02-24 00:00:00', 'student10@example.com'),
(11, 111, 'Book 11', 1011, 'Student 11', '2024-02-25 00:00:00', 'student11@example.com'),
(12, 112, 'Book 12', 1012, 'Student 12', '2024-02-26 00:00:00', 'student12@example.com'),
(13, 113, 'Book 13', 1013, 'Student 13', '2024-02-27 00:00:00', 'student13@example.com'),
(14, 114, 'Book 14', 1014, 'Student 14', '2024-02-28 00:00:00', 'student14@example.com'),
(15, 115, 'Book 15', 1015, 'Student 15', '2024-02-29 00:00:00', 'student15@example.com'),
(16, 116, 'Book 16', 1016, 'Student 16', '2024-03-01 00:00:00', 'student16@example.com'),
(17, 117, 'Book 17', 1017, 'Student 17', '2024-03-02 00:00:00', 'student17@example.com'),
(18, 118, 'Book 18', 1018, 'Student 18', '2024-03-03 00:00:00', 'student18@example.com'),
(19, 119, 'Book 19', 1019, 'Student 19', '2024-03-04 00:00:00', 'student19@example.com'),
(20, 120, 'Book 21', 1020, 'Student 20', '2024-03-05 00:00:00', 'student20@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `returned_books`
--

CREATE TABLE `returned_books` (
  `returned_book_no` int(11) NOT NULL,
  `book_no` int(11) NOT NULL,
  `book_title` varchar(150) NOT NULL,
  `student_no` int(11) NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `returned_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returned_books`
--

INSERT INTO `returned_books` (`returned_book_no`, `book_no`, `book_title`, `student_no`, `student_name`, `returned_date`) VALUES
(1, 1, 'Book 1', 0, 'John Doe', '2024-02-07 04:00:00'),
(2, 2, 'Book 2', 0, 'Jane Smith', '2024-02-08 00:00:00'),
(3, 3, 'Book 3', 0, 'Bob Johnson', '2024-02-09 00:00:00'),
(4, 4, 'Book 4', 0, 'Alice Brown', '2024-02-10 00:00:00'),
(5, 5, 'Book 5', 0, 'Charlie Wilson', '2024-02-11 00:00:00'),
(6, 6, 'Book 6', 0, 'Diana Miller', '2024-02-12 00:00:00'),
(7, 7, 'Book 7', 0, 'Eddie Davis', '2024-02-13 00:00:00'),
(8, 8, 'Book 8', 0, 'Fiona White', '2024-02-14 00:00:00'),
(9, 9, 'Book 9', 0, 'George Adams', '2024-02-15 00:00:00'),
(10, 10, 'Book 10', 0, 'Helen Clark', '2024-02-16 00:00:00'),
(11, 11, 'Book 11', 0, 'Ivan Turner', '2024-02-17 00:00:00'),
(12, 12, 'Book 12', 0, 'Jasmine Lee', '2024-02-18 00:00:00'),
(13, 13, 'Book 13', 0, 'Kevin Harris', '2024-02-19 00:00:00'),
(14, 14, 'Book 14', 0, 'Linda Evans', '2024-02-20 00:00:00'),
(15, 15, 'Book 15', 0, 'Mark Robinson', '2024-02-21 00:00:00'),
(16, 16, 'Book 16', 0, 'Nancy King', '2024-02-22 00:00:00'),
(17, 17, 'Book 17', 0, 'Oscar Scott', '2024-02-23 00:00:00'),
(18, 18, 'Book 18', 0, 'Pamela Ward', '2024-02-24 00:00:00'),
(19, 19, 'Book 19', 0, 'Quincy Morris', '2024-02-25 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_no` int(6) NOT NULL,
  `admission_id` int(6) NOT NULL,
  `password` varchar(150) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(60) NOT NULL,
  `class` varchar(60) NOT NULL,
  `student_name` varchar(60) NOT NULL,
  `last_accessed_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_no`, `admission_id`, `password`, `username`, `email`, `class`, `student_name`, `last_accessed_date`) VALUES
(24, 424112, '$2y$10$RdvbmzE7Obg8KCrksIvR7ORb/OOzpP3wPYIPyQIJvXJipJhcBG/nS', 'umesha', 'umesha.pms@gmail.com', 'Grade 11', 'Umesha', '2024-02-28 13:02:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_no`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_no`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`borrowed_book_no`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`fine_no`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_no`);

--
-- Indexes for table `returned_books`
--
ALTER TABLE `returned_books`
  ADD PRIMARY KEY (`returned_book_no`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_no` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `borrowed_book_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `fine_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `returned_books`
--
ALTER TABLE `returned_books`
  MODIFY `returned_book_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_no` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
