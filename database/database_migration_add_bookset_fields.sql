-- Migration: Add missing fields to erp_bookset_packages table
-- Date: 2025-12-31
-- Description: Add fields for booksets without products and bookset_id foreign key

-- Add bookset_id column (if it doesn't exist)
-- Note: Check if column exists first, if it does, skip this
ALTER TABLE `erp_bookset_packages` 
ADD COLUMN `bookset_id` int(11) UNSIGNED NULL COMMENT 'Foreign key to erp_booksets table' AFTER `id`;

-- Add package_price field (for booksets without products)
ALTER TABLE `erp_bookset_packages` 
ADD COLUMN `package_price` decimal(10,2) NULL DEFAULT 0 COMMENT 'Package Price' AFTER `package_name`;

-- Add package_offer_price field (for booksets without products)
ALTER TABLE `erp_bookset_packages` 
ADD COLUMN `package_offer_price` decimal(10,2) NULL DEFAULT 0 COMMENT 'Package Offer Price' AFTER `package_price`;

-- Add gst field (for booksets without products)
ALTER TABLE `erp_bookset_packages` 
ADD COLUMN `gst` decimal(5,2) NULL DEFAULT 0 COMMENT 'GST Percentage' AFTER `package_offer_price`;

-- Add hsn field (for booksets without products)
ALTER TABLE `erp_bookset_packages` 
ADD COLUMN `hsn` varchar(50) NULL DEFAULT NULL COMMENT 'HSN Code' AFTER `gst`;

-- Also, make category_id nullable since packages with products don't use categories
ALTER TABLE `erp_bookset_packages` 
MODIFY COLUMN `category_id` int(11) UNSIGNED NULL COMMENT 'Foreign key to erp_bookset_categories table';
