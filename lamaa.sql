-- ================================================================
-- LAMAA Laundry Service - لماء للمغاسل
-- MySQL Database Dump - Compatible with phpMyAdmin / XAMPP
-- ================================================================
-- Usage:
--   1. Open phpMyAdmin (http://localhost/phpmyadmin)
--   2. Create database named: lamaa
--   3. Select that database, go to Import tab
--   4. Upload this file and click Go
-- ================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

-- Drop tables in safe order
DROP TABLE IF EXISTS `order_notifications`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `password_reset_tokens`;

-- ----------------------------------------------------------------
-- Table: users
-- ----------------------------------------------------------------
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `total_orders` int NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: services
-- ----------------------------------------------------------------
CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `base_price` double NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: orders
-- ----------------------------------------------------------------
CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_name_ar` varchar(255) NOT NULL,
  `quantity` int NOT NULL DEFAULT 1,
  `total_price` double NOT NULL,
  `is_free_order` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'new',
  `notes` text DEFAULT NULL,
  `damage_note_photo_url` text DEFAULT NULL,
  `pickup_date` date NOT NULL,
  `delivery_date` date DEFAULT NULL,
  `pickup_time_slot` varchar(255) DEFAULT NULL,
  `delivery_time_slot` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `rating_comment` text DEFAULT NULL,
  `express_delivery` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_service_id_foreign` (`service_id`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: order_notifications
-- ----------------------------------------------------------------
CREATE TABLE `order_notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `body_ar` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_notifications_customer_id_foreign` (`customer_id`),
  KEY `order_notifications_order_id_foreign` (`order_id`),
  CONSTRAINT `order_notifications_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `order_notifications_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: migrations (Laravel internal tracking)
-- ----------------------------------------------------------------
CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: sessions
-- ----------------------------------------------------------------
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: password_reset_tokens
-- ----------------------------------------------------------------
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: cache
-- ----------------------------------------------------------------
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: cache_locks
-- ----------------------------------------------------------------
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: jobs
-- ----------------------------------------------------------------
CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: failed_jobs
-- ----------------------------------------------------------------
CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- SEED DATA
-- ================================================================

-- Admin user (phone: 0500000000 / password: admin123)
-- Hash is bcrypt of 'admin123' compatible with PHP password_verify()
INSERT INTO `users` (`name`, `phone`, `email`, `address`, `password`, `role`, `total_orders`, `created_at`, `updated_at`) VALUES
('مدير لماء', '0500000000', 'admin@lamaa.sa', NULL, '$2b$10$yOLlqQp45JGBcmDAhm3hA.CgRl472r0Nynq1El8Wcs6bQeJep103i', 'admin', 0, NOW(), NOW());

-- Sample customer (phone: 0501234567 / password: customer123)
INSERT INTO `users` (`name`, `phone`, `email`, `address`, `password`, `role`, `total_orders`, `created_at`, `updated_at`) VALUES
('أحمد محمد', '0501234567', 'ahmed@example.com', 'الرياض، حي النزهة', '$2b$10$yOLlqQp45JGBcmDAhm3hA.CgRl472r0Nynq1El8Wcs6bQeJep103i', 'customer', 3, NOW(), NOW());

-- Services
INSERT INTO `services` (`name_ar`, `name_en`, `icon`, `base_price`, `description`, `created_at`, `updated_at`) VALUES
('غسيل الملابس العادية', 'Regular Clothes Washing', '👕', 25, NULL, NOW(), NOW()),
('غسيل شراشف الفنادق', 'Hotel Sheets Washing', '🛏️', 40, NULL, NOW(), NOW()),
('غسيل ملابس عمال المطاعم', 'Restaurant Uniforms', '👨‍🍳', 30, NULL, NOW(), NOW()),
('غسيل السجاد والبطانيات', 'Carpets & Blankets', '🛋️', 60, NULL, NOW(), NOW()),
('غسيل ملابس نزلاء الفنادق', 'Hotel Guest Clothing', '🏨', 35, NULL, NOW(), NOW()),
('غسيل عاجل ومستعجل', 'Express Service', '⚡', 45, NULL, NOW(), NOW());

-- Migration tracking records
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2024_01_01_000001_create_users_table', 1),
('2024_01_01_000002_create_services_table', 1),
('2024_01_01_000003_create_orders_table', 1),
('2024_01_01_000004_create_order_notifications_table', 1);

COMMIT;
-- ================================================================
-- END OF FILE
-- Admin Login: phone=0500000000 | password=admin123
-- Admin Panel: http://localhost:8000/admin
-- ================================================================
