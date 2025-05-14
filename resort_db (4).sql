-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 06:58 AM
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
-- Database: `resort_db`
--
CREATE DATABASE IF NOT EXISTS `resort_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `resort_db`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_tb`
--

CREATE TABLE `admin_tb` (
  `admin_id` int(11) NOT NULL,
  `admin_username` varchar(50) NOT NULL,
  `admin_password` varchar(50) NOT NULL,
  `admin_type` varchar(50) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_tb`
--

INSERT INTO `admin_tb` (`admin_id`, `admin_username`, `admin_password`, `admin_type`) VALUES
(1, 'admin', 'admin123', 'admin'),
(2, 'staff', '123', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `contact_tb`
--

CREATE TABLE `contact_tb` (
  `contact_id` int(11) NOT NULL,
  `contact_address` varchar(50) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `contact_email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_tb`
--

INSERT INTO `contact_tb` (`contact_id`, `contact_address`, `contact_number`, `contact_email`) VALUES
(1, 'Brgy. Sapsap, Pastrana, Leyte', '09942689414', 'casamarcosresort@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `food_tb`
--

CREATE TABLE `food_tb` (
  `food_id` int(11) NOT NULL,
  `food_image_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image_tb`
--

CREATE TABLE `image_tb` (
  `image_id` int(11) NOT NULL,
  `image_name` varchar(50) NOT NULL,
  `image_img` varchar(50) NOT NULL,
  `image_description` varchar(500) NOT NULL,
  `image_price` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image_tb`
--

INSERT INTO `image_tb` (`image_id`, `image_name`, `image_img`, `image_description`, `image_price`) VALUES
(10, 'pork', '2.png', 'aa', '100'),
(11, 'pork', '3.png', 'aa', '200'),
(12, 'pancit', '4.png', 'aa', '2000'),
(13, 'pork', '5.png', 'aa', '200'),
(14, 'Front', '5.png', 'aa', '200'),
(15, 'Front', '6.png', 'aa', '500');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `message_contact_id` int(11) NOT NULL,
  `recipient_email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message_content` text NOT NULL,
  `date_sent` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'unread',
  `viewed_status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `message_contact_id`, `recipient_email`, `subject`, `message_content`, `date_sent`, `status`, `viewed_status`) VALUES
(30, 1, 'james@poto.com', 'casa marcos reservation', 'sample', '2025-04-24 04:41:07', 'unread', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `pay_method_id` int(11) NOT NULL,
  `pay_reservation_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `amount` varchar(50) NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `date_of_payment` date NOT NULL,
  `proof_of_payment` varchar(50) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `pay_method_id`, `pay_reservation_id`, `name`, `amount`, `reference_number`, `date_of_payment`, `proof_of_payment`, `payment_type`, `status`) VALUES
