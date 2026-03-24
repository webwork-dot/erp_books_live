-- SQL to add Payment Block and National Delivery Block columns to erp_schools table
-- Run this SQL to add the toggle functionality fields

ALTER TABLE `erp_schools`
  ADD COLUMN `is_block_payment` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Payment Block Status (0=Active, 1=Blocked)' AFTER `status`,
  ADD COLUMN `is_national_block` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'National Delivery Block Status (0=Active, 1=Blocked)' AFTER `is_block_payment`;

