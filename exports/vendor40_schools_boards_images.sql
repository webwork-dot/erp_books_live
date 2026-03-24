-- Final export for vendor_id = 40
-- Tables: erp_schools, erp_school_images, erp_school_boards, erp_school_boards_mapping
-- Source: shivambo_livedbs.sql lines 37980–37991

-- 1) Ensure boards exist for vendor_id = 40
INSERT INTO erp_school_boards (vendor_id, board_name, status)
SELECT 40, 'CBSE', 'active' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM erp_school_boards WHERE board_name='CBSE' AND (vendor_id=40 OR vendor_id IS NULL));

INSERT INTO erp_school_boards (vendor_id, board_name, status)
SELECT 40, 'ICSE', 'active' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM erp_school_boards WHERE board_name='ICSE' AND (vendor_id=40 OR vendor_id IS NULL));

INSERT INTO erp_school_boards (vendor_id, board_name, status)
SELECT 40, 'IGCSE', 'active' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM erp_school_boards WHERE board_name='IGCSE' AND (vendor_id=40 OR vendor_id IS NULL));

SET @cbse_id := (SELECT id FROM erp_school_boards WHERE (vendor_id=40 OR vendor_id IS NULL) AND board_name='CBSE' LIMIT 1);
SET @icse_id := (SELECT id FROM erp_school_boards WHERE (vendor_id=40 OR vendor_id IS NULL) AND board_name='ICSE' LIMIT 1);
SET @igcse_id := (SELECT id FROM erp_school_boards WHERE (vendor_id=40 OR vendor_id IS NULL) AND board_name='IGCSE' LIMIT 1);

-- 2) Insert schools for vendor_id = 40
INSERT INTO erp_schools (vendor_id, school_name, address, state_id, city_id, status, created_at, updated_at) VALUES
(40, 'GUNDECHA EDUCATION ACADEMY  KANDIVALI', 'Thakur Village Road, Valley of Flowers, near Evershine Dream Park, Thakur Village, Kandivali East, Mumbai, Maharashtra 400101', 1568, 17423, 'active', '2021-04-24 16:44:33', '2021-07-16 12:22:18'),
(40, 'GUNDECHA EDUCATION ACADEMY OSHIWARA', 'Off Link Road Next to Mega Mall, Oshiwara, Mhada Colony, Andheri West, Mumbai, Maharashtra 400053', 1568, 17423, 'active', '2021-04-24 16:45:06', '2021-07-16 12:22:49'),
(40, 'MKVV International Vidyalaya', 'Sukarwadi, Borivali East, Mumbai, Maharashtra 400066', 1568, 17423, 'active', '2021-02-13 17:39:06', '2021-09-03 16:13:25'),
(40, 'City International School, Aundh', '2/1, Vidyapeeth Rd, Near Bremen Chowk, Phase 1, Siddarth Nagar Society, Aundh, Pune, Maharashtra 411067', 1568, 17479, 'active', '2021-03-30 17:12:52', '2021-09-03 16:13:15'),
(40, 'City International School, Wanowrie', 'Fatima Nagar, Opp. Mahatma Phule Sanskrutik Bhawan, Wanowrie, Pune, Maharashtra 411040', 1568, 17479, 'active', '2021-03-30 17:12:58', '2021-09-03 16:13:08'),
(40, 'City International School, Satara Road', 'Maharshi Nagar, Pune - Satara Rd, behind Dena Bank, Pune, Maharashtra 411037', 1568, 17479, 'active', '2021-03-30 17:13:04', '2021-09-03 16:12:59'),
(40, 'Tree House High School, Kalyan', 'Godrej Hill, Khadakpada, Kalyan West, Kalyan, Maharashtra 421301', 1568, 17334, 'active', '2021-04-06 20:23:27', '2021-04-15 12:25:18'),
(40, 'City International School, Mumbai', 'New Link Rd, Oshiwara, Andheri West, Mumbai, Maharashtra 400102', 1568, 17423, 'active', '2021-04-21 12:56:55', '2021-09-03 16:12:46'),
(40, 'GUNDECHA EDUCATION ACADEMY IGCSE, KANDIVALI', 'Thakur Village Road, Valley of Flowers, near Evershine Dream Park, Thakur Village, Kandivali East, Mumbai, Maharashtra 400101', 1568, 17423, 'active', '2021-04-26 17:38:41', '2021-07-16 12:23:08'),
(40, 'Sharada Gyan Peeth International School, Malad East', 'Datta Mandir Road, Near Military Camp, Next To Central Ordance Depot, Malad East, Mumbai – 400097', 1568, 17423, 'active', '2021-04-26 20:24:40', '2021-09-03 16:11:27');

