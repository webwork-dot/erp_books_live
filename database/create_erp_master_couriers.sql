-- Create erp_master_courier table for vendor courier management
-- Run this in each vendor/tenant database (not master)
-- Table name must match: erp_master_courier (model uses this)

CREATE TABLE IF NOT EXISTS erp_master_courier (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT UNSIGNED NOT NULL,
    courier_name VARCHAR(150) NOT NULL,
    tracking_link VARCHAR(255) DEFAULT NULL,
    status TINYINT(1) DEFAULT 1 COMMENT '1=active, 0=inactive',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_vendor_id (vendor_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
