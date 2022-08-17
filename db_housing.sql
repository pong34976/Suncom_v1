-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2021 at 04:03 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_housing`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_housing`
--

CREATE TABLE `tb_housing` (
  `id` int(255) NOT NULL,
  `noroom` varchar(10) NOT NULL,
  `housingrow` enum('warrant','commission') NOT NULL,
  `status` enum('1','2','3','4') NOT NULL COMMENT '''people'',''free'',''maintain'',''off'''
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `tb_housing`
--

INSERT INTO `tb_housing` (`id`, `noroom`, `housingrow`, `status`) VALUES
(2, '1280/4/1', 'warrant', '4');

-- --------------------------------------------------------

--
-- Table structure for table `tb_login`
--

CREATE TABLE `tb_login` (
  `log_id` int(3) NOT NULL,
  `log_level` int(2) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `prefix` varchar(50) NOT NULL,
  `rname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `birthdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `tb_login`
--

INSERT INTO `tb_login` (`log_id`, `log_level`, `username`, `password`, `prefix`, `rname`, `lname`, `birthdate`) VALUES
(2, 1, 'admin', 'admin', 'นาย', 'admin', 'admin', '2530-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `tb_permission`
--

CREATE TABLE `tb_permission` (
  `id` int(2) NOT NULL,
  `pmiss` varchar(100) NOT NULL,
  `showname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `tb_permission`
--

INSERT INTO `tb_permission` (`id`, `pmiss`, `showname`) VALUES
(1, 'ADMIN', 'ผู้ดูแลระบบ'),
(2, 'MASTER', 'หัวหน้างาน'),
(3, 'USER', 'ผู้บันทึกข้อมูล');

-- --------------------------------------------------------

--
-- Table structure for table `tb_phones`
--

CREATE TABLE `tb_phones` (
  `id` int(11) NOT NULL,
  `number` varchar(20) NOT NULL,
  `room` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(3) NOT NULL,
  `idcard` varchar(13) NOT NULL,
  `prefix` varchar(20) NOT NULL,
  `rname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `sex` enum('ชาย','หญิง') NOT NULL,
  `affiliation` varchar(100) NOT NULL,
  `birthdate` date NOT NULL,
  `address` text NOT NULL,
  `email` varchar(120) NOT NULL,
  `phones` varchar(11) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `idcard`, `prefix`, `rname`, `lname`, `sex`, `affiliation`, `birthdate`, `address`, `email`, `phones`, `img`) VALUES
(1, '1231231232312', '123', '123', '123', 'ชาย', '123', '2530-01-01', '213213', '123@gmail.com', '233123', 'nonm.jpg'),
(5, '12333123123', '123', '123', '123', 'ชาย', 'sdas', '2530-01-01', '2231321', 'kk_2kk129@hotmail.com', '+6600010101', '12333123123.jpg'),
(10, '12132123', '12312', '312', '123', 'หญิง', '123', '2537-04-25', '123123', '123213@gmail.com', '123312', '12132123.jpg'),
(18, '123213123', '123', '123', '123', 'ชาย', 'ศูนย์คอม', '2530-01-01', '2231321', 'pong34976@hotmail.com', '668296429', 'nonm.jpg'),
(19, '1111111111111', '213', '123', '123', 'หญิง', 'ddd', '2530-01-01', '123123', 'kk_kk129@hotmail.com', '6600010101', 'nons.jpg'),
(20, '123123123', 'sss', 'sss', 'sss', 'ชาย', '221312', '2530-01-01', '1231321', '11@gmail.com', '123123', 'nonm.jpg'),
(22, '123123123123', '12312', '313', '123123', 'ชาย', '123123', '2530-01-01', '12312312', 'kk_kkk129@hotmail.com', '1111', 'nonm.jpg'),
(25, '123123121', 'ร้อยตรี', 'ชัยณรง', 'โพธิ์มาย', 'ชาย', 'ม ท บ 1 3.', '2530-01-01', '123123\r\nads\r\n\r\nads\r\nads\r\nads\r\n\r\nad\r\nasasdasd\r\nsda\r\nsad\r\nadsasd\r\nd\r\nsdasdaasaddsadads', '123123@gmail.com', '0655682334', 'nonm.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_housing`
--
ALTER TABLE `tb_housing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `noroom` (`noroom`);

--
-- Indexes for table `tb_login`
--
ALTER TABLE `tb_login`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `tb_permission`
--
ALTER TABLE `tb_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_phones`
--
ALTER TABLE `tb_phones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phones` (`phones`),
  ADD KEY `rname` (`rname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_housing`
--
ALTER TABLE `tb_housing`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_login`
--
ALTER TABLE `tb_login`
  MODIFY `log_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_permission`
--
ALTER TABLE `tb_permission`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_phones`
--
ALTER TABLE `tb_phones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
