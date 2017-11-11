-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2017 at 02:23 AM
-- Server version: 10.1.24-MariaDB
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eyechart`
--
CREATE DATABASE IF NOT EXISTS `eyechart` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `eyechart`;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `EmployeeId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `UserName` varchar(10) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `EmailAddress` varchar(100) NOT NULL,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CreatedBy` varchar(10) NOT NULL DEFAULT 'Root',
  `Modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ModifiedBy` varchar(10) NOT NULL DEFAULT 'Root',
  PRIMARY KEY (`EmployeeId`),
  UNIQUE KEY `EmployeeUdx` (`UserName`,`EmailAddress`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `employees`
--

TRUNCATE TABLE `employees`;
--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`EmployeeId`, `UserName`, `FirstName`, `LastName`, `EmailAddress`, `Created`, `CreatedBy`, `Modified`, `ModifiedBy`) VALUES
(1, 'jpacheco', 'Joshua', 'Pacheco', 'joshua.pacheco@gmail.com', '2017-11-10 18:13:53', 'jpacheco', '2017-11-10 18:13:53', 'jpacheco');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `SessionDataId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `SessionId` varchar(30) NOT NULL,
  `SessionUser` varchar(10) NOT NULL,
  `Token` char(32) CHARACTER SET ascii NOT NULL,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`SessionDataId`),
  UNIQUE KEY `SessionIdAndTokenUdx` (`SessionId`,`Token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `session`
--

TRUNCATE TABLE `session`;
-- --------------------------------------------------------

--
-- Table structure for table `sessiondata`
--

DROP TABLE IF EXISTS `sessiondata`;
CREATE TABLE IF NOT EXISTS `sessiondata` (
  `Token` char(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `sessiondata`
--

TRUNCATE TABLE `sessiondata`;
-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UserIdentityId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `UserName` varchar(10) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CreatedBy` varchar(20) NOT NULL DEFAULT 'Root',
  `ModifiedBy` varchar(20) NOT NULL,
  PRIMARY KEY (`UserIdentityId`),
  UNIQUE KEY `UserIdx` (`UserName`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserIdentityId`, `UserName`, `Password`, `Created`, `Modified`, `CreatedBy`, `ModifiedBy`) VALUES
(1, 'jpacheco', 'elam', '2017-11-10 17:49:58', '2017-11-10 17:49:58', 'jpacheco', 'jpacheco');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`UserName`) REFERENCES `users` (`UserName`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
