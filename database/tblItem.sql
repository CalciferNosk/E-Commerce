-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 12, 2024 at 09:33 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `e-com`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblItem`
--

CREATE TABLE `tblItem` (
  `id` int(11) NOT NULL,
  `ItemName` longtext DEFAULT NULL,
  `ItemCategoryId` int(11) DEFAULT NULL,
  `ItemPrice` int(11) DEFAULT NULL,
  `SellerId` int(11) DEFAULT NULL,
  `DeletedTag` tinyint(1) DEFAULT NULL,
  `CreatedDate` datetime DEFAULT current_timestamp(),
  `CreatedBy` varchar(45) DEFAULT NULL,
  `UpdatedDate` datetime DEFAULT NULL,
  `UpdatedBy` varchar(45) DEFAULT NULL,
  `ItemImages` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblItem`
--

INSERT INTO `tblItem` (`id`, `ItemName`, `ItemCategoryId`, `ItemPrice`, `SellerId`, `DeletedTag`, `CreatedDate`, `CreatedBy`, `UpdatedDate`, `UpdatedBy`, `ItemImages`) VALUES
(1, 'BOD', 1, 250, 1, 0, '2024-03-12 09:50:14', NULL, NULL, NULL, 'BOD.png'),
(2, 'BERSERKER', 1, 300, 2, NULL, '2024-03-12 10:12:05', NULL, NULL, NULL, 'BERSERKER.png'),
(3, 'HEPTASIS', 1, 145, 3, NULL, '2024-03-12 10:16:12', NULL, NULL, NULL, 'HEPTASIS.png'),
(4, 'HALBERD', 1, 250, 4, NULL, '2024-03-12 10:19:23', NULL, NULL, NULL, 'HALBERD.png'),
(5, 'MALEFIC', 1, 340, 5, NULL, '2024-03-12 10:32:33', NULL, NULL, NULL, 'MALEFIC.png'),
(6, 'GREAT DRAGON SPEAR', 1, 500, 6, NULL, '2024-03-12 11:20:05', NULL, NULL, NULL, 'GREAT.png'),
(7, 'WAR AXE', 1, 300, 7, NULL, '2024-03-12 11:20:46', NULL, NULL, NULL, 'WARAXE.png'),
(8, 'CORROSION SCYTHE', 1, 400, 8, NULL, NULL, NULL, NULL, NULL, 'CORROSION.png'),
(9, 'WINDT', 1, 600, 9, NULL, '2024-03-12 13:50:54', NULL, NULL, NULL, 'WINDT.png'),
(10, 'ROSEGOLD', 1, 450, 9, NULL, '2024-03-12 13:52:20', NULL, NULL, NULL, 'ROSEGOLD.png'),
(11, 'HCLAW', 1, 520, 10, NULL, NULL, NULL, NULL, NULL, 'HCLAW.png'),
(12, 'GSTAFF', 1, 200, 11, NULL, '2024-03-12 13:56:18', NULL, NULL, NULL, 'GSTAFF.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblItem`
--
ALTER TABLE `tblItem`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblItem`
--
ALTER TABLE `tblItem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
