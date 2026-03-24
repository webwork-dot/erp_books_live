-- Vendor Site Settings Table
-- This table stores customization settings for each vendor's live site

CREATE TABLE IF NOT EXISTS `vendor_site_settings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `vendor_id` int(11) NOT NULL COMMENT 'Vendor ID (each vendor has their own database)',
    `site_title` varchar(255) DEFAULT NULL COMMENT 'Custom site title for vendor',
    `site_description` text COMMENT 'Custom site description/meta description',
    `logo_path` varchar(500) DEFAULT NULL COMMENT 'Path to uploaded logo file',
    `favicon_path` varchar(500) DEFAULT NULL COMMENT 'Path to uploaded favicon file',
    `primary_color` varchar(7) DEFAULT '#116B31' COMMENT 'Primary brand color (hex code)',
    `secondary_color` varchar(7) DEFAULT '#ffffff' COMMENT 'Secondary brand color (hex code)',
    `accent_color` varchar(7) DEFAULT '#28a745' COMMENT 'Accent color for buttons/highlights',
    `header_bg_color` varchar(7) DEFAULT '#ffffff' COMMENT 'Header background color',
    `footer_bg_color` varchar(7) DEFAULT '#f8f9fa' COMMENT 'Footer background color',
    `text_primary_color` varchar(7) DEFAULT '#333333' COMMENT 'Primary text color',
    `text_secondary_color` varchar(7) DEFAULT '#666666' COMMENT 'Secondary text color',
    `link_color` varchar(7) DEFAULT '#116B31' COMMENT 'Link color',
    `link_hover_color` varchar(7) DEFAULT '#0d5a26' COMMENT 'Link hover color',
    `button_primary_bg` varchar(7) DEFAULT '#116B31' COMMENT 'Primary button background',
    `button_primary_text` varchar(7) DEFAULT '#ffffff' COMMENT 'Primary button text color',
    `button_secondary_bg` varchar(7) DEFAULT '#6c757d' COMMENT 'Secondary button background',
    `button_secondary_text` varchar(7) DEFAULT '#ffffff' COMMENT 'Secondary button text color',
    `modal_bg_gradient_start` varchar(7) DEFAULT '#116B31' COMMENT 'Modal background gradient start color',
    `modal_bg_gradient_end` varchar(7) DEFAULT '#28a745' COMMENT 'Modal background gradient end color',
    `modal_button_bg` varchar(7) DEFAULT '#ffffff' COMMENT 'Modal button background color',
    `modal_button_text` varchar(7) DEFAULT '#116B31' COMMENT 'Modal button text color',
    `banner_image` varchar(500) DEFAULT NULL COMMENT 'Path to uploaded banner image file',
    `since_text` varchar(255) DEFAULT 'SINCE 1952' COMMENT 'Since text displayed in modal',
           `custom_css` text COMMENT 'Custom CSS for advanced styling',
           `meta_title` varchar(255) DEFAULT NULL COMMENT 'Custom meta title for SEO',
           `meta_keywords` text COMMENT 'Custom meta keywords for SEO',
           `meta_description` text COMMENT 'Custom meta description for SEO',
           `is_active` tinyint(1) DEFAULT 1 COMMENT 'Whether these settings are active',
           `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
           `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_vendor` (`vendor_id`),
    KEY `vendor_id` (`vendor_id`)
   


) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

