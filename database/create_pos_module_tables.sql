-- POS Admin Module (Master DB)
-- Creates agent-school access mapping and school-wise UPI QR config tables.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `erp_agent_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int unsigned DEFAULT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_agent_username` (`username`),
  UNIQUE KEY `uniq_agent_email` (`email`),
  KEY `idx_agent_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_pos_agent_school_access` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agent_user_id` int unsigned NOT NULL,
  `vendor_id` int unsigned NOT NULL,
  `school_id` int unsigned NOT NULL,
  `upi_qr_id` bigint unsigned DEFAULT NULL,
  `can_uniform` tinyint(1) NOT NULL DEFAULT 1,
  `can_bookset` tinyint(1) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int unsigned DEFAULT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_agent_vendor_school` (`agent_user_id`, `vendor_id`, `school_id`),
  KEY `idx_vendor_school` (`vendor_id`, `school_id`),
  KEY `idx_agent_status` (`agent_user_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `erp_school_upi_qr` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `vendor_id` int unsigned NOT NULL,
  `school_id` int unsigned NOT NULL,
  `qr_image_path` varchar(255) NOT NULL,
  `qr_image_original_name` varchar(255) DEFAULT NULL,
  `upi_id` varchar(120) DEFAULT NULL,
  `payment_note` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `active_school_key` varchar(64) DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `updated_by` int unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_active_school_key` (`active_school_key`),
  KEY `idx_school_active` (`vendor_id`, `school_id`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- NOTE:
-- Enforce one active QR per school by writing active_school_key only for active rows.
-- Example active_school_key format: "{vendor_id}-{school_id}".
-- For inactive rows, keep active_school_key NULL.
-- erp_pos_agent_school_access.agent_user_id maps to erp_agent_users.id.
