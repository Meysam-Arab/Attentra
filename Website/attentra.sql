-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 14, 2024 at 12:53 PM
-- Server version: 8.3.0
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `attentra`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

DROP TABLE IF EXISTS `about_us`;
CREATE TABLE IF NOT EXISTS `about_us` (
  `about_us_id` bigint NOT NULL AUTO_INCREMENT,
  `about_us_guid` varchar(36) NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `is_active` bit(1) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`about_us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `attendance_id` bigint NOT NULL AUTO_INCREMENT,
  `attendance_guid` varchar(36) NOT NULL,
  `user_company_id` bigint NOT NULL,
  `start_date_time` datetime NOT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `coordinates` varchar(50) DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '0',
  `is_mission` bit(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`attendance_id`),
  KEY `user_id` (`user_company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `company_id` bigint NOT NULL AUTO_INCREMENT,
  `company_guid` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `time_zone` varchar(50) DEFAULT NULL,
  `is_active` bit(1) NOT NULL,
  `zone` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `company_user_module`
--

DROP TABLE IF EXISTS `company_user_module`;
CREATE TABLE IF NOT EXISTS `company_user_module` (
  `company_user_module_id` bigint NOT NULL AUTO_INCREMENT,
  `company_user_module_guid` varchar(36) NOT NULL,
  `company_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `module_id` bigint DEFAULT NULL,
  `cost` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `limit_count` int DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_active` bit(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`company_user_module_id`),
  KEY `UserId` (`company_id`),
  KEY `ModuleId` (`module_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `country_id` bigint NOT NULL AUTO_INCREMENT,
  `country_guid` varchar(36) NOT NULL,
  `code` varchar(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  `capital` varchar(50) NOT NULL,
  `is_active` bit(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`country_id`, `country_guid`, `code`, `name`, `capital`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '58ef7b2e4e4e38.13666870', 'IRI', 'ایران', 'تهران', b'1', '2017-04-20 00:00:00', '2017-04-20 00:00:00', NULL),
(2, '58ef7b2e4e4e38.13666870', 'USA', 'USA', 'Washington', b'1', '2017-04-20 00:00:00', '2017-04-20 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
CREATE TABLE IF NOT EXISTS `currency` (
  `currency_id` bigint NOT NULL AUTO_INCREMENT,
  `currency_guid` varchar(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `is_active` bit(1) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `currency_code` varchar(3) NOT NULL,
  `country_id` bigint NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`currency_id`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currency_id`, `currency_guid`, `name`, `is_active`, `deleted_at`, `currency_code`, `country_id`, `created_at`, `updated_at`) VALUES
(1, '58ef7b2e4e4e38.23666870', 'ریال', b'1', NULL, 'IRR', 1, '2017-04-20 00:00:00', '2017-04-20 00:00:00'),
(2, '58ef7b2e4e4e33.13666870', '$', b'1', NULL, 'USD', 2, '2017-04-20 00:00:00', '2017-04-20 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

DROP TABLE IF EXISTS `download`;
CREATE TABLE IF NOT EXISTS `download` (
  `download_id` bigint NOT NULL AUTO_INCREMENT,
  `download_guid` varchar(36) NOT NULL,
  `extention` varchar(200) NOT NULL,
  `size` varchar(200) NOT NULL,
  `is_active` bit(1) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`download_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `feedback_id` bigint NOT NULL AUTO_INCREMENT,
  `feedback_guid` varchar(36) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`feedback_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
CREATE TABLE IF NOT EXISTS `language` (
  `language_id` bigint NOT NULL AUTO_INCREMENT,
  `language_guid` varchar(36) NOT NULL,
  `title` varchar(200) NOT NULL,
  `language_direction` bit(1) NOT NULL DEFAULT b'0',
  `code` varchar(5) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_id`, `language_guid`, `title`, `language_direction`, `code`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '589accdef1fc39.28622295', 'فارسی', b'1', 'fa', NULL, '2017-02-08 07:46:38', '2017-02-08 07:46:38'),
(2, '589acd04d53961.41332971', 'English', b'0', 'en', NULL, '2017-02-08 07:47:16', '2017-02-08 07:47:16');

-- --------------------------------------------------------

--
-- Table structure for table `log_event`
--

DROP TABLE IF EXISTS `log_event`;
CREATE TABLE IF NOT EXISTS `log_event` (
  `log_event_id` bigint NOT NULL AUTO_INCREMENT,
  `log_event_guid` varchar(23) NOT NULL,
  `user_id` bigint NOT NULL,
  `controller_and_action_name` varchar(500) NOT NULL,
  `error_message` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`log_event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `mission`
--

DROP TABLE IF EXISTS `mission`;
CREATE TABLE IF NOT EXISTS `mission` (
  `mission_id` bigint NOT NULL AUTO_INCREMENT,
  `mission_guid` varchar(36) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `start_date_time` datetime DEFAULT NULL,
  `end_date_time` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`mission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
CREATE TABLE IF NOT EXISTS `module` (
  `module_id` bigint NOT NULL AUTO_INCREMENT,
  `module_guid` varchar(36) NOT NULL,
  `is_active` bit(1) NOT NULL,
  `limit_value` int DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `price` decimal(25,0) NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `news_id` bigint NOT NULL AUTO_INCREMENT,
  `news_guid` varchar(36) NOT NULL,
  `is_active` bit(1) NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`news_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` bigint NOT NULL AUTO_INCREMENT,
  `payment_guid` varchar(36) NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `currency_id` bigint DEFAULT NULL,
  `amount` decimal(20,5) NOT NULL,
  `description` varchar(500) NOT NULL,
  `authority` varchar(36) NOT NULL,
  `status` int NOT NULL,
  `followup` varchar(36) NOT NULL,
  `from_app` bit(1) NOT NULL DEFAULT b'0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `user_id` (`user_id`),
  KEY `currency_id` (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `track`
--

DROP TABLE IF EXISTS `track`;
CREATE TABLE IF NOT EXISTS `track` (
  `track_id` bigint NOT NULL AUTO_INCREMENT,
  `track_guid` varchar(36) NOT NULL,
  `user_id` bigint NOT NULL,
  `track_group` tinytext NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `altitude` float DEFAULT NULL,
  `accuracy` float DEFAULT NULL,
  `speed` float DEFAULT NULL,
  `bearing` float DEFAULT NULL,
  `battery_power` tinyint NOT NULL DEFAULT '-1',
  `battery_status` tinyint NOT NULL DEFAULT '-1',
  `charge_status` tinyint NOT NULL DEFAULT '-1',
  `charge_type` tinyint NOT NULL DEFAULT '-1',
  `signal_power` tinyint NOT NULL DEFAULT '-1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`track_id`),
  KEY `user_company_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `transe_about_us`
--

DROP TABLE IF EXISTS `transe_about_us`;
CREATE TABLE IF NOT EXISTS `transe_about_us` (
  `transe_about_us_id` bigint NOT NULL AUTO_INCREMENT,
  `transe_about_us_guid` varchar(36) NOT NULL,
  `about_us_id` bigint NOT NULL,
  `language_id` bigint NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `address` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`transe_about_us_id`),
  KEY `about_us_id` (`about_us_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `transe_download`
--

DROP TABLE IF EXISTS `transe_download`;
CREATE TABLE IF NOT EXISTS `transe_download` (
  `transe_download_id` bigint NOT NULL AUTO_INCREMENT,
  `transe_download_guid` varchar(36) NOT NULL,
  `language_id` bigint NOT NULL,
  `download_id` bigint NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL,
  PRIMARY KEY (`transe_download_id`),
  KEY `lang_id` (`language_id`),
  KEY `download_id` (`download_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `transe_news`
--

DROP TABLE IF EXISTS `transe_news`;
CREATE TABLE IF NOT EXISTS `transe_news` (
  `transe_news_id` bigint NOT NULL AUTO_INCREMENT,
  `transe_news_guid` varchar(36) NOT NULL,
  `language_id` bigint NOT NULL,
  `news_id` bigint NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`transe_news_id`),
  KEY `language_id` (`language_id`),
  KEY `download_id` (`news_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `transe_user_type`
--

DROP TABLE IF EXISTS `transe_user_type`;
CREATE TABLE IF NOT EXISTS `transe_user_type` (
  `transe_user_type_id` bigint NOT NULL AUTO_INCREMENT,
  `transe_user_type_guid` varchar(36) NOT NULL,
  `user_type_id` bigint NOT NULL,
  `language_id` bigint NOT NULL,
  `title` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`transe_user_type_id`),
  KEY `user_type_id` (`user_type_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `trans_module`
--

DROP TABLE IF EXISTS `trans_module`;
CREATE TABLE IF NOT EXISTS `trans_module` (
  `trans_module_id` bigint NOT NULL AUTO_INCREMENT,
  `trans_module_guid` varchar(36) NOT NULL,
  `module_id` bigint NOT NULL,
  `language_id` bigint NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`trans_module_id`),
  KEY `module_id` (`module_id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` bigint NOT NULL AUTO_INCREMENT,
  `user_guid` varchar(36) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `family` varchar(50) DEFAULT NULL,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `user_type_id` bigint DEFAULT NULL,
  `report_row_limit` int NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `payment` float NOT NULL DEFAULT '0',
  `balance` decimal(20,5) NOT NULL DEFAULT '0.00000',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `end_sound` text,
  `cloud` bit(1) NOT NULL,
  `email` varchar(100) NOT NULL,
  `country_id` tinyint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `api_token` varchar(60) DEFAULT NULL,
  `gender` bit(1) DEFAULT NULL,
  `phone_code` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_active` bit(1) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `UserTypeId` (`user_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `user_company`
--

DROP TABLE IF EXISTS `user_company`;
CREATE TABLE IF NOT EXISTS `user_company` (
  `user_company_id` bigint NOT NULL AUTO_INCREMENT,
  `user_company_guid` varchar(36) NOT NULL,
  `user_id` bigint NOT NULL,
  `company_id` bigint DEFAULT NULL,
  `self_roll_call` bit(1) NOT NULL DEFAULT b'0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_company_id`),
  KEY `UserId` (`user_id`),
  KEY `CompanyId` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `user_mission`
--

DROP TABLE IF EXISTS `user_mission`;
CREATE TABLE IF NOT EXISTS `user_mission` (
  `user_mission_id` bigint NOT NULL AUTO_INCREMENT,
  `user_mission_guid` varchar(36) NOT NULL,
  `mission_id` bigint NOT NULL,
  `user_company_id` bigint NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_mission_id`),
  KEY `user_company_id` (`user_company_id`),
  KEY `mission_id` (`mission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `user_package`
--

DROP TABLE IF EXISTS `user_package`;
CREATE TABLE IF NOT EXISTS `user_package` (
  `user_package_id` bigint NOT NULL AUTO_INCREMENT,
  `user_package_guid` varchar(36) NOT NULL,
  `user_id` bigint NOT NULL,
  `package_id` bigint NOT NULL,
  `info` text NOT NULL,
  `expiration_date_time` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_package_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

DROP TABLE IF EXISTS `user_type`;
CREATE TABLE IF NOT EXISTS `user_type` (
  `user_type_id` bigint NOT NULL AUTO_INCREMENT,
  `user_type_guid` varchar(36) NOT NULL,
  `is_active` bit(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_user_company$attendance` FOREIGN KEY (`user_company_id`) REFERENCES `user_company` (`user_company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `company_user_module`
--
ALTER TABLE `company_user_module`
  ADD CONSTRAINT `fk_company$company_user_module` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_module$company_user_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`module_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user$company_user_module` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `currency`
--
ALTER TABLE `currency`
  ADD CONSTRAINT `fk_country$currency` FOREIGN KEY (`country_id`) REFERENCES `country` (`country_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_currency$payment` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`currency_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user$payment` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `track`
--
ALTER TABLE `track`
  ADD CONSTRAINT `fk_user$track` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transe_about_us`
--
ALTER TABLE `transe_about_us`
  ADD CONSTRAINT `fk_about_us$trans_about_us` FOREIGN KEY (`about_us_id`) REFERENCES `about_us` (`about_us_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_language$trans_about_us` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transe_download`
--
ALTER TABLE `transe_download`
  ADD CONSTRAINT `fk_download$transe_download` FOREIGN KEY (`download_id`) REFERENCES `download` (`download_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_language$transe_download` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transe_news`
--
ALTER TABLE `transe_news`
  ADD CONSTRAINT `fk_language$transe_news` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_news$transe_news` FOREIGN KEY (`news_id`) REFERENCES `news` (`news_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transe_user_type`
--
ALTER TABLE `transe_user_type`
  ADD CONSTRAINT `fk_language$trans_user_type` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_type$trans_user_type` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`user_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trans_module`
--
ALTER TABLE `trans_module`
  ADD CONSTRAINT `fk_language$trans_module` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_module$trans_module` FOREIGN KEY (`module_id`) REFERENCES `module` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_company`
--
ALTER TABLE `user_company`
  ADD CONSTRAINT `fk_company$user_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user$user_company` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_mission`
--
ALTER TABLE `user_mission`
  ADD CONSTRAINT `fk_mission$user_company` FOREIGN KEY (`user_company_id`) REFERENCES `user_company` (`user_company_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mission$user_mission` FOREIGN KEY (`mission_id`) REFERENCES `mission` (`mission_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
