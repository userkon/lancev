-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 12:23 PM
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
-- Database: `scms`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Macchiato'),
(2, 'Milktea'),
(3, 'Premium Milktea'),
(4, 'Cheesecakes'),
(5, 'Fruit Tea'),
(6, 'Soda Pop'),
(7, 'Slushee'),
(8, 'Frappe');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CUST_ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(50) DEFAULT NULL,
  `LAST_NAME` varchar(50) DEFAULT NULL,
  `PHONE_NUMBER` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CUST_ID`, `FIRST_NAME`, `LAST_NAME`, `PHONE_NUMBER`) VALUES
(9, 'Num', '5', '09394566543'),
(11, 'Number', '1', '0000000000'),
(14, 'Num', '4', '09781633451'),
(15, 'Number', '3', '09956288467'),
(16, 'Num', '2', '09891344576'),
(17, 'Frances', 'Deogracias', '09218771893');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `EMPLOYEE_ID` int(11) NOT NULL,
  `FIRST_NAME` varchar(50) DEFAULT NULL,
  `LAST_NAME` varchar(50) DEFAULT NULL,
  `GENDER` varchar(50) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `PHONE_NUMBER` varchar(11) DEFAULT NULL,
  `JOB_ID` int(11) DEFAULT NULL,
  `HIRED_DATE` varchar(50) NOT NULL,
  `LOCATION_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`EMPLOYEE_ID`, `FIRST_NAME`, `LAST_NAME`, `GENDER`, `EMAIL`, `PHONE_NUMBER`, `JOB_ID`, `HIRED_DATE`, `LOCATION_ID`) VALUES
(1, 'Owner', 'Client', 'Male', 'admin@gmail.com', '01004321347', 1, '0000-00-00', 113),
(2, 'Staff ', 'Account', 'Male', 'lanceg@gmail.com', '09094341516', 2, '2024-06-30', 156),
(4, 'Staff1', 'Only', 'Male', 'endcruz@gmail.com', '08736621516', 1, '2024-07-21', 158);

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `INGREDIENTS_ID` int(10) UNSIGNED NOT NULL,
  `INGREDIENTS_CODE` varchar(8) NOT NULL,
  `ING_NAME` varchar(255) NOT NULL,
  `ING_QUANTITY` int(11) NOT NULL,
  `STOCK_DATE` date NOT NULL,
  `SUPPLIER_ID` int(11) NOT NULL,
  `unit` enum('kg','g','tbsp') NOT NULL,
  `DESCRIPTION` text DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`INGREDIENTS_ID`, `INGREDIENTS_CODE`, `ING_NAME`, `ING_QUANTITY`, `STOCK_DATE`, `SUPPLIER_ID`, `unit`, `DESCRIPTION`, `category_id`) VALUES
(1, 'ING0001', 'Coffee Beans', 2, '2024-11-23', 16, 'kg', 'beansss', 0),
(3, 'ING0003', 'Matcha Powder', 100, '2024-11-23', 11, 'g', 'yum', 0),
(4, 'ING0004', 'Taro Powder', 3, '2024-11-23', 11, 'g', 'yay', 0),
(5, 'ING0005', 'Boba Pearl', 2, '2024-11-23', 15, 'kg', 'wow pearll', 0),
(6, 'ING0006', 'Water', 5, '2024-11-24', 13, '', 'gulp', 0),
(7, 'ING0007', 'Mlik', 5, '2024-11-24', 15, '', 'yum', 0),
(8, 'ING0008', 'Strawberry Syrup', 5, '2024-11-24', 16, 'g', 'yay', 0),
(9, 'ING0009', 'Okinawa Powder', 5, '2024-11-24', 13, 'g', 'yumy', 0),
(10, 'ING0010', 'Caramel Syrup', 2, '2024-11-24', 15, '', 'uy yummy', 0),
(11, 'ING0011', 'Chocolate', 2, '2024-11-25', 12, 'g', 'eee', 0),
(12, 'ING0012', 'Oreo', 2, '2024-11-25', 13, '', 's', 0),
(13, 'ING0013', 'matcha frappe ing', 2, '2024-11-25', 11, 'g', 'test', 0),
(14, 'ING0014', 'blueberry ing', 2, '2024-11-25', 12, '', 'test', 0),
(15, 'ING0015', 'slushee ingredient', 2, '2024-11-25', 11, 'g', 'test', 0),
(16, 'ING0016', 'cheesecake ', 5, '2024-11-25', 11, '', 'test', 0),
(17, 'ING0017', 'asdfa6&*/?', 24, '1989-12-06', 17, 'g', '13245675@#$%^&*', 0);

