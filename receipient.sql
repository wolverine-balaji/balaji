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
-- Table structure for table `receipient`
--

CREATE TABLE `receipient` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `age` int(10) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `phone` bigint(10) NOT NULL,
  `email` varchar(20) NOT NULL,
  `address` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipient`
--

INSERT INTO `receipient` (`id`, `name`, `age`, `gender`, `phone`, `email`, `address`, `username`, `password`) VALUES
(1, 'balaji', 20, 'Male', 2147483647, 'wolviebala248@gmail.', 'jdhhds', 'balaji', '123'),
(3, 'dhumbi', 20, 'Female', 2147483647, 'dhumbivinoda19@gmail', 'jdhhdfge', 'dhumbi', 'dheepikha'),
(4, 'a', 23, 'Male', 2147483647, 'app@gmail.com', 'mnjbhb', 'abc', 'def'),
(5, 'abhinaya', 21, 'Female', 2147483647, 'abi@gmail.com', 'jhdfdshf', 'abhi', 'abhi'),
(6, 'livi', 22, 'Male', 2147483647, 'livi@gmail.com', 'njdejhdhd', 'livi', 'living'),
(7, 'ba', 34, 'Male', 6384791159, 'ba@gmail.com', 'jgftf', 'ba', 'ba'),
(8, 'dharshan', 24, 'Male', 9688691482, 'dharshu@gmail.com', 'jdugdgd', 'dharsh', 'an'),
(9, 'dheeps', 20, 'Female', 1234567893, 'dheeps@gmail.com', 'galapagaos', 'dheeps', 'dheeps');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `receipient`
--
ALTER TABLE `receipient`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `receipient`
--
ALTER TABLE `receipient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
