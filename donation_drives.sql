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
-- Table structure for table `donation_drives`
--

CREATE TABLE `donation_drives` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `locality` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_drives`
--

INSERT INTO `donation_drives` (`id`, `date`, `time`, `state`, `city`, `locality`) VALUES
(1, '0000-00-00', '00:00:00', 'Tamil Nadu', 'Chennai', 'nghg'),
(2, '0000-00-00', '00:00:00', 'Tamil Nadu', 'Bangalore', 'nghg'),
(3, '2025-06-15', '00:00:00', 'Karnataka', 'Bangalore', 'Rajajinagar'),
(4, '0000-00-00', '00:00:00', 'Tamil Nadu', 'Chennai', 'nghg'),
(5, '0000-00-00', '00:00:00', 'Tamil Nadu', 'Bangalore', 'mg road'),
(6, '2025-06-10', '11:00:00', 'Tamil Nadu', 'Bangalore', 'nghg'),
(7, '2025-06-21', '15:00:00', 'Kerala', 'Chennai', 'kasthuri nagar');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donation_drives`
--
ALTER TABLE `donation_drives`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donation_drives`
--
ALTER TABLE `donation_drives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
