-- Table to store individual products from bookset packages in orders
-- This allows tracking which specific products were included in each package for each order

CREATE TABLE IF NOT EXISTS `tbl_order_bookset_products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL COMMENT 'Order ID from tbl_order_details',
  `order_item_id` INT(11) NOT NULL COMMENT 'Order item ID from tbl_order_items',
  `package_id` INT(11) NOT NULL COMMENT 'Package ID from erp_bookset_packages',
  `package_name` VARCHAR(255) NOT NULL COMMENT 'Package name at time of order',
  `package_price` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Package price at time of order',
  `product_type` VARCHAR(50) NOT NULL COMMENT 'Type: textbook, notebook, stationery',
  `product_id` INT(11) NOT NULL COMMENT 'Product ID (textbook_id, notebook_id, or stationery_id)',
  `product_name` VARCHAR(500) NOT NULL COMMENT 'Product name at time of order',
  `product_sku` VARCHAR(255) DEFAULT NULL COMMENT 'Product SKU',
  `product_isbn` VARCHAR(255) DEFAULT NULL COMMENT 'Product ISBN',
  `quantity` INT(11) DEFAULT 1 COMMENT 'Quantity of this product',
  `unit_price` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Unit price at time of order',
  `total_price` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Total price (unit_price * quantity)',
  `weight` DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Product weight',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_order_item_id` (`order_item_id`),
  KEY `idx_package_id` (`package_id`),
  KEY `idx_product_type_id` (`product_type`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Individual products from bookset packages in orders';

