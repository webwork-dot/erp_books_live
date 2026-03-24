-- SQL to add sub-categories support to features system
-- Run this SQL to add sub-category functionality

--
-- Additional column for erp_features table (Parent ID for sub-categories)
--
ALTER TABLE `erp_features`
  ADD COLUMN `parent_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Parent feature ID (NULL for main category, feature_id for sub-category)' AFTER `id`,
  ADD KEY `idx_parent_id` (`parent_id`);

--
-- Add foreign key constraint for parent_id
--
ALTER TABLE `erp_features`
  ADD CONSTRAINT `fk_features_parent` FOREIGN KEY (`parent_id`) REFERENCES `erp_features` (`id`) ON DELETE CASCADE;

--
-- Table structure for table `erp_client_feature_subcategories`
--
CREATE TABLE `erp_client_feature_subcategories` (
  `id` int(11) UNSIGNED NOT NULL,
  `client_id` int(11) UNSIGNED NOT NULL COMMENT 'Client/Vendor ID',
  `feature_id` int(11) UNSIGNED NOT NULL COMMENT 'Main Feature ID (parent category)',
  `subcategory_id` int(11) UNSIGNED NOT NULL COMMENT 'Sub-category Feature ID',
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Is sub-category enabled for this client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Sub-category assignments to clients/vendors';

--
-- Indexes for table `erp_client_feature_subcategories`
--
ALTER TABLE `erp_client_feature_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_client_feature_subcategory` (`client_id`, `feature_id`, `subcategory_id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_feature_id` (`feature_id`),
  ADD KEY `idx_subcategory_id` (`subcategory_id`);

--
-- AUTO_INCREMENT for table `erp_client_feature_subcategories`
--
ALTER TABLE `erp_client_feature_subcategories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `erp_client_feature_subcategories`
--
ALTER TABLE `erp_client_feature_subcategories`
  ADD CONSTRAINT `fk_client_feature_subcategories_client` FOREIGN KEY (`client_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_client_feature_subcategories_feature` FOREIGN KEY (`feature_id`) REFERENCES `erp_features` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_client_feature_subcategories_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `erp_features` (`id`) ON DELETE CASCADE;

