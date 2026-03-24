-- =============================================
-- Add logo column to erp_clients (vendors) table
-- Multi-Tenant School Ecommerce ERP System
-- =============================================

USE `erp_master`;

-- Add logo column
ALTER TABLE `erp_clients` 
ADD COLUMN `logo` VARCHAR(255) NULL DEFAULT NULL 
COMMENT 'Vendor logo file path' 
AFTER `sidebar_color`;

-- Add index for better query performance
CREATE INDEX `idx_logo` ON `erp_clients` (`logo`);

-- Verify the column was added
DESCRIBE `erp_clients`;

