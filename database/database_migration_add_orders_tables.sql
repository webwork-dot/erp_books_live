-- Migration: Add Orders System Tables
-- Date: 2025-12-31
-- Description: Create tables for vendor orders management with payment status, order status, and order items

-- ============================================
-- 1. Main Orders Table
-- ============================================
CREATE TABLE IF NOT EXISTS `erp_orders` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_vendors table',
  `school_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_schools table',
  `order_number` varchar(50) NOT NULL COMMENT 'Unique order number/invoice number',
  `order_date` datetime NOT NULL COMMENT 'Date when order was placed',
  `delivery_date` datetime NULL DEFAULT NULL COMMENT 'Expected or actual delivery date',
  `payment_status` enum('pending','failed','success') NOT NULL DEFAULT 'pending' COMMENT 'Payment status: pending (yellow), failed (red), success (green)',
  `order_status` enum('pending','processing','delivered','cancelled') NOT NULL DEFAULT 'pending' COMMENT 'Order status',
  `payment_method` varchar(50) NULL DEFAULT NULL COMMENT 'Payment method (cash, card, online, etc.)',
  `payment_date` datetime NULL DEFAULT NULL COMMENT 'Date when payment was made',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Subtotal amount before tax',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Tax/GST amount',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Discount amount',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Total order amount',
  `delivery_address` text NULL DEFAULT NULL COMMENT 'Delivery address',
  `delivery_city` varchar(100) NULL DEFAULT NULL,
  `delivery_state` varchar(100) NULL DEFAULT NULL,
  `delivery_pincode` varchar(20) NULL DEFAULT NULL,
  `delivery_phone` varchar(20) NULL DEFAULT NULL,
  `notes` text NULL DEFAULT NULL COMMENT 'Order notes/comments',
  `cancelled_at` datetime NULL DEFAULT NULL COMMENT 'Date when order was cancelled',
  `cancellation_reason` text NULL DEFAULT NULL COMMENT 'Reason for cancellation',
  `delivered_at` datetime NULL DEFAULT NULL COMMENT 'Date when order was delivered',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_order_number` (`order_number`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_school_id` (`school_id`),
  KEY `idx_order_date` (`order_date`),
  KEY `idx_payment_status` (`payment_status`),
  KEY `idx_order_status` (`order_status`),
  KEY `idx_payment_order_status` (`payment_status`, `order_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Main orders table';

-- ============================================
-- 2. Order Items Table
-- ============================================
CREATE TABLE IF NOT EXISTS `erp_order_items` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_orders table',
  `product_type` enum('textbook','notebook','stationery','bookset','package') NOT NULL COMMENT 'Type of product',
  `product_id` int(11) UNSIGNED NULL DEFAULT NULL COMMENT 'ID of the product (varies by product_type)',
  `bookset_id` int(11) UNSIGNED NULL DEFAULT NULL COMMENT 'If product_type is bookset or package',
  `package_id` int(11) UNSIGNED NULL DEFAULT NULL COMMENT 'If product_type is package',
  `product_name` varchar(255) NOT NULL COMMENT 'Product name at time of order',
  `display_name` varchar(255) NULL DEFAULT NULL COMMENT 'Display name if different',
  `sku` varchar(100) NULL DEFAULT NULL COMMENT 'Product SKU/ISBN',
  `quantity` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Quantity ordered',
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Unit price at time of order',
  `discounted_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Discounted price per unit',
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Tax/GST percentage',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Tax amount for this item',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Discount amount for this item',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Subtotal before tax',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Total amount for this item',
  `weight` decimal(10,2) NULL DEFAULT NULL COMMENT 'Product weight',
  `notes` text NULL DEFAULT NULL COMMENT 'Item-specific notes',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_product_type_id` (`product_type`, `product_id`),
  KEY `idx_bookset_id` (`bookset_id`),
  KEY `idx_package_id` (`package_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `erp_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Order items table';

-- ============================================
-- 3. Order Status History Table (Optional - for tracking status changes)
-- ============================================
CREATE TABLE IF NOT EXISTS `erp_order_status_history` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_orders table',
  `status_type` enum('payment_status','order_status') NOT NULL COMMENT 'Type of status change',
  `old_status` varchar(50) NULL DEFAULT NULL COMMENT 'Previous status',
  `new_status` varchar(50) NOT NULL COMMENT 'New status',
  `changed_by` int(11) UNSIGNED NULL DEFAULT NULL COMMENT 'User/vendor who made the change',
  `notes` text NULL DEFAULT NULL COMMENT 'Notes about the status change',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_status_type` (`status_type`),
  CONSTRAINT `fk_order_status_history_order` FOREIGN KEY (`order_id`) REFERENCES `erp_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Order status change history';

-- ============================================
-- 4. Foreign Key Constraints (Add after tables are created)
-- ============================================
-- Note: These constraints will only be added if the referenced tables exist
-- Remove foreign key constraints if they cause issues

-- Add foreign key for vendor_id (only if erp_vendors table exists)
-- ALTER TABLE `erp_orders` ADD CONSTRAINT `fk_orders_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_vendors` (`id`) ON DELETE CASCADE;

-- Add foreign key for school_id (only if erp_schools table exists)
-- ALTER TABLE `erp_orders` ADD CONSTRAINT `fk_orders_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE RESTRICT;

-- ============================================
-- 5. Indexes for better performance
-- ============================================
-- Additional composite indexes for common queries
-- Note: These indexes may already exist if you run this script multiple times
-- If you get "Duplicate key name" errors, you can safely ignore them or drop the indexes first

-- Uncomment these lines if you want to add additional indexes (remove if they already exist)
-- CREATE INDEX `idx_orders_vendor_status` ON `erp_orders` (`vendor_id`, `order_status`);
-- CREATE INDEX `idx_orders_vendor_payment` ON `erp_orders` (`vendor_id`, `payment_status`);
-- CREATE INDEX `idx_orders_school_date` ON `erp_orders` (`school_id`, `order_date`);

-- ============================================
-- 6. Sample data structure notes
-- ============================================
-- Payment Status Tags:
--   - 'pending' = Yellow badge
--   - 'failed' = Red badge
--   - 'success' = Green badge
--
-- Order Status Tags:
--   - 'pending' = Default status
--   - 'processing' = Order is being processed
--   - 'delivered' = Order has been delivered (Green badge)
--   - 'cancelled' = Order has been cancelled (Red badge)
--
-- Filter Tabs:
--   - All Orders
--   - Payment Pending (payment_status = 'pending')
--   - Payment Failed (payment_status = 'failed')
--   - Payment Success (payment_status = 'success')
--   - Delivered Orders (order_status = 'delivered')
--   - Cancelled Orders (order_status = 'cancelled')

