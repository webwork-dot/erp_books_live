-- Script to backfill board_id and grade_id for existing bookset orders
-- This script attempts to populate the missing board and grade data by matching
-- the package_ids in bookset orders to their corresponding packages

USE `erp_client_shivambookscom`;

-- Update existing bookset orders to populate board_id and grade_id
-- This assumes that all packages in a bookset order belong to the same school/board/grade

UPDATE tbl_order_items oi
LEFT JOIN erp_bookset_packages bp ON FIND_IN_SET(bp.id, REPLACE(oi.package_id, ' ', '')) > 0
SET oi.board_id = bp.board_id,
    oi.grade_id = bp.grade_id
WHERE oi.order_type = 'bookset'
  AND oi.board_id IS NULL
  AND oi.grade_id IS NULL
  AND oi.package_id IS NOT NULL
  AND oi.package_id != ''
  AND bp.id IS NOT NULL
GROUP BY oi.id;

-- Alternative approach: If the above doesn't work due to multiple packages with different boards/grades,
-- you might need to store the board/grade info in the bookset_packages_json field during order creation

-- For orders that still don't have board/grade info, you can manually update them:
-- UPDATE tbl_order_items SET board_id = X, grade_id = Y WHERE id = Z;