--
-- Triggers `ingredients`
--
DELIMITER $$
CREATE TRIGGER `before_insert_ingredient` BEFORE INSERT ON `ingredients` FOR EACH ROW BEGIN
    SET NEW.INGREDIENTS_CODE = CONCAT('ING', LPAD(NEW.INGREDIENTS_ID, 4, '0'));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `JOB_ID` int(11) NOT NULL,
  `JOB_TITLE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`JOB_ID`, `JOB_TITLE`) VALUES
(1, 'Manager'),
(2, 'Cashier');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `LOCATION_ID` int(11) NOT NULL,
  `PROVINCE` varchar(100) DEFAULT NULL,
  `CITY` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`LOCATION_ID`, `PROVINCE`, `CITY`) VALUES
(111, 'Metro Manila', 'Valenzuela '),
(113, 'Metro Manila', 'Caloocan'),
(114, 'Metro Manila', 'Caloocan'),
(115, 'Metro Manila', 'Caloocan'),
(116, 'Metro Manila', 'Quezon City'),
(155, 'Metro Manila', 'Quezon City'),
(156, 'Metro Manila', 'Caloocan'),
(158, 'Metro Manila', 'Quezon City'),
(159, 'Metro Manila', 'Caloocan'),
(160, '', ''),
(161, 'Metro Manila', 'Quezon City');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `FIRST_NAME` varchar(50) DEFAULT NULL,
  `LAST_NAME` varchar(50) DEFAULT NULL,
  `LOCATION_ID` int(11) NOT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `PHONE_NUMBER` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`FIRST_NAME`, `LAST_NAME`, `LOCATION_ID`, `EMAIL`, `PHONE_NUMBER`) VALUES
('Lance', 'Vidallon', 113, 'admin@gmail.com', '0123456789');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `PRODUCT_ID` int(11) NOT NULL,
  `PRODUCT_CODE` varchar(20) NOT NULL,
  `NAME` varchar(50) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `ing_name` varchar(255) DEFAULT NULL,
  `recipe_name` varchar(255) DEFAULT NULL,
  `DESCRIPTION` varchar(250) NOT NULL,
  `QTY_STOCK` int(50) DEFAULT NULL,
  `ON_HAND` int(250) NOT NULL,
  `PRICE` int(50) DEFAULT NULL,
  `expenses` decimal(10,2) DEFAULT NULL,
  `CATEGORY_ID` int(11) DEFAULT NULL,
  `SUPPLIER_ID` int(11) DEFAULT NULL,
  `DATE_STOCK_IN` varchar(50) NOT NULL,
  `recipe_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`PRODUCT_ID`, `PRODUCT_CODE`, `NAME`, `quantity`, `unit`, `ing_name`, `recipe_name`, `DESCRIPTION`, `QTY_STOCK`, `ON_HAND`, `PRICE`, `expenses`, `CATEGORY_ID`, `SUPPLIER_ID`, `DATE_STOCK_IN`, `recipe_id`) VALUES
