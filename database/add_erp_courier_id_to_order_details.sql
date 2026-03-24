-- Add erp_courier_id to tbl_order_details for self-delivery courier selection
-- Run this in vendor/tenant database

ALTER TABLE tbl_order_details 
ADD COLUMN erp_courier_id INT UNSIGNED DEFAULT NULL COMMENT 'FK to erp_master_courier when courier=manual' 
AFTER courier;
