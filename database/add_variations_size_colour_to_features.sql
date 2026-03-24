-- SQL to add Variations, Size, and Colour fields to erp_features table
-- These fields are boolean (1/0) selections

-- Add has_variations column
ALTER TABLE `erp_features` 
ADD COLUMN `has_variations` TINYINT(1) NOT NULL DEFAULT 0 
COMMENT 'Enable variations for this feature (1=Yes, 0=No)' 
AFTER `is_school`;

-- Add has_size column
ALTER TABLE `erp_features` 
ADD COLUMN `has_size` TINYINT(1) NOT NULL DEFAULT 0 
COMMENT 'Enable size selection for this feature (1=Yes, 0=No)' 
AFTER `has_variations`;

-- Add has_colour column
ALTER TABLE `erp_features` 
ADD COLUMN `has_colour` TINYINT(1) NOT NULL DEFAULT 0 
COMMENT 'Enable colour selection for this feature (1=Yes, 0=No)' 
AFTER `has_size`;

-- Add indexes for better query performance (optional)
ALTER TABLE `erp_features` 
ADD INDEX `idx_has_variations` (`has_variations`),
ADD INDEX `idx_has_size` (`has_size`),
ADD INDEX `idx_has_colour` (`has_colour`);

-- Note: These fields will automatically sync to vendor databases via Feature_sync_model
-- The sync happens when:
-- 1. Features are assigned to vendors
-- 2. Features are updated in the master database
-- 3. Manual sync is triggered via syncVendorFeatures() method
