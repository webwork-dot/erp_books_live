-- Migration to add total_weight_gm to tbl_order_details
-- Stores total order weight in grams (gm) - sum of all package/product weights for bookset orders

USE `erp_client_shivambookscom`;

-- Add total_weight_gm field (weight in grams)
ALTER TABLE `tbl_order_details`
ADD COLUMN `total_weight_gm` decimal(12,2) DEFAULT NULL COMMENT 'Total order weight in grams' AFTER `type_order`;
