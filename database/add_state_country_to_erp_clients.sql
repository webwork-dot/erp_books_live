-- Add state and country to erp_clients (for vendor pickup address in 3rd party shipping)
-- Run in MASTER database (erp_master)
-- Skip if columns already exist

ALTER TABLE `erp_clients` 
ADD COLUMN `state` VARCHAR(100) NULL AFTER `pincode`,
ADD COLUMN `country` VARCHAR(100) NULL AFTER `state`;
