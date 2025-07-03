-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 03, 2025 alle 18:47
-- Versione del server: 10.4.27-MariaDB
-- Versione PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `autoshowroom`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(5, 'admin', '$2y$10$izHmH.m9bX0cf4jiYtbvbOYNAygkUOtMgfRygPUQXHhgnL1HpZ4j6');

-- --------------------------------------------------------

--
-- Struttura della tabella `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'Abarth'),
(2, 'Alfa Romeo'),
(3, 'Audi'),
(4, 'BMW'),
(5, 'Citroën'),
(6, 'Dacia'),
(7, 'Fiat'),
(8, 'Ford'),
(9, 'Honda'),
(10, 'Hyundai'),
(11, 'Jeep'),
(12, 'Kia'),
(13, 'Lamborghini'),
(14, 'Land Rover'),
(15, 'Lexus'),
(16, 'Maserati'),
(17, 'Mazda'),
(18, 'Mercedes-Benz'),
(19, 'MINI'),
(20, 'Mitsubishi'),
(21, 'Nissan'),
(22, 'Opel'),
(23, 'Peugeot'),
(24, 'Porsche'),
(25, 'Renault'),
(26, 'SEAT'),
(27, 'Skoda'),
(28, 'Suzuki'),
(29, 'Tesla'),
(30, 'Toyota'),
(31, 'Volkswagen'),
(32, 'Volvo');

-- --------------------------------------------------------

--
-- Struttura della tabella `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `car_type_id` int(11) NOT NULL,
  `fuel_type_id` int(11) NOT NULL,
  `transmission_id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `km` int(11) NOT NULL,
  `seats` tinyint(4) NOT NULL,
  `power_kw` int(11) NOT NULL,
  `car_condition` enum('nuovo','usato') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `cars`
--

INSERT INTO `cars` (`id`, `brand_id`, `car_type_id`, `fuel_type_id`, `transmission_id`, `year`, `price`, `km`, `seats`, `power_kw`, `car_condition`) VALUES
(24, 24, 1, 1, 2, 2018, '150000.00', 0, 2, 0, 'nuovo'),
(25, 24, 1, 1, 2, 2018, '150000.00', 0, 2, 0, 'nuovo'),
(26, 24, 1, 1, 2, 2010, '100000.00', 0, 2, 0, 'nuovo');

-- --------------------------------------------------------

--
-- Struttura della tabella `car_images`
--

CREATE TABLE `car_images` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `car_images`
--

INSERT INTO `car_images` (`id`, `car_id`, `image_path`) VALUES
(77, 24, '../assets/uploads/img_679bd125844464.81149695.jpg'),
(78, 24, '../assets/uploads/img_679bd126f37324.71713837.jpg'),
(79, 24, '../assets/uploads/img_679bd128cffde7.67304516.jpg'),
(80, 24, '../assets/uploads/img_679bd12acdd8e2.83546938.jpg'),
(81, 24, '../assets/uploads/img_679bd12cbb65e4.97765442.jpg'),
(82, 24, '../assets/uploads/img_679bd12e9ca804.01826249.jpg'),
(83, 25, '../assets/uploads/img_679bd13af2af07.20413087.jpg'),
(84, 25, '../assets/uploads/img_679bd13c7cd460.22067205.jpg'),
(85, 25, '../assets/uploads/img_679bd13e5c98f9.14262013.jpg'),
(86, 25, '../assets/uploads/img_679bd1405a67b9.45413487.jpg'),
(87, 25, '../assets/uploads/img_679bd14252f032.80556307.jpg'),
(88, 25, '../assets/uploads/img_679bd14431c2f3.71577073.jpg'),
(89, 26, '../assets/uploads/img_679bd355e971b8.84448483.jpg'),
(90, 26, '../assets/uploads/img_679bd357635c64.96674934.jpg'),
(91, 26, '../assets/uploads/img_679bd35984c1b4.08787846.jpg'),
(92, 26, '../assets/uploads/img_679bd35b989dd5.06805724.jpg'),
(93, 26, '../assets/uploads/img_679bd35d9a16d9.44335155.jpg'),
(94, 26, '../assets/uploads/img_679bd35f8cbee4.47203901.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `car_types`
--

CREATE TABLE `car_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `car_types`
--

INSERT INTO `car_types` (`id`, `name`) VALUES
(1, 'Berlina'),
(3, 'Cabrio'),
(2, 'Coupé'),
(5, 'Crossover'),
(9, 'Fuoristrada'),
(7, 'Monovolume'),
(8, 'Pick-up'),
(10, 'Roadster'),
(6, 'Station Wagon'),
(4, 'SUV'),
(11, 'Van');

-- --------------------------------------------------------

--
-- Struttura della tabella `fuel_types`
--

CREATE TABLE `fuel_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `fuel_types`
--

INSERT INTO `fuel_types` (`id`, `name`) VALUES
(1, 'Benzina'),
(2, 'Diesel'),
(6, 'Elettrico'),
(7, 'GPL'),
(3, 'Ibrido benzina'),
(4, 'Ibrido diesel'),
(9, 'Idrogeno'),
(8, 'Metano'),
(5, 'Plug-in Hybrid');

-- --------------------------------------------------------

--
-- Struttura della tabella `transmissions`
--

CREATE TABLE `transmissions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `transmissions`
--

INSERT INTO `transmissions` (`id`, `name`) VALUES
(2, 'Automatico'),
(4, 'CVT'),
(1, 'Manuale'),
(3, 'Semiautomatico');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indici per le tabelle `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `car_type_id` (`car_type_id`),
  ADD KEY `fuel_type_id` (`fuel_type_id`),
  ADD KEY `transmission_id` (`transmission_id`);

--
-- Indici per le tabelle `car_images`
--
ALTER TABLE `car_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indici per le tabelle `car_types`
--
ALTER TABLE `car_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `fuel_types`
--
ALTER TABLE `fuel_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `transmissions`
--
ALTER TABLE `transmissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT per la tabella `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT per la tabella `car_images`
--
ALTER TABLE `car_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT per la tabella `car_types`
--
ALTER TABLE `car_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `fuel_types`
--
ALTER TABLE `fuel_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `transmissions`
--
ALTER TABLE `transmissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cars_ibfk_2` FOREIGN KEY (`car_type_id`) REFERENCES `car_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cars_ibfk_3` FOREIGN KEY (`fuel_type_id`) REFERENCES `fuel_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cars_ibfk_4` FOREIGN KEY (`transmission_id`) REFERENCES `transmissions` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `car_images`
--
ALTER TABLE `car_images`
  ADD CONSTRAINT `car_images_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
