-- =============================================
-- Add image field to vendor_features table
-- This allows vendors to upload custom images for each feature
-- Run this on each vendor database (erp_client_webwork, etc.)
-- =============================================

-- Use the current database
USE erp_client_webwork;

-- Check if table exists first
SET @table_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES 
                     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'vendor_features');

-- Only proceed if table exists
SET @preparedStatement = (SELECT IF(
  @table_exists > 0 AND (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = DATABASE())
      AND (TABLE_NAME = 'vendor_features')
      AND (COLUMN_NAME = 'image')
  ) = 0,
  'ALTER TABLE vendor_features ADD COLUMN `image` VARCHAR(255) NULL DEFAULT NULL COMMENT ''Vendor-uploaded image for this feature'' AFTER `feature_name`',
  'SELECT 1 AS ''Column already exists or table does not exist'''
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add updated_at column if it doesn't exist
SET @preparedStatement = (SELECT IF(
  @table_exists > 0 AND (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = DATABASE())
      AND (TABLE_NAME = 'vendor_features')
      AND (COLUMN_NAME = 'updated_at')
  ) = 0,
  'ALTER TABLE vendor_features ADD COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER `synced_at`',
  'SELECT 1 AS ''Column already exists or table does not exist'''
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add index for image column if it doesn't exist
SET @preparedStatement = (SELECT IF(
  @table_exists > 0 AND (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE
      (TABLE_SCHEMA = DATABASE())
      AND (TABLE_NAME = 'vendor_features')
      AND (INDEX_NAME = 'idx_image')
  ) = 0,
  'ALTER TABLE vendor_features ADD INDEX `idx_image` (`image`)',
  'SELECT 1 AS ''Index already exists or table does not exist'''
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Show result
SELECT 'Image column added successfully to vendor_features table' AS Result;
