-- =====================================================
-- Bookset Package Tables
-- =====================================================

-- Table: erp_bookset_categories
-- Stores categories for bookset packages
CREATE TABLE IF NOT EXISTS `erp_bookset_categories` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL COMMENT 'Category Name',
  `description` text DEFAULT NULL COMMENT 'Category Description',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_bookset_categories_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bookset Package Categories';

-- Table: erp_bookset_packages
-- Stores bookset packages
CREATE TABLE IF NOT EXISTS `erp_bookset_packages` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `school_id` int(11) NOT NULL COMMENT 'Foreign key to erp_schools table',
  `board_id` int(11) NOT NULL COMMENT 'Foreign key to erp_school_boards table',
  `grade_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_grades table',
  `category_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_bookset_categories table',
  `package_name` varchar(255) NOT NULL COMMENT 'Package Name',
  `package_weight` decimal(10,2) NOT NULL COMMENT 'Weight of Package (in gm)',
  `note` text DEFAULT NULL COMMENT 'Note',
  `mandatory_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of mandatory products',
  `optional_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of optional products',
  `mandatory_optional_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of mandatory+optional products',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_school_id` (`school_id`),
  KEY `idx_board_id` (`board_id`),
  KEY `idx_grade_id` (`grade_id`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_bookset_packages_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookset_packages_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookset_packages_board` FOREIGN KEY (`board_id`) REFERENCES `erp_school_boards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookset_packages_grade` FOREIGN KEY (`grade_id`) REFERENCES `erp_textbook_grades` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bookset_packages_category` FOREIGN KEY (`category_id`) REFERENCES `erp_bookset_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bookset Packages';

-- Table: erp_bookset_package_products
-- Stores products within bookset packages (many-to-many relationship)
CREATE TABLE IF NOT EXISTS `erp_bookset_package_products` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_bookset_packages table',
  `product_type` enum('textbook','notebook') NOT NULL COMMENT 'Type of product (textbook or notebook)',
  `product_id` int(11) UNSIGNED NOT NULL COMMENT 'Product ID (textbook_id or notebook_id)',
  `display_name` varchar(255) NOT NULL COMMENT 'Display Name for this product in the package',
  `quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Quantity',
  `discounted_mrp` decimal(10,2) NOT NULL COMMENT 'Discounted MRP',
  `is_it` enum('mandatory','optional','mandatory+optional') NOT NULL DEFAULT 'mandatory' COMMENT 'Is It? (mandatory, optional, or mandatory+optional)',
  `weight` decimal(10,2) NOT NULL COMMENT 'Weight of this product (in gm)',
  `note` text DEFAULT NULL COMMENT 'Note for this product',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_package_id` (`package_id`),
  KEY `idx_product_type` (`product_type`),
  KEY `idx_product_id` (`product_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_bookset_package_products_package` FOREIGN KEY (`package_id`) REFERENCES `erp_bookset_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Products within Bookset Packages';

