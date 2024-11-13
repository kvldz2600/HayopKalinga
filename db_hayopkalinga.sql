-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2023 at 02:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hayopkalinga`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `id_pet` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `reason_for_visit` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Waiting Appointment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `id_pet`, `id_user`, `date`, `time`, `reason_for_visit`, `status`) VALUES
(18, 20, 7, '2023-10-24', '10:00:00', 'Grooming', 'Waiting Appointment'),
(19, 19, 6, '2023-10-24', '09:00:00', 'Vaccination', 'Waiting Appointment'),
(20, 19, 6, '2023-10-27', '12:00:00', 'Check-up', 'Waiting Appointment'),
(21, 20, 7, '2023-10-28', '11:00:00', 'Check-up', 'Waiting Appointment'),
(22, 21, 8, '2023-10-26', '16:00:00', 'Grooming', 'Service Given');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `name` varchar(512) DEFAULT NULL,
  `quantity` int(22) DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `price` float DEFAULT NULL,
  `freshness` varchar(255) DEFAULT 'Fresh'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `name`, `quantity`, `exp_date`, `price`, `freshness`) VALUES
(5, 'Chocolate', 49, '2023-10-27', 500, 'Near Expiration'),
(8, 'Syringe', 190, '2024-01-05', 200, 'Near Expiration'),
(9, 'Choco Flakes', 200, '2023-10-25', 500, 'Expired'),
(10, 'Gloves', 3000, '2024-02-01', 20, 'Fresh'),
(11, 'Mouse', 500, '2023-10-26', 500, 'Near Expiration'),
(12, 'Speaker', 500, '2023-10-28', 5000, 'Near Expiration');

-- --------------------------------------------------------

--
-- Table structure for table `pet_information`
--

CREATE TABLE `pet_information` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pet_name` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `breed` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `med_history` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `current_medication` text DEFAULT NULL,
  `vaccination_record` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_information`
--

INSERT INTO `pet_information` (`id`, `user_id`, `pet_name`, `type`, `breed`, `age`, `gender`, `med_history`, `allergies`, `current_medication`, `vaccination_record`) VALUES
(19, 6, 'Brownie', 'Cat', 'Sphinx', 11, 'male', 'N/A', 'N/A', 'N/A', 'N/A'),
(20, 7, 'Hailey', 'Dog', 'Bulldog', 9, 'male', 'N/A', 'Chocolate', ' N/A', 'June 12, 2009\\\\\\\\r\\\\\\\\nVet Name - Jayme Alvaro\\\\\\\\r\\\\\\\\nBatch Number XH9028\\\\\\\\r\\\\\\\\nNext Due: December 9, 2009'),
(21, 8, 'Blacky', 'Dog', 'Askal', 5, 'neutered_spayed', 'N/A', 'Chocolate', 'N/A', 'June 12 2019\\r\\nDr. Jose Rizal\\r\\nBatch Number SPH101294\\r\\nNext Due Due: July 19 2024');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `pnumb` varchar(13) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `address`, `pnumb`, `email`, `password`) VALUES
(0, '', '', '0', '', 'admin.hayopkalinga@gmail.com', '$2y$10$PSCEpQ0SxPfv9CFx4oEFuu8kcztnWuIord3lWZU/Qluf1U3HmY14S'),
(6, 'Juan', 'Dela Cruz', '5678 Kahit saan Street', '+639987654321', 'juan.cruz@gmail.com', '$2y$10$W1dpK1Wz.DRYQcQdekKiQuWNHyA.m63.tI4G43AX2hRxAzKhwfZG.'),
(7, 'Juanita', 'Gomez', '5678 Kung saan man street', '09987654321', 'juanita.cruz@gmail.com', '$2y$10$qGm77O5fNcPpkXbPBvkj0.KC8qrDkMNtjFnQdPG81CPztgMvfwVaC'),
(8, 'Test', 'Demo', '12345 Kahit saan saan', '+639789456123', 'test.demo@gmail.com', '$2y$10$R8jQt1GSJ2QoDnNZq49BcuUGgUsRfJDUp6w0w6XQuW5ZZ3YpoHbRG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pet` (`id_pet`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pet_information`
--
ALTER TABLE `pet_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pnumb` (`pnumb`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pet_information`
--
ALTER TABLE `pet_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`id_pet`) REFERENCES `pet_information` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `pet_information`
--
ALTER TABLE `pet_information`
  ADD CONSTRAINT `pet_information_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
