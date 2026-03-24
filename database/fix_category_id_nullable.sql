-- Fix category_id to be nullable in erp_bookset_packages table
-- This is needed because packages with products don't use category_id
-- 
-- IMPORTANT: Run these queries ONE AT A TIME in order
-- If any step fails, check the error and adjust accordingly

-- STEP 1: First, find the actual foreign key constraint name
-- Run this query to see the constraint name:
SELECT CONSTRAINT_NAME 
FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = 'erp_master' 
  AND TABLE_NAME = 'erp_bookset_packages' 
  AND COLUMN_NAME = 'category_id' 
  AND REFERENCED_TABLE_NAME IS NOT NULL;

-- STEP 2: Drop the foreign key constraint
-- Replace 'fk_bookset_packages_category' with the actual constraint name from STEP 1
-- If you don't know the name, you can also run: SHOW CREATE TABLE `erp_bookset_packages`;
ALTER TABLE `erp_bookset_packages` 
DROP FOREIGN KEY `fk_bookset_packages_category`;

-- STEP 3: Make category_id nullable
ALTER TABLE `erp_bookset_packages` 
MODIFY `category_id` INT(11) NULL DEFAULT NULL;

-- STEP 4: Re-add the foreign key constraint with ON DELETE SET NULL
-- This allows NULL values and sets category_id to NULL if the referenced category is deleted
ALTER TABLE `erp_bookset_packages` 
ADD CONSTRAINT `fk_bookset_packages_category` 
FOREIGN KEY (`category_id`) REFERENCES `erp_bookset_categories` (`id`) 
ON DELETE SET NULL ON UPDATE CASCADE;
