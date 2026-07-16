-- ====================================================================
-- lamaa_patch_v5.sql
-- ملف تصحيح قاعدة بيانات لماء (يضيف فقط الأعمدة/الجداول الناقصة)
-- آمن للاستيراد فوق قاعدة بيانات lamaa الحالية بدون حذف أي بيانات
-- استورده من phpMyAdmin -> اختر قاعدة بيانات lamaa -> تبويب Import
-- ====================================================================

-- 1) أعمدة نظام الولاء والأدوار في جدول users
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `total_pieces_washed` INT NOT NULL DEFAULT 0 AFTER `total_orders`,
  ADD COLUMN IF NOT EXISTS `free_pieces_earned` INT NOT NULL DEFAULT 0 AFTER `total_pieces_washed`,
  ADD COLUMN IF NOT EXISTS `country_code` VARCHAR(255) NOT NULL DEFAULT '+966' AFTER `phone`,
  ADD COLUMN IF NOT EXISTS `created_by` BIGINT UNSIGNED NULL AFTER `role`;

-- إضافة القيد الأجنبي لعمود created_by (يشير إلى users.id) - يتم تجاهله إن كان موجودًا مسبقًا
SET @fk_exists = (
  SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA = DATABASE()
  AND TABLE_NAME = 'users'
  AND CONSTRAINT_NAME = 'users_created_by_foreign'
);
SET @sql = IF(@fk_exists = 0,
  'ALTER TABLE `users` ADD CONSTRAINT `users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL',
  'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 2) أعمدة جديدة في جدول orders
ALTER TABLE `orders`
  ADD COLUMN IF NOT EXISTS `service_mode` VARCHAR(255) NOT NULL DEFAULT 'wash_iron' AFTER `service_id`,
  ADD COLUMN IF NOT EXISTS `city` VARCHAR(255) NULL AFTER `address`,
  ADD COLUMN IF NOT EXISTS `district` VARCHAR(255) NULL AFTER `city`,
  ADD COLUMN IF NOT EXISTS `building_name` VARCHAR(255) NULL AFTER `district`,
  ADD COLUMN IF NOT EXISTS `building_number` VARCHAR(255) NULL AFTER `building_name`,
  ADD COLUMN IF NOT EXISTS `address_notes` TEXT NULL AFTER `building_number`,
  ADD COLUMN IF NOT EXISTS `payment_method` VARCHAR(255) NOT NULL DEFAULT 'cash' AFTER `total_price`,
  ADD COLUMN IF NOT EXISTS `coupon_code` VARCHAR(255) NULL AFTER `payment_method`,
  ADD COLUMN IF NOT EXISTS `discount_amount` DOUBLE NOT NULL DEFAULT 0 AFTER `coupon_code`,
  ADD COLUMN IF NOT EXISTS `subtotal_price` DOUBLE NOT NULL DEFAULT 0 AFTER `discount_amount`,
  ADD COLUMN IF NOT EXISTS `free_delivery` TINYINT(1) NOT NULL DEFAULT 0 AFTER `express_delivery`,
  ADD COLUMN IF NOT EXISTS `is_pos_order` TINYINT(1) NOT NULL DEFAULT 0 AFTER `free_delivery`,
  ADD COLUMN IF NOT EXISTS `free_pieces_count` INT NOT NULL DEFAULT 0 AFTER `is_pos_order`;

-- 3) جدول order_items (تفاصيل قطع الطلب)
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `service_id` BIGINT UNSIGNED NULL,
  `service_name` VARCHAR(255) NOT NULL,
  `service_name_ar` VARCHAR(255) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `free_quantity` INT NOT NULL DEFAULT 0,
  `unit_price` DOUBLE NOT NULL,
  `subtotal` DOUBLE NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_service_id_foreign` (`service_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4) جدول coupons (الكوبونات)
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(255) NOT NULL,
  `percentage` DOUBLE NOT NULL,
  `usage_limit` INT NULL,
  `used_count` INT NOT NULL DEFAULT 0,
  `expires_at` DATE NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5) جدول packages (الباقات)
CREATE TABLE IF NOT EXISTS `packages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_ar` VARCHAR(255) NOT NULL,
  `name_en` VARCHAR(255) NOT NULL,
  `price` DOUBLE NOT NULL,
  `pieces_included` INT NOT NULL DEFAULT 0,
  `free_delivery` TINYINT(1) NOT NULL DEFAULT 0,
  `description_ar` TEXT NULL,
  `description_en` TEXT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6) أعمدة أسعار إضافية في جدول services
ALTER TABLE `services`
  ADD COLUMN IF NOT EXISTS `price_wash_only` DOUBLE NULL AFTER `base_price`,
  ADD COLUMN IF NOT EXISTS `price_iron_only` DOUBLE NULL AFTER `price_wash_only`;

-- 7) عمود audience في جدول order_notifications + جعل customer_id يقبل NULL
ALTER TABLE `order_notifications`
  ADD COLUMN IF NOT EXISTS `audience` VARCHAR(255) NOT NULL DEFAULT 'customer' AFTER `customer_id`;

ALTER TABLE `order_notifications`
  MODIFY COLUMN `customer_id` BIGINT UNSIGNED NULL;

-- ====================================================================
-- انتهى التصحيح. لا حاجة لتشغيل php artisan migrate بعد هذا الاستيراد
-- لأن هذا الملف يضيف تمامًا نفس الأعمدة/الجداول التي كانت تنقص قاعدة بياناتك
-- ====================================================================
