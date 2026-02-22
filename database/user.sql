-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2026 at 03:55 PM
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
-- Database: `user`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `payment_method` varchar(20) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `event_id`, `seat_number`, `price`, `status`, `payment_method`, `transaction_id`, `created_at`) VALUES
(21, 4, 15, 'A2', 5000.00, 'pending', 'card', NULL, '2026-02-22 03:47:35'),
(22, 4, 17, 'B1', 5000.00, 'pending', 'card', NULL, '2026-02-22 03:47:43'),
(23, 4, 17, 'A1', 5000.00, 'pending', 'card', NULL, '2026-02-22 03:55:08'),
(24, 4, 15, 'A1', 5000.00, 'pending', 'card', NULL, '2026-02-22 03:55:14'),
(25, 4, 18, 'A1', 5000.00, 'confirmed', 'card', NULL, '2026-02-22 03:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `artist` varchar(120) NOT NULL,
  `venue` varchar(150) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `status` enum('active','soldout','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `artist`, `venue`, `event_date`, `event_time`, `price`, `poster`, `status`, `created_at`) VALUES
(14, 'Sariling Mundo Tour', 'TJ Monterde', 'Cebu City Sports Center', '2026-12-22', '20:00:00', 1500.00, 'images/1769754553_tj.png', 'active', '2026-01-30 06:29:13'),
(15, 'P-Pop Legend', 'SB19', 'Philippine Arena, Bulacan', '2026-04-18', '19:00:00', 2500.00, 'images/1769754635_sb19.jpg', 'active', '2026-01-30 06:30:35'),
(16, 'Folk-Pop Sessions', 'Ben&Ben', 'Waterfront Hotel Cebu', '2026-08-12', '20:00:00', 1800.00, 'images/1769754683_BenBen.jpg', 'active', '2026-01-30 06:31:23'),
(17, 'Pop Royalty', 'Sarah G.', 'Araneta Coliseum, QC', '2026-10-15', '20:00:00', 3000.00, 'images/1769754732_sarahg.jpg', 'active', '2026-01-30 06:32:12'),
(18, 'Higa Night Live', 'Arthur Nery', 'SM Seaside Arena, Cebu', '2026-07-16', '20:00:00', 1800.00, 'images/1769757109_ArthurNery.jpg', 'active', '2026-01-30 07:11:49'),
(19, 'OPM Hearts Tour', 'Moira Dela Torre', 'Araneta Coliseum, Quezon City', '2026-09-07', '19:30:00', 2000.00, 'images/1769757264_moira.jpg', 'active', '2026-01-30 07:14:24'),
(20, 'Saturn Nights', 'Zack Tabudlo', 'Waterfront Hotel Ballroom, Cebu', '2026-07-20', '20:00:00', 1500.00, 'images/1769757382_Zack_Tabudlo_Coke_Studio_16-9.jpg', 'active', '2026-01-30 07:16:22'),
(21, 'Where Have You Been Live', 'IV of Spades', 'Philippine Arena, Bulacan', '2026-10-11', '18:30:00', 2800.00, 'images/1769757442_ivos_2025_08_10_17_17_27.jpg', 'active', '2026-01-30 07:17:22'),
(22, 'OPM Legends Night', 'Rico Blanco', 'Cebu City Sports Center', '2026-11-20', '20:00:00', 2200.00, 'images/1769757515_RicoBlancoPR.jpg', 'active', '2026-01-30 07:18:35'),
(23, 'Asia’s Songbird Live in Concert', 'Regine Velasquez', 'Smart Araneta Coliseum, Quezon City', '2026-12-26', '20:00:00', 3500.00, 'images/1769758250_maxresdefault.jpg', 'active', '2026-01-30 07:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `points` int(11) DEFAULT 0,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `lname`, `email`, `password`, `role`, `points`, `status`) VALUES
(1, 'jeremy', 'rabanes', 'rabanesjeremy@gmail.com', '$2y$10$r.DOQmeMY36viOLWMSquM.HHJXCOeqmIN21tHU4sxjACe0BnjllL6', 'admin', 0, 'active'),
(4, 'Kimberly', 'Herbias', 'kimberly@gmail.com', '$2y$10$r.DOQmeMY36viOLWMSquM.HHJXCOeqmIN21tHU4sxjACe0BnjllL6', 'user', 2600, 'active'),
(6, 'Jhon Darell', 'Pateno', 'darell@gmail.com', '$2y$10$PMLyY8EdRXx1WQovdzeBIOq9hZ4neKyCcui2ABIJ7XKNuUcNij.1u', 'user', 2500, 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
