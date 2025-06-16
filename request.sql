-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 05:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `blood_type` varchar(10) NOT NULL,
  `status` enum('Pending','Accepted','Rejected') NOT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `username`, `state`, `district`, `blood_type`, `status`, `requested_at`) VALUES
(1, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-04-28 05:02:18'),
(2, 'balaji', 'Tamil Nadu', 'Chennai', 'A+', 'Rejected', '2025-04-28 05:02:40'),
(3, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-04-28 05:05:35'),
(4, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-04-28 05:08:01'),
(5, '', 'Maharashtra', 'Mumbai', 'A-', 'Accepted', '2025-04-28 05:11:21'),
(6, '', 'Tamil Nadu', 'Chennai', 'B+', 'Accepted', '2025-04-28 05:13:40'),
(7, '', 'karnataka', 'bangalore', 'a', 'Accepted', '2025-04-28 05:22:22'),
(8, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-04-28 05:26:04'),
(9, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-05-02 17:06:52'),
(10, '', 'Tamil Nadu', 'Chennai', 'A-', 'Accepted', '2025-05-06 16:40:54'),
(11, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-05-06 17:23:56'),
(12, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-05-06 17:38:53'),
(13, '', 'Tamil Nadu', 'Coimbatore', 'A+', 'Accepted', '2025-05-07 08:19:45'),
(14, '', 'Maharashtra', 'Pune', 'B+', 'Accepted', '2025-05-07 08:20:43'),
(15, '', 'Tamil Nadu', 'Madurai', 'A+', 'Accepted', '2025-05-07 08:21:33'),
(16, '', 'Maharashtra', 'Pune', 'A+', 'Accepted', '2025-05-07 10:01:41'),
(17, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-05-07 10:23:46'),
(18, '', 'Maharashtra', 'Nagpur', 'O+', 'Accepted', '2025-05-07 10:39:55'),
(19, '', 'Karnataka', 'Mysore', 'B-', 'Accepted', '2025-05-07 10:48:34'),
(20, '', 'Maharashtra', 'Nagpur', 'O+', 'Accepted', '2025-05-07 10:57:36'),
(21, '', 'Maharashtra', 'Mumbai', 'O+', 'Accepted', '2025-05-07 14:55:32'),
(22, '', 'Tamil Nadu', 'Chennai', 'A-', 'Accepted', '2025-05-07 17:11:43'),
(23, '', 'Maharashtra', 'Mumbai', 'A+', 'Accepted', '2025-05-07 17:16:57'),
(24, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-05-07 17:29:43'),
(25, '', 'Tamil Nadu', 'Chennai', 'A-', 'Accepted', '2025-05-09 09:40:39'),
(26, '', 'Tamil Nadu', 'Coimbatore', 'A+', 'Accepted', '2025-05-09 11:02:18'),
(27, '', 'Maharashtra', 'Pune', 'AB-', 'Rejected', '2025-05-11 05:34:59'),
(28, '', 'Maharashtra', 'Mumbai', 'O+', 'Rejected', '2025-05-11 05:35:28'),
(29, '', 'Tamil Nadu', 'Chennai', 'A+', 'Rejected', '2025-05-12 08:27:20'),
(30, '', 'Karnataka', 'Bangalore', 'O+', 'Accepted', '2025-05-12 09:19:16'),
(31, '', 'Karnataka', 'Bangalore', 'A-', 'Accepted', '2025-05-19 01:46:32'),
(32, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-06-11 04:09:12'),
(33, '', 'Tamil Nadu', 'Chennai', 'A+', 'Accepted', '2025-06-11 04:10:37'),
(34, '', 'Maharashtra', 'Mumbai', 'B+', 'Pending', '2025-06-12 02:54:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
