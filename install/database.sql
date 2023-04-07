-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 19, 2023 at 05:06 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


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
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
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
(5, 'signature', 'Signature', 'Powered By: <a href=\"https://bytescrafter.net/\" target=\"_blank\">BytesCrafter</a>', '<p style=\"text-align: center;\"><b>Pinakamakinang © 2023. All rights reserved. </b><br><span _ngcontent-serverapp-c117=\"\">Made possible by <a _ngcontent-serverapp-c117=\"\" href=\"http://bytescrafter.net\" style=\"\"><font color=\"#003163\" style=\"\"><b>BytesCrafter</b></font></a></span></p>', 0),
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
(25, 'event_pass', 'e-Pass Verification', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket Confirmation</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello Brilliant,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We are happy to inform you that your booking for Brilliant Skin Essentials Inc. -#PINAKAMAKINANG “The Brilliant Concert 2023 is now under processing! Get ready to witness the BRIGHTEST event of the year.</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br>Title:&nbsp;</span></font><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">#PINAKAMAKINANG “The Brilliant Concert 2023</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Date: 07 February 2023</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Time: 4:00 PM</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">\n        </span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Participant`s details:</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Reference ID: {REFERENCE_ID}</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Group : {GROUP_NAME}<br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Name: {FIRST_NAME} {LAST_NAME}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Phone: {PHONE_NUMBER}</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Number of Seats: {TOTAL_SEATS}</span><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Remarks: {REMARKS}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Event location:</span></font></p><p><font color=\"#555555\"><b style=\"font-size: 14px;\">Smart Araneta Coliseum</b><br><span style=\"font-size: 14px;\">General Roxas Ave, Araneta City, QC, 1109 Metro Manila</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a href=\"https://goo.gl/maps/P7gXh8FEMLjPSxUH6\" target=\"_blank\" style=\"background-color: rgb(0, 179, 147); color: rgb(255, 255, 255); padding: 10px 15px;\">Open on Google Map</a></span></p><div><br></div><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We can’t wait to see you!</span></font></p><p style=\"\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket Reservation</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello Brilliant,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We are happy to inform you that your booking for </span><b style=\"font-size: 14px;\">Brilliant Skin Essentials Inc. -#PINAKAMAKINANG “The Brilliant Concert 2023\"</b><span style=\"font-size: 14px;\">&nbsp;is now being processed! We will send your seat number and QR ticket one (1) week before the event.<br></span></font></p><p><span style=\"font-size: 14px; color: rgb(85, 85, 85);\">Get ready to witness the BRIGHTEST event of the year!</span><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">See you, Brilliant!</span></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\"><br></span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Title:&nbsp;</span></font><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">#PINAKAMAKINANG “The Brilliant Concert 2023</span><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">“</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Date: 07 February 2023</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Time: 4:00 PM</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Reservation details:</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Reference ID: {REFERENCE_ID}</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Group : {GROUP_NAME}<br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Name: {FIRST_NAME} {LAST_NAME}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Phone: {PHONE_NUMBER}</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Number of Seats: {TOTAL_SEATS}</span><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Remarks: {REMARKS}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Event location:</span></font></p><p><font color=\"#555555\"><b style=\"font-size: 14px;\">Smart Araneta Coliseum</b><br><span style=\"font-size: 14px;\">General Roxas Ave, Araneta City, QC, 1109 Metro Manila</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a href=\"https://goo.gl/maps/P7gXh8FEMLjPSxUH6\" target=\"_blank\" style=\"background-color: rgb(0, 179, 147); color: rgb(255, 255, 255); padding: 10px 15px;\">Open on Google Map</a></span></p><div><br></div><p><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', 0),
(26, 'epass_confirm', 'e-Pass Confirmation - New!', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>Ticket Confirmation</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello Brilliant,</span><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><span style=\"font-weight: bold;\"><br></span></span></p>            <p style=\"\"><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We are happy to inform you that your booking for Brilliant Skin Essentials Inc. -#PINAKAMAKINANG “The Brilliant Concert 2023 is now <b>approved </b>and <b>reserved</b>! Get ready to witness the BRIGHTEST event of the year.</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br>Title:&nbsp;</span></font><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">#PINAKAMAKINANG “The Brilliant Concert 2023</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Date: 07 February 2023</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Time: 4:00 PM</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">\n        </span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Participant`s details:</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Reference ID: {REFERENCE_ID}</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Group : {GROUP_NAME}<br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Name: {FIRST_NAME} {LAST_NAME}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Phone: {PHONE_NUMBER}</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Number of Seats: {TOTAL_SEATS}</span><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Remarks: {REMARKS}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Event location:</span></font></p><p><font color=\"#555555\"><b style=\"font-size: 14px;\">Smart Araneta Coliseum</b><br><span style=\"font-size: 14px;\">General Roxas Ave, Araneta City, QC, 1109 Metro Manila</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a href=\"https://goo.gl/maps/P7gXh8FEMLjPSxUH6\" target=\"_blank\" style=\"background-color: rgb(0, 179, 147); color: rgb(255, 255, 255); padding: 10px 15px;\">Open on Google Map</a></span></p><div><br></div><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We can’t wait to see you!</span></font></p><p style=\"\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', '<div style=\"background-color: #eeeeef; padding: 50px 0; \">    <div style=\"max-width:640px; margin:0 auto; \">  <div style=\"color: #fff; text-align: center; background-color:#33333e; padding: 30px; border-top-left-radius: 3px; border-top-right-radius: 3px; margin: 0;\"><h1>e-Pass Confirmation</h1> </div> <div style=\"padding: 20px; background-color: rgb(255, 255, 255);\">            <p style=\"\"><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\">Hello Brilliant,</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We are happy to inform you that your booking for Brilliant Skin Essentials Inc. -#PINAKAMAKINANG “The Brilliant Concert 2023 is now <b>approved </b>and <b>reserved</b>! Get ready to witness the BRIGHTEST event of the year.</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br><b>Title:&nbsp;</b></span></font><span style=\"color: rgb(85, 85, 85); font-size: 14px;\"><b>#PINAKAMAKINANG “The Brilliant Concert 2023</b></span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><b>Date: 07 February 2023</b></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><b>Time: 4:00 PM</b></span></font></p><p><br></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><b>Companion`s details</b>:</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Reference ID: {REFERENCE_ID}</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Group : {GROUP_NAME}<br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Name: {FIRST_NAME} {LAST_NAME}</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Phone: {PHONE_NUMBER}</span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Number of Seats: {TOTAL_SEATS}</span></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px;\">Remarks: {REMARKS}</span></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">REMINDERS: Do not post your QR code on any social media platforms to prevent an unauthorized person from using it.</span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><b>Event location:</b></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">Smart Araneta Coliseum</span><br><span style=\"font-size: 14px;\"><i>General Roxas Ave, Araneta City, QC, 1109 Metro Manila</i></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><span style=\"color: rgb(85, 85, 85); font-size: 14px; line-height: 20px;\"><a href=\"https://goo.gl/maps/P7gXh8FEMLjPSxUH6\" target=\"_blank\" style=\"background-color: rgb(0, 179, 147); color: rgb(255, 255, 255); padding: 10px 15px;\">Open on Google Map</a></span></p><div><br></div><p><font color=\"#555555\"><span style=\"font-size: 14px;\"><br></span></font></p><p><font color=\"#555555\"><span style=\"font-size: 14px;\">We can’t wait to see you!</span></font></p><p style=\"\"><br></p>            <p style=\"color: rgb(85, 85, 85); font-size: 14px;\">{SIGNATURE}</p>        </div>    </div></div>', 0);

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
  `override` tinyint(1) NOT NULL DEFAULT 0,
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
  `labels` varchar(1) DEFAULT NULL,
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
  `category_id` int(11) NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `files` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `due_date` date DEFAULT NULL,
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
(1, 'Payroll', 0, 0),
(2, 'Contribution', 0, 0),
(3, 'Incentive', 0, 0),
(4, 'Purchase', 1, 0),
(5, 'Miscellaneous', 1, 0);

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
  `created_by` int(11) NOT NULL,
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
  `type` text COLLATE utf8_unicode_ci NOT NULL,
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
(5, 'Elsewhere', 5, 0);

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
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `title`, `status`, `color`, `description`, `deleted`) VALUES
(1, 'Casual Leave', 'active', '#83c340', '', 0);

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
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint(10) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `sku` text COLLATE utf8_unicode_ci NOT NULL,
  `unit` bigint(10) NOT NULL,
  `category` bigint(10) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `vendor` bigint(10) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_categories`
--

CREATE TABLE `material_categories` (
  `id` bigint(10) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_inventory`
--

CREATE TABLE `material_inventory` (
  `id` bigint(10) NOT NULL,
  `warehouse` bigint(10) NOT NULL,
  `stock` float NOT NULL,
  `material_id` bigint(10) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `sku` text COLLATE utf8_unicode_ci NOT NULL,
  `unit` bigint(10) NOT NULL,
  `category` bigint(10) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `vendor` bigint(10) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_inventory_stock_override`
--

CREATE TABLE `material_inventory_stock_override` (
  `id` bigint(10) NOT NULL,
  `warehouse` bigint(10) NOT NULL,
  `material_inventory_id` bigint(10) NOT NULL,
  `stock` float NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_inventory_transfers`
--

CREATE TABLE `material_inventory_transfers` (
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
-- Table structure for table `material_inventory_transfer_items`
--

CREATE TABLE `material_inventory_transfer_items` (
  `id` bigint(10) NOT NULL,
  `material_inventory_id` bigint(10) NOT NULL,
  `reference_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` float NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

--
-- Dumping data for table `notification_settings`
--

INSERT INTO `notification_settings` (`id`, `event`, `category`, `enable_email`, `enable_web`, `enable_slack`, `notify_to_team`, `notify_to_team_members`, `notify_to_terms`, `sort`, `deleted`) VALUES
(1, 'project_created', 'project', 0, 0, 0, '', '', '', 1, 0),
(2, 'project_deleted', 'project', 0, 0, 0, '', '', '', 2, 0),
(3, 'project_task_created', 'project', 0, 1, 0, '', '', 'project_members,task_assignee', 3, 0),
(4, 'project_task_updated', 'project', 0, 1, 0, '', '', 'task_assignee', 4, 0),
(5, 'project_task_assigned', 'project', 0, 1, 0, '', '', 'task_assignee', 5, 0),
(7, 'project_task_started', 'project', 0, 0, 0, '', '', '', 7, 0),
(8, 'project_task_finished', 'project', 0, 0, 0, '', '', '', 8, 0),
(9, 'project_task_reopened', 'project', 0, 0, 0, '', '', '', 9, 0),
(10, 'project_task_deleted', 'project', 0, 1, 0, '', '', 'task_assignee', 10, 0),
(11, 'project_task_commented', 'project', 0, 1, 0, '', '', 'task_assignee', 11, 0),
(12, 'project_member_added', 'project', 0, 1, 0, '', '', 'project_members', 12, 0),
(13, 'project_member_deleted', 'project', 0, 1, 0, '', '', 'project_members', 13, 0),
(14, 'project_file_added', 'project', 0, 1, 0, '', '', 'project_members', 14, 0),
(15, 'project_file_deleted', 'project', 0, 1, 0, '', '', 'project_members', 15, 0),
(16, 'project_file_commented', 'project', 0, 1, 0, '', '', 'project_members', 16, 0),
(17, 'project_comment_added', 'project', 0, 1, 0, '', '', 'project_members', 17, 0),
(18, 'project_comment_replied', 'project', 0, 1, 0, '', '', 'project_members,comment_creator', 18, 0),
(19, 'project_customer_feedback_added', 'project', 0, 1, 0, '', '', 'project_members', 19, 0),
(20, 'project_customer_feedback_replied', 'project', 0, 1, 0, '', '', 'project_members,comment_creator', 20, 0),
(21, 'client_signup', 'client', 0, 0, 0, '', '', '', 21, 0),
(22, 'invoice_online_payment_received', 'invoice', 0, 0, 0, '', '', '', 22, 0),
(23, 'leave_application_submitted', 'leave', 0, 0, 0, '', '', '', 23, 0),
(24, 'leave_approved', 'leave', 0, 1, 0, '', '', 'leave_applicant', 24, 0),
(25, 'leave_assigned', 'leave', 0, 1, 0, '', '', 'leave_applicant', 25, 0),
(26, 'leave_rejected', 'leave', 0, 1, 0, '', '', 'leave_applicant', 26, 0),
(27, 'leave_canceled', 'leave', 0, 0, 0, '', '', '', 27, 0),
(28, 'ticket_created', 'ticket', 0, 0, 0, '', '', '', 28, 0),
(29, 'ticket_commented', 'ticket', 0, 1, 0, '', '', 'client_primary_contact,ticket_creator', 29, 0),
(30, 'ticket_closed', 'ticket', 0, 1, 0, '', '', 'client_primary_contact,ticket_creator', 30, 0),
(31, 'ticket_reopened', 'ticket', 0, 1, 0, '', '', 'client_primary_contact,ticket_creator', 31, 0),
(32, 'estimate_request_received', 'estimate', 0, 0, 0, '', '', '', 32, 0),
(34, 'estimate_accepted', 'estimate', 0, 0, 0, '', '', '', 34, 0),
(35, 'estimate_rejected', 'estimate', 0, 0, 0, '', '', '', 35, 0),
(36, 'new_message_sent', 'message', 0, 0, 0, '', '', '', 36, 0),
(37, 'message_reply_sent', 'message', 0, 0, 0, '', '', '', 37, 0),
(38, 'invoice_payment_confirmation', 'invoice', 0, 0, 0, '', '', '', 22, 0),
(39, 'new_event_added_in_calendar', 'event', 0, 0, 0, '', '', '', 39, 0),
(40, 'recurring_invoice_created_vai_cron_job', 'invoice', 0, 0, 0, '', '', '', 22, 0),
(41, 'new_announcement_created', 'announcement', 0, 0, 0, '', '', 'recipient', 41, 0),
(42, 'invoice_due_reminder_before_due_date', 'invoice', 0, 0, 0, '', '', '', 22, 0),
(43, 'invoice_overdue_reminder', 'invoice', 0, 0, 0, '', '', '', 22, 0),
(44, 'recurring_invoice_creation_reminder', 'invoice', 0, 0, 0, '', '', '', 22, 0),
(45, 'project_completed', 'project', 0, 0, 0, '', '', '', 2, 0),
(46, 'lead_created', 'lead', 0, 0, 0, '', '', '', 21, 0),
(47, 'client_created_from_lead', 'lead', 0, 0, 0, '', '', '', 21, 0),
(48, 'project_task_deadline_pre_reminder', 'project', 0, 1, 0, '', '', 'task_assignee', 20, 0),
(49, 'project_task_reminder_on_the_day_of_deadline', 'project', 0, 1, 0, '', '', 'task_assignee', 20, 0),
(50, 'project_task_deadline_overdue_reminder', 'project', 0, 1, 0, '', '', 'task_assignee', 20, 0),
(51, 'recurring_task_created_via_cron_job', 'project', 0, 1, 0, '', '', 'project_members,task_assignee', 20, 0),
(52, 'calendar_event_modified', 'event', 0, 0, 0, '', '', '', 39, 0),
(53, 'client_contact_requested_account_removal', 'client', 0, 0, 0, '', '', '', 21, 0),
(54, 'bitbucket_push_received', 'project', 0, 1, 0, '', '', '', 45, 0),
(55, 'github_push_received', 'project', 0, 1, 0, '', '', '', 45, 0),
(56, 'invited_client_contact_signed_up', 'client', 0, 0, 0, '', '', '', 21, 0),
(57, 'created_a_new_post', 'timeline', 0, 0, 0, '', '', '', 52, 0),
(58, 'timeline_post_commented', 'timeline', 0, 0, 0, '', '', '', 52, 0),
(59, 'ticket_assigned', 'ticket', 0, 0, 0, '', '', 'ticket_assignee', 31, 0);

-- --------------------------------------------------------

--
-- Table structure for table `overtime`
--

CREATE TABLE `overtime` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` varchar(255) DEFAULT '',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_created` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `settings` longtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `title`, `type`, `description`, `online_payable`, `available_on_invoice`, `minimum_payment_amount`, `available_on_payroll`, `created_on`, `created_by`, `settings`, `deleted`) VALUES
(1, 'Cash', 'custom', 'Cash payments', 0, 1, 0, 1, '0000-00-00 00:00:00', 0, '', 0),
(2, 'Stripe', 'stripe', 'Stripe online payments', 1, 0, 0, 0, '0000-00-00 00:00:00', 0, 'a:3:{s:15:\"pay_button_text\";s:6:\"Stripe\";s:10:\"secret_key\";s:6:\"\";s:15:\"publishable_key\";s:6:\"\";}', 0),
(3, 'PayPal Payments Standard', 'paypal_payments_standard', 'PayPal Payments Standard Online Payments', 1, 0, 0, 0, '0000-00-00 00:00:00', 0, 'a:4:{s:15:\"pay_button_text\";s:6:\"PayPal\";s:5:\"email\";s:4:\"\";s:11:\"paypal_live\";s:1:\"0\";s:5:\"debug\";s:1:\"0\";}', 0),
(4, 'Bank Transfer', 'custom', '<p>BDO, BPI, ETC</p>', 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 'a:0:{}', 0),
(5, 'GCash', 'custom', '', 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 'a:0:{}', 0);

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
  `department` int(11) NOT NULL,
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
  `lates` decimal(10,2) NOT NULL,
  `overbreak` decimal(10,2) NOT NULL,
  `undertime` decimal(10,2) NOT NULL,
  `reg_nd` decimal(10,2) NOT NULL,
  `rest_nd` decimal(10,2) NOT NULL,
  `legal_nd` decimal(10,2) NOT NULL,
  `spcl_nd` decimal(10,2) NOT NULL,
  `reg_ot` decimal(10,2) NOT NULL,
  `rest_ot` decimal(10,2) NOT NULL,
  `legal_ot` decimal(10,2) NOT NULL,
  `spcl_ot` decimal(10,2) NOT NULL,
  `reg_ot_nd` decimal(10,2) NOT NULL,
  `rest_ot_nd` decimal(10,2) NOT NULL,
  `legal_ot_nd` decimal(10,2) NOT NULL,
  `spcl_ot_nd` decimal(10,2) NOT NULL,
  `sss` decimal(10,2) NOT NULL,
  `pagibig` decimal(10,2) NOT NULL,
  `phealth` decimal(10,2) NOT NULL,
  `hmo` decimal(10,2) NOT NULL,
  `com_loan` decimal(10,2) NOT NULL,
  `sss_loan` decimal(10,2) NOT NULL,
  `hdmf_loan` decimal(10,2) NOT NULL,
  `deduct_adjust` decimal(10,2) NOT NULL,
  `deduct_other` decimal(10,2) NOT NULL,
  `allowance` decimal(10,2) NOT NULL,
  `incentive` decimal(10,2) NOT NULL,
  `bonus_month` decimal(10,2) NOT NULL,
  `month13th` decimal(10,2) NOT NULL,
  `pto` decimal(10,2) NOT NULL,
  `add_adjust` decimal(10,2) NOT NULL,
  `add_other` decimal(10,3) NOT NULL,
  `signed_by` int(11) DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` int(11) DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
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

--
-- Dumping data for table `project_files`
--

INSERT INTO `project_files` (`id`, `file_name`, `file_id`, `service_type`, `description`, `file_size`, `created_at`, `project_id`, `uploaded_by`, `deleted`) VALUES
(1, '_file62bd74b480dd9-283978476_814497406604075_5609871194260571618_n.png', '', '', 'Payslip template from BSEI', 10996, '2022-06-30 10:02:28', 1, 1, 0),
(2, '_file62c4005c899fa-SOP-Arrival-from-Delivery.docx', '', '', 'from sir Aleck', 214146, '2022-07-05 09:11:56', 1, 1, 1),
(3, '_file62c40062b5571-SOP-Scheduling-of-Release.docx', '', '', 'from sir Aleck', 210565, '2022-07-05 09:12:02', 1, 1, 1),
(4, '_file62c400816d2b1-SOP-Arrival-from-Delivery.docx', '', '', 'STANDARD PROCEDURE OF WAREHOUSE', 214146, '2022-07-05 09:12:33', 1, 1, 0),
(5, '_file62c4008711c91-SOP-Scheduling-of-Release.docx', '', '', 'STANDARD PROCEDURE OF WAREHOUSE', 210565, '2022-07-05 09:12:39', 1, 1, 0),
(6, '_file62c4008c759ef-SOP-Return-Policy.docx', '', '', 'STANDARD PROCEDURE OF WAREHOUSE', 424414, '2022-07-05 09:12:44', 1, 1, 0),
(7, '_file62c40091a2d58-SOP-Status-of-all-Vehicles-of-BSEI.docx', '', '', 'STANDARD PROCEDURE OF WAREHOUSE', 793891, '2022-07-05 09:12:49', 1, 1, 0),
(8, '_file62c400980b454-SOP-Preparing-Loading-of-Products.docx', '', '', 'STANDARD PROCEDURE OF WAREHOUSE', 1604837, '2022-07-05 09:12:56', 1, 1, 0),
(9, '_file62c4009dedda2-SOP-Unloading-of-Products.docx', '', '', 'STANDARD PROCEDURE OF WAREHOUSE', 525250, '2022-07-05 09:13:01', 1, 1, 0),
(10, '_file62c400c4726ba-PRODUCT-LIST--1-.pdf', '', '', 'SALES WEBSITE LISTS', 32586, '2022-07-05 09:13:40', 1, 1, 0);

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
  `total` decimal(10,2) NOT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` decimal(20,2) NOT NULL DEFAULT 0.00,
  `labels` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` int(4) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services_categories`
--

CREATE TABLE `services_categories` (
  `id` bigint(10) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `active` int(4) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
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
('accepted_file_formats', 'txt,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx,pdf', 'display', 0),
('allow_partial_invoice_payment_from_clients', '1', 'app', 0),
('allowed_ip_addresses', '', 'app', 0),
('apis_galyon_app', '1', 'modules', 0),
('apis_galyon_web', '1', 'modules', 0),
('apis_makeid', '1', 'modules', 0),
('apis_payhp', '1', 'modules', 0),
('apis_syntry', '1', 'modules', 0),
('app_title', 'Brilliant Skin Essentials Inc.', 'app', 0),
('attendance_calc_mode', 'simple', 'finance', 0),
('auto_clockout', '1', 'calendar', 0),
('biweekly_tax_table', 'a:6:{i:0;a:5:{i:0;i:1;i:1;s:1:\"0\";i:2;s:5:\"10416\";i:3;s:1:\"0\";i:4;s:1:\"0\";}i:1;a:5:{i:0;i:2;i:1;s:5:\"10417\";i:2;s:5:\"16666\";i:3;s:1:\"0\";i:4;s:3:\"0.2\";}i:2;a:5:{i:0;i:3;i:1;s:5:\"16667\";i:2;s:5:\"33332\";i:3;s:4:\"1250\";i:4;s:4:\"0.25\";}i:3;a:5:{i:0;i:4;i:1;s:5:\"33333\";i:2;s:5:\"83332\";i:3;s:7:\"5416.67\";i:4;s:3:\"0.3\";}i:4;a:5:{i:0;i:5;i:1;s:5:\"83333\";i:2;s:6:\"333332\";i:3;s:8:\"20416.67\";i:4;s:4:\"0.32\";}i:5;a:5:{i:0;i:6;i:1;s:6:\"333333\";i:2;s:9:\"999999999\";i:3;s:9:\"100416.67\";i:4;s:4:\"0.35\";}}', 'payroll', 0),
('breaktime_tracking', '1', 'calendar', 0),
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
('client_default_dashboard', 'a:5:{i:0;O:8:\"stdClass\":2:{s:7:\"columns\";a:4:{i:0;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:14:\"total_projects\";s:5:\"title\";s:14:\"Total projects\";}}i:1;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:14:\"total_invoices\";s:5:\"title\";s:14:\"Total invoices\";}}i:2;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:14:\"total_payments\";s:5:\"title\";s:14:\"Total payments\";}}i:3;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:9:\"total_due\";s:5:\"title\";s:9:\"Total due\";}}}s:5:\"ratio\";s:7:\"3-3-3-3\";}i:1;O:8:\"stdClass\":2:{s:7:\"columns\";a:4:{i:0;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:12:\"events_today\";s:5:\"title\";s:12:\"Events today\";}}i:1;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:12:\"open_tickets\";s:5:\"title\";s:12:\"Open tickets\";}}i:2;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:11:\"new_tickets\";s:5:\"title\";s:11:\"New Tickets\";}}i:3;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:14:\"closed_tickets\";s:5:\"title\";s:14:\"Closed Tickets\";}}}s:5:\"ratio\";s:7:\"3-3-3-3\";}i:2;O:8:\"stdClass\":2:{s:7:\"columns\";a:2:{i:0;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:18:\"invoice_statistics\";s:5:\"title\";s:18:\"Invoice Statistics\";}}i:1;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:20:\"draft_invoices_value\";s:5:\"title\";s:20:\"Draft invoices value\";}}}s:5:\"ratio\";s:3:\"9-3\";}i:3;O:8:\"stdClass\":2:{s:7:\"columns\";a:2:{i:0;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:9:\"todo_list\";s:5:\"title\";s:9:\"Todo list\";}}i:1;a:2:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:11:\"sticky_note\";s:5:\"title\";s:21:\"Sticky Note (Private)\";}i:1;O:8:\"stdClass\":2:{s:6:\"widget\";s:6:\"events\";s:5:\"title\";s:6:\"Events\";}}}s:5:\"ratio\";s:3:\"6-6\";}i:4;O:8:\"stdClass\":2:{s:7:\"columns\";a:1:{i:0;a:1:{i:0;O:8:\"stdClass\":2:{s:6:\"widget\";s:18:\"open_projects_list\";s:5:\"title\";s:18:\"Open Projects List\";}}}s:5:\"ratio\";s:2:\"12\";}}', 'app', 0),
('client_message_own_contacts', '', 'app', 0),
('client_message_users', '', 'app', 0),
('company_address', '35 J. Sta. Catalina St., Sitio Caingin, Morong, 1960 Rizal', 'company', 0),
('company_email', 'contact@brilliantskinessentials.ph', 'company', 0),
('company_name', 'Brilliant Skin Essentials Inc.', 'company', 0),
('company_phone', '022136461', 'company', 0),
('company_vat_number', '', 'company', 0),
('company_website', 'https://brilliantskinessentials.ph', 'company', 0),
('create_new_projects_automatically_when_estimates_gets_accepted', '', 'app', 0),
('currency_position', 'left', 'finance', 0),
('currency_symbol', 'P ', 'finance', 0),
('daily_tax_table', 'a:6:{i:0;a:5:{i:0;i:1;i:1;s:1:\"0\";i:2;s:3:\"684\";i:3;s:1:\"0\";i:4;s:1:\"0\";}i:1;a:5:{i:0;i:2;i:1;s:3:\"685\";i:2;s:4:\"1095\";i:3;s:1:\"0\";i:4;s:3:\"0.2\";}i:2;a:5:{i:0;i:3;i:1;s:4:\"1096\";i:2;s:4:\"2191\";i:3;s:5:\"82.19\";i:4;s:4:\"0.25\";}i:3;a:5:{i:0;i:4;i:1;s:4:\"2192\";i:2;s:4:\"5478\";i:3;s:6:\"356.16\";i:4;s:3:\"0.3\";}i:4;a:5:{i:0;i:5;i:1;s:4:\"5479\";i:2;s:5:\"21917\";i:3;s:7:\"1342.47\";i:4;s:4:\"0.32\";}i:5;a:5:{i:0;i:6;i:1;s:5:\"21918\";i:2;s:9:\"999999999\";i:3;s:7:\"6602.74\";i:4;s:4:\"0.35\";}}', 'payroll', 0),
('date_format', 'd/m/Y', 'calendar', 0),
('decimal_separator', '.', 'finance', 0),
('default_currency', 'PHP', 'finance', 0),
('default_due_date_after_billing_date', '', 'app', 0),
('default_left_menu', 'a:81:{i:0;a:1:{s:4:\"name\";s:9:\"dashboard\";}i:1;a:4:{s:4:\"name\";s:4:\"Home\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:4:\"home\";s:15:\"open_in_new_tab\";s:0:\"\";}i:2;a:2:{s:4:\"name\";s:8:\"timeline\";s:11:\"is_sub_menu\";s:1:\"1\";}i:3;a:2:{s:4:\"name\";s:13:\"announcements\";s:11:\"is_sub_menu\";s:1:\"1\";}i:4;a:2:{s:4:\"name\";s:6:\"events\";s:11:\"is_sub_menu\";s:1:\"1\";}i:5;a:2:{s:4:\"name\";s:4:\"todo\";s:11:\"is_sub_menu\";s:1:\"1\";}i:6;a:2:{s:4:\"name\";s:5:\"notes\";s:11:\"is_sub_menu\";s:1:\"1\";}i:7;a:2:{s:4:\"name\";s:8:\"messages\";s:11:\"is_sub_menu\";s:1:\"1\";}i:8;a:4:{s:4:\"name\";s:14:\"Human Resource\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:8:\"suitcase\";s:15:\"open_in_new_tab\";s:0:\"\";}i:9;a:2:{s:4:\"name\";s:8:\"payrolls\";s:11:\"is_sub_menu\";s:1:\"1\";}i:10;a:2:{s:4:\"name\";s:20:\"submenu_hrm_employee\";s:11:\"is_sub_menu\";s:1:\"1\";}i:11;a:2:{s:4:\"name\";s:22:\"submenu_hrm_department\";s:11:\"is_sub_menu\";s:1:\"1\";}i:12;a:2:{s:4:\"name\";s:20:\"submenu_hrm_schedule\";s:11:\"is_sub_menu\";s:1:\"1\";}i:13;a:2:{s:4:\"name\";s:22:\"submenu_hrm_attendance\";s:11:\"is_sub_menu\";s:1:\"1\";}i:14;a:2:{s:4:\"name\";s:20:\"submenu_hrm_overtime\";s:11:\"is_sub_menu\";s:1:\"1\";}i:15;a:2:{s:4:\"name\";s:24:\"submenu_hrm_disciplinary\";s:11:\"is_sub_menu\";s:1:\"1\";}i:16;a:2:{s:4:\"name\";s:18:\"submenu_hrm_leaves\";s:11:\"is_sub_menu\";s:1:\"1\";}i:17;a:2:{s:4:\"name\";s:20:\"submenu_hrm_holidays\";s:11:\"is_sub_menu\";s:1:\"1\";}i:18;a:4:{s:4:\"name\";s:13:\"Manufacturing\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:4:\"fire\";s:15:\"open_in_new_tab\";s:0:\"\";}i:19;a:2:{s:4:\"name\";s:23:\"manufacturing_order\";s:11:\"is_sub_menu\";s:1:\"1\";}i:20;a:2:{s:4:\"name\";s:27:\"bill_of_materials\";s:11:\"is_sub_menu\";s:1:\"1\";}i:21;a:2:{s:4:\"name\";s:21:\"submenu_pid_materials\";s:11:\"is_sub_menu\";s:1:\"1\";}i:22;a:2:{s:4:\"name\";s:19:\"submenu_pid_process\";s:11:\"is_sub_menu\";s:1:\"1\";}i:23;a:2:{s:4:\"name\";s:17:\"submenu_pid_units\";s:11:\"is_sub_menu\";s:1:\"1\";}i:24;a:4:{s:4:\"name\";s:9:\"Warehouse\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:4:\"bank\";s:15:\"open_in_new_tab\";s:0:\"\";}i:25;a:2:{s:4:\"name\";s:21:\"submenu_lms_warehouse\";s:11:\"is_sub_menu\";s:1:\"1\";}i:26;a:2:{s:4:\"name\";s:19:\"submenu_lms_pallets\";s:11:\"is_sub_menu\";s:1:\"1\";}i:27;a:2:{s:4:\"name\";s:17:\"submenu_lms_zones\";s:11:\"is_sub_menu\";s:1:\"1\";}i:28;a:2:{s:4:\"name\";s:17:\"submenu_lms_racks\";s:11:\"is_sub_menu\";s:1:\"1\";}i:29;a:2:{s:4:\"name\";s:16:\"submenu_lms_bays\";s:11:\"is_sub_menu\";s:1:\"1\";}i:30;a:2:{s:4:\"name\";s:18:\"submenu_lms_levels\";s:11:\"is_sub_menu\";s:1:\"1\";}i:31;a:2:{s:4:\"name\";s:21:\"submenu_lms_transfers\";s:11:\"is_sub_menu\";s:1:\"1\";}i:32;a:2:{s:4:\"name\";s:21:\"submenu_pid_purchases\";s:11:\"is_sub_menu\";s:1:\"1\";}i:33;a:2:{s:4:\"name\";s:19:\"submenu_pid_returns\";s:11:\"is_sub_menu\";s:1:\"1\";}i:34;a:2:{s:4:\"name\";s:20:\"submenu_pid_supplier\";s:11:\"is_sub_menu\";s:1:\"1\";}i:35;a:4:{s:4:\"name\";s:10:\"Accounting\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:13:\"cc-mastercard\";s:15:\"open_in_new_tab\";s:0:\"\";}i:36;a:2:{s:4:\"name\";s:19:\"submenu_fas_summary\";s:11:\"is_sub_menu\";s:1:\"1\";}i:37;a:2:{s:4:\"name\";s:22:\"submenu_fas_incentives\";s:11:\"is_sub_menu\";s:1:\"1\";}i:38;a:2:{s:4:\"name\";s:25:\"submenu_fas_contributions\";s:11:\"is_sub_menu\";s:1:\"1\";}i:39;a:2:{s:4:\"name\";s:20:\"submenu_fas_payments\";s:11:\"is_sub_menu\";s:1:\"1\";}i:40;a:2:{s:4:\"name\";s:20:\"submenu_fas_expenses\";s:11:\"is_sub_menu\";s:1:\"1\";}i:41;a:2:{s:4:\"name\";s:5:\"taxes\";s:11:\"is_sub_menu\";s:1:\"1\";}i:42;a:2:{s:4:\"name\";s:21:\"submenu_fas_transfers\";s:11:\"is_sub_menu\";s:1:\"1\";}i:43;a:2:{s:4:\"name\";s:20:\"submenu_fas_accounts\";s:11:\"is_sub_menu\";s:1:\"1\";}i:44;a:4:{s:4:\"name\";s:9:\"Logistics\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:5:\"truck\";s:15:\"open_in_new_tab\";s:0:\"\";}i:45;a:2:{s:4:\"name\";s:20:\"submenu_lms_delivery\";s:11:\"is_sub_menu\";s:1:\"1\";}i:46;a:2:{s:4:\"name\";s:20:\"submenu_lms_vehicles\";s:11:\"is_sub_menu\";s:1:\"1\";}i:47;a:2:{s:4:\"name\";s:19:\"submenu_lms_drivers\";s:11:\"is_sub_menu\";s:1:\"1\";}i:48;a:4:{s:4:\"name\";s:5:\"Sales\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:9:\"cart-plus\";s:15:\"open_in_new_tab\";s:0:\"\";}i:49;a:2:{s:4:\"name\";s:23:\"submenu_sms_salesmatrix\";s:11:\"is_sub_menu\";s:1:\"1\";}i:50;a:2:{s:4:\"name\";s:20:\"submenu_sms_invoices\";s:11:\"is_sub_menu\";s:1:\"1\";}i:51;a:2:{s:4:\"name\";s:21:\"submenu_sms_estimates\";s:11:\"is_sub_menu\";s:1:\"1\";}i:52;a:2:{s:4:\"name\";s:18:\"stores\";s:11:\"is_sub_menu\";s:1:\"1\";}i:53;a:2:{s:4:\"name\";s:20:\"submenu_pid_products\";s:11:\"is_sub_menu\";s:1:\"1\";}i:54;a:2:{s:4:\"name\";s:20:\"submenu_sms_services\";s:11:\"is_sub_menu\";s:1:\"1\";}i:55;a:2:{s:4:\"name\";s:21:\"submenu_lms_consumers\";s:11:\"is_sub_menu\";s:1:\"1\";}i:56;a:2:{s:4:\"name\";s:21:\"submenu_sms_customers\";s:11:\"is_sub_menu\";s:1:\"1\";}i:57;a:2:{s:4:\"name\";s:19:\"clients\";s:11:\"is_sub_menu\";s:1:\"1\";}i:58;a:4:{s:4:\"name\";s:8:\"Safekeep\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:4:\"lock\";s:15:\"open_in_new_tab\";s:0:\"\";}i:59;a:2:{s:4:\"name\";s:18:\"asset_entries\";s:11:\"is_sub_menu\";s:1:\"1\";}i:60;a:2:{s:4:\"name\";s:20:\"asset_categories\";s:11:\"is_sub_menu\";s:1:\"1\";}i:61;a:2:{s:4:\"name\";s:20:\"asset_location\";s:11:\"is_sub_menu\";s:1:\"1\";}i:62;a:2:{s:4:\"name\";s:19:\"asset_vendor\";s:11:\"is_sub_menu\";s:1:\"1\";}i:63;a:2:{s:4:\"name\";s:17:\"asset_brand\";s:11:\"is_sub_menu\";s:1:\"1\";}i:64;a:4:{s:4:\"name\";s:9:\"Marketing\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:10:\"line-chart\";s:15:\"open_in_new_tab\";s:0:\"\";}i:65;a:2:{s:4:\"name\";s:5:\"epass\";s:11:\"is_sub_menu\";s:1:\"1\";}i:66;a:2:{s:4:\"name\";s:11:\"raffle_draw\";s:11:\"is_sub_menu\";s:1:\"1\";}i:67;a:2:{s:4:\"name\";s:17:\"submenu_mcs_leads\";s:11:\"is_sub_menu\";s:1:\"1\";}i:68;a:2:{s:4:\"name\";s:18:\"submenu_mcs_status\";s:11:\"is_sub_menu\";s:1:\"1\";}i:69;a:2:{s:4:\"name\";s:18:\"submenu_mcs_source\";s:11:\"is_sub_menu\";s:1:\"1\";}i:70;a:4:{s:4:\"name\";s:11:\"Help Center\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:9:\"life-ring\";s:15:\"open_in_new_tab\";s:0:\"\";}i:71;a:2:{s:4:\"name\";s:7:\"tickets\";s:11:\"is_sub_menu\";s:1:\"1\";}i:72;a:2:{s:4:\"name\";s:15:\"help_page_title\";s:11:\"is_sub_menu\";s:1:\"1\";}i:73;a:2:{s:4:\"name\";s:14:\"knowledge_base\";s:11:\"is_sub_menu\";s:1:\"1\";}i:74;a:2:{s:4:\"name\";s:5:\"pages\";s:11:\"is_sub_menu\";s:1:\"1\";}i:75;a:4:{s:4:\"name\";s:8:\"Planning\";s:3:\"url\";s:1:\"#\";s:4:\"icon\";s:11:\"paper-plane\";s:15:\"open_in_new_tab\";s:0:\"\";}i:76;a:2:{s:4:\"name\";s:24:\"submenu_pms_all_projects\";s:11:\"is_sub_menu\";s:1:\"1\";}i:77;a:2:{s:4:\"name\";s:23:\"submenu_pms_view_gantts\";s:11:\"is_sub_menu\";s:1:\"1\";}i:78;a:2:{s:4:\"name\";s:20:\"submenu_pms_my_tasks\";s:11:\"is_sub_menu\";s:1:\"1\";}i:79;a:2:{s:4:\"name\";s:22:\"submenu_pms_timesheets\";s:11:\"is_sub_menu\";s:1:\"1\";}i:80;a:1:{s:4:\"name\";s:8:\"settings\";}}', 'app', 0),
('default_theme_color', 'DE78B3', 'display', 0),
('disable_access_favorite_project_option_for_clients', '', 'app', 0),
('disable_client_login', '', 'app', 0),
('disable_client_signup', '1', 'app', 0),
('disable_dashboard_customization_by_clients', '', 'app', 0),
('disable_editing_left_menu_by_clients', '', 'app', 0),
('disable_topbar_menu_customization', '', 'app', 0),
('disable_user_invitation_option_by_clients', '', 'app', 0),
('email_protocol', 'smtp', 'app', 0),
('email_sent_from_address', 'system@brilliantskinessentialsinc.com', 'app', 0),
('email_sent_from_name', 'Brilliant Skin Essentials Inc.', 'app', 0),
('email_smtp_host', 'smtp.mailgun.org', 'app', 0),
('email_smtp_pass', 'c3b8c2fad78548dd555ae351bcb15a3424092b518ed0f1f7ff163df0ac626b9bf25095bdd2a6c215d95a0e5c301fc3fd461f56474d35aafc0d3b84595529a3621NoJ9chX5I6Z0Mp_bVUSYymYruMCxFUFiNNOlYN2qZv5Tg9Crzech8DqghUuBk7UiliYJfBkiFP205TcIbYq3zTJ_UPRH3QEvj51-MG_WtSRoxy27Z7O8r-PmjZZsKEI', 'app', 0),
('email_smtp_port', '587', 'app', 0),
('email_smtp_security_type', 'tls', 'app', 0),
('email_smtp_user', 'system@brilliantskinessentialsinc.com', 'app', 0),
('enable_chat_via_pusher', '1', 'app', 0),
('enable_footer', '', 'app', 0),
('enable_google_calendar_api', '1', 'app', 0),
('enable_google_drive_api_to_upload_file', '', 'app', 0),
('enable_push_notification', '1', 'app', 0),
('enable_recurring_option_for_tasks', '1', 'app', 0),
('enable_rich_text_editor', '1', 'display', 0),
('estimate_color', '', 'app', 0),
('estimate_footer', '<p><br></p>', 'app', 0),
('estimate_logo', 'a:1:{s:9:\"file_name\";s:36:\"_file63e7eb72e930e-estimate-logo.png\";}', 'app', 0),
('estimate_prefix', '', 'app', 0),
('favicon', 'a:1:{s:9:\"file_name\";s:30:\"_file63e7eb55d7f1b-favicon.png\";}', 'general', 0),
('first_day_of_week', '1', 'calendar', 0),
('footer_copyright_text', 'Copyright © 2020 - Bytes Crafter', 'app', 0),
('footer_menus', 'a:1:{i:0;O:8:\"stdClass\":2:{s:9:\"menu_name\";s:5:\"asdas\";s:3:\"url\";s:34:\"https://brilliantskinessentials.ph\";}}', 'app', 0),
('google_drive_authorized', '1', 'app', 0),
('google_drive_client_id', '101960398024-b84q52m7mdv3rnli6m7ajt3b85bk1l5r.apps.googleusercontent.com', 'app', 0),
('google_drive_client_secret', 'RBMS9m-NKv5p9Qr_atjGtf7_', 'app', 0),
('google_drive_oauth_access_token', '{\"access_token\":\"ya29.a0AfH6SMBcZUrd4b2-uXMK6A0Qi2UFrxkaCJ2rAlvHdHXn5WV1BWPox1vWiL7QymBmuknCw56U5l5jrngMmXq-RjqoHEFOx_X1pOkk8Xg90AhqoIFvYtum1iJJ_FfDgga0zDv2fKuhsOxx99iBOku4NJWmigi6\",\"expires_in\":3599,\"refresh_token\":\"1\\/\\/0g87QeRAKQAtcCgYIARAAGBASNwF-L9IrPZt2KJ3qKCBhSqSkmN6-1mk2cWwZWMIyQQ-sU-zWfIaeuPYuULzbrpkBrmdTDW7tKwU\",\"scope\":\"https:\\/\\/www.googleapis.com\\/auth\\/drive\",\"token_type\":\"Bearer\",\"created\":1617646205}', 'app', 0),
('hidden_client_menus', '', 'app', 0),
('inactive_ticket_closing_date', '2023-02-06', 'app', 0),
('initial_number_of_the_estimate', '2', 'app', 0),
('initial_number_of_the_invoice', '4', 'app', 0),
('invoice_color', '', 'app', 0),
('invoice_footer', '<p><br></p>', 'app', 0),
('invoice_logo', 'a:1:{s:9:\"file_name\";s:35:\"_file63e7eb7db718c-invoice-logo.png\";}', 'app', 0),
('invoice_prefix', '', 'app', 0),
('invoice_style', 'style_1', 'app', 0),
('invoice_terms', '', 'app', 0),
('invoice_warranty', '', 'app', 0),
('language', 'english', 'general', 0),
('last_check_fix', '2023-01-30 17:16:10', 'app', 0),
('last_cron_job_time', '1675698602', 'app', 0),
('last_hourly_job_time', '1675697401', 'app', 0),
('last_minutely_job_time', '1675698602', 'app', 0),
('module_accounts', '1', 'modules', 0),
('module_allprojects', '1', 'app', 0),
('module_ams_category', '1', 'app', 0),
('module_ams_location', '1', 'app', 0),
('module_announcement', '1', 'app', 0),
('module_asset_category', '1', 'modules', 0),
('module_assets', '1', 'app', 0),
('module_ats', '1', 'app', 0),
('module_attendance', '1', 'app', 0),
('module_balancesheet', '1', 'modules', 0),
('module_billofmaterials', '1', 'modules', 0),
('module_brands', '1', 'app', 0),
('module_chat', '1', 'app', 0),
('module_clients', '1', 'app', 0),
('module_consumer', '1', 'modules', 0),
('module_css', '1', 'app', 0),
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
('module_fas_accounts', '1', 'app', 0),
('module_fas_balancesheet', '1', 'app', 0),
('module_fas_contributions', '1', 'app', 0),
('module_fas_payments', '1', 'app', 0),
('module_fas_payroll', '1', 'app', 0),
('module_fas_summary', '1', 'app', 0),
('module_fas_transfer', '1', 'app', 0),
('module_gantt', '1', 'app', 0),
('module_help', '1', 'modules', 0),
('module_holidays', '1', 'modules', 0),
('module_hrm_department', '1', 'app', 0),
('module_hrm_disciplinary', '1', 'app', 0),
('module_hrm_employee', '1', 'app', 0),
('module_hrm_holidays', '1', 'app', 0),
('module_inventory', '1', 'modules', 0),
('module_invoice', '1', 'app', 0),
('module_item_transfer', '1', 'modules', 0),
('module_knowledge_base', '1', 'app', 0),
('module_lead', '1', 'app', 0),
('module_leave', '1', 'app', 0),
('module_lms_consumer', '1', 'app', 0),
('module_lms_delivery', '1', 'app', 0),
('module_lms_driver', '1', 'app', 0),
('module_lms_transfer', '1', 'app', 0),
('module_lms_vehicles', '1', 'app', 0),
('module_lms_warehouse', '1', 'app', 0),
('module_location', '1', 'modules', 0),
('module_message', '1', 'app', 0),
('module_mytask', '1', 'app', 0),
('module_note', '1', 'app', 0),
('module_overtime', '1', 'app', 0),
('module_page', '1', 'modules', 0),
('module_payments', '1', 'modules', 0),
('module_payroll', '1', 'modules', 0),
('module_pid_billofmaterials', '1', 'app', 0),
('module_pid_inventory', '1', 'app', 0),
('module_pid_productions', '1', 'app', 0),
('module_pid_products', '1', 'app', 0),
('module_pid_purchases', '1', 'app', 0),
('module_pid_rawmaterials', '1', 'app', 0),
('module_pid_returns', '1', 'app', 0),
('module_pid_supplier', '1', 'app', 0),
('module_productions', '1', 'modules', 0),
('module_products', '1', 'modules', 0),
('module_project_timesheet', '1', 'app', 0),
('module_purchases', '1', 'modules', 0),
('module_raffle', '1', 'modules', 0),
('module_rawmaterials', '1', 'modules', 0),
('module_returns', '1', 'modules', 0),
('module_sales_matrix', '1', 'modules', 0),
('module_schedule', '1', 'modules', 0),
('module_services', '1', 'app', 0),
('module_sms_coupons', '', 'app', 0),
('module_sms_customers', '1', 'app', 0),
('module_sms_giftcard', '', 'app', 0),
('module_sms_pos', '', 'app', 0),
('module_sms_sales_matrix', '1', 'app', 0),
('module_summary', '1', 'modules', 0),
('module_supplier', '1', 'modules', 0),
('module_ticket', '1', 'app', 0),
('module_timeline', '1', 'app', 0),
('module_todo', '1', 'app', 0),
('module_transfer', '1', 'modules', 0),
('module_vehicles', '1', 'modules', 0),
('module_vendors', '1', 'app', 0),
('module_warehouse', '1', 'modules', 0),
('monthly_tax_table', 'a:6:{i:0;a:5:{i:0;i:1;i:1;s:1:\"0\";i:2;s:5:\"20832\";i:3;s:1:\"0\";i:4;s:1:\"0\";}i:1;a:5:{i:0;i:2;i:1;s:5:\"20833\";i:2;s:5:\"33332\";i:3;s:1:\"0\";i:4;s:3:\"0.2\";}i:2;a:5:{i:0;i:3;i:1;s:5:\"33333\";i:2;s:5:\"66666\";i:3;s:4:\"2500\";i:4;s:4:\"0.25\";}i:3;a:5:{i:0;i:4;i:1;s:5:\"66667\";i:2;s:6:\"166666\";i:3;s:8:\"10833.33\";i:4;s:3:\"0.3\";}i:4;a:5:{i:0;i:5;i:1;s:6:\"166667\";i:2;s:6:\"666666\";i:3;s:8:\"40833.33\";i:4;s:4:\"0.32\";}i:5;a:5:{i:0;i:6;i:1;s:6:\"666667\";i:2;s:9:\"999999999\";i:3;s:9:\"200833.33\";i:4;s:4:\"0.35\";}}', 'payroll', 0),
('name_format', 'lastfirst', 'display', 0),
('no_of_decimals', '2', 'finance', 0),
('project_task_deadline_overdue_reminder', '1', 'app', 0),
('project_task_deadline_pre_reminder', '', 'app', 0),
('project_task_reminder_on_the_day_of_deadline', '1', 'app', 0),
('pusher_app_id', '1177201', 'app', 0),
('pusher_cluster', 'ap1', 'app', 0),
('pusher_key', 'bc1296ac6df7f2795491', 'app', 0),
('pusher_secret', '6383618ccc84e6a9bf0c', 'app', 0),
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
('show_background_image_in_signin_page', 'no', 'general', 0),
('show_logo_in_signin_page', 'yes', 'general', 0),
('show_theme_color_changer', 'no', 'display', 0),
('signin_page_background', 'sigin-background-image.jpg', 'app', 0),
('site_logo', 'a:1:{s:9:\"file_name\";s:32:\"_file63e7eb55d6caf-site-logo.png\";}', 'general', 0),
('site_title', 'Brilliant Skin Essentials Inc.', 'general', 0),
('task_point_range', '5', 'app', 0),
('time_format', 'capital', 'calendar', 0),
('timezone', 'Asia/Manila', 'calendar', 0),
('user_1_dashboard', '', 'user', 0),
('user_1_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_100_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_101_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_102_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_103_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_104_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_105_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_106_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_107_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_108_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_109_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_110_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_111_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_112_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_113_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_114_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_115_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_116_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_117_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"96.5083\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_118_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_119_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_12_dashboard', '', 'user', 0),
('user_120_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_121_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_122_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_123_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_124_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_125_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_126_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_127_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_128_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_129_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_13_dashboard', '', 'user', 0),
('user_130_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_131_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_132_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_133_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_134_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_135_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_136_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_137_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_138_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_139_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_14_dashboard', '', 'user', 0),
('user_140_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_141_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_142_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_143_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_144_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_145_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_146_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_147_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_148_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_149_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_150_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0);
INSERT INTO `settings` (`setting_name`, `setting_value`, `type`, `deleted`) VALUES
('user_151_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_152_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_153_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_154_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_155_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_156_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_157_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_158_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_159_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_160_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_161_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_162_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_163_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_164_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_165_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_166_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_167_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_168_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_169_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_170_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_171_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_172_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"96.1953\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_173_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_174_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_175_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_177_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_178_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_179_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_180_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_181_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_182_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_183_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_184_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_185_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_186_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_187_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_188_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_189_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_190_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_191_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_192_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_193_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_194_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"98.6993\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_196_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_197_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_198_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_199_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_2_dashboard', '', 'user', 0),
('user_2_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_200_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_201_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_202_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_203_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_204_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"107.4633\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_205_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_206_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_207_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_208_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_209_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_21_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;s:5:\"78.75\";i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;s:1:\"0\";i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_210_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_211_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_212_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_213_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_214_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_215_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_216_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"107.4633\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_217_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_218_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_219_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_220_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"108.5067\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_221_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.3333\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_222_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_223_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_224_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_225_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"107.4633\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_226_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:281.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"311.5393\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_227_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:241.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"213.0904\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_228_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:275.625;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"245.3920\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_229_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:281.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"250.0035\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_230_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:281.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"260.0404\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_231_dashboard', '', 'user', 0),
('user_231_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:213.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"191.5143\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_232_dashboard', '', 'user', 0),
('user_232_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:180;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"155.9783\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_233_dashboard', '', 'user', 0),
('user_233_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:157.5;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"136.1550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_234_dashboard', '', 'user', 0),
('user_234_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_235_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:163.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"141.3717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_236_dashboard', '', 'user', 0),
('user_236_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:157.5;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"136.1550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_237_dashboard', '', 'user', 0),
('user_237_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:163.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"138.7633\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_238_dashboard', '', 'user', 0),
('user_238_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:157.5;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"136.1550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_239_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"115.2883\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_240_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:157.5;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"133.5467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_241_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"128.3300\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0);
INSERT INTO `settings` (`setting_name`, `setting_value`, `type`, `deleted`) VALUES
('user_242_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:140.625;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"117.8967\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_243_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"125.7217\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_244_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_245_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_246_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_247_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_248_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_249_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:140.625;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"121.2771\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_250_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"127.0154\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_251_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_252_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_253_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:281.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"311.6854\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_254_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:281.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"325.7287\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_255_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:225;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"199.9861\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_256_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:208.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"185.9429\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_257_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:213.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"190.7213\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_258_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:213.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"190.7213\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_259_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:196.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"174.9879\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_26_dashboard', '', 'user', 0),
('user_260_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:191.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"170.7311\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_261_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:174.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"150.7199\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_262_dashboard', '', 'user', 0),
('user_262_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:208.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"186.2976\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_263_dashboard', '', 'user', 0),
('user_263_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:191.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"169.5417\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_264_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"128.3300\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_265_dashboard', '', 'user', 0),
('user_265_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"128.3300\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_266_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:163.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"138.7633\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_267_dashboard', '', 'user', 0),
('user_267_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"117.3750\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_268_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_269_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"115.2883\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_27_dashboard', '', 'user', 0),
('user_270_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"115.2883\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_271_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"115.2883\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_272_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"115.2883\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_273_dashboard', '', 'user', 0),
('user_273_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_274_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:196.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"175.2800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_275_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"128.3300\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_276_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:140.625;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"120.5050\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_277_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:140.625;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"120.5050\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_278_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:281.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"258.3293\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_279_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:140.625;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"117.8967\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_280_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_281_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"115.2883\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_282_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:230.625;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"206.3087\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_283_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:281.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"488.0296\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_284_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:219.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"193.0167\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_285_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:157.5;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"136.1550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_286_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"130.9383\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_287_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"130.9383\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_288_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"130.9383\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_289_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:168.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"145.5450\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_290_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:163.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"141.8099\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_291_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:140.625;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"117.8967\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_292_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:140.625;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"117.8967\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_293_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:140.625;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"117.8967\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_295_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:196.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"172.6717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_296_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"128.3300\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_297_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_298_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_299_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_3_dashboard', '', 'user', 0),
('user_300_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_301_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_302_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:258.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"228.3439\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_303_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"117.3750\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_304_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_306_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"128.3300\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_307_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_308_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_309_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"128.3300\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_31_dashboard', '', 'user', 0),
('user_310_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"125.7217\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_311_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_312_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_313_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"125.7217\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_314_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_315_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_316_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_317_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_318_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_319_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_320_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_322_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_323_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_324_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_325_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_326_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_327_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_329_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_330_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_331_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_333_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_334_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_335_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_338_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:196.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"175.2800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_339_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"130.9383\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_3393_dashboard', '', 'user', 0),
('user_3394_dashboard', '', 'user', 0),
('user_34_dashboard', '', 'user', 0);
INSERT INTO `settings` (`setting_name`, `setting_value`, `type`, `deleted`) VALUES
('user_340_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:151.875;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"130.9383\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_3400_dashboard', '', 'user', 0),
('user_3401_dashboard', '', 'user', 0),
('user_341_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_342_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_343_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"112.6800\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_344_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"115.2883\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_345_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:135;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"115.2883\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_346_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_347_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_348_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_349_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_350_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_351_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_352_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_353_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_354_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_355_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_356_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_357_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_358_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:174.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"151.8050\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_359_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:174.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"151.8050\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_360_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:174.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"151.8050\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_361_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:174.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"151.8050\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_362_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:180;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"162.2383\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_363_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:180;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"162.2383\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_364_dashboard', '', 'user', 0),
('user_364_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:146.25;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"123.1133\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_365_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_366_dashboard', '', 'user', 0),
('user_367_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_368_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_369_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_37_dashboard', '', 'user', 0),
('user_370_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_371_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_372_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_373_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_374_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_375_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_376_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_377_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_378_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_379_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_381_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_382_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_383_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_384_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_385_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_386_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_387_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_388_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_389_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_390_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_391_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_392_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_393_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_394_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_395_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_396_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_397_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_398_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_399_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_4_dashboard', '', 'user', 0),
('user_400_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_401_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_402_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_403_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_404_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_405_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_406_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_408_dashboard', '', 'user', 0),
('user_41_dashboard', '', 'user', 0),
('user_437_dashboard', '', 'user', 0),
('user_438_dashboard', '', 'user', 0),
('user_439_dashboard', '', 'user', 0),
('user_440_dashboard', '', 'user', 0),
('user_441_dashboard', '', 'user', 0),
('user_47_dashboard', '', 'user', 0),
('user_49_dashboard', '', 'user', 0),
('user_49_disable_keyboard_shortcuts', '0', 'user', 0),
('user_49_disable_push_notification', '1', 'user', 0),
('user_49_hidden_topbar_menus', 'to_do,favorite_projects', 'user', 0),
('user_49_notification_sound_volume', '0', 'user', 0),
('user_49_personal_language', 'english', 'user', 0),
('user_5_dashboard', '', 'user', 0),
('user_50_dashboard', '', 'user', 0),
('user_51_dashboard', '', 'user', 0),
('user_52_dashboard', '', 'user', 0),
('user_54_dashboard', '', 'user', 0),
('user_56_dashboard', '', 'user', 0),
('user_57_dashboard', '', 'user', 0),
('user_6_dashboard', '', 'user', 0),
('user_60_dashboard', '', 'user', 0),
('user_60_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_61_dashboard', '', 'user', 0),
('user_61_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_61_left_menu', '', 'app', 0),
('user_62_dashboard', '', 'user', 0),
('user_62_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_63_dashboard', '', 'user', 0),
('user_64_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_65_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_66_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_67_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_68_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_69_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_7_dashboard', '', 'user', 0),
('user_70_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_71_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_72_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.8550\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_73_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_74_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_75_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_76_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_77_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_78_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_79_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_80_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_81_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_82_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:123.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"104.3333\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_83_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:7:\"96.5083\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_84_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0);
INSERT INTO `settings` (`setting_name`, `setting_value`, `type`, `deleted`) VALUES
('user_85_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:129.375;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"110.0717\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_86_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:78.75;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:6:\"0.0000\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_87_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_88_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_89_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_9_dashboard', '', 'user', 0),
('user_90_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_91_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_92_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_93_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_94_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_95_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_96_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_97_dashboard', '', 'user', 0),
('user_97_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_98_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('user_99_deductions', 'a:7:{i:0;a:5:{i:0;s:10:\"sss_contri\";i:1;d:0;i:2;d:118.125;i:3;d:0;i:4;d:0;}i:1;a:5:{i:0;s:14:\"pagibig_contri\";i:1;d:0;i:2;s:5:\"50.00\";i:3;d:0;i:4;d:0;}i:2;a:5:{i:0;s:17:\"philhealth_contri\";i:1;d:0;i:2;s:8:\"102.2467\";i:3;d:0;i:4;d:0;}i:3;a:5:{i:0;s:10:\"hmo_contri\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:4;a:5:{i:0;s:12:\"company_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:5;a:5:{i:0;s:8:\"sss_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}i:6;a:5:{i:0;s:9:\"hdmf_loan\";i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;}}', 'user', 0),
('verify_email_before_client_signup', '', 'app', 0),
('weekends', '6,0', 'calendar', 0),
('weekly_tax_table', 'a:6:{i:0;a:5:{i:0;i:1;i:1;s:1:\"0\";i:2;s:4:\"4808\";i:3;s:1:\"0\";i:4;s:1:\"0\";}i:1;a:5:{i:0;i:2;i:1;s:4:\"4808\";i:2;s:4:\"7691\";i:3;s:1:\"0\";i:4;s:3:\"0.2\";}i:2;a:5:{i:0;i:3;i:1;s:4:\"7692\";i:2;s:5:\"15384\";i:3;s:6:\"576.92\";i:4;s:4:\"0.25\";}i:3;a:5:{i:0;i:4;i:1;s:5:\"15385\";i:2;s:5:\"38461\";i:3;s:4:\"2500\";i:4;s:3:\"0.3\";}i:4;a:5:{i:0;i:5;i:1;s:5:\"38462\";i:2;s:6:\"153845\";i:3;s:7:\"9423.08\";i:4;s:4:\"0.32\";}i:5;a:5:{i:0;i:6;i:1;s:6:\"153846\";i:2;s:9:\"999999999\";i:3;s:8:\"46346.15\";i:4;s:4:\"0.35\";}}', 'payroll', 0);

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
(4, 'Cancelled', '', '#e74c3c', 4, 0),
(5, 'Planning', '', '#ad159e', 2, 0);

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
(1, 'Tax (10%)', 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `heads` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `members` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `rate_per_hour` double NOT NULL,
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

--
-- Dumping data for table `team_member_job_info`
--

INSERT INTO `team_member_job_info` (`id`, `user_id`, `job_idnum`, `rfid_num`, `sched_id`, `salary`, `salary_term`, `rate_per_hour`, `hours_per_day`, `date_of_hire`, `contact_name`, `contact_address`, `contact_phone`, `signiture_url`, `sss`, `tin`, `pag_ibig`, `phil_health`, `bank_name`, `bank_account`, `bank_number`, `deleted`) VALUES
(1, 10, '', NULL, 25, 15600, 'Monthly', 0, 8, '2018-09-11', '', '', '', NULL, '34-6106349-2', '414-641-769-000', '1212-0245-1328', '03-250682869-3', '', '', '', 0),
(2, 3, '', NULL, 25, 0, '', 0, 0, '2018-12-14', '', '', '', NULL, '34-8192321-1', '752-994-685-000', '1212-4368-2323', '03-026391384-8', '', '', '', 0),
(3, 11, '', NULL, 25, 15600, 'Monthly', 0, 0, '2018-09-11', '', '', '', NULL, '34-6106349-2', '414-641-769-000', '1212-0245-1328', '03-250682869-3', '', '', '', 0),
(4, 12, '', NULL, 25, 21060, 'Monthly', 0, 0, '2017-11-27', '', '', '', NULL, '34-4375495-0', '335-327-472-000', '1211-2660-5087', '09-025655513-8', '', '', '', 0),
(5, 13, '', NULL, 25, 15600, 'Monthly', 0, 8, '2020-05-17', '', '', '', NULL, '34-8613527-9', '743-536-341-000', '1212-7290-7028', '03-026490984-4', '', '', '', 0),
(6, 14, '', NULL, 25, 16510, 'Monthly', 0, 0, '0000-00-00', '', '', '', NULL, '34-8162564-5', '752-042-366-000', '1212-4463-0168', '03-252869103-9', '', '', '', 0),
(7, 15, '', NULL, 25, 600, 'Daily', 0, 8, '2018-01-09', '', '', '', NULL, '34-8257013-9', '277-030-930-000', '', '03-000240034-7', '', '', '', 0),
(8, 16, '', NULL, 25, 400, 'Daily', 0, 8, '2020-05-25', '', '', '', NULL, '34-3305905-2', '423-801-654-000', '1210-8078-9395', '2200-0098-3683', '', '', '', 0),
(9, 17, '', NULL, 25, 470, 'Daily', 0, 8, '2017-02-11', '', '', '', NULL, '34-7100085-6', '728-830-484-000', '1212-3402-9171', '03-051383395-0', '', '', '', 0),
(10, 6, '', NULL, 25, 0, '', 0, 0, '0000-00-00', '', '', '', NULL, '', '', '', '', '', '', '', 0);

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
  `operator` char(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` decimal(10,2) NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_type` enum('staff','client','lead','customer','driver','supplier','vendor') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'client',
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
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `street` text COLLATE utf8_unicode_ci NOT NULL,
  `city` text COLLATE utf8_unicode_ci NOT NULL,
  `state` text COLLATE utf8_unicode_ci NOT NULL,
  `zip` text COLLATE utf8_unicode_ci NOT NULL,
  `country` text COLLATE utf8_unicode_ci NOT NULL,
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
  `created_by` int(11) NOT NULL DEFAULT 0,
  `last_online` datetime DEFAULT NULL,
  `labels` text COLLATE utf8_unicode_ci NOT NULL,
  `requested_account_removal` tinyint(1) NOT NULL DEFAULT 0,
  `license_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `license_image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `resigned` int(1) DEFAULT 0,
  `terminated` int(1) DEFAULT 0,
  `company` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `first_name`, `last_name`, `user_type`, `is_admin`, `role_id`, `email`, `password`, `image`, `status`, `message_checked_at`, `client_id`, `vendor_id`, `notification_checked_at`, `is_primary_contact`, `job_title`, `disable_login`, `access_erpat`, `access_syntry`, `access_madage`, `access_galyon`, `note`, `address`, `street`, `city`, `state`, `zip`, `country`, `alternative_address`, `phone`, `alternative_phone`, `dob`, `ssn`, `gender`, `sticky_note`, `skype`, `asset_vendor_id`, `enable_web_notification`, `enable_email_notification`, `created_at`, `created_by`, `last_online`, `labels`, `requested_account_removal`, `license_number`, `license_image`, `resigned`, `terminated`, `company`, `deleted`, `updated_at`) VALUES
(1, '1ff765fb-04e0-4ed9-acd9-23f5406908a5', 'System', 'Admin', 'staff', 1, 0, 'admin@bytescrafter.net', '$2y$10$0IZl5/gilURaPwQxSsJU4eiM6tUiGo9IMiQX3euxryswMkA9YxKbS', 'a:1:{s:9:\"file_name\";s:29:\"_file605c17ad6fd52-avatar.png\";}', 'active', '2022-07-20 16:49:39', 0, 0, '2023-02-05 02:29:16', 0, 'Software Engineer', 0, 0, 0, 0, 1, NULL, '', 'B1O L18 Narra St., Silcas Village, San Francisco', 'Binan City', 'Laguna', '4024', 'Philippines', 'B1O L18 Narra St., Silcas Village, San Francisco', '', '', '0000-00-00', '', 'male', NULL, '', 0, 1, 1, '2021-03-24 21:23:38', 0, '2023-02-12 06:36:42', '', 0, '', '', 0, 0, '', 0, '2022-09-17 02:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(10) NOT NULL,
  `files` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `transmission` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `no_of_wheels` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plate_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `max_cargo_weight` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `INDEX` (`id`,`event_id`) USING BTREE;

--
-- Indexes for table `epass_block`
--
ALTER TABLE `epass_block`
  ADD PRIMARY KEY (`id`),
  ADD KEY `INDEX` (`id`,`area_id`) USING BTREE;

--
-- Indexes for table `epass_seat`
--
ALTER TABLE `epass_seat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `INDEX` (`id`,`block_id`) USING BTREE;

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `INDEX` (`id`,`uuid`,`event_id`,`user_id`,`guest`) USING BTREE;

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
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_categories`
--
ALTER TABLE `material_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_inventory`
--
ALTER TABLE `material_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_inventory_stock_override`
--
ALTER TABLE `material_inventory_stock_override`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_inventory_transfers`
--
ALTER TABLE `material_inventory_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_inventory_transfer_items`
--
ALTER TABLE `material_inventory_transfer_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_from` (`from_user_id`),
  ADD KEY `message_to` (`to_user_id`);

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
-- Indexes for table `overtime`
--
ALTER TABLE `overtime`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `uuid` (`uuid`);

--
-- Indexes for table `services_categories`
--
ALTER TABLE `services_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uuid` (`uuid`);

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
  ADD UNIQUE KEY `rfid_num` (`rfid_num`);

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
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_categories`
--
ALTER TABLE `material_categories`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_inventory`
--
ALTER TABLE `material_inventory`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_inventory_stock_override`
--
ALTER TABLE `material_inventory_stock_override`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_inventory_transfers`
--
ALTER TABLE `material_inventory_transfers`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_inventory_transfer_items`
--
ALTER TABLE `material_inventory_transfer_items`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `overtime`
--
ALTER TABLE `overtime`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services_categories`
--
ALTER TABLE `services_categories`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_member_job_info`
--
ALTER TABLE `team_member_job_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=414;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `to_do`
--
ALTER TABLE `to_do`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7964;

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
