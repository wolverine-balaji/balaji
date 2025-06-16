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
-- Table structure for table `blood_requests`
--

CREATE TABLE `blood_requests` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `hospital_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `blood_group` varchar(10) NOT NULL DEFAULT 'pending',
  `status` varchar(20) NOT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_requests`
--

INSERT INTO `blood_requests` (`id`, `username`, `hospital_name`, `address`, `blood_group`, `status`, `requested_at`) VALUES
(1, 'hospcmc', 'cmc hospital', 'kdwh', 'A+', 'accepted', '2025-05-07 10:22:02'),
(2, 'hospcmc', 'cmc hospital', 'kdwh', 'A+', 'rejected', '2025-05-07 10:22:01'),
(3, 'hospcmc', 'cmc hospital', '', 'A+', 'accepted', '2025-05-07 10:22:00'),
(4, 'hospcmc', 'cmc hospital', '', 'A-', 'accepted', '2025-05-07 09:54:39'),
(5, '', 'Hospital Partner', '', 'A+', 'accepted', '2025-05-07 09:43:48'),
(6, '', 'cmc hospital', '', 'A+', 'accepted', '2025-05-07 10:21:51'),
(7, '', 'cmc hospital', '', 'AB-', 'accepted', '2025-05-07 10:01:05'),
(8, '', 'cmc hospital', '', 'AB+', 'accepted', '2025-05-07 10:24:06'),
(9, '', 'cmc hospital', '', 'A+', 'accepted', '2025-05-07 10:49:01'),
(10, '', 'cmc hospital', '', 'B-', 'accepted', '2025-05-07 14:56:17'),
(11, '', 'cmc hospital', '', 'AB+', 'accepted', '2025-05-07 15:05:40'),
(12, '', 'cmc hospital', '', 'O+', 'accepted', '2025-05-07 15:06:31'),
(13, '', 'cmc hospital', '', 'O+', 'accepted', '2025-05-07 17:12:12'),
(14, '', 'cmc hospital', '', 'A-', 'accepted', '2025-05-07 17:52:09'),
(15, '', 'govt hospital', '', 'A+', 'accepted', '2025-05-07 17:52:03'),
(16, '', 'cmc hospital', '', 'A+', 'rejected', '2025-05-17 16:15:31'),
(17, '', 'cmc hospital', '', 'B+', 'accepted', '2025-05-19 01:47:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_requests`
--
ALTER TABLE `blood_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_requests`
--
ALTER TABLE `blood_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
