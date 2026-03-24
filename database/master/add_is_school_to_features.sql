-- =============================================
-- Add is_school column to erp_features table
-- Multi-Tenant School Ecommerce ERP System
-- =============================================

USE `erp_master`;

-- Add is_school column to erp_features table
ALTER TABLE `erp_features` 
ADD COLUMN `is_school` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is this a school-specific feature' 
AFTER `description`;

-- Verify the column was added
DESCRIBE `erp_features`;

