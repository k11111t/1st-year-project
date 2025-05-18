-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 05:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2019_comp10120_y8`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `username` text NOT NULL,
  `balance` int(11) NOT NULL,
  `totalprofit` int(11) NOT NULL,
  `totalloss` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`username`, `balance`, `totalprofit`, `totalloss`) VALUES
('Pogman', 900, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gamemode`
--

CREATE TABLE `gamemode` (
  `maxplayers` int(11) NOT NULL,
  `minplayers` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `gamename` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gamemode`
--

INSERT INTO `gamemode` (`maxplayers`, `minplayers`, `gameID`, `gamename`, `description`) VALUES
(5, 1, 1, 'Black Jack', 'Blackjack is a card game where players aim to beat the dealer by getting closest to 21 without going over.');

-- --------------------------------------------------------

--
-- Table structure for table `gameroom`
--

CREATE TABLE `gameroom` (
  `username` text NOT NULL,
  `gameID` int(11) NOT NULL,
  `currentNoPlayers` int(11) NOT NULL,
  `gameEnd` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `username` text NOT NULL,
  `itemID` int(11) NOT NULL,
  `equipped` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`username`, `itemID`, `equipped`) VALUES
('Pogman', 1, 1),
('Pogman', 2, 1),
('Pogman', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `gameID` int(11) NOT NULL,
  `username` text NOT NULL,
  `win` int(11) NOT NULL,
  `lose` int(11) NOT NULL,
  `streak` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statistics`
--

INSERT INTO `statistics` (`gameID`, `username`, `win`, `lose`, `streak`) VALUES
(1, 'Pogman', 0, 3, 0),
(2, 'Pogman', 0, 3, 0),
(3, 'Pogman', 0, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `itemType` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  `visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `pwd` text NOT NULL,
  `lastlogin` datetime(6) NOT NULL,
  `email` varchar(999) NOT NULL,
  `forename` text NOT NULL,
  `surname` text NOT NULL,
  `username` text NOT NULL,
  `sessionid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`pwd`, `lastlogin`, `email`, `forename`, `surname`, `username`, `sessionid`) VALUES
('$2y$10$hidU6tQFpTG2SsxkUYici.n7sEbXnGjQueJklabCwz5ocN3KjiZnu', '2025-05-18 15:56:13.000000', 'vhdang0@gmail.com', 'Viet Hung', 'Dang', 'Pogman', 'fd322p2v5pn0isfuugfh48krnf');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
