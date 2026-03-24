-- =====================================================
-- Add with_product field to erp_bookset_packages table
-- =====================================================

-- Add with_product column to erp_bookset_packages table
ALTER TABLE `erp_bookset_packages`
ADD COLUMN `with_product` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Package with products, 0 = Package without products' AFTER `status`;

-- Add index for better query performance
ALTER TABLE `erp_bookset_packages` ADD INDEX `idx_with_product` (`with_product`);

-- Update existing records based on whether they have products
UPDATE `erp_bookset_packages` bp
SET bp.`with_product` = (
    SELECT CASE 
        WHEN COUNT(bpp.id) > 0 THEN 1 
        ELSE 0 
    END
    FROM `erp_bookset_package_products` bpp
    WHERE bpp.`package_id` = bp.`id` 
    AND bpp.`status` = 'active'
);









