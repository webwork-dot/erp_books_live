-- Add Payment Gateway Fields to erp_clients table
-- This migration adds fields for Razorpay and CCAvenue payment gateway integration

ALTER TABLE `erp_clients` 
ADD COLUMN `payment_gateway` ENUM('razorpay', 'ccavenue', '') DEFAULT '' COMMENT 'Payment gateway provider (razorpay or ccavenue)' AFTER `logo`,
ADD COLUMN `razorpay_key_id` VARCHAR(255) DEFAULT NULL COMMENT 'Razorpay Key ID (Live)' AFTER `payment_gateway`,
ADD COLUMN `razorpay_key_secret` VARCHAR(255) DEFAULT NULL COMMENT 'Razorpay Key Secret (Live)' AFTER `razorpay_key_id`,
ADD COLUMN `ccavenue_merchant_id` VARCHAR(255) DEFAULT NULL COMMENT 'CCAvenue Merchant ID (Live)' AFTER `razorpay_key_secret`,
ADD COLUMN `ccavenue_access_code` VARCHAR(255) DEFAULT NULL COMMENT 'CCAvenue Access Code (Live)' AFTER `ccavenue_merchant_id`,
ADD COLUMN `ccavenue_working_key` VARCHAR(255) DEFAULT NULL COMMENT 'CCAvenue Working Key (Live)' AFTER `ccavenue_access_code`;

-- Add index for payment gateway lookup
CREATE INDEX `idx_payment_gateway` ON `erp_clients` (`payment_gateway`);

