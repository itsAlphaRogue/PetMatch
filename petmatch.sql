-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2025 at 06:48 PM
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
-- Database: `petmatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$5YInfwxZgviSC/i25xkz/O3TZqTtFirdZQOchW5Qx7ylPXF7cs5tu');

-- --------------------------------------------------------

--
-- Table structure for table `adoption_requests`
--

CREATE TABLE `adoption_requests` (
  `id` int(11) NOT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `breeds`
--

CREATE TABLE `breeds` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `breeds`
--

INSERT INTO `breeds` (`id`, `name`) VALUES
(33, 'border collie'),
(24, 'bulldog'),
(30, 'german shepherd'),
(26, 'husky'),
(29, 'labrador retriever'),
(31, 'pomarian'),
(27, 'poodle'),
(32, 'siberian husky');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `breed_id` int(11) DEFAULT NULL,
  `age` enum('Puppy','Young','Adult') DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `status` enum('Available','Reserved','Adopted') DEFAULT 'Available',
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `name`, `breed_id`, `age`, `gender`, `status`, `description`, `image`) VALUES
(33, 'Lucky', 30, 'Adult', 'Male', 'Available', 'Lucky is a loyal and protective adult German Shepherd. He’s intelligent, well-behaved, and makes a great companion for active families or individuals looking for a faithful friend.\r\n', 'pet_685537b4f29ba3.84969768.jpg'),
(34, 'Wiggles', 31, 'Young', 'Female', 'Available', 'Wiggles is a playful young Pomeranian with a bubbly personality. She’s full of energy, loves attention, and is perfect for someone looking for a cheerful and cuddly companion.\r\n', 'pet_68553895022226.03787809.jpg'),
(35, 'Juno', 27, 'Puppy', 'Male', 'Available', 'Juno is an adorable poodle puppy with a curious and friendly nature. He’s smart, easy to train, and loves to be around people, making him a great fit for any loving home.\r\n', 'pet_685538cb197a50.13778026.jpg'),
(36, 'Hugo', 32, 'Puppy', 'Male', 'Available', 'Hugo is a lively Siberian Husky puppy with striking looks and a playful spirit. He’s energetic, adventurous, and will thrive in an active home that can keep up with his fun-loving nature.', 'pet_68553e65278f33.33217059.png'),
(37, 'Kirpkey', 33, 'Adult', 'Female', 'Available', 'Kirpkey is a smart and energetic adult Border Collie. She’s highly trainable, loves to stay active, and is ideal for families or individuals who enjoy outdoor activities and mental challenges.', 'pet_6855411e5de5e9.75153079.png'),
(38, 'Frito', 24, 'Adult', 'Male', 'Available', 'Frito is a calm and sturdy adult Bulldog with a gentle heart. He’s affectionate, great with families, and enjoys relaxed walks and plenty of cozy downtime.', 'pet_685541af9f5905.52906579.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`) VALUES
(7, 'Tester', 'tester@gmail.com', 'lkjadsflkjalfkjadslf', '5988377744'),
(8, 'User', 'user@gmail.com', '$2y$10$0rnEISgq8SIX0DMHBtLPlO.e/oRYLbAsT55hMrrRoEdCNlS.iklrO', '9832837459'),
(9, 'Laxmi', 'laxmip@gmail.com', '$2y$10$l4j9N5ACV0myZnwnjg4BSe/aqL92zRdfj8z/aapOsM4RbUdOGQncW', '9812345623'),
(10, 'Sujal', 'sujal123@gmail.com', '$2y$10$B/W0e7oatg0ZbLMaYyqkyuyT97RqedAjSmfL0t7ZDuRpQmKrJG1DS', '9843833933'),
(11, 'sudip', 'xthaasudip07@gmail.com', '$2y$10$PGMRBUfA6iovuWk.FASaDO0ONCOV/Mu6nciZCwBwNi3eKz1e8Fzte', '9852688851');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `adoption_requests`
--
ALTER TABLE `adoption_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pet_id` (`pet_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `breeds`
--
ALTER TABLE `breeds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `breed_id` (`breed_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `adoption_requests`
--
ALTER TABLE `adoption_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `breeds`
--
ALTER TABLE `breeds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adoption_requests`
--
ALTER TABLE `adoption_requests`
  ADD CONSTRAINT `adoption_requests_ibfk_1` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adoption_requests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`breed_id`) REFERENCES `breeds` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
