-- Set is_individual=1 for textbook and notebook products in erp_products
-- Run this on vendor database if /books page shows no products
-- This syncs from legacy tables (erp_textbooks, erp_notebooks) where is_individual=1
-- Or sets is_individual=1 for all textbook/notebook products if you want them all on /books page

-- Option A: Sync from legacy tables (preferred - respects source table flags)
UPDATE erp_products p
INNER JOIN erp_textbooks t ON p.legacy_table = 'erp_textbooks' AND p.legacy_id = t.id
SET p.is_individual = t.is_individual;

UPDATE erp_products p
INNER JOIN erp_notebooks n ON p.legacy_table = 'erp_notebooks' AND p.legacy_id = n.id
SET p.is_individual = n.is_individual;

-- Option B: Force all textbook/notebook to individual (uncomment if Option A leaves products with is_individual=0)
-- UPDATE erp_products SET is_individual = 1 WHERE type IN ('textbook','notebook') AND status = 1 AND is_deleted = 0;
