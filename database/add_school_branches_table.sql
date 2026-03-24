-- SQL to create erp_school_branches table
-- Run this SQL to add the branches functionality

--
-- Table structure for table `erp_school_branches`
--
CREATE TABLE `erp_school_branches` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL COMMENT 'School ID from erp_schools table',
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Vendor who created this branch',
  `branch_name` varchar(255) NOT NULL COMMENT 'Branch Name',
  `address` text NOT NULL COMMENT 'Branch Address',
  `country_id` int(11) NOT NULL DEFAULT 101 COMMENT 'Country ID (default: 101 = India)',
  `state_id` mediumint(9) NOT NULL COMMENT 'State ID from states table',
  `city_id` mediumint(9) NOT NULL COMMENT 'City ID from cities table',
  `pincode` varchar(10) NOT NULL COMMENT 'Pincode',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='School branches managed by vendors';

--
-- Indexes for table `erp_school_branches`
--
ALTER TABLE `erp_school_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_state_id` (`state_id`),
  ADD KEY `idx_city_id` (`city_id`),
  ADD KEY `idx_status` (`status`);

--
-- AUTO_INCREMENT for table `erp_school_branches`
--
ALTER TABLE `erp_school_branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `erp_school_branches`
--
ALTER TABLE `erp_school_branches`
  ADD CONSTRAINT `fk_school_branches_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_school_branches_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_school_branches_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `fk_school_branches_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `fk_school_branches_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

