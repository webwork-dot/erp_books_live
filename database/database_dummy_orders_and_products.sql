-- ============================================
-- Dummy Data for Orders and Products
-- Date: 2025-12-31
-- Description: Creates 5 dummy orders, 5 stationery products, 5 uniforms, and 5 packages
-- ============================================

-- ============================================
-- 1. Create Stationery Categories (if not exists)
-- ============================================
INSERT INTO `erp_stationery_categories` (`id`, `vendor_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'Writing Instruments', 'Pens, pencils, and writing tools', 'active', NOW(), NOW()),
(2, 3, 'Geometry Tools', 'Compass, protractor, and measuring tools', 'active', NOW(), NOW()),
(3, 3, 'Erasers & Correction', 'Erasers and correction supplies', 'active', NOW(), NOW()),
(4, 3, 'Notebooks', 'Notebooks and writing pads', 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `name`=`name`;

-- ============================================
-- 2. Dummy Stationery Products (5 items)
-- ============================================
INSERT INTO `erp_stationery` (`id`, `vendor_id`, `category_id`, `brand_id`, `colour_id`, `product_name`, `isbn`, `sku`, `product_code`, `min_quantity`, `days_to_exchange`, `pointers`, `product_description`, `packaging_length`, `packaging_width`, `packaging_height`, `packaging_weight`, `gst_percentage`, `gst_type`, `hsn`, `mrp`, `selling_price`, `meta_title`, `meta_keywords`, `meta_description`, `status`, `is_individual`, `is_set`, `created_at`, `updated_at`) VALUES
(1, 3, 1, NULL, NULL, 'Premium Ballpoint Pen Set - Blue', 'PEN001', 'STN-PEN-001', 'PEN-SET-BLUE', 1, 7, '<p>Premium quality ballpoint pens</p>', '<p>Set of 10 premium blue ballpoint pens with smooth writing experience. Perfect for students and professionals.</p>', 15.00, 2.00, 2.00, 50.00, 18.00, 'cgst_sgst', '96081000', 250.00, 200.00, 'Premium Ballpoint Pen Set', 'pen, ballpoint, stationery', 'Premium ballpoint pen set for students', 'active', 1, 0, NOW(), NOW()),
(2, 3, 4, NULL, NULL, 'A4 Size Notebook - 200 Pages', 'NB001', 'STN-NB-001', 'NB-A4-200', 1, 7, '<p>High quality ruled notebook</p>', '<p>Premium A4 size notebook with 200 ruled pages. Perfect binding with durable cover.</p>', 30.00, 21.00, 2.50, 300.00, 12.00, 'cgst_sgst', '48201000', 150.00, 120.00, 'A4 Notebook 200 Pages', 'notebook, ruled, A4', 'Premium A4 notebook with 200 pages', 'active', 1, 0, NOW(), NOW()),
(3, 3, 2, NULL, NULL, 'Geometry Box Set - Complete', 'GB001', 'STN-GB-001', 'GB-COMPLETE', 1, 7, '<p>Complete geometry box with all instruments</p>', '<p>Complete geometry box containing compass, protractor, ruler, divider, and eraser. Made with high quality materials.</p>', 20.00, 10.00, 3.00, 150.00, 18.00, 'cgst_sgst', '90172000', 350.00, 280.00, 'Geometry Box Complete Set', 'geometry box, compass, protractor', 'Complete geometry box set for students', 'active', 1, 0, NOW(), NOW()),
(4, 3, 1, NULL, NULL, 'HB Pencil Set - Pack of 12', 'PCL001', 'STN-PCL-001', 'PCL-HB-12', 1, 7, '<p>Premium HB pencils</p>', '<p>Set of 12 premium HB pencils with eraser tips. Perfect for writing and drawing.</p>', 18.00, 2.00, 2.00, 100.00, 12.00, 'cgst_sgst', '96091000', 120.00, 95.00, 'HB Pencil Set 12 Pack', 'pencil, HB, stationery', 'Premium HB pencil set of 12', 'active', 1, 0, NOW(), NOW()),
(5, 3, 3, NULL, NULL, 'Eraser Set - Pack of 10', 'ERS001', 'STN-ERS-001', 'ERS-SET-10', 1, 7, '<p>High quality erasers</p>', '<p>Set of 10 premium quality erasers. Non-dust and smudge-free.</p>', 12.00, 5.00, 2.00, 50.00, 18.00, 'cgst_sgst', '40169200', 80.00, 65.00, 'Eraser Set 10 Pack', 'eraser, stationery', 'Premium eraser set of 10', 'active', 1, 0, NOW(), NOW());

-- ============================================
-- 3. Dummy Uniform Products (5 items - adding 4 more to existing 1)
-- Note: Using existing uniform types: 1=shirt, 2=Pants, 3=Tie, 4=Socks
-- ============================================
INSERT INTO `erp_uniforms` (`id`, `vendor_id`, `uniform_type_id`, `school_id`, `branch_id`, `board_id`, `gender`, `color`, `product_name`, `isbn`, `min_quantity`, `days_to_exchange`, `material_id`, `product_origin`, `product_description`, `manufacturer_details`, `packer_details`, `customer_details`, `price`, `size_chart_id`, `size_id`, `packaging_length`, `packaging_width`, `packaging_height`, `packaging_weight`, `tax`, `gst_percentage`, `hsn`, `meta_title`, `meta_keywords`, `meta_description`, `status`, `is_individual`, `is_set`, `created_at`, `updated_at`) VALUES
(2, 3, 1, 17, NULL, 7, 'male', 'Navy Blue', 'Boys School Shirt - Navy Blue', 'UNF-SHIRT-001', 1, 7, 2, 'India', '<p>Premium quality school shirt for boys in navy blue color</p>', '<p>Manufactured by ABC Textiles</p>', '<p>Packed by XYZ Garments</p>', '<p>For school students</p>', 599.00, 2, NULL, 30.00, 25.00, 5.00, 200.00, NULL, 5.00, '61091000', 'Boys School Shirt Navy Blue', 'uniform, shirt, school', 'Premium boys school shirt', 'active', 1, 0, NOW(), NOW()),
(3, 3, 2, 18, NULL, 6, 'female', 'Grey', 'Girls School Pants - Grey', 'UNF-PANTS-001', 1, 7, 2, 'India', '<p>Premium quality school pants for girls in grey color</p>', '<p>Manufactured by ABC Textiles</p>', '<p>Packed by XYZ Garments</p>', '<p>For school students</p>', 699.00, 2, NULL, 35.00, 30.00, 3.00, 250.00, NULL, 5.00, '62034200', 'Girls School Pants Grey', 'uniform, pants, school', 'Premium girls school pants', 'active', 1, 0, NOW(), NOW()),
(4, 3, 2, 19, NULL, 10, 'unisex', 'Grey', 'School Trousers - Grey', 'UNF-TROUSERS-001', 1, 7, 2, 'India', '<p>Premium quality school trousers in grey color</p>', '<p>Manufactured by ABC Textiles</p>', '<p>Packed by XYZ Garments</p>', '<p>For school students</p>', 799.00, 2, NULL, 40.00, 35.00, 5.00, 300.00, NULL, 5.00, '62034200', 'School Trousers Grey', 'uniform, trousers, school', 'Premium school trousers', 'active', 1, 0, NOW(), NOW()),
(5, 3, 3, 20, NULL, 7, 'unisex', 'Navy Blue', 'School Tie - Navy Blue', 'UNF-TIE-001', 1, 7, 2, 'India', '<p>Premium quality school tie in navy blue</p>', '<p>Manufactured by ABC Textiles</p>', '<p>Packed by XYZ Garments</p>', '<p>For school students</p>', 299.00, 2, NULL, 25.00, 5.00, 2.00, 50.00, NULL, 5.00, '62114300', 'School Tie Navy Blue', 'uniform, tie, school', 'Premium school tie', 'active', 1, 0, NOW(), NOW()),
(6, 3, 1, 16, NULL, 6, 'female', 'White', 'Girls School Shirt - White', 'UNF-SHIRT-002', 1, 7, 2, 'India', '<p>Premium quality school shirt for girls in white color</p>', '<p>Manufactured by ABC Textiles</p>', '<p>Packed by XYZ Garments</p>', '<p>For school students</p>', 599.00, 2, NULL, 30.00, 25.00, 5.00, 200.00, NULL, 5.00, '61091000', 'Girls School Shirt White', 'uniform, shirt, school', 'Premium girls school shirt', 'active', 1, 0, NOW(), NOW());

-- ============================================
-- 4. Dummy Package Products (5 items - ensuring we have 5 good packages)
-- ============================================
INSERT INTO `erp_bookset_packages` (`id`, `vendor_id`, `bookset_id`, `school_id`, `board_id`, `grade_id`, `category_id`, `category`, `package_name`, `package_price`, `package_offer_price`, `gst`, `hsn`, `package_weight`, `is_it`, `note`, `mandatory_count`, `optional_count`, `mandatory_optional_count`, `status`, `with_product`, `created_at`, `updated_at`) VALUES
(12, 3, NULL, 16, 6, 1, NULL, 'textbook', 'Grade 1 Complete Bookset Package', 2500.00, 2200.00, 12.00, '49011000', 2000.00, 'mandatory', 'Complete package for Grade 1 students', 5, 0, 0, 'active', 1, NOW(), NOW()),
(13, 3, NULL, 17, 7, 2, NULL, 'notebook', 'Grade 2 Notebook Package', 800.00, 700.00, 12.00, '48201000', 1500.00, 'mandatory', 'Complete notebook package for Grade 2', 3, 0, 0, 'active', 1, NOW(), NOW()),
(14, 3, NULL, 18, 6, 3, NULL, 'stationery', 'Grade 3 Stationery Kit', 1200.00, 1000.00, 18.00, '96081000', 800.00, 'mandatory+optional', 'Complete stationery kit for Grade 3', 0, 0, 1, 'active', 0, NOW(), NOW()),
(15, 3, NULL, 19, 10, 1, NULL, 'textbook', 'Grade 1 Premium Package', 3500.00, 3000.00, 12.00, '49011000', 2500.00, 'mandatory+optional', 'Premium package with textbooks and notebooks', 0, 0, 1, 'active', 1, NOW(), NOW()),
(16, 3, NULL, 20, 7, 2, NULL, 'notebook', 'Grade 2 Complete Study Package', 1500.00, 1300.00, 12.00, '48201000', 1800.00, 'mandatory', 'Complete study package for Grade 2', 4, 0, 0, 'active', 1, NOW(), NOW());

-- ============================================
-- 5. Dummy Orders (5 orders with different statuses)
-- ============================================

-- Order 1: Payment Success + Delivered
INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `cancelled_at`, `cancellation_reason`, `delivered_at`, `created_at`, `updated_at`) VALUES
(1, 3, 16, 'ORD-2025-001', '2025-12-15 10:30:00', '2025-12-20 14:00:00', 'success', 'delivered', 'Online Payment', '2025-12-15 10:35:00', 2500.00, 300.00, 200.00, 2600.00, '123 Education Street, Andheri West', 'Mumbai', 'Maharashtra', '400053', '9876543210', 'Please deliver during school hours', NULL, NULL, '2025-12-20 14:00:00', '2025-12-15 10:30:00', '2025-12-20 14:00:00');

-- Order Items for Order 1
INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'package', NULL, NULL, 12, 'Grade 1 Complete Bookset Package', 'Grade 1 Complete Bookset', 'PKG-001', 1, 2500.00, 2200.00, 12.00, 264.00, 300.00, 2200.00, 2464.00, 2000.00, NULL, '2025-12-15 10:30:00', '2025-12-15 10:30:00'),
(2, 1, 'stationery', 1, NULL, NULL, 'Premium Ballpoint Pen Set - Blue', 'Pen Set Blue', 'STN-PEN-001', 2, 200.00, 200.00, 18.00, 72.00, 0.00, 400.00, 472.00, 100.00, NULL, '2025-12-15 10:30:00', '2025-12-15 10:30:00');

-- Order 2: Payment Pending + Processing
INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `cancelled_at`, `cancellation_reason`, `delivered_at`, `created_at`, `updated_at`) VALUES
(2, 3, 17, 'ORD-2025-002', '2025-12-28 14:20:00', '2026-01-05 16:00:00', 'pending', 'processing', NULL, NULL, 1800.00, 216.00, 100.00, 1916.00, '456 School Road, Bandra West', 'Mumbai', 'Maharashtra', '400050', '9876543211', 'Urgent delivery required', NULL, NULL, NULL, '2025-12-28 14:20:00', '2025-12-28 14:20:00');

-- Order Items for Order 2
INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(3, 2, 'uniform', 2, NULL, NULL, 'Boys School Shirt - Navy Blue', 'School Shirt Navy', 'UNF-SHIRT-001', 5, 599.00, 550.00, 5.00, 137.50, 245.00, 2750.00, 2887.50, 1000.00, 'Size: Medium', '2025-12-28 14:20:00', '2025-12-28 14:20:00'),
(4, 2, 'stationery', 2, NULL, NULL, 'A4 Size Notebook - 200 Pages', 'A4 Notebook', 'STN-NB-001', 10, 120.00, 110.00, 12.00, 132.00, 100.00, 1100.00, 1232.00, 3000.00, NULL, '2025-12-28 14:20:00', '2025-12-28 14:20:00');

-- Order 3: Payment Failed + Cancelled
INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `cancelled_at`, `cancellation_reason`, `delivered_at`, `created_at`, `updated_at`) VALUES
(3, 3, 18, 'ORD-2025-003', '2025-12-25 11:15:00', NULL, 'failed', 'cancelled', 'Card Payment', NULL, 3200.00, 384.00, 200.00, 3384.00, '789 Knowledge Park, Powai', 'Mumbai', 'Maharashtra', '400076', '9876543212', 'Payment gateway error', '2025-12-25 15:30:00', 'Payment failed due to insufficient funds', NULL, '2025-12-25 11:15:00', '2025-12-25 15:30:00');

-- Order Items for Order 3
INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(5, 3, 'package', NULL, NULL, 13, 'Grade 2 Notebook Package', 'Grade 2 Notebooks', 'PKG-002', 2, 800.00, 700.00, 12.00, 168.00, 200.00, 1400.00, 1568.00, 3000.00, NULL, '2025-12-25 11:15:00', '2025-12-25 11:15:00'),
(6, 3, 'uniform', 3, NULL, NULL, 'Girls School Skirt - White', 'School Skirt White', 'UNF-SKIRT-001', 8, 699.00, 650.00, 5.00, 260.00, 392.00, 5200.00, 5460.00, 2000.00, 'Size: Small', '2025-12-25 11:15:00', '2025-12-25 11:15:00');

-- Order 4: Payment Success + Processing
INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `cancelled_at`, `cancellation_reason`, `delivered_at`, `created_at`, `updated_at`) VALUES
(4, 3, 19, 'ORD-2025-004', '2025-12-30 09:45:00', '2026-01-10 12:00:00', 'success', 'processing', 'Cash on Delivery', '2025-12-30 09:45:00', 4500.00, 540.00, 300.00, 4740.00, '321 Learning Avenue, Vile Parle', 'Mumbai', 'Maharashtra', '400056', '9876543213', 'Bulk order for school', NULL, NULL, NULL, '2025-12-30 09:45:00', '2025-12-30 09:45:00');

-- Order Items for Order 4
INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(7, 4, 'package', NULL, NULL, 15, 'Grade 1 Premium Package', 'Premium Package Grade 1', 'PKG-003', 1, 3500.00, 3000.00, 12.00, 360.00, 500.00, 3000.00, 3360.00, 2500.00, NULL, '2025-12-30 09:45:00', '2025-12-30 09:45:00'),
(8, 4, 'stationery', 3, NULL, NULL, 'Geometry Box Set - Complete', 'Geometry Box', 'STN-GB-001', 5, 280.00, 250.00, 18.00, 225.00, 150.00, 1250.00, 1475.00, 750.00, NULL, '2025-12-30 09:45:00', '2025-12-30 09:45:00'),
(9, 4, 'uniform', 4, NULL, NULL, 'School Trousers - Grey', 'School Trousers', 'UNF-TROUSERS-001', 3, 799.00, 750.00, 5.00, 112.50, 147.00, 2250.00, 2362.50, 900.00, 'Size: Large', '2025-12-30 09:45:00', '2025-12-30 09:45:00');

-- Order 5: Payment Pending + Pending
INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `cancelled_at`, `cancellation_reason`, `delivered_at`, `created_at`, `updated_at`) VALUES
(5, 3, 20, 'ORD-2025-005', '2025-12-31 16:00:00', '2026-01-15 10:00:00', 'pending', 'pending', NULL, NULL, 2200.00, 264.00, 150.00, 2314.00, '654 Education Lane, Goregaon', 'Mumbai', 'Maharashtra', '400063', '9876543214', 'New order - awaiting confirmation', NULL, NULL, NULL, '2025-12-31 16:00:00', '2025-12-31 16:00:00');

-- Order Items for Order 5
INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(10, 5, 'package', NULL, NULL, 16, 'Grade 2 Complete Study Package', 'Study Package Grade 2', 'PKG-004', 1, 1500.00, 1300.00, 12.00, 156.00, 200.00, 1300.00, 1456.00, 1800.00, NULL, '2025-12-31 16:00:00', '2025-12-31 16:00:00'),
(11, 5, 'stationery', 4, NULL, NULL, 'HB Pencil Set - Pack of 12', 'HB Pencils 12', 'STN-PCL-001', 8, 95.00, 90.00, 12.00, 86.40, 40.00, 720.00, 806.40, 800.00, NULL, '2025-12-31 16:00:00', '2025-12-31 16:00:00'),
(12, 5, 'uniform', 5, NULL, NULL, 'School Tie - Navy Blue', 'School Tie', 'UNF-TIE-001', 2, 299.00, 280.00, 5.00, 28.00, 38.00, 560.00, 588.00, 100.00, NULL, '2025-12-31 16:00:00', '2025-12-31 16:00:00');

-- ============================================
-- 6. Order Status History (Sample entries)
-- ============================================
INSERT INTO `erp_order_status_history` (`id`, `order_id`, `status_type`, `old_status`, `new_status`, `changed_by`, `notes`, `created_at`) VALUES
(1, 1, 'payment_status', NULL, 'pending', 3, 'Order created', '2025-12-15 10:30:00'),
(2, 1, 'payment_status', 'pending', 'success', 3, 'Payment received via online payment', '2025-12-15 10:35:00'),
(3, 1, 'order_status', 'pending', 'processing', 3, 'Order confirmed and processing started', '2025-12-15 11:00:00'),
(4, 1, 'order_status', 'processing', 'delivered', 3, 'Order delivered successfully', '2025-12-20 14:00:00'),
(5, 2, 'order_status', 'pending', 'processing', 3, 'Order confirmed, awaiting payment', '2025-12-28 14:25:00'),
(6, 3, 'payment_status', NULL, 'pending', 3, 'Order created', '2025-12-25 11:15:00'),
(7, 3, 'payment_status', 'pending', 'failed', 3, 'Payment failed - insufficient funds', '2025-12-25 12:00:00'),
(8, 3, 'order_status', 'pending', 'cancelled', 3, 'Order cancelled due to payment failure', '2025-12-25 15:30:00'),
(9, 4, 'payment_status', NULL, 'success', 3, 'Payment received via cash on delivery', '2025-12-30 09:45:00'),
(10, 4, 'order_status', 'pending', 'processing', 3, 'Order confirmed and processing', '2025-12-30 10:00:00');

-- ============================================
-- Notes:
-- ============================================
-- 1. Stationery Products: 5 items created (IDs 1-5)
-- 2. Uniform Products: 5 items created (IDs 1-6, using existing ID 1 and adding 2-6)
-- 3. Package Products: 5 items created (IDs 12-16)
-- 4. Orders: 5 orders created with different statuses:
--    - Order 1: Payment Success + Delivered
--    - Order 2: Payment Pending + Processing
--    - Order 3: Payment Failed + Cancelled
--    - Order 4: Payment Success + Processing
--    - Order 5: Payment Pending + Pending
-- 5. Order Items: Multiple items per order with realistic pricing
-- 6. Order Status History: Sample history entries for tracking status changes
--
-- All orders are for vendor_id = 3 (ganeshbook)
-- All orders reference existing schools (IDs 16-20)
-- Product IDs reference the newly created products above

