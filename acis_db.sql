-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2025 at 09:26 AM
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
-- Database: `acis_db`
--
CREATE DATABASE IF NOT EXISTS `acis_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `acis_db`;

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

DROP TABLE IF EXISTS `audit_trail`;
CREATE TABLE `audit_trail` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `availabilities`
--

DROP TABLE IF EXISTS `availabilities`;
CREATE TABLE `availabilities` (
  `id` int(11) NOT NULL,
  `inspector_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `availabilities`
--

INSERT INTO `availabilities` (`id`, `inspector_id`, `available_date`, `is_available`, `reason`) VALUES
(1, 4, '2025-10-15', 1, 'Initial auto-generated availability'),
(2, 4, '2025-10-16', 1, 'Initial auto-generated availability'),
(3, 4, '2025-10-17', 1, 'Initial auto-generated availability'),
(4, 4, '2025-10-20', 1, 'Initial auto-generated availability'),
(5, 4, '2025-10-21', 1, 'Initial auto-generated availability'),
(6, 4, '2025-10-22', 1, 'Initial auto-generated availability'),
(7, 4, '2025-10-23', 1, 'Initial auto-generated availability'),
(8, 4, '2025-10-24', 1, 'Initial auto-generated availability'),
(9, 4, '2025-10-27', 0, 'Personal Matter'),
(10, 4, '2025-10-28', 1, 'Initial auto-generated availability'),
(11, 4, '2025-10-29', 1, 'Initial auto-generated availability'),
(12, 4, '2025-10-30', 1, 'Initial auto-generated availability'),
(13, 4, '2025-10-31', 1, 'Initial auto-generated availability'),
(14, 4, '2025-11-03', 1, 'Initial auto-generated availability'),
(15, 4, '2025-11-04', 1, 'Initial auto-generated availability'),
(16, 4, '2025-11-05', 1, 'Initial auto-generated availability'),
(17, 4, '2025-11-06', 1, 'Initial auto-generated availability'),
(18, 4, '2025-11-07', 1, 'Initial auto-generated availability'),
(19, 4, '2025-11-10', 1, 'Initial auto-generated availability'),
(20, 4, '2025-11-11', 1, 'Initial auto-generated availability'),
(21, 4, '2025-11-12', 1, 'Initial auto-generated availability'),
(22, 4, '2025-11-13', 1, 'Initial auto-generated availability'),
(23, 4, '2025-11-14', 1, 'Initial auto-generated availability'),
(24, 7, '2025-10-15', 1, 'Initial auto-generated availability'),
(25, 7, '2025-10-16', 1, 'Initial auto-generated availability'),
(26, 7, '2025-10-17', 1, 'Initial auto-generated availability'),
(27, 7, '2025-10-20', 1, 'Initial auto-generated availability'),
(28, 7, '2025-10-21', 1, 'Initial auto-generated availability'),
(29, 7, '2025-10-22', 1, 'Initial auto-generated availability'),
(30, 7, '2025-10-23', 1, 'Initial auto-generated availability'),
(31, 7, '2025-10-24', 1, 'Initial auto-generated availability'),
(32, 7, '2025-10-27', 1, 'Initial auto-generated availability'),
(33, 7, '2025-10-28', 1, 'Initial auto-generated availability'),
(34, 7, '2025-10-29', 1, 'Initial auto-generated availability'),
(35, 7, '2025-10-30', 1, 'Initial auto-generated availability'),
(36, 7, '2025-10-31', 1, 'Initial auto-generated availability'),
(37, 7, '2025-11-03', 1, 'Initial auto-generated availability'),
(38, 7, '2025-11-04', 1, 'Initial auto-generated availability'),
(39, 7, '2025-11-05', 1, 'Initial auto-generated availability'),
(40, 7, '2025-11-06', 1, 'Initial auto-generated availability'),
(41, 7, '2025-11-07', 1, 'Initial auto-generated availability'),
(42, 7, '2025-11-10', 1, 'Initial auto-generated availability'),
(43, 7, '2025-11-11', 1, 'Initial auto-generated availability'),
(44, 7, '2025-11-12', 1, 'Initial auto-generated availability'),
(45, 7, '2025-11-13', 1, 'Initial auto-generated availability'),
(46, 7, '2025-11-14', 1, 'Initial auto-generated availability'),
(47, 9, '2025-10-15', 1, 'Initial auto-generated availability'),
(48, 9, '2025-10-16', 1, 'Initial auto-generated availability'),
(49, 9, '2025-10-17', 1, 'Initial auto-generated availability'),
(50, 9, '2025-10-20', 1, 'Initial auto-generated availability'),
(51, 9, '2025-10-21', 1, 'Initial auto-generated availability'),
(52, 9, '2025-10-22', 1, 'Initial auto-generated availability'),
(53, 9, '2025-10-23', 1, 'Initial auto-generated availability'),
(54, 9, '2025-10-24', 1, 'Initial auto-generated availability'),
(55, 9, '2025-10-27', 1, 'Initial auto-generated availability'),
(56, 9, '2025-10-28', 1, 'Initial auto-generated availability'),
(57, 9, '2025-10-29', 1, 'Initial auto-generated availability'),
(58, 9, '2025-10-30', 1, 'Initial auto-generated availability'),
(59, 9, '2025-10-31', 1, 'Initial auto-generated availability'),
(60, 9, '2025-11-03', 1, 'Initial auto-generated availability'),
(61, 9, '2025-11-04', 1, 'Initial auto-generated availability'),
(62, 9, '2025-11-05', 1, 'Initial auto-generated availability'),
(63, 9, '2025-11-06', 1, 'Initial auto-generated availability'),
(64, 9, '2025-11-07', 1, 'Initial auto-generated availability'),
(65, 9, '2025-11-10', 1, 'Initial auto-generated availability'),
(66, 9, '2025-11-11', 1, 'Initial auto-generated availability'),
(67, 9, '2025-11-12', 1, 'Initial auto-generated availability'),
(68, 9, '2025-11-13', 1, 'Initial auto-generated availability'),
(69, 9, '2025-11-14', 1, 'Initial auto-generated availability'),
(70, 10, '2025-10-15', 1, 'Initial auto-generated availability'),
(71, 10, '2025-10-16', 1, 'Initial auto-generated availability'),
(72, 10, '2025-10-17', 1, 'Initial auto-generated availability'),
(73, 10, '2025-10-20', 1, 'Initial auto-generated availability'),
(74, 10, '2025-10-21', 1, 'Initial auto-generated availability'),
(75, 10, '2025-10-22', 1, 'Initial auto-generated availability'),
(76, 10, '2025-10-23', 1, 'Initial auto-generated availability'),
(77, 10, '2025-10-24', 1, 'Initial auto-generated availability'),
(78, 10, '2025-10-27', 1, 'Initial auto-generated availability'),
(79, 10, '2025-10-28', 1, 'Initial auto-generated availability'),
(80, 10, '2025-10-29', 1, 'Initial auto-generated availability'),
(81, 10, '2025-10-30', 1, 'Initial auto-generated availability'),
(82, 10, '2025-10-31', 1, 'Initial auto-generated availability'),
(83, 10, '2025-11-03', 1, 'Initial auto-generated availability'),
(84, 10, '2025-11-04', 1, 'Initial auto-generated availability'),
(85, 10, '2025-11-05', 1, 'Initial auto-generated availability'),
(86, 10, '2025-11-06', 1, 'Initial auto-generated availability'),
(87, 10, '2025-11-07', 1, 'Initial auto-generated availability'),
(88, 10, '2025-11-10', 1, 'Initial auto-generated availability'),
(89, 10, '2025-11-11', 1, 'Initial auto-generated availability'),
(90, 10, '2025-11-12', 1, 'Initial auto-generated availability'),
(91, 10, '2025-11-13', 1, 'Initial auto-generated availability'),
(92, 10, '2025-11-14', 1, 'Initial auto-generated availability');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `establishment_name` varchar(150) NOT NULL,
  `address` text NOT NULL,
  `type` enum('residential','commercial','industrial','institutional','assembly','storage','miscellaneous') NOT NULL,
  `risk_level` enum('low','medium','high') DEFAULT 'low',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `owner_name`, `establishment_name`, `address`, `type`, `risk_level`, `status`, `created_at`) VALUES
