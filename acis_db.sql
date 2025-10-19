-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2025 at 07:08 PM
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
-- Table structure for table `availabilities`
--

DROP TABLE IF EXISTS `availabilities`;
CREATE TABLE `availabilities` (
  `id` int(11) NOT NULL,
  `inspector_id` int(11) NOT NULL,
  `available_date` date NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `reason` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `availabilities`
--

INSERT INTO `availabilities` (`id`, `inspector_id`, `available_date`, `is_available`, `reason`, `created`, `modified`) VALUES
(1, 4, '2025-10-20', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-19 16:34:22'),
(2, 4, '2025-10-21', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-19 07:20:06'),
(3, 4, '2025-10-22', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(4, 4, '2025-10-23', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(5, 4, '2025-10-24', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(6, 4, '2025-10-27', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(7, 4, '2025-10-28', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(8, 4, '2025-10-29', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(9, 4, '2025-10-30', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(10, 4, '2025-10-31', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(11, 4, '2025-11-03', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(12, 4, '2025-11-04', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(13, 4, '2025-11-05', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(14, 4, '2025-11-06', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(15, 4, '2025-11-07', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(16, 4, '2025-11-10', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(17, 4, '2025-11-11', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(18, 4, '2025-11-12', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(19, 4, '2025-11-13', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(20, 4, '2025-11-14', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(21, 4, '2025-11-17', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(22, 4, '2025-11-18', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(23, 7, '2025-10-20', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-19 16:16:23'),
(24, 7, '2025-10-21', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(25, 7, '2025-10-22', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-19 16:21:35'),
(26, 7, '2025-10-23', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(27, 7, '2025-10-24', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(28, 7, '2025-10-27', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(29, 7, '2025-10-28', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(30, 7, '2025-10-29', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(31, 7, '2025-10-30', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(32, 7, '2025-10-31', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(33, 7, '2025-11-03', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(34, 7, '2025-11-04', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(35, 7, '2025-11-05', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(36, 7, '2025-11-06', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(37, 7, '2025-11-07', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(38, 7, '2025-11-10', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(39, 7, '2025-11-11', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(40, 7, '2025-11-12', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(41, 7, '2025-11-13', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(42, 7, '2025-11-14', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(43, 7, '2025-11-17', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(44, 7, '2025-11-18', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(45, 9, '2025-10-20', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-19 16:21:35'),
(46, 9, '2025-10-21', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-19 12:21:44'),
(47, 9, '2025-10-22', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(48, 9, '2025-10-23', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(49, 9, '2025-10-24', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(50, 9, '2025-10-27', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(51, 9, '2025-10-28', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(52, 9, '2025-10-29', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(53, 9, '2025-10-30', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(54, 9, '2025-10-31', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(55, 9, '2025-11-03', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(56, 9, '2025-11-04', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(57, 9, '2025-11-05', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(58, 9, '2025-11-06', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(59, 9, '2025-11-07', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(60, 9, '2025-11-10', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(61, 9, '2025-11-11', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(62, 9, '2025-11-12', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(63, 9, '2025-11-13', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(64, 9, '2025-11-14', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(65, 9, '2025-11-17', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(66, 9, '2025-11-18', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(67, 13, '2025-10-20', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-19 16:19:00'),
(68, 13, '2025-10-21', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-19 12:15:32'),
(69, 13, '2025-10-22', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(70, 13, '2025-10-23', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(71, 13, '2025-10-24', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(72, 13, '2025-10-27', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(73, 13, '2025-10-28', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(74, 13, '2025-10-29', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(75, 13, '2025-10-30', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(76, 13, '2025-10-31', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(77, 13, '2025-11-03', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(78, 13, '2025-11-04', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(79, 13, '2025-11-05', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(80, 13, '2025-11-06', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(81, 13, '2025-11-07', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(82, 13, '2025-11-10', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(83, 13, '2025-11-11', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(84, 13, '2025-11-12', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(85, 13, '2025-11-13', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(86, 13, '2025-11-14', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(87, 13, '2025-11-17', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(88, 13, '2025-11-18', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(89, 14, '2025-10-20', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-19 16:21:35'),
(90, 14, '2025-10-21', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-19 07:20:06'),
(91, 14, '2025-10-22', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(92, 14, '2025-10-23', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(93, 14, '2025-10-24', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(94, 14, '2025-10-27', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(95, 14, '2025-10-28', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(96, 14, '2025-10-29', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(97, 14, '2025-10-30', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(98, 14, '2025-10-31', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(99, 14, '2025-11-03', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(100, 14, '2025-11-04', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(101, 14, '2025-11-05', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(102, 14, '2025-11-06', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(103, 14, '2025-11-07', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(104, 14, '2025-11-10', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(105, 14, '2025-11-11', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(106, 14, '2025-11-12', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(107, 14, '2025-11-13', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(108, 14, '2025-11-14', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(109, 14, '2025-11-17', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25'),
(110, 14, '2025-11-18', 1, 'Auto-generated to maintain 22-day window', '2025-10-18 21:46:25', '2025-10-18 21:46:25');

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
(1, 'Kaoruko Tsumugi', 'HoteCafe', 'safasasfa', 'commercial', 'low', 'active', '2025-10-19 16:22:43');

-- --------------------------------------------------------

--
-- Table structure for table `inspections`
--

DROP TABLE IF EXISTS `inspections`;
CREATE TABLE `inspections` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `inspector_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `actual_date` date DEFAULT NULL,
  `status` enum('scheduled','completed','missed','ongoing') DEFAULT 'scheduled',
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspections`
--

INSERT INTO `inspections` (`id`, `client_id`, `inspector_id`, `scheduled_date`, `actual_date`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '2025-10-24', NULL, 'scheduled', '', '2025-10-19 16:22:43', '2025-10-19 16:34:22');

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
  `status` enum('available','unavailable') DEFAULT 'available',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inspectors`
--

INSERT INTO `inspectors` (`id`, `user_id`, `name`, `specialization`, `status`, `created_at`) VALUES
(4, 4, 'FO2-Panganiban', 'general', 'available', '2025-10-10 09:46:15'),
(7, 9, 'FO2-Mendoza', 'mechanical', 'available', '2025-10-10 09:59:47'),
(9, 8, 'FO2-Granada', 'hazardous', 'available', '2025-10-10 20:00:05'),
(13, 2, 'FO1-Alaska', 'electrical', 'available', '2025-10-17 02:29:34'),
(14, 1, 'FO1-Nonie', 'structural', 'available', '2025-10-17 02:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `scheduling_logs`
--

DROP TABLE IF EXISTS `scheduling_logs`;
CREATE TABLE `scheduling_logs` (
  `id` int(11) NOT NULL,
  `inspection_id` int(11) NOT NULL,
  `old_date` date NOT NULL,
  `new_date` date NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scheduling_logs`
--

INSERT INTO `scheduling_logs` (`id`, `inspection_id`, `old_date`, `new_date`, `updated_by`, `created_at`) VALUES
(1, 1, '2025-10-20', '2025-10-24', 1, '2025-10-19 16:34:22');

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
(9, 'inspector3', '$2y$10$bzvrbS6oWwEen8MKZiydX.ou1OJeUxqRTbZSKJRFQKrFYM62fKali', 'inspector', 'active', '2025-10-10 09:56:34', '2025-10-10 09:56:34'),
(10, 'userTest', '$2y$10$wILuNTsa69adscnfr4rEoeX8kBnMjIs95mxJgJ0O0bZX0pgnLxs3G', 'inspector', 'active', '2025-10-17 22:08:44', '2025-10-17 22:08:44');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `inspectors`
--
ALTER TABLE `inspectors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_id` (`user_id`);

--
-- Indexes for table `scheduling_logs`
--
ALTER TABLE `scheduling_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inspection_id` (`inspection_id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `availabilities`
--
ALTER TABLE `availabilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inspections`
--
ALTER TABLE `inspections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inspectors`
--
ALTER TABLE `inspectors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `scheduling_logs`
--
ALTER TABLE `scheduling_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `availabilities`
--
ALTER TABLE `availabilities`
  ADD CONSTRAINT `availabilities_ibfk_1` FOREIGN KEY (`inspector_id`) REFERENCES `inspectors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inspections`
--
ALTER TABLE `inspections`
  ADD CONSTRAINT `inspections_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inspections_ibfk_2` FOREIGN KEY (`inspector_id`) REFERENCES `inspectors` (`id`);

--
-- Constraints for table `inspectors`
--
ALTER TABLE `inspectors`
  ADD CONSTRAINT `inspectors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `scheduling_logs`
--
ALTER TABLE `scheduling_logs`
  ADD CONSTRAINT `scheduling_logs_ibfk_1` FOREIGN KEY (`inspection_id`) REFERENCES `inspections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `scheduling_logs_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
