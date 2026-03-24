-- Migration to add missing board_id, grade_id, and bookset_packages_json fields to tbl_order_items
-- This migration adds the fields needed to store bookset board and grade information

USE `erp_client_shivambookscom`;

-- Add board_id field
ALTER TABLE `tbl_order_items`
ADD COLUMN `board_id` int(11) DEFAULT NULL COMMENT 'Board ID for bookset' AFTER `school_id`;

-- Add grade_id field
ALTER TABLE `tbl_order_items`
ADD COLUMN `grade_id` int(11) DEFAULT NULL COMMENT 'Grade ID for bookset' AFTER `board_id`;

-- Add bookset_packages_json field for storing additional bookset data
ALTER TABLE `tbl_order_items`
ADD COLUMN `bookset_packages_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON data for bookset packages' AFTER `grade_id`;

-- Add indexes for better performance
ALTER TABLE `tbl_order_items` ADD INDEX `idx_board_id` (`board_id`);
ALTER TABLE `tbl_order_items` ADD INDEX `idx_grade_id` (`grade_id`);