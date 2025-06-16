-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 05:00 AM
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
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `hospital_name` varchar(100) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `state` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `hospital_name`, `appointment_date`, `state`, `city`, `address`, `created_at`) VALUES
(1, 'cmc hospital', '2025-05-24 00:00:00', 'Tamil Nadu', 'Chennai', 'kdwh', '2025-05-05 01:35:07'),
(2, 'cmc hospital', '2025-05-17 00:00:00', 'Karnataka', 'Bangalore', 'kndnd', '2025-05-05 01:40:11'),
(3, 'cmc hospital', '2025-05-24 00:00:00', 'Karnataka', 'Bangalore', 'kddjd', '2025-05-05 01:45:27'),
(4, 'cmc hospital', '2025-05-15 00:00:00', 'Karnataka', 'Bangalore', 'qkfji2hfuhgh', '2025-05-05 01:57:29'),
(5, 'cmc hospital', '2025-05-14 00:00:00', 'Karnataka', 'Bangalore', 'khxhh', '2025-05-05 01:59:10'),
(6, 'cmc hospital', '2025-05-13 00:00:00', 'Maharashtra', 'Bangalore', 'jdncnc', '2025-05-05 02:02:11'),
(7, 'cmc hospital', '2025-05-22 00:00:00', 'Maharashtra', 'Mumbai', 'kciokcfokc', '2025-05-05 09:47:47'),
(8, 'cmc hospital', '2025-05-21 00:00:00', 'Karnataka', 'Bangalore', 'kjmugydg', '2025-05-05 09:48:22'),
(9, 'cmc hospital', '2025-05-13 00:00:00', 'Karnataka', 'Bangalore', 'qkfji2hfuhgh', '2025-05-09 11:07:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
