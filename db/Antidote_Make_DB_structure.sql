CREATE DATABASE  IF NOT EXISTS `antidote` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `antidote`;
-- MySQL dump 10.13  Distrib 8.0.20, for Linux (x86_64)
--
-- Host: localhost    Database: antidote
-- ------------------------------------------------------
-- Server version	8.0.20-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `contribution_types`
--

DROP TABLE IF EXISTS `contribution_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contribution_types` (
  `id_payment_type` smallint NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id_payment_type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contributions`
--

DROP TABLE IF EXISTS `contributions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contributions` (
  `id_contribution` int NOT NULL AUTO_INCREMENT,
  `id_contribution_type` smallint NOT NULL,
  `date_paid` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_helper` int NOT NULL,
  `id_people` int NOT NULL,
  `id_meal` int NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `confirmation_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id_contribution`),
  KEY `fk_payment_person_idx` (`id_people`),
  KEY `fk_payment_helper_idx` (`id_helper`),
  KEY `fk_payment_type_idx` (`id_contribution_type`),
  CONSTRAINT `fk_payment_helper` FOREIGN KEY (`id_helper`) REFERENCES `people` (`id_people`),
  CONSTRAINT `fk_payment_person` FOREIGN KEY (`id_people`) REFERENCES `people` (`id_people`),
  CONSTRAINT `fk_payment_type` FOREIGN KEY (`id_contribution_type`) REFERENCES `contribution_types` (`id_payment_type`)
) ENGINE=InnoDB AUTO_INCREMENT=429 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `delivery_addresses`
--

DROP TABLE IF EXISTS `delivery_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `delivery_addresses` (
  `id_deliver_address` int NOT NULL AUTO_INCREMENT,
  `id_person` int NOT NULL,
  `lng` decimal(11,8) NOT NULL,
  `lat` decimal(11,8) NOT NULL,
  `street_number` varchar(20) NOT NULL,
  `route` varchar(100) DEFAULT NULL,
  `locality` varchar(100) DEFAULT NULL,
  `administrative_area_level_1` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `added` datetime DEFAULT CURRENT_TIMESTAMP,
  `country` varchar(100) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `default_ship` bit(1) NOT NULL DEFAULT b'0',
  `km_delivery_rate` decimal(9,2) DEFAULT NULL,
  `delivery_surcharge` decimal(9,2) DEFAULT NULL,
  `delivery_price` decimal(9,2) DEFAULT NULL,
  `car_time` int DEFAULT NULL,
  `eta` int DEFAULT NULL,
  PRIMARY KEY (`id_deliver_address`),
  UNIQUE KEY `id_deliver_address_UNIQUE` (`id_deliver_address`),
  KEY `fk_delivery_addresses_1_idx` (`id_person`),
  CONSTRAINT `fk_delivery_addresses_1` FOREIGN KEY (`id_person`) REFERENCES `people` (`id_people`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `deliverys`
--

DROP TABLE IF EXISTS `deliverys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deliverys` (
  `id_delivery` int NOT NULL AUTO_INCREMENT,
  `Id_driver` int NOT NULL,
  `id_delivery_address` int DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_delivery_start` datetime DEFAULT NULL,
  `date_delivery_finished` datetime DEFAULT NULL,
  `delivery_rating` tinyint DEFAULT NULL,
  `delivery_comment` varchar(500) DEFAULT NULL,
  `meal_id` int NOT NULL,
  `duration_est` int DEFAULT NULL,
  `preperation_est` int DEFAULT NULL,
  `eta_est` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_delivery`),
  UNIQUE KEY `id_delivery_UNIQUE` (`id_delivery`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_communication`
--

