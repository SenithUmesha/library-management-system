-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2024 at 09:50 PM
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
(12, 'Admin', '$2y$10$dUvIWKuLHMmjjq6kNoyrdebme.Ci2tIwJ/ScniWrwVobhBBRbOZVe', 'admin', 'umesha.pms@gmail.com', '2024-02-27 21:42:25');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `bookId` int(11) NOT NULL,
  `bookTitle` varchar(150) NOT NULL,
  `author` varchar(60) NOT NULL,
  `ISBN` varchar(30) NOT NULL,
  `bookCopies` varchar(10) NOT NULL,
  `publisherName` varchar(60) NOT NULL,
  `available` varchar(10) NOT NULL,
  `categories` varchar(30) NOT NULL,
  `callNumber` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`bookId`, `bookTitle`, `author`, `ISBN`, `bookCopies`, `publisherName`, `available`, `categories`, `callNumber`) VALUES
(6, 'Software Engineering', 'Robert Thompson', '123-423-4-13', '12', 'Robert Thompson.Inc', 'YES', 'Information Technology', '0216230.');

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `borrowId` int(11) NOT NULL,
  `memberName` varchar(255) NOT NULL,
  `matricNo` varchar(10) NOT NULL,
  `bookName` varchar(255) NOT NULL,
  `borrowDate` varchar(20) NOT NULL,
  `returnDate` varchar(20) NOT NULL,
  `bookId` int(2) NOT NULL,
  `borrowStatus` int(2) NOT NULL,
  `fine` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `newsId` int(11) NOT NULL,
  `announcement` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`newsId`, `announcement`) VALUES
(1, 'Welcome to Our Online Library Management System. You can have access to all our e-books at a really good affordable price!'),
(10, 'New books now available on our library. Please bring your student id along to borrow books'),
(11, 'This month fines list has been published. Be sure to check out if you are also in the list.');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `book_name` varchar(50) NOT NULL,
  `student_name` varchar(50) NOT NULL,
  `isbn` varchar(50) NOT NULL,
  `reserved_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `returned_books`
--

CREATE TABLE `returned_books` (
  `ID` int(11) NOT NULL,
  `Book Name` varchar(50) NOT NULL,
  `Member Name` varchar(50) NOT NULL,
  `Matric Number` varchar(50) NOT NULL,
  `Returned Date` date NOT NULL,
  `Actions` varchar(50) NOT NULL,
  `Rating` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `returned_books`
--

INSERT INTO `returned_books` (`ID`, `Book Name`, `Member Name`, `Matric Number`, `Returned Date`, `Actions`, `Rating`) VALUES
(1, 'Madolduwa', 'Thisara', '0w9wCa', '2024-02-14', '', 1);

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
(24, 424112, '$2y$10$RdvbmzE7Obg8KCrksIvR7ORb/OOzpP3wPYIPyQIJvXJipJhcBG/nS', 'umesha', 'umesha.pms@gmail.com', 'Grade 11', 'Umesha', '2024-02-27 21:43:20');

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
  ADD PRIMARY KEY (`bookId`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`borrowId`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`newsId`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `returned_books`
--
ALTER TABLE `returned_books`
  ADD PRIMARY KEY (`ID`);

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
  MODIFY `bookId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `borrowId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `newsId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `returned_books`
--
ALTER TABLE `returned_books`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_no` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
