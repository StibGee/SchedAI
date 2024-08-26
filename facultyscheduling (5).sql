-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2024 at 06:33 PM
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
-- Database: `facultyscheduling`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE `calendar` (
  `id` int(11) NOT NULL,
  `sem` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calendar`
--

INSERT INTO `calendar` (`id`, `sem`, `year`, `name`) VALUES
(5, 1, 2023, '2023-2024'),
(6, 2, 2023, '2023-2024');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`) VALUES
(1, 'Computer Science'),
(2, 'Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `facultyid` varchar(255) DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `bday` date DEFAULT NULL,
  `contactno` text DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `masters` varchar(255) DEFAULT NULL,
  `phd` varchar(255) DEFAULT NULL,
  `departmentid` int(11) DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `teachinghours` decimal(10,0) DEFAULT NULL,
  `remainingteachinghours` decimal(10,1) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT 'faculty',
  `rank` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `facultyid`, `fname`, `mname`, `lname`, `gender`, `bday`, `contactno`, `type`, `masters`, `phd`, `departmentid`, `startdate`, `teachinghours`, `remainingteachinghours`, `username`, `password`, `role`, `rank`) VALUES
(1, '2020-00712', 'Gadmar', 'A', 'Belamide', 'Male', '2020-07-08', '09193162291', 'Regular', 'No', 'No', 1, '2002-09-09', 20, 17.0, 'gadmar123', '$2y$10$jKDkS2ttP2wdJY0dR6d2pu3xNHfSriV8Wv/5YbfyBXDWpV5bUDg5W', 'Admin', NULL),
(2, '2020-00321', 'test1', 'Odsd', 'Jose', 'Male', '1978-08-13', '091932632333', 'contractual', 'Yes', 'yes', 2, '2024-08-21', 10, 3.0, 'rhezana2121', '$2y$10$otKvReIOYjT35Rdxf2x3RuRoOR7/BPuLbj8NE3BqwJxIlggmWaJHa', 'faculty', 'phd');

-- --------------------------------------------------------

--
-- Table structure for table `facultysubject`
--

