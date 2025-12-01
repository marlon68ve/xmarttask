/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.10-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: u991737198_tas2023
-- ------------------------------------------------------
-- Server version	10.11.10-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(256) NOT NULL,
  `hash` varchar(256) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT 0,
  `user_type` int(11) DEFAULT 1,
  `userpicture` varchar(100) NOT NULL,
  `numero` int(11) NOT NULL,
  `amigo` varchar(40) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--
-- WHERE:  id > 5 AND lname = "Ortiz"

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(6,'marlonsr','Marlon','Ortiz','marlon.ortiz@outlook.com','$2y$10$LVfoOSoQgiUu/Y3opQM7TO8FZT.xP8XcMnNlW/UQ1fx6vaVYpXBBK','ff2281cdce307582ee31f2cb4cbeced5',1,10,'ui/img/foto carnet para web Marlon Sr.jpg',0,'','2023-12-19 13:57:32','2023-12-22 00:16:41'),
(25,'miguel','Miguel','Ortiz','miguelr.ortiz@gmail.com','$2y$10$mAXp68z9SDn/BRiUCyrTzed4xHHghresqrcuJV39yyyrKCBe2StAa','e0a96a6e4c9cfc7c094409f4bb346807',1,1,'ui/img/',0,'','2024-11-05 09:58:01','2024-11-21 09:58:35'),
(290,'Migvir','Migvir','Ortiz ','migvir@hotmail.com','$2y$10$psXyw2FYH1yZtoltTpriC.K5NKhYoUGZqWyFEM0ZIJE9S4XvJOVCW','c466e48c183532e2bd16aefc7d486c2b',1,1,'',0,'','2024-12-15 00:50:34','2024-12-15 00:52:42');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-19 17:52:11
