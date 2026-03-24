-- =====================================================
-- Insert Dummy Schools for Testing
-- =====================================================
-- 
-- IMPORTANT: Before running this script, ensure you have run these SQL files FIRST:
-- 1. create_schools_tables.sql (creates erp_schools and erp_school_images tables)
-- 2. create_school_boards_table.sql (creates erp_school_boards table)
-- 3. create_school_boards_mapping_table.sql (creates erp_school_boards_mapping table) ⚠️ REQUIRED!
-- 4. States and cities data imported from varitty_varitdbc.sql
--
-- STEP 1: Create the mapping table (REQUIRED!)
-- Run this first: erp-system/database/master/create_school_boards_mapping_table.sql
-- Or manually run:
-- CREATE TABLE IF NOT EXISTS `erp_school_boards_mapping` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `school_id` int(11) NOT NULL,
--   `board_id` int(11) NOT NULL,
--   `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `unique_school_board` (`school_id`, `board_id`),
--   KEY `idx_school_id` (`school_id`),
--   KEY `idx_board_id` (`board_id`),
--   CONSTRAINT `fk_school_boards_mapping_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE,
--   CONSTRAINT `fk_school_boards_mapping_board` FOREIGN KEY (`board_id`) REFERENCES `erp_school_boards` (`id`) ON DELETE CASCADE
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- STEP 2: Verify states and cities exist
-- SELECT COUNT(*) FROM states; -- Should return > 0
-- SELECT COUNT(*) FROM cities; -- Should return > 0
--
-- STEP 3: Find your vendor ID
-- SELECT id, name, domain FROM erp_clients;
-- Replace vendor_id = 3 in the INSERT statements below with your actual vendor ID
--
-- All schools use: Maharashtra (1568) and Mumbai (17423)
-- All passwords: 123456
-- =====================================================

-- Example: Get vendor ID (replace 'ganesh-books' with your vendor domain)
-- SET @vendor_id = (SELECT id FROM erp_clients WHERE domain = 'ganesh-books' LIMIT 1);

-- Example: Get state and city IDs (adjust names as needed)
-- SET @maharashtra_state_id = (SELECT id FROM states WHERE name = 'Maharashtra' LIMIT 1);
-- SET @mumbai_city_id = (SELECT id FROM cities WHERE name = 'Mumbai' AND state_id = @maharashtra_state_id LIMIT 1);
-- SET @delhi_state_id = (SELECT id FROM states WHERE name = 'Delhi' LIMIT 1);
-- SET @delhi_city_id = (SELECT id FROM cities WHERE name = 'New Delhi' AND state_id = @delhi_state_id LIMIT 1);
-- SET @karnataka_state_id = (SELECT id FROM states WHERE name = 'Karnataka' LIMIT 1);
-- SET @bangalore_city_id = (SELECT id FROM cities WHERE name = 'Bangalore' AND state_id = @karnataka_state_id LIMIT 1);
-- SET @tamil_nadu_state_id = (SELECT id FROM states WHERE name = 'Tamil Nadu' LIMIT 1);
-- SET @chennai_city_id = (SELECT id FROM cities WHERE name = 'Chennai' AND state_id = @tamil_nadu_state_id LIMIT 1);
-- SET @gujarat_state_id = (SELECT id FROM states WHERE name = 'Gujarat' LIMIT 1);
-- SET @ahmedabad_city_id = (SELECT id FROM cities WHERE name = 'Ahmedabad' AND state_id = @gujarat_state_id LIMIT 1);

-- Get board IDs
-- SET @cbse_board_id = (SELECT id FROM erp_school_boards WHERE board_name = 'CBSE' LIMIT 1);
-- SET @icse_board_id = (SELECT id FROM erp_school_boards WHERE board_name = 'ICSE' LIMIT 1);
-- SET @state_board_id = (SELECT id FROM erp_school_boards WHERE board_name = 'State Board' LIMIT 1);

-- =====================================================
-- Insert 5 Dummy Schools (Using Dynamic Subqueries)
-- =====================================================
-- This version uses subqueries to find state and city IDs automatically
-- Make sure to replace vendor_id = 3 with your actual vendor ID
--
-- IMPORTANT: If you get NULL errors, check if states/cities exist:
-- SELECT id, name FROM states WHERE name IN ('Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu', 'Gujarat');
-- SELECT id, name, state_id FROM cities WHERE name LIKE '%Mumbai%' OR name LIKE '%Delhi%' OR name LIKE '%Bangalore%' OR name LIKE '%Chennai%' OR name LIKE '%Ahmedabad%';

