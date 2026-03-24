-- =====================================================
-- SQL Migration: Recent Changes
-- Date: 2026-01-01
-- Description: Add customer fields to orders table
-- =====================================================

-- =====================================================
-- 1. Add Customer Fields to Orders Table
-- =====================================================
-- Adds customer_name, customer_email, and customer_address columns
-- to display customer information in the orders list

-- Add customer_name column
ALTER TABLE `erp_orders` 
ADD COLUMN `customer_name` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Customer name' AFTER `school_id`;

-- Add customer_email column
ALTER TABLE `erp_orders` 
ADD COLUMN `customer_email` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Customer email' AFTER `customer_name`;

-- Add customer_address column
ALTER TABLE `erp_orders` 
ADD COLUMN `customer_address` TEXT NULL DEFAULT NULL COMMENT 'Customer address' AFTER `customer_email`;

-- Add index on customer_email for better search performance
ALTER TABLE `erp_orders`
ADD INDEX `idx_customer_email` (`customer_email`);

-- Add index on customer_name for better search performance
ALTER TABLE `erp_orders`
ADD INDEX `idx_customer_name` (`customer_name`);

-- =====================================================
-- Note: Branch Functionality
-- =====================================================
-- No SQL changes required for branch checkbox feature.
-- The existing erp_school_branches table already supports:
-- - school_id (parent school reference)
-- - branch_name, address, state_id, city_id, pincode
-- - vendor_id, status, etc.
-- 
-- The branch functionality uses the existing table structure.

