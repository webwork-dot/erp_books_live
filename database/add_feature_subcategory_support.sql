-- SQL to add sub-category support to erp_features table
-- This allows features to have parent categories and sub-categories

USE `erp_master`;

-- Add parent_id column to erp_features table
-- NULL = main category, non-NULL = sub-category of the parent feature
ALTER TABLE `erp_features`
  ADD COLUMN `parent_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Parent feature ID (NULL for main category, ID for sub-category)' AFTER `id`,
  ADD COLUMN `category_type` enum('main','sub') NOT NULL DEFAULT 'main' COMMENT 'Category type: main or sub' AFTER `parent_id`;

-- Add index for better query performance
ALTER TABLE `erp_features`
  ADD KEY `idx_parent_id` (`parent_id`);

-- Add foreign key constraint (self-referencing)
ALTER TABLE `erp_features`
  ADD CONSTRAINT `fk_features_parent` FOREIGN KEY (`parent_id`) REFERENCES `erp_features` (`id`) ON DELETE CASCADE;

-- Update existing features to ensure they are marked as main categories
UPDATE `erp_features` SET `category_type` = 'main' WHERE `parent_id` IS NULL;