INSERT INTO `erp_schools` (
    `vendor_id`,
    `school_name`,
    `school_board`,
    `total_strength`,
    `school_description`,
    `affiliation_no`,
    `address`,
    `country_id`,
    `state_id`,
    `city_id`,
    `pincode`,
    `admin_name`,
    `admin_phone`,
    `admin_email`,
    `admin_password`,
    `status`
) VALUES
-- School 1: Delhi Public School, Mumbai
(
    3, -- Replace with your vendor_id (SELECT id FROM erp_clients WHERE domain = 'ganesh-books')
    'Delhi Public School',
    'CBSE',
    2500,
    'A premier educational institution offering quality education with modern facilities and experienced faculty.',
    'CBSE/123456/2020',
    '123 Education Street, Andheri West',
    101, -- India
    1568, -- Maharashtra
    17423, -- Mumbai
    '400053',
    'Rajesh Kumar',
    '9876543210',
    'admin@dpsmumbai.edu.in',
    SHA1('123456'), -- Password: 123456
    'active'
),

-- School 2: St. Mary's Convent School, Mumbai
(
    3, -- Replace with your vendor_id
    'St. Mary\'s Convent School',
    'ICSE',
    1800,
    'A well-established convent school providing holistic education with emphasis on moral values and academic excellence.',
    'ICSE/789012/2018',
    '456 School Road, Bandra West',
    101, -- India
    1568, -- Maharashtra
    17423, -- Mumbai
    '400050',
    'Sister Mary Joseph',
    '9876543211',
    'admin@stmarysmumbai.edu.in',
    SHA1('123456'), -- Password: 123456
    'active'
),

-- School 3: Bangalore International School, Mumbai
(
    3, -- Replace with your vendor_id
    'Bangalore International School',
    'CBSE',
    3200,
    'An international standard school with state-of-the-art infrastructure and global curriculum.',
    'CBSE/345678/2019',
    '789 Knowledge Park, Powai',
    101, -- India
    1568, -- Maharashtra
    17423, -- Mumbai
    '400076',
    'Dr. Priya Sharma',
    '9876543212',
    'admin@bismumbai.edu.in',
    SHA1('123456'), -- Password: 123456
    'active'
),

-- School 4: Chennai Public School, Mumbai
(
    3, -- Replace with your vendor_id
    'Chennai Public School',
    'State Board',
    2200,
    'A leading educational institution in Mumbai offering quality education with focus on holistic development and modern learning.',
    'MH/901234/2021',
    '321 Learning Avenue, Vile Parle',
    101, -- India
    1568, -- Maharashtra
    17423, -- Mumbai
    '400056',
    'Ramesh Iyer',
    '9876543213',
    'admin@cpsmumbai.edu.in',
    SHA1('123456'), -- Password: 123456
    'active'
),

-- School 5: Ahmedabad High School, Mumbai
(
    3, -- Replace with your vendor_id
    'Ahmedabad High School',
    'CBSE',
    2800,
    'A progressive school committed to nurturing young minds with innovative teaching methods and comprehensive development programs.',
    'CBSE/567890/2022',
    '654 Education Lane, Goregaon',
    101, -- India
    1568, -- Maharashtra
    17423, -- Mumbai
    '400063',
    'Meera Patel',
    '9876543214',
    'admin@ahmedabadhighmumbai.edu.in',
    SHA1('123456'), -- Password: 123456
    'active'
);

-- =====================================================
-- Insert School-Board Mappings (Many-to-Many)
-- =====================================================
-- This will map boards to the schools inserted above
-- Run this AFTER inserting the schools

-- School 1 (DPS Mumbai) - CBSE and ICSE
INSERT INTO `erp_school_boards_mapping` (`school_id`, `board_id`)
SELECT s.id, b.id
FROM `erp_schools` s
CROSS JOIN `erp_school_boards` b
WHERE s.school_name = 'Delhi Public School'
  AND s.vendor_id = 3
  AND b.board_name IN ('CBSE', 'ICSE')