(175, 'PROD173', 'Matcha Milktea', NULL, NULL, NULL, NULL, 'yayyy', 1, 1, NULL, NULL, 2, NULL, '2024-11-24', 20),
(176, 'PROD176', 'TaroMilktea', NULL, NULL, NULL, NULL, 'aaaaaaaa', 1, 1, NULL, NULL, 2, NULL, '2024-11-24', 19),
(177, 'PROD177', 'Strawberry Milktea', NULL, NULL, NULL, NULL, 'yeyyy', 1, 1, NULL, NULL, 2, NULL, '2024-11-24', 21),
(178, 'PROD178', 'Iced Salted Caramel', NULL, NULL, NULL, NULL, 'yahooo', 1, 1, NULL, NULL, 1, NULL, '2024-11-24', 22),
(179, 'PROD179', 'Okinawa Milktea', NULL, NULL, NULL, NULL, 'yumyyyy', 1, 1, NULL, NULL, 2, NULL, '2024-11-24', 23),
(180, 'PROD180', 'Chocolate Milk Tea', NULL, NULL, NULL, NULL, 'test', 1, 1, NULL, NULL, 2, NULL, '2024-11-25', 24),
(181, 'PROD181', 'Iced Salted Caramel Macchiato', NULL, NULL, NULL, NULL, 'e', 1, 1, NULL, NULL, 1, NULL, '2024-11-25', 22),
(182, 'PROD182', 'Oreo Cheesecake', NULL, NULL, NULL, NULL, 'e', 1, 1, NULL, NULL, 4, NULL, '2024-11-25', 25),
(183, 'PROD183', 'Matcha Frappe ', NULL, NULL, NULL, NULL, 'test', 1, 1, NULL, NULL, 8, NULL, '2024-11-25', 26),
(184, 'PROD184', 'Blueberry Fruit Tea', NULL, NULL, NULL, NULL, 'test', 1, 1, NULL, NULL, 5, NULL, '2024-11-25', 27),
(185, 'PROD185', 'Dark Chocolate Milktea', NULL, NULL, NULL, NULL, 'test', 1, 1, NULL, NULL, 3, NULL, '2024-11-25', 24),
(186, 'PROD186', 'Blueberry slushee', NULL, NULL, NULL, NULL, 't', 1, 1, NULL, NULL, 7, NULL, '2024-11-25', 28),
(187, 'PROD187', 'Blueberry soda pop', NULL, NULL, NULL, NULL, 'test', 1, 1, NULL, NULL, 6, NULL, '2024-11-25', 28);

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` int(11) NOT NULL,
  `recipe_code` varchar(10) NOT NULL,
  `recipe_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`recipe_id`, `recipe_code`, `recipe_name`) VALUES
(19, 'RCP0001', 'Taro Milktea'),
(20, 'RCP0002', 'Matcha Milktea'),
(21, 'RCP0003', 'Strawberry Milktea'),
(22, 'RCP0004', 'Iced Coffee'),
(23, 'RCP0005', 'Okinawa Milktea'),
(24, 'RCP0006', 'Chocolate Milk Tea'),
(25, 'RCP0007', 'Cheesecake'),
(26, 'RCP0008', 'Frappe Recipe'),
(27, 'RCP0009', 'fruit tea recipe'),
(28, 'RCP0010', 'slushee recipe');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredients`
--

CREATE TABLE `recipe_ingredients` (
  `recipe_ingredient_id` int(11) NOT NULL,
  `recipe_id` int(10) UNSIGNED NOT NULL,
  `ingredient_id` int(10) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_id` int(10) UNSIGNED NOT NULL,
  `custom_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipe_ingredients`
--

INSERT INTO `recipe_ingredients` (`recipe_ingredient_id`, `recipe_id`, `ingredient_id`, `quantity`, `unit_id`, `custom_id`) VALUES
(11, 19, 5, 2.00, 1, 'RCPI011'),
(12, 20, 5, 1.00, 1, 'RCPI012'),
(13, 20, 3, 2.00, 3, 'RCPI013'),
(17, 0, 6, 2.00, 2, 'RCPI017'),
(18, 21, 8, 2.00, 3, 'RCPI018'),
(19, 21, 6, 2.00, 2, 'RCPI019'),
(20, 21, 5, 2.00, 1, 'RCPI020'),
(21, 22, 1, 2.00, 3, 'RCPI021'),
(22, 22, 7, 1.00, 2, 'RCPI022'),
(23, 22, 6, 2.00, 2, 'RCPI023'),
(24, 23, 9, 1.00, 1, 'RCPI024'),
(25, 23, 5, 1.00, 1, 'RCPI025'),
(26, 23, 6, 2.00, 2, 'RCPI026'),
(27, 24, 11, 2.00, 2, 'RCPI027'),
(28, 24, 10, 2.00, 2, 'RCPI028'),
(29, 25, 12, 3.00, 2, 'RCPI029'),
(30, 26, 13, 2.00, 1, 'RCPI030'),
(31, 27, 14, 3.00, 2, 'RCPI031'),
(32, 28, 15, 2.00, 2, 'RCPI032');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `SUPPLIER_ID` int(11) NOT NULL,
  `COMPANY_NAME` varchar(50) DEFAULT NULL,
  `LOCATION_ID` int(11) NOT NULL,
  `PHONE_NUMBER` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`SUPPLIER_ID`, `COMPANY_NAME`, `LOCATION_ID`, `PHONE_NUMBER`) VALUES
