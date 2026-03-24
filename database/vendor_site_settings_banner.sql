-- Add banner_image column to vendor_site_settings table
ALTER TABLE `vendor_site_settings` 
ADD COLUMN `banner_image` VARCHAR(500) DEFAULT NULL COMMENT 'Path to uploaded banner image file';