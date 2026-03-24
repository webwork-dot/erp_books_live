-- Add Vendor role to erp_user_roles table
INSERT INTO `erp_user_roles` (`name`, `description`, `permissions`) VALUES
('Vendor', 'Vendor role for vendor login access', '{"dashboard":["read"],"profile":["read","update"]}')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

