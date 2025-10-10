-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 09:03 AM
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
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `application_no` varchar(255) NOT NULL,
  `establishment_id` int(11) DEFAULT NULL,
  `permit_type_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `mode` enum('online','walk_in') NOT NULL DEFAULT 'online',
  `priority_level` enum('normal','urgent','expedite') DEFAULT 'normal',
  `submitted_at` datetime DEFAULT current_timestamp(),
  `received_at` datetime DEFAULT current_timestamp(),
  `current_status_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `source_reference` varchar(150) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspection_orders`
--

DROP TABLE IF EXISTS `inspection_orders`;
CREATE TABLE `inspection_orders` (
  `id` int(11) NOT NULL,
  `order_no` varchar(255) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `establishment_id` int(11) DEFAULT NULL,
  `assigned_inspector_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `scheduled_start` datetime DEFAULT NULL,
  `scheduled_end` datetime DEFAULT NULL,
  `actual_start` datetime DEFAULT NULL,
  `actual_end` datetime DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `priority` enum('normal','urgent') DEFAULT 'normal',
  `reschedule_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspection_results`
--

DROP TABLE IF EXISTS `inspection_results`;
CREATE TABLE `inspection_results` (
  `id` int(11) NOT NULL,
  `inspection_order_id` int(11) NOT NULL,
  `inspected_by` int(11) DEFAULT NULL,
  `inspected_at` datetime DEFAULT current_timestamp(),
  `result` enum('pass','fail','conditional') NOT NULL,
  `remarks` text DEFAULT NULL,
  `issued_permit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspection_statuses`
--

DROP TABLE IF EXISTS `inspection_statuses`;
CREATE TABLE `inspection_statuses` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspectors`
--

DROP TABLE IF EXISTS `inspectors`;
CREATE TABLE `inspectors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `employee_no` varchar(50) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspector_availability`
--

DROP TABLE IF EXISTS `inspector_availability`;
CREATE TABLE `inspector_availability` (
  `id` int(11) NOT NULL,
  `inspector_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `shift_start` time NOT NULL,
  `shift_end` time NOT NULL,
  `status` enum('available','off','on_leave') DEFAULT 'available',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inspector_specializations`
--

DROP TABLE IF EXISTS `inspector_specializations`;
CREATE TABLE `inspector_specializations` (
  `id` int(11) NOT NULL,
  `inspector_id` int(11) NOT NULL,
  `specialization` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notice_templates`
--

DROP TABLE IF EXISTS `notice_templates`;
CREATE TABLE `notice_templates` (
  `id` int(11) NOT NULL,
  `code` varchar(60) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `default_channel` enum('sms','email','both') DEFAULT 'sms',
  `days_before` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `establishment_id` int(11) DEFAULT NULL,
  `to_contact` varchar(150) NOT NULL,
  `channel` enum('sms','email') NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `message_text` text DEFAULT NULL,
  `status` enum('queued','sent','failed') DEFAULT 'queued',
  `sent_at` timestamp NULL DEFAULT NULL,
  `attempts` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue_entries`
--

DROP TABLE IF EXISTS `queue_entries`;
CREATE TABLE `queue_entries` (
  `id` int(36) NOT NULL,
  `queue_no` varchar(50) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `entered_at` datetime DEFAULT current_timestamp(),
  `status` enum('waiting','called','serving','skipped','completed','cancelled') DEFAULT 'waiting',
  `priority` int(11) DEFAULT 0,
  `estimated_wait_minutes` int(11) DEFAULT NULL,
  `serving_started_at` timestamp NULL DEFAULT NULL,
  `served_by` int(11) DEFAULT NULL,
  `served_at` timestamp NULL DEFAULT NULL,
  `source` enum('online','walk_in') DEFAULT 'walk_in',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile` varchar(25) NOT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `password`, `email`, `mobile`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'SystemA Administrator', '$2y$10$CMSUfxUIqiZwjwer6rFV1eeVhpe7DUFvj2N69yPcuGr.S.KZWAT6O', '', '', 1, '2025-10-09 03:12:53', '2025-10-09 03:12:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `application_no` (`application_no`),
  ADD KEY `idx_app_no` (`application_no`),
  ADD KEY `establishment_id` (`establishment_id`),
  ADD KEY `permit_type_id` (`permit_type_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `current_status_id` (`current_status_id`);

--
-- Indexes for table `inspection_orders`
--
ALTER TABLE `inspection_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_no` (`order_no`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `establishment_id` (`establishment_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `idx_inspector_schedule` (`assigned_inspector_id`,`scheduled_start`);

--
-- Indexes for table `inspection_results`
--
ALTER TABLE `inspection_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inspection_order_id` (`inspection_order_id`),
  ADD KEY `inspected_by` (`inspected_by`),
  ADD KEY `issued_permit_id` (`issued_permit_id`);

--
-- Indexes for table `inspection_statuses`
--
ALTER TABLE `inspection_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `inspectors`
--
ALTER TABLE `inspectors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `employee_no` (`employee_no`);

--
-- Indexes for table `inspector_availability`
--
ALTER TABLE `inspector_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_inspector_date` (`inspector_id`,`date`);

--
-- Indexes for table `inspector_specializations`
--
ALTER TABLE `inspector_specializations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inspector_id` (`inspector_id`);

--
-- Indexes for table `notice_templates`
--
ALTER TABLE `notice_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `establishment_id` (`establishment_id`),
  ADD KEY `template_id` (`template_id`),
  ADD KEY `idx_notifications_status` (`status`);

--
-- Indexes for table `queue_entries`
--
ALTER TABLE `queue_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_queue_status_priority` (`status`,`priority`,`entered_at`),
  ADD KEY `application_id` (`application_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `served_by` (`served_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`,`mobile`),
  ADD KEY `mobile` (`mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
