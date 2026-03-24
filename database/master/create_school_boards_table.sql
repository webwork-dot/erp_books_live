-- =====================================================
-- School Boards Table for Vendor ERP System
-- =====================================================

-- Table: erp_school_boards
-- Stores school boards that vendors can add
CREATE TABLE IF NOT EXISTS `erp_school_boards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Vendor who created this board (NULL = system board available to all)',
  `board_name` varchar(100) NOT NULL COMMENT 'Board Name (e.g., CBSE, ICSE, etc.)',
  `description` text DEFAULT NULL COMMENT 'Board Description',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vendor_id` (`vendor_id`),
  KEY `idx_status` (`status`),
  KEY `idx_board_name` (`board_name`),
  CONSTRAINT `fk_boards_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='School boards managed by vendors';

-- Insert default boards for all vendors (these are common boards)
-- vendor_id = NULL means system/default boards available to all vendors
INSERT IGNORE INTO `erp_school_boards` (`vendor_id`, `board_name`, `description`, `status`) VALUES
(NULL, 'CBSE', 'Central Board of Secondary Education', 'active'),
(NULL, 'ICSE', 'Indian Certificate of Secondary Education', 'active'),
(NULL, 'State Board', 'State Education Board', 'active'),
(NULL, 'IGCSE', 'International General Certificate of Secondary Education', 'active'),
(NULL, 'IB', 'International Baccalaureate', 'active');

-- Note: vendor_id = NULL means it's a default/system board available to all vendors
-- Vendors can add their own custom boards with their vendor_id

