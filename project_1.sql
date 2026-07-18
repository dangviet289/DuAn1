-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 14, 2026 at 02:02 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `du_an1`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `du_an1`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `du_an1`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `admin_id` int NOT NULL,
  `action` varchar(100) NOT NULL,
  `target_id` int DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `admin_id`, `action`, `target_id`, `description`, `created_at`) VALUES
(1, 2, 'update_product', 1, 'Cập nhật giá sản phẩm iPhone 15 Pro Max', '2026-07-14 09:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(500) NOT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `receiver_name`, `phone`, `address`, `ward`, `district`, `city`, `is_default`) VALUES
(1, 1, 'Nguyen Van A', '0909123456', '123 Nguyen Trai', 'Phường 5', 'Quận 5', 'TP.HCM', 1);

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int NOT NULL,
  `image` varchar(500) NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `image`, `link`, `title`, `is_active`, `sort_order`) VALUES
(1, 'https://cdn.tgdd.vn/banner1.jpg', '?act=product-detail&slug=iphone-15-pro-max', 'Khuyến mãi iPhone 15', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `logo`) VALUES
(1, 'Apple', 'apple', NULL),
(2, 'Samsung', 'samsung', NULL),
(3, 'Google', 'google', NULL),
(4, 'Dell', 'dell', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `variant_id` int DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `variant_id`, `quantity`, `created_at`) VALUES
(1, 1, 1, 1, 2, '2026-07-14 09:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `parent_id`, `is_active`) VALUES
(1, 'Điện thoại', 'dien-thoai', 'uploads/category-phone.svg', NULL, 1),
(2, 'Laptop', 'laptop', 'uploads/category-laptop.svg', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('percent','fixed') NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `min_order_value` decimal(15,2) DEFAULT '0.00',
  `max_discount` decimal(15,2) DEFAULT NULL,
  `quantity` int DEFAULT '0',
  `used` int DEFAULT '0',
  `expired_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_order_value`, `max_discount`, `quantity`, `used`, `expired_at`, `is_active`) VALUES
(1, 'SALE10', 'percent', 10.00, 5000000.00, 2000000.00, 100, 5, '2026-12-31 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `shipping_fee` decimal(15,2) DEFAULT '0.00',
  `discount` decimal(15,2) DEFAULT '0.00',
  `total_price` decimal(15,2) NOT NULL,
  `status` enum('pending','confirmed','shipping','completed','cancelled') DEFAULT 'pending',
  `payment_method` enum('cod','bank_transfer','momo','vnpay') DEFAULT 'cod',
  `payment_status` enum('unpaid','paid','refunded') DEFAULT 'unpaid',
  `receiver_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(500) NOT NULL,
  `note` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `sub_total`, `shipping_fee`, `discount`, `total_price`, `status`, `payment_method`, `payment_status`, `receiver_name`, `phone`, `address`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 25000000.00, 30000.00, 0.00, 25030000.00, 'pending', 'cod', 'unpaid', 'Nguyen Van A', '0909123456', '123 Nguyen Trai, Quận 5, TP.HCM', NULL, '2026-07-14 09:01:33', '2026-07-14 09:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `variant_id` int DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `color` varchar(100) DEFAULT NULL,
  `storage` varchar(50) DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variant_id`, `product_name`, `color`, `storage`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 'iPhone 15 Pro Max', 'Titan Xanh', '256GB', 1, 25000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) DEFAULT NULL,
  `category_id` int NOT NULL,
  `brand_id` int NOT NULL,
  `description` text,
  `stock` int DEFAULT '0',
  `rating` decimal(2,1) DEFAULT '0.0',
  `review_count` int DEFAULT '0',
  `sold` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `thumbnail`, `price`, `sale_price`, `category_id`, `brand_id`, `description`, `stock`, `rating`, `review_count`, `sold`, `is_active`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 'iPhone 15 Pro Max', 'iphone-15-pro-max', 'https://cdn.tgdd.vn/Products/Images/42/247863/iphone-15-pro-max-xanh-duong-600x600.jpg', 25000000.00, 23990000.00, 1, 1, 'iPhone 15 Pro Max là điện thoại thông minh cao cấp được Apple ra mắt năm 2023, thiết kế khung Titan sang trọng.', 30, 4.8, 12, 120, 1, 1, '2026-07-14 09:01:33', '2026-07-14 09:01:33'),
