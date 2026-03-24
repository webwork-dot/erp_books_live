-- Database updates for barcode/QR code functionality
-- Run this SQL to add necessary fields and tables

-- 1. Add barcode_path field to tbl_order_details table
ALTER TABLE `tbl_order_details` 
ADD COLUMN `barcode_path` varchar(500) DEFAULT NULL COMMENT 'Path to barcode/QR code image' AFTER `shipping_label`;

-- 2. Create vendor_shipping_label table (if it doesn't exist)
CREATE TABLE IF NOT EXISTS `vendor_shipping_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL COMMENT 'Vendor ID',
  `process_slot` varchar(150) DEFAULT NULL COMMENT 'Process slot number',
  `slot_no` varchar(150) NOT NULL COMMENT 'Shipping slot number (order_unique_id)',
  `ctype` enum('direct','bigship','') DEFAULT 'direct' COMMENT 'Courier type',
  `barcode_url` varchar(500) DEFAULT NULL COMMENT 'Relative path to barcode image',
  `barcode_awb_url` varchar(500) DEFAULT NULL COMMENT 'Relative path to AWB barcode image',
  `label_url` varchar(500) DEFAULT NULL COMMENT 'Relative path to shipping label PDF',
  `awb_number` varchar(100) DEFAULT NULL COMMENT 'AWB tracking number',
  `shipment_provider` varchar(100) DEFAULT NULL COMMENT 'Shipment provider name',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_slot_no` (`slot_no`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_process_slot` (`process_slot`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Vendor shipping labels and barcodes';



