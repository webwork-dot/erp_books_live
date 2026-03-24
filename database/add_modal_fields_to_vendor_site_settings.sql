-- Add modal styling fields to vendor_site_settings table
-- Run this SQL to add the new modal styling fields

ALTER TABLE `vendor_site_settings`
ADD COLUMN `modal_bg_gradient_start` varchar(7) DEFAULT '#116B31' COMMENT 'Modal background gradient start color' AFTER `button_secondary_text`,
ADD COLUMN `modal_bg_gradient_end` varchar(7) DEFAULT '#28a745' COMMENT 'Modal background gradient end color' AFTER `modal_bg_gradient_start`,
ADD COLUMN `modal_button_bg` varchar(7) DEFAULT '#ffffff' COMMENT 'Modal button background color' AFTER `modal_bg_gradient_end`,
ADD COLUMN `modal_button_text` varchar(7) DEFAULT '#116B31' COMMENT 'Modal button text color' AFTER `modal_button_bg`,
ADD COLUMN `since_text` varchar(255) DEFAULT 'SINCE 1952' COMMENT 'Since text displayed in modal' AFTER `modal_button_text`;