DROP TABLE IF EXISTS `email_communication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_communication` (
  `id_email_communication` int NOT NULL AUTO_INCREMENT,
  `id_person` int NOT NULL,
  `date_sent` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_email_template` int NOT NULL,
  `email_communicationcol` varchar(45) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` varchar(20000) NOT NULL,
  PRIMARY KEY (`id_email_communication`),
  KEY `fk_id_template_idx` (`id_email_template`),
  CONSTRAINT `fk_id_template` FOREIGN KEY (`id_email_template`) REFERENCES `email_template` (`id_emails`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_template`
--

DROP TABLE IF EXISTS `email_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_template` (
  `id_emails` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` varchar(20000) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_emails`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `field_list`
--

DROP TABLE IF EXISTS `field_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `field_list` (
  `id_column` int NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `column_name` varchar(50) NOT NULL,
  `last_update` datetime NOT NULL,
  `created` datetime NOT NULL,
  `data_type` varchar(20) NOT NULL,
  `length` int DEFAULT NULL,
  `ordinal_position` smallint NOT NULL,
  `schema` varchar(45) NOT NULL,
  `column_type` varchar(45) NOT NULL,
  `max_size` smallint DEFAULT NULL,
  PRIMARY KEY (`id_column`),
  UNIQUE KEY `id_column_UNIQUE` (`id_column`),
  UNIQUE KEY `unq_table_field` (`table_name`,`column_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Used for dbaseefining all the fields in the database, and for updates, deletes and adding records.	';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `food`
--

DROP TABLE IF EXISTS `food`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food` (
  `id_food` int NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `Intro` varchar(12000) NOT NULL,
  `Image_path` varchar(512) DEFAULT NULL,
  `Description` mediumtext,
  `wh_id` int NOT NULL,
  `default_unit` varchar(50) DEFAULT NULL,
  `grams_default` decimal(8,2) NOT NULL,
  `visible` bit(1) DEFAULT b'0',
  `id_person_add` int NOT NULL,
  `uid_food` varchar(8) DEFAULT NULL,
  `id_supplier` int DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `id_type` int DEFAULT NULL,
  PRIMARY KEY (`id_food`),
  KEY `fk_id_person_idx` (`id_person_add`),
  KEY `fk_id_food_type_idx` (`id_type`),
  CONSTRAINT `fk_id_food_type` FOREIGN KEY (`id_type`) REFERENCES `food_types` (`id_food_type`),
  CONSTRAINT `fk_id_person` FOREIGN KEY (`id_person_add`) REFERENCES `people` (`id_people`)
) ENGINE=InnoDB AUTO_INCREMENT=243 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `food_BEFORE_INSERT` BEFORE INSERT ON `food` FOR EACH ROW BEGIN
SET new.uid_food = left(uuid(),8);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `food_types`
--

DROP TABLE IF EXISTS `food_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food_types` (
  `id_food_type` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `order_food` smallint NOT NULL DEFAULT '99',
  PRIMARY KEY (`id_food_type`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `food_vitamins`
--

DROP TABLE IF EXISTS `food_vitamins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food_vitamins` (
  `id_food_vitamin` int NOT NULL AUTO_INCREMENT,
  `id_food` int NOT NULL,
  `id_vitamin` int DEFAULT NULL,
  `percentage` smallint NOT NULL,
  `color` varchar(7) DEFAULT 'Null',
  PRIMARY KEY (`id_food_vitamin`),
  UNIQUE KEY `unq_food_vitamin` (`id_food`,`id_vitamin`),
  KEY `FK_id_food_vitamin_idx` (`id_vitamin`)
) ENGINE=InnoDB AUTO_INCREMENT=1643 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu_ingedients`
--

DROP TABLE IF EXISTS `menu_ingedients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_ingedients` (
  `id_Menu_Ingredients` int NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `quantity` smallint NOT NULL,
  `quantity_type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_Menu_Ingredients`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id_menu` int NOT NULL AUTO_INCREMENT,
  `title` varchar(35) NOT NULL,
  `subtitle` varchar(65) NOT NULL,
  `how_to` varchar(2048) NOT NULL,
  `time_to_make` smallint NOT NULL,
  `skill_level` tinyint NOT NULL,
  `image_path` varchar(500) NOT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id_pages` int NOT NULL AUTO_INCREMENT,
  `url` varchar(400) NOT NULL,
  `title` varchar(60) NOT NULL,
  PRIMARY KEY (`id_pages`),
  UNIQUE KEY `url_UNIQUE` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `people`
--

DROP TABLE IF EXISTS `people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `people` (
  `id_people` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `image_path` varchar(200) DEFAULT NULL,
  `uid_people` char(8) DEFAULT NULL,
  `about_me` varchar(8000) DEFAULT NULL,
  `can_authorize` bit(1) DEFAULT b'0',
  `auto_login` char(36) DEFAULT NULL,
  `fb_id` varchar(30) DEFAULT NULL,
  `oauth_provider` varchar(30) DEFAULT NULL,
  `oauth_uid` varchar(25) DEFAULT NULL,
  `locale` varchar(10) DEFAULT NULL,
  `restaurant` int DEFAULT '2',
  `added` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_people`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `uid_people_UNIQUE` (`uid_people`),
  UNIQUE KEY `auto_login_UNIQUE` (`auto_login`)
) ENGINE=InnoDB AUTO_INCREMENT=973 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `people_BEFORE_INSERT` BEFORE INSERT ON `people` FOR EACH ROW BEGIN
SET new.uid_people = left(uuid(),8);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `before_insert_people` BEFORE INSERT ON `people` FOR EACH ROW SET new.auto_login = uuid() */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `people_eat`
--

DROP TABLE IF EXISTS `people_eat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `people_eat` (
  `id_people_eat` int NOT NULL AUTO_INCREMENT,
  `id_recipe` int NOT NULL,
  `consumption_date` datetime DEFAULT NULL,
  `comment` varchar(2048) DEFAULT NULL,
  `id_helper` int NOT NULL,
  `id_people` int NOT NULL,
  `id_portion_size` smallint NOT NULL,
  `consumed` bit(1) NOT NULL DEFAULT b'0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_people_eat`),
  KEY `fk_people_idx` (`id_recipe`),
  KEY `fk_people_eat_person_id_idx` (`id_people`),
  KEY `fk_people_eat_portion_idx` (`id_portion_size`),
  CONSTRAINT `fk_people_eat_person_id` FOREIGN KEY (`id_people`) REFERENCES `people` (`id_people`),
  CONSTRAINT `fk_people_eat_portion` FOREIGN KEY (`id_portion_size`) REFERENCES `portion_sizes` (`id_portion_size`),
  CONSTRAINT `fk_people_eat_recipe` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id_recipe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `people_food_favourites`
--

DROP TABLE IF EXISTS `people_food_favourites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `people_food_favourites` (
  `id_food_favourite` int NOT NULL AUTO_INCREMENT,
  `id_people` int DEFAULT NULL,
  `id_food` int DEFAULT NULL,
  PRIMARY KEY (`id_food_favourite`),
  UNIQUE KEY `id_food_favourite_UNIQUE` (`id_food_favourite`),
  KEY `fk_id_person_fav_food_idx` (`id_people`),
  KEY `fk_id_recipe_fav_food_idx` (`id_food`),
  CONSTRAINT `fk_id_person_fav_food` FOREIGN KEY (`id_people`) REFERENCES `people` (`id_people`),
  CONSTRAINT `fk_id_recipe_fav_food` FOREIGN KEY (`id_food`) REFERENCES `food` (`id_food`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `people_meals`
--

DROP TABLE IF EXISTS `people_meals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `people_meals` (
  `id_meal` int NOT NULL AUTO_INCREMENT,
  `comment` varchar(512) DEFAULT NULL,
  `served_by` int NOT NULL,
  `id_people` int NOT NULL,
  `consumed` bit(1) NOT NULL DEFAULT b'0',
  `consumption_date` datetime DEFAULT NULL,
  `served` bit(1) DEFAULT b'0',
  `date_served` datetime DEFAULT NULL,
  `id_table` int DEFAULT '11',
  `uid_meal` varchar(36) DEFAULT NULL,
  `delivery_id` int DEFAULT NULL,
  PRIMARY KEY (`id_meal`),
  KEY `fk_people_id_idx` (`id_people`),
  KEY `fk_served_by_idx` (`served_by`),
  KEY `fk_table_id_idx` (`id_table`),
  CONSTRAINT `fk_people_id` FOREIGN KEY (`id_people`) REFERENCES `people` (`id_people`),
  CONSTRAINT `fk_served_by` FOREIGN KEY (`served_by`) REFERENCES `people` (`id_people`),
  CONSTRAINT `fk_table_id` FOREIGN KEY (`id_table`) REFERENCES `serving_tables` (`id_table`)
) ENGINE=InnoDB AUTO_INCREMENT=305 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `people_meals_BEFORE_INSERT` BEFORE INSERT ON `people_meals` FOR EACH ROW BEGIN
  SET new.uid_meal = uuid();
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `people_meals_recipes`
--

DROP TABLE IF EXISTS `people_meals_recipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `people_meals_recipes` (
  `id_people_meals_recipes` int NOT NULL AUTO_INCREMENT,
  `id_recipe` int NOT NULL,
  `consumption_date` datetime DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `id_portion_size` smallint NOT NULL,
  `consumed` bit(1) NOT NULL DEFAULT b'0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_meal` int NOT NULL,
  `served` bit(1) DEFAULT b'0',
  `date_served` datetime DEFAULT NULL,
  PRIMARY KEY (`id_people_meals_recipes`),
  KEY `fk_people_eat_id_meal_idx` (`id_meal`),
  KEY `fk_people_eat_portion_idx` (`id_portion_size`),
  KEY `fk_people_eat_recipe_idx` (`id_recipe`),
  CONSTRAINT `fk_pe_portion_size` FOREIGN KEY (`id_portion_size`) REFERENCES `portion_sizes` (`id_portion_size`),
  CONSTRAINT `fk_people_eat_id_meal` FOREIGN KEY (`id_meal`) REFERENCES `people_meals` (`id_meal`),
  CONSTRAINT `fk_people_eat_recipes` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id_recipe`)
) ENGINE=InnoDB AUTO_INCREMENT=795 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `people_meals_vitamins`
--

DROP TABLE IF EXISTS `people_meals_vitamins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `people_meals_vitamins` (
  `id_pmv` int NOT NULL AUTO_INCREMENT,
  `id_meal` int NOT NULL,
  `id_people` int NOT NULL,
  `id_vitamin` int NOT NULL,
  `RDI` decimal(8,2) NOT NULL,
  PRIMARY KEY (`id_pmv`),
  KEY `fk_id_people_idx` (`id_people`),
  KEY `fk_meal_id_idx` (`id_meal`),
  KEY `fk_vitamin_id_idx` (`id_vitamin`),
  KEY `fk_pmv_id_people_idx` (`id_people`),
  KEY `fk_pmv_meal_id_idx` (`id_meal`),
  KEY `fk_pmv_vitamin_id_idx` (`id_vitamin`),
  CONSTRAINT `fk_pmv_meal_id` FOREIGN KEY (`id_meal`) REFERENCES `people_meals` (`id_meal`),
  CONSTRAINT `fk_pmv_people_id` FOREIGN KEY (`id_people`) REFERENCES `people` (`id_people`),
  CONSTRAINT `fk_pmv_vitamin_id` FOREIGN KEY (`id_vitamin`) REFERENCES `vitamins` (`id_vitamin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `people_recipe_favourites`
--

DROP TABLE IF EXISTS `people_recipe_favourites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `people_recipe_favourites` (
  `id_people_favourite` int NOT NULL AUTO_INCREMENT,
  `id_people` int NOT NULL,
  `id_recipe` int NOT NULL,
  PRIMARY KEY (`id_people_favourite`),
  UNIQUE KEY `unq_people_recipe_fav` (`id_people`,`id_recipe`),
  KEY `fk_id_person_idx` (`id_people`),
  KEY `fk_id_recipe_idx` (`id_recipe`),
  CONSTRAINT `fk_id_person_fav` FOREIGN KEY (`id_people`) REFERENCES `people` (`id_people`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_id_recipe_fav` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id_recipe`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `people_saved_cards`
--

DROP TABLE IF EXISTS `people_saved_cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `people_saved_cards` (
  `id_people_saved_cards` int NOT NULL AUTO_INCREMENT,
  `vendor` varchar(45) NOT NULL,
  `vendor_key` varchar(255) NOT NULL,
  `id_person` int NOT NULL,
  PRIMARY KEY (`id_people_saved_cards`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `portion_sizes`
--

DROP TABLE IF EXISTS `portion_sizes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portion_sizes` (
  `id_portion_size` smallint NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `weight` smallint NOT NULL,
  `multiplier` decimal(4,2) DEFAULT NULL,
  PRIMARY KEY (`id_portion_size`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id_project` int NOT NULL AUTO_INCREMENT,
  `id_person` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `intro` varchar(1024) DEFAULT NULL,
  `description` varchar(20000) NOT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `url_link` varchar(515) DEFAULT NULL,
  PRIMARY KEY (`id_project`),
  UNIQUE KEY `id_projects_UNIQUE` (`id_project`),
  UNIQUE KEY `title_UNIQUE` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `recipe_contribution`
--

DROP TABLE IF EXISTS `recipe_contribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe_contribution` (
  `id_recipe_contribution` int NOT NULL AUTO_INCREMENT,
  `id_recipe` int NOT NULL,
  `amount_currency` double NOT NULL,
  `id_portion_size` smallint NOT NULL,
  `id_food` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_recipe_contribution`),
  UNIQUE KEY `unique_contribution` (`id_recipe`,`id_portion_size`),
  UNIQUE KEY `id_recipe_contribution_UNIQUE` (`id_recipe_contribution`),
  KEY `fk_rec_id_food_idx` (`id_food`),
  CONSTRAINT `fk_rec_id_recipe` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id_recipe`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `recipe_foods`
--

DROP TABLE IF EXISTS `recipe_foods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe_foods` (
  `id_recipe_food` int NOT NULL AUTO_INCREMENT,
  `id_recipe` int NOT NULL,
  `id_food` int NOT NULL,
  `qty_grams` int NOT NULL,
  `id_person` int NOT NULL,
  `display_order` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_recipe_food`),
  UNIQUE KEY `unq_food` (`id_recipe`,`id_food`),
  KEY `fk_recipie_food_idx` (`id_food`),
  KEY `fk_person_id_idx` (`id_person`),
  KEY `fk_recipe_idx` (`id_recipe`),
  CONSTRAINT `fk_person_id` FOREIGN KEY (`id_person`) REFERENCES `people` (`id_people`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipe` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id_recipe`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipe_food` FOREIGN KEY (`id_food`) REFERENCES `food` (`id_food`)
) ENGINE=InnoDB AUTO_INCREMENT=1099 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `recipe_types`
--

DROP TABLE IF EXISTS `recipe_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe_types` (
  `id_recipe_type` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `order_rt` smallint NOT NULL DEFAULT '99',
  PRIMARY KEY (`id_recipe_type`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `recipes`
--

DROP TABLE IF EXISTS `recipes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipes` (
  `id_recipe` int NOT NULL AUTO_INCREMENT,
  `id_person` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `how_to_make` varchar(8000) NOT NULL,
  `id_type` int NOT NULL,
  `temp` bit(1) DEFAULT b'1',
  `show_on_web` bit(1) DEFAULT b'0',
  `added` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP,
  `uid_recipe` char(8) DEFAULT NULL,
  `servings` tinyint NOT NULL DEFAULT '1',
  `authorized` bit(1) NOT NULL DEFAULT b'0',
  `brief` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_recipe`),
  UNIQUE KEY `uid_recipe_UNIQUE` (`uid_recipe`),
  KEY `fk_id_person_recipie_idx` (`id_person`),
  KEY `fk_id_recipepie_ty_idx` (`id_type`),
  CONSTRAINT `fk_id_person_recipie` FOREIGN KEY (`id_person`) REFERENCES `people` (`id_people`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_id_recipepie_ty` FOREIGN KEY (`id_type`) REFERENCES `recipe_types` (`id_recipe_type`)
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `recipes_BEFORE_INSERT` BEFORE INSERT ON `recipes` FOR EACH ROW BEGIN
SET new.uid_recipe = left(uuid(),8);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `recipes_graph`
--

DROP TABLE IF EXISTS `recipes_graph`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipes_graph` (
  `id_recipe_graph` int NOT NULL AUTO_INCREMENT,
  `id_vitamin` int NOT NULL,
  `RDI` decimal(8,2) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(7) DEFAULT NULL,
  `id_recipe` int NOT NULL,
  PRIMARY KEY (`id_recipe_graph`),
  UNIQUE KEY `idx_recipe_vitamin` (`id_vitamin`,`id_recipe`),
  KEY `fk_id_recipe_cache_idx` (`id_recipe`),
  KEY `fk_id_vitamin_idx` (`id_vitamin`),
  CONSTRAINT `fk_id_recipe` FOREIGN KEY (`id_recipe`) REFERENCES `recipes` (`id_recipe`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_id_vitamin` FOREIGN KEY (`id_vitamin`) REFERENCES `vitamins` (`id_vitamin`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2962081 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restaurant`
--

DROP TABLE IF EXISTS `restaurant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurant` (
  `id_restaurant` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `url` varchar(100) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_1` varchar(45) NOT NULL,
  `address_2` varchar(45) DEFAULT NULL,
  `city` varchar(45) NOT NULL,
  `added` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `show_food_grams` tinyint NOT NULL DEFAULT '1',
  `lat` decimal(11,8) NOT NULL,
  `lng` decimal(11,8) NOT NULL,
  `accept_cash` tinyint NOT NULL DEFAULT '1',
  `accept_credit_cards` tinyint NOT NULL DEFAULT '1',
  `accept_karma` tinyint NOT NULL DEFAULT '1',
  `post_code` varchar(10) DEFAULT NULL,
  `allow_delivery` bit(1) DEFAULT NULL,
  `delivery_max_distance` int DEFAULT NULL,
  `delivery_charge_per_km` float DEFAULT NULL,
  `delivery_charge_surcharge` float DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `google_client_id` varchar(100) DEFAULT NULL,
  `google_secret` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_restaurant`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id_review` int NOT NULL AUTO_INCREMENT,
  `id_person` int NOT NULL,
  `id_recipe` int NOT NULL,
  `review_text` varchar(500) DEFAULT NULL,
  `date_reviewed` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stars` tinyint NOT NULL DEFAULT '5',
  PRIMARY KEY (`id_review`),
  UNIQUE KEY `id_review_UNIQUE` (`id_review`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `serving_tables`
--

DROP TABLE IF EXISTS `serving_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `serving_tables` (
  `id_table` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id_table`),
  UNIQUE KEY `id_tables_UNIQUE` (`id_table`),
  UNIQUE KEY `table_name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id_supplier` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image_location` varchar(255) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `geo_x` varchar(45) DEFAULT NULL,
  `geo_y` varchar(45) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `supplierscol` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vitamins`
--

DROP TABLE IF EXISTS `vitamins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vitamins` (
  `id_vitamin` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `whf_id` int NOT NULL,
  `Overview` varchar(20000) DEFAULT NULL,
  `Full_Description` mediumtext,
  `color` varchar(7) DEFAULT NULL,
  `Health_benefits` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_vitamin`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'antidote'
--

--
-- Dumping routines for database 'antidote'
--
/*!50003 DROP FUNCTION IF EXISTS `STRIP_NON_DIGIT` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `STRIP_NON_DIGIT`(input VARCHAR(255)) RETURNS varchar(255) CHARSET utf8
BEGIN
   DECLARE output   VARCHAR(255) DEFAULT '';
   DECLARE iterator INT          DEFAULT 1;
   WHILE iterator < (LENGTH(input) + 1) DO
      IF SUBSTRING(input, iterator, 1) IN ('.', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ) THEN
         SET output = CONCAT(output, SUBSTRING(input, iterator, 1));
      END IF;
      SET iterator = iterator + 1;
   END WHILE;
   RETURN output;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Clean_food_data` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Clean_food_data`()
BEGIN
update `antidote`.`food` 
set intro=replace(intro,'<div style="width:100%; font-weight:bold; background-color:orange;"><div style="float:left;padding-left:1em">','');

update `antidote`.`food` 
set intro=replace(intro,'</div>','')
where LOCATE('<div',intro);

update `antidote`.`food` 
set intro=replace(intro,'<div style="float:right;padding-top:1em;padding-right:1em">','')
where LOCATE('<div',intro)>0;

update `antidote`.`food` 
set intro=replace(intro,'<a href="genpage.php?tname=faq&amp;dbid=32">','<a href="/info.asp?qry=GI">')
where LOCATE('<a href="genpage.php?tname=faq&amp;dbid=32">',intro)>0;


update `antidote`.`food` 
set intro=replace(intro,'<i>','')
where LOCATE('<i',intro)>0;

update `antidote`.`food` 
set intro=replace(intro,'</i>','')
where LOCATE('</i>',intro)>0;

update `antidote`.`food` 
set intro=replace(intro,'<td>','')
where LOCATE('<table',intro)>0;

update `antidote`.`food` 
set intro=replace(intro,'<tr>','')
where LOCATE('<table',intro)>0;

update `antidote`.`food` 
set intro=replace(intro,'</tr>','')
where LOCATE('<table',intro)>0;

update `antidote`.`food` 
set intro=replace(intro,'</td>','')
where LOCATE('<table',intro)>0;

update `antidote`.`food` 
set intro=replace(intro,'<td align="center" colspan="2">','')
where LOCATE('<table',intro)>0;


update `antidote`.`food` 
set intro=replace(intro,'<table border=1>','')
where LOCATE('<table',intro)>0;

Select * from food
where LOCATE('<table',intro)>0;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `debug_msg` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `debug_msg`(enabled INTEGER, msg VARCHAR(255))
BEGIN
  IF enabled THEN
    select concat('** ', msg) AS '** DEBUG:';
  END IF;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `delivery_address` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delivery_address`(address_name varchar(200),lng_in numeric(11,8),lat_in numeric(11,8),id_person_in int,street_number VARCHAR(20),route VARCHAR(100),locality VARCHAR(100),administrative_area_level_1 VARCHAR(100),country VARCHAR(100),postal_code VARCHAR(10),order_time int,car_time int,eta int,id_meal_in int,
km_delivery_rate numeric (8,2),delivery_surcharge numeric (8,2),delivery_price numeric(9,2))
BEGIN
#SET @enabled = TRUE;
#call debug_msg(@enabled, 'my first debug message');
#call debug_msg(@enabled, (Select count(*) addresses_count from delivery_addresses where lng=lng_in and lat=lat_in and id_person=id_person_in));
IF (Select count(*) addresses_count from delivery_addresses where lng=lng_in and lat=lat_in and id_person=id_person_in)=0  then
	Insert into delivery_addresses (name,lng,lat,id_person,street_number,route,locality,administrative_area_level_1,country,postal_code,car_time,eta,km_delivery_rate,delivery_surcharge,delivery_price)
    Select address_name,lng_in,lat_in,id_person_in,street_number,route,locality,administrative_area_level_1,country,postal_code,car_time,eta,km_delivery_rate,delivery_surcharge,delivery_price;
    set @id_delivery_address=LAST_INSERT_ID();
else
	set @id_delivery_address=(Select id_deliver_address from delivery_addresses where lng=lng_in and lat=lat_in and id_person=id_person_in);
END IF;
/*now get that delivery address_id and update the or*/
#check deliveries table to see if there is already an address with the meal.
IF (Select count(*) meal_deliveries from deliverys where meal_id=id_meal_in)=0  then
	INSERT INTO `deliverys`	(Id_driver,	id_delivery_address,meal_id)
	Select 0,	@id_delivery_address,	id_meal_in;
	update people_meals set delivery_id=LAST_INSERT_ID() where id_meal=id_meal_in;
else
	update deliverys set id_delivery_address=@id_delivery_address where meal_id=id_meal_in;
	update people_meals set delivery_id=(Select id_delivery from deliverys where meal_id=id_meal_in) where id_meal=id_meal_in;
end if;
 /*now get that delivery address_id and update the or*/

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `foods_most_popular` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `foods_most_popular`(search varchar(50))
BEGIN


SELECT  f.name,
Image_path,grams_default,f.id_food,count(rf.id_food) 'count of'
FROM food f
left join recipe_foods rf on f.id_food=rf.id_food
left join antidote.people_meals_recipes pm  
on rf.id_recipe=pm.id_recipe
where f.name like CONCAT('%',search,'%')
group by f.id_food
order by count(f.id_food) desc
LIMIT 20;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `foods_most_popular_by_vit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `foods_most_popular_by_vit`(search varchar(50))
BEGIN


SELECT  f.name,
Image_path,grams_default,f.id_food,percentage FROM food f
inner join food_vitamins fv
 on fv.id_food=f.id_food
inner join vitamins v
 on v.id_vitamin=fv.id_vitamin
where v.name = search
order by fv.percentage*100/grams_default desc
LIMIT 100;


END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `food_vitmains` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `food_vitmains`(idfood int)
BEGIN


SELECT  vv.name, -- count(f.id_food) 'count of',
Image_path,grams_default,f.id_food,
v.percentage,COALESCE(v.id_vitamin,0) 'id_vitamin',vv.name 'vitamin_name',
v.percentage/f.grams_default*100 'percentRDI',vv.color
FROM food f
left join food_vitamins v
	on f.id_food=v.id_food
inner join vitamins vv
	 on vv.id_vitamin=v.id_vitamin
where f.id_food=idfood
order by v.percentage desc,
v.id_vitamin;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getCategorySpendingbyID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `getCategorySpendingbyID`(in category int)
BEGIN

SELECT department,CategoryName, Portfolio_Name,sum(amount) 'total_budget' ,
Current_Scope,Functional_Classification,amount_type,Restriction_Type, group_type, AppropriationName, Appropriation_or_Category_Type,
d.id_department,dc.id_category,
(Select count(*) from comments c where c.id_category=dc.id_category) 'Comments',
(Select count(*) from votes v where v.id_comment in (Select id_comment from comments dc where id_category=dc.id_category)) 'Votes'
FROM adds.budget_figures b
inner join departments d
on d.name=b.department
inner join departments_category dc on 
dc.name=b.categoryname and dc.id_department=d.id_department
Where year=2018 
and dc.id_category=category
group by department, CategoryName, Portfolio_Name,
Current_Scope,Functional_Classification,amount_type,Restriction_Type, group_type, AppropriationName, Appropriation_or_Category_Type
order by sum(amount) desc ;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `GetFoodsList` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetFoodsList`()
BEGIN
SELECT `food`.`id_food`,
    `food`.`name`,
    `food`.`Intro`,
    `food`.`Image_path`,
    `food`.`Image_local`,
    `food`.`Description`,
    `food`.`wh_id`
FROM `antidote`.`food` order by name;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Get_food_filter` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get_food_filter`(badmin bit, person int, food_filter VARCHAR(50))
BEGIN
SELECT r.id_recipe,r.id_person,r.name,r.image,r.how_to_make,
r.servings,r.uid_recipe,uid_people,
rt.name `group_name`,r.id_type,p.name `person_name`,f.id_people_favourite
FROM antidote.foods f
inner join food_types rt
	on ft.id_food_type=f.id_type
inner join people p
	on p.id_people=ff.id_person
left join people_food_favourites ff
	on f.id_food=f.id_food and ff.id_people=person
where (show_on_web=1 or badmin=1) and temp=0  
and (food_filter='' or f.name=food_filter)
order by ft.order_food asc,f.name, f.id_food;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Get_Meals` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get_Meals`(person int)
BEGIN
Select id_meal from People_meals m
where id_people=person and consumed=1
order by m.id_meal desc limit 10;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_meals_10` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_meals_10`(person int)
BEGIN
SELECT r.name 'recipe_name',
r.image, p.id_portion_size,p.name 'portion_name',c.amount_currency , e.*,m.id_people,
 (Select name from people where id_people=m.served_by) 'served_name'
 ,id_people
FROM people_meals_recipes e
inner join people_meals m
	on e.id_meal=e.id_meal 
inner join recipes r
	on r.id_recipe=e.id_recipe
inner join recipe_contribution c
	on c.id_recipe=r.id_recipe and e.id_portion_size=c.id_portion_size
inner join portion_sizes p
	on p.id_portion_size=e.id_portion_size
where m.id_people=person
and e.id_meal=m.id_meal
order by  m.id_meal desc,e.id_people_meals_recipes asc;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Get_Meals_served` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get_Meals_served`(been_served bit,date_served_from date, date_served_to date)
BEGIN
SELECT m.id_meal,served_by,p.id_people,r.id_recipe,r.name 'recipe_name',
(Select p2.name from people p2 where p2.id_people=m.served_by) 'served_by',(Select p3.image_path from people p3 where p3.id_people=m.served_by) 'served_image',
p.name 'person_name',r.image 'recipe_image',p.image_path 'person_image',how_to_make,mr.created,id_people_meals_recipes, t.name 'table_name', pt.name 'contribution',c.amount
FROM antidote.people_meals m
inner join people_meals_recipes mr
	on m.id_meal=mr.id_meal
inner join recipes r
	on r.id_recipe=mr.id_recipe
inner join people p
	on p.id_people=m.id_people
inner join serving_tables t
	on t.id_table=m.id_table
left join contributions c
	on c.id_meal=m.id_meal
inner join payment_types pt
	on pt.id_payment_type=c.id_payment_type
where mr.served=been_served
and DATEDIFF(created,date_served_from)>=0 and DATEDIFF(created,date_served_to)<=0
order by mr.id_people_meals_recipes desc;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_pageTitle` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_pageTitle`(pgurl varchar(400))
BEGIN
Select title from pages where url=pgurl ;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_people_eat_by_id` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_people_eat_by_id`(people_id int)
BEGIN
SELECT r.name 'recipe_name',
r.image,
 p.id_portion_size,p.name 'portion_name',c.amount_currency , e.*,m.id_people,m.served_by,id_people
FROM people_meals_recipes e
inner join people_meals m
	on e.id_meal=e.id_meal 
inner join recipes r
	on r.id_recipe=e.id_recipe
inner join recipe_contribution c
	on c.id_recipe=r.id_recipe and e.id_portion_size=c.id_portion_size
inner join portion_sizes p
	on p.id_portion_size=e.id_portion_size
where m.id_people=people_id and m.consumed=0
and e.id_meal=m.id_meal
order by e.id_people_meals_recipes;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_people_eat_by_person` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_people_eat_by_person`(people_id int)
BEGIN
SELECT r.name 'recipe_name',
r.image,
 p.id_portion_size,p.name 'portion_name',c.amount_currency , e.*,m.id_people,m.served_by,id_people
FROM people_meals_recipes e
inner join people_meals m
	on e.id_meal=e.id_meal 
inner join recipes r
	on r.id_recipe=e.id_recipe
inner join recipe_contribution c
	on c.id_recipe=r.id_recipe and e.id_portion_size=c.id_portion_size
inner join portion_sizes p
	on p.id_portion_size=e.id_portion_size
where m.id_people=people_id and m.consumed=0
and e.id_meal=m.id_meal
order by e.id_people_meals_recipes
limit 20;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_people_meals` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_people_meals`(people_id int)
BEGIN
SELECT m.id_meal
FROM people_meals m
inner join recipes r
	on r.id_recipe=e.id_recipe
inner join recipe_contribution c
	on c.id_recipe=r.id_recipe and e.id_portion_size=c.id_portion_size
inner join portion_sizes p
	on p.id_portion_size=e.id_portion_size
where m.id_people=people_id and m.consumed=0
and e.id_meal=m.id_meal
order by e.id_people_meals_recipes;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Get_recipes` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get_recipes`(badmin bit, person int)
BEGIN
SELECT r.id_recipe,r.id_person,r.name,r.image,r.how_to_make,
r.servings,r.uid_recipe,uid_people,
rt.name `group_name`,r.id_type,p.name `person_name`,f.id_people_favourite,r.show_on_web
FROM antidote.recipes r 
inner join recipe_types rt
	on rt.id_recipie_type=r.id_type
inner join people p
	on p.id_people=r.id_person
left join people_recipe_favourites f
	on f.id_recipe=r.id_recipe and f.id_people=person
where (show_on_web=1 or badmin=1) and temp=0  
order by rt.order_rt asc,r.id_type, id_recipe;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Get_recipes_by_id` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get_recipes_by_id`(recipe_id int)
BEGIN
SELECT r.id_recipe,r.id_person,r.name,r.image,r.how_to_make,
r.servings,r.uid_recipe,uid_people,r.added,
rt.name `group_name`,r.id_type,p.name `person_name`,r.brief,
(Select name from people p2 where p2.id_people=r.id_person) 'recipe_author',
(Select count(*) from reviews rv where rv.id_recipe=r.id_recipe) 'review_count',
IFNULL((Select avg(rv.stars) from reviews rv where rv.id_recipe=r.id_recipe),0) 'avg_review',
rt.name 'recipe_type'
FROM antidote.recipes r 
inner join antidote.recipe_types rt
	on rt.id_recipie_type=r.id_type
inner join antidote.people p
	on p.id_people=r.id_person
where recipe_id=r.id_recipe;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Get_recipes_by_Meal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get_recipes_by_Meal`(meal_id int)
BEGIN
SELECT m.id_meal,served_by,p.id_people,
r.id_recipe,r.name 'recipe_name',
p.name 'person_name',
r.image 'recipe_image',
p.image_path 'person_image',how_to_make,
mr.created,id_people_meals_recipes, 
t.name 'table_name',servings,uid_recipe,uid_people
FROM antidote.people_meals m
inner join people_meals_recipes mr
	on m.id_meal=mr.id_meal
inner join recipes r
	on r.id_recipe=mr.id_recipe
inner join people p
	on p.id_people=m.id_people
inner join serving_tables t
	on t.id_table=m.id_table 
where m.id_meal=meal_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Get_recipes_filter` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get_recipes_filter`(badmin bit, person int, food_filter VARCHAR(50),show_on_web bit)
BEGIN

SELECT r.id_recipe,r.id_person,r.name 'recipe_name',r.image,r.how_to_make,
r.servings,r.uid_recipe,uid_people,
rt.name `group_name`,r.id_type,p.name `person_name`,f.id_people_favourite,show_on_web
FROM recipes r 
inner join recipe_types rt
	on rt.id_recipe_type=r.id_type
inner join people p
	on p.id_people=r.id_person
inner join recipe_foods rf
	on rf.id_recipe = r.id_recipe
inner join food fo
	on fo.id_food=rf.id_food
left join people_recipe_favourites f
	on f.id_recipe=r.id_recipe and f.id_people=person
where (r.show_on_web=1 or badmin=1) and temp=0  
and (r.name like CONCAT('%',food_filter,'%') or rt.name like CONCAT('%',food_filter,'%') or fo.name like CONCAT('%',food_filter,'%'))
group by rt.order_rt,r.id_recipe,r.id_person,r.name,r.image,r.how_to_make,
r.servings,r.uid_recipe,uid_people,group_name,r.id_type,person_name,f.id_people_favourite,show_on_web
order by rt.order_rt asc,r.id_type, id_recipe;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Get_recipes_filter_by_vit` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get_recipes_filter_by_vit`(badmin bit, person int, vitamin_id int,show_on_web bit)
BEGIN

Select p.name 'person_name',a.recipe_name,a.id_recipe,a.id_person,rt.name `group_name`,a.how_to_make,a.id_type,a.servings,a.uid_recipe,uid_people,image,sum(vitamins) total_vitamin
from 
(SELECT r.id_recipe,r.id_person,r.how_to_make,r.id_type,r.servings,r.uid_recipe, r.name 'recipe_name',r.image,rf.qty_grams,
(Select sum(fv.percentage*100/grams_default*rf.qty_grams/r.servings/100) from recipes r2 where r2.id_recipe=r.id_recipe group by grams_default,rf.qty_grams,fv.percentage) 'vitamins'
from food f
inner join food_vitamins fv
 on fv.id_food=f.id_food
inner join vitamins v
 on v.id_vitamin=fv.id_vitamin
inner join recipe_foods rf
on rf.id_food=f.id_food
inner join recipes r
on r.id_recipe=rf.id_recipe
where v.id_vitamin = vitamin_id and r.show_on_web=1
group by r.name,r.image,grams_default,f.id_food,percentage,rf.qty_grams,r.servings,r.id_recipe) a
inner join recipe_types rt on
	a.id_type=rt.id_recipe_type
inner join people p on
	p.id_people=a.id_person
group by a.id_recipe,a.id_person,rt.name,a.how_to_make,a.id_type,a.servings,a.uid_recipe,person_name,image,recipe_name
order by group_name,sum(vitamins) desc;



END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Get_recipe_cache` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Get_recipe_cache`(recipe_id int)
BEGIN
SELECT name,rdi,color,id_vitamin FROM recipes_graph where id_recipe=recipe_id order by rdi desc LIMIT 8;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_recipe_with_contribution` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_recipe_with_contribution`(recipe_id int)
BEGIN
SELECT p.id_portion_size,p.name 'portion_name',c.amount_currency ,c.id_recipe_contribution
FROM antidote.recipe_contribution c
inner join recipes r
	on r.id_recipe=c.id_recipe
inner join portion_sizes p
	on p.id_portion_size=c.id_portion_size
where r.id_recipe=recipe_id
order by id_portion_size;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_reviews` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_reviews`(recipe_id int)
BEGIN
SELECT `id_review`,`stars`,`id_person`,`id_recipe`,`review_text`,`date_reviewed`,image_path
FROM `antidote`.`reviews` r 
inner join people p
on p.id_people=r.id_person
where id_recipe=recipe_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_reviews_by_recipe` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_reviews_by_recipe`(recipe_id int)
BEGIN
SELECT `id_review`,`stars`,`id_person`,`id_recipe`,`review_text`,`date_reviewed`,image_path,p.name,
IFNULL((Select avg(stars) from reviews r2 where r2.id_recipe=recipe_id),0) as avg_star,
IFNULL((Select count(stars) from reviews r2 where r2.id_recipe=recipe_id),0) as review_count
FROM `antidote`.`reviews` r 
inner join people p
on p.id_people=r.id_person
where id_recipe=recipe_id
order by date_reviewed desc; 
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_review_last` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_review_last`(recipe_id int)
BEGIN
SELECT `id_review`,`stars`,`id_person`,`id_recipe`,`review_text`,`date_reviewed`,image_path
FROM `antidote`.`reviews` r 
inner join people p
on p.id_people=r.id_person
where id_recipe=recipe_id
order by date_reviewed desc limit 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_website_details` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_website_details`(url varchar(255))
BEGIN
SELECT *
FROM restaurant r where r.url=url;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Insert_people_eat` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Insert_people_eat`(iMeal int, id_recipe int, id_portion_size smallint, var_comment varchar(256))
BEGIN

INSERT INTO `antidote`.`people_meals_recipes`
(`id_meal`,`id_recipe`,`id_portion_size`,`comment`)
Select iMeal,id_recipe,id_portion_size,var_comment;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Insert_people_meal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Insert_people_meal`(id_person int, id_helper int, var_comment varchar(2048),table_id int)
BEGIN
INSERT INTO `antidote`.`people_meals`
(`served_by`,
`id_people`,`comment`,`id_table`)
Select id_helper,id_person,var_comment,table_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_recipe_contribution` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_recipe_contribution`(portion_id smallint,currency_amount DOUBLE, recipe_id int, food_id int)
BEGIN
INSERT INTO `antidote`.`recipe_contribution`
(`id_recipe`,
`amount_currency`,
`id_portion_size`,
`id_food`)
Select recipe_id,
currency_amount,
portion_id,
food_id;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Insert_Temp_Food` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Insert_Temp_Food`(IN idperson int)
BEGIN
INSERT INTO `antidote`.`food`
(`name`,
`Intro`,
`Image_path`,
`Description`,
`wh_id`,
`default_unit`,
`grams_default`,
`visible`,
`id_person_add`)
VALUES
('unamed',
'',
'',
'',
0,
'',
100,
0,
idperson);

   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Insert_Temp_Recipe` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Insert_Temp_Recipe`(IN idperson int)
BEGIN
INSERT INTO `recipes`
(`id_person`,
`name`,
`image`,
`how_to_make`,
`id_type`)
VALUES
(idperson,'unamed','','',1);
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `People_Add` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `People_Add`(IN userName VARCHAR(50),IN emailAdd VARCHAR(50),IN passWrd varchar(20))
BEGIN
INSERT INTO `antidote`.`people`
(`email`,`password`,`name`)
Select 
emailAdd,passWrd,userName;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `People_Add_FB` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `People_Add_FB`(IN userName VARCHAR(50),IN emailAdd VARCHAR(50),IN oauth_uid varchar(30),IN rest_id int, in Image_url varchar(200))
BEGIN
set @passWrd= left(uuid(),6);
set @uid_people= left(uuid(),8);
set @auto= left(uuid(),32);
INSERT INTO `people`
(`email`,`password`,`name`,`fb_id`,oauth_provider,restaurant,image_path,uid_people,auto_login)
Select 
emailAdd,@passWrd,userName,oauth_uid,'facebook',rest_id,Image_url,@uid_people,@auto;

   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `People_Add_Google` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `People_Add_Google`(IN userName VARCHAR(50),IN emailAdd VARCHAR(50),IN oauth_uid varchar(30),IN rest_id int, in Image_url varchar(200))
BEGIN
set @passWrd= left(uuid(),6);
set @uid_people= left(uuid(),8);
set @auto= left(uuid(),32);
INSERT INTO `people`
(`email`,`password`,`name`,`oauth_uid`,oauth_provider,restaurant,image_path,uid_people,auto_login)
Select 
emailAdd,@passWrd,userName,oauth_uid,'google',rest_id,Image_url,@uid_people,@auto;

   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `People_Check` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `People_Check`(IN emailAdd VARCHAR(50))
BEGIN
SELECT `people`.`id_people`,
    `people`.`email`,
    `people`.`password`,
    `people`.`name`,
    `people`.`image_path`
FROM `antidote`.`people` where email=emailAdd ;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `People_login` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `People_login`(IN emailAdd VARCHAR(50),IN passWrd varchar(20))
BEGIN
SELECT `people`.`id_people`,
    `people`.`email`,
    `people`.`password`,
    `people`.`name`,
    `people`.`image_path`,
    uid_people,about_me,can_authorize
FROM `antidote`.`people` where email=emailAdd and password=passWrd;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `People_login_by_Auto` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `People_login_by_Auto`(IN  auto_code char(36) )
BEGIN
SELECT `people`.`id_people`,
    `people`.`email`,
    `people`.`password`,
    `people`.`name`,
    `people`.`image_path`,
    uid_people,about_me,can_authorize
FROM `antidote`.`people` where auto_login=auto_code;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `People_login_by_ID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `People_login_by_ID`(IN id int)
BEGIN
SELECT `people`.`id_people`,
    `people`.`email`,
    `people`.`password`,
    `people`.`name`,
    `people`.`image_path`,
    uid_people,about_me,can_authorize
FROM `antidote`.`people` where id_people=id;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `P_Update_TEXT` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `P_Update_TEXT`()
BEGIN
update recipes set how_to_make=replace(how_to_make,'Â','')  where id_recipe>0
and how_to_make like '%Â%';
update recipes set how_to_make=replace(how_to_make,'Ãƒâ€šÃ‚°','')  where id_recipe>0;
update recipes set how_to_make=replace(how_to_make,'ÃƒÆ’Ã','')  where id_recipe>0;
update food set Description=replace(Description,'â€”',' ')  where id_food>0;
update food set intro=replace(intro,'â€”',' ')  where id_food>0;
update food set intro=replace(intro,'Â',' ')  where id_food>0;
update food set Description=replace(Description,'Â',' ')  where id_food>0;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Recipes_By_ID` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Recipes_By_ID`(IN idrecipe int)
BEGIN
SELECT * from recipe_foods rf
inner join food f
	on f.id_food=rf.id_food
 where id_recipe=idrecipe
order by qty_grams desc,display_order;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Recipes_By_Person` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Recipes_By_Person`(IN idperson int)
BEGIN
SELECT `recipes`.`id_recipe`,
    `recipes`.`id_person`,
    `recipes`.`name`,
    `recipes`.`image`,
    `recipes`.`how_to_make`,
    `recipes`.`id_type`,
    `recipes`.`added`
FROM `antidote`.`recipes` where id_person=idperson;
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Recipe_Vitamins` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Recipe_Vitamins`(id_recipe int)
BEGIN
Select 
-- r.id_recipe,p.name,r.name,
v2.id_vitamin,v2.name,sum(rf.qty_grams/r.servings/grams_default*percentage) 'net_RDI',v2.color ,v2.health_benefits
from recipes r
inner join recipe_foods rf
	on rf.id_recipe=r.id_recipe
inner join food f
	on f.id_food=rf.id_food
inner join food_vitamins v
	on v.id_food=f.id_food
inner join vitamins v2
	on v2.id_vitamin=v.id_vitamin
inner join people p
	on p.id_people=r.id_person
where r.id_recipe=id_recipe
    group by v2.name
order by net_rdi desc;

    
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Recipe_Vitamins_Avg_by_days` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Recipe_Vitamins_Avg_by_days`(days int,person_id int)
BEGIN
Select 
-- r.id_recipe,p.name,r.name,
v2.id_vitamin,v2.name,sum(rf.qty_grams/r.servings/grams_default*percentage)/days 'net_RDI',v2.color  
from recipes r
inner join recipe_foods rf
	on rf.id_recipe=r.id_recipe
inner join food f
	on f.id_food=rf.id_food
inner join food_vitamins v
	on v.id_food=f.id_food
inner join vitamins v2
	on v2.id_vitamin=v.id_vitamin
inner join people p
	on p.id_people=r.id_person
inner join people_meals_recipes pe
	on pe.id_recipe=r.id_recipe 
inner join people_meals m
	on pe.id_meal=m.id_meal
where m.id_people=person_id and datediff(now(),pe.date_served)<=days
    group by v2.name
order by net_rdi desc;
    
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Recipe_Vitamins_cache` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Recipe_Vitamins_cache`(id_recipe_var int)
BEGIN
delete from `antidote`.`recipes_graph` where id_recipe=id_recipe_var;
INSERT INTO `antidote`.`recipes_graph`
(
`id_vitamin`,
`name`,
`RDI`,
`color`,
`id_recipe`)

Select 
-- r.id_recipe,p.name,r.name,
v2.id_vitamin,v2.name,round(sum(rf.qty_grams/r.servings/grams_default*percentage),0) 'net_RDI',v2.color,  id_recipe_var
from recipes r
inner join recipe_foods rf
	on rf.id_recipe=r.id_recipe
inner join food f
	on f.id_food=rf.id_food
inner join food_vitamins v
	on v.id_food=f.id_food
inner join vitamins v2
	on v2.id_vitamin=v.id_vitamin
inner join people p
	on p.id_people=r.id_person
where r.id_recipe=id_recipe_var
    group by v2.name,v2.id_vitamin
order by net_rdi desc;

    
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Recipe_Vitamins_Meals` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Recipe_Vitamins_Meals`(id_meal int)
BEGIN
Select 
-- r.id_recipe,p.name,r.name,
v2.id_vitamin,v2.name,sum(rf.qty_grams/r.servings/grams_default*percentage) 'net_RDI',v2.color  
from recipes r
inner join recipe_foods rf
	on rf.id_recipe=r.id_recipe
inner join food f
	on f.id_food=rf.id_food
inner join food_vitamins v
	on v.id_food=f.id_food
inner join vitamins v2
	on v2.id_vitamin=v.id_vitamin
inner join people p
	on p.id_people=r.id_person
inner join people_meals_recipes pe
	on pe.id_recipe=r.id_recipe 
inner join people_meals m
	on pe.id_meal=m.id_meal
where m.consumed=1
and m.id_meal
    group by v2.name
order by net_rdi desc
limit 5;
    
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Recipe_Vitamins_people_eat` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Recipe_Vitamins_people_eat`(id_meal int)
BEGIN
Select 
-- r.id_recipe,p.name,r.name,
v2.id_vitamin,v2.name,sum(rf.qty_grams/r.servings/grams_default*percentage) 'net_RDI',v2.color  
from recipes r
inner join recipe_foods rf
	on rf.id_recipe=r.id_recipe
inner join food f
	on f.id_food=rf.id_food
inner join food_vitamins v
	on v.id_food=f.id_food
inner join vitamins v2
	on v2.id_vitamin=v.id_vitamin
inner join people p
	on p.id_people=r.id_person
inner join people_meals_recipes pe
	on pe.id_recipe=r.id_recipe 
inner join people_meals m
	on pe.id_meal=m.id_meal
where m.id_meal=id_meal
    group by v2.name
order by net_rdi desc;

    
   END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Update_people_eat` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Update_people_eat`(id_person int)
BEGIN
update antidote.people_meals_recipes r join antidote.people_meals m 
on m.id_meal=r.id_meal
set r.consumed=1,r.consumption_date=now()
where m.id_people=id_person and r.consumed=0;
update antidote.people_meals set consumed=1,consumption_date=now()
where id_people=id_person and consumed=0;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `Update_people_meal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Update_people_meal`(meal_id int, table_id int)
BEGIN
update `antidote`.`people_meals`
set id_table=table_id
where id_meal=meal_id;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-06-06 13:43:20
