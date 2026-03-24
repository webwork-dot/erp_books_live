-- Rewrite image paths to vendor uploads directory
-- This converts 'uploads/school/<filename>' to 'assets/uploads/vendors/1/schools/<filename>'
-- Adjust vendor_id in the path if needed

UPDATE erp_school_images
SET image_path = CONCAT('assets/uploads/vendors/1/schools/', SUBSTRING_INDEX(image_path, '/', -1))
WHERE image_path LIKE 'uploads/school/%';

-- Ensure primary flag consistency: if multiple images exist per school, keep first inserted as primary
-- (Optional) You can uncomment this to reset all to non-primary and set first one as primary per school
-- UPDATE erp_school_images SET is_primary = 0;
-- UPDATE erp_school_images i
-- JOIN (
--   SELECT school_id, MIN(id) AS min_id
--   FROM erp_school_images
--   GROUP BY school_id
-- ) t ON t.min_id = i.id
-- SET i.is_primary = 1;
