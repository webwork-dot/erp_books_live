-- Add bookset-specific fields to tbl_order_items table
-- Run this SQL to add the necessary columns for bookset order items

ALTER TABLE `tbl_order_items` 
ADD COLUMN `order_type` VARCHAR(50) NULL DEFAULT 'individual' COMMENT 'Type of order: individual, bookset' AFTER `embroidery_json`,
ADD COLUMN `package_id` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Comma-separated package IDs for bookset' AFTER `order_type`,
ADD COLUMN `f_name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'First name for bookset personalization' AFTER `package_id`,
ADD COLUMN `m_name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Middle name for bookset personalization' AFTER `f_name`,
ADD COLUMN `s_name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Last name for bookset personalization' AFTER `m_name`,
ADD COLUMN `dob` DATE NULL DEFAULT NULL COMMENT 'Date of birth for bookset personalization' AFTER `s_name`,
ADD COLUMN `school_id` INT(11) NULL DEFAULT NULL COMMENT 'School ID for bookset' AFTER `dob`;

-- Add index for better query performance
ALTER TABLE `tbl_order_items` 
ADD INDEX `idx_order_type` (`order_type`),
ADD INDEX `idx_school_id` (`school_id`);

