-- =====================================================
-- SQL Changes to Support Multiple Packages in a Bookset
-- and Multiple Products in a Package
-- =====================================================

-- Step 1: Create erp_booksets table to group multiple packages
-- =====================================================
CREATE TABLE IF NOT EXISTS `erp_booksets` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `school_id` int(11) NOT NULL COMMENT 'Foreign key to erp_schools table',
  `board_id` int(11) NOT NULL COMMENT 'Foreign key to erp_school_boards table',
  `grade_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_grades table',
  `bookset_name` varchar(255) DEFAULT NULL COMMENT 'Bookset Name (optional)',
  `has_products` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Bookset with products, 0 = Bookset without products',
  `mandatory_packages` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of mandatory packages required',
  `total_packages` int(11) NOT NULL DEFAULT 0 COMMENT 'Total number of packages in this bookset',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_school_id` (`school_id`),
  KEY `idx_board_id` (`board_id`),
  KEY `idx_grade_id` (`grade_id`),
  KEY `idx_status` (`status`),
  KEY `idx_has_products` (`has_products`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Booksets - Groups of Packages';

-- Step 2: Add bookset_id column to erp_bookset_packages table
-- =====================================================
ALTER TABLE `erp_bookset_packages` 
ADD COLUMN `bookset_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Foreign key to erp_booksets table' AFTER `vendor_id`,
ADD KEY `idx_bookset_id` (`bookset_id`);

-- Step 3: Add foreign key constraint (optional, for referential integrity)
-- =====================================================
-- Uncomment the following line if you want to enforce referential integrity
-- ALTER TABLE `erp_bookset_packages` 
-- ADD CONSTRAINT `fk_bookset_packages_bookset` 
-- FOREIGN KEY (`bookset_id`) REFERENCES `erp_booksets` (`id`) 
-- ON DELETE SET NULL ON UPDATE CASCADE;

-- Step 4: The erp_bookset_package_products table already supports multiple products per package
-- No changes needed - it already has package_id as foreign key
-- =====================================================

-- Step 5: Update existing packages to have NULL bookset_id (they can be standalone)
-- =====================================================
-- This is already handled by DEFAULT NULL, but you can explicitly set it:
-- UPDATE `erp_bookset_packages` SET `bookset_id` = NULL WHERE `bookset_id` IS NULL;

-- =====================================================
-- Summary of Changes:
-- =====================================================
-- 1. Created erp_booksets table to group multiple packages together
--    - Includes has_products field (1 = with products, 0 = without products)
--    - Stores bookset-level information (school, board, grade, mandatory_packages, etc.)
-- 2. Added bookset_id to erp_bookset_packages to link packages to booksets
-- 3. erp_bookset_package_products already supports multiple products per package
-- 
-- Structure:
-- erp_booksets (1) -> (many) erp_bookset_packages
-- erp_bookset_packages (1) -> (many) erp_bookset_package_products
-- 
-- This allows:
-- - Multiple packages in a bookset
-- - Multiple products in each package
-- - Booksets can be marked as having products (has_products = 1) or not (has_products = 0)
-- =====================================================

