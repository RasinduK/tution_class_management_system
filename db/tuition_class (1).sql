-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2024 at 02:47 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tuition_class`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `date`, `student_id`, `status`) VALUES
(1, '2024-06-03', 1, 0),
(2, '2024-06-03', 5, 0),
(3, '2024-06-03', 8, 0),
(4, '2024-06-02', 2, 0),
(5, '2024-06-02', 9, 0),
(6, '2024-06-02', 1, 1),
(7, '2024-06-02', 5, 1),
(8, '2024-06-02', 8, 1),
(9, '2024-06-04', 1, 1),
(10, '2024-06-04', 5, 0),
(11, '2024-06-04', 8, 1),
(12, '2024-06-05', 3, 1),
(13, '2024-06-05', 6, 1),
(14, '2024-06-05', 10, 1),
(15, '2024-05-06', 1, 1),
(16, '2024-05-06', 5, 1),
(17, '2024-05-06', 8, 1),
(18, '2024-06-04', 4, 1),
(19, '2024-06-04', 7, 1),
(20, '2024-06-12', 1, 1),
(21, '2024-06-12', 5, 0),
(22, '2024-06-12', 8, 1),
(23, '2024-06-12', 11, 0),
(24, '2024-06-05', 1, 1),
(25, '2024-06-05', 5, 1),
(26, '2024-06-05', 8, 1),
(27, '2024-06-05', 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `st_no` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `school` varchar(100) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `dob` date NOT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `batch` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `st_no`, `full_name`, `address`, `school`, `grade`, `gender`, `dob`, `mobile_no`, `batch`, `category`, `created_at`) VALUES
(1, 'STU0001', 'John Doe', '123 Maple Street, Springfield', 'Springfield High', '10', 'Male', '2006-04-15', '555-1234', '2024', 'theory', '2024-06-03 04:30:00'),
(2, 'STU0002', 'Jane Smith', '456 Oak Avenue, Springfield', 'Springfield High', '11', 'Female', '2005-08-20', '555-5678', '2025', 'theory', '2024-06-03 04:30:00'),
(3, 'STU0003', 'Alice Johnson', '789 Pine Road, Springfield', 'Springfield High', '12', 'Female', '2004-11-30', '555-8765', '2026', 'theory', '2024-06-03 04:30:00'),
(4, 'STU0004', 'Robert Brown', '101 Birch Lane, Springfield', 'Springfield High', '9', 'Male', '2007-02-14', '555-4321', '2027', 'theory', '2024-06-03 04:30:00'),
(5, 'STU0005', 'Emily Davis', '202 Cedar Street, Springfield', 'Springfield High', '10', 'Female', '2006-05-22', '555-6789', '2024', 'theory', '2024-06-03 04:30:00'),
(6, 'STU0006', 'Michael Wilson', '303 Elm Avenue, Springfield', 'Springfield High', '11', 'Male', '2005-09-25', '555-9876', '2026', 'theory', '2024-06-03 04:30:00'),
(7, 'STU0007', 'Sophia Moore', '404 Fir Drive, Springfield', 'Springfield High', '12', 'Female', '2004-12-15', '555-5432', '2027', 'theory', '2024-06-03 04:30:00'),
(8, 'STU0008', 'James Taylor', '505 Willow Court, Springfield', 'Springfield High', '9', 'Male', '2007-03-10', '555-6543', '2024', 'theory', '2024-06-03 04:30:00'),
(9, 'STU0009', 'Isabella Anderson', '606 Aspen Road, Springfield', 'Springfield High', '10', 'Female', '2006-06-18', '555-7654', '2025', 'theory', '2024-06-03 04:30:00'),
(10, 'STU0010', 'William Martinez', '707 Hickory Street, Springfield', 'Springfield High', '11', 'Male', '2005-10-05', '555-8765', '2026', 'theory', '2024-06-03 04:30:00'),
(11, 'std0011', 'rasindu', '56/A,38 janaa', 'Rdcc', '12', 'Male', '2024-06-06', '0763466717', '2024', 'revision', '2024-06-04 06:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, '1111', '$2y$10$cN4QsOYrZHjqy0vK9PWU4.EyQHe3HowyERrIZpYHDpwvDA6vY.f6y'),
(2, '123', '$2y$10$VFo2YI6SIrs3bCoqPUPU7eGBTWH0mJU05TMmgOs9QjswhoENfjEDK'),
(3, '254', '$2y$10$gpaioX64R0O8pdBwk3cOJOg1.0v1M8gmf0hPNjIJ31UZrMS7eJ5Ey'),
(4, '1234', '$2y$10$sq9cDhT0TV/Dm.E3NE60VuOXQECjM1GBXhzt8/9GJ/aXjgNGsAqtu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