(1, 'Nonie Pogi', 'Nonie Enterprise', 'Dubinan West, Santiago City', 'commercial', 'low', 'inactive', '2025-10-11 04:43:40'),
(2, 'Kazuma Ichinose', 'Oolong Bar', 'Purok 9, Barangay Mizushi, Kyoto City', 'commercial', 'medium', 'active', '2025-10-13 01:36:42'),
(3, 'Kaoruko Tsumugi', 'HoteCafe', 'Okoniyame, Daishi, Shinoya Prefecture', 'residential', 'low', 'active', '2025-10-14 01:05:15');

-- --------------------------------------------------------

--
-- Table structure for table `inspections`
--

DROP TABLE IF EXISTS `inspections`;
CREATE TABLE `inspections` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `inspector_id` int(11) NOT NULL,
  `inspection_type` enum('FSIC') DEFAULT 'FSIC',
  `scheduled_date` datetime NOT NULL,
  `actual_date` datetime DEFAULT NULL,
  `status` enum('scheduled','completed','missed','cancelled') DEFAULT 'scheduled',
  `remarks` text DEFAULT NULL,
  `risk_level` enum('low','moderate','high') DEFAULT 'low',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspection_results`
--

DROP TABLE IF EXISTS `inspection_results`;
CREATE TABLE `inspection_results` (
  `id` int(11) NOT NULL,
  `inspection_id` int(11) NOT NULL,
  `result` enum('passed','failed','conditional') DEFAULT 'conditional',
  `findings` text DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `encoded_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspectors`
--

DROP TABLE IF EXISTS `inspectors`;
CREATE TABLE `inspectors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `specialization` enum('general','electrical','mechanical','structural','hazardous') DEFAULT 'general',
  `status` enum('available','on_inspection','on_leave') DEFAULT 'available',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspectors`
--

INSERT INTO `inspectors` (`id`, `user_id`, `name`, `specialization`, `status`, `created_at`) VALUES
(4, 4, 'Aries Jeff Panganiban', 'general', 'on_inspection', '2025-10-10 09:46:15'),
(7, 9, 'Kenneth Mendoza', 'general', 'available', '2025-10-10 09:59:47'),
(9, 8, 'John Reneil Granada', 'hazardous', 'on_leave', '2025-10-10 20:00:05'),
(10, 2, 'Admin-Inspector4', 'mechanical', 'available', '2025-10-15 07:24:21');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','warning','alert') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduling_logs`
--

