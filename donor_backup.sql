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
-- Table structure for table `donor_backup`
--

CREATE TABLE `donor_backup` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `blood_type` varchar(5) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `donation_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `donation_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor_backup`
--

INSERT INTO `donor_backup` (`id`, `name`, `age`, `gender`, `blood_type`, `contact_number`, `email`, `address`, `donation_date`, `created_at`, `state`, `city`, `donation_count`) VALUES
(1, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'nmjnhjb', '2025-05-19', '2025-05-15 01:41:17', 'Tamil Nadu', 'Chennai', 1),
(2, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'bhhgghv', '2025-05-19', '2025-05-15 01:41:58', 'Tamil Nadu', 'Chennai', 1),
(3, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'bhhgghv', '2025-05-19', '2025-05-15 01:48:26', 'Tamil Nadu', 'Chennai', 1),
(4, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-19', '2025-05-15 02:06:52', 'Tamil Nadu', 'Chennai', 1),
(5, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-19', '2025-05-15 02:18:46', 'Tamil Nadu', 'Chennai', 1),
(6, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-19', '2025-05-15 02:24:44', 'Tamil Nadu', 'Chennai', 1),
(7, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-19', '2025-05-15 02:31:08', 'Tamil Nadu', 'Chennai', 1),
(8, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-19', '2025-05-15 02:31:11', 'Tamil Nadu', 'Chennai', 1),
(9, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-19', '2025-05-15 02:34:05', 'Tamil Nadu', 'Chennai', 1),
(10, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-19', '2025-05-15 02:41:56', 'Tamil Nadu', 'Chennai', 1),
(11, 'dhumbi', 20, 'Female', 'B+', '8310128301', 'dhumbivinoda19@gmail.com', '.mkjk', '2025-05-19', '2025-05-19 02:07:12', 'Karnataka', 'Bangalore', 1),
(12, 'abhinaya', 21, 'Female', 'B+', '8310128300', 'ap@gmail.com', 'kjuh', '2025-05-19', '2025-05-19 02:09:00', 'Karnataka', 'Mysore', 1),
(13, 'abc', 21, 'Male', 'A-', '8310128309', 'livi1@gmail.com', 'kknj', '2025-05-19', '2025-05-19 02:10:05', 'Kerala', 'Kochi', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donor_backup`
--
ALTER TABLE `donor_backup`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donor_backup`
--
ALTER TABLE `donor_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
