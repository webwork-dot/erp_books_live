-- =====================================================
-- SQL Migration: Add Payment Required Fields
-- Date: 2026-02-17
-- Description: Add is_payment_required field to schools and branches
-- =====================================================

-- =====================================================
-- 1. Add is_payment_required to erp_schools table
-- =====================================================
-- Add is_payment_required field to schools table
-- Default 1 = payment is required, 0 = payment not required
ALTER TABLE `erp_schools`
ADD COLUMN `is_payment_required` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Payment Required Status (1=Required, 0=Not Required)' AFTER `is_national_block`;

-- =====================================================
-- 2. Add is_payment_required to erp_school_branches table
-- =====================================================
-- Add is_payment_required field to branches table
-- Default 1 = payment is required, 0 = payment not required
ALTER TABLE `erp_school_branches`
ADD COLUMN `is_payment_required` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Payment Required Status (1=Required, 0=Not Required)' AFTER `status`;

-- =====================================================
-- Note: Cascade Logic
-- =====================================================
-- When a school's is_payment_required is set to 0,
-- all branches of that school should be set to 0 as well.
-- This will be handled by application logic, not database triggers.


