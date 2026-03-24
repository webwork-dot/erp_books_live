-- ============================================
-- Dummy Orders Insert Script
-- Based on existing data in erp_master database
-- ============================================

-- Order 1: Payment Success, Delivered - Bookset Package Order
INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `delivered_at`, `created_at`, `updated_at`) VALUES
(1, 3, 16, 'ORD-2025-001', '2025-12-15 10:30:00', '2025-12-20 14:00:00', 'success', 'delivered', 'online', '2025-12-15 10:35:00', 4500.00, 810.00, 500.00, 4810.00, '123 Education Street, Andheri West', 'Mumbai', 'Maharashtra', '400053', '9876543210', 'Please deliver during school hours (9 AM - 3 PM)', '2025-12-20 13:45:00', '2025-12-15 10:30:00', '2025-12-20 13:45:00');

INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'package', NULL, 3, 5, 'testt Package', 'Grade 1 Bookset Package', NULL, 1, 5000.00, 4500.00, 18.00, 810.00, 500.00, 4500.00, 5310.00, 1500.00, 'Mandatory + Optional package', '2025-12-15 10:30:00', '2025-12-15 10:30:00');

-- Order 2: Payment Pending, Processing - Notebook Order
INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `created_at`, `updated_at`) VALUES
(2, 3, 17, 'ORD-2025-002', '2025-12-28 14:20:00', '2026-01-05 10:00:00', 'pending', 'processing', NULL, NULL, 14997.00, 749.85, 0.00, 15746.85, '456 School Road, Bandra West', 'Mumbai', 'Maharashtra', '400050', '9876543211', 'Urgent order - need before school reopening', '2025-12-28 14:20:00', '2025-12-28 14:20:00');

INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(2, 2, 'notebook', 1, NULL, NULL, 'testt notebook', 'A4 Center Binding Notebook', '74123', 3, 4999.00, 4999.00, 5.00, 749.85, 0.00, 14997.00, 15746.85, 9.00, '100 pages each', '2025-12-28 14:20:00', '2025-12-28 14:20:00');

-- Order 3: Payment Failed, Cancelled - Uniform Order
INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `cancelled_at`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(3, 3, 18, 'ORD-2025-003', '2025-12-25 11:15:00', '2026-01-10 12:00:00', 'failed', 'cancelled', 'card', NULL, 0.00, 0.00, 0.00, 0.00, '789 Knowledge Park, Powai', 'Mumbai', 'Maharashtra', '400076', '9876543212', 'Payment gateway issue', '2025-12-25 12:00:00', 'Payment transaction failed - card declined', '2025-12-25 11:15:00', '2025-12-25 12:00:00');

INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(3, 3, 'stationery', 1, NULL, NULL, 'Testtt Uniform', 'Black Male Uniform', '74123', 10, 0.00, 0.00, 5.00, 0.00, 0.00, 0.00, 0.00, 2220.00, 'Size: Medium', '2025-12-25 11:15:00', '2025-12-25 11:15:00');

-- Order 4: Payment Success, Processing - Bookset Order
INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `created_at`, `updated_at`) VALUES
(4, 3, 19, 'ORD-2025-004', '2025-12-30 09:45:00', '2026-01-08 11:00:00', 'success', 'processing', 'cash', '2025-12-30 09:50:00', 50.00, 9.00, 0.00, 59.00, '321 Learning Avenue, Vile Parle', 'Mumbai', 'Maharashtra', '400056', '9876543213', 'Cash on delivery preferred', '2025-12-30 09:45:00', '2025-12-30 09:50:00');

INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(4, 4, 'package', NULL, 5, 8, 'testt Package', 'Grade 1 Mandatory Package', '1121212', 1, 50.00, 50.00, 18.00, 9.00, 0.00, 50.00, 59.00, 100.00, 'Mandatory + Optional package', '2025-12-30 09:45:00', '2025-12-30 09:45:00');

