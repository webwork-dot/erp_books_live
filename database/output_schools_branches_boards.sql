-- SQL Data Transfer Output
-- Generated: 2026-01-14 15:26:34
-- Source: varitty_varitdbc.sql
-- Vendor ID: 31

-- School Boards
INSERT INTO `erp_school_boards` (`id`, `vendor_id`, `board_name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 31, 'CBSE', '', 'active', '2026-01-14 15:26:33', '2026-01-14 15:26:33'),
(2, 31, 'ICSE', '', 'active', '2026-01-14 15:26:33', '2026-01-14 15:26:33'),
(3, 31, 'IGCSE', '', 'active', '2026-01-14 15:26:33', '2026-01-14 15:26:33'),
(4, 31, 'IB', '', 'active', '2026-01-14 15:26:33', '2026-01-14 15:26:33'),
(5, 31, 'STATE BOARD', '', 'active', '2026-01-14 15:26:33', '2026-01-14 15:26:33'),
(6, 31, 'CBSE Curriculum', '', 'active', '2026-01-14 15:26:33', '2026-01-14 15:26:33'),
(7, 31, 'Pre School', '', 'active', '2026-01-14 15:26:33', '2026-01-14 15:26:33');

-- Schools
INSERT INTO `erp_schools` (`id`, `vendor_id`, `school_name`, `school_board`, `total_strength`, `school_description`, `affiliation_no`, `address`, `country_id`, `state_id`, `city_id`, `pincode`, `admin_name`, `admin_phone`, `admin_email`, `admin_password`, `status`, `is_branch`, `parent_school_id`, `is_block_payment`, `is_national_block`, `created_at`, `updated_at`) VALUES
(1, 31, 'Thakur Vidya Mandir High School & Junior College', '5', NULL, 'Kandivali', '', '', 101, 0, 0, '', 'Thakur Vidya Mandir High School & Junior College', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-07 17:23:03', '2026-01-14 15:26:34'),
(2, 31, 'Seth Vidya Mandir High School', '1,5', NULL, 'Seth Vidya Mandir High School - Vasai', '', '', 101, 0, 0, '', 'Seth Vidya Mandir High School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-20 17:19:19', '2026-01-14 15:26:34'),
(3, 31, 'Expert International School', '5', NULL, 'Expert International School - Virar', '', '', 101, 0, 0, '', 'Expert International School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-26 17:28:57', '2026-01-14 15:26:34'),
(4, 31, 'Twinkle Star English High School', '5', NULL, 'Twinkle Star English High School - Palghar', '', '', 101, 0, 0, '', 'Twinkle Star English High School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-29 17:40:17', '2026-01-14 15:26:34'),
(5, 31, 'Radcliffe', '1', NULL, '', '', '', 101, 1568, 17423, '', 'Radcliffe', '', 'radcliffe@mail.com', 'f4fc8a416f8be148db91d57412cc34a0', 'active', 0, NULL, 0, 0, '2023-06-02 19:09:09', '2026-01-14 15:26:34'),
(6, 31, 'CP Goenka International School', '2', NULL, '', '', '', 101, 0, 0, '', 'CP Goenka International School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-27 18:48:15', '2026-01-14 15:26:34'),
(7, 31, 'Spring Buds International (SVIS)', '7', NULL, '', '', '', 101, 0, 0, '', 'Spring Buds International (SVIS)', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-05-10 17:07:29', '2026-01-14 15:26:34'),
(8, 31, 'Ajmera Global School', '4', NULL, '', '', '', 101, 0, 0, '', 'Ajmera Global School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-08 10:18:11', '2026-01-14 15:26:34'),
(9, 31, 'Swami Vivekanand International School & Junior College', '1', NULL, '', '', '', 101, 0, 0, '', 'Swami Vivekanand International School & Junior College', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-20 18:15:24', '2026-01-14 15:26:34'),
(10, 31, 'Guru Rajendra Jain International School', '1', NULL, '', '', '', 101, 0, 0, '', 'Guru Rajendra Jain International School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-20 18:17:04', '2026-01-14 15:26:34'),
(11, 31, 'Children\''s House Pre School', '7', NULL, '', '', '', 101, 0, 0, '', 'Children\''s House Pre School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-25 12:59:25', '2026-01-14 15:26:34'),
(12, 31, 'Children\''s House School', '1', NULL, '', '', '', 101, 0, 0, '', 'Children\''s House School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-25 12:58:54', '2026-01-14 15:26:34'),
(13, 31, 'C P Goenka\''s Spring Buds International PreSchool', '7', NULL, '', '', '', 101, 0, 0, '', 'C P Goenka\''s Spring Buds International PreSchool', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-27 18:49:32', '2026-01-14 15:26:34'),
(14, 31, 'Podar World School', '1', NULL, '', '', '', 101, 0, 0, '', 'Podar World School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-27 18:58:50', '2026-01-14 15:26:34'),
(15, 31, 'Ajmera School', '2', NULL, '', '', '', 101, 0, 0, '', 'Ajmera School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-27 19:01:56', '2026-01-14 15:26:34'),
(16, 31, 'Jolly kids Pre-school', '1', NULL, '', '', '', 101, 0, 0, '', 'Jolly kids Pre-school', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-05-19 17:20:47', '2026-01-14 15:26:34');

-- School Branches
INSERT INTO `erp_school_branches` (`id`, `school_id`, `vendor_id`, `branch_name`, `address`, `country_id`, `state_id`, `city_id`, `pincode`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 31, 'Khargahr', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(2, 5, 31, 'Dighi', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(3, 5, 31, 'Hyderabad', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(4, 5, 31, 'Bengaluru', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(5, 5, 31, 'Kochi', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(6, 5, 31, 'Bhathinda', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(7, 5, 31, 'Patiala', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(8, 5, 31, 'Bhopal', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(9, 5, 31, 'Jaipur', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(10, 7, 31, 'Borivali', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(11, 7, 31, 'Kandivali', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(12, 5, 31, 'Thane', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(13, 5, 31, 'Taloja', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34'),
(14, 5, 31, 'Ulwe', '', 101, 0, 0, '', 'inactive', '2026-01-14 15:26:34', '2026-01-14 15:26:34');

-- School-Board Mappings
INSERT INTO `erp_school_boards_mapping` (`id`, `school_id`, `board_id`, `created_at`) VALUES
(1, 1, 5, '2026-01-14 15:26:34'),
(2, 2, 1, '2026-01-14 15:26:34'),
(3, 2, 5, '2026-01-14 15:26:34'),
(4, 3, 5, '2026-01-14 15:26:34'),
(5, 4, 5, '2026-01-14 15:26:34'),
(6, 5, 1, '2026-01-14 15:26:34'),
(7, 6, 2, '2026-01-14 15:26:34'),
(8, 7, 7, '2026-01-14 15:26:34'),
(9, 8, 4, '2026-01-14 15:26:34'),
(10, 9, 1, '2026-01-14 15:26:34'),
(11, 10, 1, '2026-01-14 15:26:34'),
(12, 11, 7, '2026-01-14 15:26:34'),
(13, 12, 1, '2026-01-14 15:26:34'),
(14, 13, 7, '2026-01-14 15:26:34'),
(15, 14, 1, '2026-01-14 15:26:34'),
(16, 15, 2, '2026-01-14 15:26:34'),
(17, 16, 1, '2026-01-14 15:26:34');

