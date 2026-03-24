-- Notebooks Table
-- Note: Uses textbook_types for Type (shared) and textbook_publishers for Brand (shared)
CREATE TABLE IF NOT EXISTS `erp_notebooks` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `brand_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_publishers table (used as brand)',
  `product_name` varchar(255) NOT NULL COMMENT 'Product Name/Display Name',
  `isbn` varchar(100) DEFAULT NULL COMMENT 'ISBN/Bar Code No./SKU',
  `size` varchar(100) DEFAULT NULL COMMENT 'Size',
  `binding_type` enum('center_binding','perfect_binding','spiral_binding') DEFAULT NULL COMMENT 'Binding Type',
  `no_of_pages` int(11) DEFAULT NULL COMMENT 'No. Of Pages',
  `min_quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Min Quantity',
  `days_to_exchange` int(11) DEFAULT NULL COMMENT 'Days To Exchange',
  `pointers` text COMMENT 'Pointers / Highlights (CKEditor)',
  `product_description` text NOT NULL COMMENT 'Product Description (CKEditor)',
  `packaging_length` decimal(10,2) DEFAULT NULL COMMENT 'Length (in cm)',
  `packaging_width` decimal(10,2) DEFAULT NULL COMMENT 'Width (in cm)',
  `packaging_height` decimal(10,2) DEFAULT NULL COMMENT 'Height (in cm)',
  `packaging_weight` decimal(10,2) DEFAULT NULL COMMENT 'Weight (in gm)',
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'GST (%)',
  `hsn` varchar(50) DEFAULT NULL COMMENT 'HSN',
  `product_code` varchar(100) DEFAULT NULL COMMENT 'Product Code (For control of school set)',
  `sku` varchar(100) DEFAULT NULL COMMENT 'SKU /Product Code',
  `mrp` decimal(10,2) NOT NULL COMMENT 'MRP',
  `selling_price` decimal(10,2) NOT NULL COMMENT 'Selling Price',
  `meta_title` varchar(255) DEFAULT NULL COMMENT 'Meta Title',
  `meta_keywords` text COMMENT 'Meta Keywords',
  `meta_description` text COMMENT 'Meta Description',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_brand_id` (`brand_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_notebooks_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notebooks_brand` FOREIGN KEY (`brand_id`) REFERENCES `erp_textbook_publishers` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notebooks';

-- Notebook Type Mapping Table (Many-to-Many with textbook_types)
CREATE TABLE IF NOT EXISTS `erp_notebook_type_mapping` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `notebook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_notebooks table',
  `type_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_types table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_notebook_type` (`notebook_id`, `type_id`),
  KEY `idx_notebook_id` (`notebook_id`),
  KEY `idx_type_id` (`type_id`),
  CONSTRAINT `fk_notebook_type_mapping_notebook` FOREIGN KEY (`notebook_id`) REFERENCES `erp_notebooks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notebook_type_mapping_type` FOREIGN KEY (`type_id`) REFERENCES `erp_textbook_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notebook Type Mapping';

-- Notebook Images Table
CREATE TABLE IF NOT EXISTS `erp_notebook_images` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `notebook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_notebooks table',
  `image_path` varchar(500) NOT NULL COMMENT 'Image path',
  `image_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Image order for display',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notebook_id` (`notebook_id`),
  KEY `idx_image_order` (`image_order`),
  CONSTRAINT `fk_notebook_images_notebook` FOREIGN KEY (`notebook_id`) REFERENCES `erp_notebooks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notebook Images';

