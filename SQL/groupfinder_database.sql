CREATE DATABASE  IF NOT EXISTS `groupfinder` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `groupfinder`;
-- MySQL dump 10.13  Distrib 5.6.17, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: groupfinder
-- ------------------------------------------------------
-- Server version	5.6.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_verification_request`
--

DROP TABLE IF EXISTS `admin_verification_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_verification_request` (
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `file` blob NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banks` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banks`
--

LOCK TABLES `banks` WRITE;
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
INSERT INTO `banks` VALUES 
(1,'Seleccione un banco'),
(2,'BANCO SANTANDER'),
(3,'BANCO SANTANDER BANEFE'),
(4,'BBVA'),
(5,'CORPBANCA'),
(6,'BCI-TBANC'),
(7,'BANCO FALABELLA'),
(8,'BANCO ITAU'),
(9,'BANCO DE CHILE / EDWARDS CITI'),
(10,'BANCOESTADO'),
(11,'BANCO BICE'),
(12,'BANCO SECURITY'),
(13,'BANCO CONSORCIO'),
(14,'BANCO INTERNACIONAL'),
(15,'SCOTIABANK');
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `origin` int(11) NOT NULL,
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `text` varchar(200) NOT NULL,
  `hidden` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`comment_id`),
  KEY `fk_user_id_idx` (`user_id`),
  CONSTRAINT `fk_comments_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversations` (
  `conversation_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_a` int(11) NOT NULL,
  `user_b` int(11) NOT NULL,
  `archived` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`conversation_id`),
  KEY `fk_from_id_idx` (`user_a`),
  KEY `fk_to_id_idx` (`user_b`),
  CONSTRAINT `fk_conversation_user_b` FOREIGN KEY (`user_b`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_conversations_user_a` FOREIGN KEY (`user_a`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `conversations_messages`
--

DROP TABLE IF EXISTS `conversations_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversations_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `sent_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`),
  KEY `fk_conversation_id_idx` (`conversation_id`),
  KEY `fk_sender_id_idx` (`sender_id`),
  CONSTRAINT `fk_conversation_msg_id` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`conversation_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_conversation_msg_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(255) NOT NULL,
  PRIMARY KEY (`country_id`),
  UNIQUE KEY `country_name_UNIQUE` (`country_name`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'Albania'),
(2,'Alemania'),
(3,'Arabia Saudita'),
(4,'Argentina'),
(5,'Armenia'),
(6,'Australia'),
(7,'Austria'),
(8,'Azerbaiyán'),
(9,'Bahamas'),
(10,'Bangladés'),
(11,'Baréin'),
(12,'Bélgica'),
(13,'Belice'),
(14,'Bielorrusia'),
(15,'Bolivia'),
(16,'Bosnia y Herzegovina'),
(17,'Brasil'),
(18,'Bulgaria'),
(19,'Camboya'),
(20,'Canadá'),
(21,'Catar'),
(22,'Chile'),
(23,'Chipre'),
(24,'Colombia'),
(25,'Corea del Sur'),
(26,'Costa Rica'),
(27,'Croacia'),
(28,'Cuba'),
(29,'Dinamarca'),
(30,'Ecuador'),
(31,'Egipto'),
(32,'El Salvador'),
(33,'Emiratos Árabes Unidos'),
(34,'Eslovaquia'),
(35,'Eslovenia'),
(36,'España'),
(37,'Estados Unidos'),
(38,'Estonia'),
(39,'Etiopía'),
(40,'Filipinas'),
(41,'Finlandia'),
(42,'Francia'),
(43,'Georgia'),
(44,'Ghana'),
(45,'Grecia'),
(46,'Guatemala'),
(47,'Guyana'),
(48,'Haití'),
(49,'Honduras'),
(50,'Hong Kong'),
(51,'Hungría'),
(52,'India'),
(53,'Indonesia'),
(54,'Irak'),
(55,'Irán, República Islámica de'),
(56,'Irlanda'),
(57,'Islandia'),
(58,'Islas Caimán'),
(59,'Israel'),
(60,'Italia'),
(61,'Jamaica'),
(62,'Japón'),
(63,'Jordania'),
(64,'Kazajistán'),
(65,'Kenia'),
(66,'Kuwait'),
(67,'Laos'),
(68,'Letonia'),
(69,'Líbano'),
(70,'Lituania'),
(71,'Luxemburgo'),
(72,'Malasia'),
(73,'Maldivas'),
(74,'Malta'),
(75,'Marruecos'),
(76,'México'),
(77,'Mongolia'),
(78,'Nicaragua'),
(79,'Nigeria'),
(80,'Noruega'),
(81,'Nueva Zelandia'),
(82,'Omán'),
(83,'Países Bajos'),
(84,'Pakistán'),
(85,'Panamá'),
(86,'Paraguay'),
(87,'Perú'),
(88,'Polonia'),
(89,'Portugal'),
(90,'Reino Unido'),
(91,'República Checa'),
(92,'República Dominicana'),
(93,'Rumania'),
(94,'Rusia'),
(95,'Serbia'),
(96,'Singapur'),
(97,'Sri Lanka'),
(98,'Sudáfrica'),
(99,'Suecia'),
(100,'Suiza'),
(101,'Surinam'),
(102,'Tailandia'),
(103,'Taiwán'),
(104,'Tanzania'),
(105,'Trinidad y Tobago'),
(106,'Túnez'),
(107,'Turquía'),
(108,'Ucrania'),
(109,'Uganda'),
(110,'Uruguay'),
(111,'Uzbekistán'),
(112,'Venezuela'),
(113,'Vietnam'),
(114,'Zambia'),
(115,'Zimbabue');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `notificacion_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `notif_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `text` varchar(200) NOT NULL,
  `read` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`notificacion_id`),

  KEY `fk_user_id_idx` (`user_id`),

  CONSTRAINT `fk_notifications_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `summary` varchar(200) NOT NULL DEFAULT '',
  `problem` text NOT NULL,
  `solution` text NOT NULL,
  `extra_info` text,
  `target_group` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT '2',
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `limit_date` timestamp NULL DEFAULT NULL,
  `category` int(11) NOT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `preview_hash` varchar(40) DEFAULT NULL,
  `funding_mode` int(11) NOT NULL DEFAULT '1',
  `bank_id` int(11) NOT NULL DEFAULT '1',
  `bank_acc_rut` varchar(10) DEFAULT NULL,
  `bank_acc_number` varchar(50) DEFAULT NULL,
  `bank_acc_type` int(11) NOT NULL DEFAULT '1',
  `bank_acc_name` varchar(100) DEFAULT NULL,
  `bank_acc_email` varchar(200) DEFAULT NULL,
  `rewards_activated` tinyint(1) NOT NULL DEFAULT '0',
  `funding_goal` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`project_id`,`owner_id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `preview_hash_UNIQUE` (`preview_hash`),
  KEY `fk_owner_id_idx` (`owner_id`),
  KEY `fk_projects_1_idx` (`category`),
  KEY `fk_projects_status_idx` (`status`),
  KEY `fk_projects_bank_id_idx` (`bank_id`),
  CONSTRAINT `fk_projects_bank_id` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`bank_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_projects_category` FOREIGN KEY (`category`) REFERENCES `projects_categories` (`category_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_projects_owner_id` FOREIGN KEY (`owner_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_projects_status` FOREIGN KEY (`status`) REFERENCES `projects_status` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



-- Table structure for table `projects_applications`
--

DROP TABLE IF EXISTS `projects_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_applications` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(200) NOT NULL,
  `application_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`application_id`),
  KEY `fk_applications_project_id_idx` (`project_id`) USING BTREE,
  KEY `fk_applications_user_id_idx` (`user_id`) USING BTREE,
  KEY `fk_applications_role_id_idx` (`role_id`) USING BTREE,
  CONSTRAINT `fk_applications_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_applications_role_id` FOREIGN KEY (`role_id`) REFERENCES `projects_roles` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_applications_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `projects_categories`
--

DROP TABLE IF EXISTS `projects_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects_categories`
--

LOCK TABLES `projects_categories` WRITE;
/*!40000 ALTER TABLE `projects_categories` DISABLE KEYS */;
INSERT INTO `projects_categories` VALUES (1,'Arte','Pintura, escultura, etc.'),
(2,'Construcción','Proyectos sociales de construcción'),
(3,'Gastronomía','Proyectos gastronómicos de todo tipo'),
(4,'Literatura','Libros, revistas, historietas, etc.'),
(5,'Medicina','Medicina tradicional o alternativa'),
(6,'Medio Ambiente','Eficiencia energética, reciclaje, conservación de flora y fauna, etc.'),
(7,'Música','Bandas musicales, instrumentos, etc.'),
(8,'Teatro','Obras teatrales'),(9,'Tecnología','Productos electrónicos, software, videojuegos, etc.');
/*!40000 ALTER TABLE `projects_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects_costs`
--

DROP TABLE IF EXISTS `projects_costs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_costs` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cost` float NOT NULL,
  `amount` int(11) NOT NULL,
  `detail` text NOT NULL,
  `required` tinyint(1) NOT NULL,
  PRIMARY KEY (`resource_id`),
  UNIQUE KEY `cost_id_UNIQUE` (`resource_id`),
  KEY `fk_projects_costs_1_idx` (`project_id`),
  CONSTRAINT `fk_projects_costs_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `projects_faq`
--

DROP TABLE IF EXISTS `projects_faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_faq` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `question` varchar(200) NOT NULL,
  `answer` varchar(250) NOT NULL,
  `list_number` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`question_id`),
  KEY `fk_project_faq_id_idx` (`project_id`),
  CONSTRAINT `fk_project_faq_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `projects_members`
--

DROP TABLE IF EXISTS `projects_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_members` (
  `project_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `join_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role_id` int(11) NOT NULL,
  `leader` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`project_id`,`member_id`),
  KEY `fk_members_member_id_idx` (`member_id`),
  KEY `fk_members_role_id_idx` (`role_id`),
  CONSTRAINT `fk_members_member_id` FOREIGN KEY (`member_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_members_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_members_role_id` FOREIGN KEY (`role_id`) REFERENCES `projects_roles` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `projects_picture`
--

DROP TABLE IF EXISTS `projects_picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_picture` (
  `project_id` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `img_src` varchar(255) NOT NULL,
  PRIMARY KEY (`size`,`project_id`),
  KEY `fk_projects_picture_project_id_idx` (`project_id`),
  CONSTRAINT `fk_projects_picture_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `projects_rewards`
--

DROP TABLE IF EXISTS `projects_rewards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_rewards` (
  `reward_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `min_amount` float NOT NULL,
  `delivery_type` int(11) NOT NULL DEFAULT '1',
  `delivery_date` datetime DEFAULT NULL,
  `delivery_notes` varchar(200) DEFAULT NULL,
  `limit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reward_id`),
  KEY `fk_project_rewards_1_idx` (`project_id`),
  CONSTRAINT `fk_project_rewards_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `projects_roles`
--

DROP TABLE IF EXISTS `projects_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `role_description` text NOT NULL,
  `vacants_amount` int(11) NOT NULL DEFAULT '0',
  `vacants_used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_id`),
  KEY `fk_role_id_idx` (`role_name`,`project_id`),
  KEY `fk_vacants_project_id` (`project_id`),
  CONSTRAINT `fk_vacants_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `projects_status`
--

DROP TABLE IF EXISTS `projects_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(45) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects_status`
--

LOCK TABLES `projects_status` WRITE;
/*!40000 ALTER TABLE `projects_status` DISABLE KEYS */;
INSERT INTO `projects_status` VALUES 
(1,'Abandonado'),
(2,'En edición'),
(3,'En crecimiento'),
(4,'En ejecución'),
(5,'Finalizado');
/*!40000 ALTER TABLE `projects_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects_transactions`
--

DROP TABLE IF EXISTS `projects_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects_transactions` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `backer_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `reward_id` int(11) DEFAULT NULL,
  `payment_method` int(11) DEFAULT NULL,
  `payment_amount` float NOT NULL DEFAULT '0',
  `payment_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `backer_email` varchar(255) NOT NULL,
  PRIMARY KEY (`trans_id`),
  KEY `fk_transactions_project_id_idx` (`project_id`),
  KEY `fk_transactions_backer_id_idx` (`backer_id`),
  CONSTRAINT `fk_transactions_backer_id` FOREIGN KEY (`backer_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_transactions_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(8) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `sex` tinyint(4) DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `title_validated` tinyint(1) NOT NULL DEFAULT '0',
  `country` int(11) NOT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `locked` bit(1) NOT NULL DEFAULT b'1',
  `hidden_profile` bit(1) NOT NULL DEFAULT b'0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reference` varchar(45) NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `about` text,
  `keeplogged_id` varchar(64) DEFAULT NULL,
  `keeplogged_token` varchar(40) DEFAULT NULL,
  `bank_acc_rut` varchar(10) DEFAULT NULL,
  `bank_acc_number` varchar(50) DEFAULT NULL,
  `bank_acc_type` int(11) NOT NULL DEFAULT '1',
  `bank_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `reference_UNIQUE` (`reference`),
  UNIQUE KEY `keeplogged_id_UNIQUE` (`keeplogged_id`),
  UNIQUE KEY `keeplogged_token_UNIQUE` (`keeplogged_token`),
  KEY `fk_country_id_idx` (`country`),
  CONSTRAINT `fk_country_id` FOREIGN KEY (`country`) REFERENCES `countries` (`country_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `users_activation`
--

DROP TABLE IF EXISTS `users_activation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_activation` (
  `user_id` int(11) NOT NULL,
  `activation_code` varchar(10) NOT NULL,
  `creation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `activated` bit(1) NOT NULL DEFAULT b'0',
  `activated_time` timestamp NULL DEFAULT NULL,
  `code_hash` varchar(40) NOT NULL,
  KEY `fk_activation_user_id_idx` (`user_id`),
  CONSTRAINT `fk_activation_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `users_activity`
--

DROP TABLE IF EXISTS `users_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_activity` (
  `user_id` int(11) NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `object_type` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `action` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_activity_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `users_friends`
--

DROP TABLE IF EXISTS `users_friends`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_friends` (
  `relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_a` int(11) NOT NULL,
  `user_b` int(11) NOT NULL,
  `confirmation_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `relation_status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`relation_id`),
  KEY `fk_users_friends_user_a_idx` (`user_a`),
  KEY `fk_users_friends_user_b_idx` (`user_b`),
  CONSTRAINT `fk_users_friends_user_a` FOREIGN KEY (`user_a`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_friends_user_b` FOREIGN KEY (`user_b`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `users_profile_pictures`
--

DROP TABLE IF EXISTS `users_profile_pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_profile_pictures` (
  `user_id` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `img_src` varchar(255) NOT NULL,
  PRIMARY KEY (`size`,`user_id`),
  UNIQUE KEY `source_UNIQUE` (`img_src`),
  KEY `fk_pictures_user_id_idx` (`user_id`),
  CONSTRAINT `fk_pictures_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
