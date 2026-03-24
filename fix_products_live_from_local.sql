-- ============================================================================
-- SQL Script to Fix Live Database Products from Local Database
-- This script updates missing/incorrect data in erp_client_kirtibookin (LIVE)
-- from erp_client_kirtibookin_local (LOCAL) database
-- Both databases are on the same server
-- ============================================================================

-- Update products in LIVE database from LOCAL database
-- This will update: slug, description, meta fields, and other important data
-- WHERE the product exists in both databases (matched by ISBN or product_name)

UPDATE erp_client_kirtibookin.erp_products AS live
INNER JOIN erp_client_kirtibookin_local.erp_products AS local
  ON (
    -- Match by ISBN (primary match)
    (live.isbn = local.isbn AND live.isbn IS NOT NULL AND live.isbn != '')
    OR
    -- Match by product_name (secondary match if ISBN is missing)
    (live.product_name = local.product_name AND (live.isbn IS NULL OR live.isbn = ''))
  )
SET
  -- Update slug if missing or different
  live.slug = CASE 
    WHEN live.slug IS NULL OR live.slug = '' OR live.slug LIKE 'tb-%' 
    THEN local.slug 
    ELSE live.slug 
  END,
  
  -- Update description if missing
  live.description = CASE 
    WHEN live.description IS NULL OR live.description = '' 
    THEN local.description 
    ELSE live.description 
  END,
  
  -- Update meta_title if missing
  live.meta_title = CASE 
    WHEN live.meta_title IS NULL OR live.meta_title = '' 
    THEN local.meta_title 
    ELSE live.meta_title 
  END,
  
  -- Update meta_keyword if missing
  live.meta_keyword = CASE 
    WHEN live.meta_keyword IS NULL OR live.meta_keyword = '' 
    THEN local.meta_keyword 
    ELSE live.meta_keyword 
  END,
  
  -- Update meta_description if missing
  live.meta_description = CASE 
    WHEN live.meta_description IS NULL OR live.meta_description = '' 
    THEN local.meta_description 
    ELSE live.meta_description 
  END,
  
  -- Update pointers if missing
  live.pointers = CASE 
    WHEN live.pointers IS NULL OR live.pointers = '' OR live.pointers = '[]'
    THEN local.pointers 
    ELSE live.pointers 
  END,
  
  -- Update binding_type if missing
  live.binding_type = CASE 
    WHEN live.binding_type IS NULL OR live.binding_type = '' 
    THEN local.binding_type 
    ELSE live.binding_type 
  END,
  
  -- Update no_of_pages if missing
  live.no_of_pages = CASE 
    WHEN live.no_of_pages IS NULL OR live.no_of_pages = 0 
    THEN local.no_of_pages 
    ELSE live.no_of_pages 
  END,
  
  -- Update weight if missing
  live.weight = CASE 
    WHEN live.weight IS NULL OR live.weight = '' OR live.weight = '0.00'
    THEN local.weight 
    ELSE live.weight 
  END,
  
  -- Update dimensions if missing
  live.length = CASE 
    WHEN live.length IS NULL OR live.length = '' OR live.length = '0.00'
    THEN local.length 
    ELSE live.length 
  END,
  
  live.width = CASE 
    WHEN live.width IS NULL OR live.width = '' OR live.width = '0.00'
    THEN local.width 
    ELSE live.width 
  END,
  
  live.height = CASE 
    WHEN live.height IS NULL OR live.height = '' OR live.height = '0.00'
    THEN local.height 
    ELSE live.height 
  END,
  
  -- Update brand_id if missing
  live.brand_id = CASE 
    WHEN live.brand_id IS NULL OR live.brand_id = 0 
    THEN local.brand_id 
    ELSE live.brand_id 
  END,
  
  -- Update board_id if missing
  live.board_id = CASE 
    WHEN live.board_id IS NULL OR live.board_id = '' 
    THEN local.board_id 
    ELSE live.board_id 
  END,
  
  -- Update grade_id if missing
  live.grade_id = CASE 
    WHEN live.grade_id IS NULL OR live.grade_id = 0 
    THEN local.grade_id 
    ELSE live.grade_id 
  END,
  
  -- Update subject_id if missing
  live.subject_id = CASE 
    WHEN live.subject_id IS NULL OR live.subject_id = 0 
    THEN local.subject_id 
    ELSE live.subject_id 
  END,
  
  -- Update HSN if missing
  live.hsn = CASE 
    WHEN live.hsn IS NULL OR live.hsn = 0 
    THEN local.hsn 
    ELSE live.hsn 
  END,
  
  -- Update SKU if missing
  live.sku = CASE 
    WHEN live.sku IS NULL OR live.sku = '' 
    THEN local.sku 
    ELSE live.sku 
  END,
  
  -- Update updated_at timestamp
  live.updated_at = NOW()

