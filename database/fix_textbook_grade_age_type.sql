-- Fix: Add grade_age_type column to erp_textbooks table
-- Run this SQL if you get "Unknown column 'grade_age_type' in 'field list'" error

-- Check if column exists first (optional - remove if your MySQL version doesn't support it)
-- SELECT COLUMN_NAME 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE()
-- AND TABLE_NAME = 'erp_textbooks'
-- AND COLUMN_NAME = 'grade_age_type';

-- Add the column
ALTER TABLE `erp_textbooks`
ADD COLUMN `grade_age_type` enum('grade','age') DEFAULT NULL COMMENT 'Grade or Age selection type' AFTER `board_id`;

