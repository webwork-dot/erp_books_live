-- Stationery Categories Table
CREATE TABLE IF NOT EXISTS `erp_stationery_categories` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_stationery_categories_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery Categories';

-- Stationery Brands Table
CREATE TABLE IF NOT EXISTS `erp_stationery_brands` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_stationery_brands_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery Brands';

-- Stationery Colours Table
CREATE TABLE IF NOT EXISTS `erp_stationery_colours` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_stationery_colours_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery Colours';

-- Stationery Products Table
CREATE TABLE IF NOT EXISTS `erp_stationery` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `category_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_stationery_categories table',
  `brand_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Foreign key to erp_stationery_brands table',
  `colour_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Foreign key to erp_stationery_colours table',
  `product_name` varchar(255) NOT NULL COMMENT 'Product Name/Display Name',
  `isbn` varchar(100) DEFAULT NULL COMMENT 'ISBN/Bar Code No./SKU',
  `sku` varchar(100) DEFAULT NULL COMMENT 'SKU /Product Code',
  `product_code` varchar(100) DEFAULT NULL COMMENT 'Product Code (For control of school set)',
  `min_quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Min Quantity',
  `days_to_exchange` int(11) DEFAULT NULL COMMENT 'Days To Exchange',
  `pointers` text COMMENT 'Pointers / Highlights (CKEditor)',
  `product_description` text NOT NULL COMMENT 'Product Description (CKEditor)',
  `packaging_length` decimal(10,2) DEFAULT NULL COMMENT 'Length (in cm)',
  `packaging_width` decimal(10,2) DEFAULT NULL COMMENT 'Width (in cm)',
  `packaging_height` decimal(10,2) DEFAULT NULL COMMENT 'Height (in cm)',
  `packaging_weight` decimal(10,2) DEFAULT NULL COMMENT 'Weight (in gm)',
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'GST (%)',
  `gst_type` enum('igst','cgst_sgst') DEFAULT NULL COMMENT 'Select GST',
  `hsn` varchar(50) DEFAULT NULL COMMENT 'HSN',
  `mrp` decimal(10,2) NOT NULL COMMENT 'MRP',
  `selling_price` decimal(10,2) NOT NULL COMMENT 'Selling Price',
  `meta_title` varchar(255) DEFAULT NULL COMMENT 'Meta Title',
  `meta_keywords` text COMMENT 'Meta Keywords',
  `meta_description` text COMMENT 'Meta Description',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_brand_id` (`brand_id`),
  KEY `idx_colour_id` (`colour_id`),
  KEY `idx_status` (`status`),
  KEY `idx_sku` (`sku`),
  KEY `idx_product_code` (`product_code`),
  CONSTRAINT `fk_stationery_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_stationery_category` FOREIGN KEY (`category_id`) REFERENCES `erp_stationery_categories` (`id`),
  CONSTRAINT `fk_stationery_brand` FOREIGN KEY (`brand_id`) REFERENCES `erp_stationery_brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_stationery_colour` FOREIGN KEY (`colour_id`) REFERENCES `erp_stationery_colours` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery Products';

-- Stationery Images Table
CREATE TABLE IF NOT EXISTS `erp_stationery_images` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `stationery_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_stationery table',
  `image_path` varchar(500) NOT NULL,
  `image_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_stationery_id` (`stationery_id`),
  KEY `idx_image_order` (`image_order`),
  CONSTRAINT `fk_stationery_images_stationery` FOREIGN KEY (`stationery_id`) REFERENCES `erp_stationery` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery Images';