(2, 'Samsung Galaxy S24', 'samsung-galaxy-s24', 'https://cdn.tgdd.vn/Products/Images/42/307174/samsung-galaxy-s24-xam-thumb-600x600.jpg', 22000000.00, 20990000.00, 1, 2, 'Samsung Galaxy S24 tích hợp AI, hiệu năng mạnh mẽ, ra mắt năm 2024.', 23, 4.6, 8, 80, 1, 1, '2026-07-14 09:01:33', '2026-07-14 09:01:33'),
(3, 'Google Pixel 8', 'google-pixel-8', 'https://cdn.tgdd.vn/Products/Images/42/305658/google-pixel-8-thumb-600x600.jpg', 18000000.00, NULL, 1, 3, 'Google Pixel 8 với camera AI xử lý ảnh thông minh vượt trội, ra mắt năm 2024.', 12, 4.5, 5, 40, 1, 0, '2026-07-14 09:01:33', '2026-07-14 09:01:33'),
(4, 'MacBook Air 13 M3', 'macbook-air-13-m3', 'uploads/macbook-air-m3.svg', 28990000.00, 27490000.00, 2, 1, 'MacBook Air 13 inch M3 có thiết kế mỏng nhẹ, màn hình Liquid Retina sắc nét và thời lượng pin dài, phù hợp cho học tập và làm việc.', 18, 4.9, 16, 62, 1, 1, '2026-07-15 08:30:00', '2026-07-15 08:30:00'),
(5, 'Dell Inspiron 15 3530', 'dell-inspiron-15-3530', 'uploads/dell-inspiron-15.svg', 18990000.00, 17490000.00, 2, 4, 'Dell Inspiron 15 3530 sở hữu màn hình lớn 15.6 inch, hiệu năng ổn định và bàn phím đầy đủ, phù hợp cho văn phòng và học tập.', 14, 4.6, 9, 35, 1, 0, '2026-07-16 10:00:00', '2026-07-16 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `sort_order`) VALUES
(1, 1, 'https://cdn.tgdd.vn/Products/Images/42/247863/iphone-15-pro-max-xanh-duong-600x600.jpg', 1),
(2, 1, 'https://cdn.tgdd.vn/Products/Images/42/247863/iphone-15-pro-max-1.jpg', 2),
(3, 1, 'https://cdn.tgdd.vn/Products/Images/42/247863/iphone-15-pro-max-2.jpg', 3),
(4, 2, 'https://cdn.tgdd.vn/Products/Images/42/307174/samsung-galaxy-s24-xam-thumb-600x600.jpg', 1),
(5, 3, 'https://cdn.tgdd.vn/Products/Images/42/305658/google-pixel-8-thumb-600x600.jpg', 1),
(6, 4, 'uploads/macbook-air-m3.svg', 1),
(7, 5, 'uploads/dell-inspiron-15.svg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_specifications`
--

CREATE TABLE `product_specifications` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `spec_name` varchar(255) NOT NULL,
  `spec_value` varchar(500) NOT NULL,
  `sort_order` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_specifications`
--

INSERT INTO `product_specifications` (`id`, `product_id`, `spec_name`, `spec_value`, `sort_order`) VALUES
(1, 1, 'screen', '6.7 inch, Super Retina XDR OLED', 1),
(2, 1, 'chip', 'Apple A17 Pro', 2),
(3, 1, 'battery', '4422 mAh', 3),
(4, 1, 'camera', '48MP + 12MP + 12MP', 4),
(5, 2, 'screen', '6.2 inch, Dynamic AMOLED 2X', 1),
(6, 2, 'chip', 'Snapdragon 8 Gen 3', 2),
(7, 2, 'battery', '4000 mAh', 3),
(8, 2, 'camera', '50MP + 12MP + 10MP', 4),
(9, 3, 'screen', '6.2 inch, OLED', 1),
(10, 3, 'chip', 'Google Tensor G3', 2),
(11, 3, 'battery', '4575 mAh', 3),
(12, 3, 'camera', '50MP + 12MP', 4),
(13, 4, 'screen', '13.6 inch, Liquid Retina', 1),
(14, 4, 'chip', 'Apple M3 8 nhân', 2),
(15, 4, 'ram', '16GB', 3),
(16, 4, 'storage', 'SSD 256GB', 4),
(17, 4, 'weight', '1.24 kg', 5),
(18, 5, 'screen', '15.6 inch, Full HD 120Hz', 1),
(19, 5, 'chip', 'Intel Core i5-1334U', 2),
(20, 5, 'ram', '16GB DDR4', 3),
(21, 5, 'storage', 'SSD 512GB', 4),
(22, 5, 'weight', '1.62 kg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `storage` varchar(50) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `stock` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `color`, `storage`, `price`, `stock`) VALUES
(1, 1, 'IP15PM-XD-256', 'Titan Xanh', '256GB', 25000000.00, 20),
(2, 1, 'IP15PM-DEN-512', 'Titan Đen', '512GB', 29000000.00, 10),
(3, 2, 'S24-XAM-128', 'Xám Titan', '128GB', 22000000.00, 15),
(4, 2, 'S24-TIM-256', 'Tím', '256GB', 24500000.00, 8),
(5, 3, 'PIXEL8-DEN-128', 'Đen', '128GB', 18000000.00, 12),
(6, 4, 'MBA-M3-XAM-256', 'Xám không gian', '256GB', 27490000.00, 10),
(7, 4, 'MBA-M3-BAC-512', 'Bạc', '512GB', 32990000.00, 8),
(8, 5, 'DELL3530-DEN-512', 'Đen', '512GB', 17490000.00, 14);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `rating` tinyint NOT NULL,
  `comment` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `order_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, 1, 5, 'Sản phẩm tốt, giao hàng nhanh', '2026-07-14 09:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `review_images`
--

CREATE TABLE `review_images` (
  `id` int NOT NULL,
  `review_id` int NOT NULL,
  `image_url` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
('admin', 'Quản trị viên'),
('customer', 'Khách hàng'),
('staff', 'Nhân viên');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int NOT NULL,
  `role_id` varchar(20) NOT NULL,
  `permission` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission`) VALUES
(18, 'admin', 'manage_banners'),
(13, 'admin', 'manage_brands'),
(12, 'admin', 'manage_categories'),
(17, 'admin', 'manage_coupons'),
(14, 'admin', 'manage_orders'),
(11, 'admin', 'manage_products'),
(19, 'admin', 'manage_settings'),
(16, 'admin', 'manage_staff'),
(15, 'admin', 'manage_users'),
(20, 'admin', 'view_activity_logs'),
(21, 'admin', 'view_reports'),
(1, 'customer', 'add_to_cart'),
(2, 'customer', 'checkout'),
(5, 'customer', 'manage_own_profile'),
(6, 'customer', 'manage_wishlist'),
(3, 'customer', 'view_own_orders'),
(4, 'customer', 'write_review'),
(8, 'staff', 'manage_orders'),
(7, 'staff', 'manage_products'),
(10, 'staff', 'reply_review'),
(9, 'staff', 'view_customers');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL DEFAULT '1',
  `shop_name` varchar(255) NOT NULL,
  `hotline` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `shipping_fee` decimal(15,2) DEFAULT '0.00',
  `free_ship_from` decimal(15,2) DEFAULT '0.00'
) ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `shop_name`, `hotline`, `email`, `address`, `shipping_fee`, `free_ship_from`) VALUES
(1, 'MyShop', '1900 6750', 'support@myshop.com', '123 Nguyen Trai, HN', 30000.00, 3000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role_id` varchar(20) NOT NULL DEFAULT 'customer',
  `avatar` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `role_id`, `avatar`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Nguyen Van A', 'a@gmail.com', '123456', '0909123456', 'customer', NULL, 1, '2026-07-14 09:01:33', '2026-07-14 09:01:33'),
(2, 'Admin', 'admin@gmail.com', 'admin123', '0909999999', 'admin', NULL, 1, '2026-07-14 09:01:33', '2026-07-14 09:01:33'),
(3, 'Le Van Staff', 'staff@gmail.com', 'staff123', '0909111222', 'staff', NULL, 1, '2026-07-14 09:01:33', '2026-07-14 09:01:33');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(1, 1, 3, '2026-07-14 09:01:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_brand` (`brand_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `idx_reviews_product` (`product_id`);

--
-- Indexes for table `review_images`
--
ALTER TABLE `review_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_id` (`review_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_role_permission` (`role_id`,`permission`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_wishlist` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_images`
--
ALTER TABLE `review_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `review_images`
--
ALTER TABLE `review_images`
  ADD CONSTRAINT `review_images_ibfk_1` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
