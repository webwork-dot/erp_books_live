-- ALTER script to update existing stationery tables
-- Run this if you already have the tables created

-- Add brand_id and colour_id columns to erp_stationery table
ALTER TABLE `erp_stationery` 
ADD COLUMN `brand_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Foreign key to erp_stationery_brands table' AFTER `category_id`,
ADD COLUMN `colour_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Foreign key to erp_stationery_colours table' AFTER `brand_id`;

-- Add foreign key constraints
ALTER TABLE `erp_stationery`
ADD CONSTRAINT `fk_stationery_brand` FOREIGN KEY (`brand_id`) REFERENCES `erp_stationery_brands` (`id`) ON DELETE SET NULL,
ADD CONSTRAINT `fk_stationery_colour` FOREIGN KEY (`colour_id`) REFERENCES `erp_stationery_colours` (`id`) ON DELETE SET NULL;

-- Add indexes
ALTER TABLE `erp_stationery`
ADD KEY `idx_brand_id` (`brand_id`),
ADD KEY `idx_colour_id` (`colour_id`);

-- Drop junction tables (if they exist and are empty, otherwise migrate data first)
-- Note: If you have existing data in junction tables, you need to migrate it first
-- DROP TABLE IF EXISTS `erp_stationery_brand_mapping`;
-- DROP TABLE IF EXISTS `erp_stationery_colour_mapping`;

