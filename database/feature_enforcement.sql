-- =============================================
-- Database-Level Feature Enforcement
-- Stored procedures, views, and triggers for feature-based access control
-- =============================================

-- Stored Procedure: check_feature_enabled
-- Description: Check if a feature is enabled for this vendor
DELIMITER $$
DROP PROCEDURE IF EXISTS `check_feature_enabled`$$
CREATE PROCEDURE `check_feature_enabled`(IN p_feature_slug VARCHAR(255))
BEGIN
    SELECT is_enabled FROM vendor_features 
    WHERE feature_slug = p_feature_slug AND is_enabled = 1
    LIMIT 1;
END$$
DELIMITER ;

-- Stored Procedure: check_subcategory_enabled
-- Description: Check if a subcategory is enabled for a feature
DELIMITER $$
DROP PROCEDURE IF EXISTS `check_subcategory_enabled`$$
CREATE PROCEDURE `check_subcategory_enabled`(
    IN p_feature_id INT(11) UNSIGNED,
    IN p_subcategory_slug VARCHAR(255)
)
BEGIN
    SELECT is_enabled FROM vendor_feature_subcategories 
    WHERE feature_id = p_feature_id 
    AND subcategory_slug = p_subcategory_slug 
    AND is_enabled = 1
    LIMIT 1;
END$$
DELIMITER ;

-- Function: is_feature_enabled
-- Description: Returns 1 if feature is enabled, 0 otherwise
DELIMITER $$
DROP FUNCTION IF EXISTS `is_feature_enabled`$$
CREATE FUNCTION `is_feature_enabled`(p_feature_slug VARCHAR(255))
RETURNS TINYINT(1)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_enabled TINYINT(1) DEFAULT 0;
    SELECT is_enabled INTO v_enabled 
    FROM vendor_features 
    WHERE feature_slug = p_feature_slug AND is_enabled = 1
    LIMIT 1;
    RETURN IFNULL(v_enabled, 0);
END$$
DELIMITER ;










