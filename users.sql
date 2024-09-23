-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2024 at 10:47 AM
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
-- Database: `task`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `status`) VALUES
(3, 'Charlie', 'charlie@example.com', 'user789', 'charlie789', 'active'),
(4, 'David', 'david@example.com', 'user1011', 'davidpassword', 'inactive'),
(5, 'Eve', 'eve@example.com', 'user1213', 'eve2023', 'active'),
(6, 'Frank', 'frank@example.com', 'user1415', 'frank456', 'inactive'),
(7, 'Grace', 'grace@example.com', 'user1617', 'gracepassword', 'active'),
(8, 'Heidi', 'heidi@example.com', 'user1819', 'heidi1234', 'inactive'),
(9, 'Ivan', 'ivan@example.com', 'user2021', 'ivan2023', 'active'),
(10, 'Judy', 'judy@example.com', 'user2223', 'judypassword', 'inactive'),
(11, 'Mallory', 'mallory@example.com', 'user2425', 'mallory123', 'active'),
(12, 'Oscar', 'oscar@example.com', 'user2627', 'oscar456', 'inactive'),
(13, 'Peggy', 'peggy@example.com', 'user2829', 'peggy789', 'active'),
(14, 'Sybil', 'sybil@example.com', 'user3031', 'sybil2023', 'inactive'),
(15, 'Trent', 'trent@example.com', 'user3233', 'trentpassword', 'active'),
(0, 'Bhargav Sardhara', 'jaygajidsfdsfdsfpara07@gmail.com', 'sdfsfsd', 'sfdsdfdsf', 'Active');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
