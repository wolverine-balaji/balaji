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
-- Table structure for table `donor`
--

CREATE TABLE `donor` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `donation_date` date NOT NULL DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `donation_count` int(5) NOT NULL,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor`
--

INSERT INTO `donor` (`id`, `name`, `age`, `gender`, `blood_type`, `contact_number`, `email`, `address`, `donation_date`, `created_at`, `state`, `city`, `donation_count`, `dob`) VALUES
(7, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'jkbhg', '2025-05-14', '2025-05-14 00:37:00', 'Tamil Nadu', 'Chennai', 0, NULL),
(8, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', '/nhbjb', '2025-05-14', '2025-05-14 00:37:26', 'Tamil Nadu', 'Chennai', 0, NULL),
(9, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', ',mkjnhb', '2025-05-14', '2025-05-14 00:37:50', 'Tamil Nadu', 'Chennai', 0, NULL),
(10, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'bhjbn', '2025-05-14', '2025-05-14 00:38:17', 'Tamil Nadu', 'Chennai', 0, NULL),
(11, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', '/nhbjb', '2025-05-14', '2025-05-14 00:38:24', 'Tamil Nadu', 'Chennai', 0, NULL),
(12, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'nmjnhjb', '2025-05-15', '2025-05-15 01:27:14', 'Tamil Nadu', 'Chennai', 0, NULL),
(13, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'nmjnhjb', '2025-05-15', '2025-05-15 01:29:35', 'Tamil Nadu', 'Chennai', 0, NULL),
(14, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'nmjnhjb', '2025-05-15', '2025-05-15 01:41:17', 'Tamil Nadu', 'Chennai', 0, NULL),
(15, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'bhhgghv', '2025-05-15', '2025-05-15 01:41:58', 'Tamil Nadu', 'Chennai', 0, NULL),
(16, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'bhhgghv', '2025-05-15', '2025-05-15 01:48:26', 'Tamil Nadu', 'Chennai', 0, NULL),
(17, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:06:52', 'Tamil Nadu', 'Chennai', 0, NULL),
(18, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:18:46', 'Tamil Nadu', 'Chennai', 0, NULL),
(19, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:24:44', 'Tamil Nadu', 'Chennai', 0, NULL),
(20, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:29:07', 'Tamil Nadu', 'Chennai', 0, NULL),
(21, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:31:08', 'Tamil Nadu', 'Chennai', 1, NULL),
(22, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:31:11', 'Tamil Nadu', 'Chennai', 1, NULL),
(23, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:34:05', 'Tamil Nadu', 'Chennai', 1, NULL),
(24, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:41:56', 'Tamil Nadu', 'Chennai', 1, NULL),
(25, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:55:22', 'Tamil Nadu', 'Chennai', 1, NULL),
(26, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:55:52', 'Tamil Nadu', 'Chennai', 2, NULL),
(27, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:56:01', 'Tamil Nadu', 'Chennai', 3, NULL),
(28, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'fuihuehf', '2025-05-15', '2025-05-15 02:56:08', 'Tamil Nadu', 'Chennai', 4, NULL),
(29, 'balaji', 20, 'Male', 'A+', '9043373013', 'wolviebala248@gmail.com', 'ijuhuh', '2025-05-19', '2025-05-19 01:45:37', 'Tamil Nadu', 'Chennai', 1, NULL),
(30, 'dhumbi', 20, 'Female', 'B+', '8310128301', 'dhumbivinoda19@gmail.com', '.mkjk', '2025-05-19', '2025-05-19 02:07:12', 'Karnataka', 'Bangalore', 1, NULL),
(31, 'abhinaya', 21, 'Female', 'B+', '8310128300', 'ap@gmail.com', 'kjuh', '2025-05-19', '2025-05-19 02:09:00', 'Karnataka', 'Mysore', 1, NULL),
(32, 'abc', 21, 'Male', 'A-', '8310128309', 'livi1@gmail.com', 'kknj', '2025-05-19', '2025-05-19 02:10:05', 'Kerala', 'Kochi', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donor`
--
ALTER TABLE `donor`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donor`
--
ALTER TABLE `donor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
