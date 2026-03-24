-- Add Email (Zepto Mail) and Firebase Configuration Fields to erp_clients table
-- This migration adds fields for Zepto Mail email service and Firebase integration

ALTER TABLE `erp_clients` 
ADD COLUMN `zepto_mail_api_key` VARCHAR(255) DEFAULT NULL COMMENT 'Zepto Mail API Key' AFTER `ccavenue_working_key`,
ADD COLUMN `zepto_mail_from_email` VARCHAR(255) DEFAULT NULL COMMENT 'Zepto Mail From Email Address' AFTER `zepto_mail_api_key`,
ADD COLUMN `zepto_mail_from_name` VARCHAR(255) DEFAULT NULL COMMENT 'Zepto Mail From Name' AFTER `zepto_mail_from_email`,
ADD COLUMN `firebase_api_key` VARCHAR(255) DEFAULT NULL COMMENT 'Firebase API Key' AFTER `zepto_mail_from_name`,
ADD COLUMN `firebase_auth_domain` VARCHAR(255) DEFAULT NULL COMMENT 'Firebase Auth Domain' AFTER `firebase_api_key`,
ADD COLUMN `firebase_project_id` VARCHAR(255) DEFAULT NULL COMMENT 'Firebase Project ID' AFTER `firebase_auth_domain`,
ADD COLUMN `firebase_storage_bucket` VARCHAR(255) DEFAULT NULL COMMENT 'Firebase Storage Bucket' AFTER `firebase_project_id`,
ADD COLUMN `firebase_messaging_sender_id` VARCHAR(255) DEFAULT NULL COMMENT 'Firebase Messaging Sender ID' AFTER `firebase_storage_bucket`,
ADD COLUMN `firebase_app_id` VARCHAR(255) DEFAULT NULL COMMENT 'Firebase App ID' AFTER `firebase_messaging_sender_id`;

