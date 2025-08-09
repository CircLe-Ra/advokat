-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: localhost    Database: advokat
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.20.04.1

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
-- Table structure for table `chats`
--

DROP TABLE IF EXISTS `chats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `user_id2` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chats_user_id_foreign` (`user_id`),
  KEY `chats_user_id2_foreign` (`user_id2`),
  CONSTRAINT `chats_user_id2_foreign` FOREIGN KEY (`user_id2`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chats`
--

LOCK TABLES `chats` WRITE;
/*!40000 ALTER TABLE `chats` DISABLE KEYS */;
/*!40000 ALTER TABLE `chats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `identity` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity_image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_of_birth` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_user_id_foreign` (`user_id`),
  CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (4,11,'9101010204020020','identity/Rou3NgZKEw1CqzgKGeNeJP31Tq1OJEBG3JcvPNRl.jpg','client/rUaUf1ZklUunjsyXYXdmb6DfewySL0P6cvHPYRd1.jpg','+62 822-4844-2171','MERAUKE','2002-04-02','JL.KUPRIK KELAPA LIMA NO.29 RT/RW 15/05','2025-08-09 05:16:45','2025-08-09 05:16:45');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `court_results`
--

DROP TABLE IF EXISTS `court_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `court_results` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `court_schedule_id` bigint unsigned NOT NULL,
  `file` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `court_results_court_schedule_id_foreign` (`court_schedule_id`),
  CONSTRAINT `court_results_court_schedule_id_foreign` FOREIGN KEY (`court_schedule_id`) REFERENCES `court_schedules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `court_results`
--

LOCK TABLES `court_results` WRITE;
/*!40000 ALTER TABLE `court_results` DISABLE KEYS */;
INSERT INTO `court_results` VALUES (4,3,'court-result/gz0lmaiodTOq0jQgwoLGMxGlbhIuhfViRT6Qe8VI.pdf','berita_acara_hasil_sidang.pdf','pdf','2025-08-09 09:17:17','2025-08-09 09:17:17');
/*!40000 ALTER TABLE `court_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `court_schedules`
--

DROP TABLE IF EXISTS `court_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `court_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `legal_case_id` bigint unsigned NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `agenda` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason_for_postponement` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','cancelled','finished') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `court_schedules_legal_case_id_foreign` (`legal_case_id`),
  CONSTRAINT `court_schedules_legal_case_id_foreign` FOREIGN KEY (`legal_case_id`) REFERENCES `legal_cases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `court_schedules`
--

LOCK TABLES `court_schedules` WRITE;
/*!40000 ALTER TABLE `court_schedules` DISABLE KEYS */;
INSERT INTO `court_schedules` VALUES (3,6,'2025-08-15','10:00:00','Sidang Pertama ','Pengadilan Negeri Merauke','','finished','2025-08-09 08:36:18','2025-08-09 09:17:17');
/*!40000 ALTER TABLE `court_schedules` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lawyers`
--

DROP TABLE IF EXISTS `lawyers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lawyers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lawyers_user_id_foreign` (`user_id`),
  CONSTRAINT `lawyers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lawyers`
--

LOCK TABLES `lawyers` WRITE;
/*!40000 ALTER TABLE `lawyers` DISABLE KEYS */;
INSERT INTO `lawyers` VALUES (1,4,'+62 852-2323-4234','lawyer/zq2bPQ8VnUgFRXGJZDRIy0Ay66YnNJIREztIRWf0.png','2025-07-26 17:21:05','2025-07-26 17:21:05'),(2,5,'+62 852-2323-4232','lawyer/AodKW0VD4jM6maMdb358X4Vs5RZM8KPjLQkFNyH0.png','2025-07-26 17:21:58','2025-07-26 17:21:58'),(3,6,'+62 852-2323-4233','lawyer/tblAmvRWS9FvLvDPvcBjrCIEkNtIMOgoGQ9fNgho.jpg','2025-07-26 17:22:37','2025-07-26 17:22:37');
/*!40000 ALTER TABLE `lawyers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `legal_case_documents`
--

DROP TABLE IF EXISTS `legal_case_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `legal_case_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `legal_case_id` bigint unsigned NOT NULL,
  `file` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `legal_case_documents_legal_case_id_foreign` (`legal_case_id`),
  CONSTRAINT `legal_case_documents_legal_case_id_foreign` FOREIGN KEY (`legal_case_id`) REFERENCES `legal_cases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `legal_case_documents`
--

LOCK TABLES `legal_case_documents` WRITE;
/*!40000 ALTER TABLE `legal_case_documents` DISABLE KEYS */;
INSERT INTO `legal_case_documents` VALUES (9,6,'case/novrt5BTAEqEERcveA41xe50h7RaDxc8cI4s6GYV.jpg','jpg','2025-08-09 05:25:10','2025-08-09 05:25:10');
/*!40000 ALTER TABLE `legal_case_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `legal_case_validations`
--

DROP TABLE IF EXISTS `legal_case_validations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `legal_case_validations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `legal_case_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `date_time` datetime NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `validation` enum('pending','verified','revision','revised','rejected','accepted','closed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `legal_case_validations_legal_case_id_foreign` (`legal_case_id`),
  KEY `legal_case_validations_user_id_foreign` (`user_id`),
  CONSTRAINT `legal_case_validations_legal_case_id_foreign` FOREIGN KEY (`legal_case_id`) REFERENCES `legal_cases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `legal_case_validations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `legal_case_validations`
--

LOCK TABLES `legal_case_validations` WRITE;
/*!40000 ALTER TABLE `legal_case_validations` DISABLE KEYS */;
INSERT INTO `legal_case_validations` VALUES (17,6,11,'2025-08-09 06:20:06',NULL,'pending','2025-08-09 06:20:06','2025-08-09 06:20:06'),(18,6,1,'2025-08-09 07:35:38','Tambahkan Sebuah dokumen yang bisa menunjang kasus anda ','verified','2025-08-09 07:35:38','2025-08-09 07:35:38'),(19,6,3,'2025-08-09 07:37:17','lanjutkan','accepted','2025-08-09 07:37:17','2025-08-09 07:37:17');
/*!40000 ALTER TABLE `legal_case_validations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `legal_cases`
--

DROP TABLE IF EXISTS `legal_cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `legal_cases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `lawyer_id` bigint unsigned DEFAULT NULL,
  `number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `case_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('civil','criminal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `chronology` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','pending','revision','revised','verified','rejected','accepted','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `legal_cases_client_id_foreign` (`client_id`),
  KEY `legal_cases_lawyer_id_foreign` (`lawyer_id`),
  CONSTRAINT `legal_cases_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `legal_cases_lawyer_id_foreign` FOREIGN KEY (`lawyer_id`) REFERENCES `lawyers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `legal_cases`
--

LOCK TABLES `legal_cases` WRITE;
/*!40000 ALTER TABLE `legal_cases` DISABLE KEYS */;
INSERT INTO `legal_cases` VALUES (6,4,1,'KASUS-0001/11/2025/08/09/052215','15/Pdt.G/2025/PN Mrk','criminal','Pencemaran Nama Baik','Membuat pernyataan yang menuduh seseorang melakukan tindakan asusila tanpa dasar','Membuat pernyataan yang menuduh seseorang melakukan tindakan asusila tanpa dasar','accepted','2025-08-09 05:25:10','2025-08-09 08:35:37');
/*!40000 ALTER TABLE `legal_cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_documentations`
--

DROP TABLE IF EXISTS `meeting_documentations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meeting_documentations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `meeting_schedule_id` bigint unsigned NOT NULL,
  `file` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meeting_documentations_meeting_schedule_id_foreign` (`meeting_schedule_id`),
  CONSTRAINT `meeting_documentations_meeting_schedule_id_foreign` FOREIGN KEY (`meeting_schedule_id`) REFERENCES `meeting_schedules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_documentations`
--

LOCK TABLES `meeting_documentations` WRITE;
/*!40000 ALTER TABLE `meeting_documentations` DISABLE KEYS */;
/*!40000 ALTER TABLE `meeting_documentations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_file_additions`
--

DROP TABLE IF EXISTS `meeting_file_additions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meeting_file_additions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `meeting_schedule_id` bigint unsigned NOT NULL,
  `file` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meeting_file_additions_meeting_schedule_id_foreign` (`meeting_schedule_id`),
  CONSTRAINT `meeting_file_additions_meeting_schedule_id_foreign` FOREIGN KEY (`meeting_schedule_id`) REFERENCES `meeting_schedules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_file_additions`
--

LOCK TABLES `meeting_file_additions` WRITE;
/*!40000 ALTER TABLE `meeting_file_additions` DISABLE KEYS */;
INSERT INTO `meeting_file_additions` VALUES (2,5,'meeting-result-addition/GpTISbr6XT0T2ggkbdv64lxry1spRM4ytEomwP1A.jpg','jpg','2025-08-09 08:32:10','2025-08-09 08:32:10');
/*!40000 ALTER TABLE `meeting_file_additions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_schedules`
--

DROP TABLE IF EXISTS `meeting_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meeting_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `legal_case_id` bigint unsigned NOT NULL,
  `about` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_time` datetime NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','cancelled','finished') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `file_collection` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `file_submission_deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meeting_schedules_legal_case_id_foreign` (`legal_case_id`),
  CONSTRAINT `meeting_schedules_legal_case_id_foreign` FOREIGN KEY (`legal_case_id`) REFERENCES `legal_cases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_schedules`
--

LOCK TABLES `meeting_schedules` WRITE;
/*!40000 ALTER TABLE `meeting_schedules` DISABLE KEYS */;
INSERT INTO `meeting_schedules` VALUES (5,6,'Gelar Perkara','2025-08-11 09:00:00','<div>Baik, saya akan buatkan contoh <strong>kesimpulan hasil pertemuan gelar perkara</strong> antara advokat dan klien dengan format yang rapi dan profesional.</div><div><strong>KESIMPULAN HASIL PERTEMUAN</strong><br> <strong>Gelar Perkara antara Advokat dan Klien</strong></div><div>Pada tanggal <strong>[isi tanggal pertemuan]</strong>, telah dilaksanakan pertemuan antara <strong>[nama advokat / kantor advokat]</strong> dan <strong>[nama klien]</strong> dalam rangka gelar perkara terkait <strong>[uraian singkat perkara]</strong>.</div><div>Berdasarkan pembahasan yang dilakukan, diperoleh kesimpulan sebagai berikut:</div><ol><li><strong>Kronologis dan Fakta Hukum</strong><ul><li>Klien telah menjelaskan secara rinci kronologis peristiwa yang menjadi dasar perkara.</li><li>Bukti-bukti awal yang telah diserahkan oleh klien antara lain: <strong>[daftar bukti]</strong>.</li></ul></li><li><strong>Analisis Awal Advokat</strong><ul><li>Advokat memberikan pendapat hukum sementara bahwa perkara ini termasuk kategori <strong>[pidana/perdata/tata usaha negara]</strong>.</li><li>Terdapat peluang untuk <strong>[misalnya: mengajukan gugatan / membela terdakwa / melakukan mediasi]</strong> dengan mempertimbangkan kekuatan bukti yang ada.</li></ul></li><li><strong>Rencana Tindak Lanjut</strong><ul><li>Mengumpulkan bukti tambahan berupa <strong>[jenis bukti yang diperlukan]</strong>.</li><li>Menjadwalkan pertemuan lanjutan pada <strong>[tanggal]</strong> untuk pembahasan strategi hukum.</li><li>Advokat akan menyusun <strong>[draf gugatan, jawaban, pledoi, atau dokumen hukum lainnya]</strong>.</li></ul></li><li><strong>Kesepakatan</strong><ul><li>Klien setuju untuk memberikan keterangan tambahan bila diperlukan.</li><li>Advokat akan bertindak sesuai kuasa yang diberikan, berdasarkan Surat Kuasa Khusus yang telah ditandatangani.</li></ul></li></ol><div>Demikian kesimpulan pertemuan ini dibuat untuk menjadi pegangan bersama dan tindak lanjut penanganan perkara.</div><div><br></div>','finished','yes','2025-08-13','2025-08-09 07:40:21','2025-08-09 08:14:53');
/*!40000 ALTER TABLE `meeting_schedules` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_05_26_100240_create_permission_tables',1),(5,'2025_06_24_110910_create_lawyers_table',1),(6,'2025_06_24_124656_create_client_table',1),(7,'2025_06_25_085453_create_legal_cases_table',1),(8,'2025_06_25_085939_create_legal_case_documents_table',1),(9,'2025_06_27_141227_create_legal_case_validations_table',1),(10,'2025_07_05_175640_create_meeting_schedules_table',1),(11,'2025_07_05_223650_create_meeting_documentations_table',1),(12,'2025_07_07_192451_create_court_schedules_table',1),(13,'2025_07_07_220559_create_court_results_table',1),(14,'2025_07_09_155825_create_chats_table',1),(15,'2025_07_11_113802_add_case_number_to_legal_cases_table',1),(16,'2025_07_11_133524_create_personal_access_tokens_table',1),(17,'2025_07_15_222306_create_meeting_file_additions_table',1),(18,'2025_07_22_232254_create_notifications_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(3,'App\\Models\\User',3),(4,'App\\Models\\User',4),(4,'App\\Models\\User',5),(4,'App\\Models\\User',6),(1,'App\\Models\\User',9),(2,'App\\Models\\User',11);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('0de12195-de39-46f6-ad12-2e61d7c2f195','App\\Notifications\\ChatNotification','App\\Models\\User',9,'{\"role\":\"admin\",\"from\":\"10\",\"message\":\"woii\",\"to\":\"9\"}',NULL,'2025-08-01 03:41:46','2025-08-01 03:41:46'),('119ca394-1eb1-4c68-8fcc-018b50f5fd8f','App\\Notifications\\ChatNotification','App\\Models\\User',10,'{\"role\":\"client\",\"from\":\"9\",\"message\":\"iya\",\"to\":\"10\"}',NULL,'2025-08-01 03:33:20','2025-08-01 03:33:20'),('1d96dc99-37aa-4c6a-a0d2-d615e12781bd','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"abcdef\",\"to\":\"1\"}',NULL,'2025-07-31 20:46:26','2025-07-31 20:46:26'),('2d392b26-1bf8-4b1c-8fd7-68fd67aeaf7c','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"Testing\",\"to\":\"1\"}',NULL,'2025-07-31 20:43:58','2025-07-31 20:43:58'),('317a0a0d-eb4c-4582-99b0-a522ca4a1256','App\\Notifications\\ChatNotification','App\\Models\\User',9,'{\"role\":\"admin\",\"from\":\"10\",\"message\":\"hallo\",\"to\":\"9\"}',NULL,'2025-08-01 02:54:39','2025-08-01 02:54:39'),('4114d78b-8b89-4e09-ae33-cfde0964a44b','App\\Notifications\\ChatNotification','App\\Models\\User',9,'{\"role\":\"admin\",\"from\":\"10\",\"message\":\"123\",\"to\":\"9\"}',NULL,'2025-08-01 02:55:45','2025-08-01 02:55:45'),('479ba9ee-363d-4438-b8a9-5d6b7167cc27','App\\Notifications\\ChatNotification','App\\Models\\User',10,'{\"role\":\"client\",\"from\":\"9\",\"message\":\"sippppp\",\"to\":\"10\"}',NULL,'2025-08-01 02:56:10','2025-08-01 02:56:10'),('4eb5e904-fb0e-49a7-ab5c-6a3ab7b35c96','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"7\",\"message\":\"saya lagi mengajukan kasus\",\"to\":\"1\"}',NULL,'2025-07-31 20:40:48','2025-07-31 20:40:48'),('4f534031-bb7c-4368-a539-941f8e69b3e9','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"tessssssss\",\"to\":\"1\"}',NULL,'2025-07-31 20:55:43','2025-07-31 20:55:43'),('52048fc4-3307-47b3-8568-fb0bf1cb0337','App\\Notifications\\ChatNotification','App\\Models\\User',2,'{\"role\":\"client\",\"from\":\"1\",\"message\":\"asdf\",\"to\":\"2\"}',NULL,'2025-07-31 20:52:54','2025-07-31 20:52:54'),('5c246bec-da77-44e3-825c-041d2b455c5c','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"hallo\",\"to\":\"1\"}',NULL,'2025-07-31 20:40:47','2025-07-31 20:40:47'),('607a0c37-d71e-4426-878e-0e1a5d77e77d','App\\Notifications\\ChatNotification','App\\Models\\User',2,'{\"role\":\"client\",\"from\":\"1\",\"message\":\"yoiii\",\"to\":\"2\"}',NULL,'2025-07-31 20:46:45','2025-07-31 20:46:45'),('6c95bf19-fa60-4eb2-b754-37a52bc8c7d5','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"123\",\"to\":\"1\"}',NULL,'2025-07-31 21:01:50','2025-07-31 21:01:50'),('6f699fdb-de09-48e0-aeee-f5b082760b65','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"tes\",\"to\":\"1\"}',NULL,'2025-07-31 20:54:56','2025-07-31 20:54:56'),('70a7e8ba-038c-41ca-8dd6-396148123505','App\\Notifications\\ChatNotification','App\\Models\\User',10,'{\"role\":\"client\",\"from\":\"9\",\"message\":\"123\",\"to\":\"10\"}',NULL,'2025-08-01 03:32:42','2025-08-01 03:32:42'),('71aad056-a2ad-4583-8a85-905471015b2f','App\\Notifications\\ChatNotification','App\\Models\\User',10,'{\"role\":\"client\",\"from\":\"9\",\"message\":\"juga\",\"to\":\"10\"}',NULL,'2025-08-01 02:55:08','2025-08-01 02:55:08'),('79988865-0b1d-4ef7-b366-0d4a4c713cb1','App\\Notifications\\ChatNotification','App\\Models\\User',10,'{\"role\":\"client\",\"from\":\"9\",\"message\":\"123\",\"to\":\"10\"}',NULL,'2025-08-01 03:35:05','2025-08-01 03:35:05'),('7dddca70-3140-482c-9bd7-597b31e8f042','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"zxcv\",\"to\":\"1\"}',NULL,'2025-07-31 20:53:30','2025-07-31 20:53:30'),('7ebf5802-c84a-4ea9-976d-5126038cd307','App\\Notifications\\ChatNotification','App\\Models\\User',9,'{\"role\":\"admin\",\"from\":\"10\",\"message\":\"tes\",\"to\":\"9\"}',NULL,'2025-08-01 03:32:04','2025-08-01 03:32:04'),('8e83ae55-a37b-4b72-867b-f6fd6da02a1b','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"asdf\",\"to\":\"1\"}',NULL,'2025-07-31 20:44:30','2025-07-31 20:44:30'),('9e0c5cf3-0399-444f-95fb-f10dd68ddc47','App\\Notifications\\ChatNotification','App\\Models\\User',2,'{\"role\":\"client\",\"from\":\"1\",\"message\":\"hjllo\",\"to\":\"2\"}',NULL,'2025-07-31 20:56:09','2025-07-31 20:56:09'),('a3189d6b-c900-48c3-8c48-5cf7b4809064','App\\Notifications\\ChatNotification','App\\Models\\User',7,'{\"role\":\"client\",\"from\":\"1\",\"message\":\"oke saya lihat terlebih dahulu\",\"to\":\"7\"}',NULL,'2025-07-31 20:40:49','2025-07-31 20:40:49'),('ae11c89f-2d71-4a4a-9027-1d10fa3f4894','App\\Notifications\\ChatNotification','App\\Models\\User',2,'{\"role\":\"client\",\"from\":\"1\",\"message\":\"fghdhsheh\",\"to\":\"2\"}',NULL,'2025-07-31 21:07:09','2025-07-31 21:07:09'),('b1396188-51f5-4f92-a40d-a14e9941f8b1','App\\Notifications\\ChatNotification','App\\Models\\User',2,'{\"role\":\"client\",\"from\":\"1\",\"message\":\"asdf\",\"to\":\"2\"}',NULL,'2025-07-31 20:52:29','2025-07-31 20:52:29'),('b2c7d060-9e3b-4bc8-9dc0-33ff6e8a36cb','App\\Notifications\\ChatNotification','App\\Models\\User',9,'{\"role\":\"admin\",\"from\":\"10\",\"message\":\"cvdsdfd\",\"to\":\"9\"}',NULL,'2025-08-01 03:33:10','2025-08-01 03:33:10'),('b4860219-888e-4d1e-b668-0a676c4d8d45','App\\Notifications\\ChatNotification','App\\Models\\User',9,'{\"role\":\"admin\",\"from\":\"10\",\"message\":\"tss\",\"to\":\"9\"}',NULL,'2025-08-01 03:34:55','2025-08-01 03:34:55'),('bfc2daa1-2a18-4b76-bf9d-ca2d15dc7bfd','App\\Notifications\\ChatNotification','App\\Models\\User',9,'{\"role\":\"admin\",\"from\":\"10\",\"message\":\"lkdsjfksdf\",\"to\":\"9\"}',NULL,'2025-08-01 03:38:25','2025-08-01 03:38:25'),('c4097d34-3ead-4206-956d-3761329b5352','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"10\",\"message\":\"tes\",\"to\":\"1\"}',NULL,'2025-08-01 03:42:27','2025-08-01 03:42:27'),('c715d1b9-c121-4786-87b3-54b40a29c4af','App\\Notifications\\ChatNotification','App\\Models\\User',10,'{\"role\":\"client\",\"from\":\"1\",\"message\":\"hsllo\",\"to\":\"10\"}',NULL,'2025-08-01 03:43:11','2025-08-01 03:43:11'),('c7407e34-d66e-436d-a680-026393e815b6','App\\Notifications\\ChatNotification','App\\Models\\User',2,'{\"role\":\"client\",\"from\":\"1\",\"message\":\"123\",\"to\":\"2\"}',NULL,'2025-07-31 20:53:19','2025-07-31 20:53:19'),('c9ff8fe9-4368-465f-af96-10c4bb7d5697','App\\Notifications\\ChatNotification','App\\Models\\User',9,'{\"role\":\"admin\",\"from\":\"10\",\"message\":\"123\",\"to\":\"9\"}',NULL,'2025-08-01 03:37:25','2025-08-01 03:37:25'),('ca648741-39e3-49c5-97d5-13cb89dad4dd','App\\Notifications\\ChatNotification','App\\Models\\User',2,'{\"role\":\"client\",\"from\":\"1\",\"message\":\"srususr\",\"to\":\"2\"}',NULL,'2025-07-31 21:08:28','2025-07-31 21:08:28'),('cde30257-d790-408e-a824-a8b35952f586','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"456\",\"to\":\"1\"}',NULL,'2025-07-31 21:06:37','2025-07-31 21:06:37'),('ce1652e4-7c8f-4e61-900a-0b22d98b03f9','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"rtsurturuu\",\"to\":\"1\"}',NULL,'2025-07-31 21:08:20','2025-07-31 21:08:20'),('e884b721-cc9d-4d88-aa47-d08039b696c1','App\\Notifications\\ChatNotification','App\\Models\\User',9,'{\"role\":\"admin\",\"from\":\"10\",\"message\":\"woii\",\"to\":\"9\"}',NULL,'2025-08-01 03:41:36','2025-08-01 03:41:36'),('f61a3fa3-cef8-41a6-91c5-aa7f17bf86d8','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"asdf\",\"to\":\"1\"}',NULL,'2025-07-31 20:52:46','2025-07-31 20:52:46'),('f86beef9-d3e6-456a-b9d9-4cd7446bcbd6','App\\Notifications\\ChatNotification','App\\Models\\User',10,'{\"role\":\"client\",\"from\":\"9\",\"message\":\"khsdfskdfj\",\"to\":\"10\"}',NULL,'2025-08-01 03:38:12','2025-08-01 03:38:12'),('f8a9f50b-18ea-4820-b9da-145f061d5a30','App\\Notifications\\ChatNotification','App\\Models\\User',1,'{\"role\":\"admin\",\"from\":\"2\",\"message\":\"tess\",\"to\":\"1\"}',NULL,'2025-07-31 20:54:19','2025-07-31 20:54:19');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
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
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'staf','web','2025-07-26 16:40:44','2025-07-26 16:40:44'),(2,'klien','web','2025-07-26 16:40:44','2025-07-26 16:40:44'),(3,'pimpinan','web','2025-07-26 16:40:45','2025-07-26 16:40:45'),(4,'pengacara','web','2025-07-26 16:40:45','2025-07-26 16:40:45');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('6yp80RaTCVzB78PJdKARbXpZVFMX8sEEnCVcFc7a',1,'2001:448a:70e0:4fa8:bd2d:ca13:173b:cea2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOENiUW1UUm9HM0VyZEpmem94c0hwREZxY3pad09FWjR1SWdNWDdZaSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjU5OiJodHRwczovL2Fkdm9rYXQucG5kci5teS5pZC9wdXNoZXIvYmVhbXMtYXV0aD91c2VyX2lkPXVzZXItMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1754701438),('7sZodBpFwz3gRehse1DcPRCof8TBNeQSQ16SAmOH',11,'180.252.99.54','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYjVhYWxDbVkwaXlQQjV4N09OamFKY0lGRmVldWZPYkhwZEZiMXp5YyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vYWR2b2thdC5wbmRyLm15LmlkL2NsaWVudC9zY2hlZHVsZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjExO30=',1754702196),('FXeuhu24FSyUYSvseQTBD91Pl86t1haUzMrpdn0D',11,'2001:448a:70e0:4fa8:bd2d:ca13:173b:cea2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN1NLZWJZaTltY1lTWEtjZThHT0hZSVF4QzRIUXlFZTRseVlTS3c5QSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjQ6Imh0dHBzOi8vYWR2b2thdC5wbmRyLm15LmlkL2NsaWVudC9jYXNlLzYvY291cnQtc2NoZWR1bGUvaGFuZGxpbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMTtzOjM6InVybCI7YTowOnt9fQ==',1754701055),('uAAvPQtWSLv5cnIE1xpV9yZsGAeMiyLo9o927FHU',4,'180.252.99.54','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTVYwZjhRWjRCM2U0c1N0dU5lOHNZaG55M1dHbUlhN05pdnhsN0hEaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vYWR2b2thdC5wbmRyLm15LmlkL2xhd3llci9zY2hlZHVsZSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==',1754702381),('vcne74gnT6x2GEYulFeWnsl0sU1yOFBGAy9dUpeK',3,'2001:448a:70e0:4fa8:bd2d:ca13:173b:cea2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieVBxSDBHUEY4N1lXNmkyaWFMTHdRR09tZ0ZTN1R5SmQ5bjhMSnZOTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTY6Imh0dHBzOi8vYWR2b2thdC5wbmRyLm15LmlkL2xlYWRlci9hY3RpdmUvY2FzZS82L3NjaGVkdWxlIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9',1754701353),('WwIxWLQXktKmPw255YPrFoPoAPhjUY5b7Zs0cOoA',4,'2001:448a:70e0:4fa8:bd2d:ca13:173b:cea2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoialZydGNidEtLaENyeHFWSlJMUFZGVklwdXZCejlPZjRkb2t4ZjdVVCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vYWR2b2thdC5wbmRyLm15LmlkL2xhd3llci9zY2hlZHVsZSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==',1754702512);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
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
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@admin.com','2025-07-26 16:40:46','$2y$12$hksNbwdGMitHhBmvXOqmXeMHbfqk75j36M9gXkKQHarHySeZ8ukee','ha8Xdl02WFk0x2idwLgwqmvsT0kPH5hJOlcbhHzLIRJLkEH6bFHoHtIWQcFI','2025-07-26 16:40:46','2025-07-26 16:40:46'),(3,'pimpinan advokat','ranggapamungkas124@gmail.com',NULL,'$2y$12$MYv.1vtCWbu8iqVcUnDiz.ggwXW0VxjhQhM7IhtwU95M/UPcJfWPe','IP9iJmZN8NwoK8HCoAj89ConwbBeGX0yccG046OAcNpV1AB2Qt17RvRIWYQ8','2025-07-26 17:00:52','2025-07-26 17:00:52'),(4,'Kaitanus F. X. Mogahai, S.H','kaitanus@gmail.com',NULL,'$2y$12$WVqgyTcS.t3R60KxwjphhOxYN/wqrm/q94jqou7Iy2xUXcoDn6G2m',NULL,'2025-07-26 17:21:05','2025-07-26 17:21:05'),(5,'Gabriel Naftali Jawok Epin, S.H','gabriel@gmail.com',NULL,'$2y$12$0J9Ej1tzS0vRoTI0vIFxEuZeHzM0RTCK15oKRMnlA4FaXgJjIFD2e',NULL,'2025-07-26 17:21:58','2025-07-26 17:21:58'),(6,'Rivard Mahue, S.H','rivard@gmail.com',NULL,'$2y$12$vQ.MzDncH.mdVTRd.WmeGumoyhS27wA3eqsS/umD1xXF.X31rFrhi',NULL,'2025-07-26 17:22:37','2025-07-26 17:22:37'),(9,'Admin 01','sisteminformasiunmus20@gmail.com',NULL,'$2y$12$U67D.Zrwdgx0S7ocsvcYn.IBTkEHukBI3Xa4VOC8rxezstkiheiRW','da9OQhkWlCBA3b8r1RjTzHXj84B2lPLnXZR7jsBa8ndyI96oGVmMeNm9ze2F','2025-07-31 21:35:33','2025-07-31 21:35:33'),(11,'Klien 1','klienkami01@gamil.com',NULL,'$2y$12$HMXnKUs8ci2rnx6Adfu6zOyekwH6nOaY10.gchnEUzyic3HKrLPwe',NULL,'2025-08-09 03:54:17','2025-08-09 03:54:17');
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

-- Dump completed on 2025-08-09  1:24:34
