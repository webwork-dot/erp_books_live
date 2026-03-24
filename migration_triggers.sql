DELIMITER $$
CREATE TRIGGER `after_individual_product_image_insert` AFTER INSERT ON `erp_individual_product_images` FOR EACH ROW BEGIN
    DECLARE product_id_val INT;
    DECLARE vendor_id_val INT;
    
    SELECT p.id, p.vendor_id INTO product_id_val, vendor_id_val 
    FROM erp_products p
    WHERE p.legacy_table = 'erp_individual_products' 
    AND p.legacy_id = NEW.product_id 
    LIMIT 1;
    
    IF product_id_val IS NOT NULL AND vendor_id_val IS NOT NULL THEN
        INSERT INTO erp_product_images 
        (product_id, image_path, image_order, is_main, legacy_table, legacy_id, vendor_id)
        VALUES 
        (product_id_val, NEW.image_path, NEW.image_order, NEW.is_main, 'erp_individual_product_images', NEW.id, vendor_id_val);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_individual_product_image_update` AFTER UPDATE ON `erp_individual_product_images` FOR EACH ROW BEGIN
    UPDATE erp_product_images 
    SET image_order = NEW.image_order,
        is_main = NEW.is_main,
        image_path = NEW.image_path
    WHERE legacy_table = 'erp_individual_product_images' 
    AND legacy_id = NEW.id;
END
$$
DELIMITER ;


--
-- Triggers `erp_notebook_images`
--
DELIMITER $$
CREATE TRIGGER `after_notebook_image_insert` AFTER INSERT ON `erp_notebook_images` FOR EACH ROW BEGIN
    DECLARE product_id_val INT;
    DECLARE vendor_id_val INT;
    
    SELECT p.id, p.vendor_id INTO product_id_val, vendor_id_val 
    FROM erp_products p
    WHERE p.legacy_table = 'erp_notebooks' 
    AND p.legacy_id = NEW.notebook_id 
    LIMIT 1;
    
    IF product_id_val IS NOT NULL AND vendor_id_val IS NOT NULL THEN
        INSERT INTO erp_product_images 
        (product_id, image_path, image_order, is_main, legacy_table, legacy_id, vendor_id)
        VALUES 
        (product_id_val, NEW.image_path, NEW.image_order, NEW.is_main, 'erp_notebook_images', NEW.id, vendor_id_val);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_notebook_image_update` AFTER UPDATE ON `erp_notebook_images` FOR EACH ROW BEGIN
    UPDATE erp_product_images 
    SET image_order = NEW.image_order,
        is_main = NEW.is_main,
        image_path = NEW.image_path
    WHERE legacy_table = 'erp_notebook_images' 
    AND legacy_id = NEW.id;
END
$$
DELIMITER ;

--
-- Triggers `erp_stationery_images`
--
DELIMITER $$
CREATE TRIGGER `after_stationery_image_insert` AFTER INSERT ON `erp_stationery_images` FOR EACH ROW BEGIN
    DECLARE product_id_val INT;
    DECLARE vendor_id_val INT;
    
    SELECT p.id, p.vendor_id INTO product_id_val, vendor_id_val 
    FROM erp_products p
    WHERE p.legacy_table = 'erp_stationery' 
    AND p.legacy_id = NEW.stationery_id 
    LIMIT 1;
    
    IF product_id_val IS NOT NULL AND vendor_id_val IS NOT NULL THEN
        INSERT INTO erp_product_images 
        (product_id, image_path, image_order, is_main, legacy_table, legacy_id, vendor_id)
        VALUES 
        (product_id_val, NEW.image_path, NEW.image_order, NEW.is_main, 'erp_stationery_images', NEW.id, vendor_id_val);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_stationery_image_update` AFTER UPDATE ON `erp_stationery_images` FOR EACH ROW BEGIN
    UPDATE erp_product_images 
    SET image_order = NEW.image_order,
        is_main = NEW.is_main,
        image_path = NEW.image_path
    WHERE legacy_table = 'erp_stationery_images' 
    AND legacy_id = NEW.id;
END
$$
DELIMITER ;

--
-- Triggers `erp_textbook_images`
--
DELIMITER $$
CREATE TRIGGER `after_textbook_image_insert` AFTER INSERT ON `erp_textbook_images` FOR EACH ROW BEGIN
    DECLARE product_id_val INT;
    DECLARE vendor_id_val INT;
    
    -- Get the product_id from erp_products for this textbook
    SELECT p.id, p.vendor_id INTO product_id_val, vendor_id_val 
    FROM erp_products p
    WHERE p.legacy_table = 'erp_textbooks' 
    AND p.legacy_id = NEW.textbook_id 
    LIMIT 1;
    
    IF product_id_val IS NOT NULL AND vendor_id_val IS NOT NULL THEN
        INSERT INTO erp_product_images 
        (product_id, image_path, image_order, is_main, legacy_table, legacy_id, vendor_id)
        VALUES 
        (product_id_val, NEW.image_path, NEW.image_order, NEW.is_main, 'erp_textbook_images', NEW.id, vendor_id_val);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_textbook_image_update` AFTER UPDATE ON `erp_textbook_images` FOR EACH ROW BEGIN
    UPDATE erp_product_images 
    SET image_order = NEW.image_order,
        is_main = NEW.is_main,
        image_path = NEW.image_path
    WHERE legacy_table = 'erp_textbook_images' 
    AND legacy_id = NEW.id;
END
$$
DELIMITER ;


--
-- Triggers `erp_uniform_images`
--
DELIMITER $$
CREATE TRIGGER `after_uniform_image_insert` AFTER INSERT ON `erp_uniform_images` FOR EACH ROW BEGIN
    DECLARE product_id_val INT;
    DECLARE vendor_id_val INT;
    
    SELECT p.id, p.vendor_id INTO product_id_val, vendor_id_val 
    FROM erp_products p
    WHERE p.legacy_table = 'erp_uniforms' 
    AND p.legacy_id = NEW.uniform_id 
    LIMIT 1;
    
    IF product_id_val IS NOT NULL AND vendor_id_val IS NOT NULL THEN
        INSERT INTO erp_product_images 
        (product_id, image_path, image_order, is_main, legacy_table, legacy_id, vendor_id)
        VALUES 
        (product_id_val, NEW.image_path, NEW.image_order, NEW.is_main, 'erp_uniform_images', NEW.id, vendor_id_val);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_uniform_image_update` AFTER UPDATE ON `erp_uniform_images` FOR EACH ROW BEGIN
    UPDATE erp_product_images 
    SET image_order = NEW.image_order,
        is_main = NEW.is_main,
        image_path = NEW.image_path
    WHERE legacy_table = 'erp_uniform_images' 
    AND legacy_id = NEW.id;
END
$$
DELIMITER ;