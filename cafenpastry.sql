-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2018 at 01:14 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cafenpastry`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin1234');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartid` int(11) NOT NULL,
  `cartsession` varchar(64) NOT NULL,
  `customerid` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartid`, `cartsession`, `customerid`, `date_created`) VALUES
(1, 'ed3e915dec28d4e793e9df958e5694fb', 19, '2018-03-27 09:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `cartlist`
--

CREATE TABLE `cartlist` (
  `cartlistid` int(11) NOT NULL,
  `cartid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `order_qty` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cartlist`
--

INSERT INTO `cartlist` (`cartlistid`, `cartid`, `productid`, `order_qty`, `date_created`) VALUES
(1, 1, 11, 2, '2018-03-27 09:19:50'),
(2, 1, 10, 2, '2018-03-27 09:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoriesid` int(11) NOT NULL,
  `categorycode` varchar(32) NOT NULL,
  `categorydesc` varchar(255) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoriesid`, `categorycode`, `categorydesc`, `date_created`) VALUES
(14, 'T-SHIRTS', 'ALL T-SHIRTS', '2018-03-22 13:32:39'),
(16, 'POLO SHIRTS', 'ALL POLO SHIRTS', '2018-03-22 13:33:27'),
(17, 'DRESS', 'ALL DRESS', '2018-03-22 13:33:49'),
(18, 'PANTS', 'ALL PANTS', '2018-03-22 13:33:58'),
(19, 'WATCH', 'ALL WATCH', '2018-03-22 13:34:18'),
(20, 'SHORTS', 'ALL SHORTS', '2018-03-22 13:34:29'),
(21, 'JACKET', 'ALL JACKETS', '2018-03-22 13:34:48'),
(22, 'SWEATER', 'ALL SWEATER', '2018-03-22 13:34:57'),
(23, 'SHOES', 'ALL SHOES', '2018-03-22 13:35:16');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerid` int(11) NOT NULL,
  `emailadd` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `fullname` varchar(64) NOT NULL,
  `address` varchar(64) NOT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `date_registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerid`, `emailadd`, `password`, `fullname`, `address`, `lastlogin`, `date_registered`) VALUES
