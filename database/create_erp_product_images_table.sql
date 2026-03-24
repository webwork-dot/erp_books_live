-- Create unified product images reference table
-- This table stores references to images across all product types
-- while maintaining the original images in their respective tables

CREATE TABLE IF NOT EXISTS `erp_product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT 'Reference to erp_products.id',
  `image_path` varchar(500) NOT NULL,
  `image_order` int(11) NOT NULL DEFAULT 0,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `legacy_table` varchar(50) DEFAULT NULL COMMENT 'Original source table (erp_textbook_images, erp_notebook_images, etc)',
  `legacy_id` int(11) DEFAULT NULL COMMENT 'Original ID from source table',
  `vendor_id` int(11) NOT NULL COMMENT 'For filtering by vendor',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `legacy_reference` (`legacy_table`,`legacy_id`),
  KEY `is_main` (`is_main`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Unified product images reference table';

-- Add indexes for better performance
ALTER TABLE `erp_product_images`
  ADD INDEX `image_order_index` (`image_order`),
  ADD INDEX `created_at_index` (`created_at`);