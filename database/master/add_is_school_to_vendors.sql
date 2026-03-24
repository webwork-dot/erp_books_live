-- =============================================
-- Add is_school column to erp_clients (vendors) table
-- Multi-Tenant School Ecommerce ERP System
-- =============================================

USE `erp_master`;

-- Add is_school column
ALTER TABLE `erp_clients` 
ADD COLUMN `is_school` TINYINT(1) NOT NULL DEFAULT 0 
AFTER `status`;

-- Add index for better query performance
CREATE INDEX `idx_is_school` ON `erp_clients` (`is_school`);

-- Verify the column was added
DESCRIBE `erp_clients`;

