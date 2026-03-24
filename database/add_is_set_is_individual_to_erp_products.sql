-- Add is_set and is_individual fields to erp_products table
-- These fields mirror the functionality from legacy tables (erp_textbooks, erp_notebooks, erp_stationery, erp_uniforms)

ALTER TABLE `erp_products` 
ADD COLUMN `is_set` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Set Product' AFTER `min_quantity`,
ADD COLUMN `is_individual` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Is Individual Product' AFTER `is_set`;

-- Add indexes for better performance
ALTER TABLE `erp_products`
ADD INDEX `idx_is_set` (`is_set`),
ADD INDEX `idx_is_individual` (`is_individual`);

-- Update existing records to sync values from legacy tables
UPDATE erp_products p
INNER JOIN erp_textbooks t ON p.legacy_table = 'erp_textbooks' AND p.legacy_id = t.id
SET p.is_set = t.is_set, p.is_individual = t.is_individual;

UPDATE erp_products p
INNER JOIN erp_notebooks n ON p.legacy_table = 'erp_notebooks' AND p.legacy_id = n.id
SET p.is_set = n.is_set, p.is_individual = n.is_individual;

UPDATE erp_products p
INNER JOIN erp_stationery s ON p.legacy_table = 'erp_stationery' AND p.legacy_id = s.id
SET p.is_set = s.is_set, p.is_individual = s.is_individual;

UPDATE erp_products p
INNER JOIN erp_uniforms u ON p.legacy_table = 'erp_uniforms' AND p.legacy_id = u.id
SET p.is_set = u.is_set, p.is_individual = u.is_individual;

-- For individual products table (if it exists)
UPDATE erp_products p
INNER JOIN erp_individual_products ip ON p.legacy_table = 'erp_individual_products' AND p.legacy_id = ip.id
SET p.is_set = ip.is_set, p.is_individual = ip.is_individual;

-- Create triggers to automatically sync changes from legacy tables to erp_products
DELIMITER $$

-- Trigger for textbook updates
CREATE TRIGGER IF NOT EXISTS after_textbook_update
AFTER UPDATE ON erp_textbooks
FOR EACH ROW
BEGIN
    UPDATE erp_products 
    SET is_set = NEW.is_set,
        is_individual = NEW.is_individual
    WHERE legacy_table = 'erp_textbooks' 
    AND legacy_id = NEW.id;
END$$

-- Trigger for notebook updates
CREATE TRIGGER IF NOT EXISTS after_notebook_update
AFTER UPDATE ON erp_notebooks
FOR EACH ROW
BEGIN
    UPDATE erp_products 
    SET is_set = NEW.is_set,
        is_individual = NEW.is_individual
    WHERE legacy_table = 'erp_notebooks' 
    AND legacy_id = NEW.id;
END$$

-- Trigger for stationery updates
CREATE TRIGGER IF NOT EXISTS after_stationery_update
AFTER UPDATE ON erp_stationery
FOR EACH ROW
BEGIN
    UPDATE erp_products 
    SET is_set = NEW.is_set,
        is_individual = NEW.is_individual
    WHERE legacy_table = 'erp_stationery' 
    AND legacy_id = NEW.id;
END$$

-- Trigger for uniform updates
CREATE TRIGGER IF NOT EXISTS after_uniform_update
AFTER UPDATE ON erp_uniforms
FOR EACH ROW
BEGIN
    UPDATE erp_products 
    SET is_set = NEW.is_set,
        is_individual = NEW.is_individual
    WHERE legacy_table = 'erp_uniforms' 
    AND legacy_id = NEW.id;
END$$

-- Trigger for individual product updates
CREATE TRIGGER IF NOT EXISTS after_individual_product_update
AFTER UPDATE ON erp_individual_products
FOR EACH ROW
BEGIN
    UPDATE erp_products 
    SET is_set = NEW.is_set,
        is_individual = NEW.is_individual
    WHERE legacy_table = 'erp_individual_products' 
    AND legacy_id = NEW.id;
END$$

DELIMITER ;