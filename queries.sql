-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Nov 15, 2019 at 08:55 AM
-- Server version: 8.0.18
-- PHP Version: 7.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `conferences`
--

CREATE TABLE `conferences` (
  `c_id` int(4) NOT NULL,
  `c_title` varchar(255) NOT NULL,
  `c_year` varchar(4) NOT NULL,
  `c_volume` varchar(10) DEFAULT NULL,
  `c_issue` varchar(15) DEFAULT NULL,
  `c_pages` varchar(15) DEFAULT NULL,
  `c_doi` varchar(15) DEFAULT NULL,
  `c_country` varchar(20) DEFAULT NULL,
  `c_city` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `conferences`
--

INSERT INTO `conferences` (`c_id`, `c_title`, `c_year`, `c_volume`, `c_issue`, `c_pages`, `c_doi`, `c_country`, `c_city`) VALUES
(1, 'Conftest1', '2010', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Conftest2', '2010', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Conftest3', '2010', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Conftest4', '2010', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Conftest5', '2012', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Conftest6', '2013', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'Conftest7', '2014', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Conftest8', '2012', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Conftest9', '2018', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Conftest10', '2019', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'testconf1', '2012', '', '', '', '', '', ''),
(25, 'testconf3', '2018', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `f_id` int(4) NOT NULL,
  `f_regid` varchar(10) NOT NULL,
  `f_webmail` varchar(40) NOT NULL,
  `f_weblink` varchar(30) DEFAULT NULL,
  `f_name` varchar(30) NOT NULL,
  `f_dept` varchar(6) NOT NULL,
  `f_phone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`f_id`, `f_regid`, `f_webmail`, `f_weblink`, `f_name`, `f_dept`, `f_phone`) VALUES
(1, '1000', 'test@iitp.ac.in', NULL, 'testname', 'tstdep', '9191919191'),
(4, '1001', 'test1@iitp.ac.in', NULL, 'testname1', 'tstdep', '9191919191'),
(5, '1002', 'test2@iitp.ac.in', NULL, 'testname2', 'ttt', '9090909090'),
(8, '1003', 'test3@iitp.ac.in', NULL, 'sdjklf;a', 'jjjk', '11'),
(9, '1004', 'test4@iitp.ac.in', NULL, 'testname4', 'cs', '1234567890'),
(10, '1005', 'test5@iitp.ac.in', NULL, 'testuser5', 'me', '9090909091'),
(11, '1006', 'test6@iitp.ac.in', NULL, 'testuser6', 'cs', '9090909092'),
(12, '1007', 'test7@iitp.ac.in', NULL, 'testuser6', 'cs', '6'),
(14, '1008', 'test8@iitp.ac.in', NULL, 'testuser8', 'cs', '6'),
(15, '1009', 'test9@iitp.ac.in', NULL, 'testuser9', 'cs', '6'),
(16, '2000', 'test00@iitp.ac.in', NULL, 'testuser00', 'cs', '6'),
(17, '100', 'test100@iitp.ac.in', NULL, 'testuser100', 'cs', '10000'),
(18, '101', 'test101@iitp.ac.in', NULL, 'testuser101', 'me', '10001'),
(19, '102', 'test102@iitp.ac.in', NULL, 'testuser102', 'ee', '10002'),
(20, '103', 'test103@iitp.ac.in', NULL, 'testuser103', 'ce', '10003'),
(21, '104', 'test104@iitp.ac.in', NULL, 'testuser104', 'cs', '10004'),
(22, '105', 'test105@iitp.ac.in', NULL, 'testuser105', 'me', '10005'),
(23, '106', 'test106@iitp.ac.in', NULL, 'testuser106', 'ee', '10006'),
(24, '107', 'test107@iitp.ac.in', NULL, 'testuser107', 'ce', '10007'),
(25, '108', 'test108@iitp.ac.in', NULL, 'testuser108', 'me', '10008'),
(26, '109', 'test109@iitp.ac.in', NULL, 'testuser109', 'me', '10009');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_project`
--

CREATE TABLE `faculty_project` (
  `fp_id` int(4) NOT NULL,
  `fp_fid` int(4) NOT NULL,
  `fp_pid` int(4) NOT NULL,
  `fp_position` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `faculty_project`
--

INSERT INTO `faculty_project` (`fp_id`, `fp_fid`, `fp_pid`, `fp_position`) VALUES
(1, 12, 4, 'Co-supervisor'),
(2, 12, 5, 'Supervisor'),
(3, 19, 6, 'Co-supervisor'),
(4, 12, 7, 'Co-supervisor'),
(6, 10, 7, 'Supervisor'),
(7, 10, 6, 'Supervisor'),
(8, 10, 9, 'Supervisor');

-- --------------------------------------------------------

--
-- Table structure for table `fac_publication`
--

CREATE TABLE `fac_publication` (
  `fac_pid` int(4) NOT NULL,
  `fac_fid` int(4) NOT NULL,
  `fac_jid` int(4) DEFAULT NULL,
  `fac_issn` varchar(30) DEFAULT NULL,
  `fac_cid` int(4) DEFAULT NULL,
  `fac_field` varchar(80) NOT NULL,
  `fac_rank` int(1) NOT NULL,
  `fac_publisher` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `fac_publication`
--

INSERT INTO `fac_publication` (`fac_pid`, `fac_fid`, `fac_jid`, `fac_issn`, `fac_cid`, `fac_field`, `fac_rank`, `fac_publisher`) VALUES
(1, 1, 1, NULL, NULL, 'CSE', 3, 'IEEE'),
(2, 18, 2, NULL, NULL, 'EE', 4, 'XEEE'),
(3, 19, 3, NULL, NULL, 'CSE', 2, 'FEEE'),
(4, 20, 4, NULL, NULL, 'CSE', 2, 'IEEE'),
(5, 21, 8, NULL, NULL, 'CSE', 1, 'FEEE'),
(6, 24, 6, NULL, NULL, 'CSE', 2, 'IEEE'),
(7, 24, 7, NULL, NULL, 'CE', 1, 'IEEE'),
(8, 24, 10, NULL, NULL, 'CE', 1, 'Nature'),
(9, 24, 5, NULL, NULL, 'CE', 1, 'IEEE'),
(15, 24, NULL, NULL, 11, 'CE', 2, 'IEEE'),
(16, 24, NULL, NULL, 10, 'EE', 2, 'IEEE'),
(17, 24, NULL, NULL, 7, 'CSE', 2, 'IEEE'),
(18, 24, NULL, NULL, 9, 'ME', 2, 'IEEE'),
(19, 23, NULL, NULL, 1, 'CE', 2, 'IEEE'),
(20, 22, NULL, NULL, 3, 'CE', 2, 'IEEE'),
(21, 21, NULL, NULL, 4, 'CE', 2, 'IEEE'),
(22, 20, NULL, NULL, 5, 'CE', 2, 'IEEE'),
(23, 19, NULL, NULL, 8, 'CE', 2, 'IEEE'),
(24, 18, NULL, NULL, 6, 'CE', 2, 'IEEE'),
(33, 12, 31, '', NULL, 'ME', 2, 'testusingphp1'),
(34, 12, 32, '', NULL, 'EE', 2, 'testusingphp1'),
(35, 12, 33, '', NULL, 'HS', 2, 'testusingphp1'),
(39, 12, 37, '', NULL, 'CSE', 2, 'testusingphp1'),
(40, 12, 38, '', NULL, 'EE', 2, 'testusingphp1'),
(46, 12, NULL, '', 24, 'ME', 2, 'testusingphp1'),
(47, 12, 39, '', NULL, 'EE', 2, 'testusingphp1'),
(48, 12, 40, '', NULL, 'MSE', 4, 'testusingphp1'),
(53, 12, 45, '', NULL, 'HS', 1, 'testusingphp1'),
(55, 12, NULL, '', 25, 'ME', 4, 'testusingphp2'),
(57, 12, 47, '', NULL, 'ME', 4, 'testusingphp107'),
(58, 10, 47, '', NULL, 'CSE', 2, 'testusingphp107'),
(59, 10, 48, '', NULL, 'CSE', 1, 'XEEE'),
(60, 10, NULL, '', 25, 'ME', 2, 'testusingphp2');

-- --------------------------------------------------------

--
-- Table structure for table `journals`
--

CREATE TABLE `journals` (
  `j_id` int(4) NOT NULL,
  `j_title` varchar(255) NOT NULL,
  `j_year` varchar(4) NOT NULL,
  `j_volume` varchar(10) DEFAULT NULL,
  `j_issue` varchar(15) DEFAULT NULL,
  `j_pages` varchar(15) DEFAULT NULL,
  `j_doi` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `journals`
--

INSERT INTO `journals` (`j_id`, `j_title`, `j_year`, `j_volume`, `j_issue`, `j_pages`, `j_doi`) VALUES
(1, 'Journaltest1', '2010', NULL, NULL, NULL, NULL),
(2, 'Journaltest2', '2010', NULL, NULL, NULL, NULL),
(3, 'Journaltest3', '2010', NULL, NULL, NULL, NULL),
(4, 'Journaltest4', '2011', NULL, NULL, NULL, NULL),
(5, 'Journaltest5', '2012', NULL, NULL, NULL, NULL),
(6, 'Journaltest6', '2012', NULL, NULL, NULL, NULL),
(7, 'Journaltest7', '2012', NULL, NULL, NULL, NULL),
(8, 'Journaltest8', '2016', NULL, NULL, NULL, NULL),
(9, 'Journaltest9', '2018', NULL, NULL, NULL, NULL),
(10, 'Journaltest10', '2019', NULL, NULL, NULL, NULL),
(31, 'testingph1', '2012', '', '', '', ''),
(32, 'testing1', '2017', '', '', '', ''),
(33, 'testingp2', '2013', '', '', '', ''),
(37, 'testing3', '2017', '', '', '', ''),
(38, 'testing4', '2012', '', '', '', ''),
(39, 'testing7', '2012', '', '', '', ''),
(40, 'testing71', '2013', '', '', '', ''),
(45, 'testing6', '2012', '', '', '', ''),
(47, 'testingphp107', '2015', '', '', '', ''),
(48, 'testingphp1005', '2018', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `l_id` int(3) NOT NULL,
  `l_webmail` varchar(40) NOT NULL,
  `l_username` varchar(20) NOT NULL,
  `l_password` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`l_id`, `l_webmail`, `l_username`, `l_password`) VALUES
(1, 'test@iitp.ac.in', 'testusername', 'testpass'),
(3, 'test1@iitp.ac.in', 'newtestuser11', 'newtestpass11'),
(5, 'test2@iitp.ac.in', 'newtestuser12', 'newtestpass12'),
(6, 'test3@iitp.ac.in', 'testuser3', 'testpass3'),
(7, 'test4@iitp.ac.in', 'testuser4', 'testpass4'),
(8, 'test5@iitp.ac.in', 'testuser5', 'testpass5'),
(9, 'test6@iitp.ac.in', 'testuser6', 'testpass6'),
(10, 'test7@iitp.ac.in', 'testuser70', 'testpass7'),
(11, 'test8@iitp.ac.in', 'testuser8', 'testpass8'),
(12, 'test9@iitp.ac.in', 'testuser9', 'testpass9'),
(13, 'test00@iitp.ac.in', 'testuser10', 'testpass10'),
(25, 'test100@iitp.ac.in', 'testuser100', 'testpass100'),
(26, 'test101@iitp.ac.in', 'testuser101', 'testpass101'),
(27, 'test102@iitp.ac.in', 'testuser102', 'testpass102'),
(28, 'test109@iitp.ac.in', 'testuser109', 'testpass109'),
(29, 'test108@iitp.ac.in', 'testuse100', 'f');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `p_id` int(4) NOT NULL,
  `p_title` varchar(100) NOT NULL,
  `p_budget` varchar(10) DEFAULT NULL,
  `p_duration` varchar(10) DEFAULT NULL,
  `p_sponsor` varchar(50) NOT NULL,
  `p_status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`p_id`, `p_title`, `p_budget`, `p_duration`, `p_sponsor`, `p_status`) VALUES
(4, 'test', '', '', 'test', 'Ongoing'),
(5, 'test2', '', '', 'test', 'Ongoing'),
(6, 'test102', '', '', 'testspons102', 'Ongoing'),
(7, 'stest', '', '', 'sspons', 'Completed'),
(9, 'test105', '20000', '2018-2021', 'sponstest', 'Ongoing');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conferences`
--
ALTER TABLE `conferences`
  ADD PRIMARY KEY (`c_id`),
  ADD UNIQUE KEY `c_title` (`c_title`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`f_id`),
  ADD UNIQUE KEY `f_regid` (`f_regid`),
  ADD UNIQUE KEY `f_webmail` (`f_webmail`);

--
-- Indexes for table `faculty_project`
--
ALTER TABLE `faculty_project`
  ADD PRIMARY KEY (`fp_id`),
  ADD UNIQUE KEY `fp_fid` (`fp_fid`,`fp_pid`),
  ADD KEY `faculty_project_fk1` (`fp_pid`);

--
-- Indexes for table `fac_publication`
--
ALTER TABLE `fac_publication`
  ADD PRIMARY KEY (`fac_pid`),
  ADD UNIQUE KEY `unique_fac_pro` (`fac_fid`,`fac_cid`),
  ADD UNIQUE KEY `unique_fac_jid` (`fac_fid`,`fac_jid`),
  ADD KEY `fac_publication_fk1` (`fac_jid`),
  ADD KEY `fac_publication_fk2` (`fac_cid`);

--
-- Indexes for table `journals`
--
ALTER TABLE `journals`
  ADD PRIMARY KEY (`j_id`),
  ADD UNIQUE KEY `j_title` (`j_title`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`l_id`),
  ADD UNIQUE KEY `l_webmail` (`l_webmail`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`p_id`),
  ADD UNIQUE KEY `p_title` (`p_title`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conferences`
--
ALTER TABLE `conferences`
  MODIFY `c_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `f_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `faculty_project`
--
ALTER TABLE `faculty_project`
  MODIFY `fp_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `fac_publication`
--
ALTER TABLE `fac_publication`
  MODIFY `fac_pid` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `journals`
--
ALTER TABLE `journals`
  MODIFY `j_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `l_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `p_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `faculty_project`
--
ALTER TABLE `faculty_project`
  ADD CONSTRAINT `faculty_project_fk0` FOREIGN KEY (`fp_fid`) REFERENCES `faculty` (`f_id`),
  ADD CONSTRAINT `faculty_project_fk1` FOREIGN KEY (`fp_pid`) REFERENCES `projects` (`p_id`);

--
-- Constraints for table `fac_publication`
--
ALTER TABLE `fac_publication`
  ADD CONSTRAINT `fac_publication_fk0` FOREIGN KEY (`fac_fid`) REFERENCES `faculty` (`f_id`),
  ADD CONSTRAINT `fac_publication_fk1` FOREIGN KEY (`fac_jid`) REFERENCES `journals` (`j_id`),
  ADD CONSTRAINT `fac_publication_fk2` FOREIGN KEY (`fac_cid`) REFERENCES `conferences` (`c_id`);

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_fk0` FOREIGN KEY (`l_webmail`) REFERENCES `faculty` (`f_webmail`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