(11, 'CoffeeShop3', 114, '09167821234'),
(12, 'CoffeeShop2', 115, '09871234567'),
(13, 'CoffeeShop4', 111, '09221008912'),
(15, 'CoffeeShop5', 116, '09118923451'),
(16, 'CoffeeShop1', 155, '09122334621'),
(17, 'CoffeeShop6', 159, '09236617234'),
(18, '', 160, ''),
(19, 'SM', 161, '09218771893');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `TRANS_ID` int(11) NOT NULL,
  `CUST_NAME` varchar(250) NOT NULL,
  `DATE` datetime NOT NULL,
  `GRANDTOTAL` decimal(10,2) NOT NULL,
  `CASH` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`TRANS_ID`, `CUST_NAME`, `DATE`, `GRANDTOTAL`, `CASH`) VALUES
(1, 'Jane', '2024-11-24 18:31:09', 39.00, 39.00),
(2, 'Jane', '2024-11-24 18:31:09', 39.00, 39.00),
(3, 'Jane', '2024-11-24 18:31:09', 39.00, 39.00),
(4, 'Jane', '2024-11-24 18:31:09', 39.00, 39.00),
(5, 'Jane', '2024-11-24 18:31:09', 39.00, 39.00),
(6, 'Jane', '2024-11-24 18:31:09', 39.00, 39.00),
(7, 'Jane', '2024-11-24 18:31:09', 39.00, 39.00),
(107489, '', '2024-11-25 10:47:00', 232.00, 232.00),
(283481, '', '2024-11-25 11:08:00', 234.00, 234.00),
(314382, '', '2024-11-25 10:45:00', 58.00, 58.00),
(319706, '', '2024-11-25 15:09:00', 29.00, 29.00),
(361892, '', '2024-11-24 20:55:00', 234.00, 234.00),
(380977, '', '2024-11-24 20:40:00', 117.00, 0.00),
(390919, '', '2024-11-24 20:44:00', 39.00, 39.00),
(427288, '', '2024-11-24 20:44:00', 39.00, 39.00),
(435504, '', '2024-11-24 21:19:00', 39.00, 39.00),
(518349, '', '2024-11-25 15:13:00', 3939.00, 5000.00),
(547053, '', '2024-11-24 20:39:00', 78.00, 0.00),
(713204, '', '2024-11-25 10:08:00', 116.00, 116.00),
(773204, '', '2024-11-25 10:17:00', 29.00, 29.00),
(829230, '', '2024-11-25 09:45:00', 29.00, 29.00),
(836628, '', '2024-11-25 11:52:00', 78.00, 78.00),
(890552, '', '2024-11-24 20:45:00', 58.00, 58.00),
(896279, '', '2024-11-24 21:00:00', 1899.00, 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `ID` int(11) NOT NULL,
  `TRANS_ID` int(11) NOT NULL,
  `PRODUCT` varchar(250) NOT NULL,
  `QTY` int(11) NOT NULL,
  `PRICE` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`ID`, `TRANS_ID`, `PRODUCT`, `QTY`, `PRICE`) VALUES
(1, 1, 'Matcha Milktea', 1, 39.00),
(4, 361892, 'Strawberry Milktea', 6, 39.00),
(5, 896279, 'Matcha Milktea', 23, 39.00),
(6, 896279, 'Iced Salted Caramel', 9, 29.00),
(7, 896279, 'Strawberry Milktea', 6, 39.00),
(8, 896279, 'TaroMilktea', 13, 39.00),
(9, 435504, 'Strawberry Milktea', 1, 39.00),
(10, 829230, 'Iced Salted Caramel', 1, 29.00),
(11, 713204, 'Matcha Milktea', 4, 29.00),
(12, 773204, 'Iced Salted Caramel', 1, 29.00),
(13, 314382, 'Iced Salted Caramel', 2, 29.00),
(14, 107489, 'Iced Salted Caramel', 6, 29.00),
(15, 107489, 'Matcha Milktea', 2, 29.00),
(16, 283481, 'Iced Salted Caramel', 2, 39.00),
(17, 283481, 'Matcha Milktea', 2, 39.00),
(18, 283481, 'Strawberry Milktea', 2, 39.00),
(19, 836628, 'Chocolate Milk Tea', 2, 39.00),
(20, 319706, 'Oreo Cheesecake', 1, 29.00),
(21, 518349, 'Oreo Cheesecake', 101, 39.00);

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `TYPE_ID` int(11) NOT NULL,
  `TYPE` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`TYPE_ID`, `TYPE`) VALUES
