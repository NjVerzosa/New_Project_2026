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
  `count` int(11) NOT NULL DEFAULT 0,
  `date` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `encode`
--

CREATE TABLE `data_entry` (
  `id` int(11) NOT NULL,
  `acc_number` int(255) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `cashout_history` (
  `id` int(11) NOT NULL,
  `acc_number` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `amount` int(255) NOT NULL DEFAULT 0,
  `ref` int(255) NOT NULL DEFAULT 0,
  `date_requested` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riddle`
--

CREATE TABLE `pick_one` (
  `id` int(11) NOT NULL,
  `acc_number` int(255) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT 0,
  `date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `my_referral` varchar(255) DEFAULT NULL,
  `my_referral_earnings` int(11) NOT NULL DEFAULT 0,
  `registered_at` varchar(255) DEFAULT NULL,
  `acc_number` int(11) NOT NULL DEFAULT 0,
  `role` varchar(255) NOT NULL DEFAULT 'Player',
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `code` int(11) NOT NULL DEFAULT 0,
  `profile` varchar(255) NOT NULL DEFAULT 'profile.jpg',
  `balance` DECIMAL(10,5) DEFAULT 0.00000,
  `total_income` int(11) NOT NULL DEFAULT 0,
  `number` int(11) NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL DEFAULT 0,
  `date_requested` varchar(255) DEFAULT NULL,
  `request_status` varchar(255) DEFAULT NULL,
  `last_login_date` varchar(255) DEFAULT NULL,
  `daily_login` int(11) NOT NULL DEFAULT 0,
  `daily_login_earnings` int(11) NOT NULL DEFAULT 0,
  `login_time` varchar(255) DEFAULT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `my_invitation_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--


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
ALTER TABLE `data_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `records`
--
ALTER TABLE `cashout_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `riddle`
--
ALTER TABLE `pick_one`
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
ALTER TABLE `data_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `cashout_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riddle`
--
ALTER TABLE `pick_one`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;