(18, 'dianne@yahoo.com', '12345678', 'dianne tanga', 'gentrias cavite', '2018-03-22 21:37:39', '2018-03-22 13:37:39'),
(19, 'alexis@yahoo.com', '12345678', 'alexis retardo', 'general trias cavite', '2018-03-27 17:18:53', '2018-03-27 09:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentid` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `cardnumber` varchar(32) NOT NULL,
  `securitycode` varchar(16) DEFAULT NULL,
  `nameoncard` varchar(64) NOT NULL,
  `expirationmonth` varchar(16) NOT NULL,
  `expirationyear` varchar(16) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentid`, `customerid`, `cardnumber`, `securitycode`, `nameoncard`, `expirationmonth`, `expirationyear`, `date_created`) VALUES
(1, 19, '8912391857123', '412384', 'paypal', '2', '2017', '2018-03-27 09:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productid` int(11) NOT NULL,
  `categoriesid` int(11) NOT NULL,
  `productname` varchar(128) NOT NULL,
  `onhand_qty` int(11) NOT NULL DEFAULT '0',
  `unit_price` double(15,2) NOT NULL,
  `productdesc` varchar(255) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productid`, `categoriesid`, `productname`, `onhand_qty`, `unit_price`, `productdesc`, `date_created`) VALUES
(2, 21, 'adidas fashion track jacket', 5, 1999.00, 'Brushed fleece fabric for warmth Scuba hood for enhanced coverage Rib hem and cuffs for a snug, comfortable fit Split kangaroo pocket', '2018-03-27 01:38:57'),
(3, 21, 'nike\'s men winterized therma', 5, 2999.00, '100% cotton', '2018-03-27 01:41:07'),
(4, 21, 'men\'s denim jacket', 5, 1500.00, 'available at all sizes.', '2018-03-27 01:44:32'),
(5, 21, 'one piece hoody jacket', 20, 500.00, 'cartooned', '2018-03-27 01:55:06'),
(6, 17, 'â€œMardiâ€ Dress Chambray', 5, 1500.00, 'available all size', '2018-03-27 02:11:42'),
(7, 17, 'Dress Madame Black', 4, 2300.00, 'small & meduim size available', '2018-03-27 02:12:46'),
(8, 17, 'fenn wright manson', 5, 1999.00, 'small size  available', '2018-03-27 02:14:20'),
(9, 17, 'Black Gauze Collar Sleeveless Dress', 2, 3999.00, 'available at all size', '2018-03-27 02:15:38'),
(10, 18, 'Lightweight Work Pants', 20, 450.00, 'available at all prize', '2018-03-27 02:18:57'),
(11, 18, 'Nike SB FTM 5 Pocket Denim â€“ Obsidian', 15, 600.00, 'all size available', '2018-03-27 02:20:54'),
(12, 16, ' Louisville Cardinals', 10, 650.00, 'all sizes available', '2018-03-27 02:26:00'),
(13, 16, 'nike', 10, 400.00, 'leather', '2018-03-27 09:20:45'),
(14, 16, 'wew', 232, 2323.00, 'qweqwe', '2018-04-24 04:53:54'),
(15, 20, 'qweqwe', 21, 123.00, 'qweqwe', '2018-04-24 06:15:53'),
(16, 16, 'qwe', 2, 123.00, 'qweqwe', '2018-05-12 02:49:33');

-- --------------------------------------------------------

--
-- Table structure for table `prod_image`
--

CREATE TABLE `prod_image` (
  `prod_imageid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `prod_imagepath` varchar(128) NOT NULL,
  `prod_imagefilename` varchar(128) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prod_image`
--

INSERT INTO `prod_image` (`prod_imageid`, `productid`, `prod_imagepath`, `prod_imagefilename`, `date_created`) VALUES
(4, 4, '../../public/upload/products/denim 400x400.jpg', 'denim 400x400.jpg', '2018-03-27 01:47:58'),
(5, 3, '../../public/upload/products/nike jacket 400x400.png', 'nike jacket 400x400.png', '2018-03-27 01:51:32'),
(6, 2, '../../public/upload/products/adidas jacket 400x400.jpg', 'adidas jacket 400x400.jpg', '2018-03-27 01:51:43'),
(7, 5, '../../public/upload/products/one piece hoodie 400x400.jpg', 'one piece hoodie 400x400.jpg', '2018-03-27 01:55:26'),
(8, 6, '../../public/upload/products/â€œMardiâ€ Dress Chambray.jpg', 'â€œMardiâ€ Dress Chambray.jpg', '2018-03-27 02:12:01'),
(9, 7, '../../public/upload/products/Dress Madame Black Lace.jpg', 'Dress Madame Black Lace.jpg', '2018-03-27 02:13:08'),
(10, 9, '../../public/upload/products/Black Gauze Collar Sleeveless Dress.jpg', 'Black Gauze Collar Sleeveless Dress.jpg', '2018-03-27 02:16:05'),
(11, 8, '../../public/upload/products/fenn wright manson.jpg', 'fenn wright manson.jpg', '2018-03-27 02:16:22'),
(12, 10, '../../public/upload/products/Lightweight Work Pants.jpg', 'Lightweight Work Pants.jpg', '2018-03-27 02:20:35'),
(13, 11, '../../public/upload/products/Nike SB FTM 5 Pocket Denim â€“ Obsidian.jpg', 'Nike SB FTM 5 Pocket Denim â€“ Obsidian.jpg', '2018-03-27 02:22:11'),
(14, 12, '../../public/upload/products/Louisville Cardinals.jpg', 'Louisville Cardinals.jpg', '2018-03-27 02:26:14'),
(15, 13, '../../public/upload/products/kupal.jpg', 'kupal.jpg', '2018-04-10 07:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `tmp_cart`
--

CREATE TABLE `tmp_cart` (
  `tmp_cartid` int(11) NOT NULL,
  `cartsession` varchar(64) NOT NULL,
  `productid` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tmp_cart`
--

INSERT INTO `tmp_cart` (`tmp_cartid`, `cartsession`, `productid`, `qty`, `date_created`) VALUES
(1, '0185496372c9eab0e8394c08c472b497', 9, 1, '2018-03-27 08:20:44'),
(2, '0185496372c9eab0e8394c08c472b497', 2, 1, '2018-03-27 08:21:03'),
(3, '6161311d1e5d729c93e08b8437b96a16', 2, 1, '2018-03-27 08:22:02'),
(4, '6161311d1e5d729c93e08b8437b96a16', 6, 1, '2018-03-27 08:22:40'),
(5, 'ed3e915dec28d4e793e9df958e5694fb', 11, 2, '2018-03-27 09:18:13'),
(6, 'ed3e915dec28d4e793e9df958e5694fb', 10, 2, '2018-03-27 09:18:18'),
(7, 'd4bf6634e9ceff59b0eb41e68444a4e0', 13, 1, '2018-04-10 07:03:37');

-- --------------------------------------------------------

--
-- Stand-in structure for view `tmp_cart_details`
-- (See below for the actual view)
--
CREATE TABLE `tmp_cart_details` (
`tmp_cartid` int(11)
,`cartsession` varchar(64)
,`productid` int(11)
,`productname` varchar(128)
,`productdesc` varchar(255)
,`unit_price` double(15,2)
,`order_qty` int(11)
,`date_created` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `tmp_cart_details`
--
DROP TABLE IF EXISTS `tmp_cart_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tmp_cart_details`  AS  select `t0`.`tmp_cartid` AS `tmp_cartid`,`t0`.`cartsession` AS `cartsession`,`t0`.`productid` AS `productid`,`t1`.`productname` AS `productname`,`t1`.`productdesc` AS `productdesc`,`t1`.`unit_price` AS `unit_price`,`t0`.`qty` AS `order_qty`,`t0`.`date_created` AS `date_created` from (`tmp_cart` `t0` join `product` `t1` on((`t1`.`productid` = `t0`.`productid`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartid`);

--
-- Indexes for table `cartlist`
--
ALTER TABLE `cartlist`
  ADD PRIMARY KEY (`cartlistid`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoriesid`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentid`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productid`);

--
-- Indexes for table `prod_image`
--
ALTER TABLE `prod_image`
  ADD PRIMARY KEY (`prod_imageid`);

--
-- Indexes for table `tmp_cart`
--
ALTER TABLE `tmp_cart`
  ADD PRIMARY KEY (`tmp_cartid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cartlist`
--
ALTER TABLE `cartlist`
  MODIFY `cartlistid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoriesid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `prod_image`
--
ALTER TABLE `prod_image`
  MODIFY `prod_imageid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tmp_cart`
--
ALTER TABLE `tmp_cart`
  MODIFY `tmp_cartid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
