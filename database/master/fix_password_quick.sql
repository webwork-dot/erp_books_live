-- Quick Fix: Update Admin Password to SHA1
-- Run this in phpMyAdmin or MySQL command line

USE `erp_master`;

-- Update admin password to SHA1 hash for 'admin123'
UPDATE `erp_users` 
SET `password` = 'c93ccd78b2076528346216b3b2f701e6'
WHERE `username` = 'admin';

-- Verify the update
SELECT 
    id, 
    username, 
    email, 
    password, 
    LENGTH(password) as hash_length,
    status,
    role_id
FROM `erp_users` 
WHERE `username` = 'admin';

-- Expected result:
-- password should be: c93ccd78b2076528346216b3b2f701e6
-- hash_length should be: 40 (for SHA1)

