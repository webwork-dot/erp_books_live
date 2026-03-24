-- Create banners table for storing multiple banner records per vendor (without foreign key constraint)
CREATE TABLE banners (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  vendor_id INT(11) UNSIGNED NOT NULL,
  banner_image VARCHAR(500) NOT NULL,
  alt_text VARCHAR(255) DEFAULT NULL,
  caption VARCHAR(500) DEFAULT NULL,
  is_active TINYINT(1) DEFAULT 1,
  sort_order INT(11) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_vendor_id (vendor_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add the foreign key constraint separately if the erp_clients table exists in the same database
-- ALTER TABLE banners 
-- ADD CONSTRAINT fk_banners_vendor
--   FOREIGN KEY (vendor_id)
--   REFERENCES erp_clients(id)
--   ON DELETE CASCADE;