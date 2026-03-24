-- ============================================
-- Add is_individual and is_set columns to product tables
-- ============================================

-- Add columns to erp_notebooks table
ALTER TABLE `erp_notebooks`
ADD COLUMN `is_individual` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Individual Product' AFTER `status`,
ADD COLUMN `is_set` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Set Product' AFTER `is_individual`;

-- Add columns to erp_textbooks table
ALTER TABLE `erp_textbooks`
ADD COLUMN `is_individual` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Individual Product' AFTER `status`,
ADD COLUMN `is_set` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Set Product' AFTER `is_individual`;

-- Add columns to erp_uniforms table
ALTER TABLE `erp_uniforms`
ADD COLUMN `is_individual` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Individual Product' AFTER `status`,
ADD COLUMN `is_set` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Set Product' AFTER `is_individual`;

-- Add columns to erp_stationery table
ALTER TABLE `erp_stationery`
ADD COLUMN `is_individual` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Individual Product' AFTER `status`,
ADD COLUMN `is_set` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Set Product' AFTER `is_individual`;

-- Add indexes for better query performance
ALTER TABLE `erp_notebooks` ADD INDEX `idx_is_individual` (`is_individual`), ADD INDEX `idx_is_set` (`is_set`);
ALTER TABLE `erp_textbooks` ADD INDEX `idx_is_individual` (`is_individual`), ADD INDEX `idx_is_set` (`is_set`);
ALTER TABLE `erp_uniforms` ADD INDEX `idx_is_individual` (`is_individual`), ADD INDEX `idx_is_set` (`is_set`);
ALTER TABLE `erp_stationery` ADD INDEX `idx_is_individual` (`is_individual`), ADD INDEX `idx_is_set` (`is_set`);

