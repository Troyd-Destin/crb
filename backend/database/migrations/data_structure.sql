-- MySQL dump 10.13  Distrib 5.7.9, for Win32 (AMD64)
--
-- Host: localhost    Database: crb
-- ------------------------------------------------------
-- Server version	5.7.12-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `crb_cities`
--

DROP TABLE IF EXISTS `crb_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crb_cities` (
  `city_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`city_name`),
  UNIQUE KEY `city_name_UNIQUE` (`city_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `data_schools`
--

DROP TABLE IF EXISTS `data_schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_schools` (
  `school_code` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_address1` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_address2` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_city` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_province` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_postal_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_phone` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_fax` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_email` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_website` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ecstaught` tinyint(4) DEFAULT NULL,
  `elementary_taught` tinyint(4) DEFAULT NULL,
  `junior_high_taught` tinyint(4) DEFAULT NULL,
  `senior_high_taught` tinyint(4) DEFAULT NULL,
  `authority_type` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double(25,15) DEFAULT NULL,
  `longitude` double(25,15) DEFAULT NULL,
  `authority_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authority_name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authority_address1` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authority_address2` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authority_city` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authority_province` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authority_postal_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authority_telephone` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authority_fax` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authority_e_mail` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authority_website` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `offer_school_home_education` tinyint(4) DEFAULT NULL,
  `offer_school_home_education_blended` tinyint(4) DEFAULT NULL,
  `offer_school_online` tinyint(4) DEFAULT NULL,
  `offer_school_outreach` tinyint(4) DEFAULT NULL,
  `school_updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`school_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `data_schools_population`
--

DROP TABLE IF EXISTS `data_schools_population`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_schools_population` (
  `school_code` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_authority_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ecs` int(11) DEFAULT '0',
  `grade_1` int(11) DEFAULT '0',
  `grade_2` int(11) DEFAULT '0',
  `grade_3` int(11) DEFAULT '0',
  `grade_4` int(11) DEFAULT '0',
  `grade_5` int(11) DEFAULT '0',
  `grade_6` int(11) DEFAULT '0',
  `grade_7` int(11) DEFAULT '0',
  `grade_8` int(11) DEFAULT '0',
  `grade_9` int(11) DEFAULT '0',
  `grade_10` int(11) DEFAULT '0',
  `grade_11` int(11) DEFAULT '0',
  `grade_12` int(11) DEFAULT '0',
  `total` int(11) DEFAULT '0',
  `start_year` int(11) NOT NULL,
  `end_year` int(11) NOT NULL,
  `enrollment_updated` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`school_code`,`start_year`,`end_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-27 11:51:43
