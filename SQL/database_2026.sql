-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2026 at 06:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_2026`
--

-- --------------------------------------------------------

--
-- Table structure for table `all_tasks`
--

CREATE TABLE `all_tasks` (
  `id` int(11) NOT NULL,
  `acc_number` int(255) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `score` int(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `encode`
--

CREATE TABLE `encode` (
  `id` int(11) NOT NULL,
  `acc_number` int(255) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `score` int(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` int(11) NOT NULL,
  `acc_number` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `ref` varchar(255) DEFAULT NULL,
  `date_requested` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riddle`
--

CREATE TABLE `riddle` (
  `id` int(11) NOT NULL,
  `acc_number` int(255) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `score` int(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `my_referral` varchar(255) DEFAULT NULL,
  `my_referral_earnings` int(255) NOT NULL DEFAULT 0,
  `registered_at` varchar(255) DEFAULT NULL,
  `terms` int(255) NOT NULL DEFAULT 0,
  `acc_number` varchar(255) NOT NULL DEFAULT '0',
  `role` varchar(255) NOT NULL DEFAULT 'Player',
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` int(255) DEFAULT 0,
  `code` int(255) NOT NULL DEFAULT 0,
  `profile` varchar(255) NOT NULL DEFAULT 'profile.jpg',
  `balance` int(255) NOT NULL DEFAULT 50,
  `total_income` int(255) NOT NULL DEFAULT 0,
  `number` varchar(255) DEFAULT NULL,
  `amount` int(255) DEFAULT NULL,
  `date_requested` varchar(255) DEFAULT NULL,
  `request_status` varchar(255) DEFAULT NULL,
  `last_login_date` varchar(255) DEFAULT NULL,
  `daily_login` int(255) NOT NULL DEFAULT 0,
  `daily_login_earnings` int(255) NOT NULL DEFAULT 0,
  `login_time` varchar(255) DEFAULT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `my_invitation_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `my_referral`, `my_referral_earnings`, `registered_at`, `terms`, `acc_number`, `role`, `email`, `username`, `password`, `status`, `code`, `profile`, `balance`, `total_income`, `number`, `amount`, `date_requested`, `request_status`, `last_login_date`, `daily_login`, `daily_login_earnings`, `login_time`, `device_id`, `my_invitation_code`) VALUES
(2, 'C2OTL8IWAX', 0, 'Fri, 27 Feb 2026 02:31 PM', 0, '29471782', 'Player', 'njverzosa24@gmail.com', 'Developer', '$2y$10$3LCv.cUoYmhxGXRH.GEJgOxqNlPoSbN8Hg8VtYHEKSzjCSGfte49a', 1, 0, 'profile.jpg', 50, 0, NULL, NULL, NULL, NULL, 'Sun, 1 Mar 2026', 20, 0, '1:30 PM', '5371a81f-38d8-4df2-b4a9-f0269c58ab51', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `all_tasks`
--
ALTER TABLE `all_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `encode`
--
ALTER TABLE `encode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `riddle`
--
ALTER TABLE `riddle`
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
-- AUTO_INCREMENT for table `all_tasks`
--
ALTER TABLE `all_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `encode`
--
ALTER TABLE `encode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riddle`
--
ALTER TABLE `riddle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
