-- Migration: Add category field to erp_bookset_packages table
-- Date: 2025-12-31
-- Description: Add category field to store package type (textbook, notebook, stationery) for filtering

-- Add category column to store the type (textbook, notebook, stationery)
ALTER TABLE `erp_bookset_packages` 
ADD COLUMN `category` varchar(50) NULL DEFAULT NULL COMMENT 'Package category type: textbook, notebook, or stationery' AFTER `category_id`;

-- Create index for better filtering performance
CREATE INDEX `idx_category` ON `erp_bookset_packages` (`category`);

