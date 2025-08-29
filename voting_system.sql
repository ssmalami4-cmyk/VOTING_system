-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 04, 2025 at 03:56 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voting_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `username`, `password`, `email`, `user_type`) VALUES
(1, 'IBRAHIM RABIU', 'admin', '$2y$10$NiB2f1BUji6pRhpqXpRzkOdhDgIv9KrfOqazQLHrLs6BUSoZeMeTi', 'admin@vote.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

DROP TABLE IF EXISTS `candidates`;
CREATE TABLE IF NOT EXISTS `candidates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `office` varchar(255) NOT NULL,
  `faculty` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `election_id` int(11) NOT NULL,
  `office_id` int(11) NOT NULL,
  `votes` int(11) DEFAULT 0,
  `user_type` varchar(50) NOT NULL DEFAULT 'candidate',
  PRIMARY KEY (`id`),
  KEY `fk_office` (`office_id`),
  KEY `fk_election` (`election_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `name`, `email`, `office`, `faculty`, `department`, `level`, `photo`, `approved`, `election_id`, `office_id`, `votes`, `user_type`) VALUES
(1, 'Engr Malami', 'malami@gmail.com', 'President', 'Engineering ', 'Computer Engineering ', '500', 'item22.jpg', 1, 1, 1, 6, 'candidate'),
(2, 'Sarah Sulaiman', 'sarah@agroaid.com', 'President', 'Engineering ', 'Computer Engineering ', '500', 'item20.jpg', 1, 1, 1, 5, 'candidate'),
(3, 'Eng Musbahu ', 'musby@gmail.com', 'Vice President', 'Iya Abubakar institute of ICY', 'Computer Engineering ', '500', 'item21.jpg', 1, 1, 2, 5, 'candidate'),
(4, 'Engr AY', 'ay@gmail.com', 'Vice President', 'Iya Abubakar institute of ICY', 'Computer Engineering ', '500', 'item23.jpg', 1, 1, 2, 5, 'candidate'),
(5, 'Engr Audu', 'audu@gmail.com', 'Vice President', 'Engineering ', 'Computer Engineering ', '500', 'item28.jpg', 1, 1, 2, 1, 'candidate'),
(6, 'Lukman Isa', 'isa@gmail.com', 'Vice President', 'Engineering ', 'Computer Engineering ', '500', 'item24.jpg', 1, 1, 2, 0, 'candidate');

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

DROP TABLE IF EXISTS `elections`;
CREATE TABLE IF NOT EXISTS `elections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `name`, `start_date`, `end_date`) VALUES
(1, 'DLC Election', '2025-07-27 14:42:00', '2025-08-07 14:42:00'),
(2, 'IAIICT', '2025-07-21 18:56:00', '2025-07-26 14:51:00');

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

DROP TABLE IF EXISTS `offices`;
CREATE TABLE IF NOT EXISTS `offices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `offices`
--

INSERT INTO `offices` (`id`, `name`) VALUES
(1, 'President'),
(2, 'Vice President');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `office` varchar(255) NOT NULL,
  `candidate_name` varchar(255) NOT NULL,
  `votes` int(11) NOT NULL,
  `winner` tinyint(1) DEFAULT 0,
  `election_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `election_id` (`election_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

