-- =============================================
-- Add sidebar_color column to erp_clients (vendors) table
-- Multi-Tenant School Ecommerce ERP System
-- =============================================

USE `erp_master`;

-- Add sidebar_color column
ALTER TABLE `erp_clients` 
ADD COLUMN `sidebar_color` VARCHAR(50) NULL DEFAULT 'sidebarbg1' 
COMMENT 'Sidebar color theme (sidebarbg1-sidebarbg6)' 
AFTER `status`;

-- Add index for better query performance
CREATE INDEX `idx_sidebar_color` ON `erp_clients` (`sidebar_color`);

-- Set default value for existing records
UPDATE `erp_clients` SET `sidebar_color` = 'sidebarbg1' WHERE `sidebar_color` IS NULL;

-- Verify the column was added
DESCRIBE `erp_clients`;

