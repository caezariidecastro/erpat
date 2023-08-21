-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2023 at 12:12 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+08:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erpat_source`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_devices`
--

CREATE TABLE `access_devices` (
  `id` int(11) UNSIGNED NOT NULL,
  `api_key` varchar(36) DEFAULT NULL,
  `api_secret` varchar(120) DEFAULT NULL,
  `device_name` varchar(36) DEFAULT NULL,
  `passes` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `labels` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `access_device_categories`
--

CREATE TABLE `access_device_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL DEFAULT '',
  `detail` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `access_logs`
--

CREATE TABLE `access_logs` (
  `id` int(11) UNSIGNED NOT NULL,
  `device_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `remarks` text DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(10) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `number` text COLLATE utf8_unicode_ci NOT NULL,
  `initial_balance` decimal(20,2) NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `number`, `initial_balance`, `remarks`, `created_on`, `created_by`, `deleted`) VALUES
(1, 'Payroll Account', '01234567890', '0.00', '', '2022-07-12 15:48:06', 1, 0),
(2, 'Purchasing Account\\', '01234567890', '0.00', '', '2022-12-07 06:00:16', 1, 0),
(3, 'Billing Account', '01234567890', '0.00', '', '2023-03-20 12:16:30', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `account_transactions`
--

CREATE TABLE `account_transactions` (
  `id` bigint(10) NOT NULL,
  `account_id` bigint(10) NOT NULL,
  `amount` decimal(20,2) NOT NULL,
  `transaction` enum('initial','expense','payment','transfer','payroll','contribution','incentive','purchase_order','purchase_return') COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('debit','credit') COLLATE utf8_unicode_ci NOT NULL,
  `reference` int(11) DEFAULT NULL,
  `deleted` tinyint(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_transfers`
--

CREATE TABLE `account_transfers` (
  `id` bigint(10) NOT NULL,
  `date` date NOT NULL,
  `account_from` bigint(10) NOT NULL,
  `account_to` bigint(10) NOT NULL,
  `amount` decimal(20,0) NOT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `action` enum('created','updated','deleted','bitbucket_notification_received','github_notification_received') COLLATE utf8_unicode_ci NOT NULL,
  `log_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `log_type_title` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `log_type_id` int(11) NOT NULL DEFAULT 0,
  `changes` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_for` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `log_for_id` int(11) NOT NULL DEFAULT 0,
  `log_for2` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_for_id2` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `share_with` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `files` text COLLATE utf8_unicode_ci NOT NULL,
  `read_by` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_brands`
--

CREATE TABLE `asset_brands` (
  `id` bigint(10) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_categories`
--

CREATE TABLE `asset_categories` (
  `id` bigint(10) NOT NULL,
  `parent_id` bigint(10) DEFAULT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_entries`
--

CREATE TABLE `asset_entries` (
  `id` bigint(10) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` bigint(10) NOT NULL,
  `location_id` bigint(10) NOT NULL,
  `vendor_id` bigint(10) NOT NULL,
  `type` enum('own','lease','rental','contract','service') COLLATE utf8_unicode_ci NOT NULL,
  `serial_number` text COLLATE utf8_unicode_ci NOT NULL,
  `brand_id` text COLLATE utf8_unicode_ci NOT NULL,
  `model` text COLLATE utf8_unicode_ci NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty_expiry_date` date NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_locations`
--

CREATE TABLE `asset_locations` (
  `id` bigint(10) NOT NULL,
  `parent_id` bigint(10) DEFAULT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `asset_vendors`
--

CREATE TABLE `asset_vendors` (
  `id` bigint(10) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `city` text COLLATE utf8_unicode_ci NOT NULL,
  `state` text COLLATE utf8_unicode_ci NOT NULL,
  `zip` text COLLATE utf8_unicode_ci NOT NULL,
  `country` text COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(180) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int(11) NOT NULL,
  `description` text NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `log_type` enum('schedule','overtime') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'schedule',
  `status` enum('incomplete','pending','approved','rejected','clockout') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'incomplete',
  `user_id` int(11) NOT NULL,
  `sched_id` int(11) DEFAULT 0,
  `in_time` datetime NOT NULL,
  `out_time` datetime DEFAULT NULL,
  `break_time` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `checked_by` int(11) DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `checked_at` datetime DEFAULT NULL,
  `reject_reason` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bays`
--

CREATE TABLE `bays` (
  `id` int(11) UNSIGNED NOT NULL,
  `rack_id` int(11) DEFAULT NULL,
  `qrcode` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `rfid` varchar(25) NOT NULL,
  `labels` text NOT NULL,
  `remarks` text NOT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'inactive',
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bill_of_materials`
--

CREATE TABLE `bill_of_materials` (
  `id` bigint(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `item_id` bigint(10) NOT NULL,
  `quantity` float NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_of_materials_materials`
--

CREATE TABLE `bill_of_materials_materials` (
  `id` bigint(10) NOT NULL,
  `bill_of_material_id` bigint(10) NOT NULL,
  `material_inventory_id` bigint(10) NOT NULL,
  `quantity` float NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checklist_items`
--

CREATE TABLE `checklist_items` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `is_checked` int(11) NOT NULL DEFAULT 0,
  `task_id` int(11) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('6iudim9v83hus0mgd04vvj75opdl1bpo', '::1', 1692611668, 0x5f5f63695f6c6173745f726567656e65726174657c693a313639323631313636383b757365725f69647c733a313a2231223b),
('g110io3knjf398o7h2erme35slkd43re', '::1', 1692611978, 0x5f5f63695f6c6173745f726567656e65726174657c693a313639323631313937383b757365725f69647c733a313a2231223b),
('c4rbs8hrmvc1odjcn3ruv66rdrfge39r', '::1', 1692612337, 0x5f5f63695f6c6173745f726567656e65726174657c693a313639323631323333373b757365725f69647c733a313a2231223b),
('bq86kvei7gb1mmnn6l54kgov1099itgp', '::1', 1692612640, 0x5f5f63695f6c6173745f726567656e65726174657c693a313639323631323634303b757365725f69647c733a313a2231223b),
('ah4vv0thak11mup5jlov5i7q790kdjh1', '::1', 1692612688, 0x5f5f63695f6c6173745f726567656e65726174657c693a313639323631323634303b757365725f69647c733a313a2231223b);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `company_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` date NOT NULL,
  `website` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `starred_by` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `group_ids` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_lead` tinyint(1) NOT NULL DEFAULT 0,
  `lead_status_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `lead_source_id` int(11) NOT NULL,
  `last_lead_status` text COLLATE utf8_unicode_ci NOT NULL,
  `client_migration_date` date NOT NULL,
  `vat_number` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disable_online_payment` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_groups`
--

CREATE TABLE `client_groups` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consumers`
--

CREATE TABLE `consumers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `street` text COLLATE utf8_unicode_ci NOT NULL,
  `city` text COLLATE utf8_unicode_ci NOT NULL,
  `state` text COLLATE utf8_unicode_ci NOT NULL,
  `zip` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(10) NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `street` text COLLATE utf8_unicode_ci NOT NULL,
  `city` text COLLATE utf8_unicode_ci NOT NULL,
  `state` text COLLATE utf8_unicode_ci NOT NULL,
  `zip` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `placeholder` text COLLATE utf8_unicode_ci NOT NULL,
  `example_variable_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `options` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `field_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `related_to` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `show_in_table` tinyint(1) NOT NULL DEFAULT 0,
  `show_in_invoice` tinyint(1) NOT NULL DEFAULT 0,
  `show_in_estimate` tinyint(1) NOT NULL DEFAULT 0,
  `visible_to_admins_only` tinyint(1) NOT NULL DEFAULT 0,
  `hide_from_clients` tinyint(1) NOT NULL DEFAULT 0,
  `disable_editing_by_clients` tinyint(1) NOT NULL DEFAULT 0,
  `show_on_kanban_card` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_values`
--

CREATE TABLE `custom_field_values` (
  `id` int(11) NOT NULL,
  `related_to_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `related_to_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_widgets`
--

CREATE TABLE `custom_widgets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_title` tinyint(1) NOT NULL DEFAULT 0,
  `show_border` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashboards`
--

CREATE TABLE `dashboards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(10) NOT NULL,
  `reference_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` bigint(10) NOT NULL,
  `consumer` bigint(10) NOT NULL,
  `dispatcher` bigint(10) NOT NULL,
  `driver` bigint(10) NOT NULL,
  `passengers` text COLLATE utf8_unicode_ci NOT NULL,
  `vehicle` bigint(10) NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` text COLLATE utf8_unicode_ci NOT NULL,
  `city` text COLLATE utf8_unicode_ci NOT NULL,
  `state` text COLLATE utf8_unicode_ci NOT NULL,
  `zip` text COLLATE utf8_unicode_ci NOT NULL,
  `country` text COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('draft','ongoing','completed','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discipline_categories`
--

CREATE TABLE `discipline_categories` (
  `id` bigint(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `action` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discipline_entries`
--

CREATE TABLE `discipline_entries` (
  `id` bigint(10) NOT NULL,
  `date_occurred` date NOT NULL,
  `user` bigint(10) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `witness` bigint(10) NOT NULL,
  `category` bigint(10) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email_subject` text COLLATE utf8_unicode_ci NOT NULL,
  `default_message` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `custom_message` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `template_name`, `email_subject`, `default_message`, `custom_message`, `deleted`) VALUES
(1, 'login_info', 'Login details', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"><div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\">  <h1>Login Details</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\"> Hello {USER_FIRST_NAME}, &nbsp;{USER_LAST_NAME},<br><br>An account has been created for you.</p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\"> Please use the following info to login your dashboard:</p>            <hr>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">Dashboard URL:&nbsp;<a href=\"{DASHBOARD_URL}\" target=\"_blank\">{DASHBOARD_URL}</a></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\"></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Email: {USER_LOGIN_EMAIL}</span><br></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Password:&nbsp;{USER_LOGIN_PASSWORD}</span></p>            <p style=\"color: rgb(85, 85, 85);\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '', 0),
(2, 'reset_password', 'Reset password', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"><div style=\"max-width:640px; margin:0 auto; \"><div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Reset Password</h1>\n </div>\n <div style=\"padding: 20px; background-color: rgb(255, 255, 255); color:#555;\">                    <p style=\"font-size: 14px;\"> Hello {ACCOUNT_HOLDER_NAME},<br><br>A password reset request has been created for your account.&nbsp;</p>\n                    <p style=\"font-size: 14px;\"> To initiate the password reset process, please click on the following link:</p>\n                    <p style=\"font-size: 14px;\"><a href=\"{RESET_PASSWORD_URL}\" target=\"_blank\">Reset Password</a></p>\n                    <p style=\"font-size: 14px;\"></p>\n                    <p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\"><br></span></p>\n<p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">If you\'ve received this mail in error, it\'s likely that another user entered your email address by mistake while trying to reset a password.</span><br></p>\n<p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">If you didn\'t initiate the request, you don\'t need to take any further action and can safely disregard this email.</span><br></p>\n<p style=\"font-size: 14px;\"><br></p>\n<p style=\"font-size: 14px;\">{SIGNATURE}</p>\n                </div>\n            </div>\n        </div>', '', 0),
(3, 'team_member_invitation', 'You are invited', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"><div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Account Invitation</h1>   </div>  <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\">{INVITATION_SENT_BY}</span> has sent you an invitation to join with a team.</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{INVITATION_URL}\" target=\"_blank\">Accept this Invitation</a></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">If you don\'t want to accept this invitation, simply ignore this email.</span><br><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '', 0),
(4, 'send_invoice', 'New invoice', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>INVOICE #{INVOICE_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Thank you for your business cooperation.</span><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Your invoice for the project {PROJECT_TITLE} has been generated and is attached here.</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{INVOICE_URL}\" target=\"_blank\">Show Invoice</a></span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Invoice balance due is {BALANCE_DUE}</span><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Please pay this invoice within {DUE_DATE}.&nbsp;</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>  </div> </div></div>', '', 0),
(5, 'signature', 'Signature', '<p><b>ERPat </b>- Enterprise Resource Planning Automated System<br>Made Possible by : <a href=\"http://bytescrafter.net/\" target=\"_blank\" style=\"background-color: rgb(255, 255, 255);\"><b>BytesCrafter</b></a></p>', '<p><div style=\"text-align: center;\"><b>ERPat </b>- Enterprise Resource Planning Automated System</div><div style=\"text-align: center;\">Made Possible by : <a href=\"http://bytescrafter.net/\" target=\"_blank\" style=\"background-color: rgb(255, 255, 255);\"><b>BytesCrafter</b></a></div></p>', 0),
(6, 'client_contact_invitation', 'You are invited', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Account Invitation</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\">{INVITATION_SENT_BY}</span> has sent you an invitation to a client portal.</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{INVITATION_URL}\" target=\"_blank\">Accept this Invitation</a></span></p>            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">If you don\'t want to accept this invitation, simply ignore this email.</span><br><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '', 0),
(7, 'ticket_created', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket #{TICKET_ID} Opened</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px; font-weight: bold;\">Title: {TICKET_TITLE}</span><span style=\"line-height: 18.5714px;\"><br></span></p><p style=\"\"><span style=\"line-height: 18.5714px;\">{TICKET_CONTENT}</span><br></p> <p style=\"\"><br></p> <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TICKET_URL}\" target=\"_blank\">Show Ticket</a></span></p> <p style=\"\"><br></p><p style=\"\">Regards,</p><p style=\"\"><span style=\"line-height: 18.5714px;\">{USER_NAME}</span><br></p>   </div>  </div> </div>', '', 0),
(8, 'ticket_commented', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket #{TICKET_ID} Replies</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px; font-weight: bold;\">Title: {TICKET_TITLE}</span><span style=\"line-height: 18.5714px;\"><br></span></p><p style=\"\"><span style=\"line-height: 18.5714px;\">{TICKET_CONTENT}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><br></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TICKET_URL}\" target=\"_blank\">Show Ticket</a></span></p></div></div></div>', '', 0),
(9, 'ticket_closed', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket #{TICKET_ID}</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\">The Ticket #{TICKET_ID} has been closed by&nbsp;</span><span style=\"line-height: 18.5714px;\">{USER_NAME}</span></p> <p style=\"\"><br></p> <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TICKET_URL}\" target=\"_blank\">Show Ticket</a></span></p>   </div>  </div> </div>', '', 0),
(10, 'ticket_reopened', 'Ticket  #{TICKET_ID} - {TICKET_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket #{TICKET_ID}</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\">The Ticket #{TICKET_ID} has been reopened by&nbsp;</span><span style=\"line-height: 18.5714px;\">{USER_NAME}</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{TICKET_URL}\" target=\"_blank\">Show Ticket</a></span></p>  </div> </div></div>', '', 0),
(11, 'general_notification', '{EVENT_TITLE}', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>{APP_TITLE}</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"><p style=\"\"><span style=\"line-height: 18.5714px;\">{EVENT_TITLE}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\">{EVENT_DETAILS}</span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"><br></span></p><p style=\"\"><span style=\"line-height: 18.5714px;\"></span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{NOTIFICATION_URL}\" target=\"_blank\">View Details</a></span></p>  </div> </div></div>', '', 0),
(12, 'invoice_payment_confirmation', 'Payment received', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\">\r\n <tbody><tr>\r\n <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n <tbody><tr>\r\n <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\">\r\n                                        <tbody><tr>\r\n                                                <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                        <tbody>\r\n                                                            <tr>\r\n                                                                <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                                    <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\">\r\n                                                                        <tbody><tr>\r\n                                                                                <td valign=\"top\" style=\"padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\">\r\n                                                                                    <h2 style=\"display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;\">Payment Confirmation</h2>\r\n                                                                                </td>\r\n                                                                            </tr>\r\n                                                                        </tbody>\r\n                                                                    </table>\r\n                                                                </td>\r\n                                                            </tr>\r\n                                                        </tbody>\r\n                                                    </table>\r\n                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                        <tbody>\r\n                                                            <tr>\r\n                                                                <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n\r\n                                                                    <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\">\r\n                                                                        <tbody><tr>\r\n                                                                                <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\">\r\n                                                                                    Hello,<br>\r\n                                                                                    We have received your payment of {PAYMENT_AMOUNT} for {INVOICE_ID} <br>\r\n                                                                                    Thank you for your business cooperation.\r\n                                                                                </td>\r\n                                                                            </tr>\r\n                                                                            <tr>\r\n                                                                                <td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\">\r\n                                                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                                                        <tbody>\r\n                                                                                            <tr>\r\n                                                                                                <td style=\"padding-top: 15px;padding-right: 0x;padding-bottom: 15px;padding-left: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                                                                    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                                                                        <tbody>\r\n                                                                                                            <tr>\r\n                                                                                                                <td align=\"center\" valign=\"middle\" style=\"font-family: Arial;font-size: 16px;padding: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\">\r\n                                                                                                                    <a href=\"{INVOICE_URL}\" target=\"_blank\" style=\"font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: block;\">View Invoice</a>\r\n                                                                                                                </td>\r\n                                                                                                            </tr>\r\n                                                                                                        </tbody>\r\n                                                                                                    </table>\r\n                                                                                                </td>\r\n                                                                                            </tr>\r\n                                                                                        </tbody>\r\n                                                                                    </table>\r\n                                                                                </td>\r\n                                                                            </tr>\r\n                                                                            <tr>\r\n                                                                                <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> \r\n                                                                                    \r\n                                                                                </td>\r\n                                                                            </tr>\r\n                                                                            <tr>\r\n                                                                                <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> \r\n  {SIGNATURE}\r\n  </td>\r\n </tr>\r\n </tbody>\r\n  </table>\r\n  </td>\r\n  </tr>\r\n  </tbody>\r\n </table>\r\n  </td>\r\n </tr>\r\n  </tbody>\r\n  </table>\r\n  </td>\r\n </tr>\r\n </tbody>\r\n </table>\r\n </td>\r\n </tr>\r\n </tbody>\r\n  </table>', NULL, 0),
(13, 'message_received', '{SUBJECT}', '<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\"> <meta content=\"width=device-width, initial-scale=1.0\" name=\"viewport\"> <style type=\"text/css\"> #message-container p {margin: 10px 0;} #message-container h1, #message-container h2, #message-container h3, #message-container h4, #message-container h5, #message-container h6 { padding:10px; margin:0; } #message-container table td {border-collapse: collapse;} #message-container table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; } #message-container a span{padding:10px 15px !important;} </style> <table id=\"message-container\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background:#eee; margin:0; padding:0; width:100% !important; line-height: 100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; font-family:Helvetica,Arial,sans-serif; color: #555;\"> <tbody> <tr> <td valign=\"top\"> <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"> <tbody> <tr> <td height=\"50\" width=\"600\">&nbsp;</td> </tr> <tr> <td style=\"background-color:#33333e; padding:25px 15px 30px 15px; font-weight:bold; \" width=\"600\"><h2 style=\"color:#fff; text-align:center;\">{USER_NAME} sent you a message</h2></td> </tr> <tr> <td bgcolor=\"whitesmoke\" style=\"background:#fff; font-family:Helvetica,Arial,sans-serif\" valign=\"top\" width=\"600\"> <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"> <tbody> <tr> <td height=\"10\" width=\"560\">&nbsp;</td> </tr> <tr> <td width=\"560\"><p><span style=\"background-color: transparent;\">{MESSAGE_CONTENT}</span></p> <p style=\"display:inline-block; padding: 10px 15px; background-color: #00b393;\"><a href=\"{MESSAGE_URL}\" style=\"text-decoration: none; color:#fff;\" target=\"_blank\">Reply Message</a></p> </td> </tr> <tr> <td height=\"10\" width=\"560\">&nbsp;</td> </tr> </tbody> </table> </td> </tr> <tr> <td height=\"60\" width=\"600\">&nbsp;</td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 0),
(14, 'invoice_due_reminder_before_due_date', 'Invoice due reminder', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> <h2 style=\"display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;\">Invoice Due Reminder</h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello,<br>We would like to remind you that invoice {INVOICE_ID} is due on {DUE_DATE}. Please pay the invoice within due date.&nbsp;</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p>If you have already submitted the payment, please ignore this email.</p><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px;padding-right: 0x;padding-bottom: 15px;padding-left: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-family: Arial;font-size: 16px;padding: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><a href=\"{INVOICE_URL}\" target=\"_blank\" style=\"font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: block;\">View Invoice</a> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 0),
(15, 'invoice_overdue_reminder', 'Invoice overdue reminder', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> <h2 style=\"display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;\">Invoice Overdue Reminder</h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello,<br>We would like to remind you that you have an unpaid invoice {INVOICE_ID}. We kindly request you to pay the invoice as soon as possible.&nbsp;</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p>If you have already submitted the payment, please ignore this email.</p><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px;padding-right: 0x;padding-bottom: 15px;padding-left: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-family: Arial;font-size: 16px;padding: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><a href=\"{INVOICE_URL}\" target=\"_blank\" style=\"font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: block;\">View Invoice</a> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 0),
(16, 'recurring_invoice_creation_reminder', 'Recurring invoice creation reminder', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 40px;padding-right: 18px;padding-bottom: 40px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> <h2 style=\"display: block;margin: 0;padding: 0;font-family: Arial;font-size: 30px;font-style: normal;font-weight: bold;line-height: 100%;letter-spacing: -1px;text-align: center;color: #ffffff !important;\">Invoice Cration Reminder</h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello,<br>We would like to remind you that a recurring invoice will be created on {NEXT_RECURRING_DATE}.</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{INVOICE_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">View Invoice</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 0),
(17, 'project_task_deadline_reminder', 'Project task deadline reminder', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>{APP_TITLE}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">  <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello,</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">This is to remind you that there are some tasks which deadline is {DEADLINE}.</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">{TASKS_LIST}</span></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>  </div> </div></div>', '', 0),
(18, 'estimate_sent', 'New estimate', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>ESTIMATE #{ESTIMATE_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding-top: 20px;padding-right: 18px;padding-bottom: 0;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><p> Hello {CONTACT_FIRST_NAME},<br></p><p>Here is the estimate. Please check the attachment.</p><p></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{ESTIMATE_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Estimate</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 0);
INSERT INTO `email_templates` (`id`, `template_name`, `email_subject`, `default_message`, `custom_message`, `deleted`) VALUES
(19, 'estimate_request_received', 'Estimate request received', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>ESTIMATE REQUEST #{ESTIMATE_REQUEST_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"><p style=\"color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;\"><span style=\"background-color: transparent;\">A new estimate request has been received from {CONTACT_FIRST_NAME}.</span><br></p><p style=\"color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;\"></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{ESTIMATE_REQUEST_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Estimate Request</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 0),
(20, 'estimate_rejected', 'Estimate rejected', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>ESTIMATE #{ESTIMATE_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"><p style=\"\"><font color=\"#606060\" face=\"Arial\"><span style=\"font-size: 15px;\">The estimate #{ESTIMATE_ID} has been rejected.</span></font><br></p><p style=\"color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;\"></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{ESTIMATE_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Estimate</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 0),
(21, 'estimate_accepted', 'Estimate accepted', '<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #EEEEEE;border-top: 0;border-bottom: 0;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"padding-top: 30px;padding-right: 10px;padding-bottom: 30px;padding-left: 10px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody><tr> <td align=\"center\" valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFFFFF;\"> <tbody><tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color: #33333e; max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 40px 18px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"> <h2 style=\"display: block; margin: 0px; padding: 0px; line-height: 100%; text-align: center;\"><font color=\"#ffffff\" face=\"Arial\"><span style=\"letter-spacing: -1px;\"><b>ESTIMATE #{ESTIMATE_ID}</b></span></font><br></h2></td></tr></tbody></table></td></tr></tbody></table> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <tbody> <tr> <td valign=\"top\" style=\"mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"> <table align=\"left\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width: 100%;min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\" width=\"100%\"> <tbody><tr> <td valign=\"top\" style=\"padding: 20px 18px 0px; text-size-adjust: 100%; word-break: break-word; line-height: 150%; text-align: left;\"><p style=\"\"><font color=\"#606060\" face=\"Arial\"><span style=\"font-size: 15px;\">The estimate #{ESTIMATE_ID} has been accepted.</span></font><br></p><p style=\"color: rgb(96, 96, 96); font-family: Arial; font-size: 15px;\"></p></td></tr><tr><td valign=\"top\" style=\"padding-top: 10px;padding-right: 18px;padding-bottom: 10px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"min-width: 100%; text-size-adjust: 100%;\"><tbody><tr><td style=\"padding-top: 15px; padding-bottom: 15px; text-size-adjust: 100%;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: separate !important;border-radius: 2px;background-color: #00b393;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;\"><tbody><tr><td align=\"center\" valign=\"middle\" style=\"font-size: 16px; padding: 10px; text-size-adjust: 100%;\"><a href=\"{ESTIMATE_URL}\" target=\"_blank\" style=\"font-weight: bold; line-height: 100%; color: rgb(255, 255, 255); text-size-adjust: 100%; display: block;\">Show Estimate</a></td></tr></tbody></table></td></tr></tbody></table> <p></p></td> </tr> <tr> <td valign=\"top\" style=\"padding-top: 0px;padding-right: 18px;padding-bottom: 20px;padding-left: 18px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;word-break: break-word;color: #606060;font-family: Arial;font-size: 15px;line-height: 150%;text-align: left;\"> {SIGNATURE} </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </td> </tr> </tbody> </table>', '', 0),
(22, 'new_client_greetings', 'Welcome!', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Welcome to {COMPANY_NAME}</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Thank you for creating your account. </span></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">We are happy to see you here.<br></span></p><hr><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">Dashboard URL:&nbsp;<a href=\"{DASHBOARD_URL}\" target=\"_blank\">{DASHBOARD_URL}</a></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\"></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Email: {CONTACT_LOGIN_EMAIL}</span><br></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Password:&nbsp;{CONTACT_LOGIN_PASSWORD}</span></p><p style=\"color: rgb(85, 85, 85);\"><br></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '', 0),
(23, 'verify_email', 'Verify your email', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"><div style=\"max-width:640px; margin:0 auto; \"><div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Account verification</h1></div><div style=\"padding: 20px; background-color: rgb(255, 255, 255); color:#555;\"><p style=\"font-size: 14px;\">To initiate the signup process, please click on the following link:<br></p><p style=\"font-size: 14px;\"><br></p>', '', 0),
(24, 'send_purchase_request', 'New purchase request', '<div style=\"background-color: #eeeeef; padding: 50px 0; \"> <div style=\"max-width:640px; margin:0 auto; \"> <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>PURCHASE #{P_ID}</h1></div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\"> <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello {CONTACT_FIRST_NAME},</span><br></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Thank you for your business cooperation.</span><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Our purchase request for materials has been generated and is attached here.</span></p><p style=\"\"><br></p><p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a style=\"background-color: #00b393; padding: 10px 15px; color: #ffffff;\" href=\"{PURCHASE_URL}\" target=\"_blank\">Show Purchase Request</a></span></p><p style=\"\"><br></p><p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p> </div> </div></div>', '', 0),
(25, 'event_pass', 'e-Pass Verification', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket Confirmation</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello Brilliant,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We are happy to inform you that your booking for Brilliant Skin Essentials Inc. -#PINAKAMAKINANG “The Brilliant Concert 2023 is now under processing! Get ready to witness the BRIGHTEST event of the year.</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br>Title:&nbsp;</span></font><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">#PINAKAMAKINANG “The Brilliant Concert 2023</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Date: 07 February 2023</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Time: 4:00 PM</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">\n        </span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Participant`s details:</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Reference ID: {REFERENCE_ID}</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Group : {GROUP_NAME}<br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Name: {FIRST_NAME} {LAST_NAME}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Phone: {PHONE_NUMBER}</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Number of Seats: {TOTAL_SEATS}</span><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Remarks: {REMARKS}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Event location:</span></font></p><p><font color=\"#555555\"><b style=\"font-size: 14px;\">Smart Araneta Coliseum</b><br><span style=\"font-size: 14px;\">General Roxas Ave, Araneta City, QC, 1109 Metro Manila</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a href=\"https://goo.gl/maps/P7gXh8FEMLjPSxUH6\" target=\"_blank\" style=\"background-color: rgb(0, 179, 147); color: rgb(255, 255, 255); padding: 10px 15px;\">Open on Google Map</a></span></p><div><br></div><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We can’t wait to see you!</span></font></p><p style=\"\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', NULL, 0),
(26, 'epass_confirm', 'e-Pass Confirmation', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket Confirmation</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello Brilliant,</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We are happy to inform you that your booking for Brilliant Skin Essentials Inc. -#PINAKAMAKINANG “The Brilliant Concert 2023 is now <b>approved </b>and <b>reserved</b>! Get ready to witness the BRIGHTEST event of the year.</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br><b>Title:&nbsp;</b></span></font><span style=\"color: rgb(85, 85, 85); font-size: 14px;\"><b>#PINAKAMAKINANG “The Brilliant Concert 2023</b></span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><b>Date: 07 February 2023</b></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><b>Time: 4:00 PM</b></span></font></p><p><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><b>Guest`s details</b>:</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Reference ID: {REFERENCE_ID}</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Group : {GROUP_NAME}<br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Name: {FIRST_NAME} {LAST_NAME}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Phone: {PHONE_NUMBER}</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Number of Seats: {TOTAL_SEATS}</span></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Companion`s Link: {COMPANION_LINK}</span><span style=\"color: rgb(85, 85, 85); font-size: 14px;\"><br></span></p><p><span style=\"font-size: 14px; color: rgb(85, 85, 85);\">Remarks: {REMARKS}</span><span style=\"color: rgb(85, 85, 85); font-size: 14px;\"><br></span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><b>Event location:</b></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Smart Araneta Coliseum</span><br><span style=\"font-size: 14px;\"><i>General Roxas Ave, Araneta City, QC, 1109 Metro Manila</i></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a href=\"https://goo.gl/maps/P7gXh8FEMLjPSxUH6\" target=\"_blank\" style=\"background-color: rgb(0, 179, 147); color: rgb(255, 255, 255); padding: 10px 15px;\">Open on Google Map</a></span></p><div><br></div><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We can’t wait to see you!</span></font></p><p style=\"\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', NULL, 0),
(27, 'raffle_entry', 'Raffle Entry', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Raffle Entry</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello Brilliant,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We are absolutely thrilled to welcome you to our BSEI raffle community!&nbsp;Now that you are officially a participant, get ready for an incredible experience filled with surprises, excitement, and the chance to win amazing prizes. Our raffle system is designed to provide a thrilling and enjoyable atmosphere for everyone involved, and we are confident that you will have a fantastic time.</span></font><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br>Title:&nbsp;</span></font><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">{RAFFLE_TITLE}</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">ID: {RAFFLE_ID}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Participant`s details:</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Reference ID: {REFERENCE_ID}</span></p><p><span style=\"font-size: 14px; color: rgb(85, 85, 85);\">Name: {FIRST_NAME} {LAST_NAME}</span><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Phone: {PHONE_NUMBER}</span></font></p><p><span style=\"font-size: 14px; color: rgb(85, 85, 85);\">Remarks: {REMARKS}</span><br></p><p><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Once again, welcome! May luck be on your side, and may this experience bring you joy and fulfillment.</span></font><br></p><p style=\"\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', NULL, 0),
(28, 'raffle_subscription', 'Raffle Subscription', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Subscription</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello Brilliant,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We are absolutely thrilled to welcome you to our BSEI raffle community!&nbsp;Now that you are officially a participant, get ready for an incredible experience filled with surprises, excitement, and the chance to win amazing prizes. Our raffle system is designed to provide a thrilling and enjoyable atmosphere for everyone involved, and we are confident that you will have a fantastic time.</span></font><br></p><p><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Participant`s details:</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Reference ID: {REFERENCE_ID}</span></p><p><span style=\"font-size: 14px; color: rgb(85, 85, 85);\">Name: {FIRST_NAME} {LAST_NAME}</span><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Phone: {PHONE_NUMBER}</span></font></p><p><span style=\"font-size: 14px; color: rgb(85, 85, 85);\">Remarks: {REMARKS}</span><br></p><p><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Once again, welcome! May luck be on your side, and may this experience bring you joy and fulfillment.</span></font><br></p><p style=\"\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', NULL, 0),
(29, 'raffle_join', 'Join Raffle', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Join Raffle</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello Brilliant,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We are absolutely thrilled to welcome you to our BSEI raffle community!&nbsp;Now that you are officially a participant, get ready for an incredible experience filled with surprises, excitement, and the chance to win amazing prizes. Our raffle system is designed to provide a thrilling and enjoyable atmosphere for everyone involved, and we are confident that you will have a fantastic time.</span></font><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br>Title:&nbsp;</span></font><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">{RAFFLE_TITLE}</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">ID: {RAFFLE_ID}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Participant`s details:</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Reference ID: {REFERENCE_ID}</span></p><p><span style=\"font-size: 14px; color: rgb(85, 85, 85);\">Name: {FIRST_NAME} {LAST_NAME}</span><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Phone: {PHONE_NUMBER}</span></font></p><p><span style=\"font-size: 14px; color: rgb(85, 85, 85);\">Remarks: {REMARKS}</span><br></p><p><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Once again, welcome! May luck be on your side, and may this experience bring you joy and fulfillment.</span></font><br></p><p style=\"\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', NULL, 0),
(30, 'payslips', 'ERPat - Generated Payslip', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>SYSTEM GENERATED</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Hello {FIRST_NAME} {LAST_NAME},</span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"text-align: justify; \"><font face=\"Arial\">&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"font-size: 14px;\">We hope this email finds you well. As part of our commitment to transparency and efficiency, we are pleased to provide you with your payslip for</span><span style=\"font-size: 14px;\">&nbsp;{PAY_PERIOD}.&nbsp;</span></font><span style=\"font-family: Arial; font-size: 14px;\">You will find a detailed breakdown of your earnings and deductions for the specified period to the PDF attached in this email.&nbsp;</span><span style=\"font-family: Arial; font-size: 14px;\">Hope you find everything in order.</span></p><p style=\"text-align: justify; \"><font face=\"Arial\">&nbsp;&nbsp;&nbsp;Thank you for your dedication and hard work. We value your contribution to the company and look forward to your continued success.</font></p><p style=\"text-align: justify; \"><font face=\"Arial\">&nbsp;&nbsp;&nbsp;&nbsp;{REMARKS}</font></p><p style=\"text-align: justify;\"><br></p><p><font color=\"#555555\" face=\"Arial, Helvetica, sans-serif\"><span style=\"font-size: 14px;\">Regards,</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">HR / Accounting</span><br><span style=\"font-size: 14px;\">ABC Company Inc.<br></span></font><a href=\"https://abc.company\" target=\"_blank\">https://abc.company</a><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><br></p>            <p style=\"text-align: center; color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>SYSTEM GENERATED</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\">Hello {FIRST_NAME} {LAST_NAME},</span></p><p style=\"\"><span style=\"font-size: 14px; line-height: 20px;\"><br></span></p><p style=\"text-align: justify; \"><font face=\"Arial\">&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"font-size: 14px;\">We hope this email finds you well. As part of our commitment to transparency and efficiency, we are pleased to provide you with your payslip for</span><span style=\"font-size: 14px;\">&nbsp;{PAY_PERIOD}.&nbsp;</span></font><span style=\"font-family: Arial; font-size: 14px;\">You will find a detailed breakdown of your earnings and deductions for the specified period to the PDF attached in this email.&nbsp;</span><span style=\"font-family: Arial; font-size: 14px;\">Hope you find everything in order.</span></p><p style=\"text-align: justify; \"><font face=\"Arial\">&nbsp;&nbsp;&nbsp;Thank you for your dedication and hard work. We value your contribution to the company and look forward to your continued success.</font></p><p style=\"text-align: justify; \"><font face=\"Arial\">&nbsp;&nbsp;&nbsp;&nbsp;{REMARKS}</font></p><p style=\"text-align: justify;\"><br></p><p><font color=\"#555555\" face=\"Arial, Helvetica, sans-serif\"><span style=\"font-size: 14px;\">Regards,</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">HR / Accounting</span><br><span style=\"font-size: 14px;\">AHM Outsourcing Inc.<br></span></font><a href=\"https://ahmoutsourcing.com\" target=\"_blank\">https://ahmoutsourcing.com</a><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><br></p>            <p style=\"text-align: center; color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', 0);

-- --------------------------------------------------------

--
-- Table structure for table `epass_area`
--

CREATE TABLE `epass_area` (
  `id` int(11) UNSIGNED NOT NULL,
  `event_id` int(11) NOT NULL,
  `area_name` varchar(36) DEFAULT NULL,
  `sort` int(2) NOT NULL,
  `remarks` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `epass_block`
--

CREATE TABLE `epass_block` (
  `id` int(11) UNSIGNED NOT NULL,
  `area_id` int(11) NOT NULL,
  `block_name` varchar(36) DEFAULT NULL,
  `sort` int(2) NOT NULL,
  `remarks` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `epass_seat`
--

CREATE TABLE `epass_seat` (
  `id` int(11) UNSIGNED NOT NULL,
  `block_id` int(11) NOT NULL,
  `seat_name` varchar(50) NOT NULL,
  `sort` int(2) NOT NULL,
  `remarks` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE `estimates` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `consumer_id` int(11) NOT NULL,
  `estimate_request_id` int(11) NOT NULL DEFAULT 0,
  `estimate_date` date NOT NULL,
  `valid_until` date NOT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_email_sent_date` date DEFAULT NULL,
  `status` enum('draft','sent','accepted','declined') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `tax_id` int(11) NOT NULL DEFAULT 0,
  `tax_id2` int(11) NOT NULL DEFAULT 0,
  `discount_type` enum('before_tax','after_tax') COLLATE utf8_unicode_ci NOT NULL,
  `discount_amount` double NOT NULL,
  `discount_amount_type` enum('percentage','fixed_amount') COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimate_forms`
--

CREATE TABLE `estimate_forms` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT 0,
  `enable_attachment` tinyint(4) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimate_items`
--

CREATE TABLE `estimate_items` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` double NOT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `estimate_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimate_requests`
--

CREATE TABLE `estimate_requests` (
  `id` int(11) NOT NULL,
  `estimate_form_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `client_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `status` enum('new','processing','hold','canceled','estimated') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `files` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `location` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `labels` text COLLATE utf8_unicode_ci NOT NULL,
  `share_with` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `editable_google_event` tinyint(1) NOT NULL DEFAULT 0,
  `google_event_id` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `recurring` int(1) NOT NULL DEFAULT 0,
  `repeat_every` int(11) NOT NULL DEFAULT 0,
  `repeat_type` enum('days','weeks','months','years') COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_cycles` int(11) NOT NULL DEFAULT 0,
  `last_start_date` date DEFAULT NULL,
  `recurring_dates` longtext COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_by` text COLLATE utf8_unicode_ci NOT NULL,
  `rejected_by` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_pass`
--

CREATE TABLE `event_pass` (
  `id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `guest` varchar(36) DEFAULT NULL,
  `seats` int(11) NOT NULL,
  `seat_assign` text DEFAULT NULL,
  `tickets` text DEFAULT NULL,
  `group_name` varchar(50) DEFAULT NULL,
  `vcode` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('draft','approved','cancelled','sent') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `override` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `timestamp` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event_raffle`
--

CREATE TABLE `event_raffle` (
  `id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  `event_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `winners` int(7) NOT NULL,
  `total_participants` int(7) DEFAULT NULL,
  `draw_preview` enum('event_draw','instant_show','via_email') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'event_draw',
  `raffle_type` enum('countdown','spinner','wheel','mosaic') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'countdown',
  `crowd_type` varchar(180) DEFAULT NULL,
  `labels` varchar(1) NOT NULL,
  `remarks` text DEFAULT NULL,
  `creator` int(11) DEFAULT NULL,
  `ranking` enum('asc','desc') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'asc',
  `draw_date` datetime DEFAULT NULL,
  `status` enum('draft','active','cancelled','completed') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `timestamp` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event_raffle_participants`
--

CREATE TABLE `event_raffle_participants` (
  `id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  `raffle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `labels` varchar(1) NOT NULL,
  `remarks` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event_raffle_prizes`
--

CREATE TABLE `event_raffle_prizes` (
  `id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  `raffle_id` int(11) NOT NULL,
  `winner_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `image_url` varchar(180) NOT NULL,
  `remarks` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event_raffle_winners`
--

CREATE TABLE `event_raffle_winners` (
  `id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  `raffle_id` int(11) NOT NULL,
  `participant_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `labels` varchar(1) NOT NULL,
  `remarks` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `expense_date` date NOT NULL,
  `due_date` date NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `files` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('draft','not_paid','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `tax_id` int(11) NOT NULL DEFAULT 0,
  `tax_id2` int(11) NOT NULL DEFAULT 0,
  `client_id` int(11) DEFAULT NULL,
  `vendor_id` bigint(10) NOT NULL,
  `recurring` tinyint(4) NOT NULL DEFAULT 0,
  `recurring_expense_id` tinyint(4) NOT NULL DEFAULT 0,
  `repeat_every` int(11) NOT NULL DEFAULT 0,
  `repeat_type` enum('days','weeks','months','years') COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_cycles` int(11) NOT NULL DEFAULT 0,
  `next_recurring_date` date DEFAULT NULL,
  `no_of_cycles_completed` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_payments`
--

CREATE TABLE `expenses_payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `account_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `expense_id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `note` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `is_editable` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `title`, `is_editable`, `deleted`) VALUES
(1, 'Miscellaneous', 0, 0),
(2, 'Equipments', 0, 0),
(3, 'Foods / Snacks', 0, 0),
(4, 'Salary', 1, 1),
(5, 'Payroll', 0, 0),
(6, 'Contribution', 0, 0),
(7, 'Incentive', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `general_files`
--

CREATE TABLE `general_files` (
  `id` int(11) NOT NULL,
  `file_name` text COLLATE utf8_unicode_ci NOT NULL,
  `file_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_size` double NOT NULL,
  `created_at` datetime NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `uploaded_by` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help_articles`
--

CREATE TABLE `help_articles` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `files` text COLLATE utf8_unicode_ci NOT NULL,
  `total_views` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help_categories`
--

CREATE TABLE `help_categories` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('help','knowledge_base') COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `type` enum('unofficial','regular','special') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unofficial',
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `title`, `description`, `date_from`, `date_to`, `type`, `created_on`, `created_by`, `deleted`) VALUES
(1, 'New Year\'s Day', '', '2024-01-01', '2024-01-01', 'regular', '2023-08-01 15:10:43', 1, 0),
(2, 'Lunar New Year\'s Day', '', '2024-02-10', '2024-02-10', 'special', '2023-08-01 15:11:57', 1, 0),
(3, 'People Power Anniversary', '', '2024-02-25', '2024-02-25', 'special', '2023-08-01 15:13:14', 1, 0),
(4, 'Maundy Thursday', '', '2024-03-28', '2024-03-28', 'regular', '2023-08-01 15:16:10', 1, 0),
(5, 'Good Friday', '', '2024-03-29', '2024-03-29', 'regular', '2023-08-01 15:21:12', 1, 0),
(6, 'Black Saturday', '', '2024-03-30', '2024-03-30', 'special', '2023-08-01 15:22:39', 1, 0),
(7, 'The Day of Valor', '', '2024-04-09', '2024-04-09', 'regular', '2023-08-01 15:23:25', 1, 0),
(8, 'Eidul-Fitar', '', '2024-04-10', '2024-04-10', 'regular', '2023-08-01 15:24:19', 1, 0),
(9, 'Labor Day', '', '2024-05-01', '2024-05-01', 'regular', '2023-08-01 15:24:41', 1, 0),
(10, 'Independence Day', '', '2024-06-12', '2024-06-12', 'regular', '2023-08-01 15:25:52', 1, 0),
(11, 'Eid al-Adha (Feast of the Sacrifice)', '', '2024-06-17', '2024-06-17', 'regular', '2023-08-01 15:26:16', 1, 0),
(12, 'Eid al-Adha Day 2', '', '2024-06-18', '2024-06-18', 'regular', '2023-08-01 15:33:13', 1, 0),
(13, 'Ninoy Aquino Day', '', '2024-08-21', '2024-08-21', 'special', '2023-08-01 15:49:17', 1, 0),
(14, 'National Heroes Day', '', '2024-08-24', '2024-08-24', 'regular', '2023-08-01 15:49:39', 1, 0),
(15, 'All Saints\' Day', '', '2024-11-01', '2024-11-01', 'special', '2023-08-01 15:53:23', 1, 0),
(16, 'Bonifacio Day', '', '2024-11-30', '2024-11-30', 'regular', '2023-08-01 15:53:49', 1, 0),
(17, 'Feast of the Immaculate Conception', '', '2024-12-08', '2024-12-08', 'special', '2023-08-01 15:54:16', 1, 0),
(18, 'Christmas Day', '', '2024-12-25', '2024-12-25', 'regular', '2023-08-01 15:55:13', 1, 0),
(19, 'Rizal Day', '', '2024-12-30', '2024-12-30', 'regular', '2023-08-01 15:55:36', 1, 0),
(20, 'New Year\'s Eve', '', '2024-12-31', '2024-12-31', 'special', '2023-08-01 15:55:59', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` bigint(10) NOT NULL,
  `warehouse` bigint(10) NOT NULL,
  `stock` float NOT NULL,
  `item_id` bigint(10) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `sku` text COLLATE utf8_unicode_ci NOT NULL,
  `unit` bigint(10) NOT NULL,
  `category` bigint(10) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `vendor` bigint(10) NOT NULL,
  `kind` enum('finished_goods','raw_materials','work_in_process') COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` bigint(10) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `sku` text COLLATE utf8_unicode_ci NOT NULL,
  `unit` bigint(10) NOT NULL,
  `category` bigint(10) NOT NULL,
  `brand` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `vendor` bigint(10) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_item_categories`
--

CREATE TABLE `inventory_item_categories` (
  `id` bigint(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_stock_override`
--

CREATE TABLE `inventory_stock_override` (
  `id` bigint(10) NOT NULL,
  `warehouse` bigint(10) NOT NULL,
  `inventory_id` bigint(10) NOT NULL,
  `stock` float NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transfers`
--

CREATE TABLE `inventory_transfers` (
  `id` bigint(10) NOT NULL,
  `reference_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transferee` bigint(10) NOT NULL,
  `receiver` bigint(10) NOT NULL,
  `dispatcher` bigint(10) NOT NULL,
  `driver` bigint(10) NOT NULL,
  `vehicle_id` bigint(10) NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('draft','ongoing','completed','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transfer_items`
--

CREATE TABLE `inventory_transfer_items` (
  `id` bigint(10) NOT NULL,
  `inventory_id` bigint(10) NOT NULL,
  `reference_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` float NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `consumer_id` int(11) DEFAULT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_email_sent_date` date DEFAULT NULL,
  `status` enum('draft','not_paid','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `tax_id` int(11) NOT NULL DEFAULT 0,
  `tax_id2` int(11) NOT NULL DEFAULT 0,
  `tax_id3` int(11) NOT NULL DEFAULT 0,
  `recurring` tinyint(4) NOT NULL DEFAULT 0,
  `recurring_invoice_id` int(11) NOT NULL DEFAULT 0,
  `repeat_every` int(11) NOT NULL DEFAULT 0,
  `repeat_type` enum('days','weeks','months','years') COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_cycles` int(11) NOT NULL DEFAULT 0,
  `next_recurring_date` date DEFAULT NULL,
  `no_of_cycles_completed` int(11) NOT NULL DEFAULT 0,
  `due_reminder_date` date DEFAULT NULL,
  `recurring_reminder_date` date DEFAULT NULL,
  `discount_amount` double NOT NULL,
  `discount_amount_type` enum('percentage','fixed_amount') COLLATE utf8_unicode_ci NOT NULL,
  `discount_type` enum('before_tax','after_tax') COLLATE utf8_unicode_ci NOT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `cancelled_by` int(11) NOT NULL,
  `files` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `enable_terms` int(11) DEFAULT 0,
  `enable_warranty` int(11) DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL DEFAULT 0,
  `inventory_id` int(11) NOT NULL DEFAULT 0,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_reference_no` text COLLATE utf8_unicode_ci NOT NULL,
  `quantity` double NOT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payments`
--

CREATE TABLE `invoice_payments` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `amount` double NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `transaction_id` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE `labels` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `context` enum('event','invoice','note','project','task','ticket','to_do','asset_entry','zones','racks','bays','levels','positions','pallets','users','services') COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `company_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` date NOT NULL,
  `website` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_source`
--

CREATE TABLE `lead_source` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lead_source`
--

INSERT INTO `lead_source` (`id`, `title`, `sort`, `deleted`) VALUES
(1, 'Google', 1, 0),
(2, 'Facebook', 2, 0),
(3, 'Twitter', 3, 0),
(4, 'Youtube', 4, 0),
(5, 'Elsewhere', 5, 0),
(6, 'Email Marketing', 6, 0),
(7, 'Website SEO', 7, 0),
(8, 'Google Adwords', 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lead_status`
--

CREATE TABLE `lead_status` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lead_status`
--

INSERT INTO `lead_status` (`id`, `title`, `color`, `sort`, `deleted`) VALUES
(1, 'New', '#f1c40f', 0, 0),
(2, 'Qualified', '#2d9cdb', 1, 0),
(3, 'Discussion', '#29c2c2', 2, 0),
(4, 'Negotiation', '#2d9cdb', 3, 0),
(5, 'Won', '#83c340', 4, 0),
(6, 'Lost', '#e74c3c', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `leave_applications`
--

CREATE TABLE `leave_applications` (
  `id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_hours` decimal(7,2) NOT NULL,
  `total_days` decimal(5,2) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `reason` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected','canceled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `checked_at` datetime DEFAULT NULL,
  `checked_by` int(11) NOT NULL DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_credits`
--

CREATE TABLE `leave_credits` (
  `id` bigint(10) NOT NULL,
  `user_id` bigint(10) NOT NULL DEFAULT 0,
  `leave_id` bigint(10) NOT NULL DEFAULT 0,
  `leave_type_id` int(11) DEFAULT NULL,
  `action` enum('debit','credit') COLLATE utf8_unicode_ci DEFAULT NULL,
  `counts` decimal(10,2) DEFAULT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `required_credits` tinyint(1) DEFAULT 0,
  `paid` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `title`, `required_credits`, `paid`, `status`, `color`, `description`, `deleted`) VALUES
(1, 'Regular Leave', 1, 1, 'active', '#83c340', '<p>SL / VL / BL / PL<br></p>', 0),
(2, 'Bereavement Leave', 1, 1, 'active', '#83c340', '<p><span style=\"color: rgb(78, 94, 106);\">SL / VL / BL / PL</span><br></p>', 0),
(3, 'Leave w/o Pay', 0, 0, 'active', '#83c340', '<p>SL / VL / BL / PL<br></p>', 0);

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` int(11) UNSIGNED NOT NULL,
  `bay_id` int(11) DEFAULT NULL,
  `qrcode` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `rfid` varchar(25) NOT NULL,
  `labels` text NOT NULL,
  `remarks` text NOT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'inactive',
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `project_comment_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `borrower_id` int(11) NOT NULL,
  `cosigner_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `principal_amount` decimal(10,2) NOT NULL,
  `interest_rate` decimal(10,2) NOT NULL,
  `min_payment` decimal(10,2) NOT NULL,
  `months_topay` int(11) NOT NULL,
  `days_before_due` int(11) NOT NULL DEFAULT 7,
  `penalty_rate` decimal(10,2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `payroll_binding` enum('none','daily','weekly','biweekly','monthly') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'none',
  `start_payment` datetime DEFAULT NULL,
  `date_applied` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_categories`
--

CREATE TABLE `loan_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `parent_id` varchar(36) DEFAULT '',
  `name` varchar(150) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_fees`
--

CREATE TABLE `loan_fees` (
  `id` int(11) UNSIGNED NOT NULL,
  `loan_id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `loan_id` int(11) NOT NULL,
  `preferred_date` datetime NOT NULL,
  `date_paid` datetime NOT NULL,
  `late_interest` decimal(10,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `serial_data` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `loan_stages`
--

CREATE TABLE `loan_stages` (
  `id` int(11) UNSIGNED NOT NULL,
  `loan_id` int(11) NOT NULL,
  `stage_name` varchar(180) NOT NULL,
  `serial_data` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `executed_by` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Untitled',
  `message` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `status` enum('unread','read') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unread',
  `message_id` int(11) NOT NULL DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `files` longtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted_by_users` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(20230801080700);

-- --------------------------------------------------------

--
-- Table structure for table `migrations_backup`
--

CREATE TABLE `migrations_backup` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE `milestones` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `files` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `notify_to` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `read_by` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `event` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `project_comment_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `ticket_comment_id` int(11) NOT NULL,
  `project_file_id` int(11) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `activity_log_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `invoice_payment_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `estimate_id` int(11) NOT NULL,
  `estimate_request_id` int(11) NOT NULL,
  `actual_message_id` int(11) NOT NULL,
  `parent_message_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE `notification_settings` (
  `id` int(11) NOT NULL,
  `event` varchar(250) NOT NULL,
  `category` varchar(50) NOT NULL,
  `enable_email` int(1) NOT NULL DEFAULT 0,
  `enable_web` int(1) NOT NULL DEFAULT 0,
  `enable_slack` int(1) NOT NULL DEFAULT 0,
  `notify_to_team` text NOT NULL,
  `notify_to_team_members` text NOT NULL,
  `notify_to_terms` text NOT NULL,
  `sort` int(11) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `internal_use_only` tinyint(1) NOT NULL DEFAULT 0,
  `visible_to_team_members_only` tinyint(1) NOT NULL DEFAULT 0,
  `visible_to_clients_only` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pallets`
--

CREATE TABLE `pallets` (
  `id` int(11) UNSIGNED NOT NULL,
  `position_id` int(11) DEFAULT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `qrcode` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `rfid` varchar(25) NOT NULL,
  `labels` text NOT NULL,
  `remarks` text NOT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'inactive',
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payhp_migrations`
--

CREATE TABLE `payhp_migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payhp_payslips`
--

CREATE TABLE `payhp_payslips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fullname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `directorate` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bankname` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banknum` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payriod` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paydate` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly` double(8,2) NOT NULL DEFAULT 0.00,
  `allowance` double(8,2) NOT NULL DEFAULT 0.00,
  `deduction` double(8,2) NOT NULL DEFAULT 0.00,
  `incentive` double(8,2) NOT NULL DEFAULT 0.00,
  `nightdiff` double(8,2) NOT NULL DEFAULT 0.00,
  `reghdpay` double(8,2) NOT NULL DEFAULT 0.00,
  `spchdpay` double(8,2) NOT NULL DEFAULT 0.00,
  `regot` double(8,2) NOT NULL DEFAULT 0.00,
  `rstot` double(8,2) NOT NULL DEFAULT 0.00,
  `reghdot` double(8,2) NOT NULL DEFAULT 0.00,
  `spchdot` double(8,2) NOT NULL DEFAULT 0.00,
  `tax` double(8,2) NOT NULL DEFAULT 0.00,
  `sss` double(8,2) NOT NULL DEFAULT 0.00,
  `phealth` double(8,2) NOT NULL DEFAULT 0.00,
  `pagibig` double(8,2) NOT NULL DEFAULT 0.00,
  `hmo` double(8,2) NOT NULL DEFAULT 0.00,
  `other` double(8,2) NOT NULL DEFAULT 0.00,
  `generated_by` bigint(20) DEFAULT NULL,
  `emailed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'custom',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `online_payable` tinyint(1) NOT NULL DEFAULT 0,
  `available_on_invoice` tinyint(1) NOT NULL DEFAULT 0,
  `minimum_payment_amount` double NOT NULL DEFAULT 0,
  `available_on_payroll` tinyint(1) NOT NULL DEFAULT 0,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0,
  `settings` longtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `title`, `type`, `description`, `online_payable`, `available_on_invoice`, `minimum_payment_amount`, `available_on_payroll`, `created_on`, `created_by`, `settings`, `deleted`) VALUES
(1, 'Cash', 'custom', 'Cash payments', 0, 0, 0, 0, '2021-03-07 18:57:10', 0, '', 0),
(2, 'Stripe', 'stripe', 'Stripe online payments', 1, 0, 0, 0, '2021-03-07 18:57:10', 0, 'a:3:{s:15:\"pay_button_text\";s:6:\"Stripe\";s:10:\"secret_key\";s:6:\"\";s:15:\"publishable_key\";s:6:\"\";}', 0),
(3, 'PayPal Payments Standard', 'paypal_payments_standard', 'PayPal Payments Standard Online Payments', 1, 0, 0, 0, '2021-03-07 18:57:10', 0, 'a:4:{s:15:\"pay_button_text\";s:6:\"PayPal\";s:5:\"email\";s:4:\"\";s:11:\"paypal_live\";s:1:\"0\";s:5:\"debug\";s:1:\"0\";}', 0),
(4, 'Check', 'custom', 'Checking Account', 0, 0, 0, 0, '2021-03-07 18:57:10', 0, 'a:0:{}', 0);

-- --------------------------------------------------------

--
-- Table structure for table `paypal_ipn`
--

CREATE TABLE `paypal_ipn` (
  `id` int(11) NOT NULL,
  `transaction_id` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `ipn_hash` longtext COLLATE utf8_unicode_ci NOT NULL,
  `ipn_data` longtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` int(11) UNSIGNED NOT NULL,
  `category` int(11) NOT NULL,
  `department` text DEFAULT NULL,
  `earnings` text DEFAULT NULL,
  `deductions` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `sched_hours` decimal(10,2) NOT NULL,
  `remarks` text NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `signed_by` int(11) DEFAULT NULL,
  `tax_table` enum('daily','weekly','biweekly','monthly') NOT NULL,
  `accountant_id` int(11) NOT NULL,
  `status` enum('draft','cancelled','ongoing','completed') NOT NULL DEFAULT 'draft',
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payslips`
--

CREATE TABLE `payslips` (
  `id` int(11) UNSIGNED NOT NULL,
  `payroll` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL,
  `schedule` decimal(10,2) NOT NULL,
  `worked` decimal(10,2) NOT NULL,
  `absent` decimal(10,2) NOT NULL,
  `bonus` decimal(10,2) DEFAULT 0.00,
  `leave_credit` decimal(10,2) DEFAULT 0.00,
  `special_hd` decimal(10,2) DEFAULT 0.00,
  `legal_hd` decimal(10,2) DEFAULT 0.00,
  `reg_nd` decimal(10,2) NOT NULL,
  `reg_ot` decimal(10,2) NOT NULL,
  `rest_ot` decimal(10,2) NOT NULL,
  `pto` decimal(10,2) NOT NULL,
  `signed_by` int(11) DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `status` enum('draft','approved','rejected') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `remarks` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payslips_deductions`
--

CREATE TABLE `payslips_deductions` (
  `id` int(11) UNSIGNED NOT NULL,
  `payslip_id` int(11) DEFAULT NULL,
  `item_key` varchar(180) NOT NULL,
  `title` varchar(150) NOT NULL DEFAULT '',
  `amount` decimal(10,2) DEFAULT 0.00,
  `remarks` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payslips_earnings`
--

CREATE TABLE `payslips_earnings` (
  `id` int(11) UNSIGNED NOT NULL,
  `payslip_id` int(11) DEFAULT NULL,
  `item_key` varchar(180) NOT NULL,
  `title` varchar(150) NOT NULL DEFAULT '',
  `amount` decimal(10,2) DEFAULT 0.00,
  `remarks` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `payslips_sents`
--

CREATE TABLE `payslips_sents` (
  `id` int(11) UNSIGNED NOT NULL,
  `payslip_id` int(11) DEFAULT NULL,
  `serialized` text NOT NULL,
  `remarks` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) UNSIGNED NOT NULL,
  `level_id` int(11) DEFAULT NULL,
  `qrcode` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `rfid` varchar(25) NOT NULL,
  `labels` text NOT NULL,
  `remarks` text NOT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'inactive',
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `post_id` int(11) NOT NULL,
  `share_with` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `files` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productions`
--

CREATE TABLE `productions` (
  `id` bigint(10) NOT NULL,
  `bill_of_material_id` bigint(10) NOT NULL,
  `inventory_id` bigint(10) NOT NULL,
  `status` enum('draft','ongoing','completed','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `quantity` int(10) NOT NULL,
  `buffer` int(10) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_brands`
--

CREATE TABLE `product_brands` (
  `id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(36) DEFAULT '',
  `parent_id` varchar(36) DEFAULT '',
  `name` varchar(150) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `created_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `status` enum('open','completed','hold','canceled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'open',
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` double NOT NULL DEFAULT 0,
  `starred_by` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `estimate_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_comments`
--

CREATE TABLE `project_comments` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `comment_id` int(11) NOT NULL DEFAULT 0,
  `task_id` int(11) NOT NULL DEFAULT 0,
  `file_id` int(11) NOT NULL DEFAULT 0,
  `customer_feedback_id` int(11) NOT NULL DEFAULT 0,
  `files` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_files`
--

CREATE TABLE `project_files` (
  `id` int(11) NOT NULL,
  `file_name` text COLLATE utf8_unicode_ci NOT NULL,
  `file_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_size` double NOT NULL,
  `created_at` datetime NOT NULL,
  `project_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `is_leader` tinyint(1) DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_settings`
--

CREATE TABLE `project_settings` (
  `project_id` int(11) NOT NULL,
  `setting_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `setting_value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_time`
--

CREATE TABLE `project_time` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `hours` float NOT NULL,
  `status` enum('open','logged','approved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'logged',
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `task_id` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `expense_id` bigint(10) DEFAULT NULL,
  `vendor_id` bigint(10) NOT NULL,
  `account_id` bigint(10) NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `status` enum('draft','completed','cancelled','partially_budgeted') COLLATE utf8_unicode_ci NOT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `cancelled_by` bigint(10) DEFAULT NULL,
  `last_email_sent_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_budgets`
--

CREATE TABLE `purchase_order_budgets` (
  `id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `purchase_id` bigint(10) NOT NULL,
  `created_by` int(11) DEFAULT 1,
  `created_on` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_materials`
--

CREATE TABLE `purchase_order_materials` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `quantity` double NOT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `material_id` bigint(10) NOT NULL,
  `material_inventory_id` bigint(10) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_returns`
--

CREATE TABLE `purchase_order_returns` (
  `id` int(11) NOT NULL,
  `purchase_id` bigint(10) NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('draft','completed','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `cancelled_at` datetime DEFAULT NULL,
  `cancelled_by` bigint(10) DEFAULT NULL,
  `last_email_sent_date` datetime DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_return_materials`
--

CREATE TABLE `purchase_order_return_materials` (
  `id` int(11) NOT NULL,
  `purchase_order_return_id` bigint(10) NOT NULL,
  `purchase_order_material_id` bigint(10) NOT NULL,
  `quantity` double NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `racks`
--

CREATE TABLE `racks` (
  `id` int(11) UNSIGNED NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `qrcode` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `rfid` varchar(25) NOT NULL,
  `labels` text NOT NULL,
  `remarks` text NOT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'inactive',
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `permissions`, `deleted`) VALUES
(1, 'IT SUPPORT', 'a:233:{s:21:\"disable_event_sharing\";s:0:\"\";s:12:\"announcement\";s:1:\"1\";s:18:\"message_permission\";s:3:\"all\";s:27:\"message_permission_specific\";s:0:\"\";s:11:\"access_logs\";s:1:\"1\";s:5:\"staff\";s:8:\"specific\";s:14:\"staff_specific\";s:218:\"team:3,team:4,team:5,team:8,team:9,team:10,team:11,team:13,team:18,team:19,team:20,team:21,team:23,team:25,team:26,team:27,team:28,team:29,team:30,team:32,team:35,team:41,team:43,team:44,team:45,team:46,team:47,team:48\";s:12:\"staff_invite\";s:1:\"1\";s:13:\"staff_account\";s:1:\"1\";s:21:\"staff_update_schedule\";s:1:\"1\";s:30:\"staff_view_personal_background\";s:1:\"1\";s:26:\"staff_view_job_description\";s:1:\"1\";s:20:\"staff_view_bank_info\";s:1:\"1\";s:31:\"staff_view_contribution_details\";s:1:\"1\";s:34:\"can_view_team_members_contact_info\";s:1:\"1\";s:34:\"can_view_team_members_social_links\";s:1:\"1\";s:10:\"department\";s:3:\"all\";s:19:\"department_specific\";s:0:\"\";s:10:\"attendance\";s:3:\"all\";s:19:\"attendance_specific\";s:0:\"\";s:5:\"leave\";s:3:\"all\";s:14:\"leave_specific\";s:0:\"\";s:7:\"holiday\";s:1:\"1\";s:11:\"deciplinary\";s:1:\"1\";s:17:\"can_use_biometric\";s:0:\"\";s:8:\"schedule\";s:1:\"1\";s:9:\"warehouse\";s:0:\"\";s:9:\"inventory\";s:0:\"\";s:8:\"purchase\";s:0:\"\";s:6:\"return\";s:0:\"\";s:8:\"supplier\";s:0:\"\";s:10:\"production\";s:0:\"\";s:14:\"billofmaterial\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:8:\"delivery\";s:0:\"\";s:13:\"item_transfer\";s:0:\"\";s:7:\"vehicle\";s:0:\"\";s:6:\"driver\";s:0:\"\";s:18:\"accounting_summary\";s:0:\"\";s:13:\"balance_sheet\";s:0:\"\";s:7:\"account\";s:0:\"\";s:8:\"transfer\";s:0:\"\";s:7:\"payment\";s:0:\"\";s:7:\"expense\";s:0:\"\";s:4:\"loan\";s:1:\"1\";s:3:\"tax\";s:0:\"\";s:13:\"can_use_payhp\";s:1:\"1\";s:7:\"payroll\";s:0:\"\";s:25:\"payroll_auto_contribution\";s:1:\"1\";s:22:\"compensation_tax_table\";s:0:\"\";s:13:\"sales_summary\";s:0:\"\";s:7:\"invoice\";s:0:\"\";s:7:\"service\";s:0:\"\";s:7:\"product\";s:0:\"\";s:6:\"client\";s:0:\"\";s:5:\"store\";s:0:\"\";s:4:\"lead\";s:0:\"\";s:8:\"estimate\";s:0:\"\";s:16:\"estimate_request\";s:0:\"\";s:11:\"event_epass\";s:0:\"\";s:11:\"raffle_draw\";s:0:\"\";s:5:\"asset\";s:0:\"\";s:8:\"location\";s:0:\"\";s:6:\"vendor\";s:0:\"\";s:5:\"brand\";s:0:\"\";s:23:\"can_manage_all_projects\";s:0:\"\";s:19:\"can_create_projects\";s:0:\"\";s:17:\"can_edit_projects\";s:0:\"\";s:19:\"can_delete_projects\";s:0:\"\";s:30:\"can_add_remove_project_members\";s:0:\"\";s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";s:1:\"1\";s:37:\"can_update_only_assigned_tasks_status\";s:1:\"1\";s:20:\"can_comment_on_tasks\";s:1:\"1\";s:24:\"show_assigned_tasks_only\";s:0:\"\";s:21:\"can_create_milestones\";s:1:\"1\";s:19:\"can_edit_milestones\";s:1:\"1\";s:21:\"can_delete_milestones\";s:1:\"1\";s:27:\"timesheet_manage_permission\";s:3:\"all\";s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:16:\"can_delete_files\";s:1:\"1\";s:6:\"ticket\";s:8:\"specific\";s:15:\"ticket_specific\";s:5:\"3,4,1\";s:12:\"ticket_staff\";s:3:\"all\";s:21:\"ticket_staff_specific\";s:0:\"\";s:4:\"page\";s:1:\"1\";s:4:\"help\";s:1:\"1\";s:14:\"knowledge_base\";s:1:\"1\";s:19:\"announcement_create\";s:0:\"\";s:19:\"announcement_update\";s:0:\"\";s:19:\"announcement_delete\";s:0:\"\";s:18:\"access_logs_create\";s:0:\"\";s:18:\"access_logs_update\";s:0:\"\";s:18:\"access_logs_delete\";s:0:\"\";s:12:\"staff_create\";s:1:\"1\";s:12:\"staff_update\";s:1:\"1\";s:12:\"staff_delete\";s:1:\"1\";s:17:\"department_create\";s:1:\"1\";s:17:\"department_update\";s:1:\"1\";s:17:\"department_delete\";s:1:\"1\";s:17:\"attendance_create\";s:0:\"\";s:17:\"attendance_update\";s:0:\"\";s:17:\"attendance_delete\";s:0:\"\";s:12:\"leave_create\";s:1:\"1\";s:12:\"leave_update\";s:1:\"1\";s:12:\"leave_delete\";s:0:\"\";s:14:\"holiday_create\";s:1:\"1\";s:14:\"holiday_update\";s:1:\"1\";s:14:\"holiday_delete\";s:1:\"1\";s:18:\"deciplinary_create\";s:1:\"1\";s:18:\"deciplinary_update\";s:1:\"1\";s:18:\"deciplinary_delete\";s:1:\"1\";s:15:\"schedule_create\";s:0:\"\";s:15:\"schedule_update\";s:0:\"\";s:15:\"schedule_delete\";s:0:\"\";s:16:\"warehouse_create\";s:0:\"\";s:16:\"warehouse_update\";s:0:\"\";s:16:\"warehouse_delete\";s:0:\"\";s:16:\"inventory_create\";s:0:\"\";s:16:\"inventory_update\";s:0:\"\";s:16:\"inventory_delete\";s:0:\"\";s:15:\"purchase_create\";s:0:\"\";s:15:\"purchase_update\";s:0:\"\";s:15:\"purchase_delete\";s:0:\"\";s:13:\"return_create\";s:0:\"\";s:13:\"return_update\";s:0:\"\";s:13:\"return_delete\";s:0:\"\";s:15:\"supplier_create\";s:0:\"\";s:15:\"supplier_update\";s:0:\"\";s:15:\"supplier_delete\";s:0:\"\";s:17:\"production_create\";s:0:\"\";s:17:\"production_update\";s:0:\"\";s:17:\"production_delete\";s:0:\"\";s:21:\"billofmaterial_create\";s:0:\"\";s:21:\"billofmaterial_update\";s:0:\"\";s:21:\"billofmaterial_delete\";s:0:\"\";s:11:\"unit_create\";s:0:\"\";s:11:\"unit_update\";s:0:\"\";s:11:\"unit_delete\";s:0:\"\";s:15:\"delivery_create\";s:0:\"\";s:15:\"delivery_update\";s:0:\"\";s:15:\"delivery_delete\";s:0:\"\";s:20:\"item_transfer_create\";s:0:\"\";s:20:\"item_transfer_update\";s:0:\"\";s:20:\"item_transfer_delete\";s:0:\"\";s:14:\"vehicle_create\";s:0:\"\";s:14:\"vehicle_update\";s:0:\"\";s:14:\"vehicle_delete\";s:0:\"\";s:13:\"driver_create\";s:0:\"\";s:13:\"driver_update\";s:0:\"\";s:13:\"driver_delete\";s:0:\"\";s:14:\"account_create\";s:0:\"\";s:14:\"account_update\";s:0:\"\";s:14:\"account_delete\";s:0:\"\";s:15:\"transfer_create\";s:0:\"\";s:15:\"transfer_update\";s:0:\"\";s:15:\"transfer_delete\";s:0:\"\";s:14:\"payment_create\";s:0:\"\";s:14:\"payment_update\";s:0:\"\";s:14:\"payment_delete\";s:0:\"\";s:14:\"expense_create\";s:0:\"\";s:14:\"expense_update\";s:0:\"\";s:14:\"expense_delete\";s:0:\"\";s:11:\"loan_create\";s:1:\"1\";s:11:\"loan_update\";s:1:\"1\";s:11:\"loan_delete\";s:0:\"\";s:10:\"tax_create\";s:0:\"\";s:10:\"tax_update\";s:0:\"\";s:10:\"tax_delete\";s:0:\"\";s:14:\"payroll_create\";s:0:\"\";s:14:\"payroll_update\";s:0:\"\";s:14:\"payroll_delete\";s:0:\"\";s:14:\"invoice_create\";s:0:\"\";s:14:\"invoice_update\";s:0:\"\";s:14:\"invoice_delete\";s:0:\"\";s:14:\"service_create\";s:0:\"\";s:14:\"service_update\";s:0:\"\";s:14:\"service_delete\";s:0:\"\";s:14:\"product_create\";s:0:\"\";s:14:\"product_update\";s:0:\"\";s:14:\"product_delete\";s:0:\"\";s:13:\"client_create\";s:0:\"\";s:13:\"client_update\";s:0:\"\";s:13:\"client_delete\";s:0:\"\";s:12:\"store_create\";s:0:\"\";s:12:\"store_update\";s:0:\"\";s:12:\"store_delete\";s:0:\"\";s:11:\"lead_create\";s:0:\"\";s:11:\"lead_update\";s:0:\"\";s:11:\"lead_delete\";s:0:\"\";s:15:\"estimate_create\";s:0:\"\";s:15:\"estimate_update\";s:0:\"\";s:15:\"estimate_delete\";s:0:\"\";s:23:\"estimate_request_create\";s:0:\"\";s:23:\"estimate_request_update\";s:0:\"\";s:23:\"estimate_request_delete\";s:0:\"\";s:18:\"event_epass_create\";s:0:\"\";s:18:\"event_epass_update\";s:0:\"\";s:18:\"event_epass_delete\";s:0:\"\";s:18:\"raffle_draw_create\";s:0:\"\";s:18:\"raffle_draw_update\";s:0:\"\";s:18:\"raffle_draw_delete\";s:0:\"\";s:12:\"asset_create\";s:0:\"\";s:12:\"asset_update\";s:0:\"\";s:12:\"asset_delete\";s:0:\"\";s:21:\"asset_category_create\";s:0:\"\";s:21:\"asset_category_update\";s:0:\"\";s:21:\"asset_category_delete\";s:0:\"\";s:15:\"location_create\";s:0:\"\";s:15:\"location_update\";s:0:\"\";s:15:\"location_delete\";s:0:\"\";s:13:\"vendor_create\";s:0:\"\";s:13:\"vendor_update\";s:0:\"\";s:13:\"vendor_delete\";s:0:\"\";s:12:\"brand_create\";s:0:\"\";s:12:\"brand_update\";s:0:\"\";s:12:\"brand_delete\";s:0:\"\";s:11:\"page_create\";s:1:\"1\";s:11:\"page_update\";s:1:\"1\";s:11:\"page_delete\";s:1:\"1\";s:11:\"help_create\";s:1:\"1\";s:11:\"help_update\";s:1:\"1\";s:11:\"help_delete\";s:1:\"1\";s:20:\"help_category_create\";s:1:\"1\";s:20:\"help_category_update\";s:1:\"1\";s:20:\"help_category_delete\";s:1:\"1\";s:21:\"knowledge_base_create\";s:1:\"1\";s:21:\"knowledge_base_update\";s:1:\"1\";s:21:\"knowledge_base_delete\";s:1:\"1\";s:30:\"knowledge_base_category_create\";s:1:\"1\";s:30:\"knowledge_base_category_update\";s:1:\"1\";s:30:\"knowledge_base_category_delete\";s:1:\"1\";}', 0),
(2, 'ACCTG Manager', 'a:230:{s:21:\"disable_event_sharing\";s:0:\"\";s:12:\"announcement\";s:0:\"\";s:18:\"message_permission\";s:8:\"specific\";s:27:\"message_permission_specific\";s:6:\"team:8\";s:11:\"access_logs\";s:0:\"\";s:5:\"staff\";s:8:\"specific\";s:14:\"staff_specific\";s:6:\"team:8\";s:12:\"staff_invite\";s:0:\"\";s:13:\"staff_account\";s:0:\"\";s:21:\"staff_update_schedule\";s:0:\"\";s:30:\"staff_view_personal_background\";s:0:\"\";s:26:\"staff_view_job_description\";s:0:\"\";s:20:\"staff_view_bank_info\";s:0:\"\";s:31:\"staff_view_contribution_details\";s:0:\"\";s:34:\"can_view_team_members_contact_info\";s:0:\"\";s:34:\"can_view_team_members_social_links\";s:0:\"\";s:10:\"department\";s:0:\"\";s:19:\"department_specific\";s:0:\"\";s:10:\"attendance\";s:8:\"specific\";s:19:\"attendance_specific\";s:6:\"team:8\";s:5:\"leave\";s:8:\"specific\";s:14:\"leave_specific\";s:6:\"team:8\";s:7:\"holiday\";s:0:\"\";s:11:\"deciplinary\";s:0:\"\";s:17:\"can_use_biometric\";s:0:\"\";s:8:\"schedule\";s:0:\"\";s:9:\"warehouse\";s:0:\"\";s:9:\"inventory\";s:0:\"\";s:8:\"purchase\";s:0:\"\";s:6:\"return\";s:0:\"\";s:8:\"supplier\";s:0:\"\";s:10:\"production\";s:0:\"\";s:14:\"billofmaterial\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:8:\"delivery\";s:0:\"\";s:13:\"item_transfer\";s:0:\"\";s:7:\"vehicle\";s:0:\"\";s:6:\"driver\";s:0:\"\";s:18:\"accounting_summary\";s:0:\"\";s:13:\"balance_sheet\";s:0:\"\";s:7:\"account\";s:0:\"\";s:8:\"transfer\";s:0:\"\";s:7:\"payment\";s:0:\"\";s:7:\"expense\";s:1:\"1\";s:4:\"loan\";s:0:\"\";s:3:\"tax\";s:0:\"\";s:13:\"can_use_payhp\";s:0:\"\";s:7:\"payroll\";s:1:\"1\";s:25:\"payroll_auto_contribution\";s:0:\"\";s:22:\"compensation_tax_table\";s:0:\"\";s:13:\"sales_summary\";s:0:\"\";s:7:\"invoice\";s:0:\"\";s:7:\"service\";s:0:\"\";s:7:\"product\";s:0:\"\";s:6:\"client\";s:1:\"1\";s:5:\"store\";s:0:\"\";s:4:\"lead\";s:0:\"\";s:8:\"estimate\";s:1:\"1\";s:16:\"estimate_request\";s:0:\"\";s:11:\"event_epass\";s:0:\"\";s:11:\"raffle_draw\";s:0:\"\";s:5:\"asset\";s:0:\"\";s:8:\"location\";s:0:\"\";s:6:\"vendor\";s:0:\"\";s:5:\"brand\";s:0:\"\";s:23:\"can_manage_all_projects\";s:1:\"1\";s:19:\"can_create_projects\";s:1:\"1\";s:17:\"can_edit_projects\";s:1:\"1\";s:19:\"can_delete_projects\";s:1:\"1\";s:30:\"can_add_remove_project_members\";s:1:\"1\";s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";s:1:\"1\";s:37:\"can_update_only_assigned_tasks_status\";s:0:\"\";s:20:\"can_comment_on_tasks\";s:1:\"1\";s:24:\"show_assigned_tasks_only\";s:0:\"\";s:21:\"can_create_milestones\";s:1:\"1\";s:19:\"can_edit_milestones\";s:1:\"1\";s:21:\"can_delete_milestones\";s:1:\"1\";s:27:\"timesheet_manage_permission\";s:3:\"all\";s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:16:\"can_delete_files\";s:1:\"1\";s:6:\"ticket\";s:0:\"\";s:15:\"ticket_specific\";s:0:\"\";s:12:\"ticket_staff\";s:0:\"\";s:21:\"ticket_staff_specific\";s:0:\"\";s:4:\"page\";s:0:\"\";s:4:\"help\";s:0:\"\";s:14:\"knowledge_base\";s:0:\"\";s:18:\"access_logs_create\";s:0:\"\";s:18:\"access_logs_update\";s:0:\"\";s:18:\"access_logs_delete\";s:0:\"\";s:12:\"staff_create\";s:0:\"\";s:12:\"staff_update\";s:0:\"\";s:12:\"staff_delete\";s:0:\"\";s:17:\"department_create\";s:0:\"\";s:17:\"department_update\";s:0:\"\";s:17:\"department_delete\";s:0:\"\";s:17:\"attendance_create\";s:0:\"\";s:17:\"attendance_update\";s:0:\"\";s:17:\"attendance_delete\";s:0:\"\";s:12:\"leave_create\";s:0:\"\";s:12:\"leave_update\";s:0:\"\";s:12:\"leave_delete\";s:0:\"\";s:14:\"holiday_create\";s:0:\"\";s:14:\"holiday_update\";s:0:\"\";s:14:\"holiday_delete\";s:0:\"\";s:18:\"deciplinary_create\";s:0:\"\";s:18:\"deciplinary_update\";s:0:\"\";s:18:\"deciplinary_delete\";s:0:\"\";s:15:\"schedule_create\";s:0:\"\";s:15:\"schedule_update\";s:0:\"\";s:15:\"schedule_delete\";s:0:\"\";s:16:\"warehouse_create\";s:0:\"\";s:16:\"warehouse_update\";s:0:\"\";s:16:\"warehouse_delete\";s:0:\"\";s:16:\"inventory_create\";s:0:\"\";s:16:\"inventory_update\";s:0:\"\";s:16:\"inventory_delete\";s:0:\"\";s:15:\"purchase_create\";s:0:\"\";s:15:\"purchase_update\";s:0:\"\";s:15:\"purchase_delete\";s:0:\"\";s:13:\"return_create\";s:0:\"\";s:13:\"return_update\";s:0:\"\";s:13:\"return_delete\";s:0:\"\";s:15:\"supplier_create\";s:0:\"\";s:15:\"supplier_update\";s:0:\"\";s:15:\"supplier_delete\";s:0:\"\";s:17:\"production_create\";s:0:\"\";s:17:\"production_update\";s:0:\"\";s:17:\"production_delete\";s:0:\"\";s:21:\"billofmaterial_create\";s:0:\"\";s:21:\"billofmaterial_update\";s:0:\"\";s:21:\"billofmaterial_delete\";s:0:\"\";s:11:\"unit_create\";s:0:\"\";s:11:\"unit_update\";s:0:\"\";s:11:\"unit_delete\";s:0:\"\";s:15:\"delivery_create\";s:0:\"\";s:15:\"delivery_update\";s:0:\"\";s:15:\"delivery_delete\";s:0:\"\";s:20:\"item_transfer_create\";s:0:\"\";s:20:\"item_transfer_update\";s:0:\"\";s:20:\"item_transfer_delete\";s:0:\"\";s:14:\"vehicle_create\";s:0:\"\";s:14:\"vehicle_update\";s:0:\"\";s:14:\"vehicle_delete\";s:0:\"\";s:13:\"driver_create\";s:0:\"\";s:13:\"driver_update\";s:0:\"\";s:13:\"driver_delete\";s:0:\"\";s:14:\"account_create\";s:0:\"\";s:14:\"account_update\";s:0:\"\";s:14:\"account_delete\";s:0:\"\";s:15:\"transfer_create\";s:0:\"\";s:15:\"transfer_update\";s:0:\"\";s:15:\"transfer_delete\";s:0:\"\";s:14:\"payment_create\";s:0:\"\";s:14:\"payment_update\";s:0:\"\";s:14:\"payment_delete\";s:0:\"\";s:14:\"expense_create\";s:0:\"\";s:14:\"expense_update\";s:0:\"\";s:14:\"expense_delete\";s:0:\"\";s:11:\"loan_create\";s:0:\"\";s:11:\"loan_update\";s:0:\"\";s:11:\"loan_delete\";s:0:\"\";s:10:\"tax_create\";s:0:\"\";s:10:\"tax_update\";s:0:\"\";s:10:\"tax_delete\";s:0:\"\";s:14:\"payroll_create\";s:0:\"\";s:14:\"payroll_update\";s:0:\"\";s:14:\"payroll_delete\";s:0:\"\";s:14:\"invoice_create\";s:0:\"\";s:14:\"invoice_update\";s:0:\"\";s:14:\"invoice_delete\";s:0:\"\";s:14:\"service_create\";s:0:\"\";s:14:\"service_update\";s:0:\"\";s:14:\"service_delete\";s:0:\"\";s:14:\"product_create\";s:0:\"\";s:14:\"product_update\";s:0:\"\";s:14:\"product_delete\";s:0:\"\";s:13:\"client_create\";s:0:\"\";s:13:\"client_update\";s:0:\"\";s:13:\"client_delete\";s:0:\"\";s:12:\"store_create\";s:0:\"\";s:12:\"store_update\";s:0:\"\";s:12:\"store_delete\";s:0:\"\";s:11:\"lead_create\";s:0:\"\";s:11:\"lead_update\";s:0:\"\";s:11:\"lead_delete\";s:0:\"\";s:15:\"estimate_create\";s:0:\"\";s:15:\"estimate_update\";s:0:\"\";s:15:\"estimate_delete\";s:0:\"\";s:23:\"estimate_request_create\";s:0:\"\";s:23:\"estimate_request_update\";s:0:\"\";s:23:\"estimate_request_delete\";s:0:\"\";s:18:\"event_epass_create\";s:0:\"\";s:18:\"event_epass_update\";s:0:\"\";s:18:\"event_epass_delete\";s:0:\"\";s:18:\"raffle_draw_create\";s:0:\"\";s:18:\"raffle_draw_update\";s:0:\"\";s:18:\"raffle_draw_delete\";s:0:\"\";s:12:\"asset_create\";s:0:\"\";s:12:\"asset_update\";s:0:\"\";s:12:\"asset_delete\";s:0:\"\";s:21:\"asset_category_create\";s:0:\"\";s:21:\"asset_category_update\";s:0:\"\";s:21:\"asset_category_delete\";s:0:\"\";s:15:\"location_create\";s:0:\"\";s:15:\"location_update\";s:0:\"\";s:15:\"location_delete\";s:0:\"\";s:13:\"vendor_create\";s:0:\"\";s:13:\"vendor_update\";s:0:\"\";s:13:\"vendor_delete\";s:0:\"\";s:12:\"brand_create\";s:0:\"\";s:12:\"brand_update\";s:0:\"\";s:12:\"brand_delete\";s:0:\"\";s:11:\"page_create\";s:0:\"\";s:11:\"page_update\";s:0:\"\";s:11:\"page_delete\";s:0:\"\";s:11:\"help_create\";s:0:\"\";s:11:\"help_update\";s:0:\"\";s:11:\"help_delete\";s:0:\"\";s:20:\"help_category_create\";s:0:\"\";s:20:\"help_category_update\";s:0:\"\";s:20:\"help_category_delete\";s:0:\"\";s:21:\"knowledge_base_create\";s:0:\"\";s:21:\"knowledge_base_update\";s:0:\"\";s:21:\"knowledge_base_delete\";s:0:\"\";s:30:\"knowledge_base_category_create\";s:0:\"\";s:30:\"knowledge_base_category_update\";s:0:\"\";s:30:\"knowledge_base_category_delete\";s:0:\"\";}', 0),
(3, 'HR Manager', 'a:234:{s:21:\"disable_event_sharing\";s:0:\"\";s:12:\"announcement\";s:1:\"1\";s:18:\"message_permission\";s:3:\"all\";s:27:\"message_permission_specific\";s:0:\"\";s:11:\"access_logs\";s:0:\"\";s:5:\"staff\";s:3:\"all\";s:14:\"staff_specific\";s:0:\"\";s:12:\"staff_invite\";s:0:\"\";s:13:\"staff_account\";s:0:\"\";s:21:\"staff_update_schedule\";s:1:\"1\";s:30:\"staff_view_personal_background\";s:0:\"\";s:26:\"staff_view_job_description\";s:0:\"\";s:20:\"staff_view_bank_info\";s:0:\"\";s:31:\"staff_view_contribution_details\";s:0:\"\";s:34:\"can_view_team_members_contact_info\";s:1:\"1\";s:34:\"can_view_team_members_social_links\";s:1:\"1\";s:10:\"department\";s:3:\"all\";s:19:\"department_specific\";s:0:\"\";s:10:\"attendance\";s:3:\"all\";s:19:\"attendance_specific\";s:0:\"\";s:5:\"leave\";s:3:\"all\";s:14:\"leave_specific\";s:0:\"\";s:12:\"leave_manage\";s:0:\"\";s:7:\"holiday\";s:0:\"\";s:11:\"deciplinary\";s:0:\"\";s:17:\"can_use_biometric\";s:0:\"\";s:8:\"schedule\";s:1:\"1\";s:9:\"warehouse\";s:0:\"\";s:9:\"inventory\";s:0:\"\";s:8:\"purchase\";s:0:\"\";s:6:\"return\";s:0:\"\";s:8:\"supplier\";s:0:\"\";s:10:\"production\";s:0:\"\";s:14:\"billofmaterial\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:8:\"delivery\";s:0:\"\";s:13:\"item_transfer\";s:0:\"\";s:7:\"vehicle\";s:0:\"\";s:6:\"driver\";s:0:\"\";s:18:\"accounting_summary\";s:0:\"\";s:13:\"balance_sheet\";s:0:\"\";s:7:\"account\";s:0:\"\";s:8:\"transfer\";s:0:\"\";s:7:\"payment\";s:0:\"\";s:7:\"expense\";s:1:\"1\";s:4:\"loan\";s:0:\"\";s:3:\"tax\";s:0:\"\";s:13:\"can_use_payhp\";s:1:\"1\";s:7:\"payroll\";s:1:\"1\";s:25:\"payroll_auto_contribution\";s:0:\"\";s:22:\"compensation_tax_table\";s:0:\"\";s:13:\"sales_summary\";s:0:\"\";s:7:\"invoice\";s:1:\"1\";s:7:\"service\";s:0:\"\";s:7:\"product\";s:0:\"\";s:6:\"client\";s:1:\"1\";s:5:\"store\";s:0:\"\";s:4:\"lead\";s:1:\"1\";s:8:\"estimate\";s:1:\"1\";s:16:\"estimate_request\";s:0:\"\";s:11:\"event_epass\";s:0:\"\";s:11:\"raffle_draw\";s:0:\"\";s:5:\"asset\";s:0:\"\";s:8:\"location\";s:0:\"\";s:6:\"vendor\";s:0:\"\";s:5:\"brand\";s:0:\"\";s:23:\"can_manage_all_projects\";s:1:\"1\";s:19:\"can_create_projects\";s:1:\"1\";s:17:\"can_edit_projects\";s:1:\"1\";s:19:\"can_delete_projects\";s:1:\"1\";s:30:\"can_add_remove_project_members\";s:1:\"1\";s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";s:1:\"1\";s:37:\"can_update_only_assigned_tasks_status\";s:0:\"\";s:20:\"can_comment_on_tasks\";s:1:\"1\";s:24:\"show_assigned_tasks_only\";s:0:\"\";s:21:\"can_create_milestones\";s:1:\"1\";s:19:\"can_edit_milestones\";s:1:\"1\";s:21:\"can_delete_milestones\";s:1:\"1\";s:27:\"timesheet_manage_permission\";s:3:\"all\";s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:16:\"can_delete_files\";s:1:\"1\";s:6:\"ticket\";s:3:\"all\";s:15:\"ticket_specific\";s:0:\"\";s:12:\"ticket_staff\";s:8:\"specific\";s:21:\"ticket_staff_specific\";s:7:\"team:13\";s:4:\"page\";s:0:\"\";s:4:\"help\";s:0:\"\";s:14:\"knowledge_base\";s:0:\"\";s:19:\"announcement_create\";s:0:\"\";s:19:\"announcement_update\";s:0:\"\";s:19:\"announcement_delete\";s:0:\"\";s:18:\"access_logs_create\";s:0:\"\";s:18:\"access_logs_update\";s:0:\"\";s:18:\"access_logs_delete\";s:0:\"\";s:12:\"staff_create\";s:0:\"\";s:12:\"staff_update\";s:0:\"\";s:12:\"staff_delete\";s:0:\"\";s:17:\"department_create\";s:0:\"\";s:17:\"department_update\";s:0:\"\";s:17:\"department_delete\";s:0:\"\";s:17:\"attendance_create\";s:1:\"1\";s:17:\"attendance_update\";s:1:\"1\";s:17:\"attendance_delete\";s:1:\"1\";s:12:\"leave_create\";s:1:\"1\";s:12:\"leave_update\";s:1:\"1\";s:12:\"leave_delete\";s:0:\"\";s:14:\"holiday_create\";s:0:\"\";s:14:\"holiday_update\";s:0:\"\";s:14:\"holiday_delete\";s:0:\"\";s:18:\"deciplinary_create\";s:0:\"\";s:18:\"deciplinary_update\";s:0:\"\";s:18:\"deciplinary_delete\";s:0:\"\";s:15:\"schedule_create\";s:0:\"\";s:15:\"schedule_update\";s:0:\"\";s:15:\"schedule_delete\";s:0:\"\";s:16:\"warehouse_create\";s:0:\"\";s:16:\"warehouse_update\";s:0:\"\";s:16:\"warehouse_delete\";s:0:\"\";s:16:\"inventory_create\";s:0:\"\";s:16:\"inventory_update\";s:0:\"\";s:16:\"inventory_delete\";s:0:\"\";s:15:\"purchase_create\";s:0:\"\";s:15:\"purchase_update\";s:0:\"\";s:15:\"purchase_delete\";s:0:\"\";s:13:\"return_create\";s:0:\"\";s:13:\"return_update\";s:0:\"\";s:13:\"return_delete\";s:0:\"\";s:15:\"supplier_create\";s:0:\"\";s:15:\"supplier_update\";s:0:\"\";s:15:\"supplier_delete\";s:0:\"\";s:17:\"production_create\";s:0:\"\";s:17:\"production_update\";s:0:\"\";s:17:\"production_delete\";s:0:\"\";s:21:\"billofmaterial_create\";s:0:\"\";s:21:\"billofmaterial_update\";s:0:\"\";s:21:\"billofmaterial_delete\";s:0:\"\";s:11:\"unit_create\";s:0:\"\";s:11:\"unit_update\";s:0:\"\";s:11:\"unit_delete\";s:0:\"\";s:15:\"delivery_create\";s:0:\"\";s:15:\"delivery_update\";s:0:\"\";s:15:\"delivery_delete\";s:0:\"\";s:20:\"item_transfer_create\";s:0:\"\";s:20:\"item_transfer_update\";s:0:\"\";s:20:\"item_transfer_delete\";s:0:\"\";s:14:\"vehicle_create\";s:0:\"\";s:14:\"vehicle_update\";s:0:\"\";s:14:\"vehicle_delete\";s:0:\"\";s:13:\"driver_create\";s:0:\"\";s:13:\"driver_update\";s:0:\"\";s:13:\"driver_delete\";s:0:\"\";s:14:\"account_create\";s:0:\"\";s:14:\"account_update\";s:0:\"\";s:14:\"account_delete\";s:0:\"\";s:15:\"transfer_create\";s:0:\"\";s:15:\"transfer_update\";s:0:\"\";s:15:\"transfer_delete\";s:0:\"\";s:14:\"payment_create\";s:0:\"\";s:14:\"payment_update\";s:0:\"\";s:14:\"payment_delete\";s:0:\"\";s:14:\"expense_create\";s:0:\"\";s:14:\"expense_update\";s:0:\"\";s:14:\"expense_delete\";s:0:\"\";s:11:\"loan_create\";s:0:\"\";s:11:\"loan_update\";s:0:\"\";s:11:\"loan_delete\";s:0:\"\";s:10:\"tax_create\";s:0:\"\";s:10:\"tax_update\";s:0:\"\";s:10:\"tax_delete\";s:0:\"\";s:14:\"payroll_create\";s:0:\"\";s:14:\"payroll_update\";s:0:\"\";s:14:\"payroll_delete\";s:0:\"\";s:14:\"invoice_create\";s:0:\"\";s:14:\"invoice_update\";s:0:\"\";s:14:\"invoice_delete\";s:0:\"\";s:14:\"service_create\";s:0:\"\";s:14:\"service_update\";s:0:\"\";s:14:\"service_delete\";s:0:\"\";s:14:\"product_create\";s:0:\"\";s:14:\"product_update\";s:0:\"\";s:14:\"product_delete\";s:0:\"\";s:13:\"client_create\";s:0:\"\";s:13:\"client_update\";s:0:\"\";s:13:\"client_delete\";s:0:\"\";s:12:\"store_create\";s:0:\"\";s:12:\"store_update\";s:0:\"\";s:12:\"store_delete\";s:0:\"\";s:11:\"lead_create\";s:1:\"1\";s:11:\"lead_update\";s:1:\"1\";s:11:\"lead_delete\";s:1:\"1\";s:15:\"estimate_create\";s:0:\"\";s:15:\"estimate_update\";s:0:\"\";s:15:\"estimate_delete\";s:0:\"\";s:23:\"estimate_request_create\";s:0:\"\";s:23:\"estimate_request_update\";s:0:\"\";s:23:\"estimate_request_delete\";s:0:\"\";s:18:\"event_epass_create\";s:0:\"\";s:18:\"event_epass_update\";s:0:\"\";s:18:\"event_epass_delete\";s:0:\"\";s:18:\"raffle_draw_create\";s:0:\"\";s:18:\"raffle_draw_update\";s:0:\"\";s:18:\"raffle_draw_delete\";s:0:\"\";s:12:\"asset_create\";s:0:\"\";s:12:\"asset_update\";s:0:\"\";s:12:\"asset_delete\";s:0:\"\";s:21:\"asset_category_create\";s:0:\"\";s:21:\"asset_category_update\";s:0:\"\";s:21:\"asset_category_delete\";s:0:\"\";s:15:\"location_create\";s:0:\"\";s:15:\"location_update\";s:0:\"\";s:15:\"location_delete\";s:0:\"\";s:13:\"vendor_create\";s:0:\"\";s:13:\"vendor_update\";s:0:\"\";s:13:\"vendor_delete\";s:0:\"\";s:12:\"brand_create\";s:0:\"\";s:12:\"brand_update\";s:0:\"\";s:12:\"brand_delete\";s:0:\"\";s:11:\"page_create\";s:0:\"\";s:11:\"page_update\";s:0:\"\";s:11:\"page_delete\";s:0:\"\";s:11:\"help_create\";s:0:\"\";s:11:\"help_update\";s:0:\"\";s:11:\"help_delete\";s:0:\"\";s:20:\"help_category_create\";s:0:\"\";s:20:\"help_category_update\";s:0:\"\";s:20:\"help_category_delete\";s:0:\"\";s:21:\"knowledge_base_create\";s:0:\"\";s:21:\"knowledge_base_update\";s:0:\"\";s:21:\"knowledge_base_delete\";s:0:\"\";s:30:\"knowledge_base_category_create\";s:0:\"\";s:30:\"knowledge_base_category_update\";s:0:\"\";s:30:\"knowledge_base_category_delete\";s:0:\"\";}', 0),
(4, 'HR Staff', 'a:230:{s:21:\"disable_event_sharing\";s:0:\"\";s:12:\"announcement\";s:1:\"1\";s:18:\"message_permission\";s:3:\"all\";s:27:\"message_permission_specific\";s:0:\"\";s:11:\"access_logs\";s:0:\"\";s:5:\"staff\";s:3:\"all\";s:14:\"staff_specific\";s:0:\"\";s:12:\"staff_invite\";s:1:\"1\";s:13:\"staff_account\";s:0:\"\";s:21:\"staff_update_schedule\";s:0:\"\";s:30:\"staff_view_personal_background\";s:1:\"1\";s:26:\"staff_view_job_description\";s:1:\"1\";s:20:\"staff_view_bank_info\";s:1:\"1\";s:31:\"staff_view_contribution_details\";s:1:\"1\";s:34:\"can_view_team_members_contact_info\";s:1:\"1\";s:34:\"can_view_team_members_social_links\";s:0:\"\";s:10:\"department\";s:3:\"all\";s:19:\"department_specific\";s:0:\"\";s:10:\"attendance\";s:3:\"all\";s:19:\"attendance_specific\";s:0:\"\";s:5:\"leave\";s:3:\"all\";s:14:\"leave_specific\";s:0:\"\";s:7:\"holiday\";s:1:\"1\";s:11:\"deciplinary\";s:1:\"1\";s:17:\"can_use_biometric\";s:0:\"\";s:8:\"schedule\";s:0:\"\";s:9:\"warehouse\";s:0:\"\";s:9:\"inventory\";s:0:\"\";s:8:\"purchase\";s:0:\"\";s:6:\"return\";s:0:\"\";s:8:\"supplier\";s:0:\"\";s:10:\"production\";s:0:\"\";s:14:\"billofmaterial\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:8:\"delivery\";s:0:\"\";s:13:\"item_transfer\";s:0:\"\";s:7:\"vehicle\";s:0:\"\";s:6:\"driver\";s:0:\"\";s:18:\"accounting_summary\";s:0:\"\";s:13:\"balance_sheet\";s:0:\"\";s:7:\"account\";s:0:\"\";s:8:\"transfer\";s:0:\"\";s:7:\"payment\";s:0:\"\";s:7:\"expense\";s:1:\"1\";s:4:\"loan\";s:0:\"\";s:3:\"tax\";s:0:\"\";s:13:\"can_use_payhp\";s:1:\"1\";s:7:\"payroll\";s:1:\"1\";s:25:\"payroll_auto_contribution\";s:0:\"\";s:22:\"compensation_tax_table\";s:0:\"\";s:13:\"sales_summary\";s:0:\"\";s:7:\"invoice\";s:0:\"\";s:7:\"service\";s:0:\"\";s:7:\"product\";s:0:\"\";s:6:\"client\";s:1:\"1\";s:5:\"store\";s:0:\"\";s:4:\"lead\";s:1:\"1\";s:8:\"estimate\";s:1:\"1\";s:16:\"estimate_request\";s:0:\"\";s:11:\"event_epass\";s:0:\"\";s:11:\"raffle_draw\";s:0:\"\";s:5:\"asset\";s:0:\"\";s:8:\"location\";s:0:\"\";s:6:\"vendor\";s:0:\"\";s:5:\"brand\";s:0:\"\";s:23:\"can_manage_all_projects\";s:1:\"1\";s:19:\"can_create_projects\";s:1:\"1\";s:17:\"can_edit_projects\";s:1:\"1\";s:19:\"can_delete_projects\";s:1:\"1\";s:30:\"can_add_remove_project_members\";s:1:\"1\";s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";s:1:\"1\";s:37:\"can_update_only_assigned_tasks_status\";s:0:\"\";s:20:\"can_comment_on_tasks\";s:1:\"1\";s:24:\"show_assigned_tasks_only\";s:0:\"\";s:21:\"can_create_milestones\";s:1:\"1\";s:19:\"can_edit_milestones\";s:1:\"1\";s:21:\"can_delete_milestones\";s:1:\"1\";s:27:\"timesheet_manage_permission\";s:3:\"all\";s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:16:\"can_delete_files\";s:1:\"1\";s:6:\"ticket\";s:3:\"all\";s:15:\"ticket_specific\";s:0:\"\";s:12:\"ticket_staff\";s:0:\"\";s:21:\"ticket_staff_specific\";s:0:\"\";s:4:\"page\";s:0:\"\";s:4:\"help\";s:0:\"\";s:14:\"knowledge_base\";s:0:\"\";s:18:\"access_logs_create\";s:0:\"\";s:18:\"access_logs_update\";s:0:\"\";s:18:\"access_logs_delete\";s:0:\"\";s:12:\"staff_create\";s:1:\"1\";s:12:\"staff_update\";s:1:\"1\";s:12:\"staff_delete\";s:1:\"1\";s:17:\"department_create\";s:0:\"\";s:17:\"department_update\";s:1:\"1\";s:17:\"department_delete\";s:0:\"\";s:17:\"attendance_create\";s:0:\"\";s:17:\"attendance_update\";s:0:\"\";s:17:\"attendance_delete\";s:0:\"\";s:12:\"leave_create\";s:1:\"1\";s:12:\"leave_update\";s:0:\"\";s:12:\"leave_delete\";s:0:\"\";s:14:\"holiday_create\";s:1:\"1\";s:14:\"holiday_update\";s:1:\"1\";s:14:\"holiday_delete\";s:1:\"1\";s:18:\"deciplinary_create\";s:1:\"1\";s:18:\"deciplinary_update\";s:1:\"1\";s:18:\"deciplinary_delete\";s:1:\"1\";s:15:\"schedule_create\";s:0:\"\";s:15:\"schedule_update\";s:0:\"\";s:15:\"schedule_delete\";s:0:\"\";s:16:\"warehouse_create\";s:0:\"\";s:16:\"warehouse_update\";s:0:\"\";s:16:\"warehouse_delete\";s:0:\"\";s:16:\"inventory_create\";s:0:\"\";s:16:\"inventory_update\";s:0:\"\";s:16:\"inventory_delete\";s:0:\"\";s:15:\"purchase_create\";s:0:\"\";s:15:\"purchase_update\";s:0:\"\";s:15:\"purchase_delete\";s:0:\"\";s:13:\"return_create\";s:0:\"\";s:13:\"return_update\";s:0:\"\";s:13:\"return_delete\";s:0:\"\";s:15:\"supplier_create\";s:0:\"\";s:15:\"supplier_update\";s:0:\"\";s:15:\"supplier_delete\";s:0:\"\";s:17:\"production_create\";s:0:\"\";s:17:\"production_update\";s:0:\"\";s:17:\"production_delete\";s:0:\"\";s:21:\"billofmaterial_create\";s:0:\"\";s:21:\"billofmaterial_update\";s:0:\"\";s:21:\"billofmaterial_delete\";s:0:\"\";s:11:\"unit_create\";s:0:\"\";s:11:\"unit_update\";s:0:\"\";s:11:\"unit_delete\";s:0:\"\";s:15:\"delivery_create\";s:0:\"\";s:15:\"delivery_update\";s:0:\"\";s:15:\"delivery_delete\";s:0:\"\";s:20:\"item_transfer_create\";s:0:\"\";s:20:\"item_transfer_update\";s:0:\"\";s:20:\"item_transfer_delete\";s:0:\"\";s:14:\"vehicle_create\";s:0:\"\";s:14:\"vehicle_update\";s:0:\"\";s:14:\"vehicle_delete\";s:0:\"\";s:13:\"driver_create\";s:0:\"\";s:13:\"driver_update\";s:0:\"\";s:13:\"driver_delete\";s:0:\"\";s:14:\"account_create\";s:0:\"\";s:14:\"account_update\";s:0:\"\";s:14:\"account_delete\";s:0:\"\";s:15:\"transfer_create\";s:0:\"\";s:15:\"transfer_update\";s:0:\"\";s:15:\"transfer_delete\";s:0:\"\";s:14:\"payment_create\";s:0:\"\";s:14:\"payment_update\";s:0:\"\";s:14:\"payment_delete\";s:0:\"\";s:14:\"expense_create\";s:0:\"\";s:14:\"expense_update\";s:0:\"\";s:14:\"expense_delete\";s:0:\"\";s:11:\"loan_create\";s:0:\"\";s:11:\"loan_update\";s:0:\"\";s:11:\"loan_delete\";s:0:\"\";s:10:\"tax_create\";s:0:\"\";s:10:\"tax_update\";s:0:\"\";s:10:\"tax_delete\";s:0:\"\";s:14:\"payroll_create\";s:1:\"1\";s:14:\"payroll_update\";s:1:\"1\";s:14:\"payroll_delete\";s:0:\"\";s:14:\"invoice_create\";s:0:\"\";s:14:\"invoice_update\";s:0:\"\";s:14:\"invoice_delete\";s:0:\"\";s:14:\"service_create\";s:0:\"\";s:14:\"service_update\";s:0:\"\";s:14:\"service_delete\";s:0:\"\";s:14:\"product_create\";s:0:\"\";s:14:\"product_update\";s:0:\"\";s:14:\"product_delete\";s:0:\"\";s:13:\"client_create\";s:0:\"\";s:13:\"client_update\";s:0:\"\";s:13:\"client_delete\";s:0:\"\";s:12:\"store_create\";s:0:\"\";s:12:\"store_update\";s:0:\"\";s:12:\"store_delete\";s:0:\"\";s:11:\"lead_create\";s:0:\"\";s:11:\"lead_update\";s:0:\"\";s:11:\"lead_delete\";s:0:\"\";s:15:\"estimate_create\";s:0:\"\";s:15:\"estimate_update\";s:0:\"\";s:15:\"estimate_delete\";s:0:\"\";s:23:\"estimate_request_create\";s:0:\"\";s:23:\"estimate_request_update\";s:0:\"\";s:23:\"estimate_request_delete\";s:0:\"\";s:18:\"event_epass_create\";s:0:\"\";s:18:\"event_epass_update\";s:0:\"\";s:18:\"event_epass_delete\";s:0:\"\";s:18:\"raffle_draw_create\";s:0:\"\";s:18:\"raffle_draw_update\";s:0:\"\";s:18:\"raffle_draw_delete\";s:0:\"\";s:12:\"asset_create\";s:0:\"\";s:12:\"asset_update\";s:0:\"\";s:12:\"asset_delete\";s:0:\"\";s:21:\"asset_category_create\";s:0:\"\";s:21:\"asset_category_update\";s:0:\"\";s:21:\"asset_category_delete\";s:0:\"\";s:15:\"location_create\";s:0:\"\";s:15:\"location_update\";s:0:\"\";s:15:\"location_delete\";s:0:\"\";s:13:\"vendor_create\";s:0:\"\";s:13:\"vendor_update\";s:0:\"\";s:13:\"vendor_delete\";s:0:\"\";s:12:\"brand_create\";s:0:\"\";s:12:\"brand_update\";s:0:\"\";s:12:\"brand_delete\";s:0:\"\";s:11:\"page_create\";s:0:\"\";s:11:\"page_update\";s:0:\"\";s:11:\"page_delete\";s:0:\"\";s:11:\"help_create\";s:0:\"\";s:11:\"help_update\";s:0:\"\";s:11:\"help_delete\";s:0:\"\";s:20:\"help_category_create\";s:0:\"\";s:20:\"help_category_update\";s:0:\"\";s:20:\"help_category_delete\";s:0:\"\";s:21:\"knowledge_base_create\";s:0:\"\";s:21:\"knowledge_base_update\";s:0:\"\";s:21:\"knowledge_base_delete\";s:0:\"\";s:30:\"knowledge_base_category_create\";s:0:\"\";s:30:\"knowledge_base_category_update\";s:0:\"\";s:30:\"knowledge_base_category_delete\";s:0:\"\";}', 0),
(5, 'Regular Employee', 'a:54:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"estimate\";N;s:7:\"expense\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";s:0:\"\";s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";N;s:19:\"can_create_projects\";N;s:17:\"can_edit_projects\";N;s:19:\"can_delete_projects\";N;s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:37:\"can_update_only_assigned_tasks_status\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:17:\"can_use_biometric\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:21:\"disable_event_sharing\";s:1:\"1\";s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";N;s:18:\"message_permission\";s:0:\"\";s:27:\"message_permission_specific\";s:0:\"\";s:10:\"module_hrs\";N;s:17:\"hrs_employee_view\";N;s:19:\"hrs_employee_invite\";N;s:16:\"hrs_employee_add\";N;s:17:\"hrs_employee_edit\";N;s:19:\"hrs_employee_delete\";N;s:10:\"module_fas\";N;s:10:\"module_mes\";N;s:10:\"module_mcs\";N;s:10:\"module_lds\";N;s:10:\"module_sms\";N;s:10:\"module_ams\";N;s:10:\"module_pms\";N;s:10:\"module_css\";N;}', 0),
(6, 'ACCTG Staff', 'a:233:{s:21:\"disable_event_sharing\";s:0:\"\";s:12:\"announcement\";s:0:\"\";s:18:\"message_permission\";s:0:\"\";s:27:\"message_permission_specific\";s:0:\"\";s:11:\"access_logs\";s:0:\"\";s:5:\"staff\";s:3:\"all\";s:14:\"staff_specific\";s:0:\"\";s:12:\"staff_invite\";s:0:\"\";s:13:\"staff_account\";s:1:\"1\";s:21:\"staff_update_schedule\";s:1:\"1\";s:30:\"staff_view_personal_background\";s:0:\"\";s:26:\"staff_view_job_description\";s:1:\"1\";s:20:\"staff_view_bank_info\";s:1:\"1\";s:31:\"staff_view_contribution_details\";s:1:\"1\";s:34:\"can_view_team_members_contact_info\";s:1:\"1\";s:34:\"can_view_team_members_social_links\";s:1:\"1\";s:10:\"department\";s:3:\"all\";s:19:\"department_specific\";s:0:\"\";s:10:\"attendance\";s:3:\"all\";s:19:\"attendance_specific\";s:0:\"\";s:5:\"leave\";s:0:\"\";s:14:\"leave_specific\";s:0:\"\";s:7:\"holiday\";s:0:\"\";s:11:\"deciplinary\";s:0:\"\";s:17:\"can_use_biometric\";s:1:\"1\";s:8:\"schedule\";s:0:\"\";s:9:\"warehouse\";s:0:\"\";s:9:\"inventory\";s:0:\"\";s:8:\"purchase\";s:0:\"\";s:6:\"return\";s:0:\"\";s:8:\"supplier\";s:0:\"\";s:10:\"production\";s:0:\"\";s:14:\"billofmaterial\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:8:\"delivery\";s:0:\"\";s:13:\"item_transfer\";s:0:\"\";s:7:\"vehicle\";s:0:\"\";s:6:\"driver\";s:0:\"\";s:18:\"accounting_summary\";s:1:\"1\";s:13:\"balance_sheet\";s:1:\"1\";s:7:\"account\";s:1:\"1\";s:8:\"transfer\";s:1:\"1\";s:7:\"payment\";s:1:\"1\";s:7:\"expense\";s:1:\"1\";s:4:\"loan\";s:1:\"1\";s:3:\"tax\";s:1:\"1\";s:13:\"can_use_payhp\";s:1:\"1\";s:7:\"payroll\";s:1:\"1\";s:25:\"payroll_auto_contribution\";s:1:\"1\";s:22:\"compensation_tax_table\";s:1:\"1\";s:13:\"sales_summary\";s:0:\"\";s:7:\"invoice\";s:0:\"\";s:7:\"service\";s:0:\"\";s:7:\"product\";s:0:\"\";s:6:\"client\";s:0:\"\";s:5:\"store\";s:0:\"\";s:4:\"lead\";s:0:\"\";s:8:\"estimate\";s:0:\"\";s:16:\"estimate_request\";s:0:\"\";s:11:\"event_epass\";s:0:\"\";s:11:\"raffle_draw\";s:0:\"\";s:5:\"asset\";s:0:\"\";s:8:\"location\";s:0:\"\";s:6:\"vendor\";s:0:\"\";s:5:\"brand\";s:0:\"\";s:23:\"can_manage_all_projects\";s:0:\"\";s:19:\"can_create_projects\";s:0:\"\";s:17:\"can_edit_projects\";s:0:\"\";s:19:\"can_delete_projects\";s:0:\"\";s:30:\"can_add_remove_project_members\";s:1:\"1\";s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";s:1:\"1\";s:37:\"can_update_only_assigned_tasks_status\";s:0:\"\";s:20:\"can_comment_on_tasks\";s:1:\"1\";s:24:\"show_assigned_tasks_only\";s:0:\"\";s:21:\"can_create_milestones\";s:1:\"1\";s:19:\"can_edit_milestones\";s:1:\"1\";s:21:\"can_delete_milestones\";s:1:\"1\";s:27:\"timesheet_manage_permission\";s:3:\"all\";s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:16:\"can_delete_files\";s:1:\"1\";s:6:\"ticket\";s:3:\"all\";s:15:\"ticket_specific\";s:0:\"\";s:12:\"ticket_staff\";s:0:\"\";s:21:\"ticket_staff_specific\";s:0:\"\";s:4:\"page\";s:0:\"\";s:4:\"help\";s:0:\"\";s:14:\"knowledge_base\";s:0:\"\";s:19:\"announcement_create\";s:0:\"\";s:19:\"announcement_update\";s:0:\"\";s:19:\"announcement_delete\";s:0:\"\";s:18:\"access_logs_create\";s:0:\"\";s:18:\"access_logs_update\";s:0:\"\";s:18:\"access_logs_delete\";s:0:\"\";s:12:\"staff_create\";s:0:\"\";s:12:\"staff_update\";s:0:\"\";s:12:\"staff_delete\";s:0:\"\";s:17:\"department_create\";s:0:\"\";s:17:\"department_update\";s:0:\"\";s:17:\"department_delete\";s:0:\"\";s:17:\"attendance_create\";s:0:\"\";s:17:\"attendance_update\";s:0:\"\";s:17:\"attendance_delete\";s:0:\"\";s:12:\"leave_create\";s:0:\"\";s:12:\"leave_update\";s:0:\"\";s:12:\"leave_delete\";s:0:\"\";s:14:\"holiday_create\";s:0:\"\";s:14:\"holiday_update\";s:0:\"\";s:14:\"holiday_delete\";s:0:\"\";s:18:\"deciplinary_create\";s:0:\"\";s:18:\"deciplinary_update\";s:0:\"\";s:18:\"deciplinary_delete\";s:0:\"\";s:15:\"schedule_create\";s:0:\"\";s:15:\"schedule_update\";s:0:\"\";s:15:\"schedule_delete\";s:0:\"\";s:16:\"warehouse_create\";s:0:\"\";s:16:\"warehouse_update\";s:0:\"\";s:16:\"warehouse_delete\";s:0:\"\";s:16:\"inventory_create\";s:0:\"\";s:16:\"inventory_update\";s:0:\"\";s:16:\"inventory_delete\";s:0:\"\";s:15:\"purchase_create\";s:0:\"\";s:15:\"purchase_update\";s:0:\"\";s:15:\"purchase_delete\";s:0:\"\";s:13:\"return_create\";s:0:\"\";s:13:\"return_update\";s:0:\"\";s:13:\"return_delete\";s:0:\"\";s:15:\"supplier_create\";s:0:\"\";s:15:\"supplier_update\";s:0:\"\";s:15:\"supplier_delete\";s:0:\"\";s:17:\"production_create\";s:0:\"\";s:17:\"production_update\";s:0:\"\";s:17:\"production_delete\";s:0:\"\";s:21:\"billofmaterial_create\";s:0:\"\";s:21:\"billofmaterial_update\";s:0:\"\";s:21:\"billofmaterial_delete\";s:0:\"\";s:11:\"unit_create\";s:0:\"\";s:11:\"unit_update\";s:0:\"\";s:11:\"unit_delete\";s:0:\"\";s:15:\"delivery_create\";s:0:\"\";s:15:\"delivery_update\";s:0:\"\";s:15:\"delivery_delete\";s:0:\"\";s:20:\"item_transfer_create\";s:0:\"\";s:20:\"item_transfer_update\";s:0:\"\";s:20:\"item_transfer_delete\";s:0:\"\";s:14:\"vehicle_create\";s:0:\"\";s:14:\"vehicle_update\";s:0:\"\";s:14:\"vehicle_delete\";s:0:\"\";s:13:\"driver_create\";s:0:\"\";s:13:\"driver_update\";s:0:\"\";s:13:\"driver_delete\";s:0:\"\";s:14:\"account_create\";s:1:\"1\";s:14:\"account_update\";s:1:\"1\";s:14:\"account_delete\";s:1:\"1\";s:15:\"transfer_create\";s:1:\"1\";s:15:\"transfer_update\";s:1:\"1\";s:15:\"transfer_delete\";s:1:\"1\";s:14:\"payment_create\";s:1:\"1\";s:14:\"payment_update\";s:1:\"1\";s:14:\"payment_delete\";s:1:\"1\";s:14:\"expense_create\";s:1:\"1\";s:14:\"expense_update\";s:1:\"1\";s:14:\"expense_delete\";s:1:\"1\";s:11:\"loan_create\";s:1:\"1\";s:11:\"loan_update\";s:1:\"1\";s:11:\"loan_delete\";s:1:\"1\";s:10:\"tax_create\";s:1:\"1\";s:10:\"tax_update\";s:1:\"1\";s:10:\"tax_delete\";s:1:\"1\";s:14:\"payroll_create\";s:1:\"1\";s:14:\"payroll_update\";s:1:\"1\";s:14:\"payroll_delete\";s:1:\"1\";s:14:\"invoice_create\";s:0:\"\";s:14:\"invoice_update\";s:0:\"\";s:14:\"invoice_delete\";s:0:\"\";s:14:\"service_create\";s:0:\"\";s:14:\"service_update\";s:0:\"\";s:14:\"service_delete\";s:0:\"\";s:14:\"product_create\";s:0:\"\";s:14:\"product_update\";s:0:\"\";s:14:\"product_delete\";s:0:\"\";s:13:\"client_create\";s:0:\"\";s:13:\"client_update\";s:0:\"\";s:13:\"client_delete\";s:0:\"\";s:12:\"store_create\";s:0:\"\";s:12:\"store_update\";s:0:\"\";s:12:\"store_delete\";s:0:\"\";s:11:\"lead_create\";s:0:\"\";s:11:\"lead_update\";s:0:\"\";s:11:\"lead_delete\";s:0:\"\";s:15:\"estimate_create\";s:0:\"\";s:15:\"estimate_update\";s:0:\"\";s:15:\"estimate_delete\";s:0:\"\";s:23:\"estimate_request_create\";s:0:\"\";s:23:\"estimate_request_update\";s:0:\"\";s:23:\"estimate_request_delete\";s:0:\"\";s:18:\"event_epass_create\";s:0:\"\";s:18:\"event_epass_update\";s:0:\"\";s:18:\"event_epass_delete\";s:0:\"\";s:18:\"raffle_draw_create\";s:0:\"\";s:18:\"raffle_draw_update\";s:0:\"\";s:18:\"raffle_draw_delete\";s:0:\"\";s:12:\"asset_create\";s:0:\"\";s:12:\"asset_update\";s:0:\"\";s:12:\"asset_delete\";s:0:\"\";s:21:\"asset_category_create\";s:0:\"\";s:21:\"asset_category_update\";s:0:\"\";s:21:\"asset_category_delete\";s:0:\"\";s:15:\"location_create\";s:0:\"\";s:15:\"location_update\";s:0:\"\";s:15:\"location_delete\";s:0:\"\";s:13:\"vendor_create\";s:0:\"\";s:13:\"vendor_update\";s:0:\"\";s:13:\"vendor_delete\";s:0:\"\";s:12:\"brand_create\";s:0:\"\";s:12:\"brand_update\";s:0:\"\";s:12:\"brand_delete\";s:0:\"\";s:11:\"page_create\";s:0:\"\";s:11:\"page_update\";s:0:\"\";s:11:\"page_delete\";s:0:\"\";s:11:\"help_create\";s:0:\"\";s:11:\"help_update\";s:0:\"\";s:11:\"help_delete\";s:0:\"\";s:20:\"help_category_create\";s:0:\"\";s:20:\"help_category_update\";s:0:\"\";s:20:\"help_category_delete\";s:0:\"\";s:21:\"knowledge_base_create\";s:0:\"\";s:21:\"knowledge_base_update\";s:0:\"\";s:21:\"knowledge_base_delete\";s:0:\"\";s:30:\"knowledge_base_category_create\";s:0:\"\";s:30:\"knowledge_base_category_update\";s:0:\"\";s:30:\"knowledge_base_category_delete\";s:0:\"\";}', 0),
(7, 'Maintenance', NULL, 0),
(8, 'Executives', 'a:225:{s:21:\"disable_event_sharing\";s:0:\"\";s:12:\"announcement\";s:1:\"1\";s:18:\"message_permission\";s:3:\"all\";s:27:\"message_permission_specific\";s:0:\"\";s:11:\"access_logs\";s:1:\"1\";s:5:\"staff\";s:3:\"all\";s:14:\"staff_specific\";s:0:\"\";s:12:\"staff_invite\";s:1:\"1\";s:13:\"staff_account\";s:0:\"\";s:30:\"staff_view_personal_background\";s:1:\"1\";s:26:\"staff_view_job_description\";s:1:\"1\";s:20:\"staff_view_bank_info\";s:0:\"\";s:31:\"staff_view_contribution_details\";s:1:\"1\";s:34:\"can_view_team_members_contact_info\";s:1:\"1\";s:34:\"can_view_team_members_social_links\";s:1:\"1\";s:10:\"department\";s:3:\"all\";s:19:\"department_specific\";s:0:\"\";s:10:\"attendance\";s:3:\"all\";s:19:\"attendance_specific\";s:0:\"\";s:5:\"leave\";s:3:\"all\";s:14:\"leave_specific\";s:0:\"\";s:7:\"holiday\";s:0:\"\";s:11:\"deciplinary\";s:0:\"\";s:17:\"can_use_biometric\";s:0:\"\";s:9:\"warehouse\";s:0:\"\";s:9:\"inventory\";s:0:\"\";s:8:\"purchase\";s:1:\"1\";s:6:\"return\";s:1:\"1\";s:8:\"supplier\";s:1:\"1\";s:10:\"production\";s:0:\"\";s:14:\"billofmaterial\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:8:\"delivery\";s:0:\"\";s:13:\"item_transfer\";s:0:\"\";s:7:\"vehicle\";s:0:\"\";s:6:\"driver\";s:0:\"\";s:18:\"accounting_summary\";s:0:\"\";s:13:\"balance_sheet\";s:0:\"\";s:7:\"account\";s:0:\"\";s:8:\"transfer\";s:0:\"\";s:7:\"payment\";s:0:\"\";s:7:\"expense\";s:0:\"\";s:4:\"loan\";s:1:\"1\";s:3:\"tax\";s:1:\"1\";s:13:\"can_use_payhp\";s:1:\"1\";s:7:\"payroll\";s:1:\"1\";s:25:\"payroll_auto_contribution\";s:1:\"1\";s:22:\"compensation_tax_table\";s:1:\"1\";s:13:\"sales_summary\";s:0:\"\";s:7:\"invoice\";s:0:\"\";s:7:\"service\";s:0:\"\";s:7:\"product\";s:0:\"\";s:6:\"client\";s:0:\"\";s:5:\"store\";s:0:\"\";s:4:\"lead\";s:0:\"\";s:8:\"estimate\";s:0:\"\";s:16:\"estimate_request\";s:0:\"\";s:11:\"event_epass\";s:0:\"\";s:11:\"raffle_draw\";s:0:\"\";s:5:\"asset\";s:0:\"\";s:8:\"location\";s:0:\"\";s:6:\"vendor\";s:0:\"\";s:5:\"brand\";s:0:\"\";s:23:\"can_manage_all_projects\";s:1:\"1\";s:19:\"can_create_projects\";s:1:\"1\";s:17:\"can_edit_projects\";s:1:\"1\";s:19:\"can_delete_projects\";s:1:\"1\";s:30:\"can_add_remove_project_members\";s:1:\"1\";s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";s:1:\"1\";s:37:\"can_update_only_assigned_tasks_status\";s:0:\"\";s:20:\"can_comment_on_tasks\";s:1:\"1\";s:24:\"show_assigned_tasks_only\";s:0:\"\";s:21:\"can_create_milestones\";s:1:\"1\";s:19:\"can_edit_milestones\";s:1:\"1\";s:21:\"can_delete_milestones\";s:1:\"1\";s:27:\"timesheet_manage_permission\";s:3:\"all\";s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:16:\"can_delete_files\";s:1:\"1\";s:6:\"ticket\";s:3:\"all\";s:15:\"ticket_specific\";s:0:\"\";s:12:\"ticket_staff\";s:0:\"\";s:21:\"ticket_staff_specific\";s:0:\"\";s:4:\"page\";s:1:\"1\";s:4:\"help\";s:1:\"1\";s:14:\"knowledge_base\";s:1:\"1\";s:18:\"access_logs_create\";s:0:\"\";s:18:\"access_logs_update\";s:0:\"\";s:18:\"access_logs_delete\";s:0:\"\";s:12:\"staff_create\";s:1:\"1\";s:12:\"staff_update\";s:1:\"1\";s:12:\"staff_delete\";s:1:\"1\";s:17:\"department_create\";s:0:\"\";s:17:\"department_update\";s:0:\"\";s:17:\"department_delete\";s:0:\"\";s:17:\"attendance_create\";s:1:\"1\";s:17:\"attendance_update\";s:1:\"1\";s:17:\"attendance_delete\";s:1:\"1\";s:12:\"leave_create\";s:0:\"\";s:12:\"leave_update\";s:0:\"\";s:12:\"leave_delete\";s:0:\"\";s:14:\"holiday_create\";s:0:\"\";s:14:\"holiday_update\";s:0:\"\";s:14:\"holiday_delete\";s:0:\"\";s:18:\"deciplinary_create\";s:0:\"\";s:18:\"deciplinary_update\";s:0:\"\";s:18:\"deciplinary_delete\";s:0:\"\";s:16:\"warehouse_create\";s:0:\"\";s:16:\"warehouse_update\";s:0:\"\";s:16:\"warehouse_delete\";s:0:\"\";s:16:\"inventory_create\";s:0:\"\";s:16:\"inventory_update\";s:0:\"\";s:16:\"inventory_delete\";s:0:\"\";s:15:\"purchase_create\";s:0:\"\";s:15:\"purchase_update\";s:0:\"\";s:15:\"purchase_delete\";s:0:\"\";s:13:\"return_create\";s:0:\"\";s:13:\"return_update\";s:0:\"\";s:13:\"return_delete\";s:0:\"\";s:15:\"supplier_create\";s:0:\"\";s:15:\"supplier_update\";s:0:\"\";s:15:\"supplier_delete\";s:0:\"\";s:17:\"production_create\";s:0:\"\";s:17:\"production_update\";s:0:\"\";s:17:\"production_delete\";s:0:\"\";s:21:\"billofmaterial_create\";s:0:\"\";s:21:\"billofmaterial_update\";s:0:\"\";s:21:\"billofmaterial_delete\";s:0:\"\";s:11:\"unit_create\";s:0:\"\";s:11:\"unit_update\";s:0:\"\";s:11:\"unit_delete\";s:0:\"\";s:15:\"delivery_create\";s:0:\"\";s:15:\"delivery_update\";s:0:\"\";s:15:\"delivery_delete\";s:0:\"\";s:20:\"item_transfer_create\";s:0:\"\";s:20:\"item_transfer_update\";s:0:\"\";s:20:\"item_transfer_delete\";s:0:\"\";s:14:\"vehicle_create\";s:0:\"\";s:14:\"vehicle_update\";s:0:\"\";s:14:\"vehicle_delete\";s:0:\"\";s:13:\"driver_create\";s:0:\"\";s:13:\"driver_update\";s:0:\"\";s:13:\"driver_delete\";s:0:\"\";s:14:\"account_create\";s:0:\"\";s:14:\"account_update\";s:0:\"\";s:14:\"account_delete\";s:0:\"\";s:15:\"transfer_create\";s:0:\"\";s:15:\"transfer_update\";s:0:\"\";s:15:\"transfer_delete\";s:0:\"\";s:14:\"payment_create\";s:0:\"\";s:14:\"payment_update\";s:0:\"\";s:14:\"payment_delete\";s:0:\"\";s:14:\"expense_create\";s:0:\"\";s:14:\"expense_update\";s:0:\"\";s:14:\"expense_delete\";s:0:\"\";s:11:\"loan_create\";s:1:\"1\";s:11:\"loan_update\";s:1:\"1\";s:11:\"loan_delete\";s:1:\"1\";s:10:\"tax_create\";s:1:\"1\";s:10:\"tax_update\";s:1:\"1\";s:10:\"tax_delete\";s:1:\"1\";s:14:\"payroll_create\";s:1:\"1\";s:14:\"payroll_update\";s:1:\"1\";s:14:\"payroll_delete\";s:1:\"1\";s:14:\"invoice_create\";s:0:\"\";s:14:\"invoice_update\";s:0:\"\";s:14:\"invoice_delete\";s:0:\"\";s:14:\"service_create\";s:0:\"\";s:14:\"service_update\";s:0:\"\";s:14:\"service_delete\";s:0:\"\";s:14:\"product_create\";s:0:\"\";s:14:\"product_update\";s:0:\"\";s:14:\"product_delete\";s:0:\"\";s:13:\"client_create\";s:0:\"\";s:13:\"client_update\";s:0:\"\";s:13:\"client_delete\";s:0:\"\";s:12:\"store_create\";s:0:\"\";s:12:\"store_update\";s:0:\"\";s:12:\"store_delete\";s:0:\"\";s:11:\"lead_create\";s:0:\"\";s:11:\"lead_update\";s:0:\"\";s:11:\"lead_delete\";s:0:\"\";s:15:\"estimate_create\";s:0:\"\";s:15:\"estimate_update\";s:0:\"\";s:15:\"estimate_delete\";s:0:\"\";s:23:\"estimate_request_create\";s:0:\"\";s:23:\"estimate_request_update\";s:0:\"\";s:23:\"estimate_request_delete\";s:0:\"\";s:18:\"event_epass_create\";s:0:\"\";s:18:\"event_epass_update\";s:0:\"\";s:18:\"event_epass_delete\";s:0:\"\";s:18:\"raffle_draw_create\";s:0:\"\";s:18:\"raffle_draw_update\";s:0:\"\";s:18:\"raffle_draw_delete\";s:0:\"\";s:12:\"asset_create\";s:0:\"\";s:12:\"asset_update\";s:0:\"\";s:12:\"asset_delete\";s:0:\"\";s:21:\"asset_category_create\";s:0:\"\";s:21:\"asset_category_update\";s:0:\"\";s:21:\"asset_category_delete\";s:0:\"\";s:15:\"location_create\";s:0:\"\";s:15:\"location_update\";s:0:\"\";s:15:\"location_delete\";s:0:\"\";s:13:\"vendor_create\";s:0:\"\";s:13:\"vendor_update\";s:0:\"\";s:13:\"vendor_delete\";s:0:\"\";s:12:\"brand_create\";s:0:\"\";s:12:\"brand_update\";s:0:\"\";s:12:\"brand_delete\";s:0:\"\";s:11:\"page_create\";s:1:\"1\";s:11:\"page_update\";s:1:\"1\";s:11:\"page_delete\";s:1:\"1\";s:11:\"help_create\";s:1:\"1\";s:11:\"help_update\";s:1:\"1\";s:11:\"help_delete\";s:1:\"1\";s:20:\"help_category_create\";s:1:\"1\";s:20:\"help_category_update\";s:1:\"1\";s:20:\"help_category_delete\";s:1:\"1\";s:21:\"knowledge_base_create\";s:1:\"1\";s:21:\"knowledge_base_update\";s:1:\"1\";s:21:\"knowledge_base_delete\";s:1:\"1\";s:30:\"knowledge_base_category_create\";s:1:\"1\";s:30:\"knowledge_base_category_update\";s:1:\"1\";s:30:\"knowledge_base_category_delete\";s:1:\"1\";}', 0);

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT '',
  `desc` text DEFAULT NULL,
  `mon` text DEFAULT NULL,
  `tue` text DEFAULT NULL,
  `wed` text DEFAULT NULL,
  `thu` text DEFAULT NULL,
  `fri` text DEFAULT NULL,
  `sat` text DEFAULT NULL,
  `sun` text DEFAULT NULL,
  `created_by` int(11) DEFAULT 0,
  `date_created` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `title`, `desc`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`, `created_by`, `date_created`, `deleted`) VALUES
(170, '5AM - 2PM 60 mins lunch', '<p>60 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:7:\"5:00 AM\";s:3:\"out\";s:7:\"2:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"9:00 AM\";s:9:\"out_lunch\";s:8:\"10:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"5:00 AM\";s:3:\"out\";s:7:\"2:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"9:00 AM\";s:9:\"out_lunch\";s:8:\"10:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"5:00 AM\";s:3:\"out\";s:7:\"2:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"9:00 AM\";s:9:\"out_lunch\";s:8:\"10:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"5:00 AM\";s:3:\"out\";s:7:\"2:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"9:00 AM\";s:9:\"out_lunch\";s:8:\"10:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"5:00 AM\";s:3:\"out\";s:7:\"2:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"9:00 AM\";s:9:\"out_lunch\";s:8:\"10:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 17:27:54', 0),
(173, '7:30 AM - 4:00 PM 30 mins lunch', '<p>30 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:7:\"7:30 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"11:30 AM\";s:9:\"out_lunch\";s:8:\"12:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:30 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"11:30 AM\";s:9:\"out_lunch\";s:8:\"12:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:30 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"11:30 AM\";s:9:\"out_lunch\";s:8:\"12:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:30 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"11:30 AM\";s:9:\"out_lunch\";s:8:\"12:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:30 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"11:30 AM\";s:9:\"out_lunch\";s:8:\"12:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 17:49:58', 0),
(175, '8PM - 5AM 60 mins lunch', '<p>60 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:7:\"8:00 PM\";s:3:\"out\";s:7:\"5:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 AM\";s:9:\"out_lunch\";s:7:\"1:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 PM\";s:3:\"out\";s:7:\"5:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 AM\";s:9:\"out_lunch\";s:7:\"1:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 PM\";s:3:\"out\";s:7:\"5:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 AM\";s:9:\"out_lunch\";s:7:\"1:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 PM\";s:3:\"out\";s:7:\"5:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 AM\";s:9:\"out_lunch\";s:7:\"1:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 PM\";s:3:\"out\";s:7:\"5:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 AM\";s:9:\"out_lunch\";s:7:\"1:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 17:55:19', 0),
(192, '6AM-2:30PM 30 mins lunch', '<p>30 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:7:\"6:00 AM\";s:3:\"out\";s:7:\"2:30 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"10:00 AM\";s:9:\"out_lunch\";s:8:\"10:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"6:00 AM\";s:3:\"out\";s:7:\"2:30 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"10:00 AM\";s:9:\"out_lunch\";s:8:\"10:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"6:00 AM\";s:3:\"out\";s:7:\"2:30 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"10:00 AM\";s:9:\"out_lunch\";s:8:\"10:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"6:00 AM\";s:3:\"out\";s:7:\"2:30 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"10:00 AM\";s:9:\"out_lunch\";s:8:\"10:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"6:00 AM\";s:3:\"out\";s:7:\"2:30 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"10:00 AM\";s:9:\"out_lunch\";s:8:\"10:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 18:28:02', 0),
(193, '9:30PM - 6AM 30 mins lunch', '<p>30 mins lunch</p>', 'a:11:{s:2:\"in\";s:7:\"9:30 PM\";s:3:\"out\";s:7:\"6:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"1:30 AM\";s:9:\"out_lunch\";s:7:\"2:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"9:30 PM\";s:3:\"out\";s:7:\"6:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"1:30 AM\";s:9:\"out_lunch\";s:7:\"2:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"9:30 PM\";s:3:\"out\";s:7:\"6:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"1:30 AM\";s:9:\"out_lunch\";s:7:\"2:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"9:30 PM\";s:3:\"out\";s:7:\"6:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"1:30 AM\";s:9:\"out_lunch\";s:7:\"2:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"9:30 PM\";s:3:\"out\";s:7:\"6:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"1:30 AM\";s:9:\"out_lunch\";s:7:\"2:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 18:28:39', 0),
(194, '9PM - 5:30AM 30 mins lunch', '<p>30m lunch</p>', 'a:11:{s:2:\"in\";s:7:\"9:00 PM\";s:3:\"out\";s:7:\"5:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"1:00 AM\";s:9:\"out_lunch\";s:7:\"1:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"9:00 PM\";s:3:\"out\";s:7:\"5:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"1:00 AM\";s:9:\"out_lunch\";s:7:\"1:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"9:00 PM\";s:3:\"out\";s:7:\"5:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"1:00 AM\";s:9:\"out_lunch\";s:7:\"1:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"9:00 PM\";s:3:\"out\";s:7:\"5:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"1:00 AM\";s:9:\"out_lunch\";s:7:\"1:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"9:00 PM\";s:3:\"out\";s:7:\"5:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"1:00 AM\";s:9:\"out_lunch\";s:7:\"1:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 18:29:22', 0),
(195, '8AM - 5PM 60 mins lunch', '<p>60 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 19:28:06', 0),
(196, '10:30PM - 7AM 30 mins lunch', '<p>30mins lunch</p>', 'a:11:{s:2:\"in\";s:8:\"10:30 PM\";s:3:\"out\";s:7:\"7:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:30 AM\";s:9:\"out_lunch\";s:7:\"3:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:30 PM\";s:3:\"out\";s:7:\"7:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:30 AM\";s:9:\"out_lunch\";s:7:\"3:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:30 PM\";s:3:\"out\";s:7:\"7:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:30 AM\";s:9:\"out_lunch\";s:7:\"3:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:30 PM\";s:3:\"out\";s:7:\"7:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:30 AM\";s:9:\"out_lunch\";s:7:\"3:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:30 PM\";s:3:\"out\";s:7:\"7:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:30 AM\";s:9:\"out_lunch\";s:7:\"3:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 19:30:34', 0),
(197, '10PM - 6:30AM 30 mins lunch', '<p>30 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:8:\"10:00 PM\";s:3:\"out\";s:7:\"6:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:00 AM\";s:9:\"out_lunch\";s:7:\"2:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:00 PM\";s:3:\"out\";s:7:\"6:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:00 AM\";s:9:\"out_lunch\";s:7:\"2:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:00 PM\";s:3:\"out\";s:7:\"6:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:00 AM\";s:9:\"out_lunch\";s:7:\"2:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:00 PM\";s:3:\"out\";s:7:\"6:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:00 AM\";s:9:\"out_lunch\";s:7:\"2:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:00 PM\";s:3:\"out\";s:7:\"6:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:00 AM\";s:9:\"out_lunch\";s:7:\"2:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 19:31:31', 0),
(198, '10PM - 7AM 60 mins lunch', '<p>60 mins lunch</p>', 'a:11:{s:2:\"in\";s:8:\"10:00 PM\";s:3:\"out\";s:7:\"7:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:00 AM\";s:9:\"out_lunch\";s:7:\"3:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:00 PM\";s:3:\"out\";s:7:\"7:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:00 AM\";s:9:\"out_lunch\";s:7:\"3:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:00 PM\";s:3:\"out\";s:7:\"7:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:00 AM\";s:9:\"out_lunch\";s:7:\"3:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:00 PM\";s:3:\"out\";s:7:\"7:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:00 AM\";s:9:\"out_lunch\";s:7:\"3:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"10:00 PM\";s:3:\"out\";s:7:\"7:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"2:00 AM\";s:9:\"out_lunch\";s:7:\"3:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 19:32:32', 0),
(199, '11:30PM - 8AM 30 mins lunch', '<p>30 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:8:\"11:30 PM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:30 AM\";s:9:\"out_lunch\";s:7:\"4:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:30 PM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:30 AM\";s:9:\"out_lunch\";s:7:\"4:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:30 PM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:30 AM\";s:9:\"out_lunch\";s:7:\"4:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:30 PM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:30 AM\";s:9:\"out_lunch\";s:7:\"4:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:30 PM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:30 AM\";s:9:\"out_lunch\";s:7:\"4:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 19:34:17', 0),
(202, '11PM - 8AM 60 mins lunch', '<p>60 mins lunch</p>', 'a:11:{s:2:\"in\";s:8:\"11:00 PM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:00 AM\";s:9:\"out_lunch\";s:7:\"4:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:00 PM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:00 AM\";s:9:\"out_lunch\";s:7:\"4:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:00 PM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:00 AM\";s:9:\"out_lunch\";s:7:\"4:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:00 PM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:00 AM\";s:9:\"out_lunch\";s:7:\"4:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:00 PM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:00 AM\";s:9:\"out_lunch\";s:7:\"4:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 19:38:45', 0),
(204, '4PM - 12:30AM 30 mins lunch', '<p>30m Lunch Break</p>', 'a:11:{s:2:\"in\";s:7:\"4:00 PM\";s:3:\"out\";s:8:\"12:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"8:00 PM\";s:9:\"out_lunch\";s:7:\"8:30 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"4:00 PM\";s:3:\"out\";s:8:\"12:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"8:00 PM\";s:9:\"out_lunch\";s:7:\"8:30 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"4:00 PM\";s:3:\"out\";s:8:\"12:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"8:00 PM\";s:9:\"out_lunch\";s:7:\"8:30 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"4:00 PM\";s:3:\"out\";s:8:\"12:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"8:00 PM\";s:9:\"out_lunch\";s:7:\"8:30 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"4:00 PM\";s:3:\"out\";s:8:\"12:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"8:00 PM\";s:9:\"out_lunch\";s:7:\"8:30 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 19:41:11', 0),
(205, '4PM - 1AM 60 mins lunch', '<p>60 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:7:\"4:00 PM\";s:3:\"out\";s:7:\"1:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"8:00 PM\";s:9:\"out_lunch\";s:7:\"9:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"4:00 PM\";s:3:\"out\";s:7:\"1:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"8:00 PM\";s:9:\"out_lunch\";s:7:\"9:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"4:00 PM\";s:3:\"out\";s:7:\"1:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"8:00 PM\";s:9:\"out_lunch\";s:7:\"9:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"4:00 PM\";s:3:\"out\";s:7:\"1:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"8:00 PM\";s:9:\"out_lunch\";s:7:\"9:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"4:00 PM\";s:3:\"out\";s:7:\"1:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"8:00 PM\";s:9:\"out_lunch\";s:7:\"9:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 19:42:13', 0),
(207, '8:30PM - 5:30AM 60 mins lunch', '<p>60 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:7:\"8:30 PM\";s:3:\"out\";s:7:\"5:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:30 AM\";s:9:\"out_lunch\";s:7:\"1:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:30 PM\";s:3:\"out\";s:7:\"5:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:30 AM\";s:9:\"out_lunch\";s:7:\"1:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:30 PM\";s:3:\"out\";s:7:\"5:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:30 AM\";s:9:\"out_lunch\";s:7:\"1:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:30 PM\";s:3:\"out\";s:7:\"5:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:30 AM\";s:9:\"out_lunch\";s:7:\"1:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:30 PM\";s:3:\"out\";s:7:\"5:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:30 AM\";s:9:\"out_lunch\";s:7:\"1:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-06-28 19:51:36', 0),
(211, '7AM - 4PM 60 mins lunch T - S', '<p>60 mins lunch<br></p>', NULL, 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"11:00 AM\";s:9:\"out_lunch\";s:8:\"12:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"11:00 AM\";s:9:\"out_lunch\";s:8:\"12:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"11:00 AM\";s:9:\"out_lunch\";s:8:\"12:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"11:00 AM\";s:9:\"out_lunch\";s:8:\"12:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"11:00 AM\";s:9:\"out_lunch\";s:8:\"12:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, 1, '2023-06-30 20:41:58', 0),
(216, '11PM - 7:30AM 30 mins lunch', '<p>30 mins lunch</p>', 'a:11:{s:2:\"in\";s:8:\"11:00 PM\";s:3:\"out\";s:7:\"7:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:00 AM\";s:9:\"out_lunch\";s:7:\"3:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:00 PM\";s:3:\"out\";s:7:\"7:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:00 AM\";s:9:\"out_lunch\";s:7:\"3:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:00 PM\";s:3:\"out\";s:7:\"7:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:00 AM\";s:9:\"out_lunch\";s:7:\"3:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:00 PM\";s:3:\"out\";s:7:\"7:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:00 AM\";s:9:\"out_lunch\";s:7:\"3:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"11:00 PM\";s:3:\"out\";s:7:\"7:30 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"3:00 AM\";s:9:\"out_lunch\";s:7:\"3:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-07-03 19:57:43', 0),
(218, '12AM - 8AM - 60 mins lunch', '<p>60 mins lunch</p>', 'a:11:{s:2:\"in\";s:8:\"12:00 AM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"4:00 AM\";s:9:\"out_lunch\";s:7:\"5:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"12:00 AM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"4:00 AM\";s:9:\"out_lunch\";s:7:\"5:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"12:00 AM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"4:00 AM\";s:9:\"out_lunch\";s:7:\"5:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"12:00 AM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"4:00 AM\";s:9:\"out_lunch\";s:7:\"5:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"12:00 AM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"4:00 AM\";s:9:\"out_lunch\";s:7:\"5:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', '', NULL, 1, '2023-07-03 20:32:43', 0),
(220, '12AM - 8AM 30 mins lunch', '<p>30 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:8:\"12:00 AM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"4:00 AM\";s:9:\"out_lunch\";s:7:\"4:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"12:00 AM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"4:00 AM\";s:9:\"out_lunch\";s:7:\"4:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"12:00 AM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"4:00 AM\";s:9:\"out_lunch\";s:7:\"4:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"12:00 AM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"4:00 AM\";s:9:\"out_lunch\";s:7:\"4:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:8:\"12:00 AM\";s:3:\"out\";s:7:\"8:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:7:\"4:00 AM\";s:9:\"out_lunch\";s:7:\"4:30 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', '', NULL, 1, '2023-07-03 21:00:48', 0),
(225, '8:30PM - 5:00AM 30 mins lunch', '<p>30 mins lunch<br></p>', 'a:11:{s:2:\"in\";s:7:\"8:30 PM\";s:3:\"out\";s:7:\"5:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:30 AM\";s:9:\"out_lunch\";s:7:\"1:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:30 PM\";s:3:\"out\";s:7:\"5:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:30 AM\";s:9:\"out_lunch\";s:7:\"1:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:30 PM\";s:3:\"out\";s:7:\"5:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:30 AM\";s:9:\"out_lunch\";s:7:\"1:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:30 PM\";s:3:\"out\";s:7:\"5:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:30 AM\";s:9:\"out_lunch\";s:7:\"1:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:30 PM\";s:3:\"out\";s:7:\"5:00 AM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:30 AM\";s:9:\"out_lunch\";s:7:\"1:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-07-06 15:05:28', 0),
(228, '5AM - 2PM 60m T - S', '', NULL, 'a:11:{s:2:\"in\";s:7:\"5:00 AM\";s:3:\"out\";s:7:\"2:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"10:00 AM\";s:9:\"out_lunch\";s:8:\"11:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"5:00 AM\";s:3:\"out\";s:7:\"2:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"10:00 AM\";s:9:\"out_lunch\";s:8:\"11:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"5:00 AM\";s:3:\"out\";s:7:\"2:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"10:00 AM\";s:9:\"out_lunch\";s:8:\"11:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"5:00 AM\";s:3:\"out\";s:7:\"2:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"10:00 AM\";s:9:\"out_lunch\";s:8:\"11:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"5:00 AM\";s:3:\"out\";s:7:\"2:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"10:00 AM\";s:9:\"out_lunch\";s:8:\"11:00 AM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, 1, '2023-07-08 07:09:23', 0),
(229, '7AM - 3:30PM 30m lunch', '', 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"3:30 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:8:\"12:30 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"3:30 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:8:\"12:30 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"3:30 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:8:\"12:30 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"3:30 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:8:\"12:30 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"3:30 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:8:\"12:30 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, NULL, 1, '2023-07-08 07:09:59', 0),
(230, '8AM - 5PM 60m T - S', '', NULL, 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, 1, '2023-07-08 07:13:33', 0),
(231, '7A-4P T 8A-5P WS', '', NULL, 'a:11:{s:2:\"in\";s:7:\"7:00 AM\";s:3:\"out\";s:7:\"4:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', 'a:11:{s:2:\"in\";s:7:\"8:00 AM\";s:3:\"out\";s:7:\"5:00 PM\";s:13:\"enabled_first\";N;s:8:\"in_first\";s:0:\"\";s:9:\"out_first\";s:0:\"\";s:13:\"enabled_lunch\";s:1:\"1\";s:8:\"in_lunch\";s:8:\"12:00 PM\";s:9:\"out_lunch\";s:7:\"1:00 PM\";s:14:\"enabled_second\";N;s:9:\"in_second\";s:0:\"\";s:10:\"out_second\";s:0:\"\";}', NULL, 1, '2023-07-08 07:27:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` double NOT NULL,
  `unofficial` tinyint(1) DEFAULT NULL,
  `labels` text COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `category` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services_categories`
--

CREATE TABLE `services_categories` (
  `id` bigint(10) NOT NULL,
  `category` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `setting_value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'app',
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_name`, `setting_value`, `type`, `deleted`) VALUES
('30min_break_employee', '', 'calendar', 0),
('accepted_file_formats', 'jpg,jpeg,png,bmp,doc,docx,xls,xlsx,pdf', 'display', 0),
('allow_partial_invoice_payment_from_clients', '1', 'app', 0),
('allowed_ip_addresses', '', 'app', 0),
('apis_galyon_app', '1', 'modules', 0),
('apis_galyon_web', '1', 'modules', 0),
('apis_makeid', '1', 'modules', 0),
('apis_payhp', '1', 'modules', 0),
('apis_syntry', '1', 'modules', 0),
('app_title', 'ERPat System', 'app', 0),
('attendance_calc_mode', 'simple', 'finance', 0),
('auto_clockin_employee', '', 'calendar', 0),
('auto_clockout', '1', 'calendar', 0),
('auto_close_ticket_after', '15', 'app', 0),
('autoclockout_trigger_hour', '12.00', 'calendar', 0),
('basic_pay_calculation', 'scheduled_based', 'finance', 0),
('bonuspay_trigger', '0.00', 'calendar', 0),
('breaktime_tracking', '0', 'calendar', 0),
('client_can_add_files', '', 'app', 0),
('client_can_add_project_files', '', 'app', 0),
('client_can_comment_on_files', '', 'app', 0),
('client_can_comment_on_tasks', '', 'app', 0),
('client_can_create_projects', '', 'app', 0),
('client_can_create_tasks', '', 'app', 0),
('client_can_delete_own_files_in_project', '', 'app', 0),
('client_can_edit_projects', '', 'app', 0),
('client_can_edit_tasks', '', 'app', 0),
('client_can_pay_invoice_without_login', '1', 'app', 0),
('client_can_view_activity', '', 'app', 0),
('client_can_view_files', '', 'app', 0),
('client_can_view_gantt', '', 'app', 0),
('client_can_view_milestones', '', 'app', 0),
('client_can_view_overview', '', 'app', 0),
('client_can_view_project_files', '', 'app', 0),
('client_can_view_tasks', '', 'app', 0),
('client_message_own_contacts', '', 'app', 0),
('company_address', '12flr Bytes Tower, Bry. San Francisco, Biñan, Laguna Philippines 4024', 'company', 0),
('company_email', 'info@erpat.app', 'company', 0),
('company_name', 'ABC Company Inc.', 'company', 0),
('company_phone', '+63 912 345 6789', 'company', 0),
('company_vat_number', '012 345 678 9000', 'company', 0),
('company_website', 'https://erpat.app', 'company', 0),
('create_new_projects_automatically_when_estimates_gets_accepted', '', 'app', 0),
('create_tickets_only_by_registered_emails', '1', 'app', 0),
('cron_attendances', '1', 'crons', 0),
('cron_calendars', '1', 'crons', 0),
('cron_expenses', '1', 'crons', 0),
('cron_imaps', '1', 'crons', 0),
('cron_invoices', '1', 'crons', 0),
('cron_notifications', '1', 'crons', 0),
('cron_tasks', '1', 'crons', 0),
('cron_tickets', '1', 'crons', 0),
('currency_position', 'left', 'finance', 0),
('currency_symbol', 'P', 'finance', 0),
('daily_tax_table', 'a:6:{i:0;a:5:{i:0;i:1;i:1;s:1:\"0\";i:2;s:3:\"684\";i:3;s:1:\"0\";i:4;s:1:\"0\";}i:1;a:5:{i:0;i:2;i:1;s:3:\"685\";i:2;s:4:\"1095\";i:3;s:1:\"0\";i:4;s:3:\"0.2\";}i:2;a:5:{i:0;i:3;i:1;s:4:\"1096\";i:2;s:4:\"2191\";i:3;s:5:\"82.19\";i:4;s:4:\"0.25\";}i:3;a:5:{i:0;i:4;i:1;s:4:\"2192\";i:2;s:4:\"5478\";i:3;s:6:\"356.16\";i:4;s:3:\"0.3\";}i:4;a:5:{i:0;i:5;i:1;s:4:\"5479\";i:2;s:5:\"21917\";i:3;s:7:\"1342.47\";i:4;s:4:\"0.32\";}i:5;a:5:{i:0;i:6;i:1;s:5:\"21918\";i:2;s:9:\"999999999\";i:3;s:7:\"6602.74\";i:4;s:4:\"0.35\";}}', 'payroll', 0),
('date_format', 'm-d-Y', 'calendar', 0),
('days_locked_attendance', '5', 'calendar', 0),
('days_per_year', '261', 'calendar', 0),
('decimal_separator', '.', 'finance', 0),
('default_currency', 'PHP', 'finance', 0),
('default_due_date_after_billing_date', '', 'app', 0),
('default_left_menu', 'a:75:{i:0;a:1:{s:4:\"name\";s:9:\"dashboard\";}i:1;a:4:{s:4:\"name\";s:4:\"Home\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:4:\"home\";s:15:\"open_in_new_tab\";s:0:\"\";}i:2;a:2:{s:4:\"name\";s:8:\"timeline\";s:11:\"is_sub_menu\";s:1:\"1\";}i:3;a:2:{s:4:\"name\";s:13:\"announcements\";s:11:\"is_sub_menu\";s:1:\"1\";}i:4;a:2:{s:4:\"name\";s:6:\"events\";s:11:\"is_sub_menu\";s:1:\"1\";}i:5;a:2:{s:4:\"name\";s:4:\"todo\";s:11:\"is_sub_menu\";s:1:\"1\";}i:6;a:2:{s:4:\"name\";s:5:\"notes\";s:11:\"is_sub_menu\";s:1:\"1\";}i:7;a:2:{s:4:\"name\";s:8:\"messages\";s:11:\"is_sub_menu\";s:1:\"1\";}i:8;a:4:{s:4:\"name\";s:14:\"Human Resource\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:8:\"suitcase\";s:15:\"open_in_new_tab\";s:0:\"\";}i:9;a:2:{s:4:\"name\";s:8:\"employee\";s:11:\"is_sub_menu\";s:1:\"1\";}i:10;a:2:{s:4:\"name\";s:22:\"submenu_hrm_department\";s:11:\"is_sub_menu\";s:1:\"1\";}i:11;a:2:{s:4:\"name\";s:20:\"submenu_hrm_schedule\";s:11:\"is_sub_menu\";s:1:\"1\";}i:12;a:2:{s:4:\"name\";s:22:\"submenu_hrm_attendance\";s:11:\"is_sub_menu\";s:1:\"1\";}i:13;a:2:{s:4:\"name\";s:24:\"submenu_hrm_disciplinary\";s:11:\"is_sub_menu\";s:1:\"1\";}i:14;a:2:{s:4:\"name\";s:18:\"submenu_hrm_leaves\";s:11:\"is_sub_menu\";s:1:\"1\";}i:15;a:2:{s:4:\"name\";s:20:\"submenu_hrm_holidays\";s:11:\"is_sub_menu\";s:1:\"1\";}i:16;a:4:{s:4:\"name\";s:10:\"Production\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:4:\"fire\";s:15:\"open_in_new_tab\";s:0:\"\";}i:17;a:2:{s:4:\"name\";s:19:\"manufacturing_order\";s:11:\"is_sub_menu\";s:1:\"1\";}i:18;a:2:{s:4:\"name\";s:17:\"bill_of_materials\";s:11:\"is_sub_menu\";s:1:\"1\";}i:19;a:2:{s:4:\"name\";s:17:\"submenu_pid_units\";s:11:\"is_sub_menu\";s:1:\"1\";}i:20;a:4:{s:4:\"name\";s:12:\"Distribution\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:4:\"bank\";s:15:\"open_in_new_tab\";s:0:\"\";}i:21;a:2:{s:4:\"name\";s:10:\"warehouses\";s:11:\"is_sub_menu\";s:1:\"1\";}i:22;a:2:{s:4:\"name\";s:19:\"submenu_lms_pallets\";s:11:\"is_sub_menu\";s:1:\"1\";}i:23;a:4:{s:4:\"name\";s:7:\"Finance\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:13:\"cc-mastercard\";s:15:\"open_in_new_tab\";s:0:\"\";}i:24;a:2:{s:4:\"name\";s:8:\"payrolls\";s:11:\"is_sub_menu\";s:1:\"1\";}i:25;a:2:{s:4:\"name\";s:19:\"submenu_fas_summary\";s:11:\"is_sub_menu\";s:1:\"1\";}i:26;a:2:{s:4:\"name\";s:19:\"submenu_fas_payroll\";s:11:\"is_sub_menu\";s:1:\"1\";}i:27;a:2:{s:4:\"name\";s:20:\"submenu_fas_payments\";s:11:\"is_sub_menu\";s:1:\"1\";}i:28;a:2:{s:4:\"name\";s:20:\"submenu_fas_expenses\";s:11:\"is_sub_menu\";s:1:\"1\";}i:29;a:2:{s:4:\"name\";s:5:\"loans\";s:11:\"is_sub_menu\";s:1:\"1\";}i:30;a:2:{s:4:\"name\";s:5:\"taxes\";s:11:\"is_sub_menu\";s:1:\"1\";}i:31;a:2:{s:4:\"name\";s:20:\"submenu_fas_accounts\";s:11:\"is_sub_menu\";s:1:\"1\";}i:32;a:4:{s:4:\"name\";s:9:\"Logistics\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:5:\"truck\";s:15:\"open_in_new_tab\";s:0:\"\";}i:33;a:2:{s:4:\"name\";s:20:\"submenu_lms_delivery\";s:11:\"is_sub_menu\";s:1:\"1\";}i:34;a:2:{s:4:\"name\";s:21:\"submenu_lms_transfers\";s:11:\"is_sub_menu\";s:1:\"1\";}i:35;a:2:{s:4:\"name\";s:20:\"submenu_lms_vehicles\";s:11:\"is_sub_menu\";s:1:\"1\";}i:36;a:2:{s:4:\"name\";s:19:\"submenu_lms_drivers\";s:11:\"is_sub_menu\";s:1:\"1\";}i:37;a:4:{s:4:\"name\";s:5:\"Sales\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:9:\"cart-plus\";s:15:\"open_in_new_tab\";s:0:\"\";}i:38;a:2:{s:4:\"name\";s:12:\"sales_matrix\";s:11:\"is_sub_menu\";s:1:\"1\";}i:39;a:2:{s:4:\"name\";s:20:\"submenu_sms_invoices\";s:11:\"is_sub_menu\";s:1:\"1\";}i:40;a:2:{s:4:\"name\";s:20:\"submenu_sms_services\";s:11:\"is_sub_menu\";s:1:\"1\";}i:41;a:2:{s:4:\"name\";s:20:\"submenu_pid_products\";s:11:\"is_sub_menu\";s:1:\"1\";}i:42;a:2:{s:4:\"name\";s:7:\"clients\";s:11:\"is_sub_menu\";s:1:\"1\";}i:43;a:2:{s:4:\"name\";s:21:\"submenu_sms_customers\";s:11:\"is_sub_menu\";s:1:\"1\";}i:44;a:2:{s:4:\"name\";s:6:\"stores\";s:11:\"is_sub_menu\";s:1:\"1\";}i:45;a:4:{s:4:\"name\";s:11:\"Procurement\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:3:\"fax\";s:15:\"open_in_new_tab\";s:0:\"\";}i:46;a:2:{s:4:\"name\";s:21:\"submenu_pid_purchases\";s:11:\"is_sub_menu\";s:1:\"1\";}i:47;a:2:{s:4:\"name\";s:19:\"submenu_pid_returns\";s:11:\"is_sub_menu\";s:1:\"1\";}i:48;a:2:{s:4:\"name\";s:20:\"submenu_pid_supplier\";s:11:\"is_sub_menu\";s:1:\"1\";}i:49;a:4:{s:4:\"name\";s:8:\"Safekeep\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:4:\"lock\";s:15:\"open_in_new_tab\";s:0:\"\";}i:50;a:2:{s:4:\"name\";s:13:\"asset_entries\";s:11:\"is_sub_menu\";s:1:\"1\";}i:51;a:2:{s:4:\"name\";s:16:\"asset_categories\";s:11:\"is_sub_menu\";s:1:\"1\";}i:52;a:2:{s:4:\"name\";s:14:\"asset_location\";s:11:\"is_sub_menu\";s:1:\"1\";}i:53;a:2:{s:4:\"name\";s:12:\"asset_vendor\";s:11:\"is_sub_menu\";s:1:\"1\";}i:54;a:2:{s:4:\"name\";s:11:\"asset_brand\";s:11:\"is_sub_menu\";s:1:\"1\";}i:55;a:4:{s:4:\"name\";s:9:\"Marketing\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:10:\"line-chart\";s:15:\"open_in_new_tab\";s:0:\"\";}i:56;a:2:{s:4:\"name\";s:17:\"submenu_mcs_leads\";s:11:\"is_sub_menu\";s:1:\"1\";}i:57;a:2:{s:4:\"name\";s:18:\"submenu_mcs_status\";s:11:\"is_sub_menu\";s:1:\"1\";}i:58;a:2:{s:4:\"name\";s:18:\"submenu_mcs_source\";s:11:\"is_sub_menu\";s:1:\"1\";}i:59;a:2:{s:4:\"name\";s:9:\"estimates\";s:11:\"is_sub_menu\";s:1:\"1\";}i:60;a:2:{s:4:\"name\";s:11:\"raffle_draw\";s:11:\"is_sub_menu\";s:1:\"1\";}i:61;a:2:{s:4:\"name\";s:5:\"epass\";s:11:\"is_sub_menu\";s:1:\"1\";}i:62;a:4:{s:4:\"name\";s:11:\"Help Center\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:9:\"life-ring\";s:15:\"open_in_new_tab\";s:0:\"\";}i:63;a:2:{s:4:\"name\";s:7:\"tickets\";s:11:\"is_sub_menu\";s:1:\"1\";}i:64;a:2:{s:4:\"name\";s:15:\"help_page_title\";s:11:\"is_sub_menu\";s:1:\"1\";}i:65;a:2:{s:4:\"name\";s:14:\"knowledge_base\";s:11:\"is_sub_menu\";s:1:\"1\";}i:66;a:2:{s:4:\"name\";s:5:\"pages\";s:11:\"is_sub_menu\";s:1:\"1\";}i:67;a:4:{s:4:\"name\";s:8:\"Planning\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:11:\"paper-plane\";s:15:\"open_in_new_tab\";s:0:\"\";}i:68;a:2:{s:4:\"name\";s:24:\"submenu_pms_all_projects\";s:11:\"is_sub_menu\";s:1:\"1\";}i:69;a:2:{s:4:\"name\";s:23:\"submenu_pms_view_gantts\";s:11:\"is_sub_menu\";s:1:\"1\";}i:70;a:2:{s:4:\"name\";s:20:\"submenu_pms_my_tasks\";s:11:\"is_sub_menu\";s:1:\"1\";}i:71;a:2:{s:4:\"name\";s:22:\"submenu_pms_timesheets\";s:11:\"is_sub_menu\";s:1:\"1\";}i:72;a:4:{s:4:\"name\";s:8:\"Security\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:11:\"user-secret\";s:15:\"open_in_new_tab\";s:0:\"\";}i:73;a:2:{s:4:\"name\";s:11:\"access_logs\";s:11:\"is_sub_menu\";s:1:\"1\";}i:74;a:1:{s:4:\"name\";s:8:\"settings\";}}', 'app', 0),
('default_theme_color', '1d2632', 'display', 0),
('disable_access_favorite_project_option_for_clients', '', 'app', 0),
('disable_client_login', '1', 'app', 0),
('disable_client_signup', '1', 'app', 0),
('disable_dashboard_customization_by_clients', '', 'app', 0),
('disable_editing_left_menu_by_clients', '', 'app', 0),
('disable_hourly_leave', 'disabled', 'calendar', 0),
('disable_topbar_menu_customization', '', 'app', 0),
('email_protocol', 'smtp', 'app', 0),
('email_sent_from_address', 'noreply@erpat.app', 'app', 0),
('email_sent_from_name', 'ABC Company Inc.', 'app', 0),
('email_smtp_host', 'smtp.mailgun.org', 'app', 0),
('email_smtp_pass', '', 'app', 0),
('email_smtp_port', '587', 'app', 0),
('email_smtp_security_type', 'tls', 'app', 0),
('email_smtp_user', 'noreply@erpat.app', 'app', 0),
('enable_chat_via_pusher', '1', 'app', 0),
('enable_email_piping', '1', 'app', 0),
('enable_google_calendar_api', '1', 'app', 0),
('enable_google_drive_api_to_upload_file', '', 'app', 0),
('enable_push_notification', '1', 'app', 0),
('enable_rich_text_editor', '1', 'display', 0),
('enable_training', 'yes', 'general', 0),
('estimate_color', '', 'app', 0),
('estimate_footer', '<p><br></p>', 'app', 0),
('estimate_prefix', 'PURCHASE ORDER #', 'app', 0),
('favicon', 'a:1:{s:9:\"file_name\";s:30:\"_file64e3376a0fdc8-favicon.png\";}', 'general', 0),
('first_day_of_week', '1', 'calendar', 0),
('google_drive_authorized', '', 'app', 0),
('google_drive_client_id', '', 'app', 0),
('google_drive_client_secret', '', 'app', 0),
('google_drive_folder_ids', '', 'app', 0),
('google_drive_oauth_access_token', '', 'app', 0),
('google_drive_parent_folder_id', '', 'app', 0),
('google_drive_temp_file_ids', '', 'app', 0),
('hidden_client_menus', '', 'app', 0),
('imap_authorized', '', 'app', 0),
('imap_email', 'imap@erpat.app', 'app', 0),
('imap_host', 'imap.gmail.com', 'app', 0),
('imap_password', '', 'app', 0),
('imap_port', '993', 'app', 0),
('imap_ssl_enabled', '1', 'app', 0),
('inactive_ticket_closing_date', '2023-08-11', 'app', 0),
('initial_number_of_the_estimate', '1', 'app', 0),
('initial_number_of_the_invoice', '1', 'app', 0),
('invoice_color', '', 'app', 0),
('invoice_footer', '<p><br></p>', 'app', 0),
('invoice_logo', 'a:1:{s:9:\"file_name\";s:35:\"_file64e337aa07313-invoice-logo.png\";}', 'app', 0),
('invoice_prefix', 'BILLING STATEMENT #', 'app', 0),
('invoice_style', 'style_1', 'app', 0),
('invoice_terms', '', 'app', 0),
('invoice_warranty', '', 'app', 0),
('language', 'english', 'general', 0),
('last_check_fix', '2023-08-21 10:09:24', 'app', 0),
('last_cron_job_time', '1691765521', 'app', 0),
('last_daily_job_time', '20230811', 'app', 0),
('last_hourly_job_time', '1691761981', 'app', 0),
('last_minutely_job_time', '1691765521', 'app', 0),
('last_monthly_first_day_job_time', '202306', 'app', 0),
('last_monthly_job_time', '202308', 'app', 0),
('last_quarterly_job_time', '202306', 'app', 0),
('last_weekly_job_time', '20230808', 'app', 0),
('last_yearly_job_time', '2023', 'app', 0),
('module_access', '1', 'modules', 0),
('module_account', '1', 'modules', 0),
('module_accounting_summary', '1', 'modules', 0),
('module_accounts', '1', 'modules', 0),
('module_allprojects', '1', 'app', 0),
('module_ams', '1', 'app', 0),
('module_ams_category', '1', 'app', 0),
('module_ams_location', '1', 'app', 0),
('module_announcement', '1', 'app', 0),
('module_asset_category', '1', 'modules', 0),
('module_assets', '1', 'app', 0),
('module_attendance', '1', 'app', 0),
('module_balancesheet', '1', 'modules', 0),
('module_billofmaterials', '1', 'modules', 0),
('module_brands', '1', 'app', 0),
('module_chat', '1', 'app', 0),
('module_clients', '1', 'app', 0),
('module_consumer', '1', 'modules', 0),
('module_contributions', '1', 'modules', 0),
('module_css', '', 'app', 0),
('module_customers', '1', 'modules', 0),
('module_delivery', '1', 'modules', 0),
('module_department', '1', 'modules', 0),
('module_disciplinary', '1', 'modules', 0),
('module_driver', '1', 'modules', 0),
('module_employee', '1', 'modules', 0),
('module_epass', '1', 'modules', 0),
('module_estimate', '1', 'app', 0),
('module_estimate_request', '1', 'app', 0),
('module_event', '1', 'app', 0),
('module_expense', '1', 'app', 0),
('module_fas', '1', 'app', 0),
('module_fas_accounts', '1', 'app', 0),
('module_fas_balancesheet', '1', 'app', 0),
('module_fas_contributions', '1', 'app', 0),
('module_fas_incentives', '1', 'app', 0),
('module_fas_payments', '1', 'app', 0),
('module_fas_payroll', '1', 'app', 0),
('module_fas_summary', '1', 'app', 0),
('module_fas_transfer', '1', 'app', 0),
('module_gantt', '1', 'app', 0),
('module_help', '1', 'modules', 0),
('module_holidays', '1', 'modules', 0),
('module_hrm', '1', 'app', 0),
('module_hrm_department', '1', 'app', 0),
('module_hrm_disciplinary', '1', 'app', 0),
('module_hrm_employee', '1', 'app', 0),
('module_hrm_holidays', '1', 'app', 0),
('module_hrs', '1', 'app', 0),
('module_hts', '1', 'app', 0),
('module_incentives', '1', 'modules', 0),
('module_inventory', '1', 'modules', 0),
('module_invoice', '1', 'app', 0),
('module_item_transfer', '1', 'modules', 0),
('module_knowledge_base', '1', 'modules', 0),
('module_lds', '1', 'app', 0),
('module_lead', '1', 'app', 0),
('module_leave', '1', 'app', 0),
('module_lms', '', 'app', 0),
('module_lms_consumer', '', 'app', 0),
('module_lms_delivery', '', 'app', 0),
('module_lms_driver', '', 'app', 0),
('module_lms_transfer', '', 'app', 0),
('module_lms_vehicles', '', 'app', 0),
('module_lms_warehouse', '', 'app', 0),
('module_loan', '1', 'modules', 0),
('module_location', '1', 'modules', 0),
('module_mcm', '', 'app', 0),
('module_mcs', '1', 'app', 0),
('module_mes', '1', 'app', 0),
('module_message', '1', 'app', 0),
('module_mytask', '1', 'app', 0),
('module_note', '1', 'app', 0),
('module_overtime', '1', 'app', 0),
('module_page', '1', 'modules', 0),
('module_pages', '1', 'modules', 0),
('module_payment', '1', 'modules', 0),
('module_payments', '1', 'modules', 0),
('module_payroll', '1', 'modules', 0),
('module_pid', '', 'app', 0),
('module_pid_billofmaterials', '', 'app', 0),
('module_pid_inventory', '', 'app', 0),
('module_pid_productions', '', 'app', 0),
('module_pid_products', '', 'app', 0),
('module_pid_purchases', '1', 'app', 0),
('module_pid_rawmaterials', '', 'app', 0),
('module_pid_returns', '', 'app', 0),
('module_pid_supplier', '', 'app', 0),
('module_pms', '1', 'app', 0),
('module_product', '1', 'modules', 0),
('module_productions', '1', 'modules', 0),
('module_products', '1', 'modules', 0),
('module_project_timesheet', '1', 'app', 0),
('module_purchase', '1', 'modules', 0),
('module_purchases', '1', 'modules', 0),
('module_raffle', '1', 'modules', 0),
('module_rawmaterials', '1', 'modules', 0),
('module_return', '1', 'modules', 0),
('module_returns', '1', 'modules', 0),
('module_sales_matrix', '1', 'modules', 0),
('module_sales_summary', '1', 'modules', 0),
('module_schedule', '1', 'modules', 0),
('module_service', '1', 'modules', 0),
('module_services', '1', 'app', 0),
('module_sms', '1', 'app', 0),
('module_sms_coupons', '', 'app', 0),
('module_sms_customers', '1', 'app', 0),
('module_sms_giftcard', '', 'app', 0),
('module_sms_pos', '', 'app', 0),
('module_sms_sales_matrix', '', 'app', 0),
('module_stores', '1', 'modules', 0),
('module_summary', '1', 'modules', 0),
('module_supplier', '1', 'modules', 0),
('module_ticket', '1', 'modules', 0),
('module_timeline', '1', 'app', 0),
('module_todo', '1', 'app', 0),
('module_transfer', '1', 'modules', 0),
('module_unit', '1', 'modules', 0),
('module_vehicle', '1', 'modules', 0),
('module_vehicles', '1', 'modules', 0),
('module_vendors', '1', 'app', 0),
('module_warehouse', '1', 'modules', 0),
('name_format', 'firstlast', 'display', 0),
('nightpay_end_trigger', '6:00 AM', 'calendar', 0),
('nightpay_start_trigger', '10:00 PM', 'calendar', 0),
('no_of_decimals', '2', 'finance', 0),
('overtime_trigger', '0.49', 'calendar', 0),
('payroll_reply_to', 'payroll@erpat.app', 'finance', 0),
('project_reference_in_tickets', '1', 'app', 0),
('pusher_app_id', '', 'app', 0),
('pusher_cluster', '', 'app', 0),
('pusher_key', '', 'app', 0),
('pusher_secret', '', 'app', 0),
('re_captcha_secret_key', '', 'app', 0),
('re_captcha_site_key', '', 'app', 0),
('rows_per_page', '25', 'display', 0),
('rtl', '0', 'app', 0),
('scrollbar', 'jquery', 'display', 0),
('send_bcc_to', '', 'app', 0),
('send_estimate_bcc_to', '', 'app', 0),
('send_invoice_due_after_reminder', '', 'app', 0),
('send_invoice_due_pre_reminder', '', 'app', 0),
('send_recurring_invoice_reminder_before_creation', '', 'app', 0),
('show_background_image_in_signin_page', 'yes', 'general', 0),
('show_logo_in_signin_page', 'yes', 'general', 0),
('show_recent_ticket_comments_at_the_top', '1', 'app', 0),
('show_theme_color_changer', 'no', 'display', 0),
('signin_page_background', 'a:4:{s:9:\"file_name\";s:51:\"system_file64e336f935936-sigin-background-image.jpg\";s:9:\"file_size\";s:5:\"32064\";s:7:\"file_id\";N;s:12:\"service_type\";N;}', 'general', 0),
('since_last_break', '30', 'app', 0),
('since_last_clock_out', '300', 'app', 0),
('site_admin_email', 'system@erpat.app', 'general', 0),
('site_logo', 'a:1:{s:9:\"file_name\";s:32:\"_file64e3376a0e8d2-site-logo.png\";}', 'general', 0),
('site_title', 'ERPat System', 'general', 0),
('syntry_site_link', 'https://syntry-demo.erpat.app', 'general', 0),
('task_point_range', '5', 'app', 0),
('ticket_prefix', '', 'app', 0),
('time_format', 'capital', 'calendar', 0),
('timezone', 'Asia/Manila', 'calendar', 0),
('user_1_dashboard', '', 'user', 0),
('verify_email_before_client_signup', '', 'app', 0),
('weekends', '6,0', 'calendar', 0),
('whitelisted_autoclockout', '', 'app', 0),
('whitelisted_breaktime_tracking', '', 'app', 0),
('yearly_paid_time_off', '0', 'calendar', 0);

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `facebook` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `linkedin` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `googleplus` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `digg` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtube` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `pinterest` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `instagram` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `github` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tumblr` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `vine` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  `owner` varchar(36) DEFAULT NULL,
  `city_id` varchar(36) DEFAULT NULL,
  `category_id` varchar(36) DEFAULT NULL,
  `name` varchar(120) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `image` varchar(180) NOT NULL,
  `phone` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(180) NOT NULL DEFAULT '',
  `vcode` varchar(50) NOT NULL DEFAULT '',
  `cover` text NOT NULL,
  `images` text NOT NULL,
  `certificates` text NOT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `isClosed` enum('0','1') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `commission` decimal(10,2) NOT NULL DEFAULT 0.00,
  `lazada` text NOT NULL,
  `shopee` text NOT NULL,
  `fbpage` text NOT NULL,
  `igname` text NOT NULL,
  `pending_updates` text NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stores_categories`
--

CREATE TABLE `stores_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `uuid` varchar(36) DEFAULT '',
  `parent_id` varchar(36) DEFAULT '',
  `name` varchar(150) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stripe_ipn`
--

CREATE TABLE `stripe_ipn` (
  `id` int(11) NOT NULL,
  `payment_intent` text COLLATE utf8_unicode_ci NOT NULL,
  `verification_code` text COLLATE utf8_unicode_ci NOT NULL,
  `payment_verification_code` text COLLATE utf8_unicode_ci NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `contact_user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_id` int(11) NOT NULL,
  `milestone_id` int(11) NOT NULL DEFAULT 0,
  `assigned_to` int(11) NOT NULL,
  `deadline` date DEFAULT NULL,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `points` tinyint(4) NOT NULL DEFAULT 1,
  `status` enum('to_do','in_progress','done') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'to_do',
  `status_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `collaborators` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `recurring` tinyint(1) NOT NULL DEFAULT 0,
  `repeat_every` int(11) NOT NULL DEFAULT 0,
  `repeat_type` enum('days','weeks','months','years') COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_cycles` int(11) NOT NULL DEFAULT 0,
  `recurring_task_id` int(11) NOT NULL DEFAULT 0,
  `no_of_cycles_completed` int(11) NOT NULL DEFAULT 0,
  `created_date` date NOT NULL,
  `blocking` text COLLATE utf8_unicode_ci NOT NULL,
  `blocked_by` text COLLATE utf8_unicode_ci NOT NULL,
  `parent_task_id` int(11) NOT NULL,
  `next_recurring_date` date DEFAULT NULL,
  `reminder_date` date NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `deleted` tinyint(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_status`
--

CREATE TABLE `task_status` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `key_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task_status`
--

INSERT INTO `task_status` (`id`, `title`, `key_name`, `color`, `sort`, `deleted`) VALUES
(1, 'To Do', 'to_do', '#F9A52D', 0, 0),
(2, 'In progress', 'in_progress', '#1672B9', 1, 0),
(3, 'Done', 'done', '#00B393', 3, 0),
(4, 'In Review', '', '#e74c3c', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(11) NOT NULL,
  `title` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `percentage` double NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `title`, `percentage`, `deleted`) VALUES
(1, 'Tax (12%)', 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `members` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `heads` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `date_created` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `title`, `description`, `members`, `heads`, `date_created`, `created_by`, `deleted`) VALUES
(1, 'IT Department', '', '', '', 2023, 1, 0),
(2, 'HR Department', '', '', '', 2023, 1, 0),
(3, 'Finance Department', '', '', '', 2023, 1, 0),
(4, 'Sales Department', '', '', '', 2023, 1, 0),
(5, 'Production Department', '', '', '', 2023, 1, 0),
(6, 'Procurement Department', '', '', '', 2023, 1, 0),
(7, 'Research Department', '', '', '', 2023, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `team_member_job_info`
--

CREATE TABLE `team_member_job_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_idnum` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `rfid_num` varchar(24) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sched_id` int(11) DEFAULT 0,
  `salary` double NOT NULL,
  `salary_term` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rate_per_hour` decimal(10,2) NOT NULL,
  `hours_per_day` float NOT NULL,
  `date_of_hire` date DEFAULT NULL,
  `contact_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT '',
  `contact_address` varchar(200) COLLATE utf8_unicode_ci DEFAULT '',
  `contact_phone` varchar(200) COLLATE utf8_unicode_ci DEFAULT '',
  `signiture_url` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sss` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pag_ibig` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phil_health` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `bank_account` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `bank_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `ticket_type_id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `requested_by` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `status` enum('new','client_replied','open','closed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `last_activity_at` datetime DEFAULT NULL,
  `assigned_to` int(11) NOT NULL DEFAULT 0,
  `creator_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `creator_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `task_id` int(11) NOT NULL,
  `closed_at` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_comments`
--

CREATE TABLE `ticket_comments` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `files` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_templates`
--

CREATE TABLE `ticket_templates` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `ticket_type_id` int(11) NOT NULL,
  `private` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticket_types`
--

INSERT INTO `ticket_types` (`id`, `title`, `deleted`) VALUES
(1, 'General Support', 0),
(2, 'Payroll Dispute', 0),
(3, 'IT Issues', 0),
(4, 'HR Concern', 0),
(5, 'Maintainance', 0),
(6, 'Security Reports', 0);

-- --------------------------------------------------------

--
-- Table structure for table `to_do`
--

CREATE TABLE `to_do` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('to_do','done') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'to_do',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(10) NOT NULL,
  `base_unit` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `operator` char(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` decimal(10,2) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `base_unit`, `title`, `abbreviation`, `operator`, `value`, `created_on`, `created_by`, `deleted`) VALUES
(1, 0, 'Piece(s)', 'pcs', NULL, '1.00', '2022-12-07 06:16:35', 1, 0),
(2, 0, 'Fee', 'fee', NULL, '1.00', '2022-12-09 12:20:55', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_type` enum('staff','client','lead','customer','driver','supplier','vendor','system') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'client',
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `role_id` int(11) NOT NULL DEFAULT 0,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `message_checked_at` datetime DEFAULT NULL,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `notification_checked_at` datetime DEFAULT NULL,
  `is_primary_contact` tinyint(1) NOT NULL DEFAULT 0,
  `job_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Untitled',
  `disable_login` tinyint(1) NOT NULL DEFAULT 0,
  `access_erpat` int(1) DEFAULT 0,
  `access_syntry` int(1) DEFAULT 0,
  `access_madage` int(1) DEFAULT 0,
  `access_galyon` int(1) DEFAULT 1,
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` text COLLATE utf8_unicode_ci NOT NULL,
  `city` text COLLATE utf8_unicode_ci NOT NULL,
  `state` text COLLATE utf8_unicode_ci NOT NULL,
  `zip` text COLLATE utf8_unicode_ci NOT NULL,
  `country` text COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `alternative_address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alternative_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date NOT NULL DEFAULT '0001-01-01',
  `ssn` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8_unicode_ci DEFAULT NULL,
  `sticky_note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `skype` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `asset_vendor_id` int(11) NOT NULL DEFAULT 0,
  `enable_web_notification` tinyint(1) NOT NULL DEFAULT 1,
  `enable_email_notification` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT 0,
  `last_online` datetime DEFAULT NULL,
  `labels` text COLLATE utf8_unicode_ci NOT NULL,
  `requested_account_removal` tinyint(1) NOT NULL DEFAULT 0,
  `license_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `license_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `resigned` int(1) DEFAULT 0,
  `terminated` int(1) DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `first_name`, `last_name`, `user_type`, `is_admin`, `role_id`, `email`, `password`, `image`, `status`, `message_checked_at`, `client_id`, `vendor_id`, `notification_checked_at`, `is_primary_contact`, `job_title`, `disable_login`, `access_erpat`, `access_syntry`, `access_madage`, `access_galyon`, `note`, `street`, `city`, `state`, `zip`, `country`, `address`, `alternative_address`, `phone`, `alternative_phone`, `dob`, `ssn`, `gender`, `sticky_note`, `skype`, `asset_vendor_id`, `enable_web_notification`, `enable_email_notification`, `created_at`, `created_by`, `last_online`, `labels`, `requested_account_removal`, `license_number`, `license_image`, `resigned`, `terminated`, `deleted`, `updated_at`) VALUES
(1, '50773d1b-47cd-40ae-a749-900e6fa450df', 'System', 'Admin', 'staff', 1, 0, 'admin@erpat.app', '$2y$10$Ukzg5GrTJsBDe7SP1cJj9emtwveKdiHkfeoQQMydb6ciVokioMNSm', NULL, 'active', '2023-07-08 07:58:23', 0, 0, '2023-08-10 09:39:46', 0, 'Admin', 0, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', '', '1991-10-12', '', 'male', NULL, '', 0, 1, 1, '2020-11-01 07:33:41', 0, '2023-08-21 10:10:53', '', 0, '', '', 0, 0, 0, '2022-08-03 09:16:25'),
(2, '77576be8-e364-4800-8b5d-3fc3b1dcccf6', 'Juan', 'Dela Cruz', 'staff', 0, 39, 'juandelacruz@erpat.app', '$2y$10$sNW8P/KVIDBJxHlH/8t.U.YUwQtU44wPlWkyDrELgAeFpmBq6Dedu', NULL, 'active', NULL, 0, 0, NULL, 0, 'IT Manager', 0, 0, 0, 0, 1, NULL, '', '', '', '', '', NULL, NULL, '', NULL, '0001-01-01', NULL, 'male', NULL, NULL, 0, 1, 1, '2023-08-21 08:55:43', 0, NULL, '', 0, '', '', 0, 0, 0, '2023-08-21 08:55:43');

-- --------------------------------------------------------

--
-- Table structure for table `users_meta`
--

CREATE TABLE `users_meta` (
  `user_id` int(11) NOT NULL,
  `meta_key` varchar(180) NOT NULL,
  `meta_val` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(10) NOT NULL,
  `brand` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transmission` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `no_of_wheels` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plate_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `max_cargo_weight` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(10) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `contact` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `city` text COLLATE utf8_unicode_ci NOT NULL,
  `state` text COLLATE utf8_unicode_ci NOT NULL,
  `zip` text COLLATE utf8_unicode_ci NOT NULL,
  `country` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

CREATE TABLE `verification` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` enum('invoice_payment','reset_password','verify_email','invitation') COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `zip_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `head` bigint(10) NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `created_on` datetime NOT NULL,
  `deleted` tinyint(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` int(11) UNSIGNED NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `qrcode` varchar(100) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `rfid` varchar(25) NOT NULL,
  `labels` text NOT NULL,
  `remarks` text NOT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'inactive',
  `created_by` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_devices`
--
ALTER TABLE `access_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `access_device_categories`
--
ALTER TABLE `access_device_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `access_logs`
--
ALTER TABLE `access_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_transfers`
--
ALTER TABLE `account_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `asset_brands`
--
ALTER TABLE `asset_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_categories`
--
ALTER TABLE `asset_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_entries`
--
ALTER TABLE `asset_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_locations`
--
ALTER TABLE `asset_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `asset_vendors`
--
ALTER TABLE `asset_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `checked_by` (`checked_by`);

--
-- Indexes for table `bays`
--
ALTER TABLE `bays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qrcode` (`qrcode`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `rfid` (`rfid`);

--
-- Indexes for table `bill_of_materials`
--
ALTER TABLE `bill_of_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_of_materials_materials`
--
ALTER TABLE `bill_of_materials_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `checklist_items`
--
ALTER TABLE `checklist_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_groups`
--
ALTER TABLE `client_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consumers`
--
ALTER TABLE `consumers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_widgets`
--
ALTER TABLE `custom_widgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dashboards`
--
ALTER TABLE `dashboards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discipline_categories`
--
ALTER TABLE `discipline_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discipline_entries`
--
ALTER TABLE `discipline_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `epass_area`
--
ALTER TABLE `epass_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `epass_block`
--
ALTER TABLE `epass_block`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `epass_seat`
--
ALTER TABLE `epass_seat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimates`
--
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimate_forms`
--
ALTER TABLE `estimate_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimate_items`
--
ALTER TABLE `estimate_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimate_requests`
--
ALTER TABLE `estimate_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `event_pass`
--
ALTER TABLE `event_pass`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_raffle`
--
ALTER TABLE `event_raffle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_raffle_participants`
--
ALTER TABLE `event_raffle_participants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_raffle_prizes`
--
ALTER TABLE `event_raffle_prizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_raffle_winners`
--
ALTER TABLE `event_raffle_winners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_payments`
--
ALTER TABLE `expenses_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_files`
--
ALTER TABLE `general_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_articles`
--
ALTER TABLE `help_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_categories`
--
ALTER TABLE `help_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_item_categories`
--
ALTER TABLE `inventory_item_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_stock_override`
--
ALTER TABLE `inventory_stock_override`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_transfers`
--
ALTER TABLE `inventory_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_transfer_items`
--
ALTER TABLE `inventory_transfer_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_payments`
--
ALTER TABLE `invoice_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lead_source`
--
ALTER TABLE `lead_source`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lead_status`
--
ALTER TABLE `lead_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_applications`
--
ALTER TABLE `leave_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_type_id` (`leave_type_id`),
  ADD KEY `user_id` (`applicant_id`),
  ADD KEY `checked_by` (`checked_by`);

--
-- Indexes for table `leave_credits`
--
ALTER TABLE `leave_credits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qrcode` (`qrcode`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `rfid` (`rfid`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_categories`
--
ALTER TABLE `loan_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_fees`
--
ALTER TABLE `loan_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_stages`
--
ALTER TABLE `loan_stages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_from` (`from_user_id`),
  ADD KEY `message_to` (`to_user_id`);

--
-- Indexes for table `migrations_backup`
--
ALTER TABLE `migrations_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event` (`event`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pallets`
--
ALTER TABLE `pallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qrcode` (`qrcode`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `rfid` (`rfid`);

--
-- Indexes for table `payhp_migrations`
--
ALTER TABLE `payhp_migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payhp_payslips`
--
ALTER TABLE `payhp_payslips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payhp_payslips_uuid_index` (`uuid`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paypal_ipn`
--
ALTER TABLE `paypal_ipn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslips`
--
ALTER TABLE `payslips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslips_deductions`
--
ALTER TABLE `payslips_deductions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslips_earnings`
--
ALTER TABLE `payslips_earnings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslips_sents`
--
ALTER TABLE `payslips_sents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qrcode` (`qrcode`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `rfid` (`rfid`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_brands`
--
ALTER TABLE `product_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_files`
--
ALTER TABLE `project_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_settings`
--
ALTER TABLE `project_settings`
  ADD UNIQUE KEY `unique_index` (`project_id`,`setting_name`);

--
-- Indexes for table `project_time`
--
ALTER TABLE `project_time`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_budgets`
--
ALTER TABLE `purchase_order_budgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_materials`
--
ALTER TABLE `purchase_order_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_returns`
--
ALTER TABLE `purchase_order_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_return_materials`
--
ALTER TABLE `purchase_order_return_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `racks`
--
ALTER TABLE `racks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qrcode` (`qrcode`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `rfid` (`rfid`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services_categories`
--
ALTER TABLE `services_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores_categories`
--
ALTER TABLE `stores_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stripe_ipn`
--
ALTER TABLE `stripe_ipn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_status`
--
ALTER TABLE `task_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_member_job_info`
--
ALTER TABLE `team_member_job_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfid_num` (`rfid_num`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_templates`
--
ALTER TABLE `ticket_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `to_do`
--
ALTER TABLE `to_do`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_type` (`user_type`),
  ADD KEY `email` (`email`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `deleted` (`deleted`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verification`
--
ALTER TABLE `verification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qrcode` (`qrcode`),
  ADD KEY `barcode` (`barcode`),
  ADD KEY `rfid` (`rfid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_devices`
--
ALTER TABLE `access_devices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `access_device_categories`
--
ALTER TABLE `access_device_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `access_logs`
--
ALTER TABLE `access_logs`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `account_transactions`
--
ALTER TABLE `account_transactions`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_transfers`
--
ALTER TABLE `account_transfers`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_brands`
--
ALTER TABLE `asset_brands`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_categories`
--
ALTER TABLE `asset_categories`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_entries`
--
ALTER TABLE `asset_entries`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_locations`
--
ALTER TABLE `asset_locations`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_vendors`
--
ALTER TABLE `asset_vendors`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bays`
--
ALTER TABLE `bays`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_of_materials`
--
ALTER TABLE `bill_of_materials`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_of_materials_materials`
--
ALTER TABLE `bill_of_materials_materials`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checklist_items`
--
ALTER TABLE `checklist_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_groups`
--
ALTER TABLE `client_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consumers`
--
ALTER TABLE `consumers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_widgets`
--
ALTER TABLE `custom_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dashboards`
--
ALTER TABLE `dashboards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discipline_categories`
--
ALTER TABLE `discipline_categories`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discipline_entries`
--
ALTER TABLE `discipline_entries`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `epass_area`
--
ALTER TABLE `epass_area`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `epass_block`
--
ALTER TABLE `epass_block`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `epass_seat`
--
ALTER TABLE `epass_seat`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimates`
--
ALTER TABLE `estimates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimate_forms`
--
ALTER TABLE `estimate_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimate_items`
--
ALTER TABLE `estimate_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimate_requests`
--
ALTER TABLE `estimate_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_pass`
--
ALTER TABLE `event_pass`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_raffle`
--
ALTER TABLE `event_raffle`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_raffle_participants`
--
ALTER TABLE `event_raffle_participants`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_raffle_prizes`
--
ALTER TABLE `event_raffle_prizes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_raffle_winners`
--
ALTER TABLE `event_raffle_winners`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_payments`
--
ALTER TABLE `expenses_payments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `general_files`
--
ALTER TABLE `general_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `help_articles`
--
ALTER TABLE `help_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `help_categories`
--
ALTER TABLE `help_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_item_categories`
--
ALTER TABLE `inventory_item_categories`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_stock_override`
--
ALTER TABLE `inventory_stock_override`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_transfers`
--
ALTER TABLE `inventory_transfers`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_transfer_items`
--
ALTER TABLE `inventory_transfer_items`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_payments`
--
ALTER TABLE `invoice_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labels`
--
ALTER TABLE `labels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_source`
--
ALTER TABLE `lead_source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lead_status`
--
ALTER TABLE `lead_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leave_applications`
--
ALTER TABLE `leave_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_credits`
--
ALTER TABLE `leave_credits`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_categories`
--
ALTER TABLE `loan_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_fees`
--
ALTER TABLE `loan_fees`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_stages`
--
ALTER TABLE `loan_stages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations_backup`
--
ALTER TABLE `migrations_backup`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_settings`
--
ALTER TABLE `notification_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pallets`
--
ALTER TABLE `pallets`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payhp_migrations`
--
ALTER TABLE `payhp_migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payhp_payslips`
--
ALTER TABLE `payhp_payslips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `paypal_ipn`
--
ALTER TABLE `paypal_ipn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payslips`
--
ALTER TABLE `payslips`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payslips_deductions`
--
ALTER TABLE `payslips_deductions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payslips_earnings`
--
ALTER TABLE `payslips_earnings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payslips_sents`
--
ALTER TABLE `payslips_sents`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productions`
--
ALTER TABLE `productions`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_brands`
--
ALTER TABLE `product_brands`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_comments`
--
ALTER TABLE `project_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_files`
--
ALTER TABLE `project_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_time`
--
ALTER TABLE `project_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_budgets`
--
ALTER TABLE `purchase_order_budgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_materials`
--
ALTER TABLE `purchase_order_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_returns`
--
ALTER TABLE `purchase_order_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_return_materials`
--
ALTER TABLE `purchase_order_return_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `racks`
--
ALTER TABLE `racks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services_categories`
--
ALTER TABLE `services_categories`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores_categories`
--
ALTER TABLE `stores_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stripe_ipn`
--
ALTER TABLE `stripe_ipn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_status`
--
ALTER TABLE `task_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `team_member_job_info`
--
ALTER TABLE `team_member_job_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_templates`
--
ALTER TABLE `ticket_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `to_do`
--
ALTER TABLE `to_do`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `verification`
--
ALTER TABLE `verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
