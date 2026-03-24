-- Textbook Types Table
CREATE TABLE IF NOT EXISTS `erp_textbook_types` (
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
  CONSTRAINT `fk_textbook_types_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Types';

-- Textbook Publishers Table
CREATE TABLE IF NOT EXISTS `erp_textbook_publishers` (
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
  CONSTRAINT `fk_textbook_publishers_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Publishers';

-- Textbook Grades Table
CREATE TABLE IF NOT EXISTS `erp_textbook_grades` (
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
  CONSTRAINT `fk_textbook_grades_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Grades';

-- Textbook Ages Table
CREATE TABLE IF NOT EXISTS `erp_textbook_ages` (
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
  CONSTRAINT `fk_textbook_ages_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Ages';

-- Textbook Subjects Table
CREATE TABLE IF NOT EXISTS `erp_textbook_subjects` (
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
  CONSTRAINT `fk_textbook_subjects_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Subjects';

-- Textbooks Table
CREATE TABLE IF NOT EXISTS `erp_textbooks` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `publisher_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_publishers table',
  `board_id` int(11) NOT NULL COMMENT 'Foreign key to erp_school_boards table',
  `grade_age_type` enum('grade','age') DEFAULT NULL COMMENT 'Grade or Age selection type',
  `product_name` varchar(255) NOT NULL COMMENT 'Product Name/Display Name',
  `isbn` varchar(100) NOT NULL COMMENT 'ISBN/Bar Code No./SKU',
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
  `product_code` varchar(100) DEFAULT NULL COMMENT 'Product Code (For control of school set)',
  `sku` varchar(100) DEFAULT NULL COMMENT 'SKU /Product Code',
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
  KEY `idx_publisher_id` (`publisher_id`),
  KEY `idx_board_id` (`board_id`),
  KEY `idx_status` (`status`),
  KEY `idx_isbn` (`isbn`),
  KEY `idx_sku` (`sku`),
  KEY `idx_product_code` (`product_code`),
  CONSTRAINT `fk_textbooks_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_textbooks_publisher` FOREIGN KEY (`publisher_id`) REFERENCES `erp_textbook_publishers` (`id`),
  CONSTRAINT `fk_textbooks_board` FOREIGN KEY (`board_id`) REFERENCES `erp_school_boards` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbooks';

-- Textbook Images Table
CREATE TABLE IF NOT EXISTS `erp_textbook_images` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `textbook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbooks table',
  `image_path` varchar(500) NOT NULL,
  `image_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_textbook_id` (`textbook_id`),
  KEY `idx_image_order` (`image_order`),
  CONSTRAINT `fk_textbook_images_textbook` FOREIGN KEY (`textbook_id`) REFERENCES `erp_textbooks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Images';

-- Textbook Types Junction Table (Many-to-Many)
CREATE TABLE IF NOT EXISTS `erp_textbook_type_mapping` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `textbook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbooks table',
  `type_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_types table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_textbook_type` (`textbook_id`, `type_id`),
  KEY `idx_textbook_id` (`textbook_id`),
  KEY `idx_type_id` (`type_id`),
  CONSTRAINT `fk_textbook_type_mapping_textbook` FOREIGN KEY (`textbook_id`) REFERENCES `erp_textbooks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_textbook_type_mapping_type` FOREIGN KEY (`type_id`) REFERENCES `erp_textbook_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook-Type Mapping';

-- Textbook Grades Junction Table (Many-to-Many)
CREATE TABLE IF NOT EXISTS `erp_textbook_grade_mapping` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `textbook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbooks table',
  `grade_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_grades table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_textbook_grade` (`textbook_id`, `grade_id`),
  KEY `idx_textbook_id` (`textbook_id`),
  KEY `idx_grade_id` (`grade_id`),
  CONSTRAINT `fk_textbook_grade_mapping_textbook` FOREIGN KEY (`textbook_id`) REFERENCES `erp_textbooks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_textbook_grade_mapping_grade` FOREIGN KEY (`grade_id`) REFERENCES `erp_textbook_grades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook-Grade Mapping';

-- Textbook Ages Junction Table (Many-to-Many)
CREATE TABLE IF NOT EXISTS `erp_textbook_age_mapping` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `textbook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbooks table',
  `age_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_ages table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_textbook_age` (`textbook_id`, `age_id`),
  KEY `idx_textbook_id` (`textbook_id`),
  KEY `idx_age_id` (`age_id`),
  CONSTRAINT `fk_textbook_age_mapping_textbook` FOREIGN KEY (`textbook_id`) REFERENCES `erp_textbooks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_textbook_age_mapping_age` FOREIGN KEY (`age_id`) REFERENCES `erp_textbook_ages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook-Age Mapping';

-- Textbook Subjects Junction Table (Many-to-Many)
CREATE TABLE IF NOT EXISTS `erp_textbook_subject_mapping` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `textbook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbooks table',
  `subject_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_subjects table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_textbook_subject` (`textbook_id`, `subject_id`),
  KEY `idx_textbook_id` (`textbook_id`),
  KEY `idx_subject_id` (`subject_id`),
  CONSTRAINT `fk_textbook_subject_mapping_textbook` FOREIGN KEY (`textbook_id`) REFERENCES `erp_textbooks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_textbook_subject_mapping_subject` FOREIGN KEY (`subject_id`) REFERENCES `erp_textbook_subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook-Subject Mapping';

