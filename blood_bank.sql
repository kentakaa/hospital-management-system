-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 02:52 PM
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
-- Database: `blood_recovery`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `data` text DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `data`, `patient_id`, `doctor_id`, `appointment_date`, `status`, `reason`) VALUES
(3, NULL, 1, 1, '2026-01-15 20:55:00', 'pending', 'joker hai hopper');

-- --------------------------------------------------------

--
-- Table structure for table `beds`
--

CREATE TABLE `beds` (
  `bed_id` int(11) NOT NULL,
  `ward_id` int(11) NOT NULL,
  `bed_number` int(11) NOT NULL,
  `is_occupied` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beds`
--

INSERT INTO `beds` (`bed_id`, `ward_id`, `bed_number`, `is_occupied`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 0),
(3, 1, 3, 0),
(4, 1, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `blood_stock`
--

CREATE TABLE `blood_stock` (
  `stock_id` int(11) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `units_available` int(11) DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_stock`
--

INSERT INTO `blood_stock` (`stock_id`, `blood_group`, `units_available`, `last_updated`) VALUES
(17, 'A+', 11, '2026-01-10 12:30:20'),
(18, 'A-', 2, '2026-01-12 06:36:45'),
(19, 'B+', 8, '2026-01-06 12:56:51'),
(20, 'B-', 0, '2026-01-06 14:29:03'),
(21, 'AB+', 6, '2026-01-10 12:35:59'),
(22, 'AB-', 1, '2026-01-10 16:23:46'),
(23, 'O+', 12, '2026-01-06 12:56:51'),
(24, 'O-', 7, '2026-01-06 12:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `camps`
--

CREATE TABLE `camps` (
  `camp_id` int(11) NOT NULL,
  `camp_name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `organizer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `camps`
--

INSERT INTO `camps` (`camp_id`, `camp_name`, `location`, `date`, `organizer`) VALUES
(1, 'Blood Donation Camp 1', 'Delhi', '2026-01-05', 'Red Cross'),
(2, 'Blood Donation Camp 2', 'Mumbai', '2026-01-06', 'Lions Club'),
(3, 'Blood Donation Camp 3', 'Pune', '2026-01-07', 'Rotary Club'),
(4, 'Blood Donation Camp 4', 'Bengaluru', '2026-01-08', 'AIIMS'),
(5, 'Blood Donation Camp 5', 'Chennai', '2026-01-09', 'Govt Hospital'),
(6, 'Blood Donation Camp 6', 'Hyderabad', '2026-01-10', 'Red Cross'),
(7, 'Blood Donation Camp 7', 'Kolkata', '2026-01-11', 'NGO Trust'),
(8, 'Blood Donation Camp 8', 'Jaipur', '2026-01-12', 'Medical College'),
(9, 'Blood Donation Camp 9', 'Lucknow', '2026-01-13', 'Health Dept'),
(10, 'Blood Donation Camp 10', 'Ahmedabad', '2026-01-14', 'Rotary Club');

-- --------------------------------------------------------

--
-- Table structure for table `camp_registrations`
--

CREATE TABLE `camp_registrations` (
  `reg_id` int(11) NOT NULL,
  `camp_id` int(11) DEFAULT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `reg_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `user_id`, `specialization`, `qualification`, `experience`, `phone`, `created_at`) VALUES
(1, 15, 'cardiology', 'mbbs', 4, NULL, '2026-01-14 09:43:52');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `camp_id` int(11) DEFAULT NULL,
  `donation_date` date DEFAULT NULL,
  `units_donated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `donor_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `last_donation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admission_date` date DEFAULT curdate(),
  `bed_number` int(11) NOT NULL,
  `current_bed_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `user_id`, `admission_date`, `bed_number`, `current_bed_id`) VALUES
(1, 8, '2026-01-04', 1, 1),
(3, 10, '2026-01-04', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `units_requested` int(11) DEFAULT NULL,
  `status` enum('pending','approved','fulfilled','rejected') DEFAULT 'pending',
  `request_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`request_id`, `recipient_id`, `blood_group`, `units_requested`, `status`, `request_date`) VALUES
(1, 11, 'A-', 56, 'fulfilled', '2026-01-06'),
(2, 11, 'A-', 2, 'fulfilled', '2026-01-06'),
(3, 11, 'B-', 1, 'fulfilled', '2026-01-06'),
(4, 11, 'AB-', 2, 'fulfilled', '2026-01-06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','donor','recipient','staff','patient','doctor') DEFAULT 'donor',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `phone`, `password`, `role`, `is_active`) VALUES
(1, 'wolf', 'wolf@gmail.com', '9650218856', '0000', 'donor', 0),
(2, 'Arshad', 'arshad@gmail.com', '9012421242', '9090', 'donor', 1),
(3, 'Admin', 'admin@gmail.com', '9090903245', 'admin11', 'admin', 1),
(8, 'assistant', 'assist@gmail.com', '8945218812', '$2y$10$8Z5hlNF3w3iEOyTWVxop6uQC/vbhImKgpawufiSQRR2QMQ4znyd4G', 'patient', 1),
(10, 'lester', 'lester@gmail.com', '8956455689', '$2y$10$MKUBumQL86U5AtWJTNRqYuY8Z6GYYv4wjQa9jHyirn1lS3Pi6eOKC', 'patient', 1),
(11, 'groot', 'gr@gmail.com', '9076567845', 'groot1', 'recipient', 1),
(12, 'billu', 'billu@mail.com', '9090765654', '$2y$10$XyNp/tZt3hdbUHhiqMY8f.qThGGK..NBxG58oEi/EgaxDUu7Un7XK', 'staff', 1),
(15, 'hopper', 'hopper@mail.com', '9087890987', '$2y$10$ewkiVsNoXKH8AvRXcK0zNu9xy18roPCse3hnnLj5FDaRqxsJGwn4C', 'doctor', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wards`
--

CREATE TABLE `wards` (
  `ward_id` int(11) NOT NULL,
  `ward_name` varchar(100) NOT NULL,
  `total_beds` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wards`
--

INSERT INTO `wards` (`ward_id`, `ward_name`, `total_beds`) VALUES
(1, 'General Ward', 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `fk_appointments_patients` (`patient_id`);

--
-- Indexes for table `beds`
--
ALTER TABLE `beds`
  ADD PRIMARY KEY (`bed_id`),
  ADD KEY `fk_beds_wards` (`ward_id`);

--
-- Indexes for table `blood_stock`
--
ALTER TABLE `blood_stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `camps`
--
ALTER TABLE `camps`
  ADD PRIMARY KEY (`camp_id`);

--
-- Indexes for table `camp_registrations`
--
ALTER TABLE `camp_registrations`
  ADD PRIMARY KEY (`reg_id`),
  ADD KEY `camp_id` (`camp_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `camp_id` (`camp_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`donor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD KEY `patients_ibfk_1` (`user_id`),
  ADD KEY `fk_patients_beds` (`current_bed_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`ward_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `beds`
--
ALTER TABLE `beds`
  MODIFY `bed_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blood_stock`
--
ALTER TABLE `blood_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `camps`
--
ALTER TABLE `camps`
  MODIFY `camp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `camp_registrations`
--
ALTER TABLE `camp_registrations`
  MODIFY `reg_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `wards`
--
ALTER TABLE `wards`
  MODIFY `ward_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointments_patients` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `beds`
--
ALTER TABLE `beds`
  ADD CONSTRAINT `fk_beds_wards` FOREIGN KEY (`ward_id`) REFERENCES `wards` (`ward_id`) ON DELETE CASCADE;

--
-- Constraints for table `camp_registrations`
--
ALTER TABLE `camp_registrations`
  ADD CONSTRAINT `camp_registrations_ibfk_1` FOREIGN KEY (`camp_id`) REFERENCES `camps` (`camp_id`),
  ADD CONSTRAINT `camp_registrations_ibfk_2` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`),
  ADD CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`camp_id`) REFERENCES `camps` (`camp_id`);

--
-- Constraints for table `donors`
--
ALTER TABLE `donors`
  ADD CONSTRAINT `donors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `fk_patients_beds` FOREIGN KEY (`current_bed_id`) REFERENCES `beds` (`bed_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
