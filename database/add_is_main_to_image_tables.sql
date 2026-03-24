-- ============================================
-- Add is_main column to image tables for main image selection
-- ============================================

-- Add is_main column to erp_notebook_images table
ALTER TABLE `erp_notebook_images`
ADD COLUMN `is_main` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Main Image' AFTER `image_order`;

-- Add is_main column to erp_textbook_images table
ALTER TABLE `erp_textbook_images`
ADD COLUMN `is_main` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Main Image' AFTER `image_order`;

-- Add is_main column to erp_uniform_images table
ALTER TABLE `erp_uniform_images`
ADD COLUMN `is_main` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Main Image' AFTER `image_order`;

-- Add is_main column to erp_stationery_images table
ALTER TABLE `erp_stationery_images`
ADD COLUMN `is_main` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Main Image' AFTER `image_order`;

-- Add indexes for better query performance
ALTER TABLE `erp_notebook_images` ADD INDEX `idx_is_main` (`is_main`);
ALTER TABLE `erp_textbook_images` ADD INDEX `idx_is_main` (`is_main`);
ALTER TABLE `erp_uniform_images` ADD INDEX `idx_is_main` (`is_main`);
ALTER TABLE `erp_stationery_images` ADD INDEX `idx_is_main` (`is_main`);