LIMIT 2;

-- School 2 (St. Mary's) - ICSE only
INSERT INTO `erp_school_boards_mapping` (`school_id`, `board_id`)
SELECT s.id, b.id
FROM `erp_schools` s
CROSS JOIN `erp_school_boards` b
WHERE s.school_name = 'St. Mary\'s Convent School'
  AND s.vendor_id = 3
  AND b.board_name = 'ICSE'
LIMIT 1;

-- School 3 (Bangalore International) - CBSE and IB
INSERT INTO `erp_school_boards_mapping` (`school_id`, `board_id`)
SELECT s.id, b.id
FROM `erp_schools` s
CROSS JOIN `erp_school_boards` b
WHERE s.school_name = 'Bangalore International School'
  AND s.vendor_id = 3
  AND b.board_name IN ('CBSE', 'IB')
LIMIT 2;

-- School 4 (Chennai Public) - State Board and CBSE
INSERT INTO `erp_school_boards_mapping` (`school_id`, `board_id`)
SELECT s.id, b.id
FROM `erp_schools` s
CROSS JOIN `erp_school_boards` b
WHERE s.school_name = 'Chennai Public School'
  AND s.vendor_id = 3
  AND b.board_name IN ('State Board', 'CBSE')
LIMIT 2;

-- School 5 (Ahmedabad High) - CBSE only
INSERT INTO `erp_school_boards_mapping` (`school_id`, `board_id`)
SELECT s.id, b.id
FROM `erp_schools` s
CROSS JOIN `erp_school_boards` b
WHERE s.school_name = 'Ahmedabad High School'
  AND s.vendor_id = 3
  AND b.board_name = 'CBSE'
LIMIT 1;

-- =====================================================
-- QUICK INSERT (Replace IDs with your actual values)
-- =====================================================
-- Step 1: Find your vendor ID
-- SELECT id, name, domain FROM erp_clients;

-- Step 2: Find state and city IDs (examples for common cities)
-- SELECT id, name FROM states WHERE name IN ('Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu', 'Gujarat');
-- SELECT id, name, state_id FROM cities WHERE name IN ('Mumbai', 'New Delhi', 'Bangalore', 'Chennai', 'Ahmedabad');

-- Step 3: Replace @VENDOR_ID, @STATE_ID, and @CITY_ID below and run

-- Example INSERT (uncomment and adjust IDs):
/*
INSERT INTO `erp_schools` (
    `vendor_id`, `school_name`, `school_board`, `total_strength`, `school_description`,
    `affiliation_no`, `address`, `country_id`, `state_id`, `city_id`, `pincode`,
    `admin_name`, `admin_phone`, `admin_email`, `admin_password`, `status`
) VALUES
(3, 'Delhi Public School', 'CBSE', 2500, 'Premier educational institution', 'CBSE/123456/2020', '123 Education Street', 101, 1, 1, '400053', 'Rajesh Kumar', '9876543210', 'admin@dpsmumbai.edu.in', SHA1('admin123'), 'active'),
(3, 'St. Mary\'s Convent School', 'ICSE', 1800, 'Well-established convent school', 'ICSE/789012/2018', '456 School Road', 101, 2, 2, '110001', 'Sister Mary Joseph', '9876543211', 'admin@stmarysdelhi.edu.in', SHA1('admin123'), 'active'),
(3, 'Bangalore International School', 'CBSE', 3200, 'International standard school', 'CBSE/345678/2019', '789 Knowledge Park', 101, 3, 3, '560066', 'Dr. Priya Sharma', '9876543212', 'admin@bisbangalore.edu.in', SHA1('admin123'), 'active'),
(3, 'Chennai Public School', 'State Board', 2200, 'Leading educational institution', 'TN/901234/2021', '321 Learning Avenue', 101, 4, 4, '600017', 'Ramesh Iyer', '9876543213', 'admin@cpschennai.edu.in', SHA1('admin123'), 'active'),
(3, 'Ahmedabad High School', 'CBSE', 2800, 'Progressive school', 'CBSE/567890/2022', '654 Education Lane', 101, 5, 5, '380015', 'Meera Patel', '9876543214', 'admin@ahmedabadhigh.edu.in', SHA1('admin123'), 'active');
*/