(381, 1, 100, 'james', '5000', '123456', '2025-03-31', 'gcash.png', '', 'paid'),
(410, 1, 103, 'fgfdfg', '11999', '', '2025-03-31', '', '', 'unpaid'),
(411, 1, 104, 'reo', '11999', '', '2025-03-31', '', '', 'unpaid'),
(415, 8, 105, 'reo', '11999', '', '0000-00-00', '', '', 'unpaid'),
(416, 8, 106, 'justin catanoy', '11999', '', '0000-00-00', '', '', 'unpaid'),
(417, 1, 107, 'farm', '11999', '', '0000-00-00', '', '', 'unpaid'),
(418, 1, 107, '', '', '23456789', '2025-03-31', 'gcash.png', '', 'paid'),
(419, 1, 108, 'james nathaniel c', '5399', '', '0000-00-00', '', '', 'unpaid'),
(420, 1, 108, '', '', '23456789', '2025-03-31', '67ea4e7a8aa50_gcash.png', '', 'paid'),
(421, 1, 109, '', '', '23456789', '2025-03-31', 'gcash.png', '', 'paid'),
(422, 1, 110, 'james nathanie', '7999', '23456789', '2025-03-31', 'gcash.png', '', 'paid'),
(423, 1, 111, 'WENDELL Rivas YU', '11999', '123', '2025-03-31', 'img001.jpg', '', 'paid'),
(424, 1, 126, 'james nathaniel ca', '11999', '4024370117161', '2025-04-08', 'Messenger_creation_74E63F5A-A79A-465B-BE4E-D744721', '', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `pay_method`
--

CREATE TABLE `pay_method` (
  `method_id` int(11) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'gcash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pay_method`
--

INSERT INTO `pay_method` (`method_id`, `payment_method`) VALUES
(1, 'gcash'),
(8, 'cash');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `reply_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `reply_content` text NOT NULL,
  `date_sent` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `res_services_id` int(11) NOT NULL,
  `res_method_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(50) DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `res_services_id`, `res_method_id`, `name`, `email`, `phone`, `checkin`, `checkout`, `message`, `status`, `payment_status`) VALUES
(100, 14, 1, 'james nathaniel', 'james@poto.com', '0923456789', '2025-03-31', '2025-04-01', '', 'checked out', 'unpaid'),
(101, 14, 1, 'james', 'tgdft@ghdf.com', '0923456789', '2025-03-31', '2025-04-01', 'sam', 'checked in', 'unpaid'),
(102, 14, 1, 'jhon ', 'juan@carlos.com', '0923456789', '2025-03-31', '2025-04-01', '', 'Checked Out', 'unpaid'),
(103, 14, 1, 'fgfdfg', 'espielreo635@gmail.com', '0923456789', '2025-03-31', '2025-04-01', 's', 'checked in', 'unpaid'),
(104, 14, 1, 'reo', 'espielreo6351@gmail.com', '0923456789', '2025-03-31', '2025-04-01', 'sam', 'Checked Out', 'unpaid'),
(105, 14, 8, 'reo', 'espielreo635@gmail.com', '0923456789', '2025-03-31', '2025-04-01', 'sam', 'Approved', 'unpaid'),
(106, 14, 8, 'justin catanoy', 'justin@gmail.com', '0923456789', '2025-03-31', '2025-04-01', 'sam', 'Approved', 'unpaid'),
(107, 14, 1, 'farm', 'farm@gmail.com', '0923456789', '2025-03-31', '2025-04-01', 'san', 'Approved', 'paid'),
(108, 16, 1, 'james nathaniel c', 'jamesc@poto.com', '0923456789', '2025-03-31', '2025-04-01', 'sam', 'pending', 'paid'),
(109, 18, 1, 'james calinawan', 'james@gmail.com', '0923456789', '2025-03-31', '2025-04-01', 'sam', 'checked out', 'paid'),
(110, 17, 1, 'james nathanie', 'james123@gmail.com', '0923456789', '2025-03-31', '2025-04-01', 'sam', 'pending', 'paid'),
(111, 14, 1, 'WENDELL Rivas YU', 'wendelrivasyu.17@gmail.com', '912345678', '2025-03-31', '2025-04-01', 'N/A', 'Approved', 'paid'),
(112, 14, 8, 'WENDELL Rivas YU', 'wendelrivasyu.17@gmail.com', '912345678', '2025-03-31', '2025-04-01', 'N/A N/A', 'pending', 'unpaid'),
(126, 14, 1, 'james nathaniel ca', 'james22@poto.com', '0923456789', '2025-04-08', '2025-04-09', '', 'pending', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `room_status_history`
--

CREATE TABLE `room_status_history` (
  `history_id` int(11) NOT NULL,
  `services_id` int(11) NOT NULL,
  `previous_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `change_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services_tb`
--

CREATE TABLE `services_tb` (
  `services_id` int(11) NOT NULL,
  `services_name` varchar(50) NOT NULL,
  `services_image` varchar(100) NOT NULL,
  `services_description` varchar(500) NOT NULL,
  `services_price` decimal(10,0) NOT NULL,
  `booking_count` int(11) DEFAULT 0,
  `status_note` varchar(50) NOT NULL,
  `status` enum('available','occupied','maintenance') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services_tb`
--

INSERT INTO `services_tb` (`services_id`, `services_name`, `services_image`, `services_description`, `services_price`, `booking_count`, `status_note`, `status`) VALUES
(14, 'Sapphira Villas 8 Pax', 'villas.jpg', 'A two story villa with 8 single-size beds, 2 private bathrooms, large private living room, TV, Wi-Fi & air conditioning.\r\n', 11999, 0, '', 'occupied'),
(16, 'Matrimonial Plus', 'mp.jpg', 'Queen-size bed, larger room, larger private bathroom, TV, Wi-Fi, air conditioning.\r\n', 5399, 0, '', 'available'),
(17, 'Barkada', 'CVP.jpg', '4 Twin-sized bed, private bathroom, TV, Wi-Fi, air conditioning', 7999, 2, '', 'occupied'),
(18, 'CV Room 4 Pax', 'CV4.jpg', '2 Queen-sized bed, Shared bathroom, TV, Wi-Fi, air conditioning', 3999, 0, '', 'available'),
(20, 'CV Room 8 Pax', 'CVP.jpg', '8 Single-sized bed, shared bathroom, TV, Wi-Fi, air conditioning', 5999, 0, '', ''),
(51, 'Sapphira Villas 6 Pax', 'history.jpg', 'A two story villa with 1 Queen-size bed & 4 single-size beds, 2 private bathrooms, large private living room, TV, Wi-Fi & air conditioning.', 8999, 0, '', 'occupied'),
(58, 'Matrimonial', 'room.jpg', 'Queen-size bed, private bathroom, TV, Wi-fi, air conditioning', 4999, 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `time_tb`
--

CREATE TABLE `time_tb` (
  `time_id` int(11) NOT NULL,
  `time_reservation_id` int(11) NOT NULL,
  `time_in` time NOT NULL DEFAULT current_timestamp(),
  `time_out` time NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_tb`
--

INSERT INTO `time_tb` (`time_id`, `time_reservation_id`, `time_in`, `time_out`) VALUES
(7, 100, '07:59:00', '19:59:00'),
(8, 103, '05:31:00', '16:31:24'),
(9, 101, '04:47:00', '16:47:17'),
(10, 109, '17:01:00', '17:02:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_tb`
--
ALTER TABLE `admin_tb`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `contact_tb`
--
ALTER TABLE `contact_tb`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `food_tb`
--
ALTER TABLE `food_tb`
  ADD PRIMARY KEY (`food_id`),
  ADD KEY `food_image_id` (`food_image_id`);

--
-- Indexes for table `image_tb`
--
ALTER TABLE `image_tb`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `message_contact_id` (`message_contact_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `resort_db` (`pay_method_id`),
  ADD KEY `pay_reservation_id` (`pay_reservation_id`);

--
-- Indexes for table `pay_method`
--
ALTER TABLE `pay_method`
  ADD PRIMARY KEY (`method_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `message_id` (`message_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `resort_db` (`res_services_id`) USING BTREE,
  ADD KEY `res_method_id` (`res_method_id`);

--
-- Indexes for table `room_status_history`
--
ALTER TABLE `room_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_services_id` (`services_id`),
  ADD KEY `idx_date_range` (`start_date`,`end_date`);

--
-- Indexes for table `services_tb`
--
ALTER TABLE `services_tb`
  ADD PRIMARY KEY (`services_id`);

--
-- Indexes for table `time_tb`
--
ALTER TABLE `time_tb`
  ADD PRIMARY KEY (`time_id`),
  ADD KEY `resort_db` (`time_reservation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_tb`
--
ALTER TABLE `admin_tb`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_tb`
--
ALTER TABLE `contact_tb`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `food_tb`
--
ALTER TABLE `food_tb`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image_tb`
--
ALTER TABLE `image_tb`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=425;

--
-- AUTO_INCREMENT for table `pay_method`
--
ALTER TABLE `pay_method`
  MODIFY `method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `room_status_history`
--
ALTER TABLE `room_status_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services_tb`
--
ALTER TABLE `services_tb`
  MODIFY `services_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `time_tb`
--
ALTER TABLE `time_tb`
  MODIFY `time_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `food_tb`
--
ALTER TABLE `food_tb`
  ADD CONSTRAINT `food_tb_ibfk_1` FOREIGN KEY (`food_image_id`) REFERENCES `image_tb` (`image_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`message_contact_id`) REFERENCES `contact_tb` (`contact_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`pay_method_id`) REFERENCES `pay_method` (`method_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`pay_reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`message_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`res_services_id`) REFERENCES `services_tb` (`services_id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`res_method_id`) REFERENCES `pay_method` (`method_id`);

--
-- Constraints for table `room_status_history`
--
ALTER TABLE `room_status_history`
  ADD CONSTRAINT `room_status_history_ibfk_1` FOREIGN KEY (`services_id`) REFERENCES `services_tb` (`services_id`);

--
-- Constraints for table `time_tb`
--
ALTER TABLE `time_tb`
  ADD CONSTRAINT `time_tb_ibfk_1` FOREIGN KEY (`time_reservation_id`) REFERENCES `reservations` (`reservation_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
