-- Size Charts Table
CREATE TABLE IF NOT EXISTS `erp_size_charts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL COMMENT 'Size Chart Name',
  `description` text,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_size_charts_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Size Charts for Uniforms';

-- Sizes Table
CREATE TABLE IF NOT EXISTS `erp_sizes` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `size_chart_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_size_charts table',
  `name` varchar(100) NOT NULL COMMENT 'Size Name (e.g., S, M, L, XL, 28, 30, etc.)',
  `display_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Order for display',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_size_chart_id` (`size_chart_id`),
  KEY `idx_status` (`status`),
  KEY `idx_display_order` (`display_order`),
  CONSTRAINT `fk_sizes_size_chart` FOREIGN KEY (`size_chart_id`) REFERENCES `erp_size_charts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sizes within Size Charts';

