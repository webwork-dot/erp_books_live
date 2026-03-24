-- Rename order_number to invoice_number in tbl_order_third_party_shipping
-- Run if table was created with order_number column

ALTER TABLE `tbl_order_third_party_shipping` 
CHANGE COLUMN `order_number` `invoice_number` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Invoice number';
