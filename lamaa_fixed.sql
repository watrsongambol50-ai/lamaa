-- ================================================================
-- LAMAA Laundry Service - لماء للمغاسل
-- MySQL Database Dump - Updated Version (with categories)
-- ================================================================
-- Usage:
--   1. Open phpMyAdmin (http://localhost/phpmyadmin)
--   2. Create database named: lamaa
--   3. Select that database → Import → Upload this file → Go
-- Admin:    phone=0500000000  password=admin123
-- Customer: phone=0501234567  password=admin123
-- ================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;
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
-- Table: users (with region column)
-- ----------------------------------------------------------------
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
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
-- Table: services (with category column)
-- ----------------------------------------------------------------
CREATE TABLE `services` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'shirt',
  `category` varchar(255) NOT NULL DEFAULT 'ملابس_عامة',
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
  `pickup_date` date DEFAULT NULL,
  `pickup_time_slot` varchar(255) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `express_delivery` tinyint(1) NOT NULL DEFAULT 0,
  `rating` int DEFAULT NULL,
  `rating_comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_service_id_foreign` (`service_id`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: order_notifications
-- ----------------------------------------------------------------
CREATE TABLE `order_notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `body_ar` text DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_notifications_customer_id_foreign` (`customer_id`),
  CONSTRAINT `order_notifications_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: migrations
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
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table: cache
-- ----------------------------------------------------------------
CREATE TABLE `cache` (`key` varchar(255) NOT NULL, `value` mediumtext NOT NULL, `expiration` int NOT NULL, PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE `cache_locks` (`key` varchar(255) NOT NULL, `owner` varchar(255) NOT NULL, `expiration` int NOT NULL, PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- Table: jobs
-- ----------------------------------------------------------------
CREATE TABLE `jobs` (`id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, `queue` varchar(255) NOT NULL, `payload` longtext NOT NULL, `attempts` tinyint UNSIGNED NOT NULL, `reserved_at` int UNSIGNED DEFAULT NULL, `available_at` int UNSIGNED NOT NULL, `created_at` int UNSIGNED NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB;
CREATE TABLE `job_batches` (`id` varchar(255) NOT NULL, `name` varchar(255) NOT NULL, `total_jobs` int NOT NULL, `pending_jobs` int NOT NULL, `failed_jobs` int NOT NULL, `failed_job_ids` longtext NOT NULL, `options` mediumtext, `cancelled_at` int DEFAULT NULL, `created_at` int NOT NULL, `finished_at` int DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB;
CREATE TABLE `failed_jobs` (`id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, `uuid` varchar(255) NOT NULL, `connection` text NOT NULL, `queue` text NOT NULL, `payload` longtext NOT NULL, `exception` longtext NOT NULL, `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) ENGINE=InnoDB;
CREATE TABLE `password_reset_tokens` (`email` varchar(255) NOT NULL, `token` varchar(255) NOT NULL, `created_at` timestamp NULL DEFAULT NULL, PRIMARY KEY (`email`)) ENGINE=InnoDB;

-- ----------------------------------------------------------------
-- USERS DATA
-- ----------------------------------------------------------------
INSERT INTO `users` (`id`, `name`, `phone`, `email`, `address`, `region`, `password`, `role`, `total_orders`, `created_at`, `updated_at`) VALUES
(1, 'مدير لماء', '0500000000', NULL, NULL, NULL, '$2y$10$w3MYbJ5VSeZVcbZ2LdioA.tMnR4hfwt1.n2QWQL7UYLHBWpaD59tm', 'admin', 0, NOW(), NOW()),
(2, 'عميل تجريبي', '0501234567', 'test@lamaa.sa', 'شارع الملك فهد', 'الرياض', '$2y$10$w3MYbJ5VSeZVcbZ2LdioA.tMnR4hfwt1.n2QWQL7UYLHBWpaD59tm', 'customer', 3, NOW(), NOW());

-- Password for both: admin123

-- ----------------------------------------------------------------
-- SERVICES DATA (with categories from price list)
-- ----------------------------------------------------------------
INSERT INTO `services` (`name_ar`, `name_en`, `icon`, `category`, `base_price`, `description`, `created_at`, `updated_at`) VALUES
-- ثياب تقليدية
('ثوب أبيض',     'White Thobe',     'thobe',  'ثياب_تقليدية', 4,  'express:9',  NOW(), NOW()),
('ثوب ملون',      'Colored Thobe',   'thobe',  'ثياب_تقليدية', 5,  'express:7',  NOW(), NOW()),
('ثوب صوف',       'Wool Thobe',      'thobe',  'ثياب_تقليدية', 7,  'express:10', NOW(), NOW()),
('غترة',          'Ghutra',          'head',   'ثياب_تقليدية', 4,  'express:6',  NOW(), NOW()),
('شماغ',          'Shimagh',         'head',   'ثياب_تقليدية', 4,  'express:8',  NOW(), NOW()),
('طاقية',         'Taqia (Cap)',     'cap',    'ثياب_تقليدية', 1,  NULL,         NOW(), NOW()),
-- ملابس عامة
('قميص',          'Shirt',           'shirt',  'ملابس_عامة',   4,  'express:6',  NOW(), NOW()),
('بنطلون',        'Pants',           'pants',  'ملابس_عامة',   4,  'express:6',  NOW(), NOW()),
('تي شرت',        'T-Shirt',         'tshirt', 'ملابس_عامة',   4,  'express:6',  NOW(), NOW()),
('شورت',          'Shorts',          'shorts', 'ملابس_عامة',   4,  'express:6',  NOW(), NOW()),
('فانيلة رياضية', 'Sports Vest',     'sports', 'ملابس_عامة',   4,  'express:6',  NOW(), NOW()),
('فنيلة داخلية',  'Undershirt',      'inner',  'ملابس_عامة',   2,  'express:3',  NOW(), NOW()),
('سروال قصير',    'Short Trousers',  'shorts', 'ملابس_عامة',   2,  'express:3',  NOW(), NOW()),
('سروال طويل',    'Long Trousers',   'pants',  'ملابس_عامة',   2,  'express:3',  NOW(), NOW()),
('جوارب',         'Socks',           'socks',  'ملابس_عامة',   1,  'express:2',  NOW(), NOW()),
('معطف / بالطوية','Coat / Overcoat', 'coat',   'ملابس_عامة',   5,  'express:8',  NOW(), NOW()),
('مريول',         'Apron',           'apron',  'ملابس_عامة',   5,  'express:8',  NOW(), NOW()),
-- بدل وزي
('بدلة عسكرية',   'Military Suit',   'suit',   'بدل_وزي',      8,  'express:12', NOW(), NOW()),
('بدلة صوف',      'Wool Suit',       'suit',   'بدل_وزي',      10, 'express:20', NOW(), NOW()),
('بدلة سفاري',    'Safari Suit',     'suit',   'بدل_وزي',      6,  'express:12', NOW(), NOW()),
('بدلة رياضية',   'Sports Suit',     'suit',   'بدل_وزي',      5,  'express:10', NOW(), NOW()),
('بدلة باكستانية','Pakistani Suit',  'suit',   'بدل_وزي',      4,  'express:6',  NOW(), NOW()),
-- ثياب خارجية
('جاكيت',         'Jacket',          'jacket', 'ثياب_خارجية',  8,  'express:15', NOW(), NOW()),
('بلوزة حرير',    'Silk Blouse',     'blouse', 'ثياب_خارجية',  5,  'express:8',  NOW(), NOW()),
('فستان',         'Dress',           'dress',  'ثياب_خارجية',  7,  'express:40', NOW(), NOW()),
('تنورة',         'Skirt',           'dress',  'ثياب_خارجية',  5,  NULL,         NOW(), NOW()),
('بشت / فرو',    'Bisht / Fur',     'coat',   'ثياب_خارجية',  20, 'express:40', NOW(), NOW()),
('قميص حرير',     'Silk Shirt',      'shirt',  'ثياب_خارجية',  5,  'express:8',  NOW(), NOW()),
('عباية',         'Abaya',           'abaya',  'ثياب_خارجية',  10, 'express:20', NOW(), NOW()),
('طرحة / نقاب',  'Hijab / Niqab',  'hijab',  'ثياب_خارجية',  3,  'express:6',  NOW(), NOW()),
('قميص نوم',      'Nightgown',       'nightgown','ثياب_خارجية',5,  'express:8',  NOW(), NOW()),
('ستار',          'Curtain',         'curtain','ثياب_خارجية',  15, 'express:30', NOW(), NOW()),
-- مفروشات
('شرشف مزدوج',   'Double Bed Sheet','sheet',  'مفروشات',      10, 'express:15', NOW(), NOW()),
('شرشف مفرد',    'Single Bed Sheet','sheet',  'مفروشات',      7,  'express:10', NOW(), NOW()),
('كيس مخدة',     'Pillow Case',     'pillow', 'مفروشات',      1,  'express:4',  NOW(), NOW()),
('روب حمام',      'Bath Robe',       'robe',   'مفروشات',      8,  'express:15', NOW(), NOW()),
('بيجامة',        'Pajama',          'pajama', 'مفروشات',      8,  'express:12', NOW(), NOW()),
('فانيلية صوف',   'Wool Vest',       'wool',   'مفروشات',      5,  'express:8',  NOW(), NOW()),
('منشفة / منديل','Towel',           'towel',  'مفروشات',      5,  'express:8',  NOW(), NOW()),
('مفرش',          'Mattress Cover',  'mattress','مفروشات',     25, 'express:45', NOW(), NOW()),
('سجاد / موكيت', 'Carpet / Rug',   'carpet', 'مفروشات',      10, 'express:25', NOW(), NOW()),
('بطانية',        'Blanket',         'blanket','مفروشات',      10, 'express:25', NOW(), NOW()),
('شوار',          'Duvet',           'duvet',  'مفروشات',      25, NULL,         NOW(), NOW()),
('إبداء',         'Mattress',        'mattress','مفروشات',     50, NULL,         NOW(), NOW());

-- ----------------------------------------------------------------
-- MIGRATIONS DATA
-- ----------------------------------------------------------------
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2024_01_01_000000_create_users_table', 1),
('2024_01_01_000001_add_fields_to_users_table', 1),
('2024_01_01_000002_create_services_table', 1),
('2024_01_01_000003_create_orders_table', 1),
('2024_01_01_000004_create_order_notifications_table', 1),
('2024_01_01_000005_add_region_to_users_table', 1),
('2024_01_01_000006_add_category_to_services_table', 1);

COMMIT;
