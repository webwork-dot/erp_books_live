-- Add SEO and Meta fields to erp_clients table
-- Run this SQL on the master database (erp_master)

ALTER TABLE `erp_clients` 
ADD COLUMN `favicon` VARCHAR(255) NULL DEFAULT NULL AFTER `logo`,
ADD COLUMN `site_title` VARCHAR(255) NULL DEFAULT NULL AFTER `favicon`,
ADD COLUMN `meta_description` TEXT NULL DEFAULT NULL AFTER `site_title`,
ADD COLUMN `meta_keywords` TEXT NULL DEFAULT NULL AFTER `meta_description`;

-- Add indexes for better performance
CREATE INDEX `idx_site_title` ON `erp_clients` (`site_title`);










