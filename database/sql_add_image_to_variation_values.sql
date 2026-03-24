-- Add image field to variation values table
-- This allows each variation value (e.g., "Red", "Small", "Cotton") to have an associated image

ALTER TABLE `erp_variation_values`
ADD COLUMN `image_path` VARCHAR(500) DEFAULT NULL COMMENT 'Path to the image file for this variation value' AFTER `value`;

-- Optional: Add index for better performance if needed
-- ALTER TABLE `erp_variation_values` ADD INDEX `idx_image_path` (`image_path`);

-- Example of how images will be stored:
-- For Color variation type:
--   - Red: image_path = 'assets/uploads/vendors/1/variations/red-color.jpg'
--   - Blue: image_path = 'assets/uploads/vendors/1/variations/blue-color.jpg'
--
-- For Size variation type:
--   - Small: image_path = 'assets/uploads/vendors/1/variations/small-size.jpg'
--   - Large: image_path = 'assets/uploads/vendors/1/variations/large-size.jpg'
