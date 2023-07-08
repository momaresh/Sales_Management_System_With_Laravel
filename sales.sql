-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2023 at 09:45 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sales`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `account_number` bigint(20) NOT NULL,
  `account_type` int(11) UNSIGNED NOT NULL,
  `is_parent` tinyint(1) NOT NULL DEFAULT 1,
  `parent_account_number` bigint(20) DEFAULT NULL,
  `start_balance_status` tinyint(4) NOT NULL COMMENT 'e 1-credit -2 debit 3-balanced',
  `start_balance` decimal(10,2) NOT NULL COMMENT 'دائن او مدين او متزن اول المدة',
  `current_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'هل مفعل',
  `notes` varchar(225) DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `com_code` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='جدول الشجرة المحاسبية العامة';

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `account_number`, `account_type`, `is_parent`, `parent_account_number`, `start_balance_status`, `start_balance`, `current_balance`, `active`, `notes`, `added_by`, `updated_by`, `created_at`, `updated_at`, `com_code`, `date`) VALUES
(1, 12345, 9, 1, NULL, 1, '0.00', '0.00', 1, 'الحساب الاب للعملاء', 1, 1, '2023-05-26 00:03:49', '2023-05-26 17:39:28', 1, '2023-05-26'),
(2, 12346, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للموردين', 1, 1, '2023-05-25 23:43:58', '2023-05-26 17:39:08', 1, '2023-05-25'),
(4, 12347, 3, 0, 12345, 3, '0.00', '0.00', 1, NULL, 1, 1, '2023-05-27 14:06:45', '2023-06-15 20:44:44', 1, '2023-05-27'),
(6, 12348, 3, 0, 12345, 3, '0.00', '-74748.00', 1, NULL, 1, 1, '2023-05-29 14:13:30', '2023-07-07 00:10:14', 1, '2023-05-29'),
(10, 12349, 2, 0, 12346, 3, '0.00', '-27800.00', 1, NULL, 1, NULL, '2023-05-29 18:35:09', '2023-07-07 00:46:44', 1, '2023-05-29'),
(11, 12353, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للعملاء', 2, NULL, '2023-05-31 19:49:17', '2023-05-31 19:49:17', 2, '2023-05-31'),
(12, 12354, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للمورين', 2, NULL, '2023-05-31 19:50:16', '2023-05-31 19:50:16', 2, '2023-05-31'),
(13, 12350, 3, 0, 12353, 3, '0.00', '-23500.00', 1, NULL, 2, NULL, '2023-05-31 19:56:07', '2023-07-08 19:17:49', 2, '2023-05-31'),
(14, 12351, 2, 0, 12354, 3, '0.00', '-56250.00', 1, NULL, 2, NULL, '2023-05-31 19:57:47', '2023-07-08 18:03:56', 2, '2023-05-31'),
(16, 12352, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للبنوك', 1, NULL, '2023-06-03 20:15:33', '2023-06-03 20:15:33', 1, '2023-06-03'),
(19, 12355, 6, 0, 12352, 3, '0.00', '-110000.00', 1, 'بنك الكريمي', 1, NULL, '2023-06-03 20:38:48', '2023-07-06 23:36:46', 1, '2023-06-03'),
(20, 12356, 3, 0, 12345, 3, '0.00', '-35280.00', 1, NULL, 1, 1, '2023-06-05 18:55:46', '2023-07-06 23:40:38', 1, '2023-06-05'),
(21, 12357, 2, 0, 12346, 3, '0.00', '0.00', 1, NULL, 1, 1, '2023-06-05 19:19:10', '2023-07-07 00:34:31', 1, '2023-06-05'),
(22, 12358, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للمناديب', 1, NULL, '2023-06-09 19:20:42', '2023-06-09 19:20:42', 1, '2023-06-09'),
(23, 12359, 4, 0, 12358, 3, '0.00', '3533.10', 1, NULL, 1, NULL, '2023-06-09 19:22:35', '2023-07-05 23:13:41', 1, '2023-06-09'),
(24, 12360, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للموظفين', 1, NULL, '2023-06-11 14:09:22', '2023-06-11 14:09:22', 1, '2023-06-11'),
(25, 12361, 6, 0, 12352, 3, '0.00', '0.00', 1, 'بنك الخليج', 1, 1, '2023-06-11 14:57:49', '2023-07-04 20:22:15', 1, '2023-06-11'),
(26, 12362, 3, 0, 12345, 3, '0.00', '0.00', 1, NULL, 1, NULL, '2023-06-11 19:59:17', '2023-06-15 23:58:12', 1, '2023-06-11'),
(27, 12363, 4, 0, 12358, 3, '0.00', '6458.80', 1, NULL, 1, NULL, '2023-06-11 20:20:38', '2023-07-07 00:10:14', 1, '2023-06-11'),
(28, 12364, 2, 0, 12346, 3, '0.00', '-260000.00', 1, NULL, 1, 1, '2023-06-11 20:34:16', '2023-07-07 00:40:54', 1, '2023-06-11'),
(31, 12365, 3, 0, 12345, 3, '0.00', '0.00', 1, NULL, 1, NULL, '2023-06-14 16:15:00', '2023-07-06 21:54:44', 1, '2023-06-14'),
(32, 12366, 3, 0, 12345, 3, '0.00', '0.00', 1, NULL, 1, NULL, '2023-06-14 16:18:53', '2023-06-15 23:39:56', 1, '2023-06-14'),
(33, 12367, 3, 0, 12345, 2, '1000.00', '0.00', 1, NULL, 1, 1, '2023-06-15 14:26:33', '2023-06-18 07:37:27', 1, '2023-06-15'),
(34, 12368, 4, 0, 12358, 1, '-100.00', '2000.00', 1, NULL, 1, 1, '2023-06-24 13:32:28', '2023-07-06 23:40:38', 1, '2023-06-24'),
(35, 12369, 3, 0, 12345, 3, '0.00', '38955.00', 1, NULL, 1, 1, '2023-06-24 14:23:50', '2023-07-06 22:48:15', 1, '2023-06-24'),
(36, 12370, 6, 0, 12352, 3, '0.00', '0.00', 1, 'بنك اليمن والخليج', 1, NULL, '2023-07-04 20:24:27', '2023-07-04 20:24:27', 1, '2023-07-04'),
(37, 12371, 3, 0, 12345, 3, '0.00', '0.00', 1, NULL, 1, NULL, '2023-07-05 19:31:04', '2023-07-05 23:13:41', 1, '2023-07-05'),
(38, 12372, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للخزن', 1, NULL, '2023-07-07 11:15:53', '2023-07-07 11:15:53', 1, '2023-07-07'),
(41, 12373, 14, 0, 12372, 2, '1000.00', '0.00', 1, NULL, 1, 1, '2023-06-24 13:32:28', '2023-07-07 12:08:12', 1, '2023-06-24'),
(42, 12374, 14, 0, 12372, 3, '0.00', '0.00', 1, NULL, 1, 1, '2023-06-24 13:32:28', '2023-07-06 23:40:38', 1, '2023-06-24'),
(43, 12375, 14, 0, 12372, 3, '0.00', '0.00', 1, NULL, 1, 1, '2023-06-24 13:32:28', '2023-07-06 23:40:38', 1, '2023-06-24'),
(44, 12376, 14, 0, 12372, 3, '0.00', '0.00', 1, NULL, 1, 1, '2023-06-24 13:32:28', '2023-07-06 23:40:38', 1, '2023-06-24'),
(45, 12377, 14, 0, 12372, 3, '0.00', '0.00', 1, NULL, 1, 1, '2023-06-24 13:32:28', '2023-07-06 23:40:38', 1, '2023-06-24'),
(46, 12378, 14, 0, 12372, 3, '0.00', '0.00', 1, NULL, 1, 1, '2023-06-24 13:32:28', '2023-07-06 23:40:38', 1, '2023-06-24'),
(47, 12379, 14, 0, 12372, 3, '0.00', '0.00', 1, NULL, 1, 1, '2023-07-07 18:43:52', '2023-07-07 18:44:49', 1, '2023-07-07'),
(48, 12380, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للخزن', 2, NULL, '2023-07-07 19:40:19', '2023-07-07 19:40:19', 2, '2023-07-07'),
(49, 12381, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للبنوك', 2, NULL, '2023-07-07 19:41:18', '2023-07-07 19:41:18', 2, '2023-07-07'),
(50, 12382, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للمناديب', 2, NULL, '2023-07-07 19:41:49', '2023-07-07 19:41:49', 2, '2023-07-07'),
(51, 12383, 9, 1, NULL, 3, '0.00', '0.00', 1, 'الحساب الاب للموضفين', 2, NULL, '2023-07-07 19:42:22', '2023-07-07 19:42:22', 2, '2023-07-07'),
(52, 12384, 3, 0, 12353, 3, '0.00', '22845.20', 1, NULL, 2, 2, '2023-07-07 21:40:05', '2023-07-08 19:26:48', 2, '2023-07-07'),
(53, 12385, 4, 0, 12382, 3, '0.00', '3675.00', 1, NULL, 2, 2, '2023-07-07 21:50:55', '2023-07-08 19:17:49', 2, '2023-07-07'),
(54, 12386, 2, 0, 12354, 3, '0.00', '0.00', 1, NULL, 2, 2, '2023-07-07 21:55:44', '2023-07-07 21:55:53', 2, '2023-07-07'),
(55, 12387, 14, 0, 12380, 3, '0.00', '1265.00', 1, NULL, 2, 2, '2023-07-08 09:22:44', '2023-07-08 19:41:15', 2, '2023-07-08'),
(56, 12388, 6, 0, 12381, 3, '0.00', '-20000.00', 1, 'بنك الكريمي', 2, NULL, '2023-07-08 19:20:20', '2023-07-08 19:20:39', 2, '2023-07-08');

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `related_internal_accounts` tinyint(4) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`id`, `name`, `active`, `related_internal_accounts`, `added_by`, `updated_by`, `created_at`, `updated_at`, `date`) VALUES
(1, 'رأس المال', 1, 0, 1, NULL, '2023-05-25 20:39:52', NULL, '2023-05-25'),
(2, 'مورد', 1, 1, 1, NULL, '2023-05-25 20:41:02', NULL, '2023-05-25'),
(3, 'عميل', 1, 1, 1, NULL, '2023-05-25 20:41:02', NULL, '2023-05-25'),
(4, 'مندوب', 1, 1, 1, NULL, '2023-05-25 20:41:02', NULL, '2023-05-25'),
(5, 'موظف', 1, 1, 1, NULL, '2023-05-25 20:41:02', NULL, '2023-05-25'),
(6, 'بنكي', 1, 0, 1, NULL, '2023-05-25 20:41:02', NULL, '2023-05-25'),
(7, 'مصروفات', 1, 0, 1, NULL, '2023-05-25 20:41:02', NULL, '2023-05-25'),
(8, 'قسم داخلي', 1, 1, 1, NULL, '2023-05-25 20:41:02', NULL, '2023-05-25'),
(9, 'عام', 1, 0, 1, NULL, '2023-05-25 20:41:02', NULL, '2023-05-25'),
(14, 'خزنة', 1, 1, 1, NULL, '2023-05-25 20:41:02', NULL, '2023-05-25');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(192) NOT NULL,
  `email` varchar(192) NOT NULL,
  `password` varchar(192) NOT NULL,
  `user_name` varchar(192) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `roles_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `user_name`, `active`, `roles_id`, `created_at`, `updated_at`, `added_by`, `updated_by`, `com_code`) VALUES
(1, 'Mohammed Maresh', 'maresh@gmail.com', '$2y$10$Ih2FiZggaCHs42oHAuE2fexSOTw4.Se6ELy1QhuWASDRkXHzQZeEG', 'mo_maresh', 1, 1, '2023-05-20 09:21:33', '2023-05-20 09:21:33', NULL, NULL, 1),
(2, 'علي دباش', 'ali@gmail.com', '$2y$10$Ih2FiZggaCHs42oHAuE2fexSOTw4.Se6ELy1QhuWASDRkXHzQZeEG', 'ali', 1, 7, '2023-05-31 22:21:59', NULL, 1, NULL, 2),
(3, 'موسى مارش', 'musa@gmail.com', '$2y$10$sR.B8hplbEu/ZugUERr3LOqe4J.udWS6xFMrMGDzpAL16WtTPh686', 'musa', 1, 1, '2023-06-22 11:04:43', '2023-06-22 11:29:16', 1, 1, 1),
(4, 'Moneeb Maresh', 'mohammed18@gmail.com', '$2y$10$8FjysQr.btgjg8HAbFx20uTWfZ8ppSYMVAQer4ncyN2/GJQOi.jui', 'momaresh', 1, 3, '2023-07-05 23:17:37', '2023-07-06 14:43:32', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_panel_settings`
--

CREATE TABLE `admin_panel_settings` (
  `id` int(11) NOT NULL,
  `com_code` int(11) NOT NULL,
  `system_name` varchar(255) NOT NULL,
  `photo` varchar(150) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `address` varchar(255) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `customer_parent_account` bigint(20) NOT NULL,
  `delegate_parent_account` bigint(20) NOT NULL,
  `supplier_parent_account` bigint(20) NOT NULL,
  `employee_parent_account` bigint(20) NOT NULL,
  `treasury_parent_account` bigint(20) DEFAULT NULL,
  `customer_first_code` varchar(192) NOT NULL,
  `delegate_first_code` varchar(192) NOT NULL,
  `supplier_first_code` varchar(192) NOT NULL,
  `employee_first_code` varchar(192) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_panel_settings`
--

INSERT INTO `admin_panel_settings` (`id`, `com_code`, `system_name`, `photo`, `active`, `address`, `phone`, `customer_parent_account`, `delegate_parent_account`, `supplier_parent_account`, `employee_parent_account`, `treasury_parent_account`, `customer_first_code`, `delegate_first_code`, `supplier_first_code`, `employee_first_code`, `added_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mo_Maresh soft', '1688604966698.png', 1, 'صنعاء-الدائري', '04536828', 12345, 12358, 12346, 12360, 12372, '232000', '231000', '233000', '234000', NULL, 1, NULL, '2023-07-07 08:16:03'),
(2, 2, 'مارش سوفت', '1688760153600.jpg', 1, 'تعز-شارع جمال', '02728921', 12353, 12382, 12354, 12383, 12380, '1020300', '7080900', '4050600', '6066300', 1, 2, '2023-05-31 19:18:35', '2023-07-07 17:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `admin_shifts`
--

CREATE TABLE `admin_shifts` (
  `id` bigint(20) NOT NULL,
  `shift_code` bigint(20) NOT NULL COMMENT 'كود الشفت المستخدم بالربط مع جدول حركة النقدية',
  `admin_id` int(11) NOT NULL,
  `treasuries_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL COMMENT 'توقيت بدايه الشفت',
  `end_date` datetime DEFAULT NULL,
  `is_finished` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'هل تم انتهاء الشفت',
  `delivered_to_shift_id` bigint(20) DEFAULT NULL COMMENT 'كود الشفت الذي تسلم هذا الشفت وارجعه',
  `money_should_delivered` decimal(10,2) DEFAULT NULL COMMENT 'النقدية التي يفترض ان تسلم ',
  `what_really_delivered` decimal(10,2) DEFAULT NULL COMMENT 'المبلغ الفعلي الذي تم تسلمه ',
  `money_state` tinyint(1) DEFAULT NULL COMMENT '0-blanced -1-inability 2-extra \r\nصفر متزن - واحد  يوجد عز - اثنين يوجد زيادة',
  `money_state_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `review_receive_date` datetime DEFAULT NULL COMMENT 'تاريخ مراجعه واستلام هذا الشفت',
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `finished_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `com_code` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='جدول شفتات الخزن للمستخدمين ';

-- --------------------------------------------------------

--
-- Table structure for table `admin_treasuries`
--

CREATE TABLE `admin_treasuries` (
  `admin_id` int(11) NOT NULL,
  `treasuries_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_treasuries`
--

INSERT INTO `admin_treasuries` (`admin_id`, `treasuries_id`, `active`, `added_by`, `updated_by`, `created_at`, `updated_at`, `com_code`) VALUES
(1, 1, 1, 1, NULL, '2023-06-01 08:19:43', NULL, 1),
(1, 4, 1, 2, NULL, '2023-06-01 08:25:15', NULL, 1),
(2, 8, 1, 2, NULL, '2023-07-08 07:59:28', '2023-07-08 07:59:28', 2),
(3, 3, 1, 1, NULL, '2023-06-22 10:14:33', '2023-06-22 10:14:33', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `person_id` int(11) NOT NULL,
  `customer_code` varchar(192) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`person_id`, `customer_code`, `created_at`, `updated_at`, `com_code`) VALUES
(1, '232001', '2023-06-05 15:55:46', '2023-06-05 15:56:04', 1),
(2, '232002', '2023-05-29 11:13:30', '2023-05-29 11:56:54', 1),
(4, '1020300', '2023-05-31 16:56:07', '2023-05-31 16:56:07', 2),
(6, '232003', '2023-06-05 15:55:46', '2023-06-05 15:56:04', 1),
(9, '232004', '2023-06-11 16:59:17', '2023-06-11 16:59:33', 1),
(12, '232005', '2023-06-14 13:15:00', '2023-06-14 13:15:00', 1),
(13, '232006', '2023-06-14 13:18:53', '2023-06-15 09:24:32', 1),
(14, '232007', '2023-06-15 11:26:33', '2023-06-15 11:26:33', 1),
(16, '232008', '2023-06-24 11:23:50', '2023-07-02 17:48:54', 1),
(17, '232009', '2023-07-05 16:31:04', '2023-07-05 16:31:04', 1),
(18, '1020301', '2023-07-07 18:40:06', '2023-07-07 18:42:32', 2);

-- --------------------------------------------------------

--
-- Table structure for table `delegates`
--

CREATE TABLE `delegates` (
  `person_id` int(11) NOT NULL,
  `delegate_code` varchar(192) NOT NULL,
  `percent_type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1- اجر ثابت\r\n2- نسبة على كل فاتورة',
  `percent_sales_commission_group` decimal(10,2) NOT NULL COMMENT 'نسبة عمولة المندوب بالمبيعات بالجملة',
  `percent_sales_commission_half_group` decimal(10,2) NOT NULL COMMENT 'عمول المندوب بمبيعات نص الجملة',
  `percent_sales_commission_one` decimal(10,2) NOT NULL COMMENT 'نسبة عمولة المندوب بالمبيعات قطاعلي',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delegates`
--

INSERT INTO `delegates` (`person_id`, `delegate_code`, `percent_type`, `percent_sales_commission_group`, `percent_sales_commission_half_group`, `percent_sales_commission_one`, `created_at`, `updated_at`, `com_code`) VALUES
(8, '231001', 1, '10.00', '5.00', '3.00', '2023-06-09 16:22:35', '2023-06-09 17:26:58', 1),
(10, '231002', 1, '7.00', '5.00', '3.00', '2023-06-11 17:20:38', '2023-06-11 17:20:47', 1),
(15, '231003', 2, '2000.00', '1500.00', '1000.00', '2023-06-24 10:32:28', '2023-07-06 19:47:47', 1),
(19, '7080901', 1, '5.00', '3.00', '2.00', '2023-07-07 18:50:55', '2023-07-07 18:51:06', 2);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_order_details`
--

CREATE TABLE `invoice_order_details` (
  `id` bigint(20) NOT NULL,
  `invoice_order_id` bigint(20) NOT NULL,
  `item_code` bigint(20) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `rejected_quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `production_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `batch_id` bigint(20) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='تفاصيل انصاف فاتورة المشتريات والمرتجعات';

-- --------------------------------------------------------

--
-- Table structure for table `invoice_order_header`
--

CREATE TABLE `invoice_order_header` (
  `id` bigint(20) NOT NULL,
  `pill_code` bigint(20) NOT NULL,
  `order_type` tinyint(1) NOT NULL COMMENT '1-order 2-return on same pill 3-return on general',
  `pill_number` varchar(25) DEFAULT NULL,
  `order_date` date NOT NULL COMMENT 'تاريخ الفاتورة',
  `discount_type` tinyint(1) DEFAULT NULL COMMENT 'نواع الخصم - واحد خصم نسبة  - اثنين خصم يدوي قيمة',
  `discount_percent` decimal(10,2) DEFAULT 0.00 COMMENT 'قيمة نسبة الخصم',
  `discount_value` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'قيمة الخصم',
  `tax_percent` decimal(10,2) DEFAULT 0.00 COMMENT 'نسبة الضريبة ',
  `total_before_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(10,2) DEFAULT 0.00 COMMENT 'القيمة الاجمالية النهائية للفاتورة',
  `pill_type` tinyint(1) DEFAULT NULL COMMENT 'نوع الفاتورة - كاش او اجل  - واحد واثنين',
  `what_paid` decimal(10,2) DEFAULT 0.00,
  `what_remain` decimal(10,2) DEFAULT 0.00,
  `money_for_account` decimal(10,2) DEFAULT NULL,
  `notes` varchar(225) DEFAULT NULL COMMENT 'اجمالي الفاتورة قبل الخصم',
  `invoice_type` tinyint(1) NOT NULL COMMENT '1- purchase 2- sales',
  `is_original_return` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='جدول مشتريات ومترجعات المودين ';

-- --------------------------------------------------------

--
-- Table structure for table `inv_item_card`
--

CREATE TABLE `inv_item_card` (
  `id` bigint(20) NOT NULL,
  `item_code` bigint(20) NOT NULL,
  `barcode` varchar(250) NOT NULL,
  `name` varchar(225) NOT NULL,
  `item_type` tinyint(1) NOT NULL COMMENT 'واحد  مخزني - اتنين استهلاكي - ثلاثه عهده',
  `inv_itemcard_categories_id` int(11) NOT NULL,
  `parent_inv_itemcard_id` bigint(20) DEFAULT NULL COMMENT 'كود الصنف الاب له',
  `does_has_retailunit` tinyint(1) NOT NULL COMMENT 'هل للصنف وحده تجزئة',
  `retail_unit_id` int(11) DEFAULT NULL COMMENT 'كود وحده  قياس التجزئة ',
  `unit_id` int(11) NOT NULL COMMENT 'كود وحده  قياس الاب',
  `retail_uom_quntToParent` decimal(10,2) DEFAULT NULL,
  `price_per_one_in_master_unit` decimal(10,2) NOT NULL COMMENT 'سعر الحبة في الوحدة الاساسية',
  `price_per_half_group_in_master_unit` decimal(10,2) NOT NULL COMMENT 'سعر نص جملة في الوحدة الاساسية',
  `price_per_group_in_master_unit` decimal(10,2) NOT NULL COMMENT 'سعر جملة في الوحدة الاساسية',
  `cost_price_in_master` decimal(10,2) NOT NULL COMMENT 'سعر التكلفة بالوحدة الاساسية',
  `price_per_one_in_retail_unit` decimal(10,2) DEFAULT NULL COMMENT 'سعر الحبة في الوحدة التجزئة',
  `price_per_half_group_in_retail_unit` decimal(10,2) DEFAULT NULL COMMENT 'سعر نص جملة في الوحدة التجزئة',
  `price_per_group_in_retail_unit` decimal(10,2) DEFAULT NULL COMMENT 'سعر الجملة في الوحدة التجزئة',
  `cost_price_in_retail` decimal(10,2) DEFAULT NULL COMMENT 'سعر التكلفة بالوحدة التجزئة',
  `has_fixed_price` tinyint(1) NOT NULL,
  `all_quantity_with_master_unit` decimal(10,2) DEFAULT NULL,
  `all_quantity_with_retail_unit` decimal(10,2) DEFAULT NULL,
  `remain_quantity_in_retail` decimal(10,2) DEFAULT NULL,
  `item_img` varchar(200) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` date DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `inv_item_card`
--

INSERT INTO `inv_item_card` (`id`, `item_code`, `barcode`, `name`, `item_type`, `inv_itemcard_categories_id`, `parent_inv_itemcard_id`, `does_has_retailunit`, `retail_unit_id`, `unit_id`, `retail_uom_quntToParent`, `price_per_one_in_master_unit`, `price_per_half_group_in_master_unit`, `price_per_group_in_master_unit`, `cost_price_in_master`, `price_per_one_in_retail_unit`, `price_per_half_group_in_retail_unit`, `price_per_group_in_retail_unit`, `cost_price_in_retail`, `has_fixed_price`, `all_quantity_with_master_unit`, `all_quantity_with_retail_unit`, `remain_quantity_in_retail`, `item_img`, `active`, `added_by`, `updated_by`, `created_at`, `updated_at`, `date`, `com_code`) VALUES
(1, 1, 'item1', 'دقيق السنابل', 2, 9, NULL, 1, 6, 5, '40.00', '9240.00', '8988.00', '8820.00', '8400.00', '231.00', '224.70', '220.50', '210.00', 1, '23.00', '920.00', '0.00', NULL, 1, 1, 1, '2023-07-07 00:46:44', '2023-07-06 21:46:44', '2023-06-24', 1),
(2, 2, 'item2', 'فاصلوياء حمراء', 2, 11, NULL, 1, 10, 4, '24.00', '49500.00', '48150.00', '47250.00', '45000.00', '2062.50', '2006.25', '1968.75', '1875.00', 1, '3.00', '76.00', '4.00', NULL, 1, 1, 1, '2023-07-07 00:46:45', '2023-07-06 21:46:45', '2023-07-04', 1),
(3, 3, 'item3', 'مشمع تاركت هندي', 1, 13, NULL, 1, 12, 11, '31.00', '49500.00', '48150.00', '47250.00', '45000.00', '1596.77', '1553.23', '1524.19', '1451.61', 0, '2.00', '62.00', '0.00', NULL, 1, 1, 1, '2023-07-07 00:32:11', '2023-07-06 21:32:11', '2023-07-04', 1),
(4, 4, 'item4', 'رز بسمتي', 2, 14, NULL, 1, 6, 5, '40.00', '8800.00', '8560.00', '8400.00', '8000.00', '220.00', '214.00', '210.00', '200.00', 1, '1.00', '40.00', '0.00', NULL, 1, 1, NULL, '2023-07-07 00:40:54', '2023-07-06 21:40:54', '2023-06-24', 1),
(5, 5, 'item5', 'رز الفاخر', 2, 14, NULL, 1, 6, 5, '40.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, NULL, NULL, NULL, NULL, 1, 1, NULL, '2023-07-04 17:18:18', '2023-07-04 17:18:18', '2023-07-04', 1),
(6, 1, 'item1', 'فاصلوياء حمراء', 2, 17, NULL, 1, 15, 14, '24.00', '7700.00', '7490.00', '7350.00', '7000.00', '320.83', '312.08', '306.25', '291.67', 1, '15.00', '362.00', '2.00', '1688765342719.jpg', 1, 2, 2, '2023-07-08 19:26:48', '2023-07-08 16:26:48', '2023-07-07', 2);

-- --------------------------------------------------------

--
-- Table structure for table `inv_item_card_batches`
--

CREATE TABLE `inv_item_card_batches` (
  `id` bigint(20) NOT NULL,
  `batch_code` bigint(20) NOT NULL,
  `store_id` int(11) NOT NULL COMMENT 'كود المخزن',
  `item_code` bigint(20) NOT NULL COMMENT 'كود الصنف الالي ',
  `inv_unit_id` int(11) NOT NULL COMMENT 'كود الوحده الاب ',
  `unit_cost_price` decimal(10,2) NOT NULL COMMENT 'سعر الشراء للوحده',
  `quantity` decimal(10,2) NOT NULL COMMENT 'الكمية بالوحده الاب',
  `total_cost_price` decimal(10,2) NOT NULL COMMENT 'اجمالي سعر شراء الباتش ككل',
  `production_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='جدول  باتشات الاصناف بالمخازن';

-- --------------------------------------------------------

--
-- Table structure for table `inv_item_card_movements`
--

CREATE TABLE `inv_item_card_movements` (
  `id` bigint(20) NOT NULL,
  `inv_item_card_movements_categories_id` int(11) NOT NULL,
  `item_code` bigint(20) NOT NULL,
  `inv_item_card_movements_types_id` int(11) NOT NULL,
  `order_header_id` bigint(20) DEFAULT NULL,
  `order_details_id` bigint(20) DEFAULT NULL,
  `store_id` int(11) NOT NULL,
  `batch_id` bigint(20) NOT NULL,
  `quantity_before_movement` varchar(60) NOT NULL,
  `quantity_after_movement` varchar(60) NOT NULL,
  `quantity_before_movement_in_current_store` varchar(60) DEFAULT NULL,
  `quantity_after_movement_in_current_store` varchar(60) DEFAULT NULL,
  `byan` varchar(100) NOT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` date NOT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inv_item_card_movements_categories`
--

CREATE TABLE `inv_item_card_movements_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `inv_item_card_movements_categories`
--

INSERT INTO `inv_item_card_movements_categories` (`id`, `name`) VALUES
(1, 'حركة علي المشتريات'),
(2, 'حركة علي المبيعات'),
(3, 'حركة علي المخازن');

-- --------------------------------------------------------

--
-- Table structure for table `inv_item_card_movements_types`
--

CREATE TABLE `inv_item_card_movements_types` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `inv_item_card_movements_types`
--

INSERT INTO `inv_item_card_movements_types` (`id`, `type`) VALUES
(1, 'مشتريات '),
(2, 'مرتجع مشتريات بأصل الفاتورة'),
(3, 'مرتجع مشتريات عام'),
(4, 'مبيعات'),
(5, 'مرتجع مبيعات'),
(6, 'صرف داخلي لمندوب'),
(7, 'مرتجع صرف داخلي لمندوب'),
(8, 'تحويل بين مخازن'),
(9, 'مبيعات صرف مباشر لعميل'),
(10, 'مبيعات صرف لمندوب التوصيل'),
(11, 'صرف خامات لخط التصنيع'),
(12, 'رد خامات من خط التصنيع'),
(13, 'استلام انتاج تام من خط التصنيع'),
(14, 'رد انتاج تام الي خط التصنيع'),
(15, 'جرد بالمخازن'),
(16, 'مرتجع مبيعات بأصل الفاتورة');

-- --------------------------------------------------------

--
-- Table structure for table `inv_item_categories`
--

CREATE TABLE `inv_item_categories` (
  `id` int(11) NOT NULL,
  `category_code` int(11) NOT NULL,
  `name` varchar(192) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `com_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inv_item_categories`
--

INSERT INTO `inv_item_categories` (`id`, `category_code`, `name`, `active`, `added_by`, `updated_by`, `created_at`, `updated_at`, `date`, `com_code`) VALUES
(2, 1, 'الكترونيات', 1, 1, 1, '2023-05-23 16:34:03', '2023-05-23 17:10:51', NULL, 1),
(9, 2, 'الطحين', 1, 1, NULL, '2023-05-24 05:32:27', '2023-05-24 05:32:27', NULL, 1),
(10, 3, 'كمبيوترات', 1, 1, NULL, '2023-06-05 16:52:36', '2023-06-05 16:52:36', NULL, 1),
(11, 4, 'بقوليات', 1, 1, 1, '2023-06-06 05:57:30', '2023-06-06 06:00:49', NULL, 1),
(13, 5, 'مفروشات', 1, 1, 1, '2023-06-12 18:40:31', '2023-06-12 18:44:10', NULL, 1),
(14, 6, 'ارز', 1, 1, NULL, '2023-06-24 10:18:35', '2023-06-24 10:18:35', NULL, 1),
(16, 1, 'الفواكه', 1, 2, NULL, '2023-07-07 17:48:51', '2023-07-07 17:48:51', NULL, 2),
(17, 2, 'البقوليات', 1, 2, NULL, '2023-07-07 17:49:08', '2023-07-07 17:49:08', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `inv_stores_inventory_details`
--

CREATE TABLE `inv_stores_inventory_details` (
  `id` bigint(20) NOT NULL,
  `inv_stores_inventory_header_id` bigint(20) NOT NULL,
  `item_code` bigint(20) NOT NULL,
  `batch_id` bigint(20) NOT NULL,
  `old_quantity` decimal(10,2) NOT NULL,
  `new_quantity` decimal(10,2) NOT NULL,
  `different_quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` varchar(225) DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `closed_by` int(11) DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='تفاصيل امر الجرد';

-- --------------------------------------------------------

--
-- Table structure for table `inv_stores_inventory_header`
--

CREATE TABLE `inv_stores_inventory_header` (
  `id` bigint(20) NOT NULL,
  `inventory_code` bigint(20) NOT NULL,
  `store_id` int(11) NOT NULL COMMENT 'مخزن الجرد',
  `inventory_date` date NOT NULL,
  `inventory_type` tinyint(1) NOT NULL COMMENT 'واحد جرد يومي - اثنين جرد اسبوعي - ثلاثه جرد شهري - اربعه جرد سنوي ',
  `notes` varchar(225) DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'هل امر الجرد مغلق ومرحل',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `closed_by` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='جدول جرد المخازن';

-- --------------------------------------------------------

--
-- Table structure for table `inv_units`
--

CREATE TABLE `inv_units` (
  `id` int(11) NOT NULL,
  `unit_code` int(11) NOT NULL,
  `name` varchar(192) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `master` tinyint(1) NOT NULL DEFAULT 1,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `com_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inv_units`
--

INSERT INTO `inv_units` (`id`, `unit_code`, `name`, `active`, `master`, `added_by`, `updated_by`, `created_at`, `updated_at`, `date`, `com_code`) VALUES
(4, 1, 'كرتون', 1, 1, 1, 1, '2023-05-23 13:42:02', '2023-05-23 13:51:24', NULL, 1),
(5, 2, 'كيس', 1, 1, 1, 1, '2023-05-23 13:42:17', '2023-05-23 14:53:07', NULL, 1),
(6, 3, 'كيلو', 1, 0, 1, 1, '2023-05-23 13:42:33', '2023-06-12 18:36:52', NULL, 1),
(8, 4, 'درزن', 1, 1, 1, NULL, '2023-06-05 16:55:17', '2023-06-05 16:55:17', NULL, 1),
(9, 5, 'حبة', 1, 1, 1, 1, '2023-06-05 16:56:09', '2023-06-24 09:49:45', NULL, 1),
(10, 6, 'علبة', 1, 0, 1, NULL, '2023-06-06 06:01:27', '2023-06-06 06:01:27', NULL, 1),
(11, 7, 'لفة مشمع', 1, 1, 1, NULL, '2023-06-12 18:17:48', '2023-06-12 18:17:48', NULL, 1),
(12, 8, 'متر', 1, 0, 1, NULL, '2023-06-12 18:18:03', '2023-06-12 18:18:03', NULL, 1),
(13, 9, 'طن حديد', 1, 1, 1, NULL, '2023-07-04 17:08:46', '2023-07-04 17:08:46', NULL, 1),
(14, 1, 'كرتون', 1, 1, 2, NULL, '2023-07-07 17:30:26', '2023-07-07 17:30:26', NULL, 2),
(15, 2, 'علبة', 1, 0, 2, 2, '2023-07-07 17:30:41', '2023-07-07 18:16:56', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `move_types`
--

CREATE TABLE `move_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `in_screen` tinyint(1) NOT NULL COMMENT '1-exchange 2-collect',
  `is_private_internal` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='الحركة علي الخزنة';

--
-- Dumping data for table `move_types`
--

INSERT INTO `move_types` (`id`, `name`, `active`, `in_screen`, `is_private_internal`) VALUES
(1, 'مراجعة واستلام نقدية شفت من خزنة', 1, 2, 0),
(2, 'صرف نقدية شفت الى خزنة', 1, 1, 0),
(3, 'صرف مبلغ لحساب مالي', 1, 1, 0),
(4, 'تحصيل مبلغ من حساب مالي', 1, 2, 0),
(5, 'تحصيل ايراد مبيعات', 1, 2, 0),
(6, 'صرف نظير مرتجع مبيعات', 1, 1, 0),
(8, 'صرف سلفة علي راتب موظف', 1, 1, 1),
(9, 'صرف نظير مشتريات من مورد', 1, 1, 0),
(10, 'تحصيل نظير مرتجع مشتريات من مورد', 1, 2, 0),
(16, 'ايراد زيادة راس المال', 1, 2, 0),
(17, 'مصاريف شراء مثل النولون', 1, 1, 0),
(18, 'صرف للإيداع البنكي', 1, 1, 0),
(21, 'رد سلفة علي راتب موظف', 1, 2, 1),
(22, 'تحصيل خصومات موظفين', 1, 2, 1),
(24, 'صرف مرتب لموظف', 1, 1, 1),
(25, 'سحب من البنك\r\n', 1, 2, 0),
(26, 'صرف لرد رأس المال', 1, 1, 0),
(27, 'صرف عمولة مبيعات لمندوب', 1, 1, 0),
(28, 'تحصيل عمولة مرتجع مبيعات من مندوب', 1, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `original_return_invoice`
--

CREATE TABLE `original_return_invoice` (
  `invoice_order_id` bigint(20) NOT NULL,
  `pill_type` tinyint(4) NOT NULL,
  `what_paid` decimal(10,2) NOT NULL,
  `what_remain` decimal(10,2) NOT NULL,
  `return_date` date NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `money_for_account` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_roles_with_main_menu`
--

CREATE TABLE `permission_roles_with_main_menu` (
  `roles_id` int(11) NOT NULL,
  `roles_main_menu_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_by` int(11) NOT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permission_roles_with_main_menu`
--

INSERT INTO `permission_roles_with_main_menu` (`roles_id`, `roles_main_menu_id`, `created_at`, `added_by`, `com_code`) VALUES
(1, 2, '2023-06-21 20:28:33', 1, 1),
(1, 3, '2023-06-21 18:48:42', 1, 1),
(1, 4, '2023-06-21 18:50:53', 1, 1),
(1, 5, '2023-06-23 11:53:52', 1, 1),
(1, 6, '2023-06-21 18:50:53', 1, 1),
(1, 7, '2023-06-22 16:03:42', 3, 1),
(1, 8, '2023-06-23 11:53:52', 1, 1),
(1, 9, '2023-06-22 11:17:29', 1, 1),
(3, 4, '2023-07-06 11:40:37', 1, 1),
(7, 2, '2023-06-21 20:28:33', 2, 2),
(7, 3, '2023-06-21 18:48:42', 2, 2),
(7, 4, '2023-06-21 18:50:53', 2, 2),
(7, 5, '2023-06-23 11:53:52', 2, 2),
(7, 6, '2023-06-21 18:50:53', 2, 2),
(7, 7, '2023-06-22 16:03:42', 2, 2),
(7, 8, '2023-06-23 11:53:52', 2, 2),
(7, 9, '2023-06-22 11:17:29', 2, 2),
(8, 3, '2023-06-23 18:48:24', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `permission_roles_with_sub_menu`
--

CREATE TABLE `permission_roles_with_sub_menu` (
  `roles_id` int(11) NOT NULL,
  `roles_main_menu_id` int(11) NOT NULL,
  `roles_sub_menu_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_by` int(11) NOT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permission_roles_with_sub_menu`
--

INSERT INTO `permission_roles_with_sub_menu` (`roles_id`, `roles_main_menu_id`, `roles_sub_menu_id`, `created_at`, `added_by`, `com_code`) VALUES
(1, 2, 3, '2023-06-21 20:28:50', 1, 1),
(1, 2, 4, '2023-06-21 20:28:50', 1, 1),
(1, 2, 5, '2023-06-21 20:28:50', 1, 1),
(1, 2, 6, '2023-06-21 20:28:50', 1, 1),
(1, 9, 8, '2023-06-22 11:24:16', 1, 1),
(1, 3, 9, '2023-07-07 15:35:37', 1, 1),
(1, 2, 10, '2023-06-23 11:07:36', 1, 1),
(1, 6, 11, '2023-06-22 11:35:04', 1, 1),
(1, 6, 12, '2023-06-22 11:35:04', 1, 1),
(1, 6, 13, '2023-06-22 11:35:04', 1, 1),
(1, 6, 14, '2023-06-22 11:35:04', 1, 1),
(1, 7, 15, '2023-06-22 16:05:28', 3, 1),
(1, 8, 16, '2023-06-23 20:23:56', 1, 1),
(1, 8, 17, '2023-06-23 20:23:56', 1, 1),
(1, 5, 18, '2023-06-23 19:10:12', 1, 1),
(1, 5, 19, '2023-06-23 19:10:12', 1, 1),
(1, 4, 20, '2023-06-23 16:11:01', 1, 1),
(1, 4, 21, '2023-06-23 16:11:01', 1, 1),
(1, 4, 22, '2023-06-23 16:11:01', 1, 1),
(1, 4, 23, '2023-06-23 16:11:01', 1, 1),
(1, 3, 24, '2023-06-22 17:58:40', 3, 1),
(1, 3, 25, '2023-06-22 17:58:40', 3, 1),
(1, 3, 26, '2023-06-22 17:58:40', 3, 1),
(1, 3, 27, '2023-06-22 11:45:27', 1, 1),
(1, 3, 28, '2023-06-22 17:58:40', 3, 1),
(1, 3, 29, '2023-06-22 17:58:40', 3, 1),
(1, 3, 30, '2023-06-22 17:58:40', 3, 1),
(1, 4, 31, '2023-06-26 16:32:22', 1, 1),
(1, 5, 32, '2023-07-01 15:38:27', 1, 1),
(1, 8, 33, '2023-07-02 09:17:48', 1, 1),
(1, 3, 34, '2023-07-06 18:34:58', 1, 1),
(3, 4, 21, '2023-07-06 11:45:40', 1, 1),
(3, 4, 22, '2023-07-06 11:45:40', 1, 1),
(3, 4, 23, '2023-07-06 11:45:40', 1, 1),
(7, 2, 4, '2023-06-21 20:28:50', 2, 2),
(7, 2, 5, '2023-06-21 20:28:50', 2, 2),
(7, 2, 6, '2023-06-21 20:28:50', 2, 2),
(7, 9, 8, '2023-06-22 11:24:16', 2, 2),
(7, 3, 9, '2023-07-07 18:31:42', 2, 2),
(7, 2, 10, '2023-06-23 11:07:36', 2, 2),
(7, 6, 11, '2023-06-22 11:35:04', 2, 2),
(7, 6, 12, '2023-06-22 11:35:04', 2, 2),
(7, 6, 13, '2023-06-22 11:35:04', 2, 2),
(7, 6, 14, '2023-06-22 11:35:04', 2, 2),
(7, 7, 15, '2023-06-22 16:05:28', 2, 2),
(7, 8, 16, '2023-07-08 16:33:57', 2, 2),
(7, 8, 17, '2023-07-08 16:33:57', 2, 2),
(7, 5, 18, '2023-07-08 15:44:52', 2, 2),
(7, 5, 19, '2023-07-08 15:44:52', 2, 2),
(7, 4, 20, '2023-06-23 16:11:01', 2, 2),
(7, 4, 21, '2023-06-23 16:11:01', 2, 2),
(7, 4, 22, '2023-06-23 16:11:01', 2, 2),
(7, 4, 23, '2023-06-23 16:11:01', 2, 2),
(7, 3, 24, '2023-06-22 17:58:40', 2, 2),
(7, 3, 25, '2023-06-22 17:58:40', 2, 2),
(7, 3, 26, '2023-06-22 17:58:40', 2, 2),
(7, 3, 27, '2023-06-22 11:45:27', 2, 2),
(7, 3, 28, '2023-06-22 17:58:40', 2, 2),
(7, 3, 29, '2023-06-22 17:58:40', 2, 2),
(7, 3, 30, '2023-06-22 17:58:40', 2, 2),
(7, 4, 31, '2023-07-08 11:36:40', 2, 2),
(7, 5, 32, '2023-07-08 15:44:52', 2, 2),
(7, 8, 33, '2023-07-08 16:33:57', 2, 2),
(7, 3, 34, '2023-07-07 18:31:42', 2, 2),
(8, 3, 25, '2023-06-23 18:48:38', 2, 2),
(8, 3, 26, '2023-06-23 18:48:38', 2, 2),
(8, 3, 27, '2023-06-23 18:48:38', 2, 2),
(8, 3, 28, '2023-06-23 18:48:38', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `permission_roles_with_sub_menu_controls`
--

CREATE TABLE `permission_roles_with_sub_menu_controls` (
  `roles_id` int(11) NOT NULL,
  `roles_main_menu_id` int(11) NOT NULL,
  `roles_sub_menu_id` int(11) NOT NULL,
  `roles_sub_menu_control_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_by` int(11) NOT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permission_roles_with_sub_menu_controls`
--

INSERT INTO `permission_roles_with_sub_menu_controls` (`roles_id`, `roles_main_menu_id`, `roles_sub_menu_id`, `roles_sub_menu_control_id`, `created_at`, `added_by`, `com_code`) VALUES
(1, 2, 3, 3, '2023-06-21 21:42:10', 1, 1),
(1, 2, 3, 4, '2023-06-21 21:23:59', 1, 1),
(1, 9, 8, 6, '2023-06-22 15:28:49', 1, 1),
(1, 3, 9, 7, '2023-07-07 15:35:54', 1, 1),
(1, 3, 9, 8, '2023-07-07 15:35:54', 1, 1),
(1, 3, 9, 9, '2023-07-07 15:35:54', 1, 1),
(1, 9, 8, 10, '2023-06-22 15:26:55', 1, 1),
(1, 3, 9, 11, '2023-07-07 15:35:54', 1, 1),
(1, 3, 9, 12, '2023-07-07 15:35:54', 1, 1),
(1, 3, 9, 13, '2023-07-07 15:35:54', 1, 1),
(1, 7, 15, 14, '2023-06-22 16:05:34', 3, 1),
(1, 7, 15, 15, '2023-06-22 17:02:01', 3, 1),
(1, 7, 15, 16, '2023-06-23 20:42:30', 1, 1),
(1, 7, 15, 17, '2023-06-22 17:45:09', 3, 1),
(1, 7, 15, 18, '2023-06-22 18:28:44', 3, 1),
(1, 2, 4, 19, '2023-06-23 10:14:24', 1, 1),
(1, 2, 4, 20, '2023-06-23 10:14:24', 1, 1),
(1, 2, 4, 21, '2023-06-23 10:15:01', 1, 1),
(1, 2, 4, 22, '2023-06-23 10:14:24', 1, 1),
(1, 2, 5, 23, '2023-06-23 10:25:04', 1, 1),
(1, 2, 5, 24, '2023-06-23 10:25:04', 1, 1),
(1, 2, 5, 25, '2023-06-23 10:25:04', 1, 1),
(1, 2, 5, 26, '2023-06-23 10:25:04', 1, 1),
(1, 2, 6, 27, '2023-06-23 10:45:36', 1, 1),
(1, 2, 6, 28, '2023-06-23 10:45:36', 1, 1),
(1, 2, 6, 29, '2023-06-23 10:45:36', 1, 1),
(1, 2, 6, 30, '2023-06-23 10:45:36', 1, 1),
(1, 2, 10, 31, '2023-06-23 11:09:32', 1, 1),
(1, 2, 10, 32, '2023-06-23 11:09:32', 1, 1),
(1, 2, 10, 33, '2023-06-23 11:09:32', 1, 1),
(1, 2, 10, 34, '2023-06-23 11:09:32', 1, 1),
(1, 2, 10, 35, '2023-06-23 11:12:14', 1, 1),
(1, 2, 10, 36, '2023-06-23 11:10:17', 1, 1),
(1, 3, 24, 37, '2023-06-23 11:19:06', 1, 1),
(1, 3, 24, 38, '2023-06-23 11:19:06', 1, 1),
(1, 3, 25, 39, '2023-06-23 11:43:59', 1, 1),
(1, 3, 25, 40, '2023-06-23 11:43:59', 1, 1),
(1, 3, 25, 41, '2023-06-23 18:56:27', 1, 1),
(1, 3, 25, 42, '2023-06-23 11:43:59', 1, 1),
(1, 3, 26, 43, '2023-06-23 12:05:16', 1, 1),
(1, 3, 26, 44, '2023-06-23 12:05:16', 1, 1),
(1, 3, 26, 45, '2023-06-23 12:05:16', 1, 1),
(1, 3, 26, 46, '2023-06-23 12:05:16', 1, 1),
(1, 3, 26, 47, '2023-06-23 12:05:16', 1, 1),
(1, 3, 27, 48, '2023-06-23 12:12:42', 1, 1),
(1, 3, 27, 49, '2023-06-23 12:12:42', 1, 1),
(1, 3, 27, 50, '2023-06-23 12:12:42', 1, 1),
(1, 3, 27, 51, '2023-06-23 12:12:42', 1, 1),
(1, 3, 27, 52, '2023-06-23 12:16:31', 1, 1),
(1, 3, 28, 53, '2023-06-23 16:07:14', 1, 1),
(1, 3, 28, 54, '2023-06-23 16:07:14', 1, 1),
(1, 3, 28, 55, '2023-06-23 16:07:14', 1, 1),
(1, 3, 28, 56, '2023-06-23 16:07:14', 1, 1),
(1, 3, 28, 57, '2023-06-23 16:07:14', 1, 1),
(1, 3, 29, 58, '2023-06-23 16:07:44', 1, 1),
(1, 3, 29, 59, '2023-06-23 16:07:44', 1, 1),
(1, 3, 30, 60, '2023-06-23 16:07:53', 1, 1),
(1, 3, 30, 61, '2023-06-23 16:10:21', 1, 1),
(1, 4, 21, 62, '2023-06-23 16:42:40', 1, 1),
(1, 4, 21, 63, '2023-06-23 16:45:18', 1, 1),
(1, 4, 21, 64, '2023-06-23 16:45:18', 1, 1),
(1, 4, 21, 65, '2023-06-23 16:50:22', 1, 1),
(1, 4, 21, 66, '2023-06-23 16:48:55', 1, 1),
(1, 4, 21, 67, '2023-06-23 16:48:55', 1, 1),
(1, 4, 21, 68, '2023-06-23 16:49:43', 1, 1),
(1, 4, 21, 69, '2023-06-23 16:49:43', 1, 1),
(1, 4, 21, 70, '2023-06-23 16:49:43', 1, 1),
(1, 4, 21, 71, '2023-06-23 16:50:22', 1, 1),
(1, 4, 20, 72, '2023-06-23 17:01:27', 1, 1),
(1, 4, 20, 73, '2023-06-24 11:18:33', 1, 1),
(1, 4, 20, 74, '2023-06-23 17:34:31', 1, 1),
(1, 4, 20, 76, '2023-06-23 17:02:49', 1, 1),
(1, 4, 22, 77, '2023-06-23 17:45:31', 1, 1),
(1, 4, 23, 78, '2023-06-23 17:45:36', 1, 1),
(1, 4, 23, 79, '2023-06-23 19:00:13', 1, 1),
(1, 4, 23, 80, '2023-06-23 19:00:13', 1, 1),
(1, 4, 23, 81, '2023-06-23 19:00:13', 1, 1),
(1, 4, 23, 82, '2023-06-23 19:00:13', 1, 1),
(1, 4, 23, 83, '2023-06-23 19:00:13', 1, 1),
(1, 4, 23, 84, '2023-06-23 19:00:13', 1, 1),
(1, 4, 23, 85, '2023-06-23 19:00:13', 1, 1),
(1, 4, 23, 86, '2023-06-23 19:00:13', 1, 1),
(1, 4, 23, 87, '2023-06-23 19:02:19', 1, 1),
(1, 4, 23, 88, '2023-06-23 19:02:19', 1, 1),
(1, 5, 18, 89, '2023-06-23 19:10:42', 1, 1),
(1, 5, 18, 90, '2023-06-23 19:49:02', 1, 1),
(1, 5, 18, 91, '2023-06-23 19:49:02', 1, 1),
(1, 5, 18, 92, '2023-06-23 19:49:02', 1, 1),
(1, 5, 18, 93, '2023-06-23 19:53:29', 1, 1),
(1, 5, 18, 94, '2023-06-23 19:53:29', 1, 1),
(1, 5, 19, 95, '2023-06-23 20:12:38', 1, 1),
(1, 5, 19, 96, '2023-06-23 20:12:58', 1, 1),
(1, 5, 19, 97, '2023-06-23 20:12:58', 1, 1),
(1, 5, 19, 98, '2023-06-23 20:12:58', 1, 1),
(1, 5, 19, 99, '2023-06-23 20:12:58', 1, 1),
(1, 8, 17, 100, '2023-06-23 20:37:53', 1, 1),
(1, 8, 17, 101, '2023-06-23 20:37:53', 1, 1),
(1, 8, 16, 102, '2023-06-23 20:35:36', 1, 1),
(1, 8, 16, 103, '2023-06-23 20:37:45', 1, 1),
(1, 4, 31, 104, '2023-06-26 16:32:34', 1, 1),
(1, 4, 31, 105, '2023-06-26 16:32:34', 1, 1),
(1, 4, 31, 107, '2023-06-29 17:20:06', 1, 1),
(1, 4, 31, 108, '2023-06-30 16:55:47', 1, 1),
(1, 4, 31, 109, '2023-06-30 17:47:46', 1, 1),
(1, 5, 32, 110, '2023-07-01 15:38:39', 1, 1),
(1, 5, 32, 111, '2023-07-01 15:38:39', 1, 1),
(1, 5, 32, 112, '2023-07-01 15:38:39', 1, 1),
(1, 5, 32, 113, '2023-07-01 15:38:39', 1, 1),
(1, 5, 32, 115, '2023-07-01 18:14:19', 1, 1),
(1, 8, 33, 116, '2023-07-02 09:17:56', 1, 1),
(1, 8, 33, 117, '2023-07-02 09:17:56', 1, 1),
(1, 4, 20, 118, '2023-07-04 18:18:55', 1, 1),
(1, 3, 34, 119, '2023-07-06 18:35:04', 1, 1),
(3, 4, 21, 62, '2023-07-06 11:46:05', 1, 1),
(3, 4, 21, 63, '2023-07-06 11:46:05', 1, 1),
(3, 4, 21, 64, '2023-07-06 11:46:36', 1, 1),
(3, 4, 21, 66, '2023-07-06 11:46:36', 1, 1),
(3, 4, 21, 71, '2023-07-06 11:46:36', 1, 1),
(7, 9, 8, 6, '2023-07-06 22:14:49', 2, 2),
(7, 3, 9, 7, '2023-07-07 18:32:02', 2, 2),
(7, 3, 9, 8, '2023-07-07 18:32:02', 2, 2),
(7, 3, 9, 9, '2023-07-07 18:32:02', 2, 2),
(7, 9, 8, 10, '2023-07-06 22:14:49', 2, 2),
(7, 3, 9, 11, '2023-07-07 18:32:02', 2, 2),
(7, 3, 9, 12, '2023-07-07 18:32:02', 2, 2),
(7, 3, 9, 13, '2023-07-07 18:32:02', 2, 2),
(7, 7, 15, 14, '2023-07-06 22:09:46', 2, 2),
(7, 7, 15, 15, '2023-07-06 22:09:46', 2, 2),
(7, 7, 15, 16, '2023-07-06 22:09:46', 2, 2),
(7, 7, 15, 17, '2023-07-06 22:09:46', 2, 2),
(7, 7, 15, 18, '2023-07-06 22:09:46', 2, 2),
(7, 2, 4, 19, '2023-07-06 11:53:19', 2, 2),
(7, 2, 4, 20, '2023-07-06 11:53:19', 2, 2),
(7, 2, 4, 21, '2023-07-06 11:53:19', 2, 2),
(7, 2, 4, 22, '2023-07-06 11:53:19', 2, 2),
(7, 2, 5, 23, '2023-07-07 17:17:53', 2, 2),
(7, 2, 5, 24, '2023-07-07 17:17:53', 2, 2),
(7, 2, 5, 25, '2023-07-07 17:17:53', 2, 2),
(7, 2, 5, 26, '2023-07-07 17:17:53', 2, 2),
(7, 2, 6, 27, '2023-07-07 17:37:06', 2, 2),
(7, 2, 6, 28, '2023-07-07 17:37:06', 2, 2),
(7, 2, 6, 29, '2023-07-07 17:37:06', 2, 2),
(7, 2, 6, 30, '2023-07-07 17:37:06', 2, 2),
(7, 2, 10, 31, '2023-07-07 17:53:25', 2, 2),
(7, 2, 10, 32, '2023-07-07 17:53:25', 2, 2),
(7, 2, 10, 33, '2023-07-07 17:53:25', 2, 2),
(7, 2, 10, 34, '2023-07-07 17:53:25', 2, 2),
(7, 2, 10, 35, '2023-07-07 17:53:25', 2, 2),
(7, 2, 10, 36, '2023-07-07 17:53:25', 2, 2),
(7, 3, 24, 37, '2023-07-07 17:03:18', 2, 2),
(7, 3, 25, 39, '2023-06-23 18:51:23', 2, 2),
(7, 3, 25, 40, '2023-07-07 16:29:24', 2, 2),
(7, 3, 25, 41, '2023-07-07 16:29:24', 2, 2),
(7, 3, 25, 42, '2023-07-07 16:29:24', 2, 2),
(7, 3, 26, 43, '2023-07-07 18:32:21', 2, 2),
(7, 3, 26, 44, '2023-07-07 18:32:21', 2, 2),
(7, 3, 26, 45, '2023-07-07 18:32:21', 2, 2),
(7, 3, 26, 46, '2023-07-07 18:32:21', 2, 2),
(7, 3, 26, 47, '2023-07-07 18:32:21', 2, 2),
(7, 3, 27, 48, '2023-07-07 18:32:31', 2, 2),
(7, 3, 27, 49, '2023-07-07 18:32:31', 2, 2),
(7, 3, 27, 50, '2023-07-07 18:32:31', 2, 2),
(7, 3, 27, 51, '2023-07-07 18:32:31', 2, 2),
(7, 3, 27, 52, '2023-07-07 18:32:31', 2, 2),
(7, 3, 28, 53, '2023-07-07 18:32:52', 2, 2),
(7, 3, 28, 54, '2023-07-07 18:32:52', 2, 2),
(7, 3, 28, 55, '2023-07-07 18:32:52', 2, 2),
(7, 3, 28, 56, '2023-07-07 18:32:52', 2, 2),
(7, 3, 28, 57, '2023-07-07 18:32:52', 2, 2),
(7, 3, 29, 58, '2023-07-06 22:09:07', 2, 2),
(7, 3, 29, 59, '2023-07-06 22:09:07', 2, 2),
(7, 3, 30, 60, '2023-07-07 18:33:03', 2, 2),
(7, 3, 30, 61, '2023-07-07 18:33:03', 2, 2),
(7, 4, 21, 62, '2023-07-06 11:49:16', 2, 2),
(7, 4, 21, 63, '2023-07-06 11:49:16', 2, 2),
(7, 4, 21, 64, '2023-07-06 11:49:16', 2, 2),
(7, 4, 21, 65, '2023-07-06 11:49:16', 2, 2),
(7, 4, 21, 66, '2023-07-06 11:49:16', 2, 2),
(7, 4, 21, 67, '2023-07-06 11:49:16', 2, 2),
(7, 4, 21, 68, '2023-07-06 11:49:16', 2, 2),
(7, 4, 21, 69, '2023-07-06 11:49:16', 2, 2),
(7, 4, 21, 70, '2023-07-06 11:49:16', 2, 2),
(7, 4, 21, 71, '2023-07-06 11:49:16', 2, 2),
(7, 4, 20, 72, '2023-07-06 11:48:58', 2, 2),
(7, 4, 20, 73, '2023-07-06 11:48:58', 2, 2),
(7, 4, 20, 74, '2023-07-06 11:48:58', 2, 2),
(7, 4, 20, 76, '2023-07-06 11:48:58', 2, 2),
(7, 4, 22, 77, '2023-07-06 11:49:28', 2, 2),
(7, 4, 23, 78, '2023-07-06 11:49:43', 2, 2),
(7, 4, 23, 79, '2023-07-06 11:49:43', 2, 2),
(7, 4, 23, 80, '2023-07-06 11:49:43', 2, 2),
(7, 4, 23, 81, '2023-07-06 11:49:43', 2, 2),
(7, 4, 23, 82, '2023-07-06 11:49:43', 2, 2),
(7, 4, 23, 83, '2023-07-06 11:49:43', 2, 2),
(7, 4, 23, 84, '2023-07-06 11:49:43', 2, 2),
(7, 4, 23, 85, '2023-07-06 11:49:43', 2, 2),
(7, 4, 23, 86, '2023-07-06 11:49:43', 2, 2),
(7, 4, 23, 87, '2023-07-06 11:49:43', 2, 2),
(7, 4, 23, 88, '2023-07-06 11:49:43', 2, 2),
(7, 5, 18, 89, '2023-07-08 15:45:04', 2, 2),
(7, 5, 18, 90, '2023-07-08 15:45:04', 2, 2),
(7, 5, 18, 91, '2023-07-08 15:45:04', 2, 2),
(7, 5, 18, 92, '2023-07-08 15:45:04', 2, 2),
(7, 5, 18, 93, '2023-07-08 15:45:04', 2, 2),
(7, 5, 18, 94, '2023-07-08 15:45:04', 2, 2),
(7, 5, 19, 95, '2023-07-08 15:45:14', 2, 2),
(7, 5, 19, 96, '2023-07-08 15:45:14', 2, 2),
(7, 5, 19, 97, '2023-07-08 15:45:14', 2, 2),
(7, 5, 19, 98, '2023-07-08 15:45:14', 2, 2),
(7, 5, 19, 99, '2023-07-08 15:45:14', 2, 2),
(7, 8, 17, 100, '2023-07-08 16:34:22', 2, 2),
(7, 8, 17, 101, '2023-07-08 16:34:22', 2, 2),
(7, 8, 16, 102, '2023-07-08 16:34:07', 2, 2),
(7, 8, 16, 103, '2023-07-08 16:34:07', 2, 2),
(7, 4, 31, 104, '2023-07-08 11:37:03', 2, 2),
(7, 4, 31, 105, '2023-07-08 11:37:03', 2, 2),
(7, 4, 31, 106, '2023-07-08 11:37:03', 2, 2),
(7, 4, 31, 107, '2023-07-08 11:37:03', 2, 2),
(7, 4, 31, 108, '2023-07-08 11:37:03', 2, 2),
(7, 4, 31, 109, '2023-07-08 11:37:03', 2, 2),
(7, 5, 32, 110, '2023-07-08 15:45:28', 2, 2),
(7, 5, 32, 111, '2023-07-08 15:45:28', 2, 2),
(7, 5, 32, 112, '2023-07-08 15:45:28', 2, 2),
(7, 5, 32, 113, '2023-07-08 15:45:28', 2, 2),
(7, 5, 32, 115, '2023-07-08 15:45:28', 2, 2),
(7, 8, 33, 116, '2023-07-08 16:34:30', 2, 2),
(7, 8, 33, 117, '2023-07-08 16:34:30', 2, 2),
(7, 4, 20, 118, '2023-07-06 11:48:58', 2, 2),
(7, 3, 34, 119, '2023-07-07 18:33:16', 2, 2),
(8, 3, 25, 39, '2023-06-23 18:48:57', 2, 2),
(8, 3, 25, 40, '2023-06-23 18:48:57', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `first_name` varchar(192) NOT NULL,
  `last_name` varchar(192) NOT NULL,
  `account_number` bigint(20) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(192) NOT NULL,
  `person_type` tinyint(1) NOT NULL COMMENT '1- customer 2- supplier',
  `active` tinyint(1) NOT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `first_name`, `last_name`, `account_number`, `city_id`, `address`, `phone`, `person_type`, `active`, `added_by`, `updated_by`, `created_at`, `updated_at`, `date`, `com_code`) VALUES
(1, 'Mohammed', 'Maresh', 12347, NULL, NULL, '774415062', 1, 1, 1, NULL, '2023-05-27 13:34:43', NULL, '2023-05-27', 1),
(2, 'محمد', 'الحربي', 12348, NULL, 'مذبح', '778-882-221', 1, 1, 1, 1, '2023-05-29 11:13:30', '2023-05-29 11:56:54', '2023-05-29', 1),
(3, 'علي', 'دباش', 12349, NULL, 'الجراف', '01288293', 2, 1, 1, 1, '2023-05-29 15:35:09', '2023-05-29 15:37:18', '2023-05-29', 1),
(4, 'علياء', 'محمد', 12350, NULL, 'تعز - شارع جمال', '777-777-672', 1, 1, 2, NULL, '2023-05-31 16:56:07', '2023-05-31 16:56:07', '2023-05-31', 2),
(5, 'خالد', 'الاثوري', 12351, NULL, 'صنعاء - الصافية', '771-293-333', 2, 1, 2, NULL, '2023-05-31 16:57:47', '2023-05-31 16:57:47', '2023-06-03', 2),
(6, 'منيب', 'مارش', 12356, NULL, 'Taiz', '777-707-671', 1, 1, 1, 1, '2023-06-05 15:55:46', '2023-06-05 15:56:04', '2023-06-05', 1),
(7, 'سالم', 'السعدي', 12357, NULL, 'حدة', '777-777-892', 2, 1, 1, 1, '2023-06-05 16:19:10', '2023-07-02 17:56:02', '2023-06-05', 1),
(8, 'موسى', 'مارش', 12359, NULL, 'البيضاء', '771-293-333', 3, 1, 1, 1, '2023-06-09 16:22:35', '2023-06-09 17:26:58', '2023-06-09', 1),
(9, 'احمد', 'الفقية', 12362, NULL, 'بني حوات - صنعاء', '777-714-473', 1, 1, 1, 1, '2023-06-11 16:59:17', '2023-06-11 16:59:33', '2023-06-11', 1),
(10, 'طاهر', 'الهبوب', 12363, NULL, 'الرياض', '0912022203', 3, 1, 1, 1, '2023-06-11 17:20:38', '2023-06-11 17:20:47', '2023-06-18', 1),
(11, 'محمد', 'الحيدري', 12364, NULL, 'تركيا', '0232221312', 2, 1, 1, 1, '2023-06-11 17:34:16', '2023-06-11 17:34:30', '2023-06-18', 1),
(12, 'اكرم', 'غليس', 12365, NULL, 'سعوان', '733650436', 1, 1, 1, NULL, '2023-06-14 13:15:00', '2023-06-14 13:15:00', '2023-06-14', 1),
(13, 'Akram Saeed Abdullah', 'Al-Harazi', 12366, NULL, 'Al-Matar', '771710423', 1, 1, 1, 1, '2023-06-14 13:18:53', '2023-06-15 09:24:32', '2023-06-15', 1),
(14, 'Hithem', 'Al-Hafity', 12367, NULL, 'Thirty-Street', '(598) 897-6372', 1, 1, 1, NULL, '2023-06-15 11:26:33', '2023-06-15 11:26:33', '2023-06-15', 1),
(15, 'مالك', 'البريهي', 12368, NULL, 'اب', '733650436', 3, 1, 1, 1, '2023-06-24 10:32:28', '2023-07-06 19:47:47', '2023-06-24', 1),
(16, 'محمود', 'مارش', 12369, NULL, 'اب', '772738829', 1, 1, 1, 1, '2023-06-24 11:23:50', '2023-07-02 17:48:54', '2023-06-24', 1),
(17, 'عيسى', 'الحميدي', 12371, NULL, 'عدن', '790650436', 1, 1, 1, NULL, '2023-07-05 16:31:04', '2023-07-05 16:31:04', '2023-07-05', 1),
(18, 'سامي', 'محمد', 12384, NULL, 'Al-Matar', '772838291', 1, 1, 2, 2, '2023-07-07 18:40:05', '2023-07-07 18:42:32', '2023-07-08', 2),
(19, 'عبدالله', 'السامعي', 12385, NULL, 'تعز', '783365046', 3, 1, 2, 2, '2023-07-07 18:50:55', '2023-07-07 18:51:06', '2023-07-08', 2),
(20, 'غسان', 'الحافظي', 12386, NULL, 'الخمسين', '717288102', 2, 0, 2, 2, '2023-07-07 18:55:44', '2023-07-07 18:55:53', '2023-07-08', 2);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_header`
--

CREATE TABLE `purchase_order_header` (
  `invoice_id` bigint(20) NOT NULL,
  `auto_serial` bigint(20) NOT NULL,
  `purchase_code` bigint(20) NOT NULL,
  `supplier_code` varchar(255) NOT NULL,
  `store_id` bigint(20) NOT NULL COMMENT 'كود المخزن المستلم للفاتورة',
  `added_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='جدول مشتريات ومترجعات المودين ';

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(192) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `active`, `created_at`, `updated_at`, `added_by`, `updated_by`, `com_code`) VALUES
(1, 'الادارة العلياء', 1, '2023-06-21 09:19:42', '2023-06-22 11:34:05', 1, 1, 1),
(2, 'كاشير', 1, '2023-06-22 08:05:42', '2023-06-22 11:34:18', 1, 1, 1),
(3, 'محاسب', 1, '2023-06-22 08:05:52', '2023-06-22 08:05:52', 1, NULL, 1),
(4, 'مدير حسابات', 1, '2023-06-22 08:06:05', '2023-06-22 08:06:05', 1, NULL, 1),
(5, 'امين مخزن', 1, '2023-06-22 08:06:16', '2023-06-22 08:06:16', 1, NULL, 1),
(6, 'مندوب مبيعات', 1, '2023-06-22 08:06:30', '2023-06-22 08:06:30', 1, NULL, 1),
(7, 'الادارة العلياء', 1, '2023-06-21 09:19:42', '2023-06-22 11:34:05', 1, 2, 2),
(8, 'كاشير', 1, '2023-06-22 08:05:42', '2023-06-22 11:34:18', 1, 2, 2),
(9, 'محاسب', 1, '2023-06-22 08:05:52', '2023-06-22 08:05:52', 1, NULL, 2),
(10, 'مدير حسابات', 1, '2023-06-22 08:06:05', '2023-06-22 08:06:05', 1, NULL, 2),
(11, 'امين مخزن', 1, '2023-06-22 08:06:16', '2023-06-22 08:06:16', 1, NULL, 2),
(12, 'مندوب مبيعات', 1, '2023-06-22 08:06:30', '2023-06-22 08:06:30', 1, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `roles_main_menu`
--

CREATE TABLE `roles_main_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(192) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles_main_menu`
--

INSERT INTO `roles_main_menu` (`id`, `name`, `active`, `created_at`, `updated_at`, `added_by`, `updated_by`, `com_code`) VALUES
(2, 'المخازن', 1, '2023-06-21 10:18:27', '2023-06-21 10:18:27', 1, NULL, 1),
(3, 'الحسابات', 1, '2023-06-21 10:18:41', '2023-06-21 10:18:41', 1, NULL, 1),
(4, 'الحركات المخزنية', 1, '2023-06-21 10:18:58', '2023-06-21 10:18:58', 1, NULL, 1),
(5, 'المبيعات', 1, '2023-06-21 10:19:09', '2023-06-21 10:19:09', 1, NULL, 1),
(6, 'الصلاحيات', 1, '2023-06-21 10:19:23', '2023-06-21 10:19:23', 1, NULL, 1),
(7, 'حركة شفتات الخزن', 1, '2023-06-21 10:19:48', '2023-06-21 10:19:48', 1, NULL, 1),
(8, 'التقارير', 1, '2023-06-21 10:20:02', '2023-06-21 10:26:16', 1, 1, 1),
(9, 'الضبط العام', 1, '2023-06-22 10:42:28', '2023-06-22 10:42:28', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles_sub_menu`
--

CREATE TABLE `roles_sub_menu` (
  `id` int(11) NOT NULL,
  `roles_main_menu_id` int(11) NOT NULL,
  `name` varchar(192) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles_sub_menu`
--

INSERT INTO `roles_sub_menu` (`id`, `roles_main_menu_id`, `name`, `active`, `created_at`, `updated_at`, `added_by`, `updated_by`, `com_code`) VALUES
(3, 2, 'فئات الفواتير', 1, '2023-06-21 11:06:55', '2023-06-21 11:06:55', 1, NULL, 1),
(4, 2, 'المخازن', 1, '2023-06-21 11:07:15', '2023-06-21 11:07:15', 1, NULL, 1),
(5, 2, 'الوحدات', 1, '2023-06-21 11:07:36', '2023-06-21 11:07:36', 1, NULL, 1),
(6, 2, 'فئات الاصناف', 1, '2023-06-21 11:08:01', '2023-06-21 11:08:01', 1, NULL, 1),
(8, 9, 'الضبط العام', 1, '2023-06-22 10:43:17', '2023-06-22 10:43:17', 1, NULL, 1),
(9, 3, 'الخزن', 1, '2023-06-22 10:43:28', '2023-07-07 15:25:40', 1, 1, 1),
(10, 2, 'الاصناف', 1, '2023-06-22 10:44:13', '2023-06-22 10:44:13', 1, NULL, 1),
(11, 6, 'المستخدمين', 1, '2023-06-22 10:44:43', '2023-06-22 10:44:43', 1, NULL, 1),
(12, 6, 'الصلاحيات', 1, '2023-06-22 10:44:59', '2023-06-22 10:44:59', 1, NULL, 1),
(13, 6, 'القوائم الرئيسية للصلاحيات', 1, '2023-06-22 10:45:34', '2023-06-22 10:45:34', 1, NULL, 1),
(14, 6, 'القوائم الفرعية للصلاحيات', 1, '2023-06-22 10:45:55', '2023-06-22 10:45:55', 1, NULL, 1),
(15, 7, 'شفتات الخزن', 1, '2023-06-22 10:46:24', '2023-06-22 10:46:24', 1, NULL, 1),
(16, 8, 'كشف حساب مورد', 1, '2023-06-22 10:46:48', '2023-06-22 10:46:48', 1, NULL, 1),
(17, 8, 'كشف حساب عميل', 1, '2023-06-22 10:47:07', '2023-06-22 10:47:07', 1, NULL, 1),
(18, 5, 'فواتير المبيعات', 1, '2023-06-22 10:47:37', '2023-06-22 10:47:37', 1, NULL, 1),
(19, 5, 'فواتير المرتجعات العام', 1, '2023-06-22 10:48:19', '2023-06-22 10:48:19', 1, NULL, 1),
(20, 4, 'فواتير المرتجعات العام', 1, '2023-06-22 10:48:32', '2023-06-22 10:48:32', 1, NULL, 1),
(21, 4, 'فواتير المشتريات', 1, '2023-06-22 10:49:08', '2023-06-22 10:49:08', 1, NULL, 1),
(22, 4, 'الاصناف في المخازن', 1, '2023-06-22 10:49:35', '2023-06-22 10:49:35', 1, NULL, 1),
(23, 4, 'جرد المخازن', 1, '2023-06-22 10:49:53', '2023-06-22 10:49:53', 1, NULL, 1),
(24, 3, 'انواع الحسابات', 1, '2023-06-22 10:50:39', '2023-06-22 10:50:39', 1, NULL, 1),
(25, 3, 'الحسابات', 1, '2023-06-22 10:51:07', '2023-06-22 10:51:07', 1, NULL, 1),
(26, 3, 'العملاء', 1, '2023-06-22 10:51:26', '2023-06-22 10:51:26', 1, NULL, 1),
(27, 3, 'المناديب', 1, '2023-06-22 10:51:48', '2023-06-22 10:51:48', 1, NULL, 1),
(28, 3, 'الموردين', 1, '2023-06-22 10:52:03', '2023-06-22 10:52:03', 1, NULL, 1),
(29, 3, 'شاشة تحصيل النقدية', 1, '2023-06-22 10:52:41', '2023-06-22 10:52:41', 1, NULL, 1),
(30, 3, 'شاشة صرف النقدية', 1, '2023-06-22 10:52:59', '2023-06-22 10:52:59', 1, NULL, 1),
(31, 4, 'فواتير المرتجعات بالاصل', 1, '2023-06-26 16:31:35', '2023-06-26 16:31:35', 1, NULL, 1),
(32, 5, 'فواتير المرتجعات بالاصل', 1, '2023-07-01 15:37:35', '2023-07-01 15:37:35', 1, NULL, 1),
(33, 8, 'كشف التقارير اليومية', 1, '2023-07-02 09:13:58', '2023-07-02 09:16:28', 1, 1, 1),
(34, 3, 'شاشة الدفع الآجل', 1, '2023-07-06 18:34:20', '2023-07-06 18:34:20', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles_sub_menu_control`
--

CREATE TABLE `roles_sub_menu_control` (
  `id` int(11) NOT NULL,
  `roles_sub_menu_id` int(11) NOT NULL,
  `name` varchar(192) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles_sub_menu_control`
--

INSERT INTO `roles_sub_menu_control` (`id`, `roles_sub_menu_id`, `name`, `active`, `created_at`, `updated_at`, `added_by`, `updated_by`, `com_code`) VALUES
(3, 3, 'حذف', 1, '2023-06-21 20:58:55', '2023-06-21 20:58:55', 1, NULL, 1),
(4, 3, 'تعديل', 1, '2023-06-21 20:59:03', '2023-06-21 20:59:03', 1, NULL, 1),
(5, 3, 'اضافة', 1, '2023-06-21 20:59:16', '2023-06-21 20:59:16', 1, NULL, 1),
(6, 8, 'تعديل', 1, '2023-06-22 10:54:33', '2023-06-22 10:54:33', 1, NULL, 1),
(7, 9, 'تعديل', 1, '2023-06-22 10:55:48', '2023-06-22 10:55:48', 1, NULL, 1),
(8, 9, 'التفاصيل', 1, '2023-06-22 10:56:19', '2023-06-22 10:56:19', 1, NULL, 1),
(9, 9, 'اضافة', 1, '2023-06-22 10:56:38', '2023-06-22 10:56:38', 1, NULL, 1),
(10, 8, 'عرض', 1, '2023-06-22 15:04:31', '2023-06-22 15:04:31', 1, NULL, 1),
(11, 9, 'عرض', 1, '2023-06-22 15:46:36', '2023-06-22 15:46:36', 1, NULL, 1),
(12, 9, 'اضافة خزنة استلام', 1, '2023-06-22 15:47:05', '2023-06-22 15:47:05', 1, NULL, 1),
(13, 9, 'حذف خزنة استلام', 1, '2023-06-22 15:47:21', '2023-06-22 15:47:21', 1, NULL, 1),
(14, 15, 'عرض', 1, '2023-06-22 16:04:40', '2023-06-22 16:04:40', 3, NULL, 1),
(15, 15, 'اضافة', 1, '2023-06-22 17:01:34', '2023-06-22 17:01:34', 3, NULL, 1),
(16, 15, 'انهاء شفت', 1, '2023-06-22 17:01:49', '2023-06-22 17:01:49', 3, NULL, 1),
(17, 15, 'طباعة', 1, '2023-06-22 17:44:42', '2023-06-22 17:44:42', 3, NULL, 1),
(18, 15, 'مراجعة شفت', 1, '2023-06-22 17:44:58', '2023-06-22 17:44:58', 3, NULL, 1),
(19, 4, 'عرض', 1, '2023-06-23 10:13:31', '2023-06-23 10:13:31', 1, NULL, 1),
(20, 4, 'حذف', 1, '2023-06-23 10:13:42', '2023-06-23 10:13:42', 1, NULL, 1),
(21, 4, 'تعديل', 1, '2023-06-23 10:13:50', '2023-06-23 10:13:50', 1, NULL, 1),
(22, 4, 'اضافة', 1, '2023-06-23 10:14:00', '2023-06-23 10:14:00', 1, NULL, 1),
(23, 5, 'عرض', 1, '2023-06-23 10:24:22', '2023-06-23 10:24:22', 1, NULL, 1),
(24, 5, 'حذف', 1, '2023-06-23 10:24:30', '2023-06-23 10:24:30', 1, NULL, 1),
(25, 5, 'تعديل', 1, '2023-06-23 10:24:37', '2023-06-23 10:24:37', 1, NULL, 1),
(26, 5, 'اضافة', 1, '2023-06-23 10:24:46', '2023-06-23 10:24:46', 1, NULL, 1),
(27, 6, 'حذف', 1, '2023-06-23 10:44:55', '2023-06-23 10:44:55', 1, NULL, 1),
(28, 6, 'تعديل', 1, '2023-06-23 10:45:05', '2023-06-23 10:45:05', 1, NULL, 1),
(29, 6, 'اضافة', 1, '2023-06-23 10:45:16', '2023-06-23 10:45:16', 1, NULL, 1),
(30, 6, 'عرض', 1, '2023-06-23 10:45:23', '2023-06-23 10:45:23', 1, NULL, 1),
(31, 10, 'تعديل', 1, '2023-06-23 11:08:35', '2023-06-23 11:08:35', 1, NULL, 1),
(32, 10, 'حذف', 1, '2023-06-23 11:08:43', '2023-06-23 11:08:43', 1, NULL, 1),
(33, 10, 'اضافة', 1, '2023-06-23 11:08:53', '2023-06-23 11:08:53', 1, NULL, 1),
(34, 10, 'عرض', 1, '2023-06-23 11:09:05', '2023-06-23 11:09:05', 1, NULL, 1),
(35, 10, 'عرض الحركات', 1, '2023-06-23 11:09:19', '2023-06-23 11:09:19', 1, NULL, 1),
(36, 10, 'التفاصيل', 1, '2023-06-23 11:10:00', '2023-06-23 11:10:00', 1, NULL, 1),
(37, 24, 'عرض', 1, '2023-06-23 11:18:22', '2023-06-23 11:18:22', 1, NULL, 1),
(38, 24, 'اضافة', 1, '2023-06-23 11:18:37', '2023-06-23 11:18:37', 1, NULL, 1),
(39, 25, 'عرض', 1, '2023-06-23 11:41:46', '2023-06-23 11:41:46', 1, NULL, 1),
(40, 25, 'حذف', 1, '2023-06-23 11:42:01', '2023-06-23 11:42:01', 1, NULL, 1),
(41, 25, 'اضافة', 1, '2023-06-23 11:42:10', '2023-06-23 11:42:10', 1, NULL, 1),
(42, 25, 'تعديل', 1, '2023-06-23 11:43:26', '2023-06-23 11:43:26', 1, NULL, 1),
(43, 26, 'عرض', 1, '2023-06-23 12:04:02', '2023-06-23 12:04:02', 1, NULL, 1),
(44, 26, 'حذف', 1, '2023-06-23 12:04:11', '2023-06-23 12:04:11', 1, NULL, 1),
(45, 26, 'تعديل', 1, '2023-06-23 12:04:27', '2023-06-23 12:04:27', 1, NULL, 1),
(46, 26, 'اضافة', 1, '2023-06-23 12:04:44', '2023-06-23 12:04:44', 1, NULL, 1),
(47, 26, 'التفاصيل', 1, '2023-06-23 12:04:56', '2023-06-23 12:04:56', 1, NULL, 1),
(48, 27, 'عرض', 1, '2023-06-23 12:11:46', '2023-06-23 12:11:46', 1, NULL, 1),
(49, 27, 'اضافة', 1, '2023-06-23 12:11:55', '2023-06-23 12:11:55', 1, NULL, 1),
(50, 27, 'تعديل', 1, '2023-06-23 12:12:03', '2023-06-23 12:12:03', 1, NULL, 1),
(51, 27, 'حذف', 1, '2023-06-23 12:12:14', '2023-06-23 12:12:14', 1, NULL, 1),
(52, 27, 'التفاصيل', 1, '2023-06-23 12:12:24', '2023-06-23 12:12:24', 1, NULL, 1),
(53, 28, 'عرض', 1, '2023-06-23 16:03:56', '2023-06-23 16:03:56', 1, NULL, 1),
(54, 28, 'تعديل', 1, '2023-06-23 16:04:58', '2023-06-23 16:04:58', 1, NULL, 1),
(55, 28, 'حذف', 1, '2023-06-23 16:05:03', '2023-06-23 16:05:03', 1, NULL, 1),
(56, 28, 'اضافة', 1, '2023-06-23 16:05:09', '2023-06-23 16:05:09', 1, NULL, 1),
(57, 28, 'التفاصيل', 1, '2023-06-23 16:05:13', '2023-06-23 16:05:13', 1, NULL, 1),
(58, 29, 'عرض', 1, '2023-06-23 16:05:43', '2023-06-23 16:05:43', 1, NULL, 1),
(59, 29, 'اضافة', 1, '2023-06-23 16:05:51', '2023-06-23 16:05:51', 1, NULL, 1),
(60, 30, 'اضافة', 1, '2023-06-23 16:06:11', '2023-06-23 16:06:11', 1, NULL, 1),
(61, 30, 'عرض', 1, '2023-06-23 16:06:45', '2023-06-23 16:06:45', 1, NULL, 1),
(62, 21, 'عرض', 1, '2023-06-23 16:42:31', '2023-06-23 16:42:31', 1, NULL, 1),
(63, 21, 'اضافة', 1, '2023-06-23 16:44:10', '2023-06-23 16:44:10', 1, NULL, 1),
(64, 21, 'تعديل', 1, '2023-06-23 16:44:15', '2023-06-23 16:44:15', 1, NULL, 1),
(65, 21, 'حذف', 1, '2023-06-23 16:44:24', '2023-06-23 16:44:24', 1, NULL, 1),
(66, 21, 'التفاصيل', 1, '2023-06-23 16:44:28', '2023-06-23 16:44:28', 1, NULL, 1),
(67, 21, 'اضافة صنف', 1, '2023-06-23 16:44:36', '2023-06-23 16:44:36', 1, NULL, 1),
(68, 21, 'تعديل صنف', 1, '2023-06-23 16:44:43', '2023-06-23 16:44:43', 1, NULL, 1),
(69, 21, 'حذف صنف', 1, '2023-06-23 16:44:51', '2023-06-23 16:44:51', 1, NULL, 1),
(70, 21, 'اعتماد', 1, '2023-06-23 16:44:59', '2023-06-23 16:44:59', 1, NULL, 1),
(71, 21, 'طباعة', 1, '2023-06-23 16:50:08', '2023-06-23 16:50:08', 1, NULL, 1),
(72, 20, 'عرض', 1, '2023-06-23 17:01:02', '2023-06-23 17:01:02', 1, NULL, 1),
(73, 20, 'اعتماد', 1, '2023-06-23 17:01:12', '2023-06-23 17:01:12', 1, NULL, 1),
(74, 20, 'حذف', 1, '2023-06-23 17:01:16', '2023-06-23 17:01:16', 1, NULL, 1),
(76, 20, 'اضافة', 1, '2023-06-23 17:02:23', '2023-06-23 17:02:23', 1, NULL, 1),
(77, 22, 'عرض', 1, '2023-06-23 17:45:00', '2023-06-23 17:45:00', 1, NULL, 1),
(78, 23, 'عرض', 1, '2023-06-23 17:45:22', '2023-06-23 17:45:22', 1, NULL, 1),
(79, 23, 'حذف', 1, '2023-06-23 18:58:52', '2023-06-23 18:58:52', 1, NULL, 1),
(80, 23, 'اغلاق', 1, '2023-06-23 18:58:56', '2023-06-23 18:58:56', 1, NULL, 1),
(81, 23, 'تعديل', 1, '2023-06-23 18:59:03', '2023-06-23 18:59:03', 1, NULL, 1),
(82, 23, 'التفاصيل', 1, '2023-06-23 18:59:08', '2023-06-23 18:59:08', 1, NULL, 1),
(83, 23, 'اغلاق باتش', 1, '2023-06-23 18:59:20', '2023-06-23 18:59:20', 1, NULL, 1),
(84, 23, 'حذف باتش', 1, '2023-06-23 18:59:28', '2023-06-23 18:59:28', 1, NULL, 1),
(85, 23, 'تعديل باتش', 1, '2023-06-23 18:59:46', '2023-06-23 18:59:46', 1, NULL, 1),
(86, 23, 'اضافة باتش', 1, '2023-06-23 18:59:55', '2023-06-23 18:59:55', 1, NULL, 1),
(87, 23, 'اضافة', 1, '2023-06-23 19:02:00', '2023-06-23 19:02:00', 1, NULL, 1),
(88, 23, 'طباعة', 1, '2023-06-23 19:02:06', '2023-06-23 19:02:06', 1, NULL, 1),
(89, 18, 'عرض', 1, '2023-06-23 19:05:28', '2023-06-23 19:05:28', 1, NULL, 1),
(90, 18, 'اضافة', 1, '2023-06-23 19:48:08', '2023-06-23 19:48:08', 1, NULL, 1),
(91, 18, 'طباعة', 1, '2023-06-23 19:48:15', '2023-06-23 19:48:15', 1, NULL, 1),
(92, 18, 'حذف', 1, '2023-06-23 19:48:20', '2023-06-23 19:48:20', 1, NULL, 1),
(93, 18, 'اعتماد', 1, '2023-06-23 19:48:25', '2023-06-23 19:48:25', 1, NULL, 1),
(94, 18, 'عرض المرآة', 1, '2023-06-23 19:53:08', '2023-06-23 19:53:08', 1, NULL, 1),
(95, 19, 'عرض', 1, '2023-06-23 20:11:53', '2023-06-23 20:11:53', 1, NULL, 1),
(96, 19, 'اضافة', 1, '2023-06-23 20:12:00', '2023-06-23 20:12:00', 1, NULL, 1),
(97, 19, 'طباعة', 1, '2023-06-23 20:12:07', '2023-06-23 20:12:07', 1, NULL, 1),
(98, 19, 'حذف', 1, '2023-06-23 20:12:19', '2023-06-23 20:12:19', 1, NULL, 1),
(99, 19, 'اعتماد', 1, '2023-06-23 20:12:28', '2023-06-23 20:12:28', 1, NULL, 1),
(100, 17, 'عرض', 1, '2023-06-23 20:24:34', '2023-06-23 20:24:34', 1, NULL, 1),
(101, 17, 'طباعة', 1, '2023-06-23 20:24:50', '2023-06-23 20:24:50', 1, NULL, 1),
(102, 16, 'عرض', 1, '2023-06-23 20:25:17', '2023-06-23 20:25:17', 1, NULL, 1),
(103, 16, 'طباعة', 1, '2023-06-23 20:25:24', '2023-06-23 20:25:24', 1, NULL, 1),
(104, 31, 'عرض', 1, '2023-06-26 16:31:45', '2023-06-26 16:31:45', 1, NULL, 1),
(105, 31, 'اضافة', 1, '2023-06-26 16:31:53', '2023-06-26 16:31:53', 1, NULL, 1),
(106, 31, 'تعديل', 1, '2023-06-26 16:32:01', '2023-06-26 16:32:01', 1, NULL, 1),
(107, 31, 'اعتماد', 1, '2023-06-29 17:19:58', '2023-06-29 17:19:58', 1, NULL, 1),
(108, 31, 'التفاصيل', 1, '2023-06-30 16:55:34', '2023-06-30 16:55:34', 1, NULL, 1),
(109, 31, 'طباعة', 1, '2023-06-30 17:47:34', '2023-06-30 17:47:34', 1, NULL, 1),
(110, 32, 'عرض', 1, '2023-07-01 15:37:47', '2023-07-01 15:37:47', 1, NULL, 1),
(111, 32, 'اضافة', 1, '2023-07-01 15:37:54', '2023-07-01 15:37:54', 1, NULL, 1),
(112, 32, 'التفاصيل', 1, '2023-07-01 15:38:02', '2023-07-01 15:38:02', 1, NULL, 1),
(113, 32, 'طباعة', 1, '2023-07-01 15:38:09', '2023-07-01 15:38:09', 1, NULL, 1),
(115, 32, 'اعتماد', 1, '2023-07-01 18:13:59', '2023-07-01 18:13:59', 1, NULL, 1),
(116, 33, 'عرض', 1, '2023-07-02 09:14:07', '2023-07-02 09:14:07', 1, NULL, 1),
(117, 33, 'طباعة', 1, '2023-07-02 09:14:13', '2023-07-02 09:14:13', 1, NULL, 1),
(118, 20, 'طباعة', 1, '2023-07-04 18:18:42', '2023-07-04 18:18:42', 1, NULL, 1),
(119, 34, 'عرض', 1, '2023-07-06 18:34:32', '2023-07-06 18:34:32', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_header`
--

CREATE TABLE `sales_order_header` (
  `invoice_id` bigint(20) NOT NULL,
  `auto_serial` bigint(20) NOT NULL,
  `customer_code` varchar(192) DEFAULT NULL COMMENT 'كود العميل',
  `delegate_code` bigint(20) DEFAULT NULL COMMENT 'كود المندوب',
  `sales_code` bigint(20) NOT NULL,
  `sales_type` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1- group 2- half_group 3- per_one',
  `delegate_commission_type` tinyint(1) DEFAULT NULL COMMENT '1-percent 2-value',
  `delegate_commission` decimal(10,2) DEFAULT NULL,
  `money_for_delegate` decimal(10,2) DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='جدول مشتريات ومترجعات المودين ';

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `store_code` int(11) NOT NULL,
  `name` varchar(192) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `phone` varchar(192) NOT NULL,
  `address` varchar(192) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `com_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `store_code`, `name`, `active`, `phone`, `address`, `added_by`, `updated_by`, `created_at`, `updated_at`, `date`, `com_code`) VALUES
(1, 1, 'مارت', 1, '777-777-678', 'تعز - شارع جمال', 1, 1, '2023-05-22 16:03:01', '2023-05-22 16:06:00', NULL, 1),
(3, 2, 'الخير', 1, '01567889', 'صنعاء - هايل', 1, 1, '2023-05-22 16:07:14', '2023-07-04 17:05:57', NULL, 1),
(4, 3, 'المخزن الرئيسي', 1, '01283922', 'صنعاء - التحرير', 1, 1, '2023-06-12 18:04:00', '2023-06-12 18:04:43', NULL, 1),
(5, 4, 'سيتي مارت', 1, '04536828', 'صنعاء-الدائري', 1, NULL, '2023-06-24 10:11:56', '2023-06-24 10:11:56', NULL, 1),
(6, 5, 'مخزن فرع المطار', 1, '771710423', 'Al-Matar', 1, NULL, '2023-07-04 17:05:45', '2023-07-04 17:05:45', NULL, 1),
(7, 1, 'مخزن فرع الجراف', 1, '773650436', 'Al-Matar', 2, NULL, '2023-07-06 11:54:07', '2023-07-06 11:54:07', NULL, 2),
(8, 2, 'المخزن الرئيسي', 0, '0120345', 'صنعاء-الدائري', 2, 2, '2023-07-07 17:16:01', '2023-07-07 17:28:08', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `person_id` int(11) NOT NULL,
  `supplier_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`person_id`, `supplier_code`, `created_at`, `updated_at`, `com_code`) VALUES
(3, '233001', '2023-05-29 15:35:09', '2023-05-29 15:37:19', 1),
(5, '4050600', '2023-05-31 16:57:47', '2023-05-31 16:57:47', 2),
(7, '233002', '2023-06-05 16:19:10', '2023-07-02 17:56:02', 1),
(11, '233003', '2023-06-11 17:34:16', '2023-06-11 17:34:30', 1),
(20, '4050601', '2023-07-07 18:55:44', '2023-07-07 18:55:53', 2);

-- --------------------------------------------------------

--
-- Table structure for table `treasuries`
--

CREATE TABLE `treasuries` (
  `id` int(11) NOT NULL,
  `treasury_code` int(11) NOT NULL,
  `name` varchar(192) NOT NULL,
  `account_number` bigint(20) DEFAULT NULL,
  `master` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `last_exchange_arrive` int(11) NOT NULL,
  `last_collection_arrive` int(11) NOT NULL,
  `last_unpaid_arrive` int(11) NOT NULL DEFAULT 0,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `com_code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treasuries`
--

INSERT INTO `treasuries` (`id`, `treasury_code`, `name`, `account_number`, `master`, `active`, `last_exchange_arrive`, `last_collection_arrive`, `last_unpaid_arrive`, `added_by`, `updated_by`, `created_at`, `updated_at`, `date`, `com_code`) VALUES
(1, 1, 'كاشير1', 12373, 1, 1, 4, 5, 13, 1, 1, '2023-05-21 07:58:09', '2023-07-07 09:08:12', NULL, 1),
(2, 2, 'كاشير2', 12374, 0, 1, 1, 1, 0, 1, 1, '2023-05-21 08:58:09', '2023-06-12 16:37:59', NULL, 1),
(3, 3, 'كاشير3', 12375, 0, 1, 1, 1, 0, 1, NULL, '2023-05-21 10:31:41', '2023-05-21 10:31:41', NULL, 1),
(4, 4, 'كاشير4', 12376, 0, 1, 16, 13, 0, 1, 1, '2023-05-21 11:07:15', '2023-07-05 20:13:41', NULL, 1),
(5, 5, 'كاشير5', 12377, 0, 1, 0, 0, 0, 1, NULL, '2023-06-24 10:09:42', '2023-06-24 10:09:42', NULL, 1),
(6, 6, 'كاشير6', 12378, 1, 1, 0, 0, 0, 1, NULL, '2023-07-04 17:04:04', '2023-07-04 17:04:04', NULL, 1),
(7, 7, 'كاشير7', 12379, 0, 0, 0, 0, 0, 1, 1, '2023-07-07 15:43:52', '2023-07-07 15:44:49', NULL, 1),
(8, 1, 'كاشير1', 12387, 1, 1, 4, 4, 10, 2, 2, '2023-07-08 06:22:44', '2023-07-08 16:26:48', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `treasuries_delivery`
--

CREATE TABLE `treasuries_delivery` (
  `treasuries_id` int(11) NOT NULL,
  `treasuries_receive_from_id` int(11) NOT NULL,
  `added_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treasuries_delivery`
--

INSERT INTO `treasuries_delivery` (`treasuries_id`, `treasuries_receive_from_id`, `added_by`, `updated_by`, `created_at`, `updated_at`, `com_code`) VALUES
(1, 1, 1, NULL, '2023-05-22 05:48:29', '2023-05-22 05:48:29', 1),
(1, 3, 1, NULL, '2023-05-22 05:46:22', '2023-05-22 05:46:22', 1),
(1, 4, 1, NULL, '2023-05-21 20:35:05', NULL, 1),
(1, 5, 1, NULL, '2023-06-24 10:10:43', '2023-06-24 10:10:43', 1),
(2, 2, 1, NULL, '2023-05-22 05:48:48', '2023-05-22 05:48:48', 1),
(3, 1, 3, NULL, '2023-06-22 19:15:30', '2023-06-22 19:15:30', 1),
(3, 3, 3, NULL, '2023-06-22 18:43:10', '2023-06-22 18:43:10', 1),
(3, 4, 1, NULL, '2023-07-06 11:27:59', '2023-07-06 11:27:59', 1),
(4, 1, 1, NULL, '2023-07-03 17:27:43', '2023-07-03 17:27:43', 1),
(4, 2, 1, NULL, '2023-07-03 17:27:47', '2023-07-03 17:27:47', 1),
(4, 3, 1, NULL, '2023-07-03 17:27:51', '2023-07-03 17:27:51', 1),
(6, 3, 1, NULL, '2023-07-04 17:04:37', '2023-07-04 17:04:37', 1),
(6, 4, 1, NULL, '2023-07-04 17:04:41', '2023-07-04 17:04:41', 1),
(8, 8, 2, NULL, '2023-07-08 06:25:16', '2023-07-08 06:25:16', 2);

-- --------------------------------------------------------

--
-- Table structure for table `treasuries_transactions`
--

CREATE TABLE `treasuries_transactions` (
  `id` bigint(20) NOT NULL,
  `transaction_code` bigint(20) NOT NULL,
  `shift_code` bigint(20) NOT NULL COMMENT 'كود الشفت للمستخدم',
  `move_type` int(11) NOT NULL COMMENT 'نوع حركة النقدية ',
  `account_number` bigint(20) DEFAULT NULL COMMENT 'رقم الحساب المالي ',
  `last_arrive` int(11) NOT NULL COMMENT ' هذا يقوم بتسجيل بيانات اخر ايصال سواء كان تحصيل او صرف ونقوم بالتفريق بينهم عن طريق in_screen',
  `transaction_type` tinyint(1) NOT NULL COMMENT '1-Exchange 2-Collect 3-unpaid\r\n\r\nوايضا اذا كان صرف نقوم بإضافة المبلغ للحساب واذا تجميع نقوم بسحب من الحساب',
  `treasuries_id` int(11) NOT NULL COMMENT 'فقط من التفريق بين اخر الايصالات بناء عليها وعلى in_screen هل هو صرف او تجميع',
  `invoice_id` bigint(20) DEFAULT NULL COMMENT 'كود الجدول الاخر المرتبط بالحركة',
  `is_account` tinyint(1) NOT NULL COMMENT 'هل هو حساب مالي',
  `is_approved` tinyint(1) NOT NULL,
  `money` decimal(10,2) NOT NULL COMMENT 'قيمة المبلغ المصروف او المحصل بالخزنة',
  `money_for_account` decimal(10,2) DEFAULT NULL,
  `byan` varchar(225) DEFAULT NULL,
  `move_date` date NOT NULL,
  `added_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `com_code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='جدول حركة النقدية بالشفتات';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_number` (`account_number`),
  ADD KEY `parent_account_number` (`parent_account_number`),
  ADD KEY `account_type` (`account_type`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_type_updated_by_fk` (`updated_by`),
  ADD KEY `account_type_added_by_fk` (`added_by`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD KEY `admin_added_by_fk` (`added_by`),
  ADD KEY `admin_updated_by_fk` (`updated_by`),
  ADD KEY `admin_com_code_fk` (`com_code`),
  ADD KEY `roles_id` (`roles_id`);

--
-- Indexes for table `admin_panel_settings`
--
ALTER TABLE `admin_panel_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_name` (`system_name`),
  ADD UNIQUE KEY `com_code` (`com_code`),
  ADD KEY `adminPanel_added_by_fk` (`added_by`),
  ADD KEY `adminPanel_updated_by_fk` (`updated_by`);

--
-- Indexes for table `admin_shifts`
--
ALTER TABLE `admin_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_treasuries_fk10` (`admin_id`,`treasuries_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `finished_by` (`finished_by`),
  ADD KEY `delivered_to_shift_id` (`delivered_to_shift_id`);

--
-- Indexes for table `admin_treasuries`
--
ALTER TABLE `admin_treasuries`
  ADD PRIMARY KEY (`admin_id`,`treasuries_id`) USING BTREE,
  ADD KEY `treasuries_id` (`treasuries_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`person_id`),
  ADD UNIQUE KEY `customer_code` (`customer_code`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `delegates`
--
ALTER TABLE `delegates`
  ADD PRIMARY KEY (`person_id`),
  ADD UNIQUE KEY `delegate_code` (`delegate_code`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `invoice_order_details`
--
ALTER TABLE `invoice_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_code` (`item_code`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `batch_id` (`batch_id`),
  ADD KEY `purchase_order_id` (`invoice_order_id`),
  ADD KEY `store_id` (`store_id`);

--
-- Indexes for table `invoice_order_header`
--
ALTER TABLE `invoice_order_header`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `inv_item_card`
--
ALTER TABLE `inv_item_card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_itemcard_categories_id` (`inv_itemcard_categories_id`),
  ADD KEY `parent_inv_itemcard_id` (`parent_inv_itemcard_id`),
  ADD KEY `retail_unit_id` (`retail_unit_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `inv_item_card_batches`
--
ALTER TABLE `inv_item_card_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `inv_unit_id` (`inv_unit_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `inv_item_card_batches_ibfk_2` (`item_code`);

--
-- Indexes for table `inv_item_card_movements`
--
ALTER TABLE `inv_item_card_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_itemcard_movements_categories` (`inv_item_card_movements_categories_id`),
  ADD KEY `items_movements_types` (`inv_item_card_movements_types_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `batch_id` (`batch_id`),
  ADD KEY `order_details_id` (`order_details_id`),
  ADD KEY `inv_item_card_movements_ibfk_8` (`order_header_id`),
  ADD KEY `inv_item_card_movements_ibfk_2` (`item_code`);

--
-- Indexes for table `inv_item_card_movements_categories`
--
ALTER TABLE `inv_item_card_movements_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_item_card_movements_types`
--
ALTER TABLE `inv_item_card_movements_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_item_categories`
--
ALTER TABLE `inv_item_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `inv_stores_inventory_details`
--
ALTER TABLE `inv_stores_inventory_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `batch_id` (`batch_id`),
  ADD KEY `cloased_by` (`closed_by`),
  ADD KEY `inv_stores_inventory_header_id` (`inv_stores_inventory_header_id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `item_code` (`item_code`);

--
-- Indexes for table `inv_stores_inventory_header`
--
ALTER TABLE `inv_stores_inventory_header`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `cloased_by` (`closed_by`);

--
-- Indexes for table `inv_units`
--
ALTER TABLE `inv_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `move_types`
--
ALTER TABLE `move_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `original_return_invoice`
--
ALTER TABLE `original_return_invoice`
  ADD PRIMARY KEY (`invoice_order_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `permission_roles_with_main_menu`
--
ALTER TABLE `permission_roles_with_main_menu`
  ADD PRIMARY KEY (`roles_id`,`roles_main_menu_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `roles_main_menu_id` (`roles_main_menu_id`);

--
-- Indexes for table `permission_roles_with_sub_menu`
--
ALTER TABLE `permission_roles_with_sub_menu`
  ADD PRIMARY KEY (`roles_id`,`roles_sub_menu_id`,`roles_main_menu_id`) USING BTREE,
  ADD KEY `added_by` (`added_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `roles_main_menu_id` (`roles_sub_menu_id`),
  ADD KEY `roles_id` (`roles_id`),
  ADD KEY `roles_main_menu_id_2` (`roles_main_menu_id`),
  ADD KEY `permission_roles_with_sub_menu_ibfk_2` (`roles_id`,`roles_main_menu_id`);

--
-- Indexes for table `permission_roles_with_sub_menu_controls`
--
ALTER TABLE `permission_roles_with_sub_menu_controls`
  ADD PRIMARY KEY (`roles_id`,`roles_sub_menu_control_id`,`roles_main_menu_id`,`roles_sub_menu_id`) USING BTREE,
  ADD KEY `added_by` (`added_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `roles_sub_menu_control_id` (`roles_sub_menu_control_id`),
  ADD KEY `roles_id` (`roles_id`),
  ADD KEY `roles_main_menu_id` (`roles_main_menu_id`),
  ADD KEY `roles_sub_menu_id` (`roles_sub_menu_id`),
  ADD KEY `permission_roles_with_sub_menu_controls_ibfk_3` (`roles_id`,`roles_main_menu_id`,`roles_sub_menu_id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `account_number` (`account_number`);

--
-- Indexes for table `purchase_order_header`
--
ALTER TABLE `purchase_order_header`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `auto_serial` (`auto_serial`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `supplier_code` (`supplier_code`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `roles_main_menu`
--
ALTER TABLE `roles_main_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `roles_sub_menu`
--
ALTER TABLE `roles_sub_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_main_menu_id` (`roles_main_menu_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com-code` (`com_code`);

--
-- Indexes for table `roles_sub_menu_control`
--
ALTER TABLE `roles_sub_menu_control`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_sub_menu_id` (`roles_sub_menu_id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `sales_order_header`
--
ALTER TABLE `sales_order_header`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `auto_serial` (`auto_serial`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `customer_code` (`customer_code`),
  ADD KEY `delegate_code` (`delegate_code`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`person_id`),
  ADD UNIQUE KEY `supplier_code` (`supplier_code`),
  ADD KEY `com_code` (`com_code`);

--
-- Indexes for table `treasuries`
--
ALTER TABLE `treasuries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `account_number` (`account_number`);

--
-- Indexes for table `treasuries_delivery`
--
ALTER TABLE `treasuries_delivery`
  ADD PRIMARY KEY (`treasuries_id`,`treasuries_receive_from_id`) USING BTREE,
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `treasuries_dellivery_from_id_fk` (`treasuries_receive_from_id`);

--
-- Indexes for table `treasuries_transactions`
--
ALTER TABLE `treasuries_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mov_type` (`move_type`),
  ADD KEY `account_number` (`account_number`),
  ADD KEY `treasuries_id` (`treasuries_id`),
  ADD KEY `com_code` (`com_code`),
  ADD KEY `added_by` (`added_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `treasuries_transactions_ibfk_3` (`shift_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_panel_settings`
--
ALTER TABLE `admin_panel_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_shifts`
--
ALTER TABLE `admin_shifts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_order_details`
--
ALTER TABLE `invoice_order_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_item_card`
--
ALTER TABLE `inv_item_card`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inv_item_card_batches`
--
ALTER TABLE `inv_item_card_batches`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_item_card_movements`
--
ALTER TABLE `inv_item_card_movements`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_item_card_movements_categories`
--
ALTER TABLE `inv_item_card_movements_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inv_item_card_movements_types`
--
ALTER TABLE `inv_item_card_movements_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `inv_item_categories`
--
ALTER TABLE `inv_item_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `inv_stores_inventory_details`
--
ALTER TABLE `inv_stores_inventory_details`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_stores_inventory_header`
--
ALTER TABLE `inv_stores_inventory_header`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_units`
--
ALTER TABLE `inv_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `move_types`
--
ALTER TABLE `move_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles_main_menu`
--
ALTER TABLE `roles_main_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles_sub_menu`
--
ALTER TABLE `roles_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `roles_sub_menu_control`
--
ALTER TABLE `roles_sub_menu_control`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `treasuries`
--
ALTER TABLE `treasuries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `treasuries_transactions`
--
ALTER TABLE `treasuries_transactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `account_added_by_fk` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `account_com_code_fk` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `account_parent_fk` FOREIGN KEY (`parent_account_number`) REFERENCES `accounts` (`account_number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `account_type_fk` FOREIGN KEY (`account_type`) REFERENCES `account_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `account_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `account_types`
--
ALTER TABLE `account_types`
  ADD CONSTRAINT `account_type_added_by_fk` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `account_type_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_added_by_fk` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_com_code_fk` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `admin_panel_settings`
--
ALTER TABLE `admin_panel_settings`
  ADD CONSTRAINT `adminPanel_added_by_fk` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `adminPanel_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`);

--
-- Constraints for table `admin_shifts`
--
ALTER TABLE `admin_shifts`
  ADD CONSTRAINT `admin_shifts_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_shifts_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_shifts_ibfk_3` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_shifts_ibfk_4` FOREIGN KEY (`finished_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_shifts_ibfk_5` FOREIGN KEY (`delivered_to_shift_id`) REFERENCES `admin_shifts` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_treasuries_fk10` FOREIGN KEY (`admin_id`,`treasuries_id`) REFERENCES `admin_treasuries` (`admin_id`, `treasuries_id`);

--
-- Constraints for table `admin_treasuries`
--
ALTER TABLE `admin_treasuries`
  ADD CONSTRAINT `admin_treasuries_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_treasuries_ibfk_2` FOREIGN KEY (`treasuries_id`) REFERENCES `treasuries` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_treasuries_ibfk_3` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_treasuries_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_treasuries_ibfk_5` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customer_person_id_fk` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `delegates`
--
ALTER TABLE `delegates`
  ADD CONSTRAINT `delegate_ibfk_1` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `delegate_person_id_fk` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invoice_order_details`
--
ALTER TABLE `invoice_order_details`
  ADD CONSTRAINT `invoice_order_details_ibfk_1` FOREIGN KEY (`invoice_order_id`) REFERENCES `invoice_order_header` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_order_details_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `inv_units` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_order_details_ibfk_3` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_details_ibfk_11` FOREIGN KEY (`item_code`) REFERENCES `inv_item_card` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_details_ibfk_13` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_details_ibfk_14` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_details_ibfk_16` FOREIGN KEY (`batch_id`) REFERENCES `inv_item_card_batches` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `invoice_order_header`
--
ALTER TABLE `invoice_order_header`
  ADD CONSTRAINT `purchase_order_header_ibfk_11` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_header_ibfk_12` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_header_ibfk_13` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_item_card`
--
ALTER TABLE `inv_item_card`
  ADD CONSTRAINT `inv_item_card_ibfk_119` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_ibfk_210` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_ibfk_31` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_itemcard_categories_id_ibfk_011` FOREIGN KEY (`inv_itemcard_categories_id`) REFERENCES `inv_item_categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `parent_inv_itemcard_id_ibfk_210` FOREIGN KEY (`parent_inv_itemcard_id`) REFERENCES `inv_item_card` (`item_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `retail_unit_id_ibfk_310` FOREIGN KEY (`retail_unit_id`) REFERENCES `inv_units` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `unit_id_ibfk_391` FOREIGN KEY (`unit_id`) REFERENCES `inv_units` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_item_card_batches`
--
ALTER TABLE `inv_item_card_batches`
  ADD CONSTRAINT `inv_item_card_batches_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_batches_ibfk_2` FOREIGN KEY (`item_code`) REFERENCES `inv_item_card` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_batches_ibfk_3` FOREIGN KEY (`inv_unit_id`) REFERENCES `inv_units` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_batches_ibfk_4` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_batches_ibfk_5` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_batches_ibfk_6` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_item_card_movements`
--
ALTER TABLE `inv_item_card_movements`
  ADD CONSTRAINT `inv_item_card_movements_ibfk_1` FOREIGN KEY (`inv_item_card_movements_categories_id`) REFERENCES `inv_item_card_movements_categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_movements_ibfk_10` FOREIGN KEY (`batch_id`) REFERENCES `inv_item_card_batches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_movements_ibfk_11` FOREIGN KEY (`order_details_id`) REFERENCES `invoice_order_details` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_movements_ibfk_2` FOREIGN KEY (`item_code`) REFERENCES `inv_item_card` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_movements_ibfk_3` FOREIGN KEY (`inv_item_card_movements_types_id`) REFERENCES `inv_item_card_movements_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_movements_ibfk_6` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_movements_ibfk_7` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_movements_ibfk_8` FOREIGN KEY (`order_header_id`) REFERENCES `invoice_order_header` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_card_movements_ibfk_9` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_item_categories`
--
ALTER TABLE `inv_item_categories`
  ADD CONSTRAINT `inv_item_categories_ibfk_111` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_categories_ibfk_211` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_item_categories_ibfk_311` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_stores_inventory_details`
--
ALTER TABLE `inv_stores_inventory_details`
  ADD CONSTRAINT `inv_stores_inventory_details_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stores_inventory_details_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `inv_item_card_batches` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stores_inventory_details_ibfk_3` FOREIGN KEY (`closed_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stores_inventory_details_ibfk_4` FOREIGN KEY (`inv_stores_inventory_header_id`) REFERENCES `inv_stores_inventory_header` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stores_inventory_details_ibfk_5` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stores_inventory_details_ibfk_6` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stores_inventory_details_ibfk_7` FOREIGN KEY (`item_code`) REFERENCES `inv_item_card` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_stores_inventory_header`
--
ALTER TABLE `inv_stores_inventory_header`
  ADD CONSTRAINT `inv_stores_inventory_header_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stores_inventory_header_ibfk_2` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stores_inventory_header_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stores_inventory_header_ibfk_4` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_stores_inventory_header_ibfk_5` FOREIGN KEY (`closed_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `inv_units`
--
ALTER TABLE `inv_units`
  ADD CONSTRAINT `inv_units_ibfk_111` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_units_ibfk_211` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `inv_units_ibfk_311` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `original_return_invoice`
--
ALTER TABLE `original_return_invoice`
  ADD CONSTRAINT `original_return_invoice_ibfk_1` FOREIGN KEY (`invoice_order_id`) REFERENCES `invoice_order_header` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `original_return_invoice_ibfk_2` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `original_return_invoice_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `original_return_invoice_ibfk_4` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `permission_roles_with_main_menu`
--
ALTER TABLE `permission_roles_with_main_menu`
  ADD CONSTRAINT `permission_roles_with_main_menu_ibfk_1` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_roles_with_main_menu_ibfk_2` FOREIGN KEY (`roles_main_menu_id`) REFERENCES `roles_main_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_roles_with_main_menu_ibfk_3` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_roles_with_main_menu_ibfk_4` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `permission_roles_with_sub_menu`
--
ALTER TABLE `permission_roles_with_sub_menu`
  ADD CONSTRAINT `permission_roles_with_sub_menu_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_roles_with_sub_menu_ibfk_2` FOREIGN KEY (`roles_id`,`roles_main_menu_id`) REFERENCES `permission_roles_with_main_menu` (`roles_id`, `roles_main_menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_roles_with_sub_menu_ibfk_3` FOREIGN KEY (`roles_sub_menu_id`) REFERENCES `roles_sub_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_roles_with_sub_menu_ibfk_4` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `permission_roles_with_sub_menu_controls`
--
ALTER TABLE `permission_roles_with_sub_menu_controls`
  ADD CONSTRAINT `permission_roles_with_sub_menu_controls_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_roles_with_sub_menu_controls_ibfk_2` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_roles_with_sub_menu_controls_ibfk_3` FOREIGN KEY (`roles_id`,`roles_main_menu_id`,`roles_sub_menu_id`) REFERENCES `permission_roles_with_sub_menu` (`roles_id`, `roles_main_menu_id`, `roles_sub_menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_roles_with_sub_menu_controls_ibfk_4` FOREIGN KEY (`roles_sub_menu_control_id`) REFERENCES `roles_sub_menu_control` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `person`
--
ALTER TABLE `person`
  ADD CONSTRAINT `person_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `person_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `person_ibfk_3` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `person_ibfk_4` FOREIGN KEY (`account_number`) REFERENCES `accounts` (`account_number`) ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order_header`
--
ALTER TABLE `purchase_order_header`
  ADD CONSTRAINT `purchase_order_header_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_header_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_header_ibfk_3` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_order_header_ibfk_4` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_order_header` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_ibfk_3` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `roles_main_menu`
--
ALTER TABLE `roles_main_menu`
  ADD CONSTRAINT `roles_main_menu_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_main_menu_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_main_menu_ibfk_3` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `roles_sub_menu`
--
ALTER TABLE `roles_sub_menu`
  ADD CONSTRAINT `roles_sub_menu_ibfk_1` FOREIGN KEY (`roles_main_menu_id`) REFERENCES `roles_main_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_sub_menu_ibfk_2` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_sub_menu_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_sub_menu_ibfk_4` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `roles_sub_menu_control`
--
ALTER TABLE `roles_sub_menu_control`
  ADD CONSTRAINT `roles_sub_menu_control_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_sub_menu_control_ibfk_2` FOREIGN KEY (`roles_sub_menu_id`) REFERENCES `roles_sub_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_sub_menu_control_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `roles_sub_menu_control_ibfk_4` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `sales_order_header`
--
ALTER TABLE `sales_order_header`
  ADD CONSTRAINT `sales_order_header_ibfk_1` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_order_header_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_order_header_ibfk_3` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_order_header_ibfk_4` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_order_header` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `sales_matrial_type_ibfk_11` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_matrial_type_ibfk_21` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_matrial_type_ibfk_31` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `suppliers_ibfk_2` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE;

--
-- Constraints for table `treasuries`
--
ALTER TABLE `treasuries`
  ADD CONSTRAINT `treasuries_added_by_fk` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `treasuries_com_code_fk` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_ibfk_1` FOREIGN KEY (`account_number`) REFERENCES `accounts` (`account_number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `treasuries_delivery`
--
ALTER TABLE `treasuries_delivery`
  ADD CONSTRAINT `treasuries_dellivery_added_by_fk` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_dellivery_com_code_fk` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_dellivery_from_id_fk` FOREIGN KEY (`treasuries_receive_from_id`) REFERENCES `treasuries` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_dellivery_id_fk` FOREIGN KEY (`treasuries_id`) REFERENCES `treasuries` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_dellivery_updated_by_fk` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `treasuries_transactions`
--
ALTER TABLE `treasuries_transactions`
  ADD CONSTRAINT `treasuries_transactions_ibfk_1` FOREIGN KEY (`move_type`) REFERENCES `move_types` (`id`),
  ADD CONSTRAINT `treasuries_transactions_ibfk_2` FOREIGN KEY (`account_number`) REFERENCES `accounts` (`account_number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_transactions_ibfk_3` FOREIGN KEY (`shift_code`) REFERENCES `admin_shifts` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_transactions_ibfk_4` FOREIGN KEY (`treasuries_id`) REFERENCES `treasuries` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_transactions_ibfk_5` FOREIGN KEY (`com_code`) REFERENCES `admin_panel_settings` (`com_code`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_transactions_ibfk_6` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_transactions_ibfk_7` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `treasuries_transactions_ibfk_8` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_order_header` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
