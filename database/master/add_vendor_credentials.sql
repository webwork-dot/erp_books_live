-- Add username and password fields to erp_clients table for vendor login
ALTER TABLE `erp_clients` 
ADD COLUMN `username` VARCHAR(100) NULL UNIQUE COMMENT 'Vendor login username' AFTER `domain`,
ADD COLUMN `password` VARCHAR(255) NULL COMMENT 'Vendor login password (SHA1 hash)' AFTER `username`,
ADD KEY `idx_username` (`username`);

