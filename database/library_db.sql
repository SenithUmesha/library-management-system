-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2024 at 07:57 PM
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
(12, 'Admin', '$2y$10$dUvIWKuLHMmjjq6kNoyrdebme.Ci2tIwJ/ScniWrwVobhBBRbOZVe', 'admin', 'umesha.pms@gmail.com', '2024-03-06 19:55:09');

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
  `categories` varchar(30) NOT NULL,
  `added_date` datetime NOT NULL,
  `language` varchar(20) NOT NULL,
  `description` varchar(200) NOT NULL,
  `location` varchar(50) NOT NULL,
  `user_ratings` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_no`, `book_title`, `author_name`, `isbn_no`, `no_of_copies`, `publisher`, `categories`, `added_date`, `language`, `description`, `location`, `user_ratings`) VALUES
(8, 'Book 1', 'Author 1', 'ISBN001', 9, 'Publisher C', 'Fiction', '2024-02-15 00:00:00', 'English', 'Description 1', 'Library Section A', 4.5),
(9, 'Book 2', 'Author 2', 'ISBN002', 1, 'Publisher B', 'Non-Fiction', '2024-02-16 00:00:00', 'English', 'Description 2', 'Library Section B', 4.8),
(10, 'Book 3', 'Author 3', 'ISBN003', 7, 'Publisher C', 'Science', '2024-02-17 00:00:00', 'English', 'Description 3', 'Library Section C', 3.2),
(11, 'Book 4', 'Author 4', 'ISBN020', 10, 'Publisher D', 'Mystery', '2024-03-01 00:00:00', 'English', 'Description 20', 'Library Section D', 4);

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
(12, 8, 'Book 1', 8, 'Umesha', '2024-03-06 19:04:54'),
(16, 8, 'Book 1', 24, 'Umesha', '2024-03-06 19:04:54');

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
(2, 2, 'Jane Smith', 1, 'To Kill a Mockingbird', 7.80, '2024-02-10 00:00:00', '2024-03-05 00:00:00', 'Paid', '2024-03-06 13:52:47');

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
(2, 8, 'Book 1', 24, 'Umesha', '2024-02-16 00:00:00', 'umesha.pms@gmail.com');

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
(1, 8, 'Book 1', 24, 'Umesha', '2024-02-07 04:00:00'),
(2, 9, 'Book 2', 24, 'Umesha', '2024-02-08 00:00:00'),
(3, 10, 'Book 3', 25, 'Thisara', '2024-02-09 00:00:00'),
(4, 11, 'Book 4', 25, 'Thisara', '2024-02-10 00:00:00');

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
(24, 424112, '$2y$10$RdvbmzE7Obg8KCrksIvR7ORb/OOzpP3wPYIPyQIJvXJipJhcBG/nS', 'umesha', 'umesha.pms@gmail.com', 'Grade 11', 'Umesha', '2024-03-06 19:55:02'),
(25, 575343, '$2y$10$dX84LdRlT1gQwQ7UAUu/AeGsLfH5rHR0cdLzMz0SoqXsOeEESPKV2', 'thisara', 'thisarasadesh4@gmail.com', 'Grade 1', 'Thisara', '2024-03-01 14:54:47');

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
  MODIFY `borrowed_book_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `returned_book_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_no` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
