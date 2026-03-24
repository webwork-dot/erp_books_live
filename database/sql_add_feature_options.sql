-- SQL Script to add variations, size, and colour fields to erp_features table
-- This adds three boolean (TINYINT(1)) fields to track product options for each feature
-- These fields will be synced to vendor databases automatically via Feature_sync_model

-- Add has_variations column
ALTER TABLE `erp_features` 
ADD COLUMN `has_variations` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Feature supports product variations' 
AFTER `is_active`;

-- Add has_size column
ALTER TABLE `erp_features` 
ADD COLUMN `has_size` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Feature supports size options' 
AFTER `has_variations`;

-- Add has_colour column
ALTER TABLE `erp_features` 
ADD COLUMN `has_colour` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Feature supports colour options' 
AFTER `has_size`;

-- Note: The Feature_sync_model.php already handles syncing these fields to vendor databases
-- When features are synced to vendor databases, the ensureVendorFeaturesColumns() method
-- will automatically add these columns to the vendor_features table if they don't exist.
-- The sync happens in syncVendorFeatures() method which reads these values from master
-- database and syncs them to vendor databases.



