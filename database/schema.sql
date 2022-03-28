-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 08, 2020 at 03:50 PM
-- Server version: 5.7.27-0ubuntu0.18.04.1
-- PHP Version: 7.2.19-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pastebin`
--

-- --------------------------------------------------------

--
-- Table structure for table `reg_pastes`
--

CREATE TABLE `reg_pastes` (
  `p_id` varchar(13) NOT NULL,
  `pu_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `lang` varchar(20) NOT NULL,
  `encrypt_status` tinyint(1) NOT NULL,
  `ps_data` text NOT NULL,
  `pasteExposure` varchar(10) NOT NULL,
  `creation_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deletion_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `unreg_pastes`
--

CREATE TABLE `unreg_pastes` (
  `p_id` varchar(18) NOT NULL,
  `title` varchar(100) NOT NULL,
  `lang` varchar(20) NOT NULL,
  `encrypt_status` tinyint(1) NOT NULL,
  `ps_data` text,
  `creation_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `deletion_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `reg_pastes`
--
ALTER TABLE `reg_pastes`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `pu_id` (`pu_id`);

--
-- Indexes for table `unreg_pastes`
--
ALTER TABLE `unreg_pastes`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `reg_pastes`
--
ALTER TABLE `reg_pastes`
  ADD CONSTRAINT `reg_pastes_ibfk_1` FOREIGN KEY (`pu_id`) REFERENCES `users` (`u_id`);

