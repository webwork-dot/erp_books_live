-- Create temp_user table for login/registration process
CREATE TABLE IF NOT EXISTS `temp_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_phone` varchar(15) NOT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_phone` (`user_phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add any missing columns to users table if needed
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `phone_number` varchar(15) DEFAULT NULL AFTER `email`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `otp` varchar(10) DEFAULT NULL AFTER `phone_number`;
ALTER TABLE `users` ADD COLUMN IF NOT EXISTS `status` tinyint(1) DEFAULT 1 AFTER `otp`;

