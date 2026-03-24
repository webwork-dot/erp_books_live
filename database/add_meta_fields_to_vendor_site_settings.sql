-- Add meta fields to vendor_site_settings table
-- Run this SQL to add the new meta fields for SEO settings

ALTER TABLE `vendor_site_settings`
ADD COLUMN `meta_title` varchar(255) DEFAULT NULL COMMENT 'Custom meta title for SEO' AFTER `custom_css`,
ADD COLUMN `meta_keywords` text COMMENT 'Custom meta keywords for SEO' AFTER `meta_title`,
ADD COLUMN `meta_description` text COMMENT 'Custom meta description for SEO' AFTER `meta_keywords`;

