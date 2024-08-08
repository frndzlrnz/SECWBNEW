CREATE DATABASE  IF NOT EXISTS `dbresto` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `dbresto`;
-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: localhost    Database: dbresto
-- ------------------------------------------------------
-- Server version	8.0.34

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
-- Table structure for table `tbladmin`
--

DROP TABLE IF EXISTS `tbladmin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbladmin` (
  `username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbladmin`
--

LOCK TABLES `tbladmin` WRITE;
/*!40000 ALTER TABLE `tbladmin` DISABLE KEYS */;
INSERT INTO `tbladmin` VALUES ('admin','1234'),('admin2','1234');
/*!40000 ALTER TABLE `tbladmin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcombo`
--

DROP TABLE IF EXISTS `tblcombo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblcombo` (
  `main` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `side` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `drink` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `discount` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcombo`
--

LOCK TABLES `tblcombo` WRITE;
/*!40000 ALTER TABLE `tblcombo` DISABLE KEYS */;
INSERT INTO `tblcombo` VALUES ('Fish','Asd','dsa',25);
/*!40000 ALTER TABLE `tblcombo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblfood`
--

DROP TABLE IF EXISTS `tblfood`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblfood` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `quantity` int NOT NULL,
  `image` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblfood`
--

LOCK TABLES `tblfood` WRITE;
/*!40000 ALTER TABLE `tblfood` DISABLE KEYS */;
INSERT INTO `tblfood` VALUES (1,'Steak','Mains',900,4,'steak.png'),(2,'Salmon','Mains',850,1,'salmon.png'),(3,'Chicken','Mains',300,3,'chicken.png'),(4,'Baked Potato','Sides',80,14,'bakedpot.png'),(5,'Mashed Potato','Sides',75,10,'mashedpot.png'),(6,'Steamed Vegetables','Sides',50,4,'steamedveg.png'),(7,'Iced Tea','Drink',55,5,'icedtea.png'),(8,'Root Beer','Drink',60,23,'rootbeer.png'),(9,'Water','Drink',25,3,'water.png'),(10,'Barbeque','Mains',150,5,'barbeque.jpg');
/*!40000 ALTER TABLE `tblfood` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblftrans`
--

DROP TABLE IF EXISTS `tblftrans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblftrans` (
  `tID` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment` decimal(10,2) NOT NULL,
  `totaldiscount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`tID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblftrans`
--

LOCK TABLES `tblftrans` WRITE;
/*!40000 ALTER TABLE `tblftrans` DISABLE KEYS */;
INSERT INTO `tblftrans` VALUES (1,'2023-08-04','Juan Dela Cruz',1030.00,1100.00,NULL),(4,'2023-08-05','Food Lover',387.00,500.00,43.00),(5,'2023-08-05','miles morales',1040.00,1500.00,0.00),(6,'2023-08-05','steaklover',858.50,1000.00,151.50),(7,'2023-08-05','someone',858.50,859.00,151.50),(8,'2023-08-05','Phineas Flynn',975.00,1000.00,0.00),(9,'2023-08-05','Parry the Platypus',391.50,1000.00,43.50),(10,'2023-08-05','hello',2551.50,3000.00,283.50);
/*!40000 ALTER TABLE `tblftrans` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-08-05 23:35:05
