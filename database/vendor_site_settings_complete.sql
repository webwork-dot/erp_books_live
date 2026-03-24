-- Complete vendor_site_settings table structure with banner_image column
-- This is the complete structure for reference

-- If the table doesn't exist yet, create it with the complete structure
CREATE TABLE IF NOT EXISTS `vendor_site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `site_title` varchar(255) DEFAULT NULL,
  `site_description` text DEFAULT NULL,
  `logo_path` varchar(500) DEFAULT NULL,
  `favicon_path` varchar(500) DEFAULT NULL,
  `primary_color` varchar(7) DEFAULT '#116B31',
  `secondary_color` varchar(7) DEFAULT '#ffffff',
  `accent_color` varchar(7) DEFAULT '#28a745',
  `header_bg_color` varchar(7) DEFAULT '#ffffff',
  `footer_bg_color` varchar(7) DEFAULT '#f8f9fa',
  `text_primary_color` varchar(7) DEFAULT '#333333',
  `text_secondary_color` varchar(7) DEFAULT '#666666',
  `link_color` varchar(7) DEFAULT '#116B31',
  `link_hover_color` varchar(7) DEFAULT '#0d5a26',
  `button_primary_bg` varchar(7) DEFAULT '#116B31',
  `button_primary_text` varchar(7) DEFAULT '#ffffff',
  `button_secondary_bg` varchar(7) DEFAULT '#6c757d',
  `button_secondary_text` varchar(7) DEFAULT '#ffffff',
  `modal_bg_gradient_start` varchar(7) DEFAULT '#116B31',
  `modal_bg_gradient_end` varchar(7) DEFAULT '#28a745',
  `modal_button_bg` varchar(7) DEFAULT '#ffffff',
  `modal_button_text` varchar(7) DEFAULT '#116B31',
  `since_text` varchar(255) DEFAULT 'SINCE 1952',
  `custom_css` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `banner_image` varchar(500) DEFAULT NULL COMMENT 'Path to uploaded banner image file',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- If the table already exists, add the banner_image column (alternative approach)
-- ALTER TABLE `vendor_site_settings` ADD COLUMN `banner_image` VARCHAR(500) DEFAULT NULL COMMENT 'Path to uploaded banner image file';