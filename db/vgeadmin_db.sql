-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 06, 2020 at 10:00 AM
-- Server version: 5.7.29
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vgeadmin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_beginning_bal`
--

CREATE TABLE `tbl_beginning_bal` (
  `beg_bag_id` int(30) NOT NULL,
  `product_id` int(10) NOT NULL,
  `location` int(10) NOT NULL,
  `beg_balance` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_beginning_bal`
--

INSERT INTO `tbl_beginning_bal` (`beg_bag_id`, `product_id`, `location`, `beg_balance`) VALUES
(11, 1, 2, 51),
(12, 29, 2, 17),
(10, 13, 2, 28),
(8, 12, 2, 400),
(9, 14, 2, 102),
(14, 21, 2, 8),
(13, 43, 2, 0),
(15, 64, 2, 9),
(16, 99, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brands`
--

CREATE TABLE `tbl_brands` (
  `brand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_brands`
--

INSERT INTO `tbl_brands` (`brand_id`, `name`, `status`, `date_added`) VALUES
(1, 'New', 0, '2019-10-24'),
(2, 'New Brand', 0, '2019-10-24'),
(3, 'New Name', 0, '2019-10-24'),
(4, 'Update', 0, '2019-10-24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories`
--

CREATE TABLE `tbl_categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 - Active, 1 - Trashed',
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_categories`
--

INSERT INTO `tbl_categories` (`category_id`, `name`, `status`, `date_added`) VALUES
(1, 'Home and Spa Solutions', 0, '2019-10-24'),
(43, 'Hair Care', 1, '2019-10-23'),
(44, 'Body Care', 1, '2019-10-24'),
(45, 'Bath and Body Care', 0, '2019-11-04'),
(46, 'For Stress and Pain Relief', 0, '2019-11-04'),
(47, 'Facial Care', 0, '2019-11-04'),
(48, 'Body Scrubs', 0, '2019-11-04'),
(49, 'Gift Packs', 0, '2019-11-04'),
(50, 'Promo Packs & others', 0, '2019-11-04'),
(51, 'Baby Care', 0, '2019-11-04'),
(52, 'Testt', 1, '2019-12-04'),
(53, 'sample products', 0, '2019-12-17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_daily_inventory`
--

CREATE TABLE `tbl_daily_inventory` (
  `daily_id` int(10) NOT NULL,
  `date_from` datetime DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `recorded_by` int(20) NOT NULL,
  `variance_item` int(20) NOT NULL,
  `variance_bal` int(200) NOT NULL,
  `phy_balance` int(20) NOT NULL,
  `end_balance` int(20) NOT NULL,
  `phy_items` int(20) NOT NULL,
  `end_items` int(20) NOT NULL,
  `recorded_items` longtext NOT NULL,
  `verified_items` longtext NOT NULL,
  `location` int(10) NOT NULL,
  `is_trash` int(10) NOT NULL COMMENT '0: no, 1:yes',
  `verified` int(11) NOT NULL DEFAULT '0' COMMENT '0 = false, 1 = true',
  `verified_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_daily_inventory`
--

INSERT INTO `tbl_daily_inventory` (`daily_id`, `date_from`, `date`, `recorded_by`, `variance_item`, `variance_bal`, `phy_balance`, `end_balance`, `phy_items`, `end_items`, `recorded_items`, `verified_items`, `location`, `is_trash`, `verified`, `verified_by`) VALUES
(1, '2020-02-04 14:55:00', '2020-02-05 14:59:00', 55, 0, 0, 524532, 524532, 615, 615, '[{\"item_id\":\"12\",\"beg_bal\":\"400\",\"sys_enditem\":\"400\",\"phy_enditem\":\"400\",\"variance_item\":\"0\",\"sys_endbal\":\"480000\",\"phy_endbal\":\"480000\",\"variance_bal\":\"0\"},{\"item_id\":\"14\",\"beg_bal\":\"102\",\"sys_enditem\":\"102\",\"phy_enditem\":\"102\",\"variance_item\":\"0\",\"sys_endbal\":\"102\",\"phy_endbal\":\"102\",\"variance_bal\":\"0\"},{\"item_id\":\"13\",\"beg_bal\":\"28\",\"sys_enditem\":\"28\",\"phy_enditem\":\"28\",\"variance_item\":\"0\",\"sys_endbal\":\"1400\",\"phy_endbal\":\"1400\",\"variance_bal\":\"0\"},{\"item_id\":\"1\",\"beg_bal\":\"51\",\"sys_enditem\":\"51\",\"phy_enditem\":\"51\",\"variance_item\":\"0\",\"sys_endbal\":\"25500\",\"phy_endbal\":\"25500\",\"variance_bal\":\"0\"},{\"item_id\":\"29\",\"beg_bal\":\"17\",\"sys_enditem\":\"17\",\"phy_enditem\":\"17\",\"variance_item\":\"0\",\"sys_endbal\":\"3995\",\"phy_endbal\":\"3995\",\"variance_bal\":\"0\"},{\"item_id\":\"43\",\"beg_bal\":\"0\",\"sys_enditem\":\" \",\"phy_enditem\":\"0\",\"variance_item\":\"0\",\"sys_endbal\":\" \",\"phy_endbal\":\"0\",\"variance_bal\":\"0\"},{\"item_id\":\"21\",\"beg_bal\":\"8\",\"sys_enditem\":\"8\",\"phy_enditem\":\"8\",\"variance_item\":\"0\",\"sys_endbal\":\"1880\",\"phy_endbal\":\"1880\",\"variance_bal\":\"0\"},{\"item_id\":\"64\",\"beg_bal\":\"9\",\"sys_enditem\":\"9\",\"phy_enditem\":\"9\",\"variance_item\":\"0\",\"sys_endbal\":\"11655\",\"phy_endbal\":\"11655\",\"variance_bal\":\"0\"},{\"item_id\":\"99\",\"beg_bal\":\"0\",\"sys_enditem\":\" \",\"phy_enditem\":\"0\",\"variance_item\":\"0\",\"sys_endbal\":\" \",\"phy_endbal\":\"0\",\"variance_bal\":\"0\"}]', '[{\"item_id\":\"12\",\"beg_bal\":\"400\",\"sys_enditem\":\"400\",\"phy_enditem\":\"400\",\"variance_item\":\"0\",\"sys_endbal\":\"480000\",\"phy_endbal\":\"480000\",\"variance_bal\":\"0\"},{\"item_id\":\"14\",\"beg_bal\":\"102\",\"sys_enditem\":\"102\",\"phy_enditem\":\"102\",\"variance_item\":\"0\",\"sys_endbal\":\"102\",\"phy_endbal\":\"102\",\"variance_bal\":\"0\"},{\"item_id\":\"13\",\"beg_bal\":\"28\",\"sys_enditem\":\"28\",\"phy_enditem\":\"28\",\"variance_item\":\"0\",\"sys_endbal\":\"1400\",\"phy_endbal\":\"1400\",\"variance_bal\":\"0\"},{\"item_id\":\"1\",\"beg_bal\":\"51\",\"sys_enditem\":\"51\",\"phy_enditem\":\"51\",\"variance_item\":\"0\",\"sys_endbal\":\"25500\",\"phy_endbal\":\"25500\",\"variance_bal\":\"0\"},{\"item_id\":\"29\",\"beg_bal\":\"17\",\"sys_enditem\":\"17\",\"phy_enditem\":\"17\",\"variance_item\":\"0\",\"sys_endbal\":\"3995\",\"phy_endbal\":\"3995\",\"variance_bal\":\"0\"},{\"item_id\":\"43\",\"beg_bal\":\"0\",\"sys_enditem\":\" \",\"phy_enditem\":\"0\",\"variance_item\":\"0\",\"sys_endbal\":\" \",\"phy_endbal\":\"0\",\"variance_bal\":\"0\"},{\"item_id\":\"21\",\"beg_bal\":\"8\",\"sys_enditem\":\"8\",\"phy_enditem\":\"8\",\"variance_item\":\"0\",\"sys_endbal\":\"1880\",\"phy_endbal\":\"1880\",\"variance_bal\":\"0\"},{\"item_id\":\"64\",\"beg_bal\":\"9\",\"sys_enditem\":\"9\",\"phy_enditem\":\"9\",\"variance_item\":\"0\",\"sys_endbal\":\"11655\",\"phy_endbal\":\"11655\",\"variance_bal\":\"0\"},{\"item_id\":\"99\",\"beg_bal\":\"0\",\"sys_enditem\":\" \",\"phy_enditem\":\"0\",\"variance_item\":\"0\",\"sys_endbal\":\" \",\"phy_endbal\":\"0\",\"variance_bal\":\"0\"}]', 2, 0, 1, 37);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inventory_movement`
--

CREATE TABLE `tbl_inventory_movement` (
  `movement_id` int(100) NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `item_id` int(100) NOT NULL,
  `item_qty` int(100) NOT NULL,
  `type` int(100) NOT NULL COMMENT '0= in, 1=out',
  `location` int(10) NOT NULL DEFAULT '0',
  `movement_type` varchar(20) NOT NULL,
  `date_added` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_inventory_movement`
--

INSERT INTO `tbl_inventory_movement` (`movement_id`, `reference_id`, `item_id`, `item_qty`, `type`, `location`, `movement_type`, `date_added`) VALUES
(72, 6, 43, 1, 1, 1, 'Stock Transfer', '2020-01-28 14:07:14'),
(73, 6, 43, 1, 0, 2, 'Stock Transfer', '2020-01-28 14:07:14'),
(71, 5, 29, 15, 0, 2, 'Stock Transfer', '2020-01-28 12:54:41'),
(69, 2, 14, 5, 1, 2, 'Sales', '2020-01-28 11:13:28'),
(70, 5, 29, 15, 1, 1, 'Stock Transfer', '2020-01-28 12:38:16'),
(68, 2, 12, 5, 1, 2, 'Sales', '2020-01-28 11:13:28'),
(74, 6, 43, 1, 0, 2, 'Stock Transfer', '2020-01-28 14:10:34'),
(67, 1, 14, 0, 1, 2, 'Sales', '2020-01-28 11:12:16'),
(66, 1, 12, 0, 1, 2, 'Sales', '2020-01-28 11:12:16'),
(65, NULL, 14, 51, 0, 1, 'Purchase Order', '2020-01-28 11:10:20'),
(64, 4, 14, 51, 0, 2, 'Stock Transfer', '2020-01-28 11:07:36'),
(63, 4, 14, 50, 0, 2, 'Stock Transfer', '2020-01-28 11:03:22'),
(62, 4, 14, 50, 1, 1, 'Stock Transfer', '2020-01-28 11:03:22'),
(61, 3, 12, 10, 0, 2, 'Stock Transfer', '2020-01-28 11:02:30'),
(60, 3, 12, 10, 0, 2, 'Stock Transfer', '2020-01-28 11:01:44'),
(59, 3, 12, 10, 1, 1, 'Stock Transfer', '2020-01-28 11:01:44'),
(58, 2, 1, 51, 0, 2, 'Stock Transfer', '2020-01-28 09:54:00'),
(55, 2, 14, 56, 0, 2, 'Stock Transfer', '2020-01-28 09:54:00'),
(56, 2, 12, 400, 0, 2, 'Stock Transfer', '2020-01-28 09:54:00'),
(57, 2, 13, 38, 0, 2, 'Stock Transfer', '2020-01-28 09:54:00'),
(54, 2, 1, 50, 0, 2, 'Stock Transfer', '2020-01-28 09:48:18'),
(53, 2, 1, 50, 1, 1, 'Stock Transfer', '2020-01-28 09:48:18'),
(52, 2, 13, 38, 0, 2, 'Stock Transfer', '2020-01-28 09:48:18'),
(51, 2, 13, 38, 1, 1, 'Stock Transfer', '2020-01-28 09:48:18'),
(50, 2, 12, 400, 0, 2, 'Stock Transfer', '2020-01-28 09:48:18'),
(49, 2, 12, 400, 1, 1, 'Stock Transfer', '2020-01-28 09:48:18'),
(48, 2, 14, 56, 0, 2, 'Stock Transfer', '2020-01-28 09:48:18'),
(47, 2, 14, 56, 1, 1, 'Stock Transfer', '2020-01-28 09:48:18'),
(46, 1, 12, 0, 0, 2, 'Stock Transfer', '2020-01-28 09:46:18'),
(45, 1, 12, 0, 1, 1, 'Stock Transfer', '2020-01-28 09:46:05'),
(75, 6, 29, 5, 0, 2, 'Stock Transfer', '2020-01-28 14:10:34'),
(76, NULL, 21, 15, 0, 1, 'Purchase Order', '2020-01-28 14:23:30'),
(77, NULL, 64, 5, 0, 1, 'Purchase Order', '2020-01-28 14:23:30'),
(78, NULL, 45, 20, 0, 1, 'Purchase Order', '2020-01-28 14:23:30'),
(79, 7, 21, 10, 1, 1, 'Stock Transfer', '2020-01-28 14:26:55'),
(80, 7, 21, 10, 0, 2, 'Stock Transfer', '2020-01-28 14:26:55'),
(81, 7, 64, 5, 1, 1, 'Stock Transfer', '2020-01-28 14:26:55'),
(82, 7, 64, 5, 0, 2, 'Stock Transfer', '2020-01-28 14:26:55'),
(83, 7, 21, 10, 0, 2, 'Stock Transfer', '2020-01-28 14:30:41'),
(84, 7, 64, 10, 0, 2, 'Stock Transfer', '2020-01-28 14:30:41'),
(85, 3, 21, 0, 1, 2, 'Sales', '2020-01-28 14:33:33'),
(86, 3, 29, 0, 1, 2, 'Sales', '2020-01-28 14:33:33'),
(89, NULL, 12, 2, 1, 2, 'Pull Out', '2020-01-28 15:36:03'),
(87, 4, 21, 2, 1, 2, 'Sales', '2020-01-28 14:35:59'),
(88, 4, 29, 3, 1, 2, 'Sales', '2020-01-28 14:35:59'),
(90, 5, 127, 1, 1, 1, 'Sales', '2020-01-28 15:38:08'),
(91, 6, 43, 1, 1, 2, 'Sales', '2020-01-28 16:00:19'),
(92, 7, 64, 1, 1, 2, 'Sales', '2020-01-28 16:58:56'),
(93, 8, 99, 12, 1, 1, 'Stock Transfer', '2020-01-28 17:02:25'),
(94, 8, 99, 12, 0, 2, 'Stock Transfer', '2020-01-28 17:02:25'),
(95, 8, 99, 12, 0, 2, 'Stock Transfer', '2020-01-28 17:08:42'),
(96, 2, 13, 10, 1, 1, 'Pull Out', '2020-01-29 10:35:03'),
(97, 3, 12, 83, 1, 2, 'Pull Out', '2020-01-29 10:35:27'),
(98, 8, 13, 0, 1, 2, 'Sales', '2020-01-29 14:01:19'),
(99, 9, 13, 10, 1, 2, 'Sales', '2020-01-29 14:01:38'),
(100, 9, 1, 5, 1, 1, 'Stock Transfer', '2020-02-05 16:26:28'),
(101, NULL, 1, 5, 0, 1, 'Purchase Order', '2020-02-05 16:37:17'),
(102, NULL, 12, 6, 0, 1, 'Purchase Order', '2020-02-05 16:37:36'),
(103, 10, 21, 1, 1, 2, 'Sales', '2020-02-06 17:11:47'),
(104, 11, 21, 1, 1, 2, 'Sales', '2020-02-06 17:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_locations`
--

CREATE TABLE `tbl_locations` (
  `location_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 - active , 1 - trashed',
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_locations`
--

INSERT INTO `tbl_locations` (`location_id`, `name`, `status`, `date_added`) VALUES
(1, 'CSO', 0, '2019-10-24'),
(2, 'Ayala Mall Cebu', 0, '2019-10-24'),
(3, 'Robinsons Cybergate', 0, '2019-10-24'),
(4, 'SM City Cebu', 0, '2019-10-25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_logs`
--

CREATE TABLE `tbl_logs` (
  `referrer_id` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `logged_by` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monthly_inventory`
--

CREATE TABLE `tbl_monthly_inventory` (
  `monthly_id` int(10) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `month_of_record` varchar(100) NOT NULL,
  `recorded_by` int(10) NOT NULL,
  `variance_item` int(100) NOT NULL,
  `variance_amount` int(100) NOT NULL,
  `phy_balance` int(100) NOT NULL,
  `end_balance` int(100) NOT NULL,
  `phy_items` int(100) NOT NULL,
  `end_items` int(100) NOT NULL,
  `recorded_items` longtext NOT NULL,
  `location` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_options`
--

CREATE TABLE `tbl_options` (
  `option_id` int(11) NOT NULL,
  `option_name` varchar(255) NOT NULL,
  `option_value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_options`
--

INSERT INTO `tbl_options` (`option_id`, `option_name`, `option_value`) VALUES
(1, 'target_sales_week', '10000');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `product_id` int(11) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `min_stock_warehouse` int(11) NOT NULL,
  `min_stock_kiosk` int(11) NOT NULL,
  `price` float NOT NULL,
  `supplier_price` double NOT NULL,
  `acquisition` float NOT NULL,
  `acquisition_percentage` float NOT NULL,
  `pass_on_cost` float NOT NULL,
  `pass_on_cost_percentage` float NOT NULL,
  `beg_bal` int(105) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = trashed',
  `added_by` int(11) NOT NULL,
  `date_modified` datetime NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`product_id`, `barcode`, `sku`, `name`, `category`, `description`, `min_stock_warehouse`, `min_stock_kiosk`, `price`, `supplier_price`, `acquisition`, `acquisition_percentage`, `pass_on_cost`, `pass_on_cost_percentage`, `beg_bal`, `status`, `added_by`, `date_modified`, `date_added`) VALUES
(1, '', 'HS-Pep- sample', 'SAMLE', 1, '', 50, 15, 500, 500, 250, 50, 300, 20, 0, 1, 1, '2019-12-17 15:11:31', '2019-12-01'),
(12, '4800049720510', 'SKU-TEST123', 'PRODUCT SAMPLE', 53, '', 120, 100, 1200, 100, 600, 50, 720, 20, 0, 1, 1, '2019-12-17 16:45:06', '2019-12-01'),
(13, '4800417094885', 'SKU-TEST1234', 'PRODUCT SAMPLE 2', 53, 'Testing\r\n', 100, 10, 50, 500, 25, 50, 30, 20, 0, 1, 1, '2019-12-17 16:44:56', '2019-12-01'),
(14, '8998666002334', '', 'Test Product KOPp', 53, '-', 100, 50, 1, 200, 0.5, 50, 0.6, 20, 0, 1, 1, '2020-01-28 10:56:25', '2019-11-29'),
(15, '123654789', '1534446', 'Masters', 53, 'Sekreto ng mga gwapo', 30, 20, 1, 500, 0.5, 50, 0.6, 20, 0, 1, 1, '2020-01-28 10:56:13', '2019-11-26'),
(16, '123456789', '', 'Choco Test', 53, 'Testing only', 150, 50, 600, 150, 300, 50, 360, 20, 0, 1, 1, '2019-12-17 16:45:54', '2019-11-26'),
(17, '467655444548', 'DM+_!@', 'DutchMill', 53, 'LAMI', 120, 100, 130, 120, 65, 50, 78, 20, 0, 1, 1, '2019-12-17 16:45:17', '2019-11-28'),
(18, '213214141241414', 'BO_s#1t', 'Sting', 53, 'Pang pa isug', 100, 100, 1200, 1000, 600, 50, 720, 20, 0, 1, 1, '2019-12-17 16:45:28', '2019-12-10'),
(19, '123123123', 'b05#1t', 'Nature Spring', 53, 'Test', 100, 100, 120, 100, 60, 50, 72, 20, 0, 1, 1, '2019-12-17 16:46:02', '2019-12-10'),
(20, '90909090', 'test', 'EPSON', 53, 'this is a test', 50, 10, 1000, 500, 500, 50, 600, 20, 0, 1, 38, '2019-12-17 16:44:32', '2019-12-11'),
(21, '', 'BBC-P-RX-100', 'Rice Bran Body Powder Relax 100gm', 45, '', 50, 15, 235, 117.5, 117.5, 50, 141, 20, 0, 1, 38, '2019-12-14 10:28:06', '2019-12-14'),
(22, '', 'BBC-P-RS-100', 'Rice Bran Body Powder Refresh 100gm', 45, '', 50, 15, 235, 117.5, 117.5, 50, 141, 20, 0, 1, 38, '2019-12-14 10:29:16', '2019-12-14'),
(23, '', 'BBC-BM-AM-10', 'Aromatic Essence Rice Bran Body Mist AM 10ml', 45, '', 50, 20, 165, 82.5, 82.5, 50, 99, 20, 0, 1, 38, '2019-12-14 10:31:46', '2019-12-14'),
(24, '', 'BBC-BM-AM-25', 'Aromatic Essence Rice Bran Body Mist AM 25ml', 45, '', 40, 16, 285, 142.5, 142.5, 50, 171, 20, 0, 1, 38, '2019-12-14 10:33:19', '2019-12-14'),
(25, '', 'BBC-BM-AM-250', 'Aromatic Essence Rice Bran Body Mist AM 250ml', 45, '', 10, 2, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-17 10:43:02', '2019-12-14'),
(26, '', 'BBC-BM-BS-10', 'Aromatic Essence Rice Bran Body Mist BASI 10ml', 45, '', 50, 15, 165, 82.5, 82.5, 50, 99, 20, 0, 1, 38, '2019-12-14 10:37:46', '2019-12-14'),
(27, '', 'BBC-BM-BS-25', 'Aromatic Essence Rice Bran Body Mist BASI 25ml', 45, '', 50, 15, 285, 142.5, 142.5, 50, 171, 20, 0, 1, 38, '2019-12-14 10:39:08', '2019-12-14'),
(28, '', 'BBC-BM-BS-250', 'Aromatic Essence Rice Bran Body Mist BASI 250ml', 45, '', 10, 2, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-14 10:40:19', '2019-12-14'),
(29, '', 'BBC-SP-AM-15', 'Solid Perfume AM 15gm', 45, '', 40, 10, 235, 117.5, 117.5, 50, 141, 20, 0, 1, 38, '2019-12-14 10:41:45', '2019-12-14'),
(30, '', 'BBC-SP-BS-15', 'Solid Perfume BASI 15gm', 45, '', 40, 10, 235, 117.5, 117.5, 50, 141, 20, 0, 1, 38, '2019-12-14 10:42:44', '2019-12-14'),
(31, '', 'BBC-HS-RS-1K', 'Rice Bran Hair Spa Refresh 1kg', 45, '', 4, 1, 1595, 797.5, 797.5, 50, 957, 20, 0, 1, 38, '2019-12-17 10:43:57', '2019-12-14'),
(32, '', 'BBC-HS-RS-100', 'Rice Bran Hair Spa Refresh 100g', 45, '', 20, 5, 295, 147.5, 147.5, 50, 177, 20, 0, 1, 38, '2019-12-14 10:48:14', '2019-12-14'),
(33, '', 'BBC-HS-RX-1K', 'Rice Bran Hair Spa Relax 1 kg', 45, '', 4, 1, 1595, 797.5, 797.5, 50, 957, 20, 0, 1, 38, '2019-12-17 10:44:29', '2019-12-14'),
(34, '', 'BBC-HS-RX-100', 'Rice Bran Hair Spa Relax 100g', 45, '', 80, 5, 295, 147.5, 147.5, 50, 177, 20, 0, 1, 38, '2019-12-14 10:51:00', '2019-12-14'),
(35, '', 'BBC-SP-AM-1L', 'Rice Bran Shampoo AM 1 Liter', 45, '', 10, 1, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-14 10:52:23', '2019-12-14'),
(36, '', 'BBC-SP-AM-500', 'Rice Bran Shampoo AM 500ml', 45, '', 10, 2, 655, 327.5, 327.5, 50, 393, 20, 0, 1, 38, '2019-12-14 10:53:13', '2019-12-14'),
(37, '', 'BBC-SP-AM-250', 'Rice Bran Shampoo AM 250ml', 45, '', 60, 10, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-14 10:54:19', '2019-12-14'),
(38, '', 'BBC-SP-AM-50', 'Rice Bran Shampoo AM 50ml', 45, '', 100, 10, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-14 10:55:50', '2019-12-14'),
(39, '', 'BBC-CD-AM-1L', 'Rice Bran Conditioner AM  1 Liter', 45, '', 10, 1, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-14 10:56:41', '2019-12-14'),
(40, '', 'BBC-CD-AM-500', 'Rice Bran Conditioner AM  500ml', 45, '', 20, 2, 655, 327.5, 327.5, 50, 393, 20, 0, 1, 38, '2019-12-14 10:57:42', '2019-12-14'),
(41, '', 'BBC-CD-AM-250', 'Rice Bran Conditioner AM  250ml', 45, '', 100, 10, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-14 10:58:39', '2019-12-14'),
(42, '', 'BBC-CD-AM-50', 'Rice Bran Conditioner AM  50ml', 45, '', 60, 10, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-14 10:59:42', '2019-12-14'),
(43, '', 'BBC-SP-OL-1L', 'Rice Bran with Olive Oil Shampoo 1 Liter', 45, '', 10, 1, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-14 11:00:37', '2019-12-14'),
(44, '', 'BBC-SP-OL-500', 'Rice Bran with Olive Oil Shampoo 500ml', 45, '', 20, 2, 655, 327.5, 327.5, 50, 393, 20, 0, 1, 38, '2019-12-14 11:01:28', '2019-12-14'),
(45, '', 'BBC-SP-OL-250', 'Rice Bran with Olive Oil Shampoo 250ml', 45, '', 100, 10, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-14 11:02:22', '2019-12-14'),
(46, '', 'BBC-SP-OL-50', 'Rice Bran with Olive Oil Shampoo 50ml', 45, '', 100, 15, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-14 11:03:06', '2019-12-14'),
(47, '', 'BBC-CD-OL-1L', 'Rice Bran with Olive Oil Conditioner 1000ml', 45, '', 10, 1, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-14 11:03:53', '2019-12-14'),
(48, '', 'BBC-CD-OL-500', 'Rice Bran with Olive Oil Conditioner 500ml', 45, '', 20, 2, 655, 327.5, 327.5, 50, 393, 20, 0, 1, 38, '2019-12-14 11:04:39', '2019-12-14'),
(49, '', 'BBC-CD-OL-250', 'Rice Bran with Olive Oil Conditioner 250ml', 45, '', 100, 10, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-14 11:05:18', '2019-12-14'),
(50, '', 'BBC-CD-OL-50', 'Rice Bran with Olive Oil Conditioner 50ml', 45, '', 100, 10, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-14 11:06:05', '2019-12-14'),
(51, '', 'BBC-SP-GAV-1L', 'Rice Bran Shampoo with Gugo and Aloe Vera 1 Liter', 45, '', 10, 1, 1650, 825, 825, 50, 990, 20, 0, 1, 38, '2019-12-14 11:06:46', '2019-12-14'),
(52, '', 'BBC-SP-GAV-500', 'Rice Bran Shampoo with Gugo and Aloe Vera 500ml', 45, '', 20, 2, 830, 415, 415, 50, 498, 20, 0, 1, 38, '2019-12-14 11:07:31', '2019-12-14'),
(53, '', 'BBC-SP-GAV-250', 'Rice Bran Shampoo with Gugo and Aloe Vera 250ml', 45, '', 100, 10, 485, 242.5, 242.5, 50, 291, 20, 0, 1, 38, '2019-12-14 11:13:53', '2019-12-14'),
(54, '', 'BBC-SP-GAV-50', 'Rice Bran Shampoo with Gugo and Aloe Vera 50ml', 45, '', 50, 10, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-14 11:09:16', '2019-12-14'),
(55, '', 'BBC-CD-GAV-1L', 'Rice Bran Conditioner with Gugo and Aloe Vera 1 Liter', 45, '', 10, 1, 1650, 825, 825, 50, 990, 20, 0, 1, 38, '2019-12-14 11:16:10', '2019-12-14'),
(56, '', 'BBC-CD-GAV-500', 'Rice Bran Conditioner with Gugo and Aloe Vera 500 ml', 45, '', 20, 2, 830, 415, 415, 50, 498, 20, 0, 1, 38, '2019-12-14 11:21:48', '2019-12-14'),
(57, '', 'BBC-CD-GAV-250', 'Rice Bran Conditioner with Gugo and Aloe Vera 250ml', 45, '', 100, 10, 485, 242.5, 242.5, 50, 291, 20, 0, 1, 38, '2019-12-14 11:23:07', '2019-12-14'),
(58, '', 'BBC-HST-G-100', 'Rice Bran and Gugo Hair & Scalp Tonic 100ml', 45, '', 100, 10, 395, 197.5, 197.5, 50, 237, 20, 0, 1, 38, '2019-12-14 11:24:13', '2019-12-14'),
(59, '', 'BBC-S-RB-135', 'Rice Bran (Whitening Soap) 135gms ', 45, '', 350, 30, 235, 117.5, 117.5, 50, 141, 20, 0, 1, 38, '2019-12-14 11:25:05', '2019-12-14'),
(60, '', 'BBC-S-CN-90', 'SOAP - Rice Bran with Coconut 90gms', 45, '', 350, 30, 195, 97.5, 97.5, 50, 117, 20, 0, 1, 38, '2019-12-14 11:25:58', '2019-12-14'),
(61, '', 'BBC-S-GMO-90', 'SOAP -Rice Bran with Goatsmilk and Oatmeal 90gms ', 45, '', 300, 29, 195, 97.5, 97.5, 50, 117, 20, 0, 1, 38, '2019-12-14 11:26:43', '2019-12-14'),
(62, '', 'BBC-S-SW-90', 'SOAP -Rice Bran with Seaweed Extract  90gms', 45, '', 300, 30, 195, 97.5, 97.5, 50, 117, 20, 0, 1, 38, '2019-12-14 11:29:06', '2019-12-14'),
(63, '', 'BBC-S-TT-90', 'SOAP -Rice Bran with Tea Tree 90gms ', 45, '', 300, 30, 195, 97.5, 97.5, 50, 117, 20, 0, 1, 38, '2019-12-14 11:29:48', '2019-12-14'),
(64, '', 'BBC-BC-AM-1L', 'Lightening  Body Cream- Am blend (1Liter)', 45, '', 10, 1, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-14 11:32:03', '2019-12-14'),
(65, '', 'BBC-BC-AM-250', 'Lightening  Body Cream -  Am blend (250ml)', 45, '', 250, 15, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-14 11:32:53', '2019-12-14'),
(66, '', 'BBC-BC-AM-50', 'Lightening  Body Cream (50ml) - AM BLEND', 45, '', 200, 20, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-14 11:34:10', '2019-12-14'),
(67, '', 'BBC-BC-OL-1L', 'Lightening  Body Cream - olive blend (1Liter) ', 45, '', 10, 1, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-14 11:35:52', '2019-12-14'),
(68, '', 'BBC-BC-OL-250', 'Lightening  Body Cream - olive blend (250ml)', 45, '', 100, 20, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-14 11:36:37', '2019-12-14'),
(69, '', 'BBC-BC-OL-50', 'Lightening  Body Cream - olive blend (50ml) ', 45, '\r\n', 100, 20, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-14 11:37:41', '2019-12-14'),
(70, '', 'BBC-CD-GAV-50', 'Rice Bran Conditioner with Gugo and Aloe Vera 50ml', 45, '', 100, 20, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-14 11:41:31', '2019-12-14'),
(71, '', 'FC-FBW-1L', 'Rice Bran Face & Body Wash 1L', 47, '', 5, 1, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-16 15:35:46', '2019-12-16'),
(72, '', 'FC-FBW-500', 'Rice Bran Face & Body Wash 500ml', 47, '', 30, 2, 695, 347.5, 347.5, 50, 417, 20, 0, 1, 38, '2019-12-16 15:37:16', '2019-12-16'),
(73, '', 'FC-FBW-120', 'Rice Bran Face & Body Wash 120ml', 47, '', 200, 30, 195, 97.5, 97.5, 50, 117, 20, 0, 1, 38, '2019-12-16 15:38:19', '2019-12-16'),
(74, '', 'FC-FBW-OL-50', 'Rice Bran Face & Body Wash RELAX - OLIVE 50ML ', 47, '', 50, 10, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-16 15:39:19', '2019-12-16'),
(75, '', 'FC-FMk-1K', 'Rice Powder Face Mask 1kl', 47, '', 4, 1, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-17 10:38:41', '2019-12-17'),
(76, '', 'FC-FMk-25', 'Rice Powder Face Mask 25g', 47, '', 60, 30, 95, 47.5, 47.5, 50, 57, 20, 0, 1, 38, '2019-12-17 10:20:48', '2019-12-17'),
(77, '', 'FC-Lip-B-15', 'Lip Balm Tint', 47, '', 60, 20, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 10:21:50', '2019-12-17'),
(78, '', 'FC-BT-O-1L', 'Beauty Oil 1 Liter', 47, '', 4, 1, 2495, 1247.5, 1247.5, 50, 1497, 20, 0, 1, 38, '2019-12-17 10:47:21', '2019-12-17'),
(79, '', 'FC-BT-O-500', 'Beauty Oil 500ml', 47, '', 20, 2, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-17 10:46:36', '2019-12-17'),
(80, '', 'FC-BT-O-60', 'Beauty Oil 60ml', 47, '', 100, 40, 265, 132.5, 132.5, 50, 159, 20, 0, 1, 38, '2019-12-17 10:25:44', '2019-12-17'),
(81, '', 'FC-PMT-50', 'Pore Minimizing Toner 50ml', 47, '', 60, 20, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-17 10:26:26', '2019-12-17'),
(82, '', 'FC-PMT-250', 'Pore Minimizing Toner 250ml', 47, '', 10, 2, 495, 247.5, 247.5, 50, 297, 20, 0, 1, 38, '2019-12-17 10:27:12', '2019-12-17'),
(83, '', 'FC-C-BB-20', 'FACIAL BB Cream 20G', 47, '', 40, 10, 495, 247.5, 247.5, 50, 297, 20, 0, 1, 38, '2019-12-17 10:28:07', '2019-12-17'),
(84, '', 'BC-B-W-1L', 'Rice Bran Baby Wash (1L)', 51, '', 4, 1, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-17 10:39:35', '2019-12-17'),
(85, '', 'BC-B-W-150', 'Rice Bran Baby Wash 150ml', 51, '', 50, 15, 265, 132.5, 132.5, 50, 159, 20, 0, 1, 38, '2019-12-17 10:30:32', '2019-12-17'),
(86, '', 'BC-B-P-125', 'Rice Bran Baby Powder 125g', 51, '', 40, 10, 235, 117.5, 117.5, 50, 141, 20, 0, 1, 38, '2019-12-17 10:31:11', '2019-12-17'),
(87, '', 'BC-BM-C-1L', 'Baby Mist Cologne 1L', 51, '', 4, 1, 2795, 1397.5, 1397.5, 50, 1677, 20, 0, 1, 38, '2019-12-17 10:40:25', '2019-12-17'),
(88, '', 'BC-BM-C-500', 'Baby Mist Cologne (500ml)', 51, '', 10, 2, 1350, 675, 675, 50, 810, 20, 0, 1, 38, '2019-12-17 11:11:09', '2019-12-17'),
(89, '', 'BC-BM-C-250', 'Baby Mist Cologne (250ml)', 51, '', 20, 2, 995, 497.5, 497.5, 50, 597, 20, 0, 1, 38, '2019-12-17 11:05:56', '2019-12-17'),
(90, '', 'BC-BM-C-50', 'Baby Mist Cologne (50ml)', 51, '', 60, 10, 235, 117.5, 117.5, 50, 141, 20, 0, 1, 38, '2019-12-17 11:06:51', '2019-12-17'),
(91, '', 'BC-CIR-O-1L', 'Citripel  Insect Repellant Oil  1L ', 51, '', 10, 2, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-17 11:07:57', '2019-12-17'),
(92, '', 'BC-CIR-O-50', 'Citripel  Insect Repellant Oil  50ml ', 51, '', 100, 15, 245, 122.5, 122.5, 50, 147, 20, 0, 1, 38, '2019-12-17 11:10:05', '2019-12-17'),
(93, '', 'SPR-HS-1L', 'Hand Sanitizer 1 L', 46, '', 5, 2, 1150, 575, 575, 50, 690, 20, 0, 1, 38, '2019-12-17 11:14:26', '2019-12-17'),
(94, '', 'SPR-HS-50', 'Hand Sanitizer 50ml', 46, '', 100, 30, 95, 47.5, 47.5, 50, 57, 20, 0, 1, 38, '2019-12-17 11:15:14', '2019-12-17'),
(95, '', 'SPR-FT-M-1L', 'Foot Mist 1 Liter', 46, '', 5, 2, 1950, 975, 975, 50, 1170, 20, 0, 1, 38, '2019-12-17 11:16:10', '2019-12-17'),
(96, '', 'SPR-FT-M-250', 'Foot Mist 250ml', 46, '', 50, 10, 595, 297.5, 297.5, 50, 357, 20, 0, 1, 38, '2019-12-17 11:16:58', '2019-12-17'),
(97, '', 'SPR-FT-M-50', 'Foot Mist 50ml', 46, '', 150, 20, 235, 117.5, 117.5, 50, 141, 20, 0, 1, 38, '2019-12-17 11:17:47', '2019-12-17'),
(98, '', 'SPR-Med-B-J', 'Rice Bran Meditation Balm JUMBO', 46, '', 20, 2, 1195, 597.5, 597.5, 50, 717, 20, 0, 1, 38, '2019-12-17 11:18:44', '2019-12-17'),
(99, '', 'SPR-Med-B-50', 'Rice Bran Meditation Balm 50gm', 46, '', 200, 20, 345, 172.5, 172.5, 50, 207, 20, 0, 1, 38, '2019-12-17 11:20:33', '2019-12-17'),
(100, '', 'SPR-Med-B-15', 'Rice Bran Meditation Balm (Travel size) 15gm', 46, '', 250, 50, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-17 11:22:52', '2019-12-17'),
(101, '', 'SPR-Cur-B-J', 'Rice Bran Curry Balm JUMBO', 46, '', 10, 2, 1195, 597.5, 597.5, 50, 717, 20, 0, 1, 38, '2019-12-17 11:23:43', '2019-12-17'),
(102, '', 'SPR-Cur-B-50', 'Rice Bran Curry Balm 50g', 46, '', 250, 20, 345, 172.5, 172.5, 50, 207, 20, 0, 1, 38, '2019-12-17 11:25:36', '2019-12-17'),
(103, '', 'SPR-Cur-B-15', 'Rice Bran Curry Balm 15g', 46, '', 250, 48, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-17 11:26:18', '2019-12-17'),
(104, '', 'SPR-Snf-B-J', 'Rice Bran Sniff Balm JUMBO', 46, '', 10, 2, 1195, 597.5, 597.5, 50, 717, 20, 0, 1, 38, '2019-12-17 11:27:01', '2019-12-17'),
(105, '', 'SPR-Snf-B-50', 'Rice Bran Sniff Balm 50g', 46, '', 250, 20, 345, 172.5, 172.5, 50, 207, 20, 0, 1, 38, '2019-12-17 11:27:52', '2019-12-17'),
(106, '', 'SPR-Snf-B-15', 'Rice Bran Sniff Balm 15g', 46, '', 250, 50, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-17 11:29:19', '2019-12-17'),
(107, '', 'SPR-Chil-O-1L', 'Rice Bran Chili Oil 1 Liter', 46, '', 15, 3, 2500, 1250, 1250, 50, 1500, 20, 0, 1, 38, '2019-12-17 11:30:16', '2019-12-17'),
(108, '', 'SPR-Chil-O-500', 'Rice Bran Chili Oil 500ml', 46, '', 50, 5, 1300, 650, 650, 50, 780, 20, 0, 1, 38, '2019-12-17 11:31:27', '2019-12-17'),
(109, '', 'SPR-Chil-O-100', 'Rice Bran Chili Oil 100ml', 46, '', 200, 25, 345, 172.5, 172.5, 50, 207, 20, 0, 1, 38, '2019-12-17 13:02:17', '2019-12-17'),
(110, '', 'SPR-Chil-O-50 ', 'Rice Bran Chili Oil 50ml', 46, '', 200, 30, 235, 117.5, 117.5, 50, 141, 20, 0, 1, 38, '2019-12-17 13:03:46', '2019-12-17'),
(111, '', 'SPR-Chil-RO-10', 'Rice Bran Chili Oil roll on 10ml ', 46, '', 100, 20, 135, 67.5, 67.5, 50, 81, 20, 0, 1, 38, '2019-12-17 13:04:31', '2019-12-17'),
(112, '', 'SPR-ABOE-AM-1L', 'Rice Bran Aromatherapy Body Oil Energising AM Blend 1L', 46, '', 10, 2, 2200, 1100, 1100, 50, 1320, 20, 0, 1, 38, '2019-12-17 13:05:28', '2019-12-17'),
(113, '', 'SPR-ABOE-AM-500', 'Rice Bran Aromatherapy Body Oil Energising AM Blend 500ml ', 46, '', 15, 3, 1200, 600, 600, 50, 720, 20, 0, 1, 38, '2019-12-17 13:06:25', '2019-12-17'),
(114, '', 'SPR-ABOE-AM-60', 'Rice Bran Aromatherapy Body Oil Energising AM Blend 60ml', 46, '', 80, 20, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 13:08:37', '2019-12-17'),
(115, '', 'SPR-ABOF-OL-1L', 'Rice Bran Aromatherapy Body Oil Firming Olive Blend 1 Liter', 46, '', 5, 1, 2200, 1100, 1100, 50, 1320, 20, 0, 1, 38, '2019-12-17 13:09:45', '2019-12-17'),
(116, '', 'SPR-ABOF-OL-500', 'Rice Bran Aromatherapy Body Oil Firming Olive Blend 500ml', 46, '', 10, 2, 1200, 600, 600, 50, 720, 20, 0, 1, 38, '2019-12-17 13:11:25', '2019-12-17'),
(117, '', 'SPR-ABOF-OL-60', 'Rice Bran Aromatherapy Body Oil Firming Olive Blend 60ml', 46, '', 60, 20, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 13:12:19', '2019-12-17'),
(118, '', 'SPR-CM-BO-1L', 'Calming Body Oil 1L', 46, '', 10, 2, 1495, 747.5, 747.5, 50, 897, 20, 0, 1, 38, '2019-12-17 13:13:12', '2019-12-17'),
(119, '', 'SPR-CM-BO-500', 'Calming Body Oil 500ml', 46, '', 15, 2, 950, 475, 475, 50, 570, 20, 0, 1, 38, '2019-12-17 13:14:13', '2019-12-17'),
(120, '', 'SPR-CM-BO-125', 'Calming Body Oil 125ml', 46, '', 80, 20, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 13:14:55', '2019-12-17'),
(121, '', 'SPR-HD-BO-1L', 'Just Your Holiday Body Oil 1L', 46, '', 10, 1, 1495, 747.5, 747.5, 50, 897, 20, 0, 1, 38, '2019-12-17 13:15:40', '2019-12-17'),
(122, '', 'SPR-HD-BO-500', 'Just Your Holiday Body Oil 500ml', 46, '', 15, 2, 950, 475, 475, 50, 570, 20, 0, 1, 38, '2019-12-17 13:16:07', '2019-12-17'),
(123, '', 'SPR-HD-BO-125', 'Just Your Holiday Body Oil 125ml', 46, '', 80, 20, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 13:16:53', '2019-12-17'),
(124, '', 'SPR-REC-BO-1L', 'Recovery Body Oil 1L', 46, '', 10, 2, 1495, 747.5, 747.5, 50, 897, 20, 0, 1, 38, '2019-12-17 13:17:31', '2019-12-17'),
(125, '', 'SPR-REC-BO-500', 'Recovery Body Oil 500ml', 46, '', 20, 2, 950, 475, 475, 50, 570, 20, 0, 1, 38, '2019-12-17 13:18:01', '2019-12-17'),
(126, '', 'SPR-REC-BO-125', 'Recovery Body Oil 125ml', 46, '', 80, 20, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 13:18:43', '2019-12-17'),
(127, '', 'SPR-REJ-BO-1L', 'Rice Bran Rejuvenating Jojoba  Body oil 1L', 46, '', 10, 2, 1900, 747.5, 950, 50, 1140, 20, 0, 1, 38, '2019-12-17 13:19:21', '2019-12-17'),
(128, '', 'SPR-REJ-BO-500', 'Rice Bran Rejuvenating Jojoba Body oil 500ml', 46, '', 20, 2, 980, 475, 490, 50, 588, 20, 0, 1, 38, '2019-12-17 13:20:15', '2019-12-17'),
(129, '', 'SPR-REJ-BO-60', 'Rice Bran Rejuvenating Jojoba Body oil 60ml', 46, '', 80, 20, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 13:20:49', '2019-12-17'),
(130, '', 'SPR-EN-BO- 1L', 'Energising Body oil 1L', 46, '', 15, 2, 1495, 747.5, 747.5, 50, 897, 20, 0, 1, 38, '2019-12-17 13:21:45', '2019-12-17'),
(131, '', 'SPR-EN-BO- 500', 'Energising Body oil 500ml', 46, '', 15, 2, 950, 475, 475, 50, 570, 20, 0, 1, 38, '2019-12-17 13:22:21', '2019-12-17'),
(132, '', 'SPR-EN-BO- 125', 'Energising Body oil 125ML', 46, '', 80, 20, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 13:22:58', '2019-12-17'),
(133, '', 'SPR-Un-O-1L', 'Rice Bran Oil Unscented 1L', 46, '', 10, 2, 750, 375, 375, 50, 450, 20, 0, 1, 38, '2019-12-17 13:23:36', '2019-12-17'),
(134, '', 'SPR-CIR-S-1L', 'Citripel Insect Repellant  Adult Spray  1 L', 46, '', 10, 2, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-17 13:25:06', '2019-12-17'),
(135, '', 'SPR-CIR-S-100', 'Citripel Insect Repellant  Adult Spray  100ml', 46, '', 60, 20, 245, 122.5, 122.5, 50, 147, 20, 0, 1, 38, '2019-12-17 13:25:58', '2019-12-17'),
(136, '', 'HS-CB-M-A', 'Ceramic Burner medium (Assorted Color)', 1, '', 10, 5, 445, 227.5, 222.5, 50, 267, 20, 0, 1, 38, '2020-02-06 14:37:44', '2019-12-17'),
(137, '', 'HS-CEB-A', 'Ceramic Electric Burner (Assorted Color)', 1, '', 10, 2, 1, 650, 650, 50, 780, 20, 0, 1, 38, '2020-02-06 14:38:14', '2019-12-17'),
(138, '', 'HS-DIF-A', 'Diffuser (Assorted Color)', 1, '', 10, 2, 1850, 925, 925, 50, 1110, 20, 0, 1, 38, '2019-12-17 14:39:19', '2019-12-17'),
(139, '', 'HS-BSL-EO-15', 'Basil Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 14:40:30', '2019-12-17'),
(140, '', 'HS-BSL-EO-30', 'Basil Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 14:41:04', '2019-12-17'),
(141, '', 'HS-BSL-EO-120', 'Basil Essential Oil 120ml', 1, '', 5, 2, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 14:41:51', '2019-12-17'),
(142, '', 'HS-BGT-EO-15', 'Bergamot Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 14:42:29', '2019-12-17'),
(143, '', 'HS-BGT-EO-30', 'Bergamot Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 14:44:02', '2019-12-17'),
(144, '', 'HS-BGT-EO-120', 'Bergamot Essential Oil 120ml', 1, '', 10, 2, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 14:44:46', '2019-12-17'),
(145, '', 'HS-CDW-EO-15', 'Cedar Wood Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 14:45:23', '2019-12-17'),
(146, '', 'HS-CDW-EO-30', 'Cedar Wood Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 14:46:04', '2019-12-17'),
(147, '', 'HS-CDW-EO-120', 'Cedar Wood Essential Oil 120ml', 1, '', 10, 2, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 14:47:43', '2019-12-17'),
(148, '', 'HS-CML-EO-15', 'Chamomile Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 14:48:14', '2019-12-17'),
(149, '', 'HS-CML-EO-30', 'Chamomile Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 14:48:55', '2019-12-17'),
(150, '', 'HS-CML-EO-120', 'Chamomile Essential Oil 120ml', 1, '', 10, 2, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 14:49:34', '2019-12-17'),
(151, '', 'HS-CML-EO-1L', 'Chamomile Essential Oil 1L', 1, '', 2, 1, 7500, 3750, 3750, 50, 4500, 20, 0, 1, 38, '2019-12-17 14:50:20', '2019-12-17'),
(152, '', 'HS-CIT-EO-15', 'Citronella Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 14:51:22', '2019-12-17'),
(153, '', 'HS-CIT-EO-30', 'Citronella Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 15:22:12', '2019-12-17'),
(154, '', 'HS-CIT-EO-120', 'Citronella Essential Oil 120ml', 1, '', 5, 2, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 14:52:36', '2019-12-17'),
(155, '', 'HS-EUC-EO-15', 'Eucalyptus Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 14:53:15', '2019-12-17'),
(156, '', 'HS-EUC-EO-30', 'Eucalyptus Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 14:54:06', '2019-12-17'),
(157, '', 'HS-EUC-EO-120', 'Eucalyptus Essential Oil 120ml', 1, '', 10, 2, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 14:54:48', '2019-12-17'),
(158, '', 'HS-EUC-EO-1L', 'Eucalyptus Essential Oil 1L', 1, '', 1, 1, 7500, 3750, 3750, 50, 4500, 20, 0, 1, 38, '2019-12-17 14:55:26', '2019-12-17'),
(159, '', 'HS-GTEA-EO-15', 'Green Tea Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 14:56:00', '2019-12-17'),
(160, '', 'HS-GTEA-EO-30', 'Green Tea Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 14:56:39', '2019-12-17'),
(161, '', 'HS-GTEA-EO-120', 'Green Tea Essential Oil 120ml', 1, '', 5, 2, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 14:57:16', '2019-12-17'),
(162, '', 'HS-JAS-EO-15', 'Jasmine Essential Oil 15ml', 1, '', 40, 10, 335, 127.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 14:57:51', '2019-12-17'),
(163, '', 'HS-JAS-EO-30', 'Jasmine Essential Oil 30ml', 1, '', 40, 10, 650, 192.5, 325, 50, 390, 20, 0, 1, 38, '2019-12-17 14:58:30', '2019-12-17'),
(164, '', 'HS-JAS-EO-120', 'Jasmine Essential Oil 120ml', 1, '', 5, 1, 2200, 725, 1100, 50, 1320, 20, 0, 1, 38, '2019-12-17 14:59:03', '2019-12-17'),
(165, '', 'HS-LAV-EO-15', 'Lavender Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 14:59:36', '2019-12-17'),
(166, '', 'HS-LAV-EO-30', 'Lavender Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 15:00:07', '2019-12-17'),
(167, '', 'HS-LAV-EO-120', 'Lavender Essential Oil 120ml', 1, '', 5, 1, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 15:00:44', '2019-12-17'),
(168, '', 'HS-LAV-EO-1L', 'Lavender Essential Oil 1L', 1, '', 1, 1, 7500, 3, 3750, 50, 4500, 20, 0, 1, 38, '2019-12-17 15:01:10', '2019-12-17'),
(169, '', 'HS-LMN-EO-15', 'Lemon Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 15:01:43', '2019-12-17'),
(170, '', 'HS-LMN-EO-30', 'Lemon Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 15:02:14', '2019-12-17'),
(171, '', 'HS-LMN-EO-120', 'Lemon Essential oil 120ml', 1, '', 5, 1, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 15:02:49', '2019-12-17'),
(172, '', 'HS-LMGS-EO-15', 'Lemongrass Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 15:03:22', '2019-12-17'),
(173, '', 'HS-LMGS-EO-30', 'Lemongrass Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 15:04:02', '2019-12-17'),
(174, '', 'HS-LMGS-EO-120', 'Lemongrass Essential Oil 120ml', 1, '', 5, 1, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 15:04:37', '2019-12-17'),
(175, '', 'HS-LIME-EO-15', 'Lime Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 15:05:09', '2019-12-17'),
(176, '', 'HS-LIME-EO-30', 'Lime Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 15:05:38', '2019-12-17'),
(177, '', 'HS-LIME-EO-120', 'Lime Essential Oil 120ml', 1, '', 5, 1, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 15:06:05', '2019-12-17'),
(178, '', 'HS-ORN-EO-15', 'Orange Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 15:06:48', '2019-12-17'),
(179, '', 'HS-ORN-EO-30', 'Orange Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 15:07:22', '2019-12-17'),
(180, '', 'HS-ORN-EO-120', 'Orange Essential Oil 120ml', 1, '', 5, 1, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 15:07:59', '2019-12-17'),
(181, '', 'HS-PEP-EO15', 'Peppermint Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 15:08:31', '2019-12-17'),
(182, '', 'HS-PEP-EO30', 'Peppermint Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 15:09:00', '2019-12-17'),
(183, '', 'HS-PEP-EO120', 'Peppermint Essential Oil 120ml', 1, '', 10, 2, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 15:11:38', '2019-12-17'),
(184, '', 'HS-SPM-EO-15', 'Spearmint Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 15:12:17', '2019-12-17'),
(185, '', 'HS-SPM-EO-30', 'Spearmint Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 15:12:45', '2019-12-17'),
(186, '', 'HS-SPM-EO-120', 'Spearmint Essential Oil  120ml', 1, '', 5, 1, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 15:13:20', '2019-12-17'),
(187, '', 'HS-ROS-EO-15', 'Rose Essential Oil 15ml', 1, '', 40, 10, 335, 127.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 15:14:03', '2019-12-17'),
(188, '', 'HS-ROS-EO-30', 'Rose Essential Oil 30ml', 1, '', 40, 10, 650, 192.5, 325, 50, 390, 20, 0, 1, 38, '2019-12-17 15:14:52', '2019-12-17'),
(189, '', 'HS-ROS-EO-120', 'Rose Essential Oil 120ml', 1, '', 5, 1, 2200, 725, 1100, 50, 1320, 20, 0, 1, 38, '2019-12-17 15:15:22', '2019-12-17'),
(190, '', 'HS-TTR-EO-15', 'Tea Tree Essential Oil 15ml', 1, '', 40, 10, 495, 247.5, 247.5, 50, 297, 20, 0, 1, 38, '2019-12-17 15:15:56', '2019-12-17'),
(191, '', 'HS-TTR-EO-30', 'Tea Tree Essential Oil 30ml', 1, '', 40, 10, 850, 425, 425, 50, 510, 20, 0, 1, 38, '2019-12-17 15:16:23', '2019-12-17'),
(192, '', 'HS-TTR-EO-120', 'Tea Tree Essential Oil 120ml', 1, '', 5, 1, 3500, 1750, 1750, 50, 2100, 20, 0, 1, 38, '2019-12-17 15:16:59', '2019-12-17'),
(193, '', 'HS-VNL-EO-15', 'Vanilla Essential Oil 15ml', 1, '', 40, 10, 255, 127.5, 127.5, 50, 153, 20, 0, 1, 38, '2019-12-17 15:17:33', '2019-12-17'),
(194, '', 'HS-VNL-EO-30', 'Vanilla Essential Oil 30ml', 1, '', 40, 10, 385, 192.5, 192.5, 50, 231, 20, 0, 1, 38, '2019-12-17 15:18:08', '2019-12-17'),
(195, '', 'HS-VNL-EO-120', 'Vanilla Essential Oil 120ml', 1, '', 5, 2, 1450, 725, 725, 50, 870, 20, 0, 1, 38, '2019-12-17 15:18:44', '2019-12-17'),
(196, '', 'HS-YYL-EO-15', 'Ylang-Ylang Essential Oil 15ml', 1, '', 40, 10, 335, 167.5, 167.5, 50, 201, 20, 0, 1, 38, '2019-12-17 15:19:24', '2019-12-17'),
(197, '', 'HS-YYL-EO-30', 'Ylang-Ylang Essential Oil 30ml', 1, '', 40, 10, 650, 325, 325, 50, 390, 20, 0, 1, 38, '2019-12-17 15:19:58', '2019-12-17'),
(198, '', 'HS-YYL-EO-120', 'Ylang-Ylang Essential Oil 120ml', 1, '', 5, 1, 2200, 1100, 1100, 50, 1320, 20, 0, 1, 38, '2019-12-17 15:20:25', '2019-12-17'),
(199, '', 'HS-WS-CF-50', 'Water Soluble Citrus fresh 50ml', 1, '', 50, 5, 395, 197.5, 197.5, 50, 237, 20, 0, 1, 38, '2019-12-17 15:27:04', '2019-12-17'),
(200, '', 'HS-WS-CF-150', 'Water Soluble Citrus fresh 150ml', 1, '', 20, 3, 950, 475, 475, 50, 570, 20, 0, 1, 38, '2019-12-17 15:27:47', '2019-12-17'),
(201, '', 'HS-WS-SW-50', 'Water Soluble Sleep well 50ml', 1, '', 50, 5, 395, 197.5, 197.5, 50, 237, 20, 0, 1, 38, '2019-12-17 15:28:31', '2019-12-17'),
(202, '', 'HS-WS-SW-150', 'Water Soluble Sleep well 150ml', 1, '', 10, 3, 950, 475, 475, 50, 570, 20, 0, 1, 38, '2019-12-17 15:29:44', '2019-12-17'),
(203, '', 'HS-WS-HH-50', 'Water Soluble Happy home 50ml', 1, '', 50, 5, 395, 197.5, 197.5, 50, 237, 20, 0, 1, 38, '2019-12-17 15:30:26', '2019-12-17'),
(204, '', 'HS-WS-HH-150', 'Water Soluble Happy home 150ml', 1, '', 10, 3, 950, 475, 475, 50, 570, 20, 0, 1, 38, '2019-12-17 15:30:57', '2019-12-17'),
(205, '', 'HS-WS-SG-50', 'Water Soluble Sweet garden 50ml', 1, '', 50, 5, 395, 197.5, 197.5, 50, 237, 20, 0, 1, 38, '2019-12-17 15:31:33', '2019-12-17'),
(206, '', 'HS-WS-SG-150', 'Water Soluble Sweet garden 150ml', 1, '', 10, 2, 950, 475, 475, 50, 570, 20, 0, 1, 38, '2019-12-17 15:32:44', '2019-12-17'),
(207, '', 'HS-WS-TTR-50', 'Water Soluble tea time relax 50ml', 1, '', 50, 5, 395, 197.5, 197.5, 50, 237, 20, 0, 1, 38, '2019-12-17 15:33:32', '2019-12-17'),
(208, '', 'HS-WS-TTR-150', 'Water Soluble tea time relax 150ml', 1, '', 10, 2, 950, 475, 475, 50, 570, 20, 0, 1, 38, '2019-12-17 15:34:01', '2019-12-17'),
(209, '', 'HS-RSE-E-250', 'Aromatherapy Room Spa Elixir ENERGY 250ml', 1, '', 40, 3, 695, 347.5, 347.5, 50, 417, 20, 0, 1, 38, '2019-12-17 15:35:02', '2019-12-17'),
(210, '', 'HS-RSE-E-100', 'Aromatherapy Room Spa Energy Elixir 100ml', 1, '', 60, 10, 295, 147.5, 147.5, 50, 177, 20, 0, 1, 38, '2019-12-17 15:35:39', '2019-12-17'),
(211, '', 'HS-RSE-R-250', 'Aromatherapy Room Spa Elixir RELAX 250ml', 1, '', 40, 3, 695, 347.5, 347.5, 50, 417, 20, 0, 1, 38, '2019-12-17 15:36:13', '2019-12-17'),
(212, '', 'HS-RSE-R-100', 'Aromatherapy Room Spa Relax Elixir 100ml', 1, '', 50, 10, 295, 147.5, 147.5, 50, 177, 20, 0, 1, 38, '2019-12-17 15:36:58', '2019-12-17'),
(213, '', 'HS-TLGHT-6', 'TEALIGHT 6\'s pack', 1, '', 100, 20, 50, 25, 25, 50, 30, 20, 0, 1, 38, '2019-12-17 15:37:38', '2019-12-17'),
(214, '', 'HS-INCS-A', 'Incense (assorted)', 1, '', 100, 25, 35, 17.5, 17.5, 50, 21, 20, 0, 1, 38, '2019-12-17 15:38:10', '2019-12-17'),
(215, '', 'BS-SCRUB-G', 'Scrub Gloves', 48, '', 500, 5, 50, 25, 25, 50, 30, 20, 0, 1, 38, '2019-12-17 15:59:42', '2019-12-17'),
(216, '', 'BS-FOT-MK-1K', 'Rice Bran Whitening  Foot Mask 1000gms ', 48, '', 1, 1, 1295, 647.5, 647.5, 50, 777, 20, 0, 1, 38, '2019-12-17 16:00:15', '2019-12-17'),
(217, '', 'BS-FOT-MK-25', 'Rice Bran Whitening  Foot Mask 25gms ', 48, '', 80, 20, 95, 47.5, 47.5, 50, 57, 20, 0, 1, 38, '2019-12-17 16:00:51', '2019-12-17'),
(218, '', 'BS-WS-S', 'Rice Bran Wrap & Scrub Sachet', 48, '', 40, 10, 195, 97.5, 97.5, 50, 117, 20, 0, 1, 38, '2019-12-17 16:01:54', '2019-12-17'),
(219, '', 'BS-BBTR-100', 'Boreh Butter 100g', 48, '', 10, 5, 295, 147.5, 147.5, 50, 177, 20, 0, 1, 38, '2019-12-17 16:02:37', '2019-12-17'),
(220, '', 'BS-CHAM-1K', 'Champorado Scrub (Anti-Aging) 1000g', 48, '', 2, 1, 1800, 900, 900, 50, 1080, 20, 0, 1, 38, '2019-12-17 16:03:12', '2019-12-17'),
(221, '', 'BS-CHAM-100', 'Champorado Scrub 100g', 48, '', 40, 5, 295, 147.5, 147.5, 50, 177, 20, 0, 1, 38, '2019-12-17 16:03:44', '2019-12-17'),
(222, '', 'BS-LULUR-1K', 'Lulur Scrub (Whitening) 1000g', 48, '', 5, 1, 1800, 900, 900, 50, 1080, 20, 0, 1, 38, '2019-12-17 16:04:20', '2019-12-17'),
(223, '', 'BS-LULUR-100', 'Lulur Scrub whitening 100g', 48, '', 2, 1, 295, 147.5, 147.5, 50, 177, 20, 0, 1, 38, '2019-12-17 16:04:59', '2019-12-17'),
(224, '', 'BS-WBTR-1L', 'Wasabi Butter 1 liter', 48, '', 2, 1, 1995, 997.5, 997.5, 50, 1197, 20, 0, 1, 38, '2019-12-17 16:05:28', '2019-12-17'),
(225, '', 'BS-WBTR-100', 'Wasabi Butter 100g', 48, '', 20, 5, 295, 147.5, 147.5, 50, 177, 20, 0, 1, 38, '2019-12-17 16:06:08', '2019-12-17'),
(226, '', 'BS-WFRM-1K', 'Wasabi firming scrub 1 kl', 48, '', 2, 1, 1800, 900, 900, 50, 1080, 20, 0, 1, 38, '2019-12-17 16:06:41', '2019-12-17'),
(227, '', 'BS-WFRM-100', 'Wasabi Firming Scrub 100g', 48, '', 30, 5, 295, 147.5, 147.5, 50, 177, 20, 0, 1, 38, '2019-12-17 16:07:24', '2019-12-17'),
(228, '', 'BS-EAR-C', 'Ear Candle', 48, '', 100, 25, 120, 60, 60, 50, 72, 20, 0, 1, 38, '2019-12-17 16:08:07', '2019-12-17'),
(229, '', 'GF-TRVL-KT', 'Travel Kit  ', 49, '', 10, 2, 1200, 600, 600, 50, 720, 20, 0, 1, 38, '2019-12-17 16:24:03', '2019-12-17'),
(230, '', 'GF-TRVLKIT-WBOX', 'Travel kit with Wooden box', 49, '', 5, 2, 1, 650, 0.5, 50, 0.6, 20, 0, 1, 38, '2020-01-28 16:12:12', '2019-12-17'),
(231, '', 'GF-KLCHUCHI-PK', 'Kalachuchi POUCH KIT', 49, '', 5, 2, 1230, 615, 615, 50, 738, 20, 0, 1, 38, '2019-12-17 16:26:42', '2019-12-17'),
(232, '', 'GF-GRNBX - KIT', 'GREEN BOX KIT (chili 50ml,chili roll on , med balm 15g + green box)', 49, '', 10, 2, 655, 327.5, 327.5, 50, 393, 20, 0, 1, 38, '2019-12-17 16:27:39', '2019-12-17'),
(233, '', 'PP-GFT-BX', 'Gift Box', 50, '', 80, 20, 20, 20, 10, 50, 12, 20, 0, 1, 38, '2019-12-17 16:30:58', '2019-12-17'),
(234, '', 'PP-POUCH-L', 'POUCH (Large)', 50, '', 100, 30, 5, 2.5, 2.5, 50, 3, 20, 0, 1, 38, '2019-12-17 16:31:39', '2019-12-17'),
(235, '', 'PP-POUCH-M', 'POUCH(medium)', 50, '', 100, 30, 4, 2, 2, 50, 2.4, 20, 0, 1, 38, '2019-12-17 16:32:19', '2019-12-17'),
(236, '', 'PP-POUCH-S', 'POUCH (small)', 50, '', 100, 30, 3, 1.5, 1.5, 50, 1.8, 20, 0, 1, 38, '2019-12-17 16:32:49', '2019-12-17'),
(237, '', 'PP-IP-BX-S', 'IP BOX small', 50, '', 50, 20, 10, 10, 5, 50, 6, 20, 0, 1, 38, '2019-12-17 16:33:24', '2019-12-17'),
(238, '', 'PP-IP-BX-M', 'IP BOX medium', 50, '', 50, 20, 15, 15, 7.5, 50, 9, 20, 0, 1, 38, '2019-12-17 16:33:51', '2019-12-17'),
(239, '', 'PP-NTV-BX-BG', 'Native box BIG', 50, '', 20, 5, 200, 100, 100, 50, 120, 20, 0, 1, 38, '2019-12-17 16:34:32', '2019-12-17'),
(240, '', 'PP-NTV-BX-M', 'Native box Medium', 50, '', 20, 3, 150, 75, 75, 50, 90, 20, 0, 1, 38, '2020-01-28 16:13:13', '2019-12-17'),
(241, '', 'PP-NTV-BX-S', 'Native box SMALL', 50, '', 10, 2, 100, 50, 50, 50, 60, 20, 0, 1, 38, '2019-12-17 16:36:31', '2019-12-17'),
(242, '', 'O-BOOK', 'ORYSPA\'S JOURNEY BOOK', 50, '', 5, 1, 495, 247.5, 247.5, 50, 297, 20, 0, 1, 38, '2019-12-17 16:37:13', '2019-12-17'),
(243, '', 'PP-PBAG', 'Paperbag', 50, '', 1, 1, 15, 15, 7.5, 50, 9, 20, 0, 1, 38, '2019-12-17 16:37:48', '2019-12-17'),
(244, '', 'PP-ECOBAG', 'ecobag ( free for bulk purchases)', 50, '', 100, 30, 0, 5.3, 0, 50, 0, 20, 0, 1, 38, '2020-02-06 13:32:05', '2019-12-17'),
(245, '', 'PP-WOD-BX', 'Wooden box alone ', 50, '', 10, 2, 120, 120, 60, 50, 72, 20, 0, 1, 38, '2019-12-17 16:38:56', '2019-12-17'),
(246, '', 'PP-GRN-BOX', 'ORYSPA Green Box ', 50, '', 10, 2, 150, 120, 75, 50, 90, 20, 0, 1, 38, '2020-02-06 13:33:38', '2019-12-17'),
(247, '123124214', '2213dasds', 'Testing', 45, '10', 10, 10, 100, 10, 50, 50, 60, 20, 0, 1, 1, '2020-01-28 10:55:57', '2019-12-20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pull_out`
--

CREATE TABLE `tbl_pull_out` (
  `pull_out_id` int(11) NOT NULL,
  `items` longtext NOT NULL,
  `location` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `remarks` longtext NOT NULL,
  `date_created` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pull_out`
--

INSERT INTO `tbl_pull_out` (`pull_out_id`, `items`, `location`, `created_by`, `remarks`, `date_created`) VALUES
(1, '[{\"item_id\":\"12\",\"qty\":\"2\"}]', 2, 1, 'personal use', '2020-01-28'),
(2, '[{\"item_id\":\"13\",\"qty\":\"10\"}]', 1, 1, 'Test', '2020-01-29'),
(3, '[{\"item_id\":\"12\",\"qty\":\"83\"}]', 2, 1, 'tESTING', '2020-01-29');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchased_items`
--

CREATE TABLE `tbl_purchased_items` (
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_purchased_items`
--

INSERT INTO `tbl_purchased_items` (`purchase_id`, `product_id`, `category_id`, `qty`) VALUES
(6, 43, 45, 1),
(5, 127, 46, 1),
(2, 12, 53, 5),
(2, 14, 53, 5),
(4, 21, 45, 2),
(4, 29, 45, 3),
(7, 64, 45, 1),
(10, 21, 45, 1),
(9, 13, 53, 10),
(11, 21, 45, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_order`
--

CREATE TABLE `tbl_purchase_order` (
  `poid` int(10) NOT NULL,
  `supplier` varchar(20) NOT NULL,
  `created_by` varchar(100) NOT NULL,
  `created_date` date NOT NULL,
  `received_by` varchar(100) NOT NULL,
  `received_date` date DEFAULT NULL,
  `total_cost` double NOT NULL,
  `total_qty` varchar(100) NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `quantity_expected` varchar(100) NOT NULL,
  `quantity_received` longtext NOT NULL,
  `items_update` longtext NOT NULL COMMENT 'Mao ni mabutngan if unsa ang mga naadd ug mga kuwang',
  `cost_per_product` varchar(100) NOT NULL,
  `items` longtext NOT NULL,
  `total_price` varchar(100) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0= pending, 1=approved, 2=for delivery, 3= received',
  `is_trash` int(10) NOT NULL COMMENT '1 = trash, 0= active',
  `with_discrepancy` int(11) NOT NULL DEFAULT '0' COMMENT '0 = false, 1 = true'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_purchase_order`
--

INSERT INTO `tbl_purchase_order` (`poid`, `supplier`, `created_by`, `created_date`, `received_by`, `received_date`, `total_cost`, `total_qty`, `item_id`, `quantity_expected`, `quantity_received`, `items_update`, `cost_per_product`, `items`, `total_price`, `status`, `is_trash`, `with_discrepancy`) VALUES
(1, 'Oryspa', '1', '2020-01-28', '1', '2020-01-28', 4000, '1', '', '', '[{\"item_id\":\"12\",\"qty\":\"40\",\"cost_per\":\"100\",\"total_price\":\"4000\"}]', '', '', '[{\"item_id\":\"12\",\"qty\":\"40\",\"cost_per\":\"100\",\"total_price\":\"4000\"}]', '', 3, 0, 0),
(2, 'Oryspa', '1', '2020-01-28', '1', '2020-01-28', 20200, '1', '', '', '[{\"item_id\":\"14\",\"qty\":\"101\",\"cost_per\":\"200\",\"total_price\":\"20200\"}]', '', '', '[{\"item_id\":\"14\",\"qty\":\"100\",\"cost_per\":\"200\",\"total_price\":\"20000\"}]', '', 3, 0, 1),
(3, 'Oryspa', '1', '2020-01-28', '1', '2020-01-28', 10200, '1', '', '', '[{\"item_id\":\"14\",\"qty\":\"51\",\"cost_per\":\"200\",\"total_price\":\"10200\"}]', '', '', '[{\"item_id\":\"14\",\"qty\":\"50\",\"cost_per\":\"200\",\"total_price\":\"10000\"}]', '', 3, 0, 1),
(4, 'Oryspa', '1', '2020-01-28', '1', '2020-01-28', 8330, '3', '', '', '[{\"item_id\":\"21\",\"qty\":\"15\",\"cost_per\":\"117.5\",\"total_price\":\"1755\"},{\"item_id\":\"64\",\"qty\":\"5\",\"cost_per\":\"647.5\",\"total_price\":\"3235\"},{\"item_id\":\"45\",\"qty\":\"20\",\"cost_per\":\"167.5\",\"total_price\":\"3340\"}]', '', '', '[{\"item_id\":\"21\",\"qty\":\"15\",\"cost_per\":\"117.5\",\"total_price\":\"1755\"},{\"item_id\":\"64\",\"qty\":\"5\",\"cost_per\":\"647.5\",\"total_price\":\"3235\"},{\"item_id\":\"45\",\"qty\":\"25\",\"cost_per\":\"167.5\",\"total_price\":\"4175\"}]', '', 3, 0, 1),
(5, 'Oryspa', '1', '2020-02-05', '1', '2020-02-05', 2500, '1', '', '', '[{\"item_id\":\"1\",\"qty\":\"5\",\"cost_per\":\"500\",\"total_price\":\"2500\"}]', '', '', '[{\"item_id\":\"1\",\"qty\":\"5\",\"cost_per\":\"500\",\"total_price\":\"2500\"}]', '', 3, 0, 0),
(6, 'Oryspa', '1', '2020-02-05', '1', '2020-02-05', 600, '1', '', '', '[{\"item_id\":\"12\",\"qty\":\"6\",\"cost_per\":\"100\",\"total_price\":\"600\"}]', '', '', '[{\"item_id\":\"12\",\"qty\":\"6\",\"cost_per\":\"100\",\"total_price\":\"600\"}]', '', 3, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_request`
--

CREATE TABLE `tbl_request` (
  `request_id` int(10) NOT NULL,
  `items` longtext NOT NULL COMMENT 'json',
  `total_quantity` int(20) NOT NULL,
  `requested_by` int(10) NOT NULL,
  `requested_from` int(10) NOT NULL,
  `date_requested` date NOT NULL,
  `approved_by` int(10) DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `remarks` varchar(255) NOT NULL,
  `status` int(10) NOT NULL DEFAULT '0' COMMENT '0=pending, 1= approved,2=recieved, 3=cancelled'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_request`
--

INSERT INTO `tbl_request` (`request_id`, `items`, `total_quantity`, `requested_by`, `requested_from`, `date_requested`, `approved_by`, `approved_date`, `transfer_id`, `remarks`, `status`) VALUES
(1, '[{\"item_id\":\"14\",\"qty\":\"56\"},{\"item_id\":\"12\",\"qty\":\"400\"},{\"item_id\":\"13\",\"qty\":\"38\"},{\"item_id\":\"1\",\"qty\":\"50\"}]', 544, 37, 2, '2020-01-28', 1, '2020-01-28', 2, 'Testing', 2),
(2, '[{\"item_id\":\"12\",\"qty\":\"10\"}]', 10, 37, 2, '2020-01-28', 1, '2020-01-28', 3, '-', 2),
(3, '[{\"item_id\":\"14\",\"qty\":\"50\"}]', 50, 37, 2, '2020-01-28', 1, '2020-01-28', 4, '-', 2),
(4, '[{\"item_id\":\"43\",\"qty\":\"1\"}]', 1, 37, 2, '2020-01-28', 1, '2020-01-28', 6, '-', 2),
(5, '[{\"item_id\":\"21\",\"qty\":\"10\"},{\"item_id\":\"64\",\"qty\":\"5\"}]', 15, 37, 2, '2020-01-28', 1, '2020-01-28', 7, '-', 2),
(6, '[{\"item_id\":\"99\",\"qty\":\"12\"}]', 12, 37, 2, '2020-01-28', 1, '2020-01-28', 8, '-', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales`
--

CREATE TABLE `tbl_sales` (
  `sales_id` int(11) NOT NULL,
  `display_id` varchar(255) NOT NULL,
  `location` int(11) NOT NULL,
  `issued_by` int(11) NOT NULL,
  `date_issued` date NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL COMMENT 'Customer Full Name',
  `customer_contact` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `items` longtext NOT NULL,
  `remarks` longtext NOT NULL,
  `total_discount` double NOT NULL,
  `total_items` int(11) NOT NULL,
  `total_amount` double NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '0 = pending, 1 = completed, 2 = Void',
  `void_to` int(11) DEFAULT NULL,
  `voided_by` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sales`
--

INSERT INTO `tbl_sales` (`sales_id`, `display_id`, `location`, `issued_by`, `date_issued`, `payment_method`, `customer_name`, `customer_contact`, `customer_email`, `items`, `remarks`, `total_discount`, `total_items`, `total_amount`, `status`, `void_to`, `voided_by`) VALUES
(1, 'SO0001', 2, 37, '2020-01-28', 'cash', 'john', '', '', '[{\"item_id\":\"12\",\"qty\":\"90\",\"discount\":\"0\",\"total\":\"108000\"},{\"item_id\":\"14\",\"qty\":\"7\",\"discount\":\"0\",\"total\":\"7\"}]', '', 0, 97, 108007, 2, 2, 1),
(2, 'SO0002', 2, 37, '2020-01-28', 'gcash', 'John Doe', '', '', '[{\"item_id\":\"12\",\"qty\":\"5\",\"discount\":\"0\",\"total\":\"6000\"},{\"item_id\":\"14\",\"qty\":\"5\",\"discount\":\"0\",\"total\":\"5\"}]', 'Void From: <a href=\"http://vge.web2.ph/sales_order/view/1\" >SO0001</a>', 0, 10, 6005, 1, NULL, NULL),
(3, 'SO0003', 2, 37, '2020-01-28', 'cash', 'Kme', '', '', '[{\"item_id\":\"21\",\"qty\":\"3\",\"discount\":\"0\",\"total\":\"705\"},{\"item_id\":\"29\",\"qty\":\"1\",\"discount\":\"11.75\",\"total\":\"223.25\"}]', 'Change of mind', 11.75, 4, 928.25, 2, 4, 1),
(4, 'SO0004', 2, 37, '2020-01-28', 'cash', 'Kme', '', '', '[{\"item_id\":\"21\",\"qty\":\"2\",\"discount\":\"0\",\"total\":\"470\"},{\"item_id\":\"29\",\"qty\":\"3\",\"discount\":\"0\",\"total\":\"705\"}]', 'Void From: <a href=\"http://vge.web2.ph/sales_order/view/3\" >SO0003</a>', 0, 5, 1175, 1, NULL, NULL),
(5, 'SO0005', 1, 1, '2020-01-28', 'cash', 'ana', '', '', '[{\"item_id\":\"127\",\"qty\":\"1\",\"discount\":\"380\",\"total\":\"1520\"}]', '3 gives starting today', 380, 1, 1520, 1, NULL, NULL),
(6, 'SO0006', 2, 37, '2020-01-28', 'cash', 'Jeanne Sy', '', '', '[{\"item_id\":\"43\",\"qty\":\"1\",\"discount\":\"0\",\"total\":\"1295\"}]', '', 0, 1, 1295, 1, NULL, NULL),
(7, 'SO0007', 2, 37, '2020-01-28', 'cash', 'LexisE', '', '', '[{\"item_id\":\"64\",\"qty\":\"1\",\"discount\":\"0\",\"total\":\"1295\"}]', '', 0, 1, 1295, 1, NULL, NULL),
(8, 'SO0008', 2, 37, '2020-01-28', 'cash', 'Collen', '', '', '[{\"item_id\":\"13\",\"qty\":\"8\",\"discount\":\"0\",\"total\":\"400\"}]', '', 0, 8, 400, 2, 9, 1),
(9, 'SO0009', 2, 37, '2020-01-29', 'cash', 'Collen', '', '', '[{\"item_id\":\"13\",\"qty\":\"10\",\"discount\":\"0\",\"total\":\"500\"}]', 'Void From: <a href=\"http://vge.web2.ph/sales_order/view/8\" >SO0008</a>', 0, 10, 500, 1, NULL, NULL),
(10, 'SO0010', 2, 37, '2020-02-06', 'cash', 'ana', '', '', '[{\"item_id\":\"21\",\"qty\":\"1\",\"discount\":\"0\",\"total\":\"235\"}]', '', 0, 1, 235, 0, 11, NULL),
(11, 'SO0011', 2, 37, '2020-02-06', 'cash', 'ana', '', '', '[{\"item_id\":\"21\",\"qty\":\"1\",\"discount\":\"47\",\"total\":\"188\"}]', 'Void From: <a href=\"http://vge.web2.ph/sales_order/view/10\" >SO0010</a>', 47, 1, 188, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stocks`
--

CREATE TABLE `tbl_stocks` (
  `stocks_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `location` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_stocks`
--

INSERT INTO `tbl_stocks` (`stocks_id`, `product_id`, `location`, `qty`) VALUES
(1, 1, 1, 0),
(2, 12, 1, 0),
(3, 13, 1, 0),
(10, 14, 1, 0),
(14, 15, 1, 0),
(15, 16, 1, 0),
(16, 17, 1, 0),
(18, 18, 1, 0),
(19, 19, 1, 0),
(20, 20, 1, 0),
(24, 21, 1, 0),
(25, 22, 1, 0),
(26, 23, 1, 0),
(27, 24, 1, 0),
(28, 25, 1, 0),
(29, 26, 1, 0),
(30, 27, 1, 0),
(31, 28, 1, 0),
(32, 29, 1, 0),
(33, 30, 1, 0),
(34, 31, 1, 0),
(35, 32, 1, 0),
(36, 33, 1, 0),
(37, 34, 1, 0),
(38, 35, 1, 0),
(39, 36, 1, 0),
(40, 37, 1, 0),
(41, 38, 1, 0),
(42, 39, 1, 0),
(43, 40, 1, 0),
(44, 41, 1, 0),
(45, 42, 1, 0),
(46, 43, 1, 0),
(47, 44, 1, 0),
(48, 45, 1, 0),
(49, 46, 1, 0),
(50, 47, 1, 0),
(51, 48, 1, 0),
(52, 49, 1, 0),
(53, 50, 1, 0),
(54, 51, 1, 0),
(55, 52, 1, 0),
(56, 53, 1, 0),
(57, 54, 1, 0),
(58, 55, 1, 0),
(59, 56, 1, 0),
(60, 57, 1, 0),
(61, 58, 1, 0),
(62, 59, 1, 0),
(63, 60, 1, 0),
(64, 61, 1, 0),
(65, 62, 1, 0),
(66, 63, 1, 0),
(67, 64, 1, 0),
(68, 65, 1, 0),
(69, 66, 1, 0),
(70, 67, 1, 0),
(71, 68, 1, 0),
(72, 69, 1, 0),
(73, 70, 1, 0),
(74, 71, 1, 0),
(75, 72, 1, 0),
(76, 73, 1, 0),
(77, 74, 1, 0),
(78, 75, 1, 0),
(79, 76, 1, 0),
(80, 77, 1, 0),
(81, 78, 1, 0),
(82, 79, 1, 0),
(83, 80, 1, 0),
(84, 81, 1, 0),
(85, 82, 1, 0),
(86, 83, 1, 0),
(87, 84, 1, 0),
(88, 85, 1, 0),
(89, 86, 1, 0),
(90, 87, 1, 0),
(91, 88, 1, 0),
(92, 89, 1, 0),
(93, 90, 1, 0),
(94, 91, 1, 0),
(95, 92, 1, 0),
(96, 93, 1, 0),
(97, 94, 1, 0),
(98, 95, 1, 0),
(99, 96, 1, 0),
(100, 97, 1, 0),
(101, 98, 1, 0),
(102, 99, 1, 0),
(103, 100, 1, 0),
(104, 101, 1, 0),
(105, 102, 1, 0),
(106, 103, 1, 0),
(107, 104, 1, 0),
(108, 105, 1, 0),
(109, 106, 1, 0),
(110, 107, 1, 0),
(111, 108, 1, 0),
(112, 109, 1, 0),
(113, 110, 1, 0),
(114, 111, 1, 0),
(115, 112, 1, 0),
(116, 113, 1, 0),
(117, 114, 1, 0),
(118, 115, 1, 0),
(119, 116, 1, 0),
(120, 117, 1, 0),
(121, 118, 1, 0),
(122, 119, 1, 0),
(123, 120, 1, 0),
(124, 121, 1, 0),
(125, 122, 1, 0),
(126, 123, 1, 0),
(127, 124, 1, 0),
(128, 125, 1, 0),
(129, 126, 1, 0),
(130, 127, 1, 0),
(131, 128, 1, 0),
(132, 129, 1, 0),
(133, 130, 1, 0),
(134, 131, 1, 0),
(135, 132, 1, 0),
(136, 133, 1, 0),
(137, 134, 1, 0),
(138, 135, 1, 0),
(139, 136, 1, 0),
(140, 137, 1, 0),
(141, 138, 1, 0),
(142, 139, 1, 0),
(143, 140, 1, 0),
(144, 141, 1, 0),
(145, 142, 1, 0),
(146, 143, 1, 0),
(147, 144, 1, 0),
(148, 145, 1, 0),
(149, 146, 1, 0),
(150, 147, 1, 0),
(151, 148, 1, 0),
(152, 149, 1, 0),
(153, 150, 1, 0),
(154, 151, 1, 0),
(155, 152, 1, 0),
(156, 153, 1, 0),
(157, 154, 1, 0),
(158, 155, 1, 0),
(159, 156, 1, 0),
(160, 157, 1, 0),
(161, 158, 1, 0),
(162, 159, 1, 0),
(163, 160, 1, 0),
(164, 161, 1, 0),
(165, 162, 1, 0),
(166, 163, 1, 0),
(167, 164, 1, 0),
(168, 165, 1, 0),
(169, 166, 1, 0),
(170, 167, 1, 0),
(171, 168, 1, 0),
(172, 169, 1, 0),
(173, 170, 1, 0),
(174, 171, 1, 0),
(175, 172, 1, 0),
(176, 173, 1, 0),
(177, 174, 1, 0),
(178, 175, 1, 0),
(179, 176, 1, 0),
(180, 177, 1, 0),
(181, 178, 1, 0),
(182, 179, 1, 0),
(183, 180, 1, 0),
(184, 181, 1, 0),
(185, 182, 1, 0),
(186, 183, 1, 0),
(187, 184, 1, 0),
(188, 185, 1, 0),
(189, 186, 1, 0),
(190, 187, 1, 0),
(191, 188, 1, 0),
(192, 189, 1, 0),
(193, 190, 1, 0),
(194, 191, 1, 0),
(195, 192, 1, 0),
(196, 193, 1, 0),
(197, 194, 1, 0),
(198, 195, 1, 0),
(199, 196, 1, 0),
(200, 197, 1, 0),
(201, 198, 1, 0),
(202, 199, 1, 0),
(203, 200, 1, 0),
(204, 201, 1, 0),
(205, 202, 1, 0),
(206, 203, 1, 0),
(207, 204, 1, 0),
(208, 205, 1, 0),
(209, 206, 1, 0),
(210, 207, 1, 0),
(211, 208, 1, 0),
(212, 209, 1, 0),
(213, 210, 1, 0),
(214, 211, 1, 0),
(215, 212, 1, 0),
(216, 213, 1, 0),
(217, 214, 1, 0),
(218, 215, 1, 0),
(219, 216, 1, 0),
(220, 217, 1, 0),
(221, 218, 1, 0),
(222, 219, 1, 0),
(223, 220, 1, 0),
(224, 221, 1, 0),
(225, 222, 1, 0),
(226, 223, 1, 0),
(227, 224, 1, 0),
(228, 225, 1, 0),
(229, 226, 1, 0),
(230, 227, 1, 0),
(231, 228, 1, 0),
(232, 229, 1, 0),
(233, 230, 1, 0),
(234, 231, 1, 0),
(235, 232, 1, 0),
(236, 233, 1, 0),
(237, 234, 1, 0),
(238, 235, 1, 0),
(239, 236, 1, 0),
(240, 237, 1, 0),
(241, 238, 1, 0),
(242, 239, 1, 0),
(243, 240, 1, 0),
(244, 241, 1, 0),
(245, 242, 1, 0),
(246, 243, 1, 0),
(247, 244, 1, 0),
(248, 245, 1, 0),
(249, 246, 1, 0),
(251, 247, 1, 0),
(267, 12, 2, 400),
(268, 14, 2, 102),
(269, 13, 2, 28),
(270, 1, 2, 51),
(271, 29, 2, 17),
(272, 43, 2, 0),
(273, 21, 2, 6),
(274, 64, 2, 9),
(275, 99, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stocktransfer`
--

CREATE TABLE `tbl_stocktransfer` (
  `transfer_id` int(11) NOT NULL,
  `items` longtext NOT NULL COMMENT 'json',
  `location_to` varchar(100) NOT NULL,
  `location_from` varchar(100) NOT NULL,
  `transfer_by` varchar(50) NOT NULL,
  `date_added` date NOT NULL,
  `date_received` date NOT NULL,
  `received_by` varchar(50) NOT NULL,
  `items_received` longtext,
  `total_amount` int(100) NOT NULL,
  `status` int(10) NOT NULL COMMENT '0 = For Delivery, 1 = Received',
  `with_discrepancy` int(11) NOT NULL DEFAULT '0' COMMENT '0 = false, 1 = true'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_stocktransfer`
--

INSERT INTO `tbl_stocktransfer` (`transfer_id`, `items`, `location_to`, `location_from`, `transfer_by`, `date_added`, `date_received`, `received_by`, `items_received`, `total_amount`, `status`, `with_discrepancy`) VALUES
(1, '[{\"item_id\":\"12\",\"qty\":\"80\"}]', '2', '1', '1', '2020-01-28', '2020-01-28', '37', '[{\"item_id\":12,\"qty\":\"80\"}]', 0, 1, 0),
(2, '[{\"item_id\":\"14\",\"qty\":\"56\"},{\"item_id\":\"12\",\"qty\":\"400\"},{\"item_id\":\"13\",\"qty\":\"38\"},{\"item_id\":\"1\",\"qty\":\"50\"}]', '2', '1', '1', '2020-01-28', '2020-01-28', '37', '[{\"item_id\":14,\"qty\":\"56\"},{\"item_id\":12,\"qty\":\"400\"},{\"item_id\":13,\"qty\":\"38\"},{\"item_id\":1,\"qty\":\"51\"}]', 0, 1, 1),
(3, '[{\"item_id\":\"12\",\"qty\":\"10\"}]', '2', '1', '1', '2020-01-28', '2020-01-28', '37', '[{\"item_id\":12,\"qty\":\"10\"}]', 0, 1, 0),
(4, '[{\"item_id\":\"14\",\"qty\":\"50\"}]', '2', '1', '1', '2020-01-28', '2020-01-28', '37', '[{\"item_id\":14,\"qty\":\"51\"}]', 0, 1, 1),
(5, '[{\"item_id\":\"29\",\"qty\":\"15\"}]', '2', '1', '1', '2020-01-28', '2020-01-28', '37', '[{\"item_id\":29,\"qty\":\"15\"}]', 0, 1, 0),
(6, '[{\"item_id\":\"43\",\"qty\":\"1\"}]', '2', '1', '1', '2020-01-28', '2020-01-28', '37', '[{\"item_id\":43,\"qty\":\"1\"},{\"item_id\":29,\"qty\":\"5\"}]', 0, 1, 1),
(7, '[{\"item_id\":\"21\",\"qty\":\"10\"},{\"item_id\":\"64\",\"qty\":\"5\"}]', '2', '1', '1', '2020-01-28', '2020-01-28', '37', '[{\"item_id\":21,\"qty\":\"10\"},{\"item_id\":64,\"qty\":\"10\"}]', 0, 1, 1),
(8, '[{\"item_id\":\"99\",\"qty\":\"12\"}]', '2', '1', '1', '2020-01-28', '2020-01-28', '37', '[{\"item_id\":99,\"qty\":\"12\"}]', 0, 1, 0),
(9, '[{\"item_id\":\"1\",\"qty\":\"5\"}]', '2', '1', '1', '2020-02-05', '0000-00-00', '', NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` int(11) NOT NULL COMMENT '0 = superuser  , 1 = officeadmin / warehouse man, 2 =sales',
  `user_status` int(11) NOT NULL COMMENT '1= active, 0= not active',
  `trashed` int(11) NOT NULL COMMENT '0 = false, 1 = true'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `username`, `password`, `user_type`, `user_status`, `trashed`) VALUES
(1, 'admin', 'password', 0, 1, 0),
(37, 'sales', 'password', 2, 1, 0),
(38, 'warehouse', 'password', 1, 1, 0),
(39, 'officeadmin', '123', 1, 1, 0),
(40, 'jkmorales', '123', 1, 1, 0),
(41, 'ttest', '3000', 2, 1, 0),
(42, 'pprowaevertest', '123', 2, 1, 0),
(43, 'sales', '123', 2, 1, 0),
(44, 'Marion', 'Holly', 2, 1, 0),
(45, 'Tom', 'Mull', 2, 0, 1),
(46, 'Mull', 'Cory', 2, 1, 0),
(47, 'kmgomez', '123', 2, 1, 0),
(48, 'Mull', 'Uy', 2, 1, 1),
(49, 'Anna', 'Petey', 2, 1, 1),
(50, 'Sue', 'Cruiser', 2, 1, 1),
(51, 'rbriones', '123', 2, 1, 0),
(52, 'mgpequit', '123', 2, 1, 0),
(53, 'ncamotes', '123', 2, 1, 0),
(54, 'ipigon', '123', 2, 1, 0),
(55, 'lzapa', '123', 2, 1, 0),
(56, 'mreyes', '123', 2, 1, 0),
(60, 'pinc', '123', 2, 1, 1),
(61, 'jsmithy', '0945609', 2, 1, 1),
(62, 'jsmithe', '0945609', 2, 1, 1),
(63, 'arosacena', '123', 2, 1, 0),
(64, 'fmespeleta', '123', 2, 1, 0),
(65, 'tlang', '123', 2, 1, 0),
(66, 'ltrest', '123', 0, 1, 0),
(67, 'atesting', '123', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_details`
--

CREATE TABLE `tbl_user_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `birthdate` date NOT NULL,
  `position` varchar(100) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user_details`
--

INSERT INTO `tbl_user_details` (`id`, `user_id`, `fname`, `lname`, `gender`, `birthdate`, `position`, `date_added`) VALUES
(1, 1, 'Danny', 'Phantom', 'Male', '2000-11-01', 'Administrator', '2019-10-01'),
(2, 37, 'enricke janu', 'morales', 'Male', '1998-01-31', 'Sales', '2019-10-09'),
(3, 39, 'Jamnu Ko', 'Morales', 'Male', '1997-10-25', 'Sales', '2019-10-24'),
(4, 38, 'Test', 'Warehouse', 'Male', '1997-10-11', 'Administrator', '2019-10-31'),
(5, 42, 'prowaevertest', 'prowaevertest', 'Male', '1998-01-31', 'Sales', '2019-10-31'),
(6, 43, 'Sales', 'Account', 'Male', '1997-11-07', 'Sales', '2019-11-12'),
(7, 44, 'Mull', 'Bob', 'Female', '2021-12-02', 'Sales', '2019-11-12'),
(8, 45, 'Bob', 'Marion', 'Male', '2021-03-02', 'Sales', '2019-11-12'),
(9, 47, 'Kristina Mae', 'Gomez', 'Female', '1986-04-04', 'Administrator', '2019-11-12'),
(10, 48, 'Mull', 'Brock', 'Male', '1998-01-31', 'Sales', '2019-11-12'),
(11, 49, '12', 'Cliff', 'Male', '1998-01-31', 'Sales', '2019-11-12'),
(12, 50, 'Cory', 'Peter', 'Male', '1998-11-15', 'Sales', '2019-11-14'),
(13, 51, 'Rotchel ', 'Briones', 'Female', '1988-08-25', 'Sales', '2019-11-18'),
(14, 52, 'Mary Grace', 'Pequit', 'Female', '1989-12-07', 'Sales', '2019-11-18'),
(15, 53, 'Noverlina', 'Camotes', 'Female', '1989-11-10', 'Sales', '2019-11-18'),
(16, 54, 'Irene', 'Pigon', 'Male', '1991-04-15', 'Sales', '2019-11-18'),
(17, 55, 'Liezel', 'Zapa', 'Female', '1991-08-26', 'Sales', '2019-11-18'),
(18, 56, 'Malyn', 'Reyes', 'Female', '1997-05-12', 'Sales', '2019-11-18'),
(19, 61, 'john', 'smithy', 'Male', '2019-12-12', 'Sales', '2019-12-04'),
(20, 62, 'johny', 'smithe', 'Male', '2010-12-31', 'Warehouse Manager', '2019-12-04'),
(21, 63, 'Ana', 'Rosacena', 'Female', '1992-07-25', 'Administrator', '2019-12-14'),
(22, 64, 'Flora Mae', 'Espeleta', 'Female', '1959-05-16', 'Administrator', '2019-12-14'),
(23, 65, 'Testing', 'Lang', 'Male', '1997-12-06', 'Sales', '2019-12-16'),
(25, 67, 'Adeleide', 'Testing', 'Female', '1997-12-06', 'Administrator', '2019-12-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_beginning_bal`
--
ALTER TABLE `tbl_beginning_bal`
  ADD PRIMARY KEY (`beg_bag_id`);

--
-- Indexes for table `tbl_brands`
--
ALTER TABLE `tbl_brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_daily_inventory`
--
ALTER TABLE `tbl_daily_inventory`
  ADD PRIMARY KEY (`daily_id`);

--
-- Indexes for table `tbl_inventory_movement`
--
ALTER TABLE `tbl_inventory_movement`
  ADD PRIMARY KEY (`movement_id`);

--
-- Indexes for table `tbl_locations`
--
ALTER TABLE `tbl_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `tbl_monthly_inventory`
--
ALTER TABLE `tbl_monthly_inventory`
  ADD PRIMARY KEY (`monthly_id`);

--
-- Indexes for table `tbl_options`
--
ALTER TABLE `tbl_options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tbl_pull_out`
--
ALTER TABLE `tbl_pull_out`
  ADD PRIMARY KEY (`pull_out_id`);

--
-- Indexes for table `tbl_purchase_order`
--
ALTER TABLE `tbl_purchase_order`
  ADD PRIMARY KEY (`poid`);

--
-- Indexes for table `tbl_request`
--
ALTER TABLE `tbl_request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  ADD PRIMARY KEY (`sales_id`);

--
-- Indexes for table `tbl_stocks`
--
ALTER TABLE `tbl_stocks`
  ADD PRIMARY KEY (`stocks_id`);

--
-- Indexes for table `tbl_stocktransfer`
--
ALTER TABLE `tbl_stocktransfer`
  ADD PRIMARY KEY (`transfer_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_user_details`
--
ALTER TABLE `tbl_user_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_beginning_bal`
--
ALTER TABLE `tbl_beginning_bal`
  MODIFY `beg_bag_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_brands`
--
ALTER TABLE `tbl_brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `tbl_daily_inventory`
--
ALTER TABLE `tbl_daily_inventory`
  MODIFY `daily_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_inventory_movement`
--
ALTER TABLE `tbl_inventory_movement`
  MODIFY `movement_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `tbl_locations`
--
ALTER TABLE `tbl_locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_monthly_inventory`
--
ALTER TABLE `tbl_monthly_inventory`
  MODIFY `monthly_id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_options`
--
ALTER TABLE `tbl_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;

--
-- AUTO_INCREMENT for table `tbl_pull_out`
--
ALTER TABLE `tbl_pull_out`
  MODIFY `pull_out_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_purchase_order`
--
ALTER TABLE `tbl_purchase_order`
  MODIFY `poid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_request`
--
ALTER TABLE `tbl_request`
  MODIFY `request_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  MODIFY `sales_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_stocks`
--
ALTER TABLE `tbl_stocks`
  MODIFY `stocks_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT for table `tbl_stocktransfer`
--
ALTER TABLE `tbl_stocktransfer`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `tbl_user_details`
--
ALTER TABLE `tbl_user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
