-- Add profile information fields to erp_clients table
-- Fields: address, pincode, PAN, GSTIN

ALTER TABLE `erp_clients` 
ADD COLUMN `address` TEXT NULL AFTER `name`,
ADD COLUMN `pincode` VARCHAR(10) NULL AFTER `address`,
ADD COLUMN `pan` VARCHAR(20) NULL AFTER `pincode`,
ADD COLUMN `gstin` VARCHAR(20) NULL AFTER `pan`;