-- Order 5: Payment Success, Delivered - Multiple Items Order
INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `delivered_at`, `created_at`, `updated_at`) VALUES
(5, 3, 20, 'ORD-2025-005', '2025-12-20 16:30:00', '2025-12-27 15:30:00', 'success', 'delivered', 'online', '2025-12-20 16:32:00', 4999.00, 249.95, 1000.00, 4248.95, '654 Education Lane, Goregaon', 'Mumbai', 'Maharashtra', '400063', '9876543214', 'Bulk order for school library', '2025-12-27 15:20:00', '2025-12-20 16:30:00', '2025-12-27 15:20:00');

INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(5, 5, 'notebook', 1, NULL, NULL, 'testt notebook', 'A4 Center Binding Notebook', '74123', 1, 4999.00, 3999.00, 5.00, 249.95, 1000.00, 4999.00, 4248.95, 3.00, 'Library stock', '2025-12-20 16:30:00', '2025-12-20 16:30:00'),
(6, 5, 'package', NULL, 3, 6, 'notebookssss Package', 'Notebook Package', NULL, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 150.00, 'Mandatory package', '2025-12-20 16:30:00', '2025-12-20 16:30:00');

-- Order Status History for Order 1 (Payment Success, Delivered)
INSERT INTO `erp_order_status_history` (`id`, `order_id`, `status_type`, `old_status`, `new_status`, `changed_by`, `notes`, `created_at`) VALUES
(1, 1, 'payment_status', NULL, 'pending', NULL, 'Order created', '2025-12-15 10:30:00'),
(2, 1, 'payment_status', 'pending', 'success', NULL, 'Payment received via online gateway', '2025-12-15 10:35:00'),
(3, 1, 'order_status', 'pending', 'processing', NULL, 'Order confirmed and processing started', '2025-12-15 11:00:00'),
(4, 1, 'order_status', 'processing', 'delivered', NULL, 'Order delivered successfully', '2025-12-20 13:45:00');

-- Order Status History for Order 2 (Payment Pending, Processing)
INSERT INTO `erp_order_status_history` (`id`, `order_id`, `status_type`, `old_status`, `new_status`, `changed_by`, `notes`, `created_at`) VALUES
(5, 2, 'payment_status', NULL, 'pending', NULL, 'Order created - payment pending', '2025-12-28 14:20:00'),
(6, 2, 'order_status', 'pending', 'processing', NULL, 'Order processing started - awaiting payment confirmation', '2025-12-28 15:00:00');

-- Order Status History for Order 3 (Payment Failed, Cancelled)
INSERT INTO `erp_order_status_history` (`id`, `order_id`, `status_type`, `old_status`, `new_status`, `changed_by`, `notes`, `created_at`) VALUES
(7, 3, 'payment_status', NULL, 'pending', NULL, 'Order created', '2025-12-25 11:15:00'),
(8, 3, 'payment_status', 'pending', 'failed', NULL, 'Payment transaction failed - card declined', '2025-12-25 11:30:00'),
(9, 3, 'order_status', 'pending', 'cancelled', NULL, 'Order cancelled due to payment failure', '2025-12-25 12:00:00');

-- Order Status History for Order 4 (Payment Success, Processing)
INSERT INTO `erp_order_status_history` (`id`, `order_id`, `status_type`, `old_status`, `new_status`, `changed_by`, `notes`, `created_at`) VALUES
(10, 4, 'payment_status', NULL, 'pending', NULL, 'Order created', '2025-12-30 09:45:00'),
(11, 4, 'payment_status', 'pending', 'success', NULL, 'Cash payment received', '2025-12-30 09:50:00'),
(12, 4, 'order_status', 'pending', 'processing', NULL, 'Order processing started', '2025-12-30 10:00:00');

-- Order Status History for Order 5 (Payment Success, Delivered)
INSERT INTO `erp_order_status_history` (`id`, `order_id`, `status_type`, `old_status`, `new_status`, `changed_by`, `notes`, `created_at`) VALUES
(13, 5, 'payment_status', NULL, 'pending', NULL, 'Order created', '2025-12-20 16:30:00'),
(14, 5, 'payment_status', 'pending', 'success', NULL, 'Payment received via online gateway', '2025-12-20 16:32:00'),
(15, 5, 'order_status', 'pending', 'processing', NULL, 'Order confirmed and processing started', '2025-12-20 17:00:00'),
(16, 5, 'order_status', 'processing', 'delivered', NULL, 'Order delivered successfully', '2025-12-27 15:20:00');

