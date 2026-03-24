-- Uniform Types Table
CREATE TABLE IF NOT EXISTS `erp_uniform_types` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Uniform Types';

-- Materials Table
CREATE TABLE IF NOT EXISTS `erp_materials` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Product Materials';

-- Uniforms Table
CREATE TABLE IF NOT EXISTS `erp_uniforms` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `uniform_type_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_uniform_types table',
  `school_id` int(11) NOT NULL COMMENT 'Foreign key to erp_schools table',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Foreign key to erp_school_branches table',
  `board_id` int(11) DEFAULT NULL COMMENT 'Foreign key to erp_school_boards table',
  `gender` enum('male','female','unisex') NOT NULL,
  `color` varchar(100) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL COMMENT 'Product Name/Display Name',
  `isbn` varchar(100) DEFAULT NULL COMMENT 'ISBN / Bar Code No./SKU No.',
  `min_quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Min Quantity',
  `days_to_exchange` int(11) DEFAULT NULL COMMENT 'Days To Exchange',
  `material_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_materials table',
  `product_origin` varchar(255) DEFAULT NULL,
  `product_description` text COMMENT 'Product Description (CKEditor)',
  `manufacturer_details` text COMMENT 'Manufacturer Details (CKEditor)',
  `packer_details` text COMMENT 'Packer Details (CKEditor)',
  `customer_details` text COMMENT 'Customer Details (CKEditor)',
  `price` decimal(10,2) DEFAULT NULL,
  `size_chart_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Size Chart (for future use - leave empty for now)',
  `size_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Size (for future use - leave empty for now)',
  `packaging_length` decimal(10,2) DEFAULT NULL COMMENT 'Length (in cm)',
  `packaging_width` decimal(10,2) DEFAULT NULL COMMENT 'Width (in cm)',
  `packaging_height` decimal(10,2) DEFAULT NULL COMMENT 'Height (in cm)',
  `packaging_weight` decimal(10,2) DEFAULT NULL COMMENT 'Weight (in gm)',
  `tax` decimal(10,2) DEFAULT NULL,
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'GST (%)',
  `hsn` varchar(50) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` text,
  `meta_description` text,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_uniform_type_id` (`uniform_type_id`),
  KEY `idx_school_id` (`school_id`),
  KEY `idx_branch_id` (`branch_id`),
  KEY `idx_board_id` (`board_id`),
  KEY `idx_material_id` (`material_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_uniforms_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_uniforms_type` FOREIGN KEY (`uniform_type_id`) REFERENCES `erp_uniform_types` (`id`),
  CONSTRAINT `fk_uniforms_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_uniforms_branch` FOREIGN KEY (`branch_id`) REFERENCES `erp_school_branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_uniforms_board` FOREIGN KEY (`board_id`) REFERENCES `erp_school_boards` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_uniforms_material` FOREIGN KEY (`material_id`) REFERENCES `erp_materials` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Uniforms Products';

-- Uniform Images Table
CREATE TABLE IF NOT EXISTS `erp_uniform_images` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uniform_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_uniforms table',
  `image_path` varchar(500) NOT NULL,
  `image_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_uniform_id` (`uniform_id`),
  CONSTRAINT `fk_uniform_images_uniform` FOREIGN KEY (`uniform_id`) REFERENCES `erp_uniforms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Uniform Images';

