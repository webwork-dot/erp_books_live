-- =============================================
-- Master ERP Database Schema
-- Multi-Tenant School Ecommerce ERP System
-- =============================================

-- Create database
CREATE DATABASE IF NOT EXISTS `erp_master` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `erp_master`;

-- =============================================
-- Table: erp_clients
-- Description: Client/Vendor information
-- =============================================
CREATE TABLE IF NOT EXISTS `erp_clients` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL COMMENT 'Client/Vendor name',
  `domain` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Client domain (e.g., shyam.com)',
  `database_name` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Client database name',
  `status` ENUM('active', 'suspended', 'inactive') NOT NULL DEFAULT 'active' COMMENT 'Client status',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_domain` (`domain`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Client/Vendor information';

-- =============================================
-- Table: erp_features
-- Description: Available features in the system
-- =============================================
CREATE TABLE IF NOT EXISTS `erp_features` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL COMMENT 'Feature name',
  `slug` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Feature slug (e.g., books, bookset, uniforms)',
  `description` TEXT NULL COMMENT 'Feature description',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Is feature active globally',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_slug` (`slug`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Available features';

-- =============================================
-- Table: erp_client_features
-- Description: Feature assignments to clients (many-to-many)
-- =============================================
CREATE TABLE IF NOT EXISTS `erp_client_features` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` INT(11) UNSIGNED NOT NULL COMMENT 'Client ID',
  `feature_id` INT(11) UNSIGNED NOT NULL COMMENT 'Feature ID',
  `is_enabled` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Is feature enabled for this client',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_client_feature` (`client_id`, `feature_id`),
  KEY `idx_client_id` (`client_id`),
  KEY `idx_feature_id` (`feature_id`),
  CONSTRAINT `fk_client_features_client` FOREIGN KEY (`client_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_client_features_feature` FOREIGN KEY (`feature_id`) REFERENCES `erp_features` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Feature assignments to clients';

-- =============================================
-- Table: erp_client_settings
-- Description: Client branding and configuration
-- =============================================
CREATE TABLE IF NOT EXISTS `erp_client_settings` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` INT(11) UNSIGNED NOT NULL UNIQUE COMMENT 'Client ID',
  `logo` VARCHAR(255) NULL COMMENT 'Client logo path',
  `primary_color` VARCHAR(7) NULL DEFAULT '#007bff' COMMENT 'Primary brand color (hex)',
  `secondary_color` VARCHAR(7) NULL DEFAULT '#6c757d' COMMENT 'Secondary brand color (hex)',
  `theme` VARCHAR(50) NULL DEFAULT 'default' COMMENT 'Theme name',
  `sms_provider` VARCHAR(50) NULL COMMENT 'SMS provider name',
  `sms_credentials` TEXT NULL COMMENT 'SMS credentials (JSON)',
  `email_smtp_config` TEXT NULL COMMENT 'Email SMTP configuration (JSON)',
  `whatsapp_config` TEXT NULL COMMENT 'WhatsApp configuration (JSON)',
  `firebase_config` TEXT NULL COMMENT 'Firebase configuration (JSON)',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_client_id` (`client_id`),
  CONSTRAINT `fk_client_settings_client` FOREIGN KEY (`client_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Client branding and configuration';

-- =============================================
-- Table: erp_user_roles
-- Description: Super admin role definitions
-- =============================================
CREATE TABLE IF NOT EXISTS `erp_user_roles` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Role name',
  `description` TEXT NULL COMMENT 'Role description',
  `permissions` TEXT NULL COMMENT 'Permissions (JSON)',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Super admin role definitions';

-- =============================================
-- Table: erp_users
-- Description: Super admin users
-- =============================================
CREATE TABLE IF NOT EXISTS `erp_users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE COMMENT 'Username',
  `email` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Email address',
  `password` VARCHAR(255) NOT NULL COMMENT 'Hashed password',
  `role_id` INT(11) UNSIGNED NULL COMMENT 'Role ID',
  `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'User status (1=active, 0=inactive)',
  `last_login` TIMESTAMP NULL COMMENT 'Last login timestamp',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_role_id` (`role_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_erp_users_role` FOREIGN KEY (`role_id`) REFERENCES `erp_user_roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Super admin users';

-- =============================================
-- Insert default features
-- =============================================
INSERT INTO `erp_features` (`name`, `slug`, `description`, `is_active`) VALUES
('Books', 'books', 'Books module for managing individual books', 1),
('Bookset', 'bookset', 'Bookset module for managing book packages', 1),
('Uniforms', 'uniforms', 'Uniforms module for managing uniform products', 1),
('Stationery', 'stationery', 'Stationery module for managing stationery items', 1),
('Bags', 'bags', 'Bags module for managing bag products', 1),
('Sports', 'sports', 'Sports items module', 1)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- =============================================
-- Insert default roles
-- =============================================
INSERT INTO `erp_user_roles` (`name`, `description`, `permissions`) VALUES
('Super Admin', 'Full system access', '{"clients":["create","read","update","delete"],"features":["create","read","update","delete"],"users":["create","read","update","delete"],"reports":["read"]}'),
('Admin', 'Administrative access', '{"clients":["read","update"],"features":["read","update"],"users":["read"],"reports":["read"]}'),
('Manager', 'Management access', '{"clients":["read"],"features":["read"],"reports":["read"]}')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- =============================================
-- Insert default super admin user
-- Password: admin123 (change this in production!)
-- =============================================
INSERT INTO `erp_users` (`username`, `email`, `password`, `role_id`, `status`) VALUES
('admin', 'admin@erp.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1)
ON DUPLICATE KEY UPDATE `username` = VALUES(`username`);

