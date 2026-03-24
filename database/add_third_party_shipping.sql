-- Migration: 3rd Party Shipping (Shiprocket, Big Ship)
-- Run in vendor/tenant database
-- Run erp_clients changes in master database (erp_master)

-- ============================================
-- 1. erp_clients: Add state and country (run in erp_master)
-- ============================================
-- ALTER TABLE `erp_clients` 
-- ADD COLUMN `state` VARCHAR(100) NULL AFTER `pincode`,
-- ADD COLUMN `country` VARCHAR(100) NULL AFTER `state`;

-- ============================================
-- 2. tbl_order_details: Add 3rd party fields (run in vendor DB)
-- ============================================
-- Alter courier enum to add '3rd_party', add third_party_provider and dimensions
ALTER TABLE `tbl_order_details` 
ADD COLUMN `third_party_provider` VARCHAR(50) NULL DEFAULT NULL COMMENT 'shiprocket, bigship - when courier=3rd_party' AFTER `courier`,
ADD COLUMN `pkg_length_cm` DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'Package length in cm' AFTER `third_party_provider`,
ADD COLUMN `pkg_breadth_cm` DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'Package breadth in cm' AFTER `pkg_length_cm`,
ADD COLUMN `pkg_height_cm` DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'Package height in cm' AFTER `pkg_breadth_cm`,
ADD COLUMN `pkg_weight_kg` DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'Package weight in kg' AFTER `pkg_height_cm`;

-- Modify courier enum to include 3rd_party
ALTER TABLE `tbl_order_details` MODIFY COLUMN `courier` ENUM('shiprocket','manual','3rd_party','') DEFAULT NULL;

-- ============================================
-- 3. New table: tbl_order_third_party_shipping (run in vendor DB)
-- ============================================
CREATE TABLE IF NOT EXISTS `tbl_order_third_party_shipping` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL COMMENT 'FK to tbl_order_details.id',
  `order_unique_id` VARCHAR(50) NOT NULL COMMENT 'Order unique ID',
  `invoice_number` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Invoice number',
  `delivery_address_full` TEXT NULL COMMENT 'Full delivery address of the order',
  `pickup_address_full` TEXT NULL COMMENT 'Pickup address (from erp_clients)',
  `pickup_state` VARCHAR(100) NULL DEFAULT NULL,
  `pickup_country` VARCHAR(100) NULL DEFAULT NULL,
  `length_cm` DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'Package length in cm',
  `breadth_cm` DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'Package breadth in cm',
  `height_cm` DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'Package height in cm',
  `weight_kg` DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'Package weight in kg',
  `third_party_provider` VARCHAR(50) NOT NULL COMMENT 'shiprocket, bigship',
  `pickup_provider` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Mini shipping company for pickup (e.g. DTDC, Bluedart within Shiprocket)',
  `awb_no` VARCHAR(100) NULL DEFAULT NULL,
  `track_url` VARCHAR(500) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_order_id` (`order_id`),
  KEY `idx_order_unique_id` (`order_unique_id`),
  KEY `idx_third_party_provider` (`third_party_provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='3rd party shipping details (Shiprocket, Big Ship)';
