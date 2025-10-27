-- -------------------------------------------------------------
-- TablePlus 6.6.8(632)
--
-- https://tableplus.com/
--
-- Database: ecommerce2025
-- Generation Time: 2025-09-19 14:58:06.7860
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `max_uses` int(10) unsigned DEFAULT NULL,
  `used` int(10) unsigned DEFAULT 0,
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `fk_coupons_user_id` (`user_id`),
  CONSTRAINT `fk_coupons_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'processing',
  `payment_method` enum('COD','online') NOT NULL DEFAULT 'COD',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping_status` varchar(255) NOT NULL DEFAULT 'not_shipped',
  `order_code` varchar(255) DEFAULT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(12,2) NOT NULL,
  `features` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `points` int(11) DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE `wishlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'DIOR', '2025-09-08 06:45:07', '2025-09-08 06:45:07'),
(2, 'Túi xách công sở', '2025-09-15 08:42:06', '2025-09-15 08:42:06'),
(3, 'Túi đeo chéo', '2025-09-15 08:42:06', '2025-09-15 08:42:06'),
(4, 'Túi xách tay', '2025-09-15 08:42:06', '2025-09-15 08:42:06'),
(5, 'Túi tote', '2025-09-15 08:42:06', '2025-09-15 08:42:06'),
(6, 'Túi clutch', '2025-09-15 08:42:06', '2025-09-15 08:42:06');

INSERT INTO `coupons` (`id`, `user_id`, `code`, `type`, `value`, `max_uses`, `used`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, NULL, '1234567', 'percent', 12.00, 3, 2, '2025-09-18 13:34:00', '2025-09-15 06:35:20', '2025-09-18 09:10:02'),
(2, NULL, '123', 'percent', 15.00, 1, 1, '2025-09-19 13:50:00', '2025-09-15 06:50:15', '2025-09-15 06:50:27'),
(3, NULL, '09', 'percent', 90.00, 1, 1, '2025-09-21 14:53:00', '2025-09-15 07:53:42', '2025-09-15 07:53:47'),
(4, 3, 'VOUCHER_68CBB4E45D8D5', 'voucher', 70000.00, 1, 1, '2026-03-18 14:29:40', '2025-09-18 14:29:40', '2025-09-18 15:04:13'),
(5, 3, 'points_earned_47', 'points_marker', 0.00, 1, 1, '2035-09-18 14:46:00', '2025-09-18 14:46:00', '2025-09-18 14:46:00'),
(6, 3, 'VOUCHER_68CBB8C34F7C3', 'voucher', 10000.00, 1, 1, '2026-03-18 14:46:11', '2025-09-18 14:46:11', '2025-09-18 16:10:17'),
(7, 3, 'points_earned_48', 'points_marker', 0.00, 1, 1, '2035-09-18 14:47:19', '2025-09-18 14:47:19', '2025-09-18 14:47:19'),
(8, 3, 'VOUCHER_68CBBA6CC0E3E', 'voucher', 60000.00, 1, 1, '2026-03-18 14:53:16', '2025-09-18 14:53:16', '2025-09-18 16:06:22'),
(9, 3, 'points_earned_49', 'points_marker', 0.00, 1, 1, '2035-09-18 14:58:26', '2025-09-18 14:58:26', '2025-09-18 14:58:26'),
(10, 3, 'points_earned_50', 'points_marker', 0.00, 1, 1, '2035-09-18 15:00:02', '2025-09-18 15:00:02', '2025-09-18 15:00:02'),
(11, 3, 'points_earned_51', 'points_marker', 0.00, 1, 1, '2035-09-18 15:03:45', '2025-09-18 15:03:45', '2025-09-18 15:03:45'),
(12, 3, 'points_earned_52', 'points_marker', 0.00, 1, 1, '2035-09-18 15:53:39', '2025-09-18 15:53:39', '2025-09-18 15:53:39'),
(13, 3, 'VOUCHER_68CBCB73C3195', 'voucher', 10000.00, 1, 1, '2026-03-18 16:05:55', '2025-09-18 16:05:55', '2025-09-18 16:06:09');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_24_050636_create_categories_table', 1),
(5, '2025_08_24_055603_create_products_table', 1),
(6, '2025_08_25_015151_create_orders_table', 1),
(7, '2025_08_25_015152_create_order_items_table', 1);

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(18, 18, 1, 1, 100000.00, '2025-09-10 07:10:44', '2025-09-10 07:10:44'),
(19, 19, 1, 1, 100000.00, '2025-09-10 07:12:51', '2025-09-10 07:12:51'),
(20, 20, 1, 1, 100000.00, '2025-09-10 07:13:24', '2025-09-10 07:13:24'),
(25, 25, 1, 2, 100000.00, '2025-09-10 07:51:11', '2025-09-10 07:51:11'),
(26, 26, 10, 1, 650000.00, '2025-09-15 01:52:14', '2025-09-15 01:52:14'),
(27, 27, 11, 1, 790000.00, '2025-09-15 02:01:38', '2025-09-15 02:01:38'),
(28, 28, 2, 1, 950000.00, '2025-09-15 02:04:00', '2025-09-15 02:04:00'),
(29, 28, 9, 1, 1350000.00, '2025-09-15 02:04:00', '2025-09-15 02:04:00'),
(30, 29, 10, 1, 650000.00, '2025-09-15 06:01:45', '2025-09-15 06:01:45'),
(31, 30, 10, 1, 650000.00, '2025-09-15 06:36:31', '2025-09-15 06:36:31'),
(32, 30, 2, 1, 950000.00, '2025-09-15 06:36:31', '2025-09-15 06:36:31'),
(33, 31, 4, 1, 450000.00, '2025-09-15 06:44:45', '2025-09-15 06:44:45'),
(34, 31, 2, 1, 950000.00, '2025-09-15 06:44:45', '2025-09-15 06:44:45'),
(35, 32, 11, 1, 790000.00, '2025-09-15 06:49:27', '2025-09-15 06:49:27'),
(36, 32, 2, 1, 950000.00, '2025-09-15 06:49:27', '2025-09-15 06:49:27'),
(37, 33, 10, 1, 650000.00, '2025-09-15 06:50:35', '2025-09-15 06:50:35'),
(38, 34, 3, 1, 1250000.00, '2025-09-15 07:53:57', '2025-09-15 07:53:57'),
(39, 35, 4, 2, 450000.00, '2025-09-16 01:13:53', '2025-09-16 01:13:53'),
(40, 36, 10, 1, 650000.00, '2025-09-16 08:29:32', '2025-09-16 08:29:32'),
(41, 37, 10, 1, 650000.00, '2025-09-16 08:45:39', '2025-09-16 08:45:39'),
(42, 38, 3, 1, 1250000.00, '2025-09-16 09:25:11', '2025-09-16 09:25:11'),
(43, 39, 4, 1, 450000.00, '2025-09-16 09:30:47', '2025-09-16 09:30:47'),
(44, 40, 11, 1, 790000.00, '2025-09-18 09:10:10', '2025-09-18 09:10:10'),
(45, 41, 2, 1, 950000.00, '2025-09-18 09:15:00', '2025-09-18 09:15:00'),
(46, 42, 3, 1, 1250000.00, '2025-09-18 14:25:40', '2025-09-18 14:25:40'),
(47, 43, 3, 5, 1250000.00, '2025-09-18 14:26:22', '2025-09-18 14:26:22'),
(48, 44, 2, 1, 950000.00, '2025-09-18 14:31:14', '2025-09-18 14:31:14'),
(49, 45, 11, 1, 790000.00, '2025-09-18 14:41:07', '2025-09-18 14:41:07'),
(50, 46, 5, 1, 350000.00, '2025-09-18 14:43:05', '2025-09-18 14:43:05'),
(51, 47, 4, 1, 450000.00, '2025-09-18 14:45:55', '2025-09-18 14:45:55'),
(52, 48, 6, 6, 890000.00, '2025-09-18 14:47:02', '2025-09-18 14:47:02'),
(53, 49, 4, 1, 450000.00, '2025-09-18 14:58:19', '2025-09-18 14:58:19'),
(54, 50, 5, 1, 350000.00, '2025-09-18 14:59:52', '2025-09-18 14:59:52'),
(55, 51, 5, 1, 350000.00, '2025-09-18 15:03:31', '2025-09-18 15:03:31'),
(56, 52, 4, 1, 450000.00, '2025-09-18 15:05:54', '2025-09-18 15:05:54'),
(57, 53, 9, 1, 1350000.00, '2025-09-18 08:57:15', '2025-09-18 08:57:15'),
(58, 54, 5, 1, 350000.00, '2025-09-18 08:57:49', '2025-09-18 08:57:49'),
(59, 55, 9, 1, 1350000.00, '2025-09-18 08:58:49', '2025-09-18 08:58:49'),
(60, 56, 4, 1, 450000.00, '2025-09-18 16:12:07', '2025-09-18 16:12:07');

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `payment_method`, `created_at`, `updated_at`, `shipping_status`, `order_code`, `total_price`, `name`, `address`, `phone`) VALUES
(18, 3, NULL, 'cod_ordered', 'COD', '2025-09-10 07:10:44', '2025-09-18 09:17:44', 'packaged', NULL, 100000.00, 'DAT', 'HANOI', '0987654321'),
(19, 3, NULL, 'cod_ordered', 'COD', '2025-09-10 07:12:51', '2025-09-18 10:12:07', 'packaged', NULL, 100000.00, 'THANH', 'HANOI', '0987654321'),
(20, 3, NULL, 'paid_momo', 'online', '2025-09-10 07:13:24', '2025-09-10 07:42:46', 'packaged', NULL, 100000.00, 'YẾN BẠ', 'HANOI', '0987654321'),
(25, 3, NULL, 'cod_ordered', 'COD', '2025-09-10 07:51:11', '2025-09-15 01:30:31', 'completed', NULL, 200000.00, 'DAT', 'HN', '0987654321'),
(26, 3, NULL, 'Thanh toán MoMo không thành công', 'online', '2025-09-15 01:52:14', '2025-09-15 01:52:26', 'not_shipped', NULL, 650000.00, 'VIET ANH', 'HN', '0987654321'),
(27, 3, NULL, 'paid_momo', 'online', '2025-09-15 02:01:38', '2025-09-15 02:22:27', 'completed', NULL, 790000.00, 'HN2', 'HN', '0987654321'),
(28, 3, NULL, 'cod_ordered', 'COD', '2025-09-15 02:04:00', '2025-09-15 02:22:24', 'completed', NULL, 2300000.00, 'DAT', 'HN', '0123456789'),
(29, 3, NULL, 'paid_momo', 'online', '2025-09-15 06:01:45', '2025-09-15 06:08:17', 'not_shipped', NULL, 650000.00, 'DAT', 'HN', '0987654321'),
(30, 4, NULL, 'cod_ordered', 'COD', '2025-09-15 06:36:31', '2025-09-15 06:36:31', 'not_shipped', NULL, 1600000.00, 'DAT', 'HN', '09854321'),
(31, 4, NULL, 'cod_ordered', 'COD', '2025-09-15 06:44:45', '2025-09-15 06:44:59', 'completed', NULL, 1400000.00, 'HN', 'HN', '0987654321'),
(32, 4, NULL, 'cod_ordered', 'COD', '2025-09-15 06:49:27', '2025-09-15 06:49:27', 'not_shipped', NULL, 1531200.00, 'DAT', 'HN', '0987654321'),
(33, 4, NULL, 'cod_ordered', 'COD', '2025-09-15 06:50:35', '2025-09-15 07:55:04', 'shipping', NULL, 552500.00, 'DAT', 'HN', '0987654321'),
(34, 6, NULL, 'paid_momo', 'online', '2025-09-15 07:53:57', '2025-09-15 07:54:54', 'shipping', NULL, 125000.00, 'dat', 'hn', '0987654321'),
(35, 3, NULL, 'cod_ordered', 'COD', '2025-09-16 01:13:53', '2025-09-16 01:13:53', 'not_shipped', NULL, 792000.00, 'DAT', 'HN', '0987654321'),
(36, 3, NULL, 'cod_ordered', 'COD', '2025-09-16 08:29:32', '2025-09-16 08:29:32', 'not_shipped', NULL, 650000.00, 'DAT', 'HN', '0987654321'),
(37, 3, NULL, 'paid_momo', 'online', '2025-09-16 08:45:39', '2025-09-18 10:12:16', 'packaged', NULL, 650000.00, 'DTA', 'HN', '0987654321'),
(38, 7, NULL, 'Thanh toán MoMo không thành công', 'online', '2025-09-16 09:25:11', '2025-09-16 09:25:25', 'not_shipped', NULL, 1100000.00, 'DAT', 'HN', '0987654321'),
(39, 7, NULL, 'Thanh toán MoMo không thành công', 'online', '2025-09-16 09:30:47', '2025-09-16 09:31:01', 'not_shipped', NULL, 396000.00, 'ádfg', 'hn', '0987654321'),
(40, 3, NULL, 'Thanh toán MoMo không thành công', 'online', '2025-09-18 09:10:10', '2025-09-18 10:36:56', 'packaged', NULL, 695200.00, 'DAT', 'HN', '098765432'),
(41, 3, NULL, 'Thanh toán MoMo không thành công', 'online', '2025-09-18 09:15:00', '2025-09-18 16:04:48', 'packaged', 'GY9R3BB6', 836000.00, 'DAT', 'HN', '0987654321'),
(42, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 14:25:40', '2025-09-18 14:25:40', 'not_shipped', NULL, 1250000.00, 'DAT', 'HN', '098765432'),
(43, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 14:26:22', '2025-09-18 14:26:22', 'not_shipped', NULL, 6250000.00, 'Dt', 'hn', '0987654321'),
(44, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 14:31:14', '2025-09-18 14:31:14', 'not_shipped', NULL, 880000.00, 'DAT', 'HN', '098765432'),
(45, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 14:41:07', '2025-09-18 14:42:39', 'completed', NULL, 790000.00, 'HN', 'HN', '09765321'),
(46, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 14:43:05', '2025-09-18 14:43:11', 'completed', NULL, 350000.00, 'DT1', 'HN', '0987654321'),
(47, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 14:45:55', '2025-09-18 14:46:00', 'completed', NULL, 450000.00, 'DAT', 'HN', '0987654321'),
(48, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 14:47:02', '2025-09-18 14:47:19', 'completed', NULL, 5270000.00, 'DAT`', 'HN', '0987654321'),
(49, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 14:58:19', '2025-09-18 14:58:26', 'completed', NULL, 450000.00, 'HN', 'HN', '0987654321'),
(50, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 14:59:52', '2025-09-18 15:00:02', 'completed', NULL, 280000.00, 'HN', 'HN', '0987654321'),
(51, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 15:03:31', '2025-09-18 15:03:45', 'completed', NULL, 350000.00, 'HN', 'HN', '0987654321'),
(52, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 15:05:54', '2025-09-18 15:53:39', 'completed', '5ENLKKHD', 450000.00, 'HN', 'HN', '098765432'),
(53, 3, NULL, 'Thanh toán MoMo không thành công', 'online', '2025-09-18 08:57:15', '2025-09-18 08:57:25', 'not_shipped', NULL, 1350000.00, 'HN', 'HN', '09876521'),
(54, 3, NULL, 'Thanh toán MoMo không thành công', 'online', '2025-09-18 08:57:49', '2025-09-18 08:57:54', 'not_shipped', NULL, 350000.00, 'HN', 'HN', '09876521'),
(55, 3, NULL, 'Thanh toán MoMo không thành công', 'online', '2025-09-18 08:58:49', '2025-09-18 08:58:59', 'not_shipped', '5ENLKKHD', 1350000.00, 'HN', 'HN', '098765321'),
(56, 3, NULL, 'cod_ordered', 'COD', '2025-09-18 16:12:07', '2025-09-18 16:47:11', 'cancelled', '5ENLKKHD', 440000.00, 'DAT', 'HJ', '0987654321');

INSERT INTO `products` (`id`, `name`, `description`, `quantity`, `price`, `features`, `image`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'TÚI DIOR HỒNG', 'Hàng tồn kho', 983, 100000.00, 'Da cá sấu', 'products/QgFMrpeP7JODpQuIBcsykqmuPR5DwLzR1zfLgBas.png', 1, '2025-09-08 06:45:55', '2025-09-10 07:51:11'),
(2, 'Túi Công Sở Da Trơn', 'Túi xách da PU cao cấp, phù hợp môi trường văn phòng.', 4, 950000.00, 'Kích thước 28x20cm, 2 ngăn chính, quai cứng', 'products/G1e5KWSU9qqN13cZjzMmMLKORtbTSgR46srOtcm4.png', 1, '2025-09-15 08:42:06', '2025-09-18 14:31:14'),
(3, 'Túi Công Sở Đựng Laptop', 'Thiết kế hiện đại, đựng vừa laptop 14 inch.', 0, 1250000.00, 'Chống sốc, nhiều ngăn tiện lợi', 'products/vfBw6LZALQpfN0MVXEyXEv41GOLcWL8YEsFINMcc.png', 1, '2025-09-15 08:42:06', '2025-09-18 14:26:22'),
(4, 'Túi Đeo Chéo Mini', 'Nhỏ gọn, tiện dụng khi đi chơi.', 7, 450000.00, 'Dây đeo điều chỉnh, chất liệu da tổng hợp', 'products/eUCyD7qxUI2pfngOx1K84RZadxbeOcZe9fTrxKLK.png', 2, '2025-09-15 08:42:06', '2025-09-18 16:12:07'),
(5, 'Túi Đeo Chéo Canvas', 'Túi vải canvas trẻ trung, phong cách Hàn Quốc.', 8, 350000.00, 'Có nhiều màu sắc, khóa kéo bền', 'products/oledUOq3pTlIPCK6TaSH97uQzNkIEtwOl1wv7fqY.png', 2, '2025-09-15 08:42:06', '2025-09-18 08:57:49'),
(6, 'Túi Xách Tay Da Trơn', 'Phong cách sang trọng, phù hợp dạo phố.', 3, 890000.00, 'Da mềm, quai xách gọn gàng', 'products/baYeQOaY2QDAHks9P0oUnCjzJQoL6LnqAaCey6rV.png', 3, '2025-09-15 08:42:06', '2025-09-18 14:47:02'),
(7, 'Túi Xách Tay Khóa Vàng', 'Điểm nhấn khóa kim loại mạ vàng.', 7, 1150000.00, 'Nhiều ngăn nhỏ bên trong', 'products/nBkvozK7elcQM0vD94AsNjEvhZlhhoVuTjitJzl8.png', 3, '2025-09-15 08:42:06', '2025-09-15 01:48:30'),
(8, 'Túi Tote Vải Canvas', 'Dùng đi học, đi làm, dung tích lớn.', 20, 250000.00, 'Chịu tải tốt, giặt được', 'products/AqQ1wkS991De9gNpibpZ0eNtzHTMslyq8eYnb3VS.png', 4, '2025-09-15 08:42:06', '2025-09-15 01:49:00'),
(9, 'Túi Tote Da Cao Cấp', 'Túi tote da phù hợp phong cách tối giản.', 2, 1350000.00, 'Kích thước 35x30cm', 'products/HiSlYzY1zFcnekm4vknnzywr9RxuVGQMwK3o6PNk.png', 4, '2025-09-15 08:42:06', '2025-09-18 08:58:49'),
(10, 'Túi Clutch Dự Tiệc', 'Nhỏ gọn, sang trọng, phù hợp dự tiệc.', 0, 650000.00, 'Đính đá lấp lánh', 'products/7d0S6WxyRIGK7lItkCHhSCex6RcNnf7buC5LJt3G.png', 5, '2025-09-15 08:42:06', '2025-09-16 08:45:39'),
(11, 'Túi Clutch Da Bò', 'Thiết kế tối giản, phù hợp cả nam và nữ.', 0, 790000.00, 'Da thật 100%', 'products/M5IyHx97z3Xpbbg8MfjfrJ43tQiFtKQ0tP4LIXc3.png', 5, '2025-09-15 08:42:06', '2025-09-18 14:41:07');

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('8Nyx56GgNnf8iuTFUpEvfC3Pv4tqavK5uf1dwCCF', 3, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUnk4TFkyUG5QYTNNdHpiZ1NEdGJWNXlNR3ozUzY5N25BVjhFSW9kOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9vcmRlcnMvNTYiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MTQ6ImFwcGxpZWRfY291cG9uIjthOjQ6e3M6MjoiaWQiO2k6NjtzOjQ6ImNvZGUiO3M6MjE6IlZPVUNIRVJfNjhDQkI4QzM0RjdDMyI7czo0OiJ0eXBlIjtzOjc6InZvdWNoZXIiO3M6NToidmFsdWUiO3M6ODoiMTAwMDAuMDAiO319', 1758189035),
('QXni5iZ4fEmSlwkC4bnOkB5quTEtpmTRsvKPsYt3', 2, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTDVYblRDZ0RnekNQRDUxN3UzdEk0c29EWmFuOU5GRG9KeFZMM3M1aiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1758188933);

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `points`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2025-09-08 06:32:53', '$2y$12$NEMdjfUo6QXPMM8r70UnOuIFpC3ibjfR8sSamHwtTIl6hthVmLv/e', 'admin', 0, 'nQqqHggVWl', '2025-09-08 06:32:53', '2025-09-08 06:43:30'),
(2, 'ADMIN', 'admin@example.com', '2025-09-08 13:34:16', '$2y$12$jOSp7Mwgmznfs2eWovN4ZeqrqGjs7Q15cQaWquUUXJ2vskUe8XlOW', 'admin', 0, NULL, '2025-09-08 06:33:56', '2025-09-08 06:33:56'),
(3, 'KH1', 'kh1@example.com', '2025-09-08 13:44:23', '$2y$12$6pr0wS4Goa1V7aDyKLDDoeVfm9Gk78qELbGLyg9A1ghOM4ZDeFU82', 'user', 630, NULL, '2025-09-08 06:44:06', '2025-09-18 16:05:55'),
(4, 'TEST', 'powajix896@kwifa.com', '2025-09-15 06:09:35', '$2y$12$Zn8ou2WGh23Ck2CJUyRUUu1jjrMhsRoesw2PY8fsLmU/HokMzzeZi', 'user', 0, NULL, '2025-09-15 06:08:51', '2025-09-15 06:09:35'),
(5, 'TEST02', 'togefi5083@ishense.com', '2025-09-15 06:59:44', '$2y$12$vffLqV3WQiwaxPjCr7ri3uk7H2WV.9eV3CC61I3GlsxU0q/p2/J1G', 'user', 0, NULL, '2025-09-15 06:59:23', '2025-09-15 06:59:44'),
(6, 'TEST03', 'cotar91996@kwifa.com', '2025-09-15 07:40:01', '$2y$12$sHVHer3JQ71.4swM.fj8F.vAncec0I/PRBrVjg7lxXkL0OGwXTDnG', 'user', 0, NULL, '2025-09-15 07:39:41', '2025-09-15 07:40:01'),
(7, 'TEST', 'wayiwo1343@ishense.com', '2025-09-16 09:24:25', '$2y$12$FAXVV/9VtKG08vDkHlFkH.En7aWHPefXSmavPM2twEIuOOs10OPDq', 'user', 0, NULL, '2025-09-16 09:24:05', '2025-09-16 09:24:25'),
(8, 'TEST', 'sipeki8214@kwifa.com', NULL, '$2y$12$eflqmo7lL8FkMEAzHSxiMOE1ikKd0eHrQHY9JQsHz5e8PdSt34KS6', 'user', 0, NULL, '2025-09-16 09:47:12', '2025-09-16 09:47:12');

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(3, 3, 10, '2025-09-18 15:42:23', '2025-09-18 15:42:23'),
(4, 3, 4, '2025-09-18 15:43:31', '2025-09-18 15:43:31'),
(5, 2, 4, '2025-09-18 16:48:36', '2025-09-18 16:48:36');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;