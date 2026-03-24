-- ALTER statements for textbook tables
-- Run these if the tables already exist and need to be updated

-- Add grade_age_type column to erp_textbooks table (if it doesn't exist)
ALTER TABLE `erp_textbooks`
ADD COLUMN IF NOT EXISTS `grade_age_type` enum('grade','age') DEFAULT NULL COMMENT 'Grade or Age selection type' AFTER `board_id`;

-- Note: The IF NOT EXISTS clause may not work in all MySQL versions
-- If you get an error, check if the column exists first, or use:
-- ALTER TABLE `erp_textbooks`
-- ADD COLUMN `grade_age_type` enum('grade','age') DEFAULT NULL COMMENT 'Grade or Age selection type' AFTER `board_id`;


