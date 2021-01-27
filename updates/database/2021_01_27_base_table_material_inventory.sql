-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2021 at 12:41 PM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erpat`
--

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
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `material_inventory`
--
ALTER TABLE `material_inventory`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `material_inventory`
--
ALTER TABLE `material_inventory`
  MODIFY `id` bigint(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
