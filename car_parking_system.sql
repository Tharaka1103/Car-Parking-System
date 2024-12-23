-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2024 at 06:19 PM
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
-- Database: `car_parking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `car_number` varchar(20) NOT NULL,
  `payment_date` datetime NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `owner_name`, `contact_number`, `email`, `car_number`, `payment_date`, `service_id`) VALUES
(2, 'supun', '787436783648', 'aluth@g.c', 'car-5656', '2024-09-29 01:20:48', 2);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(200) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`name`, `email`, `subject`, `message`, `id`) VALUES
('test', 'test@g.c', 'ggefef', 'gggg', 1),
('Dilaksha Lakshan', 'admin@gmail.com', 'ggefef', 'dewwe', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `expires`) VALUES
('test@g.c', '4ca530f4d577474ecd981f78ee35eff87ec9e54808fc1aa4ac93eb31ba55b376', '2024-09-29 02:26:18'),
('tharaka@gmail.com', 'cd0af78de38aaca1cd99b67a3290476c8c1b078d12bd1ca703818650491962e9', '2024-09-28 21:10:43');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `vehicle_number` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `name`, `email`, `contact_number`, `vehicle_number`, `amount`, `payment_date`) VALUES
(1, 4, 'test', 'test@g.c', '787436783648', 'car-1234', 1000.00, '2024-09-29 00:26:59');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `description`, `price`) VALUES
(1, 'Tent for vehicle', 'we cover your vehicle with tent ', 1000.00),
(2, 'Body wash', 'we wash your vehicle', 1300.00),
(3, 'Dust resistance', 'we ensure that your vehicle didn\'t open into the dust', 800.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `sliit_id` varchar(20) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `sliit_id`, `nic`, `gender`, `dob`, `email`, `password`, `created_at`) VALUES
(4, 'tharaka2001', 'tharaka', 'dharmasiri', 'it22631206', '200130801391', 'male', '2024-09-02', 'tharaka@gmail.com', '$2y$10$Q4Iep4N2fHe90C/CY9MxRu5sCjM46UgfgtDRG.Aj0pi9l4r.QoyqG', '2024-09-28 16:13:00');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_passes`
--

CREATE TABLE `visitor_passes` (
  `id` int(11) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `hours` int(11) NOT NULL,
  `num_vehicles` int(11) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitor_passes`
--

INSERT INTO `visitor_passes` (`id`, `vehicle_type`, `date`, `hours`, `num_vehicles`, `contact_number`, `created_at`) VALUES
(1, 'car', '2024-10-08', 4, 1, '0714310048', '2024-09-29 06:46:36'),
(2, 'car', '2024-09-24', 4, 2, '0714310048', '2024-09-29 06:47:12'),
(3, 'car', '2024-10-10', 4, 2, '0714310048', '2024-09-29 06:48:33'),
(4, 'car', '2024-10-11', 5, 3, '0714310048', '2024-09-29 06:52:39'),
(5, 'car', '2024-10-02', 2, 2, '122222222222', '2024-09-29 06:54:54'),
(6, 'car', '2024-10-03', 2, 2, '0714310048', '2024-09-29 06:55:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `sliit_id` (`sliit_id`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visitor_passes`
--
ALTER TABLE `visitor_passes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visitor_passes`
--
ALTER TABLE `visitor_passes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
