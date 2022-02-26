-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2021 at 02:04 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cs473`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(6) NOT NULL COMMENT 'Categorie Id',
  `Name` varchar(255) NOT NULL COMMENT 'Name of the Categorie',
  `Description` text NOT NULL COMMENT 'Description of the Categorie',
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Ordering`, `Visibility`, `Allow_Comment`) VALUES
(8, 'Computers', 'Computer Items', 0, 0, 0),
(9, 'Cell Phones', 'All about Cell Phones!', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `CID` int(11) NOT NULL,
  `comment` text NOT NULL,
  `date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` float NOT NULL,
  `quantity` int(11) NOT NULL,
  `Add_Date` date NOT NULL,
  `image` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `NoOfVoters` int(11) NOT NULL,
  `Cat_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_ID`, `Name`, `Description`, `Price`, `quantity`, `Add_Date`, `image`, `Rating`, `NoOfVoters`, `Cat_ID`) VALUES
(38, 'Apple iPad Pro 12.9', 'The new iPad', 799, 12, '2021-05-23', '1050649867861_apple_ipad-pro-spring21_hero_04202021_big.jpg.large_.jpg', 0, 0, 9),
(39, 'Apple Watch Series 6 ', 'Apple Watch Series 6 Aluminum', 599, 10, '2021-05-23', '724279948870_81ww7fpkNHL._AC_SX342_.jpg', 0, 0, 9),
(40, 'Apple iPhone 12 Pro Max', 'The new iPhone - 256GB', 1099, 3, '2021-05-23', '1252853190304_unnamed.jpg', 0, 0, 9),
(41, 'PS5', 'The new Playstation MSRP Price!', 499, 2, '2021-05-23', '2000164653724_playstation-5-with-dualsense-front-product-shot-01-ps5-en-30jul20_1596x.png', 0, 0, 8),
(42, 'Apple M1 MacBook Pro', 'Base model M1 MacBook Pro', 1199, 20, '2021-05-23', '1396018189840_mbp-silver-select-202011.jpeg', 0, 0, 8);

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `ID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `OID` int(11) NOT NULL,
  `Pname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Quantity` int(11) NOT NULL,
  `unitPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `total` double NOT NULL,
  `shipMethod` varchar(255) CHARACTER SET utf8 NOT NULL,
  `orderDate` datetime NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Acknowledged'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'This is User''s ID',
  `Username` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Username',
  `Password` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Password',
  `Email` varchar(255) NOT NULL COMMENT 'User''s Email',
  `FullName` varchar(255) NOT NULL COMMENT 'User''s Full name',
  `GroupID` int(11) NOT NULL DEFAULT 0 COMMENT 'Identify user''s group',
  `RegStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'Users Approval',
  `propic` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `RegStatus`, `propic`) VALUES
(803, 'admin', '41c35b110b6d8c93a0ee5b5fe9c4a743', 'admin@cs333.com', 'Administrator', 1, 1, ''),
(804, 'stuser', 'd85692f5eae978c02053f36d62514bd9', 'staff@cs473.com', 'Staff User', 2, 1, '947080177312_employee-card.png'),
(805, 'Faisal', 'd85692f5eae978c02053f36d62514bd9', 'falqaed@uob.edu.bh', 'Faisal AlQaed', 0, 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CID`),
  ADD KEY `items_comments` (`item_id`),
  ADD KEY `comment_user` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ORDRID` (`OID`),
  ADD KEY `USRID` (`userID`),
  ADD KEY `IID` (`ItemID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `UIDENT` (`userID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Categorie Id', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189577;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'This is User''s ID', AUTO_INCREMENT=806;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comments` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `IID` FOREIGN KEY (`ItemID`) REFERENCES `items` (`item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ORDRID` FOREIGN KEY (`OID`) REFERENCES `orders` (`orderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `USRID` FOREIGN KEY (`userID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `UIDENT` FOREIGN KEY (`userID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
