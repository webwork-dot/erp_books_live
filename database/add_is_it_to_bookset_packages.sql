-- =====================================================
-- Add is_it field to erp_bookset_packages table
-- =====================================================

-- Add is_it column to erp_bookset_packages table
ALTER TABLE `erp_bookset_packages`
ADD COLUMN `is_it` enum('mandatory','optional','mandatory+optional') NOT NULL DEFAULT 'mandatory' COMMENT 'Is It? (mandatory, optional, or mandatory+optional)' AFTER `package_weight`;

-- Add index for better query performance
ALTER TABLE `erp_bookset_packages` ADD INDEX `idx_is_it` (`is_it`);









