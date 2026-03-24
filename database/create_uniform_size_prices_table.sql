-- Uniform Size Prices Table
CREATE TABLE IF NOT EXISTS `erp_uniform_size_prices` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uniform_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_uniforms table',
  `size_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_sizes table',
  `mrp` decimal(10,2) NOT NULL COMMENT 'Maximum Retail Price',
  `selling_price` decimal(10,2) NOT NULL COMMENT 'Selling Price',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uniform_size` (`uniform_id`, `size_id`),
  KEY `idx_uniform_id` (`uniform_id`),
  KEY `idx_size_id` (`size_id`),
  CONSTRAINT `fk_uniform_size_prices_uniform` FOREIGN KEY (`uniform_id`) REFERENCES `erp_uniforms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_uniform_size_prices_size` FOREIGN KEY (`size_id`) REFERENCES `erp_sizes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Size-specific prices for uniforms';

