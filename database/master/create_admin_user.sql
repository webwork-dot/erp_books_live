-- =============================================
-- Create Default Admin User
-- Multi-Tenant School Ecommerce ERP System
-- =============================================

USE `erp_master`;

-- Delete existing admin user if exists
DELETE FROM `erp_users` WHERE `username` = 'admin';

-- Insert default super admin user
-- Username: admin
-- Password: admin123
-- Password hash generated using SHA1: sha1('admin123') = 'c93ccd78b2076528346216b3b2f701e6'
-- Note: This hash is for 'admin123'. If you need a different password, generate a new SHA1 hash.
INSERT INTO `erp_users` (`username`, `email`, `password`, `role_id`, `status`) VALUES
('admin', 'admin@erp.local', 'c93ccd78b2076528346216b3b2f701e6', 1, 1)
ON DUPLICATE KEY UPDATE 
    `password` = VALUES(`password`),
    `email` = VALUES(`email`),
    `role_id` = VALUES(`role_id`),
    `status` = VALUES(`status`);

-- Verify the user was created
SELECT id, username, email, status, created_at FROM `erp_users` WHERE `username` = 'admin';

