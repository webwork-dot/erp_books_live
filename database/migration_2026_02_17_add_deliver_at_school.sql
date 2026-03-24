-- =====================================================
-- SQL Migration: Add Deliver at School Fields
-- Date: 2026-02-17
-- Description: Add deliver_at_school field to schools and branches
-- When deactivated (0): no address required from customer on order placement
-- When activated (1): address required (default)
-- =====================================================

-- =====================================================
-- 1. Add deliver_at_school to erp_schools table
-- =====================================================
-- Add deliver_at_school field to schools table
-- Default 1 = delivery at school (address required), 0 = no address required
ALTER TABLE `erp_schools`
ADD COLUMN `deliver_at_school` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Deliver at School (1=Yes/Address Required, 0=No/Address Not Required)' AFTER `is_payment_required`;

-- =====================================================
-- 2. Add deliver_at_school to erp_school_branches table
-- =====================================================
-- Add deliver_at_school field to branches table
-- Default 1 = delivery at school (address required), 0 = no address required
ALTER TABLE `erp_school_branches`
ADD COLUMN `deliver_at_school` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Deliver at School (1=Yes/Address Required, 0=No/Address Not Required)' AFTER `is_payment_required`;

-- =====================================================
-- Note: Cascade Logic
-- =====================================================
-- When a school's deliver_at_school is set to 0,
-- all branches of that school should be set to 0 as well.
-- This will be handled by application logic, not database triggers.
