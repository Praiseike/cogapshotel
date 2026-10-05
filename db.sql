-- MySQL dump 10.13  Distrib 8.0.43, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: hotel
-- ------------------------------------------------------
-- Server version	8.0.43-0ubuntu0.22.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `activity_logs_action_created_at_index` (`action`,`created_at`),
  KEY `activity_logs_created_at_index` (`created_at`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (38,1,'auth.logout','App\\Models\\User',1,'Signed out',NULL,'127.0.0.1','2026-10-05 11:41:57','2026-10-05 11:41:57'),(39,1,'auth.admin_login','App\\Models\\User',1,'Admin signed in (admin)','{\"role\": \"admin\"}','127.0.0.1','2026-10-05 11:42:54','2026-10-05 11:42:54'),(49,1,'auth.logout','App\\Models\\User',1,'Signed out',NULL,'127.0.0.1','2026-10-05 11:46:04','2026-10-05 11:46:04'),(50,3,'auth.login','App\\Models\\User',3,'praise ike signed in','{\"role\": \"guest\"}','127.0.0.1','2026-10-05 11:46:21','2026-10-05 11:46:21'),(51,3,'auth.logout','App\\Models\\User',3,'Signed out',NULL,'127.0.0.1','2026-10-05 11:46:58','2026-10-05 11:46:58'),(52,1,'auth.admin_login','App\\Models\\User',1,'Admin signed in (admin)','{\"role\": \"admin\"}','127.0.0.1','2026-10-05 11:47:24','2026-10-05 11:47:24');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `guest_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_id` bigint unsigned DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `guests_count` int unsigned NOT NULL DEFAULT '1',
  `total_amount` decimal(12,2) NOT NULL,
  `status` enum('pending_payment','confirmed','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment',
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `source` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_payment_reference_unique` (`payment_reference`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_room_id_check_in_check_out_status_index` (`room_id`,`check_in`,`check_out`,`status`),
  KEY `bookings_guest_email_index` (`guest_email`),
  KEY `bookings_source_index` (`source`),
  CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=549 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (62,3,NULL,NULL,NULL,1,'2026-09-10','2026-09-12',1,90000.00,'confirmed','BOOK-aca5KwxFfa7t','BOOK-aca5KwxFfa7t','2026-09-10 11:19:15',NULL,NULL,'2026-09-10 11:19:02','2026-09-10 11:19:15',NULL),(72,4,NULL,NULL,NULL,1,'2026-09-22','2026-09-25',2,135000.00,'confirmed','BOOK-gyee2Gm7jvJN','BOOK-gyee2Gm7jvJN','2026-09-10 13:48:45',NULL,NULL,'2026-09-10 13:48:04','2026-09-10 13:48:45',NULL),(73,5,NULL,NULL,NULL,2,'2026-09-10','2026-09-11',1,50000.00,'confirmed','BOOK-N0qvz0NbX0hn','BOOK-N0qvz0NbX0hn','2026-09-10 14:06:43',NULL,NULL,'2026-09-10 14:06:13','2026-09-10 14:06:43',NULL),(216,NULL,'praise ike','praiseike123@gmail.com','08032334874',1,'2026-10-05','2026-10-07',1,90000.00,'confirmed','BOOK-XzObynJ1A8hw','BOOK-XzObynJ1A8hw','2026-10-05 09:59:59',NULL,NULL,'2026-10-05 09:59:31','2026-10-05 09:59:59',NULL),(302,NULL,'praise','praiseike123@gmail.com','08032334874',1,'2026-10-15','2026-10-16',1,45000.00,'confirmed','BOOK-2rErUZrHMrv8','BOOK-2rErUZrHMrv8','2026-10-05 10:24:15',NULL,NULL,'2026-10-05 10:23:51','2026-10-05 10:24:15',NULL);
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('5c785c036466adea360111aa28563bfd556b5fba','i:1;',1791204504),('5c785c036466adea360111aa28563bfd556b5fba:timer','i:1791204504;',1791204504),('setting.hotel_address','s:46:\"12 Independence Avenue, Victoria Island, Lagos\";',2104412616),('setting.hotel_description','s:131:\"Experience unparalleled luxury and comfort. Our hotel offers world-class amenities, exceptional service, and an unforgettable stay.\";',2104412616),('setting.hotel_email','s:28:\"reservations@cogapshotel.com\";',2106559613),('setting.hotel_name','s:12:\"Cogaps Hotel\";',2106555038),('setting.hotel_phone','s:17:\"+234 800 555 0134\";',2104412616),('setting.hotel_whatsapp','s:17:\"+234 800 555 0134\";',2106554959),('setting.hotel_whatsapp_message','s:50:\"Hello! I would like to enquire about availability.\";',2106554959);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Standard','standard','Comfortable, thoughtfully designed rooms for the practical traveller.','https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800&q=80',1,1,'2026-09-10 10:18:50','2026-09-10 10:18:50'),(2,'Deluxe','deluxe','Spacious rooms with premium furnishings and city views.','https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80',1,2,'2026-09-10 10:18:50','2026-09-10 10:18:50'),(3,'Executive','executive','Designed for business travellers who expect more.','https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&q=80',1,3,'2026-09-10 10:18:50','2026-09-10 10:18:50'),(4,'Suite','suite','Generous living spaces and panoramic skyline views.','https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=800&q=80',1,4,'2026-09-10 10:18:50','2026-09-10 10:18:50'),(5,'Presidential','presidential','Our most prestigious accommodation, reserved for those who demand the best.','https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800&q=80',1,5,'2026-09-10 10:18:50','2026-09-10 10:18:50');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'praise','praiseike123@gmail.com','080478957834','I lost my phone','I forgot to carry my phone on my way out.',1,'2026-09-10 14:08:24','2026-09-10 14:08:41');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery`
--

DROP TABLE IF EXISTS `gallery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery`
--

LOCK TABLES `gallery` WRITE;
/*!40000 ALTER TABLE `gallery` DISABLE KEYS */;
INSERT INTO `gallery` VALUES (1,'Hotel Lobby','https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1200&q=80','Our grand lobby with 24/7 concierge',1,1,'2026-09-10 10:18:50','2026-09-10 10:18:50'),(2,'Swimming Pool','gallery/SWyZ0NWIAGVh8zU8mgLckj67HXkeAWu3W7lbeeqB.png','Outdoor infinity pool',2,1,'2026-09-10 10:18:50','2026-09-10 11:23:46'),(3,'Fine Dining Room','https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1200&q=80','Seasonal fine dining experience',3,1,'2026-09-10 10:18:50','2026-09-10 10:18:50'),(4,'Presidential Suite','https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80','The pinnacle of luxury',4,1,'2026-09-10 10:18:50','2026-09-10 10:18:50'),(5,'Conference Hall','https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=1200&q=80','Modern conference facilities',5,1,'2026-09-10 10:18:50','2026-09-10 10:18:50'),(6,'Garden Terrace','https://images.unsplash.com/photo-1529290130-4ca3753253ae?w=1200&q=80','Quiet corners to unwind',6,1,'2026-09-10 10:18:50','2026-09-10 10:18:50');
/*!40000 ALTER TABLE `gallery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_add_role_to_users_table',1),(5,'2026_09_10_102920_create_categories_table',1),(6,'2026_09_10_102921_create_rooms_table',1),(7,'2026_09_10_102922_create_bookings_table',1),(8,'2026_09_10_102926_create_gallery_table',1),(9,'2026_09_10_102926_create_payments_table',1),(10,'2026_09_10_102926_create_services_table',1),(11,'2026_09_10_102926_create_settings_table',1),(12,'2026_09_10_102927_create_contacts_table',1),(13,'2026_09_10_103000_add_soft_deletes_to_bookings_table',1),(14,'2026_09_10_103001_add_soft_deletes_to_rooms_table',1),(15,'2026_10_05_000001_add_guest_details_to_bookings_table',2),(16,'2026_10_06_000001_add_source_to_bookings_table',3),(17,'2026_10_06_000002_create_activity_logs_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned DEFAULT NULL,
  `paystack_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NGN',
  `status` enum('pending','success','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `gateway_response` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_paystack_reference_unique` (`paystack_reference`),
  KEY `payments_booking_id_foreign` (`booking_id`),
  CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (13,62,'BOOK-aca5KwxFfa7t',90000.00,'NGN','success','{\"id\": 6544534141, \"log\": {\"input\": [], \"errors\": 0, \"mobile\": false, \"history\": [{\"time\": 3, \"type\": \"action\", \"message\": \"Attempted to pay with card\"}, {\"time\": 6, \"type\": \"success\", \"message\": \"Successfully paid with card\"}], \"success\": true, \"attempts\": 1, \"start_time\": 1789042747, \"time_spent\": 6}, \"fees\": 145000, \"plan\": null, \"split\": [], \"amount\": 9000000, \"domain\": \"test\", \"paidAt\": \"2026-09-10T12:19:12.000Z\", \"source\": null, \"status\": \"success\", \"channel\": \"card\", \"connect\": null, \"message\": null, \"paid_at\": \"2026-09-10T12:19:12.000Z\", \"currency\": \"NGN\", \"customer\": {\"id\": 69490880, \"email\": \"praiseike123@gmail.com\", \"phone\": \"\", \"metadata\": null, \"last_name\": \"\", \"first_name\": \"\", \"risk_action\": \"default\", \"customer_code\": \"CUS_ao4c1qohsz43ljo\", \"international_format_phone\": null}, \"metadata\": {\"referrer\": \"http://127.0.0.1:8000/\", \"booking_id\": 62, \"booking_reference\": \"BOOK-aca5KwxFfa7t\"}, \"order_id\": null, \"createdAt\": \"2026-09-10T12:19:03.000Z\", \"reference\": \"BOOK-aca5KwxFfa7t\", \"created_at\": \"2026-09-10T12:19:03.000Z\", \"fees_split\": null, \"ip_address\": \"102.90.100.190\", \"subaccount\": [], \"plan_object\": [], \"authorization\": {\"bin\": \"408408\", \"bank\": \"TEST BANK\", \"brand\": \"visa\", \"last4\": \"4081\", \"channel\": \"card\", \"exp_year\": \"2030\", \"reusable\": true, \"card_type\": \"visa \", \"exp_month\": \"12\", \"signature\": \"SIG_whDPDQIRTdXq0nUCzDbD\", \"account_name\": null, \"country_code\": \"NG\", \"receiver_bank\": null, \"authorization_code\": \"AUTH_v6e6arg7b7\", \"receiver_bank_account_number\": null}, \"response_code\": \"00\", \"fees_breakdown\": null, \"receipt_number\": null, \"gateway_response\": \"Successful\", \"requested_amount\": 9000000, \"transaction_date\": \"2026-09-10T12:19:03.000Z\", \"pos_transaction_data\": null, \"gateway_response_code\": \"approved\"}','2026-09-10 11:19:15','2026-09-10 11:19:15','2026-09-10 11:19:15'),(16,72,'BOOK-gyee2Gm7jvJN',135000.00,'NGN','success','{\"id\": 6544897848, \"log\": {\"input\": [], \"errors\": 0, \"mobile\": false, \"history\": [{\"time\": 12, \"type\": \"action\", \"message\": \"Set payment method to: bank_transfer\"}, {\"time\": 14, \"type\": \"action\", \"message\": \"Set payment method to: bank\"}, {\"time\": 17, \"type\": \"action\", \"message\": \"Set payment method to: ussd\"}, {\"time\": 20, \"type\": \"action\", \"message\": \"Set payment method to: card\"}, {\"time\": 30, \"type\": \"action\", \"message\": \"Attempted to pay with card\"}, {\"time\": 30, \"type\": \"success\", \"message\": \"Successfully paid with card\"}], \"success\": true, \"attempts\": 1, \"start_time\": 1789051693, \"time_spent\": 30}, \"fees\": 200000, \"plan\": null, \"split\": [], \"amount\": 13500000, \"domain\": \"test\", \"paidAt\": \"2026-09-10T14:48:42.000Z\", \"source\": null, \"status\": \"success\", \"channel\": \"card\", \"connect\": null, \"message\": null, \"paid_at\": \"2026-09-10T14:48:42.000Z\", \"currency\": \"NGN\", \"customer\": {\"id\": 398303919, \"email\": \"vicsedikansamuelumoh@gmail.com\", \"phone\": null, \"metadata\": null, \"last_name\": null, \"first_name\": null, \"risk_action\": \"default\", \"customer_code\": \"CUS_ziywfy5q2ixnfns\", \"international_format_phone\": null}, \"metadata\": {\"referrer\": \"http://127.0.0.1:8000/\", \"booking_id\": 72, \"booking_reference\": \"BOOK-gyee2Gm7jvJN\"}, \"order_id\": null, \"createdAt\": \"2026-09-10T14:48:06.000Z\", \"reference\": \"BOOK-gyee2Gm7jvJN\", \"created_at\": \"2026-09-10T14:48:06.000Z\", \"fees_split\": null, \"ip_address\": \"102.221.136.32\", \"subaccount\": [], \"plan_object\": [], \"authorization\": {\"bin\": \"408408\", \"bank\": \"TEST BANK\", \"brand\": \"visa\", \"last4\": \"4081\", \"channel\": \"card\", \"exp_year\": \"2030\", \"reusable\": true, \"card_type\": \"visa \", \"exp_month\": \"12\", \"signature\": \"SIG_whDPDQIRTdXq0nUCzDbD\", \"account_name\": null, \"country_code\": \"NG\", \"receiver_bank\": null, \"authorization_code\": \"AUTH_ljw1cb23sk\", \"receiver_bank_account_number\": null}, \"response_code\": \"00\", \"fees_breakdown\": null, \"receipt_number\": null, \"gateway_response\": \"Successful\", \"requested_amount\": 13500000, \"transaction_date\": \"2026-09-10T14:48:06.000Z\", \"pos_transaction_data\": null, \"gateway_response_code\": \"approved\"}','2026-09-10 13:48:45','2026-09-10 13:48:45','2026-09-10 13:48:45'),(17,73,'BOOK-N0qvz0NbX0hn',50000.00,'NGN','success','{\"id\": 6544935805, \"log\": {\"input\": [], \"errors\": 0, \"mobile\": false, \"history\": [{\"time\": 2, \"type\": \"action\", \"message\": \"Attempted to pay with card\"}, {\"time\": 8, \"type\": \"success\", \"message\": \"Successfully paid with card\"}], \"success\": true, \"attempts\": 1, \"start_time\": 1789052793, \"time_spent\": 8}, \"fees\": 85000, \"plan\": null, \"split\": [], \"amount\": 5000000, \"domain\": \"test\", \"paidAt\": \"2026-09-10T15:06:39.000Z\", \"source\": null, \"status\": \"success\", \"channel\": \"card\", \"connect\": null, \"message\": null, \"paid_at\": \"2026-09-10T15:06:39.000Z\", \"currency\": \"NGN\", \"customer\": {\"id\": 346748247, \"email\": \"praiseike@gmail.com\", \"phone\": null, \"metadata\": null, \"last_name\": null, \"first_name\": null, \"risk_action\": \"default\", \"customer_code\": \"CUS_lgyerc2elfzjnl1\", \"international_format_phone\": null}, \"metadata\": {\"referrer\": \"http://127.0.0.1:8000/\", \"booking_id\": 73, \"booking_reference\": \"BOOK-N0qvz0NbX0hn\"}, \"order_id\": null, \"createdAt\": \"2026-09-10T15:06:20.000Z\", \"reference\": \"BOOK-N0qvz0NbX0hn\", \"created_at\": \"2026-09-10T15:06:20.000Z\", \"fees_split\": null, \"ip_address\": \"102.221.136.32\", \"subaccount\": [], \"plan_object\": [], \"authorization\": {\"bin\": \"408408\", \"bank\": \"TEST BANK\", \"brand\": \"visa\", \"last4\": \"4081\", \"channel\": \"card\", \"exp_year\": \"2030\", \"reusable\": true, \"card_type\": \"visa \", \"exp_month\": \"12\", \"signature\": \"SIG_whDPDQIRTdXq0nUCzDbD\", \"account_name\": null, \"country_code\": \"NG\", \"receiver_bank\": null, \"authorization_code\": \"AUTH_1t32vdwodd\", \"receiver_bank_account_number\": null}, \"response_code\": \"00\", \"fees_breakdown\": null, \"receipt_number\": null, \"gateway_response\": \"Successful\", \"requested_amount\": 5000000, \"transaction_date\": \"2026-09-10T15:06:20.000Z\", \"pos_transaction_data\": null, \"gateway_response_code\": \"approved\"}','2026-09-10 14:06:43','2026-09-10 14:06:43','2026-09-10 14:06:43'),(44,216,'BOOK-XzObynJ1A8hw',90000.00,'NGN','success','{\"id\": 6626848202, \"log\": {\"input\": [], \"errors\": 0, \"mobile\": false, \"history\": [{\"time\": 9, \"type\": \"action\", \"message\": \"Attempted to pay with card\"}, {\"time\": 15, \"type\": \"success\", \"message\": \"Successfully paid with card\"}], \"success\": true, \"attempts\": 1, \"start_time\": 1791197982, \"time_spent\": 15}, \"fees\": 145000, \"plan\": null, \"split\": [], \"amount\": 9000000, \"domain\": \"test\", \"paidAt\": \"2026-10-05T10:59:56.000Z\", \"source\": null, \"status\": \"success\", \"channel\": \"card\", \"connect\": null, \"message\": null, \"paid_at\": \"2026-10-05T10:59:56.000Z\", \"currency\": \"NGN\", \"customer\": {\"id\": 69490880, \"email\": \"praiseike123@gmail.com\", \"phone\": \"\", \"metadata\": null, \"last_name\": \"\", \"first_name\": \"\", \"risk_action\": \"default\", \"customer_code\": \"CUS_ao4c1qohsz43ljo\", \"international_format_phone\": null}, \"metadata\": {\"referrer\": \"http://localhost:8000/\", \"booking_id\": 216, \"booking_reference\": \"BOOK-XzObynJ1A8hw\"}, \"order_id\": null, \"createdAt\": \"2026-10-05T10:59:33.000Z\", \"reference\": \"BOOK-XzObynJ1A8hw\", \"created_at\": \"2026-10-05T10:59:33.000Z\", \"fees_split\": null, \"ip_address\": \"102.90.101.40\", \"subaccount\": [], \"plan_object\": [], \"authorization\": {\"bin\": \"408408\", \"bank\": \"TEST BANK\", \"brand\": \"visa\", \"last4\": \"4081\", \"channel\": \"card\", \"exp_year\": \"2030\", \"reusable\": true, \"card_type\": \"visa \", \"exp_month\": \"12\", \"signature\": \"SIG_whDPDQIRTdXq0nUCzDbD\", \"account_name\": null, \"country_code\": \"NG\", \"receiver_bank\": null, \"authorization_code\": \"AUTH_noofzhswym\", \"receiver_bank_account_number\": null}, \"response_code\": \"00\", \"fees_breakdown\": null, \"receipt_number\": null, \"gateway_response\": \"Successful\", \"requested_amount\": 9000000, \"transaction_date\": \"2026-10-05T10:59:33.000Z\", \"pos_transaction_data\": null, \"gateway_response_code\": \"approved\"}','2026-10-05 09:59:59','2026-10-05 09:59:59','2026-10-05 09:59:59'),(57,302,'BOOK-2rErUZrHMrv8',45000.00,'NGN','success','{\"id\": 6626928921, \"log\": {\"input\": [], \"errors\": 0, \"mobile\": false, \"history\": [{\"time\": 14, \"type\": \"action\", \"message\": \"Attempted to pay with card\"}, {\"time\": 15, \"type\": \"success\", \"message\": \"Successfully paid with card\"}], \"success\": true, \"attempts\": 1, \"start_time\": 1791199438, \"time_spent\": 15}, \"fees\": 77500, \"plan\": null, \"split\": [], \"amount\": 4500000, \"domain\": \"test\", \"paidAt\": \"2026-10-05T11:24:12.000Z\", \"source\": null, \"status\": \"success\", \"channel\": \"card\", \"connect\": null, \"message\": null, \"paid_at\": \"2026-10-05T11:24:12.000Z\", \"currency\": \"NGN\", \"customer\": {\"id\": 69490880, \"email\": \"praiseike123@gmail.com\", \"phone\": \"\", \"metadata\": null, \"last_name\": \"\", \"first_name\": \"\", \"risk_action\": \"default\", \"customer_code\": \"CUS_ao4c1qohsz43ljo\", \"international_format_phone\": null}, \"metadata\": {\"referrer\": \"http://localhost:8000/\", \"booking_id\": 302, \"booking_reference\": \"BOOK-2rErUZrHMrv8\"}, \"order_id\": null, \"createdAt\": \"2026-10-05T11:23:53.000Z\", \"reference\": \"BOOK-2rErUZrHMrv8\", \"created_at\": \"2026-10-05T11:23:53.000Z\", \"fees_split\": null, \"ip_address\": \"102.90.101.40\", \"subaccount\": [], \"plan_object\": [], \"authorization\": {\"bin\": \"408408\", \"bank\": \"TEST BANK\", \"brand\": \"visa\", \"last4\": \"4081\", \"channel\": \"card\", \"exp_year\": \"2030\", \"reusable\": true, \"card_type\": \"visa \", \"exp_month\": \"12\", \"signature\": \"SIG_whDPDQIRTdXq0nUCzDbD\", \"account_name\": null, \"country_code\": \"NG\", \"receiver_bank\": null, \"authorization_code\": \"AUTH_wwijqos75o\", \"receiver_bank_account_number\": null}, \"response_code\": \"00\", \"fees_breakdown\": null, \"receipt_number\": null, \"gateway_response\": \"Successful\", \"requested_amount\": 4500000, \"transaction_date\": \"2026-10-05T11:23:53.000Z\", \"pos_transaction_data\": null, \"gateway_response_code\": \"approved\"}','2026-10-05 10:24:15','2026-10-05 10:24:15','2026-10-05 10:24:15');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price_per_night` decimal(10,2) NOT NULL,
  `capacity` int unsigned NOT NULL DEFAULT '1',
  `bed_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amenities` json DEFAULT NULL,
  `images` json DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('available','maintenance','booked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_slug_unique` (`slug`),
  KEY `rooms_category_id_foreign` (`category_id`),
  CONSTRAINT `rooms_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rooms`
--

LOCK TABLES `rooms` WRITE;
/*!40000 ALTER TABLE `rooms` DISABLE KEYS */;
INSERT INTO `rooms` VALUES (1,1,'Cozy Double','cozy-double','Our Cozy Double offers a serene retreat with premium bedding, thoughtful amenities, and the attentive service our guests have come to expect. Perfect for travellers seeking comfort and convenience.',45000.00,2,'Queen bed','[\"Free Wi-Fi\", \"Air conditioning\", \"Smart TV\", \"Mini fridge\", \"Daily housekeeping\"]','[\"https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80\", \"https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80\", \"https://res.cloudinary.com/pkeo5c5p/image/upload/v1791202136/hotel-app/rooms/tu5zzlidirheurelvgvr.png\"]',1,'available','2026-09-10 10:18:50','2026-10-05 11:08:56',NULL),(2,1,'Twin Standard','twin-standard','Our Twin Standard offers a serene retreat with premium bedding, thoughtful amenities, and the attentive service our guests have come to expect. Perfect for travellers seeking comfort and convenience.',50000.00,2,'Two single beds','[\"Free Wi-Fi\", \"Air conditioning\", \"Smart TV\", \"Work desk\", \"Daily housekeeping\"]','[\"https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80\", \"https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80\"]',1,'available','2026-09-10 10:18:50','2026-09-10 10:18:50',NULL),(3,2,'Deluxe King','deluxe-king','Our Deluxe King offers a serene retreat with premium bedding, thoughtful amenities, and the attentive service our guests have come to expect. Perfect for travellers seeking comfort and convenience.',80000.00,2,'King bed','[\"Free Wi-Fi\", \"Mini bar\", \"Rain shower\", \"City view\", \"Nespresso machine\"]','[\"https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80\", \"https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1200&q=80\"]',1,'available','2026-09-10 10:18:50','2026-09-10 10:18:50',NULL),(4,2,'Deluxe Twin','deluxe-twin','Our Deluxe Twin offers a serene retreat with premium bedding, thoughtful amenities, and the attentive service our guests have come to expect. Perfect for travellers seeking comfort and convenience.',85000.00,3,'Two queen beds','[\"Free Wi-Fi\", \"Mini bar\", \"Rain shower\", \"City view\", \"Nespresso machine\"]','[\"https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80\", \"https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80\"]',1,'available','2026-09-10 10:18:50','2026-09-10 10:18:50',NULL),(5,3,'Executive Lounge Access','executive-lounge-access','Our Executive Lounge Access offers a serene retreat with premium bedding, thoughtful amenities, and the attentive service our guests have come to expect. Perfect for travellers seeking comfort and convenience.',120000.00,2,'King bed','[\"Free Wi-Fi\", \"Lounge access\", \"Late checkout\", \"Bathtub\", \"Workspace\"]','[\"https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80\", \"https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80\"]',1,'available','2026-09-10 10:18:50','2026-09-10 10:18:50',NULL),(6,3,'Executive Corner','executive-corner','Our Executive Corner offers a serene retreat with premium bedding, thoughtful amenities, and the attentive service our guests have come to expect. Perfect for travellers seeking comfort and convenience.',135000.00,3,'King bed','[\"Free Wi-Fi\", \"Lounge access\", \"Late checkout\", \"Corner windows\", \"Workspace\"]','[\"https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200&q=80\", \"https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80\"]',1,'available','2026-09-10 10:18:50','2026-09-10 10:18:50',NULL),(7,4,'Skyline Suite','skyline-suite','Our Skyline Suite offers a serene retreat with premium bedding, thoughtful amenities, and the attentive service our guests have come to expect. Perfect for travellers seeking comfort and convenience.',180000.00,3,'King bed','[\"Living room\", \"Dining area\", \"Private balcony\", \"Kitchenette\", \"Butler service\"]','[\"https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?w=1200&q=80\", \"https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=1200&q=80\"]',1,'available','2026-09-10 10:18:50','2026-09-10 10:18:50',NULL),(8,4,'Family Suite','family-suite','Our Family Suite offers a serene retreat with premium bedding, thoughtful amenities, and the attentive service our guests have come to expect. Perfect for travellers seeking comfort and convenience.',220000.00,5,'King + bunk','[\"Two bedrooms\", \"Living room\", \"Kitchenette\", \"Kids amenities\", \"Balcony\"]','[\"https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=1200&q=80\", \"https://images.unsplash.com/photo-1591088398332-8a7791972843?w=1200&q=80\"]',1,'available','2026-09-10 10:18:50','2026-09-10 10:18:50',NULL),(9,5,'Presidential Penthouse','presidential-penthouse','Our Presidential Penthouse offers a serene retreat with premium bedding, thoughtful amenities, and the attentive service our guests have come to expect. Perfect for travellers seeking comfort and convenience.',350000.00,4,'King bed','[\"Private terrace\", \"Dining room\", \"Jacuzzi\", \"Chef service\", \"Chauffeur\"]','[\"https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200&q=80\", \"https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200&q=80\"]',1,'available','2026-09-10 10:18:50','2026-09-10 10:18:50',NULL);
/*!40000 ALTER TABLE `rooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'Spa & Wellness','spa-wellness','Relax with massages, facials, and holistic treatments in our tranquil spa sanctuary.',25000.00,'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=800&q=80','Wellness',1,'2026-09-10 10:18:50','2026-09-10 10:18:50'),(2,'Outdoor Pool','outdoor-pool','Take a refreshing dip in our temperature-controlled outdoor pool, surrounded by lush gardens.',0.00,'services/ECuRfdeEZAnzYzGWMbC037SmK0yuPVwv3h0zJ0Cw.jpg','Leisure',1,'2026-09-10 10:18:50','2026-09-10 13:56:35'),(3,'Fine Dining','fine-dining','Award-winning chefs craft seasonal menus with local and international flavours.',35000.00,'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80','Restaurant',1,'2026-09-10 10:18:50','2026-09-10 10:18:50'),(4,'Fitness Center','fitness-center','State-of-the-art gym equipment and complimentary fitness classes for our guests.',0.00,'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&q=80','Wellness',1,'2026-09-10 10:18:50','2026-09-10 10:18:50');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('27OyPQFRd4osb9U7bZzRB24fxnItXEXkjyuNMBLY',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:144.0) Gecko/20100101 Firefox/144.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZkRtNHY2akJPZTBIc1AwMjVMRTNaaEhUR3JHbkRnQjkyYXVWRzFrVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9zZXR0aW5ncyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1791204549);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'hotel_name','Cogaps Hotel','text','2026-09-10 10:18:50','2026-10-05 09:10:38'),(2,'hotel_email','reservations@cogapshotel.com','text','2026-09-10 10:18:50','2026-10-05 10:26:53'),(3,'hotel_phone','+234 800 555 0134','text','2026-09-10 10:18:50','2026-09-10 10:18:50'),(4,'hotel_address','12 Independence Avenue, Victoria Island, Lagos','text','2026-09-10 10:18:50','2026-09-10 10:18:50'),(5,'hotel_description','Experience unparalleled luxury and comfort. Our hotel offers world-class amenities, exceptional service, and an unforgettable stay.','text','2026-09-10 10:18:50','2026-09-10 10:18:50');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'guest',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@cogapshotel.com','admin',NULL,NULL,'2026-09-10 10:18:51','$2y$12$hIvRX8hrS3tYVAxWrocPROdZ5r2dyfn1a35xVOoDBSeJXdclbQUg6',NULL,'2026-09-10 10:18:51','2026-10-05 09:57:45'),(2,'Test Guest','guest@hotel.com','guest',NULL,NULL,'2026-09-10 10:18:51','$2y$12$lxFVGc4ciwwF6ohhq0dBXe8VgIPq1wVpFi1VmMuDej41k/ilt9DIy',NULL,'2026-09-10 10:18:51','2026-09-10 10:18:51'),(3,'praise ike','praiseike123@gmail.com','guest',NULL,NULL,NULL,'$2y$12$k6Fhk2YaezUtins7Vz6qteWJcB2MC250A07oFA7IJdpbtaVgg7gIK',NULL,'2026-09-10 11:16:58','2026-09-10 11:16:58'),(4,'vics','vicsedikansamuelumoh@gmail.com','guest',NULL,NULL,NULL,'$2y$12$eCnnptL8DtuqDaD4pTrrP.IJxdkHmSDX4rZTnLQkt9P32JnhLlx/.',NULL,'2026-09-10 13:47:04','2026-09-10 13:47:04'),(5,'praise ike','praiseike@gmail.com','guest',NULL,NULL,NULL,'$2y$12$..65CS6SAl2xXXzRcvT.2OJa71latb9rWCI5YJN0iuCP7U8hpsfx2',NULL,'2026-09-10 14:03:35','2026-09-10 14:03:35');
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

-- Dump completed on 2026-10-05 13:52:12
