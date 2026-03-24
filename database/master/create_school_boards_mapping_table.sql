-- =====================================================
-- School Boards Mapping Table (Many-to-Many)
-- =====================================================

-- Table: erp_school_boards_mapping
-- Maps schools to multiple boards (many-to-many relationship)
CREATE TABLE IF NOT EXISTS `erp_school_boards_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) NOT NULL COMMENT 'School ID',
  `board_id` int(11) NOT NULL COMMENT 'Board ID from erp_school_boards',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_school_board` (`school_id`, `board_id`),
  KEY `idx_school_id` (`school_id`),
  KEY `idx_board_id` (`board_id`),
  CONSTRAINT `fk_school_boards_mapping_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_school_boards_mapping_board` FOREIGN KEY (`board_id`) REFERENCES `erp_school_boards` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Mapping table for schools and boards (many-to-many)';