DROP TABLE IF EXISTS `voters`;
CREATE TABLE IF NOT EXISTS `voters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `faculty` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `election_id` int(11) DEFAULT NULL,
  `user_type` varchar(50) NOT NULL DEFAULT 'voter',
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_id` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`id`, `name`, `email`, `student_id`, `faculty`, `department`, `level`, `election_id`, `user_type`) VALUES
(1, 'name', 'email@gmail.com', 'student_id', 'faculty', 'department', 'level', 2, 'voter'),
(2, 'Aminu Abdullahi', 'aminu.abdullahi@abu.ng', 'STU1001', 'Engineering', 'Electrical', '400', 1, 'voter'),
(3, 'Zainab Musa', 'zainab.musa@abu.ng', 'STU1002', 'Social Sciences', 'Sociology', '100', 1, 'voter'),
(4, 'Bashir Ibrahim', 'bashir.ibrahim@abu.ng', 'STU1003', 'Medicine', 'Pharmacy', '200', 1, 'voter'),
(5, 'Hauwa Sani', 'hauwa.sani@abu.ng', 'STU1004', 'Education', 'Science Education', '100', 1, 'voter'),
(6, 'Ibrahim Umar', 'ibrahim.umar@abu.ng', 'STU1005', 'Law', 'Law', '500', 1, 'voter'),
(7, 'Fatima Ali', 'fatima.ali@abu.ng', 'STU1006', 'Business', 'Accounting', '300', 1, 'voter'),
(8, 'Sadiq Suleiman', 'sadiq.suleiman@abu.ng', 'STU1007', 'Agriculture', 'Animal Science', '200', 1, 'voter'),
(9, 'Aisha Yusuf', 'aisha.yusuf@abu.ng', 'STU1008', 'Arts', 'History', '400', 1, 'voter'),
(10, 'Yahaya Abdulkadir', 'yahaya.abdulkadir@abu.ng', 'STU1009', 'Engineering', 'Civil', '300', 1, 'voter'),
(11, 'Maryam Usman', 'maryam.usman@abu.ng', 'STU1010', 'Computer Science', 'Software Engineering', '200', 1, 'voter'),
(12, '1', 'John Doe', 'john.doe@abu.edu.ng', '12345', 'Engineering', 'Computer Science', 1, 'voter'),
(13, '2', 'Jane Smith', 'jane.smith@abu.edu.ng', '12346', 'Arts', 'English', 1, 'voter'),
(14, '3', 'Michael Johnson', 'michael.johnson@abu.edu.ng', '12347', 'Science', 'Biology', 1, 'voter'),
(15, '4', 'Emily Davis', 'emily.davis@abu.edu.ng', '12348', 'Engineering', 'Mechanical Engineering', 1, 'voter'),
(16, '5', 'Chris Brown', 'chris.brown@abu.edu.ng', '12349', 'Commerce', 'Accounting', 1, 'voter'),
(17, 'Aminu Bello', 'aminubello@abu.edu.ng', 'STU001', 'Faculty of Science', 'Computer Science', '100', 2, 'voter'),
(18, 'Fatima Abdullahi', 'fatimaabdullahi@abu.edu.ng', 'STU002', 'Faculty of Arts', 'English', '200', 2, 'voter'),
(19, 'Sani Ibrahim', 'saniibrahim@abu.edu.ng', 'STU003', 'Faculty of Engineering', 'Civil Engineering', '300', 2, 'voter'),
(20, 'Maryam Usman', 'maryamusman@abu.edu.ng', 'STU004', 'Faculty of Social Sciences', 'Political Science', '400', 2, 'voter'),
(21, 'Musa Mohammed', 'musamohammed@abu.edu.ng', 'STU005', 'Faculty of Agriculture', 'Agricultural Economics', '500', 2, 'voter'),
(22, 'hajiya', 'haj@gmail.com', 'STU109', 'Engineering ', 'Computer Engineering ', '100', 2, 'voter'),
(23, 'Yakubu Abdullahi', 'yaks@abu.edu.ng', '12345', 'Engineering', 'Computer Science', '500', 1, 'voter'),
(24, 'khadijatu Musa', 'khady@abu.edu.ng', '12346', 'Arts', 'English', '500', 1, 'voter'),
(25, 'Michael Emeka', 'johnson@abu.edu.ng', '12347', 'Science', 'Biology', '300', 1, 'voter'),
(27, '14', 'Brown Yellow', 'brown@abu.edu.ng', '12349', 'Commerce', 'Accounting', 1, 'voter');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voter_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `office_id` int(11) DEFAULT NULL,
  `election_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `voter_id` (`voter_id`),
  KEY `candidate_id` (`candidate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `voter_id`, `candidate_id`, `office_id`, `election_id`) VALUES
(1, 6, 2, 1, NULL),
(2, 6, 5, 2, NULL),
(3, 6, 1, 1, NULL),
(4, 6, 4, 2, NULL),
(5, 6, 1, 1, 1),
(6, 6, 4, 2, 1),
(7, 10, 1, 1, 1),
(8, 10, 4, 2, 1),
(9, 9, 2, 1, 1),
(10, 9, 3, 2, 1),
(11, 11, 2, 1, 1),
(12, 11, 4, 2, 1),
(13, 5, 2, 1, 1),
(14, 5, 3, 2, 1),
(15, 2, 2, 1, 1),
(16, 2, 4, 2, 1),
(17, 23, 1, 1, 1),
(18, 23, 3, 2, 1),
(19, 24, 1, 1, 1),
(20, 24, 3, 2, 1),
(21, 25, 1, 1, 1),
(22, 25, 3, 2, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`),
  ADD CONSTRAINT `fk_election` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_office` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`voter_id`) REFERENCES `voters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
