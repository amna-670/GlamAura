-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: glamaura
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$GlamAura','Admin User','2025-09-08 05:31:07');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cart_id` (`cart_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (16,35,7,1,'2025-09-19 05:44:48','2025-09-19 05:44:48');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (35,2,'ctf9keech1aj9shl64e881j4gd','2025-09-19 05:44:48','2025-09-19 05:44:48');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Cosmetics','1757102063_7224.jpg','Cosmetics are products designed to enhance beauty, care for the skin, and express personal style.','2025-09-05 19:54:23'),(2,'Jewelry','1757102086_3227.jpg','Jewelry is a timeless form of adornment that symbolizes style, culture and individuality.','2025-09-05 19:54:46');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_form`
--

DROP TABLE IF EXISTS `contact_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_form`
--

LOCK TABLES `contact_form` WRITE;
/*!40000 ALTER TABLE `contact_form` DISABLE KEYS */;
INSERT INTO `contact_form` VALUES (1,'amnaidrees670@gmail.com','hey this is me shuja','2025-09-19 05:42:26');
/*!40000 ALTER TABLE `contact_form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(6) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `work_phone` varchar(20) DEFAULT NULL,
  `cell_no` varchar(20) NOT NULL,
  `dob` date DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,1,'Gosh Pressed Powder 03 Warm Sand','1757239883_3108.jpg',1,'Gosh Pressed Powder 03 Warm Sand',459,'2025-09-07 10:11:23'),(2,2,'isra kara','1757239914_5871.jpg',1,'isra kara',500,'2025-09-07 10:11:54'),(3,1,'Essence Bloom Wings eyeshadow pallette-04','1757241004_1407.jpg',1,'A dreamy palette of soft pinks and mauves for creating romantic, ethereal eye looks.',4567,'2025-09-07 10:30:04'),(4,1,'Gabrini Express Pencil # 100','1757241055_3662.jpg',1,'A versatile, deep black eyeliner pencil for intense definition.',459,'2025-09-07 10:30:55'),(5,1,'Gabrini Nude Matte Lipstick B 07','1757241097_5589.jpg',1,'A warm, nude matte lipstick with a velvety finish.',799,'2025-09-07 10:31:37'),(6,1,'Herbion Rosewater','1757241148_9513.jpg',1,'A refreshing facial mist infused with pure rosewater to hydrate and revitalize the skin.',850,'2025-09-07 10:32:28'),(7,1,'Kryolan High Gloss Brilliant Lip Shine','1757241201_5931.jpg',1,'An ultra-glossy, high-shine lip gloss for a dazzling, luminous finish.',2489,'2025-09-07 10:33:21'),(8,1,'LA Girl Lip Attraction 2 Lipstick - Peony','1757241603_2037.jpg',1,'A creamy, medium pink lipstick with a satin finish.',435,'2025-09-07 10:40:03'),(9,1,'Lafz Halal Anti Pollution CC Cream','1757241648_5044.jpg',1,'A multifunctional halal CC cream that protects against pollution while evening skin tone.',1899,'2025-09-07 10:40:48'),(10,2,'Adjustable Adult Bangle-61 ','1757241695_7417.jpg',1,'An elegantly designed, adjustable bangle bracelet for a customizable fit.',5290,'2025-09-07 10:41:35'),(11,2,'DIGITAL EARRING-88 (Maroon)','1757241771_3392.jpg',1,'Maroon-colored digital earrings with a modern, geometric design.',1794,'2025-09-07 10:42:51'),(12,2,'Four Layer Pendant-02','1757241810_5177.jpg',1,'A sophisticated four-layer pendant necklace for a bold, statement look.',799,'2025-09-07 10:43:30'),(13,2,'Locket set','1757241885_4671.jpg',1,'A classic jewelry set featuring a delicate, ornate locket and matching chain.',2532,'2025-09-07 10:44:45'),(14,2,'Madrasi Openable Kara-05','1757241931_4318.jpg',1,'An openable, traditional bangle with intricate Madrasi design.',8560,'2025-09-07 10:45:31'),(15,2,'NATASHA SET Maroon','1757241982_2866.jpg',1,'A maroon-colored jewelry set featuring a necklace and matching earrings.',2899,'2025-09-07 10:46:22'),(17,1,'Lurella Mink Eyelashes - Quirky','1757911171_2469.jpg',1,'Soft, reusable mink lashes with a quirky edge',679,'2025-09-15 04:39:31'),(18,1,'Makeup Revolution Lace Baking Powder','1757911238_1665.jpg',1,'Lock in your look with soft-focus perfection.',899,'2025-09-15 04:40:38'),(19,1,'Max Factor Miracle Pure ','1757911299_7274.jpg',1,'Versatile nude palettes infused with skin-loving formulas.',4150,'2025-09-15 04:41:39'),(20,1,'Max Factor Volumizing Lip Gloss','1757911368_4309.jpg',1,'Gloss that gives volume, comfort, and all-day shine.',1290,'2025-09-15 04:42:48'),(21,1,'Revolution Blusher Reloaded','1757911430_2521.jpg',1,'Buildable blush with a radiant, natural finish.',999,'2025-09-15 04:43:50'),(22,2,'Openable Kara-167','1757911526_5829.jpg',1,'Classic kara design crafted for everyday wear and comfort.',2999,'2025-09-15 04:45:26'),(23,2,'Sabrina Necklace','1757911588_6005.jpg',1,'Minimal design with maximum charm.',3789,'2025-09-15 04:46:28'),(24,2,'Zircon Earring-114','1757911742_9500.jpg',1,'Sparkling zircon studs that shine with timeless elegance.',499,'2025-09-15 04:49:02'),(25,2,'Zircon Fancy Studs-162','1757911805_9755.jpg',1,'A modern take on timeless zircon sparkle.',990,'2025-09-15 04:50:05'),(26,2,'Zircon Necklace-274','1757911861_7849.jpg',1,'Classic beauty meets modern charm in every zircon detail.',1299,'2025-09-15 04:51:01'),(27,2,'Zircon Locket Set-43 Golden','1757911924_9068.jpg',1,'Perfectly paired golden set for effortless glamour.',2799,'2025-09-15 04:52:04'),(28,2,'Zircon Ring-58 Silver','1757911985_1767.jpg',1,'Timeless shine in a classic silver zircon design.',699,'2025-09-15 04:53:05'),(29,2,'Zircon Hoop Bali-11 ','1757912041_6240.jpg',1,'A timeless hoop design elevated with zircon brilliance.',1999,'2025-09-15 04:54:01'),(30,2,'Zircon Ring-91 Silver','1757912104_4703.jpg',1,'Bold design meets timeless zircon sparkle.',699,'2025-09-15 04:55:04'),(31,1,'Revolution Pore Blur Primer','1757912169_7178.jpg',1,'Creates a soft-focus finish for makeup that lasts.',1899,'2025-09-15 04:56:09'),(32,1,'Revolution Pro Eye Elements - Core','1757912209_5069.jpg',1,'Prime, perfect, and intensify your eye makeup with ease.',100,'2025-09-15 04:56:49');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` text DEFAULT NULL,
  `work_phone` varchar(20) DEFAULT NULL,
  `cell_no` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Amna','amnaidrees6@gmail.com','$2y$10$saXQS467pygeUs6ZAYoFS.CEy57QClrFGriupYFMsTKk4fck2lHuq','2025-09-12 18:03:29','sharah-e- faisal, karachi ',NULL,'0324567978','2002-06-04','Regular Customer',NULL),(2,'Admin','admin@gmail.com','$2y$10$f4EeDkUwuTM.B1Y3IY4jBuzgoi2CjrM6LXCH5A2tDatKXZ./aAQQa','2025-09-16 18:34:19','sharah-e-faisal','03245678978','03245678978','2004-09-03','Cosmetics',NULL);
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

-- Dump completed on 2025-09-19 11:36:31
