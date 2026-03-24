-- =============================================
-- Vendor Feature Tables
-- These tables store feature assignments synced from master database
-- =============================================

-- Table: vendor_features
-- Description: Stores enabled features for this vendor (synced from master)
CREATE TABLE IF NOT EXISTS `vendor_features` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `feature_id` INT(11) UNSIGNED NOT NULL COMMENT 'Reference to erp_features.id in master',
  `feature_slug` VARCHAR(255) NOT NULL COMMENT 'Feature identifier for quick lookup',
  `feature_name` VARCHAR(255) NOT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Vendor-uploaded image for this feature',
  `is_enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `synced_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp for image',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_feature_slug` (`feature_slug`),
  KEY `idx_feature_id` (`feature_id`),
  KEY `idx_is_enabled` (`is_enabled`),
  KEY `idx_image` (`image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Table: vendor_feature_subcategories
-- Description: Stores enabled subcategories for features (synced from master)
CREATE TABLE IF NOT EXISTS `vendor_feature_subcategories` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `feature_id` INT(11) UNSIGNED NOT NULL,
  `subcategory_id` INT(11) UNSIGNED NOT NULL,
  `subcategory_slug` VARCHAR(255) NOT NULL,
  `subcategory_name` VARCHAR(255) NOT NULL,
  `is_enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `synced_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_subcategory` (`feature_id`, `subcategory_id`),
  KEY `idx_feature_id` (`feature_id`),
  KEY `idx_is_enabled` (`is_enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;








