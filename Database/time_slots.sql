-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 29, 2025 at 07:34 AM
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
-- Database: `db_appointment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `id` int(11) NOT NULL,
  `slot_date` date NOT NULL,
  `is_booked` tinyint(1) DEFAULT 0,
  `start_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`id`, `slot_date`, `is_booked`, `start_time`) VALUES
(1, '2025-03-30', 1, '00:00:00'),
(2, '2025-03-31', 1, '00:00:00'),
(3, '2025-04-04', 1, '10:00:00'),
(4, '2025-04-07', 1, '10:00:00'),
(5, '2025-04-07', 1, '13:30:00'),
(6, '2025-04-08', 1, '13:00:00'),
(7, '2025-04-08', 1, '14:38:00'),
(8, '2025-04-08', 1, '13:00:00'),
(9, '2025-04-23', 1, '10:00:00'),
(10, '2025-04-23', 1, '10:00:00'),
(11, '2025-04-25', 1, '10:00:00'),
(12, '2025-04-30', 1, '11:00:00'),
(13, '2025-04-30', 1, '13:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