DROP TABLE IF EXISTS `scheduling_logs`;
CREATE TABLE `scheduling_logs` (
  `id` int(11) NOT NULL,
  `inspection_id` int(11) NOT NULL,
  `triggered_by` enum('system','admin') DEFAULT 'system',
  `reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','inspector') NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$CMSUfxUIqiZwjwer6rFV1eeVhpe7DUFvj2N69yPcuGr.S.KZWAT6O', 'admin', 'active', '2025-10-09 03:12:53', '2025-10-09 03:12:53'),
(2, 'admin2', '$2y$10$vv12TADU4YJ7PTUw5oqIKOLwfig85u7.f4gmg6J14y9gwr.Zh6F92', 'admin', 'inactive', '2025-10-09 07:52:57', '2025-10-09 07:52:57'),
(4, 'inspector1', '$2y$10$Kp3LGOqLu5UUOdS5Ho30ROyWICamx8Z18.2G0aJgcfiNqa9MuDkVq', 'inspector', 'active', '2025-10-09 21:01:27', '2025-10-09 21:01:27'),
(8, 'inspector2', '$2y$10$iMHUaN5E7l3ZSYLBEhTJne7j9GZh.G1OoIkdHkeIHoLv7a/PHjl1S', 'inspector', 'inactive', '2025-10-10 09:45:47', '2025-10-10 09:45:47'),
(9, 'inspector3', '$2y$10$bzvrbS6oWwEen8MKZiydX.ou1OJeUxqRTbZSKJRFQKrFYM62fKali', 'inspector', 'active', '2025-10-10 09:56:34', '2025-10-10 09:56:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `availabilities`
--
ALTER TABLE `availabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inspector_id` (`inspector_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inspections`
--
ALTER TABLE `inspections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `inspector_id` (`inspector_id`);

--
-- Indexes for table `inspection_results`
--
ALTER TABLE `inspection_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inspection_id` (`inspection_id`),
  ADD KEY `encoded_by` (`encoded_by`);

--
-- Indexes for table `inspectors`
--
ALTER TABLE `inspectors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `scheduling_logs`
--
ALTER TABLE `scheduling_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inspection_id` (`inspection_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `availabilities`
--
ALTER TABLE `availabilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inspections`
--
ALTER TABLE `inspections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inspection_results`
--
ALTER TABLE `inspection_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inspectors`
--
ALTER TABLE `inspectors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduling_logs`
--
ALTER TABLE `scheduling_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD CONSTRAINT `audit_trail_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `availabilities`
--
ALTER TABLE `availabilities`
  ADD CONSTRAINT `availabilities_ibfk_1` FOREIGN KEY (`inspector_id`) REFERENCES `inspectors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inspections`
--
ALTER TABLE `inspections`
  ADD CONSTRAINT `inspections_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `inspections_ibfk_2` FOREIGN KEY (`inspector_id`) REFERENCES `inspectors` (`id`);

--
-- Constraints for table `inspection_results`
--
ALTER TABLE `inspection_results`
  ADD CONSTRAINT `inspection_results_ibfk_1` FOREIGN KEY (`inspection_id`) REFERENCES `inspections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inspection_results_ibfk_2` FOREIGN KEY (`encoded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `inspectors`
--
ALTER TABLE `inspectors`
  ADD CONSTRAINT `inspectors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `scheduling_logs`
--
ALTER TABLE `scheduling_logs`
  ADD CONSTRAINT `scheduling_logs_ibfk_1` FOREIGN KEY (`inspection_id`) REFERENCES `inspections` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
