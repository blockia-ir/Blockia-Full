-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2021 at 02:13 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exam_77`
--

-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_class`
--

CREATE TABLE `_tablePrefix_class` (
  `id` int(11) NOT NULL,
  `block_user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_class_student`
--

CREATE TABLE `_tablePrefix_class_student` (
  `id` int(11) NOT NULL,
  `block_user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `code` char(20) CHARACTER SET utf8mb4 DEFAULT NULL,
  `date_create` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_exam`
--

CREATE TABLE `_tablePrefix_exam` (
  `id` int(11) NOT NULL,
  `block_user_id` int(11) NOT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `class` text NOT NULL,
  `duration` tinyint(3) UNSIGNED NOT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `base_mark` tinyint(4) DEFAULT 20,
  `negative` tinyint(1) DEFAULT 0,
  `show_question_type` tinyint(4) DEFAULT 0,
  `show_list_mark` tinyint(4) DEFAULT 21,
  `show_answer` tinyint(4) DEFAULT 0,
  `number_question` tinyint(1) DEFAULT 0,
  `private` tinyint(1) DEFAULT 0,
  `check_ip` tinyint(4) DEFAULT 0,
  `check_cookie` tinyint(1) NOT NULL DEFAULT 1,
  `dir` tinyint(1) DEFAULT 0,
  `date_create` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_exam_class`
--

CREATE TABLE `_tablePrefix_exam_class` (
  `id` int(11) NOT NULL,
  `block_user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_exam_question`
--

CREATE TABLE `_tablePrefix_exam_question` (
  `id` int(11) NOT NULL,
  `block_user_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `question_type_id` tinyint(4) DEFAULT NULL,
  `question` text NOT NULL,
  `a` text DEFAULT NULL,
  `b` text DEFAULT NULL,
  `c` text DEFAULT NULL,
  `d` text DEFAULT NULL,
  `answer` char(1) NOT NULL,
  `score` decimal(10,2) DEFAULT NULL,
  `ordr` tinyint(3) UNSIGNED DEFAULT NULL,
  `date_create` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_exam_question_type`
--

CREATE TABLE `_tablePrefix_exam_question_type` (
  `id` int(1) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

INSERT INTO `_tablePrefix_exam_question_type` (`id`, `name`) VALUES
(1, 'تستی'),
(2, 'صحیح و غلط'),
(5, 'تشریحی');
-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_exam_result`
--

CREATE TABLE `_tablePrefix_exam_result` (
  `id` int(11) NOT NULL,
  `block_user_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `exam_class_id` int(11) NOT NULL,
  `class_student_id` int(11) DEFAULT NULL,
  `student_name` char(50) NOT NULL,
  `exam_name` varchar(100) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `base_mark` tinyint(4) DEFAULT NULL,
  `number_question` tinyint(1) DEFAULT NULL,
  `negative` tinyint(1) DEFAULT NULL,
  `count_question` tinyint(4) DEFAULT NULL,
  `count_true` tinyint(4) DEFAULT NULL,
  `count_false` tinyint(4) DEFAULT NULL,
  `count_empty` tinyint(4) DEFAULT NULL,
  `percent` int(11) DEFAULT NULL,
  `mark` decimal(10,2) DEFAULT NULL,
  `ip` char(15) DEFAULT NULL,
  `marked` tinyint(1) DEFAULT NULL,
  `date_create` datetime NOT NULL,
  `date_finsh` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_logins`
--

CREATE TABLE `_tablePrefix_logins` (
  `block_user_id` int(11) NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `os` varchar(20) DEFAULT NULL,
  `browser` varchar(20) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_block_users`
--

CREATE TABLE `_tablePrefix_block_users` (
  `id` int(11) NOT NULL,
  `name` varchar(250) CHARACTER SET utf8mb4 DEFAULT NULL,
  `username` char(100) CHARACTER SET utf8mb4 NOT NULL,
  `password` char(128) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(250) CHARACTER SET utf8mb4 DEFAULT NULL,
  `mobile` varchar(15) CHARACTER SET utf8mb4 DEFAULT NULL,
  `admin` varchar(250) DEFAULT NULL,
  `date_create` datetime DEFAULT current_timestamp(),
  `block_user_id` int(11) DEFAULT NULL,
  `active_link_register` tinyint(4) DEFAULT 0,
  `class_link_register` varchar(255) DEFAULT NULL,
  `description_link_register` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_student_answer`
--

CREATE TABLE `_tablePrefix_student_answer` (
  `id` int(11) NOT NULL,
  `exam_result_id` int(11) NOT NULL,
  `exam_question_id` int(11) NOT NULL,
  `answer` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `score` decimal(10,2) DEFAULT NULL,
  `marked` tinyint(1) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_tablePrefix_upload_files`
--

CREATE TABLE `_tablePrefix_upload_files` (
  `id` int(11) NOT NULL,
  `block_user_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `date` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------


--
-- Indexes for dumped tables
--

--
-- Indexes for table `_tablePrefix_class`
--
ALTER TABLE `_tablePrefix_class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `block_user_id` (`block_user_id`);

--
-- Indexes for table `_tablePrefix_class_student`
--
ALTER TABLE `_tablePrefix_class_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`),
  ADD KEY `block_user_id` (`block_user_id`),
  ADD KEY `block_user_id_2` (`block_user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `_tablePrefix_exam`
--
ALTER TABLE `_tablePrefix_exam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `block_user_id` (`block_user_id`);

--
-- Indexes for table `_tablePrefix_exam_class`
--
ALTER TABLE `_tablePrefix_exam_class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `block_user_id` (`block_user_id`),
  ADD KEY `exam_id` (`exam_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `_tablePrefix_exam_question`
--
ALTER TABLE `_tablePrefix_exam_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `block_user_id` (`block_user_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `_tablePrefix_exam_question_type`
--
ALTER TABLE `_tablePrefix_exam_question_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_tablePrefix_exam_result`
--
ALTER TABLE `_tablePrefix_exam_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ip` (`ip`),
  ADD KEY `exam_class_id` (`exam_class_id`),
  ADD KEY `class_student_id` (`class_student_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `_tablePrefix_logins`
--
ALTER TABLE `_tablePrefix_logins`
  ADD KEY `block_user_id` (`block_user_id`);

--
-- Indexes for table `_tablePrefix_block_users`
--
ALTER TABLE `_tablePrefix_block_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `_tablePrefix_student_answer`
--
ALTER TABLE `_tablePrefix_student_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_result_id` (`exam_result_id`),
  ADD KEY `exam_question_id` (`exam_question_id`);

--
-- Indexes for table `_tablePrefix_upload_files`
--
ALTER TABLE `_tablePrefix_upload_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `block_user_id` (`block_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `_tablePrefix_class`
--
ALTER TABLE `_tablePrefix_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `_tablePrefix_class_student`
--
ALTER TABLE `_tablePrefix_class_student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `_tablePrefix_exam`
--
ALTER TABLE `_tablePrefix_exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `_tablePrefix_exam_class`
--
ALTER TABLE `_tablePrefix_exam_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `_tablePrefix_exam_question`
--
ALTER TABLE `_tablePrefix_exam_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `_tablePrefix_exam_question_type`
--
ALTER TABLE `_tablePrefix_exam_question_type`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `_tablePrefix_exam_result`
--
ALTER TABLE `_tablePrefix_exam_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `_tablePrefix_block_users`
--
ALTER TABLE `_tablePrefix_block_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `_tablePrefix_student_answer`
--
ALTER TABLE `_tablePrefix_student_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `_tablePrefix_upload_files`
--
ALTER TABLE `_tablePrefix_upload_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