-- 3) Map boards and insert images
-- GUNDECHA EDUCATION ACADEMY  KANDIVALI (ICSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=40 AND school_name='GUNDECHA EDUCATION ACADEMY  KANDIVALI' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @icse_id WHERE @sid IS NOT NULL AND @icse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'assets/uploads/vendors/40/schools/school_6023d8d23fe089-05048767-80969153.jpg', 1 WHERE @sid IS NOT NULL;

-- GUNDECHA EDUCATION ACADEMY OSHIWARA (ICSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=40 AND school_name='GUNDECHA EDUCATION ACADEMY OSHIWARA' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @icse_id WHERE @sid IS NOT NULL AND @icse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'assets/uploads/vendors/40/schools/school_6023da54f17626-19291541-45700987.jpg', 1 WHERE @sid IS NOT NULL;

-- MKVV International Vidyalaya (CBSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=40 AND school_name='MKVV International Vidyalaya' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @cbse_id WHERE @sid IS NOT NULL AND @cbse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'assets/uploads/vendors/40/schools/school_6023df65e94622-53498215-27351829.jpg', 1 WHERE @sid IS NOT NULL;

-- City International School, Aundh (CBSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=40 AND school_name='City International School, Aundh' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @cbse_id WHERE @sid IS NOT NULL AND @cbse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'assets/uploads/vendors/40/schools/school_60630d858cc508-57900429-90051227.jpg', 1 WHERE @sid IS NOT NULL;

-- City International School, Wanowrie (CBSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=40 AND school_name='City International School, Wanowrie' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @cbse_id WHERE @sid IS NOT NULL AND @cbse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'assets/uploads/vendors/40/schools/school_60630d53ba1415-57963960-60989582.jpg', 1 WHERE @sid IS NOT NULL;

-- City International School, Satara Road (CBSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=40 AND school_name='City International School, Satara Road' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @cbse_id WHERE @sid IS NOT NULL AND @cbse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'assets/uploads/vendors/40/schools/school_60630d3e32fd84-62546149-75005409.jpg', 1 WHERE @sid IS NOT NULL;

-- Tree House High School, Kalyan (ICSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=40 AND school_name='Tree House High School, Kalyan' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @icse_id WHERE @sid IS NOT NULL AND @icse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'assets/uploads/vendors/40/schools/school_606c585c079495-48913538-24989500.jpg', 1 WHERE @sid IS NOT NULL;

-- City International School, Mumbai (ICSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=40 AND school_name='City International School, Mumbai' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @icse_id WHERE @sid IS NOT NULL AND @icse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'assets/uploads/vendors/40/schools/school_607828c0920405-13414185-47023844.jpg', 1 WHERE @sid IS NOT NULL;

-- GUNDECHA EDUCATION ACADEMY IGCSE, KANDIVALI (IGCSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=40 AND school_name='GUNDECHA EDUCATION ACADEMY IGCSE, KANDIVALI' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @igcse_id WHERE @sid IS NOT NULL AND @igcse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'assets/uploads/vendors/40/schools/school_608663c6b34b37-04422462-74905474.jpg', 1 WHERE @sid IS NOT NULL;

-- Sharada Gyan Peeth International School, Malad East (ICSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=40 AND school_name='Sharada Gyan Peeth International School, Malad East' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @icse_id WHERE @sid IS NOT NULL AND @icse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'assets/uploads/vendors/40/schools/school_6086b7622baf98-69393241-10853376.jpg', 1 WHERE @sid IS NOT NULL;
