-- Boards and Images subset from shivambo_livedbs.sql lines 37980-37991
-- Ensure boards exist (CBSE, ICSE, IGCSE) for vendor_id = 1

INSERT INTO erp_school_boards (vendor_id, board_name, status)
SELECT 1, 'CBSE', 'active' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM erp_school_boards WHERE board_name='CBSE' AND (vendor_id=1 OR vendor_id IS NULL));

INSERT INTO erp_school_boards (vendor_id, board_name, status)
SELECT 1, 'ICSE', 'active' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM erp_school_boards WHERE board_name='ICSE' AND (vendor_id=1 OR vendor_id IS NULL));

INSERT INTO erp_school_boards (vendor_id, board_name, status)
SELECT 1, 'IGCSE', 'active' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM erp_school_boards WHERE board_name='IGCSE' AND (vendor_id=1 OR vendor_id IS NULL));

-- Map helper vars
SET @cbse_id := (SELECT id FROM erp_school_boards WHERE (vendor_id=1 OR vendor_id IS NULL) AND board_name='CBSE' LIMIT 1);
SET @icse_id := (SELECT id FROM erp_school_boards WHERE (vendor_id=1 OR vendor_id IS NULL) AND board_name='ICSE' LIMIT 1);
SET @igcse_id := (SELECT id FROM erp_school_boards WHERE (vendor_id=1 OR vendor_id IS NULL) AND board_name='IGCSE' LIMIT 1);

-- GUNDECHA EDUCATION ACADEMY  KANDIVALI (ICSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=1 AND school_name='GUNDECHA EDUCATION ACADEMY  KANDIVALI' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @icse_id WHERE @sid IS NOT NULL AND @icse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'uploads/school/school_6023d8d23fe089-05048767-80969153.jpg', 1 WHERE @sid IS NOT NULL;

-- GUNDECHA EDUCATION ACADEMY OSHIWARA (ICSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=1 AND school_name='GUNDECHA EDUCATION ACADEMY OSHIWARA' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @icse_id WHERE @sid IS NOT NULL AND @icse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'uploads/school/school_6023da54f17626-19291541-45700987.jpg', 1 WHERE @sid IS NOT NULL;

-- MKVV International Vidyalaya (CBSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=1 AND school_name='MKVV International Vidyalaya' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @cbse_id WHERE @sid IS NOT NULL AND @cbse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'uploads/school/school_6023df65e94622-53498215-27351829.jpg', 1 WHERE @sid IS NOT NULL;

-- City International School, Aundh (CBSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=1 AND school_name='City International School, Aundh' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @cbse_id WHERE @sid IS NOT NULL AND @cbse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'uploads/school/school_60630d858cc508-57900429-90051227.jpg', 1 WHERE @sid IS NOT NULL;

-- City International School, Wanowrie (CBSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=1 AND school_name='City International School, Wanowrie' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @cbse_id WHERE @sid IS NOT NULL AND @cbse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'uploads/school/school_60630d53ba1415-57963960-60989582.jpg', 1 WHERE @sid IS NOT NULL;

-- City International School, Satara Road (CBSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=1 AND school_name='City International School, Satara Road' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @cbse_id WHERE @sid IS NOT NULL AND @cbse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'uploads/school/school_60630d3e32fd84-62546149-75005409.jpg', 1 WHERE @sid IS NOT NULL;

-- Tree House High School, Kalyan (ICSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=1 AND school_name='Tree House High School, Kalyan' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @icse_id WHERE @sid IS NOT NULL AND @icse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'uploads/school/school_606c585c079495-48913538-24989500.jpg', 1 WHERE @sid IS NOT NULL;

-- City International School, Mumbai (ICSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=1 AND school_name='City International School, Mumbai' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @icse_id WHERE @sid IS NOT NULL AND @icse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'uploads/school/school_607828c0920405-13414185-47023844.jpg', 1 WHERE @sid IS NOT NULL;

-- GUNDECHA EDUCATION ACADEMY IGCSE, KANDIVALI (IGCSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=1 AND school_name='GUNDECHA EDUCATION ACADEMY IGCSE, KANDIVALI' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @igcse_id WHERE @sid IS NOT NULL AND @igcse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'uploads/school/school_608663c6b34b37-04422462-74905474.jpg', 1 WHERE @sid IS NOT NULL;

-- Sharada Gyan Peeth International School, Malad East (ICSE)
SET @sid := (SELECT id FROM erp_schools WHERE vendor_id=1 AND school_name='Sharada Gyan Peeth International School, Malad East' LIMIT 1);
INSERT INTO erp_school_boards_mapping (school_id, board_id) SELECT @sid, @icse_id WHERE @sid IS NOT NULL AND @icse_id IS NOT NULL;
INSERT INTO erp_school_images (school_id, image_path, is_primary) SELECT @sid, 'uploads/school/school_6086b7622baf98-69393241-10853376.jpg', 1 WHERE @sid IS NOT NULL;
