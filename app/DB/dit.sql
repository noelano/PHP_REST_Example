-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2016 at 04:26 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dit`
--

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `surname` varchar(25) NOT NULL,
  `position` varchar(10) NOT NULL,
  `team` varchar(25) NOT NULL,
  `cost` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `selected_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `name`, `surname`, `position`, `team`, `cost`, `points`, `selected_by`) VALUES
(2, 'Anthony', 'Martial', 'Forward', 'Manchester United', 77, 112, 124),
(3, 'Jamie', 'Vardy', 'Forward', 'Leicester City', 76, 197, 487),
(4, 'Daniel', 'Sturridge', 'Forward', 'Liverpool', 99, 43, 29),
(5, 'Harry', 'Kane', 'Forward', 'Tottenham Hotspur', 104, 199, 461),
(6, 'Olivier', 'Giroud', 'Forward', 'Arsenal', 87, 120, 75),
(7, 'Andy', 'Carroll', 'Forward', 'West Ham', 63, 80, 22),
(8, 'Jermain', 'Defoe', 'Forward', 'Sunderland', 52, 118, 67),
(9, 'Connor', 'Wickham', 'Forward', 'Crystal Palace', 55, 66, 3),
(10, 'Wayne', 'Rooney', 'Forward', 'Manchester United', 99, 98, 78),
(11, 'Diego', 'Costa', 'Forward', 'Chelsea', 105, 116, 52);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `colour` varchar(20) NOT NULL,
  `division` int(11) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `colour`, `division`, `position`) VALUES
(1, 'Liverpool', 'Red', 1, 7),
(2, 'Leicester City', 'DodgerBlue', 1, 1),
(3, 'Tottenham Hotspur', 'White', 1, 2),
(4, 'Manchester City', 'Sky Blue', 1, 3),
(5, 'Manchester United', 'DarkRed', 1, 5),
(6, 'Arsenal', 'FireBrick', 1, 4),
(7, 'West Ham', 'BlueViolet', 1, 6),
(8, 'Chelsea', 'Blue', 1, 9),
(9, 'Sunderland', 'Tomato', 1, 17),
(10, 'Crystal Palace', 'Fuchsia', 1, 16),
(13, 'Testing Wanderers', 'Green', 1, 14);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `name` varchar(25) NOT NULL,
  `surname` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `name`, `surname`, `email`, `password`) VALUES
(1, 'noel', 'noel', 'rogers', 'noelanorodriguez@gmail.com', '12345');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique2` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
