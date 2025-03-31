-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 12, 2024 at 07:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `homely`
--

-- --------------------------------------------------------

--
-- Table structure for table `female_services`
--

CREATE TABLE `female_services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `fid` int(25) NOT NULL,
  `name` varchar(45) NOT NULL,
  `cat` varchar(45) NOT NULL,
  `imge` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`fid`, `name`, `cat`, `imge`) VALUES
(3, 'Appam', 'breakfast', 'img1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ji`
--

CREATE TABLE `ji` (
  `id` int(11) NOT NULL,
  `primary_key` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ji`
--

INSERT INTO `ji` (`id`, `primary_key`) VALUES
(1, 'A001'),
(2, 'A002'),
(3, 'A003');

--
-- Triggers `ji`
--
DELIMITER $$
CREATE TRIGGER `generate_primary_key` BEFORE INSERT ON `ji` FOR EACH ROW BEGIN
    DECLARE new_num INT;
    SET new_num = (SELECT IFNULL(MAX(CAST(SUBSTRING(primary_key, 2) AS UNSIGNED)), 0) FROM ji);
    SET NEW.primary_key = CONCAT('A', LPAD(new_num + 1, 3, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `male_services`
--

CREATE TABLE `male_services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `male_services`
--

INSERT INTO `male_services` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'sdfsd', 'sdfsdf', 1212.00, 'img/featur-3.jpg'),
(2, 'sdfsd', 'sdfsdf', 1212.00, 'img/featur-1.jpg'),
(3, 'sdfsd', 'sdfsdf', 1212.00, 'imge/featur-3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_adharr`
--

CREATE TABLE `tbl_adharr` (
  `adharr_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `adharr_no` varchar(45) NOT NULL,
  `adharr_dob` date NOT NULL,
  `fassi_number` varchar(50) NOT NULL,
  `fassi_document` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_adharr`
--

INSERT INTO `tbl_adharr` (`adharr_id`, `name`, `adharr_no`, `adharr_dob`, `fassi_number`, `fassi_document`) VALUES
(1, 'Jithin Joseph', '210864525277', '2003-07-24', '87230945120345', ''),
(2, 'John Doe', '123456789012', '1990-05-15', '56987123456789', ''),
(3, 'Jane Smith', '234567890123', '1985-09-25', '12345678901234', ''),
(4, 'Alice Johnson', '345678901234', '1978-12-10', '98765432109876', ''),
(5, 'Bob Brown', '456789012345', '1993-03-28', '45678901234567', ''),
(6, 'Emma Wilson', '567890123456', '1982-07-18', '32109876543210', ''),
(7, 'Michael Lee', '678901234567', '1998-11-05', '65432109876543', ''),
(8, 'Sophia Taylor', '789012345678', '1975-04-30', '78901234567890', ''),
(9, 'David Martinez', '890123456789', '1991-08-12', '23456789012345', ''),
(10, 'Olivia Garcia', '901234567890', '1987-02-20', '09876543210987', ''),
(11, 'James Rodriguez', '123450987654', '1980-10-08', '76543210987654', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_alternative_addresses`
--

CREATE TABLE `tbl_alternative_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal` varchar(10) NOT NULL,
  `country` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_alternative_addresses`
--

INSERT INTO `tbl_alternative_addresses` (`id`, `user_id`, `street_address`, `city`, `state`, `postal`, `country`, `created_at`) VALUES
(1, 195, 'cheruvallikulamm', 'Ernakulam', 'Kothamangalam', '685532', 'india', '2024-03-06 10:03:22'),
(9, 195, 'goo', 'h', 'kerala', '699787', 'gemni', '2024-03-06 14:20:28'),
(10, 195, 'hello', 'google', 'us', '699787', 'usa', '2024-03-06 14:20:32'),
(11, 195, 'sds', 'xvzx', 'sdsd', '699787', 'xzvcx', '2024-03-06 14:21:11'),
(12, 195, 'sds', 'xvzx', 'zvxv', '699787', 'xzvcx', '2024-03-07 02:59:23'),
(13, 195, 'hf', 'hg', 'hghj', '686510', 'ghfj', '2024-03-07 03:16:21'),
(14, 220, 'cheruvalikulam', 'Idukki', 'Elappara', '685532', 'India', '2024-03-09 05:57:01'),
(19, 220, 'cheruvalikulam', 'Idukki', 'Adimaly', '685532', 'India', '2024-03-09 06:35:02');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `cartid` int(11) NOT NULL,
  `fk_regid` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categories`
--

CREATE TABLE `tbl_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_categories`
--

INSERT INTO `tbl_categories` (`category_id`, `category_name`) VALUES
(1, 'Breakfast Items'),
(2, 'HomeHarvest Lunch\r\n'),
(3, 'Evening Eatables');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_googleusers`
--

CREATE TABLE `tbl_googleusers` (
  `id` int(11) NOT NULL,
  `oauth_provider` enum('google','facebook','twitter','linkedin') NOT NULL DEFAULT 'google',
  `oauth_uid` varchar(50) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_googleusers`
--

INSERT INTO `tbl_googleusers` (`id`, `oauth_provider`, `oauth_uid`, `first_name`, `last_name`, `email`, `picture`, `created`, `modified`) VALUES
(4, 'google', '116535500340550767920', 'jithin', 'joseph', 'josephjithin471@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocIzbkg3SW6NV-26suoGPId7NiEXAjsRYpL27ll-HTeO=s96-c', '2024-03-06 06:28:12', '2024-03-06 06:28:12'),
(5, 'google', '106399582404000918466', 'jithin', 'joseph', 'josephjithin561@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocLDIiDR4chS6GRFLrA0f3rYIn5PXkUy29YLU6NWzjRz=s96-c', '2024-03-06 06:28:55', '2024-03-06 06:28:55'),
(10, 'google', '101454134024387237711', '38-JITHIN JOSEPH', 'INT MCA 2021', 'jithinjoseph2026@mca.ajce.in', 'https://lh3.googleusercontent.com/a/ACg8ocJCZDXi5v_ZoXyKdpT5MMe4iCjeT_7MAjoNtqvBnL-y=s96-c', '2024-04-03 19:27:58', '2024-04-03 19:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_images`
--

CREATE TABLE `tbl_images` (
  `img_id` int(25) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `fk_regid` int(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_images`
--

INSERT INTO `tbl_images` (`img_id`, `filename`, `filepath`, `fk_regid`) VALUES
(169, 'del1.jpg', 'uploads/del1.jpg', 194),
(170, 'avatar.jpg', 'uploads/avatar.jpg', 195),
(171, 'del1.jpg', 'uploads/del1.jpg', 196),
(172, 'del3.jpg', 'uploads/del3.jpg', 197),
(173, 'del1.jpg', 'uploads/del1.jpg', 198),
(191, 'del1.jpg', 'uploads/del1.jpg', 216),
(195, 'del1.jpg', 'uploads/del1.jpg', 220),
(196, 'del1.jpg', 'uploads/del1.jpg', 221),
(197, 'del1.jpg', 'uploads/del1.jpg', 222),
(198, 'del1.jpg', 'uploads/del1.jpg', 223),
(202, 'https://lh3.googleusercontent.com/a/ACg8ocJCZDXi5v_ZoXyKdpT5MMe4iCjeT_7MAjoNtqvBnL-y=s96-c', 'https://lh3.googleusercontent.com/a/ACg8ocJCZDXi5v_ZoXyKdpT5MMe4iCjeT_7MAjoNtqvBnL-y=s96-c', 228);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

CREATE TABLE `tbl_login` (
  `login_id` int(25) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `user_roles` int(45) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_login`
--

INSERT INTO `tbl_login` (`login_id`, `username`, `password`, `user_roles`, `status`) VALUES
(152, 'admin', 'admin1', 3, 1),
(198, 'fastandtrack61@gmail.com', 'jithin12@123', 1, 1),
(199, 'josephjithin561@gmail.com', 'jithin12@123', 0, 1),
(200, 'josephjithin471@gmail.com', 'jithin12@123', 1, 1),
(201, 'jjithin155@gmail.com', 'jithin12@123', 1, 1),
(220, 'jjithin512@gmail.com', 'jithin12@123', 1, 1),
(224, 'fastandtsdrack61@gmail.com', 'jithin12@123', 0, 1),
(225, 'fastandtrack611@gmail.com', 'jithin12@123', 1, 1),
(226, 'fastandtrack612@gmail.com', 'jithin12@123', 1, 1),
(227, 'fastandtrack616@gmail.com', 'jithin12@123', 0, 1),
(232, 'jithinjoseph2026@mca.ajce.in', 'jithin12', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `msg_type` varchar(50) NOT NULL DEFAULT 'normal',
  `from_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`notification_id`, `user_id`, `message`, `status`, `created_at`, `msg_type`, `from_id`) VALUES
(77, 221, 'Dear Jithin Joseph, you have a pre-booking request for Appam from Alappuzha, Kayamkulam. by jithin joseph with 12', 'read', '2024-03-19 10:11:42', 'request', 53),
(79, 221, 'Dear Jithin Joseph, you have a pre-booking request for Appam from Alappuzha, Kayamkulam. by jithin joseph with 13', 'read', '2024-03-19 10:13:10', 'request', 54),
(86, 222, 'Dear Jithin Joseph, you have a pre-booking request for Appam from Alappuzha, Kayamkulam. by jithin joseph with 1', 'read', '2024-03-19 13:22:54', 'request', 57),
(88, 222, 'Dear Jithin Joseph, you have a pre-booking request for Appam from Alappuzha, Kayamkulam. by jithin joseph with 12', 'read', '2024-03-19 17:08:39', 'request', 58),
(90, 222, 'Dear Jithin Joseph, you have a pre-booking request for Appam from Alappuzha, Kayamkulam. by jithin joseph with 5', 'read', '2024-03-26 10:20:09', 'request', 59),
(91, 221, 'Dear Jithin Joseph, you have a pre-booking request for Appam from Alappuzha, Kayamkulam. by jithin joseph with 1', 'read', '2024-03-26 10:29:44', 'request', 60),
(93, 221, 'Dear Jithin Joseph, you have a pre-booking request for Appam from Alappuzha, Kayamkulam. by jithin joseph with 1', 'read', '2024-03-30 04:58:47', 'request', 61),
(101, 222, 'You have a new order. Please check your dashboard.', 'unread', '2024-03-30 16:04:34', 'normal', 78),
(102, 221, 'You have a new order. Please check your dashboard.', 'unread', '2024-03-30 16:04:34', 'normal', 79),
(279, 221, 'You have scheduled delivery of  Appam by Today.check it been delivered.', 'unread', '2024-04-01 08:29:10', 'order', 10),
(280, 221, 'You have scheduled delivery of  Appam by Today.check it started transit.', 'unread', '2024-04-01 08:29:10', 'order', 9),
(281, 221, 'You have a new order. Please check your dashboard.', 'unread', '2024-04-01 11:17:02', 'normal', 90),
(282, 221, 'You have a new order. Please check your dashboard.', 'unread', '2024-04-01 11:17:02', 'normal', 91),
(283, 221, 'You have a new order. Please check your dashboard.', 'unread', '2024-04-01 11:32:51', 'normal', 92),
(284, 221, 'You have a new order. Please check your dashboard.', 'unread', '2024-04-01 11:32:51', 'normal', 93),
(285, 221, 'You have scheduled delivery of  apple by Today.check it ongoing.', 'unread', '2024-04-02 03:45:49', 'order', 6),
(286, 221, 'The order has been accepted by another seller.', 'unread', '2024-04-02 05:10:49', 'request', 65),
(287, 222, 'Dear Jithin Joseph, you have a pre-booking request for Appam from Alappuzha, Kayamkulam. by jithin joseph with 10', 'read', '2024-04-02 05:10:49', 'request', 65),
(288, 222, 'You have a new order. Please check your dashboard.', 'unread', '2024-04-02 07:09:33', 'normal', 97),
(289, 221, 'You have scheduled delivery of  apple by Today.check it final day.', 'unread', '2024-04-03 04:08:11', 'order', 11),
(290, 221, 'You have scheduled delivery of  Appam by Today.check it ongoing.', 'unread', '2024-04-03 07:10:15', 'order', 9),
(291, 221, 'You have scheduled delivery of  apple by Today.check it ongoing.', 'unread', '2024-04-03 07:10:15', 'order', 6),
(292, 222, 'You have scheduled delivery of  Appam by Today.check it ongoing.', 'unread', '2024-04-03 13:34:05', 'order', 12),
(293, 221, 'You have scheduled delivery of  Appam by Today.check it ongoing.', 'unread', '2024-04-03 13:36:26', 'order', 13),
(294, 221, 'You have scheduled delivery of  apple by Today.check it ongoing.', 'unread', '2024-04-03 13:40:28', 'order', 14),
(295, 222, 'You have scheduled delivery of  Appam by Today.check it ongoing.', 'unread', '2024-04-05 06:54:21', 'order', 12),
(296, 222, 'You have scheduled delivery of  Appam by Today.check it ongoing.', 'unread', '2024-04-05 06:54:21', 'order', 7),
(297, 221, 'The order has been accepted by another seller.', 'unread', '2024-04-05 10:24:23', 'request', 66),
(298, 222, 'Dear Jithin Joseph, you have a pre-booking request for Appam from Alappuzha, Kayamkulam. by jithin joseph with 15', 'read', '2024-04-05 10:24:23', 'request', 66);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification_dates`
--

CREATE TABLE `tbl_notification_dates` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `notification_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notification_dates`
--

INSERT INTO `tbl_notification_dates` (`id`, `schedule_id`, `notification_date`) VALUES
(7, 6, '2024-04-02'),
(10, 6, '2024-04-03'),
(15, 7, '2024-04-05'),
(6, 9, '2024-04-01'),
(9, 9, '2024-04-03'),
(5, 10, '2024-04-01'),
(8, 11, '2024-04-03'),
(11, 12, '2024-04-03'),
(14, 12, '2024-04-05'),
(12, 13, '2024-04-03'),
(13, 14, '2024-04-03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `order_id` int(11) NOT NULL,
  `fk_preid` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`order_id`, `fk_preid`, `user_id`, `order_date`, `order_status`) VALUES
(3, 53, 195, '2024-03-19 10:12:20', '4'),
(4, 54, 195, '2024-03-19 10:13:22', '4'),
(5, 57, 195, '2024-03-19 13:24:09', '4'),
(6, 58, 195, '2024-03-19 17:08:59', '4'),
(7, 2, 195, '2024-03-19 19:26:34', '1'),
(8, 3, 195, '2024-03-19 19:35:24', '2'),
(9, 4, 195, '2024-03-19 19:36:36', '3'),
(10, 5, 195, '2024-03-19 19:42:18', '4'),
(11, 6, 195, '2024-03-19 19:46:27', '4'),
(12, 59, 195, '2024-03-26 10:21:37', '4'),
(13, 60, 195, '2024-03-26 10:30:49', '4'),
(14, 60, 195, '2024-03-26 10:30:49', '4'),
(15, 60, 195, '2024-03-26 10:30:49', '4'),
(78, 69, 195, '2024-03-30 16:04:02', '4'),
(79, 70, 195, '2024-03-30 16:04:02', '1'),
(80, 62, 195, '2024-03-31 04:31:25', '1'),
(81, 63, 195, '2024-03-31 05:10:35', '1'),
(82, 64, 195, '2024-03-31 05:25:58', '4'),
(83, 64, 195, '2024-03-31 06:22:34', '4'),
(84, 64, 195, '2024-03-31 06:23:06', '1'),
(85, 64, 195, '2024-04-01 01:36:50', '1'),
(86, 64, 195, '2024-04-01 02:06:17', '1'),
(87, 64, 195, '2024-04-01 02:10:31', '1'),
(88, 71, 195, '2024-04-01 11:02:50', '1'),
(89, 72, 195, '2024-04-01 11:06:41', '1'),
(90, 73, 195, '2024-04-01 11:15:38', '1'),
(91, 74, 195, '2024-04-01 11:15:38', '1'),
(92, 75, 195, '2024-04-01 11:32:22', '1'),
(93, 76, 195, '2024-04-01 11:32:22', '1'),
(94, 77, 195, '2024-04-02 05:09:04', '1'),
(95, 65, 195, '2024-04-02 05:11:11', '4'),
(96, 65, 195, '2024-04-02 05:12:03', '1'),
(97, 78, 195, '2024-04-02 07:08:48', '1'),
(98, 79, 195, '2024-04-05 10:21:26', '1'),
(99, 66, 195, '2024-04-05 10:25:14', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_details`
--

CREATE TABLE `tbl_order_details` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_order_details`
--

INSERT INTO `tbl_order_details` (`id`, `product_id`, `quantity`, `price`) VALUES
(2, 49, 8, 960.00),
(3, 49, 1, 120.00),
(4, 49, 7, 840.00),
(5, 49, 2, 240.00),
(6, 49, 1, 120.00),
(69, 50, 1, 12.00),
(70, 48, 1, 100.00),
(71, 48, 12, 1200.00),
(72, 48, 1, 100.00),
(73, 49, 4, 48.00),
(74, 48, 1, 100.00),
(75, 48, 1, 100.00),
(76, 49, 1, 12.00),
(77, 48, 4, 400.00),
(78, 50, 1, 12.00),
(79, 48, 1, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pincodes`
--

CREATE TABLE `tbl_pincodes` (
  `pinid` int(11) NOT NULL,
  `place` varchar(100) DEFAULT NULL,
  `District` varchar(50) DEFAULT NULL,
  `method` varchar(25) NOT NULL DEFAULT 'CDS',
  `fk_regid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pincodes`
--

INSERT INTO `tbl_pincodes` (`pinid`, `place`, `District`, `method`, `fk_regid`) VALUES
(104, 'Palakkad', 'Palakkad', 'OD', 216),
(105, 'Ottappalam', 'Palakkad', 'OD', 216),
(106, 'Chittur', 'Palakkad', 'OD', 216),
(107, 'Mannarkkad', 'Palakkad', 'OD', 216),
(108, 'Munnar', 'Idukki', 'OD', 216),
(109, 'Adimaly', 'Idukki', 'OD', 216),
(110, 'Painavu', 'Idukki', 'OD', 216),
(111, 'Rajakkad', 'Idukki', 'OD', 216),
(112, 'Peerumedu', 'Idukki', 'OD', 216),
(113, 'Vandiperiyar', 'Idukki', 'OD', 216),
(114, 'Thodupuzha', 'Idukki', 'OD', 216),
(115, 'Alappuzha', 'Alappuzha', 'OD', 221),
(116, 'Chengannur', 'Alappuzha', 'OD', 221),
(117, 'Mavelikkara', 'Alappuzha', 'OD', 221),
(118, 'Kayamkulam', 'Alappuzha', 'OD', 221),
(119, 'Kollam', 'Kollam', 'OD', 222),
(120, 'Punalur', 'Kollam', 'OD', 222),
(121, 'Karunagappally', 'Kollam', 'OD', 222),
(122, 'Alappuzha', 'Alappuzha', 'OD', 222),
(123, 'Chengannur', 'Alappuzha', 'OD', 222),
(124, 'Mavelikkara', 'Alappuzha', 'OD', 222),
(125, 'Kayamkulam', 'Alappuzha', 'OD', 222),
(126, 'Haripad', 'Alappuzha', 'OD', 222),
(127, 'Kuttanad', 'Alappuzha', 'OD', 222);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pre_orders`
--

CREATE TABLE `tbl_pre_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `pre_status` int(11) NOT NULL DEFAULT 0,
  `delivery_date` date NOT NULL,
  `delivery_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_pre_orders`
--

INSERT INTO `tbl_pre_orders` (`id`, `user_id`, `product_id`, `quantity`, `pre_status`, `delivery_date`, `delivery_time`, `created_at`) VALUES
(53, 195, 49, 12, 1, '2024-03-20', '16:42:00', '2024-03-19 10:11:42'),
(54, 195, 49, 13, 1, '2024-03-20', '16:43:00', '2024-03-19 10:13:10'),
(57, 195, 50, 1, 1, '2024-03-20', '14:57:00', '2024-03-19 13:22:54'),
(58, 195, 50, 12, 1, '2024-03-20', '14:42:00', '2024-03-19 17:08:39'),
(59, 195, 50, 5, 1, '2024-03-28', '14:00:00', '2024-03-26 10:20:09'),
(60, 195, 49, 1, 1, '2024-03-27', '17:00:00', '2024-03-26 10:29:44'),
(61, 195, 49, 1, 1, '2024-03-30', '14:32:00', '2024-03-30 04:58:47'),
(64, 195, 50, 6, 1, '2024-04-01', '14:59:00', '2024-03-31 05:25:44'),
(65, 195, 50, 10, 1, '2024-04-03', '14:44:00', '2024-04-02 05:10:48'),
(66, 195, 50, 15, 1, '2024-04-06', '14:00:00', '2024-04-05 10:24:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `fk_regid` int(11) NOT NULL,
  `p_status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`product_id`, `product_name`, `price`, `category_id`, `photo_path`, `stock`, `fk_regid`, `p_status`) VALUES
(32, 'Appam', 12, 1, 'img1.jpg', 30, 194, 1),
(35, 'Appam', 12, 1, 'img1.jpg', 51, 197, 1),
(39, 'sadhya', 120, 2, 'img2.jpg', 4, 197, 1),
(40, 'sadhya', 120, 2, 'img2.jpg', 5, 194, 1),
(41, 'stawberry', 11, 3, 'featur-2.jpg', 6, 194, 1),
(42, 'stawberry', 8, 1, 'featur-2.jpg', 23, 196, 1),
(43, 'orange', 25, 3, 'fruite-item-1.jpg', 4, 196, 1),
(44, 'sadhya', 120, 2, 'img2.jpg', 1, 196, 1),
(45, 'Appam', 12, 1, 'img1.jpg', 1, 196, 1),
(46, 'pazhampori', 12, 1, 'img3.jpg', 1, 196, 1),
(47, 'Appam', 12, 1, 'best-product-6.jpg', 1, 216, 1),
(48, 'apple', 100, 3, 'featur-1.jpg', 5, 221, 1),
(49, 'Appam', 12, 1, 'img1.jpg', 0, 221, 1),
(50, 'Appam', 12, 1, 'img1.jpg', 0, 222, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ratings`
--

CREATE TABLE `tbl_ratings` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_ratings`
--

INSERT INTO `tbl_ratings` (`id`, `product_id`, `order_id`, `user_id`, `rating`, `created_at`) VALUES
(5, 49, 11, 195, 2, '2024-03-31 17:04:47'),
(8, 49, 10, 195, 5, '2024-03-31 17:21:50'),
(9, 50, 78, 195, 5, '2024-04-01 02:44:59');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_registration`
--

CREATE TABLE `tbl_registration` (
  `regid` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal` varchar(10) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `age` int(11) NOT NULL,
  `fk_loginid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_registration`
--

INSERT INTO `tbl_registration` (`regid`, `full_name`, `dob`, `gender`, `street_address`, `city`, `state`, `postal`, `country`, `phone`, `age`, `fk_loginid`) VALUES
(148, 'Admin', '2002-01-07', 'Male', 'Cheruvalikkulmam', 'Cityville', 'Stateville', '123456', 'Countryville', '6238537097', 22, 152),
(194, 'John Doe', '1990-05-15', 'Male', 'cheruvallikulam murijapuzha', 'peruvanthanam', 'kerala', '685530', 'india', '6238357097', 34, 198),
(195, 'jithin joseph', '2003-07-24', 'Male', 'aruvithara', 'Alappuzha', 'Kayamkulam', '685536', 'India', '6238537097', 21, 199),
(196, 'Jane Smith', '1985-09-25', 'Male', 'OakStreet\r\nLosAngelesCA', 'LosSan', ' Maple Avenue', '606011', 'india', '6238357097', 39, 200),
(216, 'Jithin Joseph', '2003-03-24', 'Male', 'cheruvalikulam', 'Idukki', 'Thekkady', '685532', 'India', '6238537097', 21, 220),
(220, 'Jithin Joseph', '2003-07-24', 'Male', 'cheruvalikulam', 'Idukki', 'Thodupuzha', '685532', 'India', '6238537097', 21, 224),
(221, 'Jithin Joseph', '2003-07-24', 'Male', 'aruvithara', 'Alappuzha', 'Kayamkulam', '685536', 'India', '6238537097', 21, 225),
(222, 'Jithin Joseph', '2003-07-24', 'Male', 'cheruvalikulam', 'Alappuzha', 'Kuttanad', '685532', 'India', '6238537097', 21, 226),
(223, 'jone', '2009-07-24', 'Male', 'cheruvalikulam', 'Idukki', 'Munnar', '685532', 'India', '6238537097', 15, 227),
(228, '38-JITHIN JOSEPH INT MCA 2021', '2012-02-03', 'male', 'cheruvalikulam', 'Idukki', 'Peerumedu', '685532', 'India', '06238537097', 12, 232);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_save_for_later`
--

CREATE TABLE `tbl_save_for_later` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_schedule`
--

CREATE TABLE `tbl_schedule` (
  `schedule_id` int(11) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `status` enum('Scheduled','In Transit','Delivered') NOT NULL DEFAULT 'Scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `schedule_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_schedule`
--

INSERT INTO `tbl_schedule` (`schedule_id`, `from_date`, `to_date`, `user_id`, `order_id`, `status`, `created_at`, `schedule_status`) VALUES
(6, '2024-04-02', '2024-04-03', '195', '79', 'Delivered', '2024-04-01 05:10:29', 4),
(7, '2024-04-04', '2024-04-18', '195', '78', 'In Transit', '2024-04-01 05:10:29', 1),
(8, '2024-04-09', '2024-04-12', '195', '11', 'Scheduled', '2024-04-01 05:10:29', 1),
(9, '2024-04-01', '2024-04-03', '195', '10', 'Delivered', '2024-04-01 07:21:07', 1),
(10, '2024-04-01', '2024-04-01', '195', '8', 'Delivered', '2024-04-01 07:21:18', 1),
(11, '2024-04-03', '2024-04-03', '195', '94', 'Delivered', '2024-04-03 03:13:41', 1),
(12, '2024-04-03', '2024-04-06', '195', '97', 'In Transit', '2024-04-03 13:28:46', 1),
(13, '2024-04-03', '2024-04-04', '195', '90', 'Delivered', '2024-04-03 13:35:20', 4),
(14, '2024-04-03', '2024-04-04', '195', '92', 'Delivered', '2024-04-03 13:39:46', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transactions`
--

CREATE TABLE `tbl_transactions` (
  `transaction_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_id` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_transactions`
--

INSERT INTO `tbl_transactions` (`transaction_id`, `order_id`, `payment_id`, `amount`, `transaction_date`, `payment_status`) VALUES
(2, 5, 'pay_NoHOkXkeIE0L7v', 12.00, '2024-03-19 16:27:55', 'completed'),
(3, 4, 'pay_NoHqIAD7LuZDcJ', 1560.00, '2024-03-19 16:54:01', 'completed'),
(4, 3, 'pay_NoHsM9MRpQBQw7', 1440.00, '2024-03-19 16:55:58', 'completed'),
(5, 6, 'pay_NoIDTJ2CZhWxRW', 144.00, '2024-03-19 17:16:00', 'completed'),
(6, 49, 'pay_NoK949xeFNBSri', 120.00, '2024-03-19 19:09:10', 'completed'),
(7, 8, 'pay_NoKamteZek3HRh', 120.00, '2024-03-19 19:35:24', 'completed'),
(8, 9, 'pay_NoKc3Hroy7BmD8', 840.00, '2024-03-19 19:36:36', 'completed'),
(9, 10, 'pay_NoKi6Ya2eePBu5', 240.00, '2024-03-19 19:42:18', 'completed'),
(10, 11, 'pay_NoKmTJ2wwEjwEO', 120.00, '2024-03-19 19:46:27', 'completed'),
(11, 13, 'pay_NqxHceQOKc8909', 120.00, '2024-03-26 10:44:29', 'completed'),
(12, 7, 'pay_NqxPJTYBSTGSkC', 960.00, '2024-03-26 10:51:46', 'completed'),
(26, 78, 'pay_NscsHvXBF01zoJ', 12.00, '2024-03-30 16:04:34', 'completed'),
(27, 79, 'pay_NscsHvXBF01zoJ', 100.00, '2024-03-30 16:04:34', 'completed'),
(28, 88, 'pay_NtKnoGEX1DvRQy', 1200.00, '2024-04-01 11:02:50', 'completed'),
(29, 89, 'pay_NtKrqatO51ksNk', 100.00, '2024-04-01 11:06:41', 'completed'),
(30, 90, 'pay_NtL2n7vqX3Fzll', 48.00, '2024-04-01 11:17:02', 'completed'),
(31, 91, 'pay_NtL2n7vqX3Fzll', 100.00, '2024-04-01 11:17:02', 'completed'),
(32, 92, 'pay_NtLJSHm2mrvcAj', 100.00, '2024-04-01 11:32:51', 'completed'),
(33, 93, 'pay_NtLJSHm2mrvcAj', 12.00, '2024-04-01 11:32:51', 'completed'),
(34, 94, 'pay_NtdJE4r2XSZQxI', 400.00, '2024-04-02 05:09:04', 'completed'),
(35, 95, 'pay_NtdNHcVeiLtA5g', 120.00, '2024-04-02 05:12:53', 'completed'),
(36, 97, 'pay_NtfMTx0aS1BfUy', 12.00, '2024-04-02 07:09:33', 'completed'),
(37, 98, 'pay_NuuERkj7yoRg6l', 100.00, '2024-04-05 10:21:26', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `tbl_user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_verification`
--

CREATE TABLE `tbl_verification` (
  `v_id` int(11) NOT NULL,
  `email_verify` int(11) DEFAULT NULL,
  `adharr_verify` int(11) DEFAULT NULL,
  `fassi_verify` int(11) DEFAULT NULL,
  `fassi_document` varchar(50) DEFAULT NULL,
  `fk_regid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_verification`
--

INSERT INTO `tbl_verification` (`v_id`, `email_verify`, `adharr_verify`, `fassi_verify`, `fassi_document`, `fk_regid`) VALUES
(18, 1, 1, 1, '59875_5020_mIoMY.pdf', 194),
(19, 1, NULL, NULL, NULL, 195),
(20, 1, 1, 1, 'ne.pdf', 196),
(21, 1, 1, 1, 'ne.pdf', 197),
(39, 1, 0, 0, 'ne.pdf', 216),
(43, 1, NULL, NULL, NULL, 220),
(44, 1, 1, 1, 'ne.pdf', 221),
(45, 1, 1, 1, 'html.to.design (Community) (2).pdf', 222),
(46, 1, NULL, NULL, NULL, 223);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `female_services`
--
ALTER TABLE `female_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `ji`
--
ALTER TABLE `ji`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `primary_key` (`primary_key`);

--
-- Indexes for table `male_services`
--
ALTER TABLE `male_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_adharr`
--
ALTER TABLE `tbl_adharr`
  ADD PRIMARY KEY (`adharr_id`);

--
-- Indexes for table `tbl_alternative_addresses`
--
ALTER TABLE `tbl_alternative_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`cartid`);

--
-- Indexes for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `tbl_googleusers`
--
ALTER TABLE `tbl_googleusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD PRIMARY KEY (`img_id`);

--
-- Indexes for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`login_id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `tbl_notification_dates`
--
ALTER TABLE `tbl_notification_dates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_schedule_date` (`schedule_id`,`notification_date`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pincodes`
--
ALTER TABLE `tbl_pincodes`
  ADD PRIMARY KEY (`pinid`);

--
-- Indexes for table `tbl_pre_orders`
--
ALTER TABLE `tbl_pre_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tbl_ratings`
--
ALTER TABLE `tbl_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_registration`
--
ALTER TABLE `tbl_registration`
  ADD PRIMARY KEY (`regid`);

--
-- Indexes for table `tbl_save_for_later`
--
ALTER TABLE `tbl_save_for_later`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_schedule`
--
ALTER TABLE `tbl_schedule`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`tbl_user_id`);

--
-- Indexes for table `tbl_verification`
--
ALTER TABLE `tbl_verification`
  ADD PRIMARY KEY (`v_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `female_services`
--
ALTER TABLE `female_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `fid` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ji`
--
ALTER TABLE `ji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `male_services`
--
ALTER TABLE `male_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_adharr`
--
ALTER TABLE `tbl_adharr`
  MODIFY `adharr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_alternative_addresses`
--
ALTER TABLE `tbl_alternative_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `cartid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=934;

--
-- AUTO_INCREMENT for table `tbl_categories`
--
ALTER TABLE `tbl_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_googleusers`
--
ALTER TABLE `tbl_googleusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_images`
--
ALTER TABLE `tbl_images`
  MODIFY `img_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `tbl_login`
--
ALTER TABLE `tbl_login`
  MODIFY `login_id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=233;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=299;

--
-- AUTO_INCREMENT for table `tbl_notification_dates`
--
ALTER TABLE `tbl_notification_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `tbl_pincodes`
--
ALTER TABLE `tbl_pincodes`
  MODIFY `pinid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `tbl_pre_orders`
--
ALTER TABLE `tbl_pre_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `tbl_ratings`
--
ALTER TABLE `tbl_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_registration`
--
ALTER TABLE `tbl_registration`
  MODIFY `regid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT for table `tbl_save_for_later`
--
ALTER TABLE `tbl_save_for_later`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- AUTO_INCREMENT for table `tbl_schedule`
--
ALTER TABLE `tbl_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `tbl_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_verification`
--
ALTER TABLE `tbl_verification`
  MODIFY `v_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
