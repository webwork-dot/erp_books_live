-- Add ready_to_ship and ready_to_ship_time fields to tbl_order_details
-- This allows orders to be marked as ready for shipping after shipping label generation

ALTER TABLE `tbl_order_details`
ADD COLUMN `ready_to_ship` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Order is ready to ship, 0 = Not ready',
ADD COLUMN `ready_to_ship_time` datetime DEFAULT NULL COMMENT 'Timestamp when order was marked ready to ship';

-- Add index for performance
ALTER TABLE `tbl_order_details` ADD INDEX `idx_ready_to_ship` (`ready_to_ship`);