(1, 'Admin'),
(2, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(10) UNSIGNED NOT NULL,
  `unit_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_name`) VALUES
(2, 'Cup'),
(4, 'Piece'),
(1, 'Scoop'),
(3, 'Tablespoon');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `EMPLOYEE_ID` int(11) DEFAULT NULL,
  `USERNAME` varchar(50) DEFAULT NULL,
  `PASSWORD` varchar(50) DEFAULT NULL,
  `TYPE_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `EMPLOYEE_ID`, `USERNAME`, `PASSWORD`, `TYPE_ID`) VALUES
(1, 1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1),
(7, 2, 'user', '12dea96fec20593566ab75692c9949596833adc9', 2),
(9, 4, 'user1', 'b3daa77b4c04a9551b8781d03191fe098f325e67', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CUST_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`EMPLOYEE_ID`),
  ADD UNIQUE KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  ADD UNIQUE KEY `PHONE_NUMBER` (`PHONE_NUMBER`),
  ADD KEY `LOCATION_ID` (`LOCATION_ID`),
  ADD KEY `JOB_ID` (`JOB_ID`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`INGREDIENTS_ID`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`JOB_ID`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`LOCATION_ID`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD UNIQUE KEY `PHONE_NUMBER` (`PHONE_NUMBER`),
  ADD KEY `LOCATION_ID` (`LOCATION_ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`PRODUCT_ID`),
  ADD KEY `SUPPLIER_ID` (`SUPPLIER_ID`),
  ADD KEY `CATEGORY_ID` (`CATEGORY_ID`),
  ADD KEY `fk_recipe` (`recipe_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`),
  ADD UNIQUE KEY `recipe_code` (`recipe_code`),
  ADD UNIQUE KEY `recipe_code_2` (`recipe_code`);

--
-- Indexes for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD PRIMARY KEY (`recipe_ingredient_id`),
  ADD KEY `recipe_id` (`recipe_id`),
  ADD KEY `ingredient_id` (`ingredient_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`SUPPLIER_ID`),
  ADD KEY `LOCATION_ID` (`LOCATION_ID`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`TRANS_ID`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TRANS_ID` (`TRANS_ID`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`TYPE_ID`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`),
  ADD UNIQUE KEY `unit_name` (`unit_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TYPE_ID` (`TYPE_ID`),
  ADD KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CUST_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `EMPLOYEE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `LOCATION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `PRODUCT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  MODIFY `recipe_ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `SUPPLIER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `TRANS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=896280;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`LOCATION_ID`) REFERENCES `location` (`LOCATION_ID`),
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`JOB_ID`) REFERENCES `job` (`JOB_ID`);

--
-- Constraints for table `manager`
--
ALTER TABLE `manager`
  ADD CONSTRAINT `manager_ibfk_1` FOREIGN KEY (`LOCATION_ID`) REFERENCES `location` (`LOCATION_ID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`recipe_id`),
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`CATEGORY_ID`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_ingredients`
--
ALTER TABLE `recipe_ingredients`
  ADD CONSTRAINT `fk_ingredient_id` FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients` (`INGREDIENTS_ID`),
  ADD CONSTRAINT `fk_unit_id` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`);

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `supplier_ibfk_1` FOREIGN KEY (`LOCATION_ID`) REFERENCES `location` (`LOCATION_ID`);

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`TRANS_ID`) REFERENCES `transaction` (`TRANS_ID`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`TYPE_ID`) REFERENCES `type` (`TYPE_ID`),
  ADD CONSTRAINT `users_ibfk_4` FOREIGN KEY (`EMPLOYEE_ID`) REFERENCES `employee` (`EMPLOYEE_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
