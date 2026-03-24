-- =====================================================
-- School Management Tables for Vendor ERP System
-- =====================================================

-- =====================================================
-- Countries Table (from varitty_varitdbc.sql)
-- =====================================================

-- Table: countries
-- Countries data (from varitty_varitdbc.sql)
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `iso_code` varchar(2) NOT NULL COMMENT 'ISO 3166-1 alpha-2 code',
  `isd_code` varchar(7) DEFAULT NULL COMMENT 'International Subscriber Dialing code',
  `flag` varchar(250) DEFAULT NULL COMMENT 'Flag image filename',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_iso_code` (`iso_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Countries';

-- Insert India first (required before inserting states)
-- Note: In varitty_varitdbc.sql, India has id = 99, but states reference country_id = 101
-- We'll insert India with id = 101 to match the states data
INSERT INTO `countries` (`id`, `name`, `iso_code`, `isd_code`, `flag`) VALUES
(101, 'India', 'IN', '91', 'in.png')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Note: To import all countries data from varitty_varitdbc.sql:
-- 1. The table name in varitty_varitdbc.sql is `country` (singular)
-- 2. You may need to adjust the INSERT statement to use `countries` (plural) table name
-- 3. The INSERT statement starts around line 31926 in varitty_varitdbc.sql
-- 4. Make sure India (id=101) exists before inserting states

-- =====================================================
-- States and Cities Tables (from varitty_varitdbc.sql)
-- =====================================================

-- Table: states
-- States data (from varitty_varitdbc.sql)
CREATE TABLE IF NOT EXISTS `states` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `country_id` int NOT NULL DEFAULT 101 COMMENT '101 = India',
  PRIMARY KEY (`id`),
  KEY `idx_country_id` (`country_id`),
  CONSTRAINT `fk_states_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='States';

-- Table: cities
-- Cities data (from varitty_varitdbc.sql)
CREATE TABLE IF NOT EXISTS `cities` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country_id` int NOT NULL DEFAULT 101 COMMENT '101 = India',
  `state_id` mediumint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_state_id` (`state_id`),
  KEY `idx_country_id` (`country_id`),
  CONSTRAINT `fk_cities_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cities_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='Cities';

-- =====================================================
-- School Management Tables
-- =====================================================

-- Table: erp_schools
-- Stores school information
CREATE TABLE IF NOT EXISTS `erp_schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Vendor who created this school',
  `school_name` varchar(255) NOT NULL COMMENT 'School Name',
  `school_board` varchar(100) NOT NULL COMMENT 'School Board (CBSE, ICSE, State Board, etc.)',
  `total_strength` int(11) DEFAULT NULL COMMENT 'Total School Strength',
  `school_description` text DEFAULT NULL COMMENT 'School Description',
  `affiliation_no` varchar(100) DEFAULT NULL COMMENT 'Affiliation Number',
  `address` text NOT NULL COMMENT 'Address',
  `country_id` int NOT NULL DEFAULT 101 COMMENT 'Country ID (default: 101 = India)',
  `state_id` mediumint NOT NULL COMMENT 'State ID from states table',
  `city_id` mediumint NOT NULL COMMENT 'City ID from cities table',
  `pincode` varchar(10) NOT NULL COMMENT 'Pincode',
  `admin_name` varchar(255) NOT NULL COMMENT 'Admin Name',
  `admin_phone` varchar(20) NOT NULL COMMENT 'Admin Phone',
  `admin_email` varchar(255) NOT NULL COMMENT 'Admin Email',
  `admin_password` varchar(255) NOT NULL COMMENT 'Admin Password (SHA1 hash)',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_country_id` (`country_id`),
  KEY `idx_state_id` (`state_id`),
  KEY `idx_city_id` (`city_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_schools_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_schools_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_schools_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_schools_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Schools managed by vendors';

-- Table: erp_school_images
-- Stores multiple images for each school
CREATE TABLE IF NOT EXISTS `erp_school_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) NOT NULL COMMENT 'School ID',
  `image_path` varchar(255) NOT NULL COMMENT 'Image file path',
  `image_name` varchar(255) DEFAULT NULL COMMENT 'Original image name',
  `display_order` int(11) DEFAULT 0 COMMENT 'Display order for sorting',
  `is_primary` tinyint(1) DEFAULT 0 COMMENT 'Is primary image (1=yes, 0=no)',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_school_id` (`school_id`),
  KEY `idx_display_order` (`display_order`),
  CONSTRAINT `fk_school_images_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='School images';

-- Note: You need to import states and cities data from varitty_varitdbc.sql
-- The INSERT statements for states and cities are in that file
-- Run those INSERT statements after creating these tables
--
-- IMPORTANT: Create tables in this order:
-- 1. countries (created above)
-- 2. states (created above, depends on countries)
-- 3. cities (created above, depends on states and countries)
-- 4. erp_schools (created above, depends on erp_clients, countries, states, cities)
-- 5. erp_school_images (created above, depends on erp_schools)

