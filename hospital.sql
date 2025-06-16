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
-- Table structure for table `hospital`
--

CREATE TABLE `hospital` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hospital_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`id`, `username`, `password`, `hospital_name`, `email`, `phone`, `address`, `city`, `state`, `created_at`) VALUES
(1, 'hospkk', '$2y$10$qhVLfWfNzZLKj82H2FHP6OSXRGGa0jJ5mjSF8xnCJ.YQ7Qo/zlevS', 'kk hospitals', 'kkh@gmail.com', '8793564782', '', '', 'karnataka', '2025-05-04 17:28:12'),
(2, 'rk', 'rkh', 'rk hospital', 'rk@gmail.com', '9688691482', 'uhfuvhhv', 'Bengaluru', 'Karnataka', '2025-05-04 17:46:53'),
(3, 'hospkh', 'pkh', 'kh hospital', 'pkh@gmail.com', '9688691483', 'uhfuvhhv', 'Chennai', 'Tamil Nadu', '2025-05-04 17:48:19'),
(4, 'hospcmc', 'cmc', 'cmc hospital', 'cmc@gmail.com', '6361345678', 'uhfuvhhv', 'Chennai', 'Tamil Nadu', '2025-05-04 18:01:51'),
(6, 'hospgovt', 'govt', 'govt hospital', 'govt@gmail.com', '9445914810', 'qwerty', 'Madurai', 'Tamil Nadu', '2025-05-07 17:34:07'),
(7, 'hospbalanimanz', 'badam', 'balanimanz', 'bala@gmail.com', '1234567895', 'dgfdgh', 'Bengaluru', 'Karnataka', '2025-05-11 11:01:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hospital`
--
ALTER TABLE `hospital`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
