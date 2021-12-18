-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 03, 2018 at 12:30 PM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `medisense_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `webtemplate3_details`
--

DROP TABLE IF EXISTS `webtemplate3_details`;
CREATE TABLE IF NOT EXISTS `webtemplate3_details` (
  `webtemplate3_deatil_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `webtemplate_id` bigint(11) NOT NULL,
  `doc_id` bigint(11) NOT NULL,
  `doc_type` int(3) NOT NULL,
  `home_name` varchar(255) NOT NULL,
  `home_designation` varchar(255) NOT NULL,
  `home_company` varchar(255) NOT NULL,
  `home_place` varchar(255) NOT NULL,
  `about_name` varchar(255) NOT NULL,
  `about_specialization` varchar(255) NOT NULL,
  `about_address` varchar(255) NOT NULL,
  `about_experience` varchar(50) NOT NULL,
  `about_info` text NOT NULL,
  `about_img` varchar(255) NOT NULL,
  `skills_edu_year1` varchar(100) NOT NULL,
  `skills_edu_stream1` varchar(255) NOT NULL,
  `skills_edu_description1` text NOT NULL,
  `skills_edu_year2` varchar(100) NOT NULL,
  `skills_edu_stream2` varchar(255) NOT NULL,
  `skills_edu_description2` text NOT NULL,
  `skills_edu_year3` varchar(100) NOT NULL,
  `skills_edu_stream3` varchar(255) NOT NULL,
  `skills_edu_description3` text NOT NULL,
  `skills_exp_year1` varchar(100) NOT NULL,
  `skills_exp_designtaion1` varchar(255) NOT NULL,
  `skills_exp_description1` text NOT NULL,
  `skills_exp_year2` varchar(100) NOT NULL,
  `skills_exp_designtaion2` varchar(255) NOT NULL,
  `skills_exp_description2` text NOT NULL,
  `skills_exp_year3` varchar(100) NOT NULL,
  `skills_exp_designtaion3` varchar(255) NOT NULL,
  `skills_exp_description3` text NOT NULL,
  `skills_rating_title1` varchar(255) NOT NULL,
  `skills_rating_value1` int(3) NOT NULL,
  `skills_rating_title2` varchar(255) NOT NULL,
  `skills_rating_value2` int(3) NOT NULL,
  `skills_rating_title3` varchar(255) NOT NULL,
  `skills_rating_value3` int(3) NOT NULL,
  `skills_rating_title4` varchar(255) NOT NULL,
  `skills_rating_value4` int(3) NOT NULL,
  `skills_rating_title5` varchar(255) NOT NULL,
  `skills_rating_value5` int(3) NOT NULL,
  `skills_rating_title6` varchar(255) NOT NULL,
  `skills_rating_value6` int(3) NOT NULL,
  `service_title1` varchar(255) NOT NULL,
  `service_description1` text NOT NULL,
  `service_title2` varchar(255) NOT NULL,
  `service_description2` text NOT NULL,
  `service_title3` varchar(255) NOT NULL,
  `service_description3` text NOT NULL,
  `service_title4` varchar(255) NOT NULL,
  `service_description4` text NOT NULL,
  `service_title5` varchar(255) NOT NULL,
  `service_description5` text NOT NULL,
  `service_title6` varchar(255) NOT NULL,
  `service_description6` text NOT NULL,
  `service_value1` varchar(255) NOT NULL,
  `service_text1` varchar(255) NOT NULL,
  `service_value2` varchar(255) NOT NULL,
  `service_text2` varchar(255) NOT NULL,
  `service_value3` varchar(255) NOT NULL,
  `service_text3` varchar(255) NOT NULL,
  `service_value4` varchar(255) NOT NULL,
  `service_text4` varchar(255) NOT NULL,
  `project_title1` varchar(500) NOT NULL,
  `project_img1` varchar(255) NOT NULL,
  `project_description1` text NOT NULL,
  `project_title2` varchar(500) NOT NULL,
  `project_img2` varchar(255) NOT NULL,
  `project_description2` text NOT NULL,
  `project_title3` varchar(500) NOT NULL,
  `project_img3` varchar(255) NOT NULL,
  `project_description3` text NOT NULL,
  `project_title4` varchar(500) NOT NULL,
  `project_img4` varchar(255) NOT NULL,
  `project_description4` text NOT NULL,
  `project_title5` varchar(500) NOT NULL,
  `project_img5` varchar(255) NOT NULL,
  `project_description5` text NOT NULL,
  `project_title6` varchar(500) NOT NULL,
  `project_img6` varchar(255) NOT NULL,
  `project_description6` text NOT NULL,
  `project_title7` varchar(500) NOT NULL,
  `project_img7` varchar(255) NOT NULL,
  `project_description7` text NOT NULL,
  `project_title8` varchar(500) NOT NULL,
  `project_img8` varchar(255) NOT NULL,
  `project_description8` text NOT NULL,
  `project_title9` varchar(500) NOT NULL,
  `project_img9` varchar(255) NOT NULL,
  `project_description9` text NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `contact_address_info` text NOT NULL,
  `contact_working_hours` varchar(255) NOT NULL,
  PRIMARY KEY (`webtemplate3_deatil_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