WHERE 
  live.is_deleted = 0 
  AND local.is_deleted = 0
  AND (
    -- Only update if there's actually something to update
    (live.slug IS NULL OR live.slug = '' OR live.slug LIKE 'tb-%')
    OR (live.description IS NULL OR live.description = '')
    OR (live.meta_title IS NULL OR live.meta_title = '')
    OR (live.meta_keyword IS NULL OR live.meta_keyword = '')
    OR (live.meta_description IS NULL OR live.meta_description = '')
    OR (live.pointers IS NULL OR live.pointers = '' OR live.pointers = '[]')
    OR (live.binding_type IS NULL OR live.binding_type = '')
    OR (live.no_of_pages IS NULL OR live.no_of_pages = 0)
    OR (live.weight IS NULL OR live.weight = '' OR live.weight = '0.00')
    OR (live.brand_id IS NULL OR live.brand_id = 0)
    OR (live.board_id IS NULL OR live.board_id = '')
    OR (live.grade_id IS NULL OR live.grade_id = 0)
    OR (live.subject_id IS NULL OR live.subject_id = 0)
    OR (live.hsn IS NULL OR live.hsn = 0)
    OR (live.sku IS NULL OR live.sku = '')
  );

-- ============================================================================
-- Report: Show products that will be/were updated
-- Run this BEFORE the UPDATE to see what will change
-- Or run AFTER to verify changes
-- ============================================================================

SELECT 
  live.id AS live_id,
  live.product_name,
  live.isbn,
  CASE 
    WHEN live.slug IS NULL OR live.slug = '' OR live.slug LIKE 'tb-%' 
    THEN CONCAT('❌ ', COALESCE(live.slug, 'NULL'), ' → ✓ ', local.slug)
    ELSE '✓ OK'
  END AS slug_status,
  CASE 
    WHEN live.description IS NULL OR live.description = '' 
    THEN '❌ Missing → ✓ Will Add'
    ELSE '✓ OK'
  END AS description_status,
  CASE 
    WHEN live.meta_title IS NULL OR live.meta_title = '' 
    THEN '❌ Missing → ✓ Will Add'
    ELSE '✓ OK'
  END AS meta_title_status,
  CASE 
    WHEN live.pointers IS NULL OR live.pointers = '' OR live.pointers = '[]'
    THEN '❌ Missing → ✓ Will Add'
    ELSE '✓ OK'
  END AS pointers_status
FROM erp_client_kirtibookin.erp_products AS live
INNER JOIN erp_client_kirtibookin_local.erp_products AS local
  ON (
    (live.isbn = local.isbn AND live.isbn IS NOT NULL AND live.isbn != '')
    OR
    (live.product_name = local.product_name AND (live.isbn IS NULL OR live.isbn = ''))
  )
WHERE 
  live.is_deleted = 0 
  AND local.is_deleted = 0
  AND (
    (live.slug IS NULL OR live.slug = '' OR live.slug LIKE 'tb-%')
    OR (live.description IS NULL OR live.description = '')
    OR (live.meta_title IS NULL OR live.meta_title = '')
    OR (live.meta_keyword IS NULL OR live.meta_keyword = '')
    OR (live.meta_description IS NULL OR live.meta_description = '')
    OR (live.pointers IS NULL OR live.pointers = '' OR live.pointers = '[]')
    OR (live.binding_type IS NULL OR live.binding_type = '')
    OR (live.no_of_pages IS NULL OR live.no_of_pages = 0)
    OR (live.weight IS NULL OR live.weight = '' OR live.weight = '0.00')
    
    OR (live.hsn IS NULL OR live.hsn = 0)
    OR (live.sku IS NULL OR live.sku = '')
  )
ORDER BY live.id;

-- ============================================================================
-- Count summary
-- ============================================================================

SELECT 
  COUNT(*) AS total_products_to_update,
  SUM(CASE WHEN live.slug IS NULL OR live.slug = '' OR live.slug LIKE 'tb-%' THEN 1 ELSE 0 END) AS slug_updates,
  SUM(CASE WHEN live.description IS NULL OR live.description = '' THEN 1 ELSE 0 END) AS description_updates,
  SUM(CASE WHEN live.meta_title IS NULL OR live.meta_title = '' THEN 1 ELSE 0 END) AS meta_title_updates,
  SUM(CASE WHEN live.pointers IS NULL OR live.pointers = '' OR live.pointers = '[]' THEN 1 ELSE 0 END) AS pointers_updates
FROM erp_client_kirtibookin.erp_products AS live
INNER JOIN erp_client_kirtibookin_local.erp_products AS local
  ON (
    (live.isbn = local.isbn AND live.isbn IS NOT NULL AND live.isbn != '')
    OR
    (live.product_name = local.product_name AND (live.isbn IS NULL OR live.isbn = ''))
  )
WHERE 
  live.is_deleted = 0 
  AND local.is_deleted = 0
  AND (
    (live.slug IS NULL OR live.slug = '' OR live.slug LIKE 'tb-%')
    OR (live.description IS NULL OR live.description = '')
    OR (live.meta_title IS NULL OR live.meta_title = '')
    OR (live.meta_keyword IS NULL OR live.meta_keyword = '')
    OR (live.meta_description IS NULL OR live.meta_description = '')
    OR (live.pointers IS NULL OR live.pointers = '' OR live.pointers = '[]')
    OR (live.binding_type IS NULL OR live.binding_type = '')
    OR (live.no_of_pages IS NULL OR live.no_of_pages = 0)
    OR (live.weight IS NULL OR live.weight = '' OR live.weight = '0.00')
    OR (live.hsn IS NULL OR live.hsn = 0)
    OR (live.sku IS NULL OR live.sku = '')
  );