CREATE TABLE `facultysubject` (
  `id` int(11) NOT NULL,
  `facultyid` int(11) DEFAULT NULL,
  `subjectid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facultysubject`
--

INSERT INTO `facultysubject` (`id`, `facultyid`, `subjectid`) VALUES
(1, 1, 1),
(2, 1, 4),
(3, 1, 5),
(4, 2, 4),
(5, 2, 3),
(6, 2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `timestart` varchar(255) DEFAULT NULL,
  `timeend` varchar(255) DEFAULT NULL,
  `departmentid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `name`, `type`, `timestart`, `timeend`, `departmentid`) VALUES
(1, 'LR1', 'lab', '07:00', '19:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `subjectcode` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `unit` decimal(11,0) DEFAULT NULL,
  `hours` decimal(10,1) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `masters` varchar(255) DEFAULT NULL,
  `departmentid` int(11) DEFAULT NULL,
  `focus` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `subjectcode`, `name`, `unit`, `hours`, `type`, `masters`, `departmentid`, `focus`) VALUES
(1, 'BScs1', 'leconly', 3, 3.0, 'lec', 'No', 1, 'Major'),
(2, 'BScs2', 'leclab', 2, 2.0, 'lec', 'No', 1, 'Major'),
(3, 'BScs2', 'leclab', 1, 3.0, 'lab', 'No', 1, 'Major'),
(4, 'sbsvcdsf', 'lec', 2, 2.0, 'lec', 'Yes', 1, 'Major'),
(5, 'sdjns', 'leconly222', 2, 2.0, 'lec', 'Yes', 2, 'Minor'),
(6, 'dffer5e54', 'dfgfdgfdgfdgfd', 2, 2.0, 'lec', 'No', 1, 'Major'),
(7, 'bsscsc', 'leclab2', 2, 2.0, 'lec', 'Yes', 1, 'Major'),
(8, 'bsscsc', 'leclab2', 1, 3.0, 'lab', 'Yes', 1, 'Major'),
(9, 'sdfsds', 'leclab3', 2, 2.0, 'lec', 'Yes', 1, 'Major'),
(10, 'sdfsds', 'leclab3', 1, 3.0, 'lab', 'Yes', 1, 'Major'),
(11, 'cs125', 'leclab4', 2, 2.0, 'lec', 'Yes', 1, 'Major'),
(12, 'cs125', 'leclab4', 1, 3.0, 'lab', 'Yes', 1, 'Major');

-- --------------------------------------------------------

--
-- Table structure for table `subjectschedule`
--

CREATE TABLE `subjectschedule` (
  `id` int(11) NOT NULL,
  `subjectid` int(11) DEFAULT NULL,
  `calendarid` int(11) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `facultyid` int(11) DEFAULT NULL,
  `timestart` varchar(255) DEFAULT NULL,
  `timeend` varchar(255) DEFAULT NULL,
  `day` varchar(255) DEFAULT NULL,
  `roomid` int(11) DEFAULT NULL,
  `departmentid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjectschedule`
--

INSERT INTO `subjectschedule` (`id`, `subjectid`, `calendarid`, `section`, `facultyid`, `timestart`, `timeend`, `day`, `roomid`, `departmentid`) VALUES
(3, 1, 5, 'A', 1, NULL, NULL, NULL, NULL, 1),
(4, 4, 5, 'A', 2, NULL, NULL, NULL, NULL, 1),
(5, 3, 5, 'A', 2, NULL, NULL, NULL, NULL, 1),
(6, 6, 5, 'A', 2, NULL, NULL, NULL, NULL, 1),
(7, 7, 5, 'A', NULL, NULL, NULL, NULL, NULL, 1),
(8, 8, 5, 'A', NULL, NULL, NULL, NULL, NULL, 1),
(11, 4, 5, 'B', 2, NULL, NULL, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departmentid` (`departmentid`);

--
-- Indexes for table `facultysubject`
--
ALTER TABLE `facultysubject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facultyid` (`facultyid`),
  ADD KEY `subjectid` (`subjectid`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departmentid` (`departmentid`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departmentid` (`departmentid`);

--
-- Indexes for table `subjectschedule`
--
ALTER TABLE `subjectschedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subjectid` (`subjectid`),
  ADD KEY `calendarid` (`calendarid`),
  ADD KEY `facultyid` (`facultyid`),
  ADD KEY `roomid` (`roomid`),
  ADD KEY `departmentid` (`departmentid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar`
--
ALTER TABLE `calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `facultysubject`
--
ALTER TABLE `facultysubject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subjectschedule`
--
ALTER TABLE `subjectschedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`departmentid`) REFERENCES `department` (`id`);

--
-- Constraints for table `facultysubject`
--
ALTER TABLE `facultysubject`
  ADD CONSTRAINT `facultysubject_ibfk_1` FOREIGN KEY (`facultyid`) REFERENCES `faculty` (`id`),
  ADD CONSTRAINT `facultysubject_ibfk_2` FOREIGN KEY (`subjectid`) REFERENCES `subject` (`id`);

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`departmentid`) REFERENCES `department` (`id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_ibfk_1` FOREIGN KEY (`departmentid`) REFERENCES `department` (`id`);

--
-- Constraints for table `subjectschedule`
--
ALTER TABLE `subjectschedule`
  ADD CONSTRAINT `subjectschedule_ibfk_1` FOREIGN KEY (`subjectid`) REFERENCES `subject` (`id`),
  ADD CONSTRAINT `subjectschedule_ibfk_2` FOREIGN KEY (`calendarid`) REFERENCES `calendar` (`id`),
  ADD CONSTRAINT `subjectschedule_ibfk_3` FOREIGN KEY (`facultyid`) REFERENCES `faculty` (`id`),
  ADD CONSTRAINT `subjectschedule_ibfk_4` FOREIGN KEY (`roomid`) REFERENCES `room` (`id`),
  ADD CONSTRAINT `subjectschedule_ibfk_5` FOREIGN KEY (`departmentid`) REFERENCES `department` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
