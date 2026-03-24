-- Add is_deliver_at_school to tbl_order_details
-- 1 = order collects student info (deliver at school), 0 = order uses address (deliver at address)
-- Run this SQL once to add the column

ALTER TABLE `tbl_order_details` 
ADD COLUMN `is_deliver_at_school` TINYINT(1) NOT NULL DEFAULT 0 
COMMENT '1=deliver at school (student info), 0=deliver at address';
