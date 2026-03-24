-- Migration: Add Customer Fields to Orders Table
-- Date: 2026-01-01
-- Description: Add customer name, email, and address columns to erp_orders table

-- Add customer fields to erp_orders table
ALTER TABLE `erp_orders` 
ADD COLUMN `customer_name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Customer name' AFTER `school_id`,
ADD COLUMN `customer_email` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Customer email' AFTER `customer_name`,
ADD COLUMN `customer_address` TEXT NULL DEFAULT NULL COMMENT 'Customer address' AFTER `customer_email`;

-- Add indexes for better search performance
ALTER TABLE `erp_orders`
ADD INDEX `idx_customer_email` (`customer_email`),
ADD INDEX `idx_customer_name` (`customer_name`);

