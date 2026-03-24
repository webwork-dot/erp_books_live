-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2026 at 09:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp_client_varitty`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`erp_master_dbz`@`localhost` PROCEDURE `check_feature_enabled` (IN `p_feature_slug` VARCHAR(255))   BEGIN
    SELECT is_enabled FROM vendor_features 
    WHERE feature_slug = p_feature_slug AND is_enabled = 1
    LIMIT 1;
END$$

CREATE DEFINER=`erp_master_dbz`@`localhost` PROCEDURE `check_subcategory_enabled` (IN `p_feature_id` INT(11) UNSIGNED, IN `p_subcategory_slug` VARCHAR(255))   BEGIN
    SELECT is_enabled FROM vendor_feature_subcategories 
    WHERE feature_id = p_feature_id 
    AND subcategory_slug = p_subcategory_slug 
    AND is_enabled = 1
    LIMIT 1;
END$$

--
-- Functions
--
CREATE DEFINER=`erp_master_dbz`@`localhost` FUNCTION `is_feature_enabled` (`p_feature_slug` VARCHAR(255)) RETURNS TINYINT(1) DETERMINISTIC READS SQL DATA BEGIN
    DECLARE v_enabled TINYINT(1) DEFAULT 0;
    SELECT is_enabled INTO v_enabled 
    FROM vendor_features 
    WHERE feature_slug = p_feature_slug AND is_enabled = 1
    LIMIT 1;
    RETURN IFNULL(v_enabled, 0);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `dial_code` varchar(10) DEFAULT '+91',
  `alternate_phone` varchar(100) DEFAULT NULL,
  `alternate_dial_code` varchar(10) DEFAULT NULL,
  `address` text NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `country_name` varchar(100) DEFAULT NULL,
  `area` varchar(200) DEFAULT NULL,
  `state` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` varchar(255) NOT NULL,
  `landmark` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `other_address` varchar(250) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(50) DEFAULT NULL,
  `building_name` varchar(100) DEFAULT NULL,
  `flat_house_no` varchar(100) DEFAULT NULL,
  `location` longtext CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `lattitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `user_id`, `name`, `phone`, `dial_code`, `alternate_phone`, `alternate_dial_code`, `address`, `country_id`, `country_name`, `area`, `state`, `city`, `pincode`, `landmark`, `type`, `other_address`, `is_deleted`, `email`, `building_name`, `flat_house_no`, `location`, `lattitude`, `longitude`, `created_at`) VALUES
(1, 1, 'Anass', '6476711319', '+91', '', NULL, '347 Blvd', 101, 'India', '', 'Andaman and Nicobar Islands', 'Garacharma', '400075', '', '', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-21 13:26:41'),
(2, 1, 'Anass', '6476711319', '+91', '', NULL, '347 Blvd', 101, 'India', '', 'Andhra Pradesh', 'Adoni', '400075', '', '', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-22 07:21:14');

-- --------------------------------------------------------

--
-- Table structure for table `address_cities`
--

CREATE TABLE `address_cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `long` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `state_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address_states`
--

CREATE TABLE `address_states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `long` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `banner_image` varchar(500) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `caption` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `slug` varchar(250) NOT NULL,
  `wp_url` varchar(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `date` date DEFAULT NULL,
  `image` varchar(250) NOT NULL,
  `yt_url` varchar(300) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `shrt_desc` text NOT NULL,
  `meta_title` varchar(250) NOT NULL,
  `meta_keyword` text NOT NULL,
  `meta_description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `last_modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 101 COMMENT '101 = India',
  `state_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Cities';

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `country_id`, `state_id`) VALUES
(14717, 'Bombuflat', 101, 1547),
(14718, 'Garacharma', 101, 1547),
(14719, 'Port Blair', 101, 1547),
(14720, 'Rangat', 101, 1547),
(14721, 'Addanki', 101, 1548),
(14722, 'Adivivaram', 101, 1548),
(14723, 'Adoni', 101, 1548),
(14724, 'Aganampudi', 101, 1548),
(14725, 'Ajjaram', 101, 1548),
(14726, 'Akividu', 101, 1548),
(14727, 'Akkarampalle', 101, 1548),
(14728, 'Akkayapalle', 101, 1548),
(14729, 'Akkireddipalem', 101, 1548),
(14730, 'Alampur', 101, 1548),
(14731, 'Amalapuram', 101, 1548),
(14732, 'Amudalavalasa', 101, 1548),
(14733, 'Amur', 101, 1548),
(14734, 'Anakapalle', 101, 1548),
(14735, 'Anantapur', 101, 1548),
(14736, 'Andole', 101, 1548),
(14737, 'Atmakur', 101, 1548),
(14738, 'Attili', 101, 1548),
(14739, 'Avanigadda', 101, 1548),
(14740, 'Badepalli', 101, 1548),
(14741, 'Badvel', 101, 1548),
(14742, 'Balapur', 101, 1548),
(14743, 'Bandarulanka', 101, 1548),
(14744, 'Banganapalle', 101, 1548),
(14745, 'Bapatla', 101, 1548),
(14746, 'Bapulapadu', 101, 1548),
(14747, 'Belampalli', 101, 1548),
(14748, 'Bestavaripeta', 101, 1548),
(14749, 'Betamcherla', 101, 1548),
(14750, 'Bhattiprolu', 101, 1548),
(14751, 'Bhimavaram', 101, 1548),
(14752, 'Bhimunipatnam', 101, 1548),
(14753, 'Bobbili', 101, 1548),
(14754, 'Bombuflat', 101, 1548),
(14755, 'Bommuru', 101, 1548),
(14756, 'Bugganipalle', 101, 1548),
(14757, 'Challapalle', 101, 1548),
(14758, 'Chandur', 101, 1548),
(14759, 'Chatakonda', 101, 1548),
(14760, 'Chemmumiahpet', 101, 1548),
(14761, 'Chidiga', 101, 1548),
(14762, 'Chilakaluripet', 101, 1548),
(14763, 'Chimakurthy', 101, 1548),
(14764, 'Chinagadila', 101, 1548),
(14765, 'Chinagantyada', 101, 1548),
(14766, 'Chinnachawk', 101, 1548),
(14767, 'Chintalavalasa', 101, 1548),
(14768, 'Chipurupalle', 101, 1548),
(14769, 'Chirala', 101, 1548),
(14770, 'Chittoor', 101, 1548),
(14771, 'Chodavaram', 101, 1548),
(14772, 'Choutuppal', 101, 1548),
(14773, 'Chunchupalle', 101, 1548),
(14774, 'Cuddapah', 101, 1548),
(14775, 'Cumbum', 101, 1548),
(14776, 'Darnakal', 101, 1548),
(14777, 'Dasnapur', 101, 1548),
(14778, 'Dauleshwaram', 101, 1548),
(14779, 'Dharmavaram', 101, 1548),
(14780, 'Dhone', 101, 1548),
(14781, 'Dommara Nandyal', 101, 1548),
(14782, 'Dowlaiswaram', 101, 1548),
(14783, 'East Godavari Dist.', 101, 1548),
(14784, 'Eddumailaram', 101, 1548),
(14785, 'Edulapuram', 101, 1548),
(14786, 'Ekambara kuppam', 101, 1548),
(14787, 'Eluru', 101, 1548),
(14788, 'Enikapadu', 101, 1548),
(14789, 'Fakirtakya', 101, 1548),
(14790, 'Farrukhnagar', 101, 1548),
(14791, 'Gaddiannaram', 101, 1548),
(14792, 'Gajapathinagaram', 101, 1548),
(14793, 'Gajularega', 101, 1548),
(14794, 'Gajuvaka', 101, 1548),
(14795, 'Gannavaram', 101, 1548),
(14796, 'Garacharma', 101, 1548),
(14797, 'Garimellapadu', 101, 1548),
(14798, 'Giddalur', 101, 1548),
(14799, 'Godavarikhani', 101, 1548),
(14800, 'Gopalapatnam', 101, 1548),
(14801, 'Gopalur', 101, 1548),
(14802, 'Gorrekunta', 101, 1548),
(14803, 'Gudivada', 101, 1548),
(14804, 'Gudur', 101, 1548),
(14805, 'Guntakal', 101, 1548),
(14806, 'Guntur', 101, 1548),
(14807, 'Guti', 101, 1548),
(14808, 'Hindupur', 101, 1548),
(14809, 'Hukumpeta', 101, 1548),
(14810, 'Ichchapuram', 101, 1548),
(14811, 'Isnapur', 101, 1548),
(14812, 'Jaggayyapeta', 101, 1548),
(14813, 'Jallaram Kamanpur', 101, 1548),
(14814, 'Jammalamadugu', 101, 1548),
(14815, 'Jangampalli', 101, 1548),
(14816, 'Jarjapupeta', 101, 1548),
(14817, 'Kadiri', 101, 1548),
(14818, 'Kaikalur', 101, 1548),
(14819, 'Kakinada', 101, 1548),
(14820, 'Kallur', 101, 1548),
(14821, 'Kalyandurg', 101, 1548),
(14822, 'Kamalapuram', 101, 1548),
(14823, 'Kamareddi', 101, 1548),
(14824, 'Kanapaka', 101, 1548),
(14825, 'Kanigiri', 101, 1548),
(14826, 'Kanithi', 101, 1548),
(14827, 'Kankipadu', 101, 1548),
(14828, 'Kantabamsuguda', 101, 1548),
(14829, 'Kanuru', 101, 1548),
(14830, 'Karnul', 101, 1548),
(14831, 'Katheru', 101, 1548),
(14832, 'Kavali', 101, 1548),
(14833, 'Kazipet', 101, 1548),
(14834, 'Khanapuram Haveli', 101, 1548),
(14835, 'Kodar', 101, 1548),
(14836, 'Kollapur', 101, 1548),
(14837, 'Kondapalem', 101, 1548),
(14838, 'Kondapalle', 101, 1548),
(14839, 'Kondukur', 101, 1548),
(14840, 'Kosgi', 101, 1548),
(14841, 'Kothavalasa', 101, 1548),
(14842, 'Kottapalli', 101, 1548),
(14843, 'Kovur', 101, 1548),
(14844, 'Kovurpalle', 101, 1548),
(14845, 'Kovvur', 101, 1548),
(14846, 'Krishna', 101, 1548),
(14847, 'Kuppam', 101, 1548),
(14848, 'Kurmannapalem', 101, 1548),
(14849, 'Kurnool', 101, 1548),
(14850, 'Lakshettipet', 101, 1548),
(14851, 'Lalbahadur Nagar', 101, 1548),
(14852, 'Machavaram', 101, 1548),
(14853, 'Macherla', 101, 1548),
(14854, 'Machilipatnam', 101, 1548),
(14855, 'Madanapalle', 101, 1548),
(14856, 'Madaram', 101, 1548),
(14857, 'Madhuravada', 101, 1548),
(14858, 'Madikonda', 101, 1548),
(14859, 'Madugule', 101, 1548),
(14860, 'Mahabubnagar', 101, 1548),
(14861, 'Mahbubabad', 101, 1548),
(14862, 'Malkajgiri', 101, 1548),
(14863, 'Mamilapalle', 101, 1548),
(14864, 'Mancheral', 101, 1548),
(14865, 'Mandapeta', 101, 1548),
(14866, 'Mandasa', 101, 1548),
(14867, 'Mangalagiri', 101, 1548),
(14868, 'Manthani', 101, 1548),
(14869, 'Markapur', 101, 1548),
(14870, 'Marturu', 101, 1548),
(14871, 'Metpalli', 101, 1548),
(14872, 'Mindi', 101, 1548),
(14873, 'Mirpet', 101, 1548),
(14874, 'Moragudi', 101, 1548),
(14875, 'Mothugudam', 101, 1548),
(14876, 'Nagari', 101, 1548),
(14877, 'Nagireddipalle', 101, 1548),
(14878, 'Nandigama', 101, 1548),
(14879, 'Nandikotkur', 101, 1548),
(14880, 'Nandyal', 101, 1548),
(14881, 'Narasannapeta', 101, 1548),
(14882, 'Narasapur', 101, 1548),
(14883, 'Narasaraopet', 101, 1548),
(14884, 'Narayanavanam', 101, 1548),
(14885, 'Narsapur', 101, 1548),
(14886, 'Narsingi', 101, 1548),
(14887, 'Narsipatnam', 101, 1548),
(14888, 'Naspur', 101, 1548),
(14889, 'Nathayyapalem', 101, 1548),
(14890, 'Nayudupeta', 101, 1548),
(14891, 'Nelimaria', 101, 1548),
(14892, 'Nellore', 101, 1548),
(14893, 'Nidadavole', 101, 1548),
(14894, 'Nuzvid', 101, 1548),
(14895, 'Omerkhan daira', 101, 1548),
(14896, 'Ongole', 101, 1548),
(14897, 'Osmania University', 101, 1548),
(14898, 'Pakala', 101, 1548),
(14899, 'Palakole', 101, 1548),
(14900, 'Palakurthi', 101, 1548),
(14901, 'Palasa', 101, 1548),
(14902, 'Palempalle', 101, 1548),
(14903, 'Palkonda', 101, 1548),
(14904, 'Palmaner', 101, 1548),
(14905, 'Pamur', 101, 1548),
(14906, 'Panjim', 101, 1548),
(14907, 'Papampeta', 101, 1548),
(14908, 'Parasamba', 101, 1548),
(14909, 'Parvatipuram', 101, 1548),
(14910, 'Patancheru', 101, 1548),
(14911, 'Payakaraopet', 101, 1548),
(14912, 'Pedagantyada', 101, 1548),
(14913, 'Pedana', 101, 1548),
(14914, 'Peddapuram', 101, 1548),
(14915, 'Pendurthi', 101, 1548),
(14916, 'Penugonda', 101, 1548),
(14917, 'Penukonda', 101, 1548),
(14918, 'Phirangipuram', 101, 1548),
(14919, 'Pithapuram', 101, 1548),
(14920, 'Ponnur', 101, 1548),
(14921, 'Port Blair', 101, 1548),
(14922, 'Pothinamallayyapalem', 101, 1548),
(14923, 'Prakasam', 101, 1548),
(14924, 'Prasadampadu', 101, 1548),
(14925, 'Prasantinilayam', 101, 1548),
(14926, 'Proddatur', 101, 1548),
(14927, 'Pulivendla', 101, 1548),
(14928, 'Punganuru', 101, 1548),
(14929, 'Puttur', 101, 1548),
(14930, 'Qutubullapur', 101, 1548),
(14931, 'Rajahmundry', 101, 1548),
(14932, 'Rajamahendri', 101, 1548),
(14933, 'Rajampet', 101, 1548),
(14934, 'Rajendranagar', 101, 1548),
(14935, 'Rajoli', 101, 1548),
(14936, 'Ramachandrapuram', 101, 1548),
(14937, 'Ramanayyapeta', 101, 1548),
(14938, 'Ramapuram', 101, 1548),
(14939, 'Ramarajupalli', 101, 1548),
(14940, 'Ramavarappadu', 101, 1548),
(14941, 'Rameswaram', 101, 1548),
(14942, 'Rampachodavaram', 101, 1548),
(14943, 'Ravulapalam', 101, 1548),
(14944, 'Rayachoti', 101, 1548),
(14945, 'Rayadrug', 101, 1548),
(14946, 'Razam', 101, 1548),
(14947, 'Razole', 101, 1548),
(14948, 'Renigunta', 101, 1548),
(14949, 'Repalle', 101, 1548),
(14950, 'Rishikonda', 101, 1548),
(14951, 'Salur', 101, 1548),
(14952, 'Samalkot', 101, 1548),
(14953, 'Sattenapalle', 101, 1548),
(14954, 'Seetharampuram', 101, 1548),
(14955, 'Serilungampalle', 101, 1548),
(14956, 'Shankarampet', 101, 1548),
(14957, 'Shar', 101, 1548),
(14958, 'Singarayakonda', 101, 1548),
(14959, 'Sirpur', 101, 1548),
(14960, 'Sirsilla', 101, 1548),
(14961, 'Sompeta', 101, 1548),
(14962, 'Sriharikota', 101, 1548),
(14963, 'Srikakulam', 101, 1548),
(14964, 'Srikalahasti', 101, 1548),
(14965, 'Sriramnagar', 101, 1548),
(14966, 'Sriramsagar', 101, 1548),
(14967, 'Srisailam', 101, 1548),
(14968, 'Srisailamgudem Devasthanam', 101, 1548),
(14969, 'Sulurpeta', 101, 1548),
(14970, 'Suriapet', 101, 1548),
(14971, 'Suryaraopet', 101, 1548),
(14972, 'Tadepalle', 101, 1548),
(14973, 'Tadepalligudem', 101, 1548),
(14974, 'Tadpatri', 101, 1548),
(14975, 'Tallapalle', 101, 1548),
(14976, 'Tanuku', 101, 1548),
(14977, 'Tekkali', 101, 1548),
(14978, 'Tenali', 101, 1548),
(14979, 'Tigalapahad', 101, 1548),
(14980, 'Tiruchanur', 101, 1548),
(14981, 'Tirumala', 101, 1548),
(14982, 'Tirupati', 101, 1548),
(14983, 'Tirvuru', 101, 1548),
(14984, 'Trimulgherry', 101, 1548),
(14985, 'Tuni', 101, 1548),
(14986, 'Turangi', 101, 1548),
(14987, 'Ukkayapalli', 101, 1548),
(14988, 'Ukkunagaram', 101, 1548),
(14989, 'Uppal Kalan', 101, 1548),
(14990, 'Upper Sileru', 101, 1548),
(14991, 'Uravakonda', 101, 1548),
(14992, 'Vadlapudi', 101, 1548),
(14993, 'Vaparala', 101, 1548),
(14994, 'Vemalwada', 101, 1548),
(14995, 'Venkatagiri', 101, 1548),
(14996, 'Venkatapuram', 101, 1548),
(14997, 'Vepagunta', 101, 1548),
(14998, 'Vetapalem', 101, 1548),
(14999, 'Vijayapuri', 101, 1548),
(15000, 'Vijayapuri South', 101, 1548),
(15001, 'Vijayawada', 101, 1548),
(15002, 'Vinukonda', 101, 1548),
(15003, 'Visakhapatnam', 101, 1548),
(15004, 'Vizianagaram', 101, 1548),
(15005, 'Vuyyuru', 101, 1548),
(15006, 'Wanparti', 101, 1548),
(15007, 'West Godavari Dist.', 101, 1548),
(15008, 'Yadagirigutta', 101, 1548),
(15009, 'Yarada', 101, 1548),
(15010, 'Yellamanchili', 101, 1548),
(15011, 'Yemmiganur', 101, 1548),
(15012, 'Yenamalakudru', 101, 1548),
(15013, 'Yendada', 101, 1548),
(15014, 'Yerraguntla', 101, 1548),
(15015, 'Along', 101, 1549),
(15016, 'Basar', 101, 1549),
(15017, 'Bondila', 101, 1549),
(15018, 'Changlang', 101, 1549),
(15019, 'Daporijo', 101, 1549),
(15020, 'Deomali', 101, 1549),
(15021, 'Itanagar', 101, 1549),
(15022, 'Jairampur', 101, 1549),
(15023, 'Khonsa', 101, 1549),
(15024, 'Naharlagun', 101, 1549),
(15025, 'Namsai', 101, 1549),
(15026, 'Pasighat', 101, 1549),
(15027, 'Roing', 101, 1549),
(15028, 'Seppa', 101, 1549),
(15029, 'Tawang', 101, 1549),
(15030, 'Tezu', 101, 1549),
(15031, 'Ziro', 101, 1549),
(15032, 'Abhayapuri', 101, 1550),
(15033, 'Ambikapur', 101, 1550),
(15034, 'Amguri', 101, 1550),
(15035, 'Anand Nagar', 101, 1550),
(15036, 'Badarpur', 101, 1550),
(15037, 'Badarpur Railway Town', 101, 1550),
(15038, 'Bahbari Gaon', 101, 1550),
(15039, 'Bamun Sualkuchi', 101, 1550),
(15040, 'Barbari', 101, 1550),
(15041, 'Barpathar', 101, 1550),
(15042, 'Barpeta', 101, 1550),
(15043, 'Barpeta Road', 101, 1550),
(15044, 'Basugaon', 101, 1550),
(15045, 'Bihpuria', 101, 1550),
(15046, 'Bijni', 101, 1550),
(15047, 'Bilasipara', 101, 1550),
(15048, 'Biswanath Chariali', 101, 1550),
(15049, 'Bohori', 101, 1550),
(15050, 'Bokajan', 101, 1550),
(15051, 'Bokokhat', 101, 1550),
(15052, 'Bongaigaon', 101, 1550),
(15053, 'Bongaigaon Petro-chemical Town', 101, 1550),
(15054, 'Borgolai', 101, 1550),
(15055, 'Chabua', 101, 1550),
(15056, 'Chandrapur Bagicha', 101, 1550),
(15057, 'Chapar', 101, 1550),
(15058, 'Chekonidhara', 101, 1550),
(15059, 'Choto Haibor', 101, 1550),
(15060, 'Dergaon', 101, 1550),
(15061, 'Dharapur', 101, 1550),
(15062, 'Dhekiajuli', 101, 1550),
(15063, 'Dhemaji', 101, 1550),
(15064, 'Dhing', 101, 1550),
(15065, 'Dhubri', 101, 1550),
(15066, 'Dhuburi', 101, 1550),
(15067, 'Dibrugarh', 101, 1550),
(15068, 'Digboi', 101, 1550),
(15069, 'Digboi Oil Town', 101, 1550),
(15070, 'Dimaruguri', 101, 1550),
(15071, 'Diphu', 101, 1550),
(15072, 'Dispur', 101, 1550),
(15073, 'Doboka', 101, 1550),
(15074, 'Dokmoka', 101, 1550),
(15075, 'Donkamokan', 101, 1550),
(15076, 'Duliagaon', 101, 1550),
(15077, 'Duliajan', 101, 1550),
(15078, 'Duliajan No.1', 101, 1550),
(15079, 'Dum Duma', 101, 1550),
(15080, 'Durga Nagar', 101, 1550),
(15081, 'Gauripur', 101, 1550),
(15082, 'Goalpara', 101, 1550),
(15083, 'Gohpur', 101, 1550),
(15084, 'Golaghat', 101, 1550),
(15085, 'Golakganj', 101, 1550),
(15086, 'Gossaigaon', 101, 1550),
(15087, 'Guwahati', 101, 1550),
(15088, 'Haflong', 101, 1550),
(15089, 'Hailakandi', 101, 1550),
(15090, 'Hamren', 101, 1550),
(15091, 'Hauli', 101, 1550),
(15092, 'Hauraghat', 101, 1550),
(15093, 'Hojai', 101, 1550),
(15094, 'Jagiroad', 101, 1550),
(15095, 'Jagiroad Paper Mill', 101, 1550),
(15096, 'Jogighopa', 101, 1550),
(15097, 'Jonai Bazar', 101, 1550),
(15098, 'Jorhat', 101, 1550),
(15099, 'Kampur Town', 101, 1550),
(15100, 'Kamrup', 101, 1550),
(15101, 'Kanakpur', 101, 1550),
(15102, 'Karimganj', 101, 1550),
(15103, 'Kharijapikon', 101, 1550),
(15104, 'Kharupetia', 101, 1550),
(15105, 'Kochpara', 101, 1550),
(15106, 'Kokrajhar', 101, 1550),
(15107, 'Kumar Kaibarta Gaon', 101, 1550),
(15108, 'Lakhimpur', 101, 1550),
(15109, 'Lakhipur', 101, 1550),
(15110, 'Lala', 101, 1550),
(15111, 'Lanka', 101, 1550),
(15112, 'Lido Tikok', 101, 1550),
(15113, 'Lido Town', 101, 1550),
(15114, 'Lumding', 101, 1550),
(15115, 'Lumding Railway Colony', 101, 1550),
(15116, 'Mahur', 101, 1550),
(15117, 'Maibong', 101, 1550),
(15118, 'Majgaon', 101, 1550),
(15119, 'Makum', 101, 1550),
(15120, 'Mangaldai', 101, 1550),
(15121, 'Mankachar', 101, 1550),
(15122, 'Margherita', 101, 1550),
(15123, 'Mariani', 101, 1550),
(15124, 'Marigaon', 101, 1550),
(15125, 'Moran', 101, 1550),
(15126, 'Moranhat', 101, 1550),
(15127, 'Nagaon', 101, 1550),
(15128, 'Naharkatia', 101, 1550),
(15129, 'Nalbari', 101, 1550),
(15130, 'Namrup', 101, 1550),
(15131, 'Naubaisa Gaon', 101, 1550),
(15132, 'Nazira', 101, 1550),
(15133, 'New Bongaigaon Railway Colony', 101, 1550),
(15134, 'Niz-Hajo', 101, 1550),
(15135, 'North Guwahati', 101, 1550),
(15136, 'Numaligarh', 101, 1550),
(15137, 'Palasbari', 101, 1550),
(15138, 'Panchgram', 101, 1550),
(15139, 'Pathsala', 101, 1550),
(15140, 'Raha', 101, 1550),
(15141, 'Rangapara', 101, 1550),
(15142, 'Rangia', 101, 1550),
(15143, 'Salakati', 101, 1550),
(15144, 'Sapatgram', 101, 1550),
(15145, 'Sarthebari', 101, 1550),
(15146, 'Sarupathar', 101, 1550),
(15147, 'Sarupathar Bengali', 101, 1550),
(15148, 'Senchoagaon', 101, 1550),
(15149, 'Sibsagar', 101, 1550),
(15150, 'Silapathar', 101, 1550),
(15151, 'Silchar', 101, 1550),
(15152, 'Silchar Part-X', 101, 1550),
(15153, 'Sonari', 101, 1550),
(15154, 'Sorbhog', 101, 1550),
(15155, 'Sualkuchi', 101, 1550),
(15156, 'Tangla', 101, 1550),
(15157, 'Tezpur', 101, 1550),
(15158, 'Tihu', 101, 1550),
(15159, 'Tinsukia', 101, 1550),
(15160, 'Titabor', 101, 1550),
(15161, 'Udalguri', 101, 1550),
(15162, 'Umrangso', 101, 1550),
(15163, 'Uttar Krishnapur Part-I', 101, 1550),
(15164, 'Amarpur', 101, 1551),
(15165, 'Ara', 101, 1551),
(15166, 'Araria', 101, 1551),
(15167, 'Areraj', 101, 1551),
(15168, 'Asarganj', 101, 1551),
(15169, 'Aurangabad', 101, 1551),
(15170, 'Bagaha', 101, 1551),
(15171, 'Bahadurganj', 101, 1551),
(15172, 'Bairgania', 101, 1551),
(15173, 'Bakhtiyarpur', 101, 1551),
(15174, 'Banka', 101, 1551),
(15175, 'Banmankhi', 101, 1551),
(15176, 'Bar Bigha', 101, 1551),
(15177, 'Barauli', 101, 1551),
(15178, 'Barauni Oil Township', 101, 1551),
(15179, 'Barh', 101, 1551),
(15180, 'Barhiya', 101, 1551),
(15181, 'Bariapur', 101, 1551),
(15182, 'Baruni', 101, 1551),
(15183, 'Begusarai', 101, 1551),
(15184, 'Behea', 101, 1551),
(15185, 'Belsand', 101, 1551),
(15186, 'Bettiah', 101, 1551),
(15187, 'Bhabua', 101, 1551),
(15188, 'Bhagalpur', 101, 1551),
(15189, 'Bhimnagar', 101, 1551),
(15190, 'Bhojpur', 101, 1551),
(15191, 'Bihar', 101, 1551),
(15192, 'Bihar Sharif', 101, 1551),
(15193, 'Bihariganj', 101, 1551),
(15194, 'Bikramganj', 101, 1551),
(15195, 'Birpur', 101, 1551),
(15196, 'Bodh Gaya', 101, 1551),
(15197, 'Buxar', 101, 1551),
(15198, 'Chakia', 101, 1551),
(15199, 'Chanpatia', 101, 1551),
(15200, 'Chhapra', 101, 1551),
(15201, 'Chhatapur', 101, 1551),
(15202, 'Colgong', 101, 1551),
(15203, 'Dalsingh Sarai', 101, 1551),
(15204, 'Darbhanga', 101, 1551),
(15205, 'Daudnagar', 101, 1551),
(15206, 'Dehri', 101, 1551),
(15207, 'Dhaka', 101, 1551),
(15208, 'Dighwara', 101, 1551),
(15209, 'Dinapur', 101, 1551),
(15210, 'Dinapur Cantonment', 101, 1551),
(15211, 'Dumra', 101, 1551),
(15212, 'Dumraon', 101, 1551),
(15213, 'Fatwa', 101, 1551),
(15214, 'Forbesganj', 101, 1551),
(15215, 'Gaya', 101, 1551),
(15216, 'Gazipur', 101, 1551),
(15217, 'Ghoghardiha', 101, 1551),
(15218, 'Gogri Jamalpur', 101, 1551),
(15219, 'Gopalganj', 101, 1551),
(15220, 'Habibpur', 101, 1551),
(15221, 'Hajipur', 101, 1551),
(15222, 'Hasanpur', 101, 1551),
(15223, 'Hazaribagh', 101, 1551),
(15224, 'Hilsa', 101, 1551),
(15225, 'Hisua', 101, 1551),
(15226, 'Islampur', 101, 1551),
(15227, 'Jagdispur', 101, 1551),
(15228, 'Jahanabad', 101, 1551),
(15229, 'Jamalpur', 101, 1551),
(15230, 'Jamhaur', 101, 1551),
(15231, 'Jamui', 101, 1551),
(15232, 'Janakpur Road', 101, 1551),
(15233, 'Janpur', 101, 1551),
(15234, 'Jaynagar', 101, 1551),
(15235, 'Jha Jha', 101, 1551),
(15236, 'Jhanjharpur', 101, 1551),
(15237, 'Jogbani', 101, 1551),
(15238, 'Kanti', 101, 1551),
(15239, 'Kasba', 101, 1551),
(15240, 'Kataiya', 101, 1551),
(15241, 'Katihar', 101, 1551),
(15242, 'Khagaria', 101, 1551),
(15243, 'Khagaul', 101, 1551),
(15244, 'Kharagpur', 101, 1551),
(15245, 'Khusrupur', 101, 1551),
(15246, 'Kishanganj', 101, 1551),
(15247, 'Koath', 101, 1551),
(15248, 'Koilwar', 101, 1551),
(15249, 'Lakhisarai', 101, 1551),
(15250, 'Lalganj', 101, 1551),
(15251, 'Lauthaha', 101, 1551),
(15252, 'Madhepura', 101, 1551),
(15253, 'Madhubani', 101, 1551),
(15254, 'Maharajganj', 101, 1551),
(15255, 'Mahnar Bazar', 101, 1551),
(15256, 'Mairwa', 101, 1551),
(15257, 'Makhdumpur', 101, 1551),
(15258, 'Maner', 101, 1551),
(15259, 'Manihari', 101, 1551),
(15260, 'Marhaura', 101, 1551),
(15261, 'Masaurhi', 101, 1551),
(15262, 'Mirganj', 101, 1551),
(15263, 'Mohiuddinagar', 101, 1551),
(15264, 'Mokama', 101, 1551),
(15265, 'Motihari', 101, 1551),
(15266, 'Motipur', 101, 1551),
(15267, 'Munger', 101, 1551),
(15268, 'Murliganj', 101, 1551),
(15269, 'Muzaffarpur', 101, 1551),
(15270, 'Nabinagar', 101, 1551),
(15271, 'Narkatiaganj', 101, 1551),
(15272, 'Nasriganj', 101, 1551),
(15273, 'Natwar', 101, 1551),
(15274, 'Naugachhia', 101, 1551),
(15275, 'Nawada', 101, 1551),
(15276, 'Nirmali', 101, 1551),
(15277, 'Nokha', 101, 1551),
(15278, 'Paharpur', 101, 1551),
(15279, 'Patna', 101, 1551),
(15280, 'Phulwari', 101, 1551),
(15281, 'Piro', 101, 1551),
(15282, 'Purnia', 101, 1551),
(15283, 'Pusa', 101, 1551),
(15284, 'Rafiganj', 101, 1551),
(15285, 'Raghunathpur', 101, 1551),
(15286, 'Rajgir', 101, 1551),
(15287, 'Ramnagar', 101, 1551),
(15288, 'Raxaul', 101, 1551),
(15289, 'Revelganj', 101, 1551),
(15290, 'Rusera', 101, 1551),
(15291, 'Sagauli', 101, 1551),
(15292, 'Saharsa', 101, 1551),
(15293, 'Samastipur', 101, 1551),
(15294, 'Sasaram', 101, 1551),
(15295, 'Shahpur', 101, 1551),
(15296, 'Shaikhpura', 101, 1551),
(15297, 'Sherghati', 101, 1551),
(15298, 'Shivhar', 101, 1551),
(15299, 'Silao', 101, 1551),
(15300, 'Sitamarhi', 101, 1551),
(15301, 'Siwan', 101, 1551),
(15302, 'Sonepur', 101, 1551),
(15303, 'Sultanganj', 101, 1551),
(15304, 'Supaul', 101, 1551),
(15305, 'Teghra', 101, 1551),
(15306, 'Tekari', 101, 1551),
(15307, 'Thakurganj', 101, 1551),
(15308, 'Vaishali', 101, 1551),
(15309, 'Waris Aliganj', 101, 1551),
(15310, 'Chandigarh', 101, 1552),
(15311, 'Ahiwara', 101, 1553),
(15312, 'Akaltara', 101, 1553),
(15313, 'Ambagarh Chauki', 101, 1553),
(15314, 'Ambikapur', 101, 1553),
(15315, 'Arang', 101, 1553),
(15316, 'Bade Bacheli', 101, 1553),
(15317, 'Bagbahara', 101, 1553),
(15318, 'Baikunthpur', 101, 1553),
(15319, 'Balod', 101, 1553),
(15320, 'Baloda', 101, 1553),
(15321, 'Baloda Bazar', 101, 1553),
(15322, 'Banarsi', 101, 1553),
(15323, 'Basna', 101, 1553),
(15324, 'Bemetra', 101, 1553),
(15325, 'Bhanpuri', 101, 1553),
(15326, 'Bhatapara', 101, 1553),
(15327, 'Bhatgaon', 101, 1553),
(15328, 'Bhilai', 101, 1553),
(15329, 'Bilaspur', 101, 1553),
(15330, 'Bilha', 101, 1553),
(15331, 'Birgaon', 101, 1553),
(15332, 'Bodri', 101, 1553),
(15333, 'Champa', 101, 1553),
(15334, 'Charcha', 101, 1553),
(15335, 'Charoda', 101, 1553),
(15336, 'Chhuikhadan', 101, 1553),
(15337, 'Chirmiri', 101, 1553),
(15338, 'Dantewada', 101, 1553),
(15339, 'Deori', 101, 1553),
(15340, 'Dhamdha', 101, 1553),
(15341, 'Dhamtari', 101, 1553),
(15342, 'Dharamjaigarh', 101, 1553),
(15343, 'Dipka', 101, 1553),
(15344, 'Doman Hill Colliery', 101, 1553),
(15345, 'Dongargaon', 101, 1553),
(15346, 'Dongragarh', 101, 1553),
(15347, 'Durg', 101, 1553),
(15348, 'Frezarpur', 101, 1553),
(15349, 'Gandai', 101, 1553),
(15350, 'Gariaband', 101, 1553),
(15351, 'Gaurela', 101, 1553),
(15352, 'Gelhapani', 101, 1553),
(15353, 'Gharghoda', 101, 1553),
(15354, 'Gidam', 101, 1553),
(15355, 'Gobra Nawapara', 101, 1553),
(15356, 'Gogaon', 101, 1553),
(15357, 'Hatkachora', 101, 1553),
(15358, 'Jagdalpur', 101, 1553),
(15359, 'Jamui', 101, 1553),
(15360, 'Jashpurnagar', 101, 1553),
(15361, 'Jhagrakhand', 101, 1553),
(15362, 'Kanker', 101, 1553),
(15363, 'Katghora', 101, 1553),
(15364, 'Kawardha', 101, 1553),
(15365, 'Khairagarh', 101, 1553),
(15366, 'Khamhria', 101, 1553),
(15367, 'Kharod', 101, 1553),
(15368, 'Kharsia', 101, 1553),
(15369, 'Khonga Pani', 101, 1553),
(15370, 'Kirandu', 101, 1553),
(15371, 'Kirandul', 101, 1553),
(15372, 'Kohka', 101, 1553),
(15373, 'Kondagaon', 101, 1553),
(15374, 'Korba', 101, 1553),
(15375, 'Korea', 101, 1553),
(15376, 'Koria Block', 101, 1553),
(15377, 'Kota', 101, 1553),
(15378, 'Kumhari', 101, 1553),
(15379, 'Kumud Katta', 101, 1553),
(15380, 'Kurasia', 101, 1553),
(15381, 'Kurud', 101, 1553),
(15382, 'Lingiyadih', 101, 1553),
(15383, 'Lormi', 101, 1553),
(15384, 'Mahasamund', 101, 1553),
(15385, 'Mahendragarh', 101, 1553),
(15386, 'Mehmand', 101, 1553),
(15387, 'Mongra', 101, 1553),
(15388, 'Mowa', 101, 1553),
(15389, 'Mungeli', 101, 1553),
(15390, 'Nailajanjgir', 101, 1553),
(15391, 'Namna Kalan', 101, 1553),
(15392, 'Naya Baradwar', 101, 1553),
(15393, 'Pandariya', 101, 1553),
(15394, 'Patan', 101, 1553),
(15395, 'Pathalgaon', 101, 1553),
(15396, 'Pendra', 101, 1553),
(15397, 'Phunderdihari', 101, 1553),
(15398, 'Pithora', 101, 1553),
(15399, 'Raigarh', 101, 1553),
(15400, 'Raipur', 101, 1553),
(15401, 'Rajgamar', 101, 1553),
(15402, 'Rajhara', 101, 1553),
(15403, 'Rajnandgaon', 101, 1553),
(15404, 'Ramanuj Ganj', 101, 1553),
(15405, 'Ratanpur', 101, 1553),
(15406, 'Sakti', 101, 1553),
(15407, 'Saraipali', 101, 1553),
(15408, 'Sarajpur', 101, 1553),
(15409, 'Sarangarh', 101, 1553),
(15410, 'Shivrinarayan', 101, 1553),
(15411, 'Simga', 101, 1553),
(15412, 'Sirgiti', 101, 1553),
(15413, 'Takhatpur', 101, 1553),
(15414, 'Telgaon', 101, 1553),
(15415, 'Tildanewra', 101, 1553),
(15416, 'Urla', 101, 1553),
(15417, 'Vishrampur', 101, 1553),
(15418, 'Amli', 101, 1554),
(15419, 'Silvassa', 101, 1554),
(15420, 'Daman', 101, 1555),
(15421, 'Diu', 101, 1555),
(15422, 'Delhi', 101, 1556),
(15423, 'New Delhi', 101, 1556),
(15424, 'Aldona', 101, 1557),
(15425, 'Altinho', 101, 1557),
(15426, 'Aquem', 101, 1557),
(15427, 'Arpora', 101, 1557),
(15428, 'Bambolim', 101, 1557),
(15429, 'Bandora', 101, 1557),
(15430, 'Bardez', 101, 1557),
(15431, 'Benaulim', 101, 1557),
(15432, 'Betora', 101, 1557),
(15433, 'Bicholim', 101, 1557),
(15434, 'Calapor', 101, 1557),
(15435, 'Candolim', 101, 1557),
(15436, 'Caranzalem', 101, 1557),
(15437, 'Carapur', 101, 1557),
(15438, 'Chicalim', 101, 1557),
(15439, 'Chimbel', 101, 1557),
(15440, 'Chinchinim', 101, 1557),
(15441, 'Colvale', 101, 1557),
(15442, 'Corlim', 101, 1557),
(15443, 'Cortalim', 101, 1557),
(15444, 'Cuncolim', 101, 1557),
(15445, 'Curchorem', 101, 1557),
(15446, 'Curti', 101, 1557),
(15447, 'Davorlim', 101, 1557),
(15448, 'Dona Paula', 101, 1557),
(15449, 'Goa', 101, 1557),
(15450, 'Guirim', 101, 1557),
(15451, 'Jua', 101, 1557),
(15452, 'Kalangat', 101, 1557),
(15453, 'Kankon', 101, 1557),
(15454, 'Kundaim', 101, 1557),
(15455, 'Loutulim', 101, 1557),
(15456, 'Madgaon', 101, 1557),
(15457, 'Mapusa', 101, 1557),
(15458, 'Margao', 101, 1557),
(15459, 'Margaon', 101, 1557),
(15460, 'Miramar', 101, 1557),
(15461, 'Morjim', 101, 1557),
(15462, 'Mormugao', 101, 1557),
(15463, 'Navelim', 101, 1557),
(15464, 'Pale', 101, 1557),
(15465, 'Panaji', 101, 1557),
(15466, 'Parcem', 101, 1557),
(15467, 'Parra', 101, 1557),
(15468, 'Penha de Franca', 101, 1557),
(15469, 'Pernem', 101, 1557),
(15470, 'Pilerne', 101, 1557),
(15471, 'Pissurlem', 101, 1557),
(15472, 'Ponda', 101, 1557),
(15473, 'Porvorim', 101, 1557),
(15474, 'Quepem', 101, 1557),
(15475, 'Queula', 101, 1557),
(15476, 'Raia', 101, 1557),
(15477, 'Reis Magos', 101, 1557),
(15478, 'Salcette', 101, 1557),
(15479, 'Saligao', 101, 1557),
(15480, 'Sancoale', 101, 1557),
(15481, 'Sanguem', 101, 1557),
(15482, 'Sanquelim', 101, 1557),
(15483, 'Sanvordem', 101, 1557),
(15484, 'Sao Jose-de-Areal', 101, 1557),
(15485, 'Sattari', 101, 1557),
(15486, 'Serula', 101, 1557),
(15487, 'Sinquerim', 101, 1557),
(15488, 'Siolim', 101, 1557),
(15489, 'Taleigao', 101, 1557),
(15490, 'Tivim', 101, 1557),
(15491, 'Valpoi', 101, 1557),
(15492, 'Varca', 101, 1557),
(15493, 'Vasco', 101, 1557),
(15494, 'Verna', 101, 1557),
(15495, 'Abrama', 101, 1558),
(15496, 'Adalaj', 101, 1558),
(15497, 'Adityana', 101, 1558),
(15498, 'Advana', 101, 1558),
(15499, 'Ahmedabad', 101, 1558),
(15500, 'Ahwa', 101, 1558),
(15501, 'Alang', 101, 1558),
(15502, 'Ambaji', 101, 1558),
(15503, 'Ambaliyasan', 101, 1558),
(15504, 'Amod', 101, 1558),
(15505, 'Amreli', 101, 1558),
(15506, 'Amroli', 101, 1558),
(15507, 'Anand', 101, 1558),
(15508, 'Andada', 101, 1558),
(15509, 'Anjar', 101, 1558),
(15510, 'Anklav', 101, 1558),
(15511, 'Ankleshwar', 101, 1558),
(15512, 'Anklesvar INA', 101, 1558),
(15513, 'Antaliya', 101, 1558),
(15514, 'Arambhada', 101, 1558),
(15515, 'Asarma', 101, 1558),
(15516, 'Atul', 101, 1558),
(15517, 'Babra', 101, 1558),
(15518, 'Bag-e-Firdosh', 101, 1558),
(15519, 'Bagasara', 101, 1558),
(15520, 'Bahadarpar', 101, 1558),
(15521, 'Bajipura', 101, 1558),
(15522, 'Bajva', 101, 1558),
(15523, 'Balasinor', 101, 1558),
(15524, 'Banaskantha', 101, 1558),
(15525, 'Bansda', 101, 1558),
(15526, 'Bantva', 101, 1558),
(15527, 'Bardoli', 101, 1558),
(15528, 'Barwala', 101, 1558),
(15529, 'Bayad', 101, 1558),
(15530, 'Bechar', 101, 1558),
(15531, 'Bedi', 101, 1558),
(15532, 'Beyt', 101, 1558),
(15533, 'Bhachau', 101, 1558),
(15534, 'Bhanvad', 101, 1558),
(15535, 'Bharuch', 101, 1558),
(15536, 'Bharuch INA', 101, 1558),
(15537, 'Bhavnagar', 101, 1558),
(15538, 'Bhayavadar', 101, 1558),
(15539, 'Bhestan', 101, 1558),
(15540, 'Bhuj', 101, 1558),
(15541, 'Bilimora', 101, 1558),
(15542, 'Bilkha', 101, 1558),
(15543, 'Billimora', 101, 1558),
(15544, 'Bodakdev', 101, 1558),
(15545, 'Bodeli', 101, 1558),
(15546, 'Bopal', 101, 1558),
(15547, 'Boria', 101, 1558),
(15548, 'Boriavi', 101, 1558),
(15549, 'Borsad', 101, 1558),
(15550, 'Botad', 101, 1558),
(15551, 'Cambay', 101, 1558),
(15552, 'Chaklasi', 101, 1558),
(15553, 'Chala', 101, 1558),
(15554, 'Chalala', 101, 1558),
(15555, 'Chalthan', 101, 1558),
(15556, 'Chanasma', 101, 1558),
(15557, 'Chandisar', 101, 1558),
(15558, 'Chandkheda', 101, 1558),
(15559, 'Chanod', 101, 1558),
(15560, 'Chaya', 101, 1558),
(15561, 'Chenpur', 101, 1558),
(15562, 'Chhapi', 101, 1558),
(15563, 'Chhaprabhatha', 101, 1558),
(15564, 'Chhatral', 101, 1558),
(15565, 'Chhota Udepur', 101, 1558),
(15566, 'Chikhli', 101, 1558),
(15567, 'Chiloda', 101, 1558),
(15568, 'Chorvad', 101, 1558),
(15569, 'Chotila', 101, 1558),
(15570, 'Dabhoi', 101, 1558),
(15571, 'Dadara', 101, 1558),
(15572, 'Dahod', 101, 1558),
(15573, 'Dakor', 101, 1558),
(15574, 'Damnagar', 101, 1558),
(15575, 'Deesa', 101, 1558),
(15576, 'Delvada', 101, 1558),
(15577, 'Devgadh Baria', 101, 1558),
(15578, 'Devsar', 101, 1558),
(15579, 'Dhandhuka', 101, 1558),
(15580, 'Dhanera', 101, 1558),
(15581, 'Dhangdhra', 101, 1558),
(15582, 'Dhansura', 101, 1558),
(15583, 'Dharampur', 101, 1558),
(15584, 'Dhari', 101, 1558),
(15585, 'Dhola', 101, 1558),
(15586, 'Dholka', 101, 1558),
(15587, 'Dholka Rural', 101, 1558),
(15588, 'Dhoraji', 101, 1558),
(15589, 'Dhrangadhra', 101, 1558),
(15590, 'Dhrol', 101, 1558),
(15591, 'Dhuva', 101, 1558),
(15592, 'Dhuwaran', 101, 1558),
(15593, 'Digvijaygram', 101, 1558),
(15594, 'Disa', 101, 1558),
(15595, 'Dungar', 101, 1558),
(15596, 'Dungarpur', 101, 1558),
(15597, 'Dungra', 101, 1558),
(15598, 'Dwarka', 101, 1558),
(15599, 'Flelanganj', 101, 1558),
(15600, 'GSFC Complex', 101, 1558),
(15601, 'Gadhda', 101, 1558),
(15602, 'Gandevi', 101, 1558),
(15603, 'Gandhidham', 101, 1558),
(15604, 'Gandhinagar', 101, 1558),
(15605, 'Gariadhar', 101, 1558),
(15606, 'Ghogha', 101, 1558),
(15607, 'Godhra', 101, 1558),
(15608, 'Gondal', 101, 1558),
(15609, 'Hajira INA', 101, 1558),
(15610, 'Halol', 101, 1558),
(15611, 'Halvad', 101, 1558),
(15612, 'Hansot', 101, 1558),
(15613, 'Harij', 101, 1558),
(15614, 'Himatnagar', 101, 1558),
(15615, 'Ichchhapor', 101, 1558),
(15616, 'Idar', 101, 1558),
(15617, 'Jafrabad', 101, 1558),
(15618, 'Jalalpore', 101, 1558),
(15619, 'Jambusar', 101, 1558),
(15620, 'Jamjodhpur', 101, 1558),
(15621, 'Jamnagar', 101, 1558),
(15622, 'Jasdan', 101, 1558),
(15623, 'Jawaharnagar', 101, 1558),
(15624, 'Jetalsar', 101, 1558),
(15625, 'Jetpur', 101, 1558),
(15626, 'Jodiya', 101, 1558),
(15627, 'Joshipura', 101, 1558),
(15628, 'Junagadh', 101, 1558),
(15629, 'Kadi', 101, 1558),
(15630, 'Kadodara', 101, 1558),
(15631, 'Kalavad', 101, 1558),
(15632, 'Kali', 101, 1558),
(15633, 'Kaliawadi', 101, 1558),
(15634, 'Kalol', 101, 1558),
(15635, 'Kalol INA', 101, 1558),
(15636, 'Kandla', 101, 1558),
(15637, 'Kanjari', 101, 1558),
(15638, 'Kanodar', 101, 1558),
(15639, 'Kapadwanj', 101, 1558),
(15640, 'Karachiya', 101, 1558),
(15641, 'Karamsad', 101, 1558),
(15642, 'Karjan', 101, 1558),
(15643, 'Kathial', 101, 1558),
(15644, 'Kathor', 101, 1558),
(15645, 'Katpar', 101, 1558),
(15646, 'Kavant', 101, 1558),
(15647, 'Keshod', 101, 1558),
(15648, 'Kevadiya', 101, 1558),
(15649, 'Khambhaliya', 101, 1558),
(15650, 'Khambhat', 101, 1558),
(15651, 'Kharaghoda', 101, 1558),
(15652, 'Khed Brahma', 101, 1558),
(15653, 'Kheda', 101, 1558),
(15654, 'Kheralu', 101, 1558),
(15655, 'Kodinar', 101, 1558),
(15656, 'Kosamba', 101, 1558),
(15657, 'Kundla', 101, 1558),
(15658, 'Kutch', 101, 1558),
(15659, 'Kutiyana', 101, 1558),
(15660, 'Lakhtar', 101, 1558),
(15661, 'Lalpur', 101, 1558),
(15662, 'Lambha', 101, 1558),
(15663, 'Lathi', 101, 1558),
(15664, 'Limbdi', 101, 1558),
(15665, 'Limla', 101, 1558),
(15666, 'Lunavada', 101, 1558),
(15667, 'Madhapar', 101, 1558),
(15668, 'Maflipur', 101, 1558),
(15669, 'Mahemdavad', 101, 1558),
(15670, 'Mahudha', 101, 1558),
(15671, 'Mahuva', 101, 1558),
(15672, 'Mahuvar', 101, 1558),
(15673, 'Makarba', 101, 1558),
(15674, 'Makarpura', 101, 1558),
(15675, 'Makassar', 101, 1558),
(15676, 'Maktampur', 101, 1558),
(15677, 'Malia', 101, 1558),
(15678, 'Malpur', 101, 1558),
(15679, 'Manavadar', 101, 1558),
(15680, 'Mandal', 101, 1558),
(15681, 'Mandvi', 101, 1558),
(15682, 'Mangrol', 101, 1558),
(15683, 'Mansa', 101, 1558),
(15684, 'Meghraj', 101, 1558),
(15685, 'Mehsana', 101, 1558),
(15686, 'Mendarla', 101, 1558),
(15687, 'Mithapur', 101, 1558),
(15688, 'Modasa', 101, 1558),
(15689, 'Mogravadi', 101, 1558),
(15690, 'Morbi', 101, 1558),
(15691, 'Morvi', 101, 1558),
(15692, 'Mundra', 101, 1558),
(15693, 'Nadiad', 101, 1558),
(15694, 'Naliya', 101, 1558),
(15695, 'Nanakvada', 101, 1558),
(15696, 'Nandej', 101, 1558),
(15697, 'Nandesari', 101, 1558),
(15698, 'Nandesari INA', 101, 1558),
(15699, 'Naroda', 101, 1558),
(15700, 'Navagadh', 101, 1558),
(15701, 'Navagam Ghed', 101, 1558),
(15702, 'Navsari', 101, 1558),
(15703, 'Ode', 101, 1558),
(15704, 'Okaf', 101, 1558),
(15705, 'Okha', 101, 1558),
(15706, 'Olpad', 101, 1558),
(15707, 'Paddhari', 101, 1558),
(15708, 'Padra', 101, 1558),
(15709, 'Palanpur', 101, 1558),
(15710, 'Palej', 101, 1558),
(15711, 'Pali', 101, 1558),
(15712, 'Palitana', 101, 1558),
(15713, 'Paliyad', 101, 1558),
(15714, 'Pandesara', 101, 1558),
(15715, 'Panoli', 101, 1558),
(15716, 'Pardi', 101, 1558),
(15717, 'Parnera', 101, 1558),
(15718, 'Parvat', 101, 1558),
(15719, 'Patan', 101, 1558),
(15720, 'Patdi', 101, 1558),
(15721, 'Petlad', 101, 1558),
(15722, 'Petrochemical Complex', 101, 1558),
(15723, 'Porbandar', 101, 1558),
(15724, 'Prantij', 101, 1558),
(15725, 'Radhanpur', 101, 1558),
(15726, 'Raiya', 101, 1558),
(15727, 'Rajkot', 101, 1558),
(15728, 'Rajpipla', 101, 1558),
(15729, 'Rajula', 101, 1558),
(15730, 'Ramod', 101, 1558),
(15731, 'Ranavav', 101, 1558),
(15732, 'Ranoli', 101, 1558),
(15733, 'Rapar', 101, 1558),
(15734, 'Sahij', 101, 1558),
(15735, 'Salaya', 101, 1558),
(15736, 'Sanand', 101, 1558),
(15737, 'Sankheda', 101, 1558),
(15738, 'Santrampur', 101, 1558),
(15739, 'Saribujrang', 101, 1558),
(15740, 'Sarigam INA', 101, 1558),
(15741, 'Sayan', 101, 1558),
(15742, 'Sayla', 101, 1558),
(15743, 'Shahpur', 101, 1558),
(15744, 'Shahwadi', 101, 1558),
(15745, 'Shapar', 101, 1558),
(15746, 'Shivrajpur', 101, 1558),
(15747, 'Siddhapur', 101, 1558),
(15748, 'Sidhpur', 101, 1558),
(15749, 'Sihor', 101, 1558),
(15750, 'Sika', 101, 1558),
(15751, 'Singarva', 101, 1558),
(15752, 'Sinor', 101, 1558),
(15753, 'Sojitra', 101, 1558),
(15754, 'Sola', 101, 1558),
(15755, 'Songadh', 101, 1558),
(15756, 'Suraj Karadi', 101, 1558),
(15757, 'Surat', 101, 1558),
(15758, 'Surendranagar', 101, 1558),
(15759, 'Talaja', 101, 1558),
(15760, 'Talala', 101, 1558),
(15761, 'Talod', 101, 1558),
(15762, 'Tankara', 101, 1558),
(15763, 'Tarsali', 101, 1558),
(15764, 'Thangadh', 101, 1558),
(15765, 'Tharad', 101, 1558),
(15766, 'Thasra', 101, 1558),
(15767, 'Udyognagar', 101, 1558),
(15768, 'Ukai', 101, 1558),
(15769, 'Umbergaon', 101, 1558),
(15770, 'Umbergaon INA', 101, 1558),
(15771, 'Umrala', 101, 1558),
(15772, 'Umreth', 101, 1558),
(15773, 'Un', 101, 1558),
(15774, 'Una', 101, 1558),
(15775, 'Unjha', 101, 1558),
(15776, 'Upleta', 101, 1558),
(15777, 'Utran', 101, 1558),
(15778, 'Uttarsanda', 101, 1558),
(15779, 'V.U. Nagar', 101, 1558),
(15780, 'V.V. Nagar', 101, 1558),
(15781, 'Vadia', 101, 1558),
(15782, 'Vadla', 101, 1558),
(15783, 'Vadnagar', 101, 1558),
(15784, 'Vadodara', 101, 1558),
(15785, 'Vaghodia INA', 101, 1558),
(15786, 'Valbhipur', 101, 1558),
(15787, 'Vallabh Vidyanagar', 101, 1558),
(15788, 'Valsad', 101, 1558),
(15789, 'Valsad INA', 101, 1558),
(15790, 'Vanthali', 101, 1558),
(15791, 'Vapi', 101, 1558),
(15792, 'Vapi INA', 101, 1558),
(15793, 'Vartej', 101, 1558),
(15794, 'Vasad', 101, 1558),
(15795, 'Vasna Borsad INA', 101, 1558),
(15796, 'Vaso', 101, 1558),
(15797, 'Veraval', 101, 1558),
(15798, 'Vidyanagar', 101, 1558),
(15799, 'Vijalpor', 101, 1558),
(15800, 'Vijapur', 101, 1558),
(15801, 'Vinchhiya', 101, 1558),
(15802, 'Vinzol', 101, 1558),
(15803, 'Virpur', 101, 1558),
(15804, 'Visavadar', 101, 1558),
(15805, 'Visnagar', 101, 1558),
(15806, 'Vyara', 101, 1558),
(15807, 'Wadhwan', 101, 1558),
(15808, 'Waghai', 101, 1558),
(15809, 'Waghodia', 101, 1558),
(15810, 'Wankaner', 101, 1558),
(15811, 'Zalod', 101, 1558),
(15812, 'Ambala', 101, 1559),
(15813, 'Ambala Cantt', 101, 1559),
(15814, 'Asan Khurd', 101, 1559),
(15815, 'Asandh', 101, 1559),
(15816, 'Ateli', 101, 1559),
(15817, 'Babiyal', 101, 1559),
(15818, 'Bahadurgarh', 101, 1559),
(15819, 'Ballabgarh', 101, 1559),
(15820, 'Barwala', 101, 1559),
(15821, 'Bawal', 101, 1559),
(15822, 'Bawani Khera', 101, 1559),
(15823, 'Beri', 101, 1559),
(15824, 'Bhiwani', 101, 1559),
(15825, 'Bilaspur', 101, 1559),
(15826, 'Buria', 101, 1559),
(15827, 'Charkhi Dadri', 101, 1559),
(15828, 'Chhachhrauli', 101, 1559),
(15829, 'Chita', 101, 1559),
(15830, 'Dabwali', 101, 1559),
(15831, 'Dharuhera', 101, 1559),
(15832, 'Dundahera', 101, 1559),
(15833, 'Ellenabad', 101, 1559),
(15834, 'Farakhpur', 101, 1559),
(15835, 'Faridabad', 101, 1559),
(15836, 'Farrukhnagar', 101, 1559),
(15837, 'Fatehabad', 101, 1559),
(15838, 'Firozpur Jhirka', 101, 1559),
(15839, 'Gannaur', 101, 1559),
(15840, 'Ghraunda', 101, 1559),
(15841, 'Gohana', 101, 1559),
(15842, 'Gurgaon', 101, 1559),
(15843, 'Haileymandi', 101, 1559),
(15844, 'Hansi', 101, 1559),
(15845, 'Hasanpur', 101, 1559),
(15846, 'Hathin', 101, 1559),
(15847, 'Hisar', 101, 1559),
(15848, 'Hissar', 101, 1559),
(15849, 'Hodal', 101, 1559),
(15850, 'Indri', 101, 1559),
(15851, 'Jagadhri', 101, 1559),
(15852, 'Jakhal Mandi', 101, 1559),
(15853, 'Jhajjar', 101, 1559),
(15854, 'Jind', 101, 1559),
(15855, 'Julana', 101, 1559),
(15856, 'Kaithal', 101, 1559),
(15857, 'Kalanur', 101, 1559),
(15858, 'Kalanwali', 101, 1559),
(15859, 'Kalayat', 101, 1559),
(15860, 'Kalka', 101, 1559),
(15861, 'Kanina', 101, 1559),
(15862, 'Kansepur', 101, 1559),
(15863, 'Kardhan', 101, 1559),
(15864, 'Karnal', 101, 1559),
(15865, 'Kharkhoda', 101, 1559),
(15866, 'Kheri Sampla', 101, 1559),
(15867, 'Kundli', 101, 1559),
(15868, 'Kurukshetra', 101, 1559),
(15869, 'Ladrawan', 101, 1559),
(15870, 'Ladwa', 101, 1559),
(15871, 'Loharu', 101, 1559),
(15872, 'Maham', 101, 1559),
(15873, 'Mahendragarh', 101, 1559),
(15874, 'Mustafabad', 101, 1559),
(15875, 'Nagai Chaudhry', 101, 1559),
(15876, 'Narayangarh', 101, 1559),
(15877, 'Narnaul', 101, 1559),
(15878, 'Narnaund', 101, 1559),
(15879, 'Narwana', 101, 1559),
(15880, 'Nilokheri', 101, 1559),
(15881, 'Nuh', 101, 1559),
(15882, 'Palwal', 101, 1559),
(15883, 'Panchkula', 101, 1559),
(15884, 'Panipat', 101, 1559),
(15885, 'Panipat Taraf Ansar', 101, 1559),
(15886, 'Panipat Taraf Makhdum Zadgan', 101, 1559),
(15887, 'Panipat Taraf Rajputan', 101, 1559),
(15888, 'Pehowa', 101, 1559),
(15889, 'Pinjaur', 101, 1559),
(15890, 'Punahana', 101, 1559),
(15891, 'Pundri', 101, 1559),
(15892, 'Radaur', 101, 1559),
(15893, 'Raipur Rani', 101, 1559),
(15894, 'Rania', 101, 1559),
(15895, 'Ratiya', 101, 1559),
(15896, 'Rewari', 101, 1559),
(15897, 'Rohtak', 101, 1559),
(15898, 'Ropar', 101, 1559),
(15899, 'Sadauri', 101, 1559),
(15900, 'Safidon', 101, 1559),
(15901, 'Samalkha', 101, 1559),
(15902, 'Sankhol', 101, 1559),
(15903, 'Sasauli', 101, 1559),
(15904, 'Shahabad', 101, 1559),
(15905, 'Sirsa', 101, 1559),
(15906, 'Siwani', 101, 1559),
(15907, 'Sohna', 101, 1559),
(15908, 'Sonipat', 101, 1559),
(15909, 'Sukhrali', 101, 1559),
(15910, 'Taoru', 101, 1559),
(15911, 'Taraori', 101, 1559),
(15912, 'Tauru', 101, 1559),
(15913, 'Thanesar', 101, 1559),
(15914, 'Tilpat', 101, 1559),
(15915, 'Tohana', 101, 1559),
(15916, 'Tosham', 101, 1559),
(15917, 'Uchana', 101, 1559),
(15918, 'Uklana Mandi', 101, 1559),
(15919, 'Uncha Siwana', 101, 1559),
(15920, 'Yamunanagar', 101, 1559),
(15921, 'Arki', 101, 1560),
(15922, 'Baddi', 101, 1560),
(15923, 'Bakloh', 101, 1560),
(15924, 'Banjar', 101, 1560),
(15925, 'Bhota', 101, 1560),
(15926, 'Bhuntar', 101, 1560),
(15927, 'Bilaspur', 101, 1560),
(15928, 'Chamba', 101, 1560),
(15929, 'Chaupal', 101, 1560),
(15930, 'Chuari Khas', 101, 1560),
(15931, 'Dagshai', 101, 1560),
(15932, 'Dalhousie', 101, 1560),
(15933, 'Dalhousie Cantonment', 101, 1560),
(15934, 'Damtal', 101, 1560),
(15935, 'Daulatpur', 101, 1560),
(15936, 'Dera Gopipur', 101, 1560),
(15937, 'Dhalli', 101, 1560),
(15938, 'Dharamshala', 101, 1560),
(15939, 'Gagret', 101, 1560),
(15940, 'Ghamarwin', 101, 1560),
(15941, 'Hamirpur', 101, 1560),
(15942, 'Jawala Mukhi', 101, 1560),
(15943, 'Jogindarnagar', 101, 1560),
(15944, 'Jubbal', 101, 1560),
(15945, 'Jutogh', 101, 1560),
(15946, 'Kala Amb', 101, 1560),
(15947, 'Kalpa', 101, 1560),
(15948, 'Kangra', 101, 1560),
(15949, 'Kasauli', 101, 1560),
(15950, 'Kot Khai', 101, 1560),
(15951, 'Kullu', 101, 1560),
(15952, 'Kulu', 101, 1560),
(15953, 'Manali', 101, 1560),
(15954, 'Mandi', 101, 1560),
(15955, 'Mant Khas', 101, 1560),
(15956, 'Mehatpur Basdehra', 101, 1560),
(15957, 'Nadaun', 101, 1560),
(15958, 'Nagrota', 101, 1560),
(15959, 'Nahan', 101, 1560),
(15960, 'Naina Devi', 101, 1560),
(15961, 'Nalagarh', 101, 1560),
(15962, 'Narkanda', 101, 1560),
(15963, 'Nurpur', 101, 1560),
(15964, 'Palampur', 101, 1560),
(15965, 'Pandoh', 101, 1560),
(15966, 'Paonta Sahib', 101, 1560),
(15967, 'Parwanoo', 101, 1560),
(15968, 'Parwanu', 101, 1560),
(15969, 'Rajgarh', 101, 1560),
(15970, 'Rampur', 101, 1560),
(15971, 'Rawalsar', 101, 1560),
(15972, 'Rohru', 101, 1560),
(15973, 'Sabathu', 101, 1560),
(15974, 'Santokhgarh', 101, 1560),
(15975, 'Sarahan', 101, 1560),
(15976, 'Sarka Ghat', 101, 1560),
(15977, 'Seoni', 101, 1560),
(15978, 'Shimla', 101, 1560),
(15979, 'Sirmaur', 101, 1560),
(15980, 'Solan', 101, 1560),
(15981, 'Solon', 101, 1560),
(15982, 'Sundarnagar', 101, 1560),
(15983, 'Sundernagar', 101, 1560),
(15984, 'Talai', 101, 1560),
(15985, 'Theog', 101, 1560),
(15986, 'Tira Sujanpur', 101, 1560),
(15987, 'Una', 101, 1560),
(15988, 'Yol', 101, 1560),
(15989, 'Achabal', 101, 1561),
(15990, 'Akhnur', 101, 1561),
(15991, 'Anantnag', 101, 1561),
(15992, 'Arnia', 101, 1561),
(15993, 'Awantipora', 101, 1561),
(15994, 'Badami Bagh', 101, 1561),
(15995, 'Bandipur', 101, 1561),
(15996, 'Banihal', 101, 1561),
(15997, 'Baramula', 101, 1561),
(15998, 'Baramulla', 101, 1561),
(15999, 'Bari Brahmana', 101, 1561),
(16000, 'Bashohli', 101, 1561),
(16001, 'Batote', 101, 1561),
(16002, 'Bhaderwah', 101, 1561),
(16003, 'Bijbiara', 101, 1561),
(16004, 'Billawar', 101, 1561),
(16005, 'Birwah', 101, 1561),
(16006, 'Bishna', 101, 1561),
(16007, 'Budgam', 101, 1561),
(16008, 'Charari Sharief', 101, 1561),
(16009, 'Chenani', 101, 1561),
(16010, 'Doda', 101, 1561),
(16011, 'Duru-Verinag', 101, 1561),
(16012, 'Gandarbat', 101, 1561),
(16013, 'Gho Manhasan', 101, 1561),
(16014, 'Gorah Salathian', 101, 1561),
(16015, 'Gulmarg', 101, 1561),
(16016, 'Hajan', 101, 1561),
(16017, 'Handwara', 101, 1561),
(16018, 'Hiranagar', 101, 1561),
(16019, 'Jammu', 101, 1561),
(16020, 'Jammu Cantonment', 101, 1561),
(16021, 'Jammu Tawi', 101, 1561),
(16022, 'Jourian', 101, 1561),
(16023, 'Kargil', 101, 1561),
(16024, 'Kathua', 101, 1561),
(16025, 'Katra', 101, 1561),
(16026, 'Khan Sahib', 101, 1561),
(16027, 'Khour', 101, 1561),
(16028, 'Khrew', 101, 1561),
(16029, 'Kishtwar', 101, 1561),
(16030, 'Kud', 101, 1561),
(16031, 'Kukernag', 101, 1561),
(16032, 'Kulgam', 101, 1561),
(16033, 'Kunzer', 101, 1561),
(16034, 'Kupwara', 101, 1561),
(16035, 'Lakhenpur', 101, 1561),
(16036, 'Leh', 101, 1561),
(16037, 'Magam', 101, 1561),
(16038, 'Mattan', 101, 1561),
(16039, 'Naushehra', 101, 1561),
(16040, 'Pahalgam', 101, 1561),
(16041, 'Pampore', 101, 1561),
(16042, 'Parole', 101, 1561),
(16043, 'Pattan', 101, 1561),
(16044, 'Pulwama', 101, 1561),
(16045, 'Punch', 101, 1561),
(16046, 'Qazigund', 101, 1561),
(16047, 'Rajauri', 101, 1561),
(16048, 'Ramban', 101, 1561),
(16049, 'Ramgarh', 101, 1561),
(16050, 'Ramnagar', 101, 1561),
(16051, 'Ranbirsingh Pora', 101, 1561),
(16052, 'Reasi', 101, 1561),
(16053, 'Rehambal', 101, 1561),
(16054, 'Samba', 101, 1561),
(16055, 'Shupiyan', 101, 1561),
(16056, 'Sopur', 101, 1561),
(16057, 'Srinagar', 101, 1561),
(16058, 'Sumbal', 101, 1561),
(16059, 'Sunderbani', 101, 1561),
(16060, 'Talwara', 101, 1561),
(16061, 'Thanamandi', 101, 1561),
(16062, 'Tral', 101, 1561),
(16063, 'Udhampur', 101, 1561),
(16064, 'Uri', 101, 1561),
(16065, 'Vijaypur', 101, 1561),
(16066, 'Adityapur', 101, 1562),
(16067, 'Amlabad', 101, 1562),
(16068, 'Angarpathar', 101, 1562),
(16069, 'Ara', 101, 1562),
(16070, 'Babua Kalan', 101, 1562),
(16071, 'Bagbahra', 101, 1562),
(16072, 'Baliapur', 101, 1562),
(16073, 'Baliari', 101, 1562),
(16074, 'Balkundra', 101, 1562),
(16075, 'Bandhgora', 101, 1562),
(16076, 'Barajamda', 101, 1562),
(16077, 'Barhi', 101, 1562),
(16078, 'Barka Kana', 101, 1562),
(16079, 'Barki Saraiya', 101, 1562),
(16080, 'Barughutu', 101, 1562),
(16081, 'Barwadih', 101, 1562),
(16082, 'Basaria', 101, 1562),
(16083, 'Basukinath', 101, 1562),
(16084, 'Bermo', 101, 1562),
(16085, 'Bhagatdih', 101, 1562),
(16086, 'Bhaurah', 101, 1562),
(16087, 'Bhojudih', 101, 1562),
(16088, 'Bhuli', 101, 1562),
(16089, 'Bokaro', 101, 1562),
(16090, 'Borio Bazar', 101, 1562),
(16091, 'Bundu', 101, 1562),
(16092, 'Chaibasa', 101, 1562),
(16093, 'Chaitudih', 101, 1562),
(16094, 'Chakradharpur', 101, 1562),
(16095, 'Chakulia', 101, 1562),
(16096, 'Chandaur', 101, 1562),
(16097, 'Chandil', 101, 1562),
(16098, 'Chandrapura', 101, 1562),
(16099, 'Chas', 101, 1562),
(16100, 'Chatra', 101, 1562),
(16101, 'Chhatatanr', 101, 1562),
(16102, 'Chhotaputki', 101, 1562),
(16103, 'Chiria', 101, 1562),
(16104, 'Chirkunda', 101, 1562),
(16105, 'Churi', 101, 1562),
(16106, 'Daltenganj', 101, 1562),
(16107, 'Danguwapasi', 101, 1562),
(16108, 'Dari', 101, 1562),
(16109, 'Deoghar', 101, 1562),
(16110, 'Deorikalan', 101, 1562),
(16111, 'Devghar', 101, 1562),
(16112, 'Dhanbad', 101, 1562),
(16113, 'Dhanwar', 101, 1562),
(16114, 'Dhaunsar', 101, 1562),
(16115, 'Dugda', 101, 1562),
(16116, 'Dumarkunda', 101, 1562),
(16117, 'Dumka', 101, 1562),
(16118, 'Egarkunr', 101, 1562),
(16119, 'Gadhra', 101, 1562),
(16120, 'Garwa', 101, 1562),
(16121, 'Ghatsila', 101, 1562),
(16122, 'Ghorabandha', 101, 1562),
(16123, 'Gidi', 101, 1562),
(16124, 'Giridih', 101, 1562),
(16125, 'Gobindpur', 101, 1562),
(16126, 'Godda', 101, 1562),
(16127, 'Godhar', 101, 1562),
(16128, 'Golphalbari', 101, 1562),
(16129, 'Gomoh', 101, 1562),
(16130, 'Gua', 101, 1562),
(16131, 'Gumia', 101, 1562),
(16132, 'Gumla', 101, 1562),
(16133, 'Haludbani', 101, 1562),
(16134, 'Hazaribag', 101, 1562),
(16135, 'Hesla', 101, 1562),
(16136, 'Husainabad', 101, 1562),
(16137, 'Isri', 101, 1562),
(16138, 'Jadugora', 101, 1562),
(16139, 'Jagannathpur', 101, 1562),
(16140, 'Jamadoba', 101, 1562),
(16141, 'Jamshedpur', 101, 1562),
(16142, 'Jamtara', 101, 1562),
(16143, 'Jarangdih', 101, 1562),
(16144, 'Jaridih', 101, 1562),
(16145, 'Jasidih', 101, 1562),
(16146, 'Jena', 101, 1562),
(16147, 'Jharia', 101, 1562),
(16148, 'Jharia Khas', 101, 1562),
(16149, 'Jhinkpani', 101, 1562),
(16150, 'Jhumri Tilaiya', 101, 1562),
(16151, 'Jorapokhar', 101, 1562),
(16152, 'Jugsalai', 101, 1562),
(16153, 'Kailudih', 101, 1562),
(16154, 'Kalikapur', 101, 1562),
(16155, 'Kandra', 101, 1562),
(16156, 'Kanke', 101, 1562),
(16157, 'Katras', 101, 1562),
(16158, 'Kedla', 101, 1562),
(16159, 'Kenduadih', 101, 1562),
(16160, 'Kharkhari', 101, 1562),
(16161, 'Kharsawan', 101, 1562),
(16162, 'Khelari', 101, 1562),
(16163, 'Khunti', 101, 1562),
(16164, 'Kiri Buru', 101, 1562),
(16165, 'Kiriburu', 101, 1562),
(16166, 'Kodarma', 101, 1562),
(16167, 'Kuju', 101, 1562),
(16168, 'Kurpania', 101, 1562),
(16169, 'Kustai', 101, 1562),
(16170, 'Lakarka', 101, 1562),
(16171, 'Lapanga', 101, 1562),
(16172, 'Latehar', 101, 1562),
(16173, 'Lohardaga', 101, 1562),
(16174, 'Loiya', 101, 1562),
(16175, 'Loyabad', 101, 1562),
(16176, 'Madhupur', 101, 1562),
(16177, 'Mahesh Mundi', 101, 1562),
(16178, 'Maithon', 101, 1562),
(16179, 'Malkera', 101, 1562),
(16180, 'Mango', 101, 1562),
(16181, 'Manoharpur', 101, 1562),
(16182, 'Marma', 101, 1562),
(16183, 'Meghahatuburu Forest village', 101, 1562),
(16184, 'Mera', 101, 1562),
(16185, 'Meru', 101, 1562),
(16186, 'Mihijam', 101, 1562),
(16187, 'Mugma', 101, 1562),
(16188, 'Muri', 101, 1562),
(16189, 'Mushabani', 101, 1562),
(16190, 'Nagri Kalan', 101, 1562),
(16191, 'Netarhat', 101, 1562),
(16192, 'Nirsa', 101, 1562),
(16193, 'Noamundi', 101, 1562),
(16194, 'Okni', 101, 1562),
(16195, 'Orla', 101, 1562),
(16196, 'Pakaur', 101, 1562),
(16197, 'Palamau', 101, 1562),
(16198, 'Palawa', 101, 1562),
(16199, 'Panchet', 101, 1562),
(16200, 'Panrra', 101, 1562),
(16201, 'Paratdih', 101, 1562),
(16202, 'Pathardih', 101, 1562),
(16203, 'Patratu', 101, 1562),
(16204, 'Phusro', 101, 1562),
(16205, 'Pondar Kanali', 101, 1562),
(16206, 'Rajmahal', 101, 1562),
(16207, 'Ramgarh', 101, 1562),
(16208, 'Ranchi', 101, 1562),
(16209, 'Ray', 101, 1562),
(16210, 'Rehla', 101, 1562),
(16211, 'Religara', 101, 1562),
(16212, 'Rohraband', 101, 1562),
(16213, 'Sahibganj', 101, 1562),
(16214, 'Sahnidih', 101, 1562),
(16215, 'Saraidhela', 101, 1562),
(16216, 'Saraikela', 101, 1562),
(16217, 'Sarjamda', 101, 1562),
(16218, 'Saunda', 101, 1562),
(16219, 'Sewai', 101, 1562),
(16220, 'Sijhua', 101, 1562),
(16221, 'Sijua', 101, 1562),
(16222, 'Simdega', 101, 1562),
(16223, 'Sindari', 101, 1562),
(16224, 'Sinduria', 101, 1562),
(16225, 'Sini', 101, 1562),
(16226, 'Sirka', 101, 1562),
(16227, 'Siuliban', 101, 1562),
(16228, 'Surubera', 101, 1562),
(16229, 'Tati', 101, 1562),
(16230, 'Tenudam', 101, 1562),
(16231, 'Tisra', 101, 1562),
(16232, 'Topa', 101, 1562),
(16233, 'Topchanchi', 101, 1562),
(16234, 'Adityanagar', 101, 1563),
(16235, 'Adityapatna', 101, 1563),
(16236, 'Afzalpur', 101, 1563),
(16237, 'Ajjampur', 101, 1563),
(16238, 'Aland', 101, 1563),
(16239, 'Almatti Sitimani', 101, 1563),
(16240, 'Alnavar', 101, 1563),
(16241, 'Alur', 101, 1563),
(16242, 'Ambikanagara', 101, 1563),
(16243, 'Anekal', 101, 1563),
(16244, 'Ankola', 101, 1563),
(16245, 'Annigeri', 101, 1563),
(16246, 'Arkalgud', 101, 1563),
(16247, 'Arsikere', 101, 1563),
(16248, 'Athni', 101, 1563),
(16249, 'Aurad', 101, 1563),
(16250, 'Badagavettu', 101, 1563),
(16251, 'Badami', 101, 1563),
(16252, 'Bagalkot', 101, 1563),
(16253, 'Bagepalli', 101, 1563),
(16254, 'Bailhongal', 101, 1563),
(16255, 'Baindur', 101, 1563),
(16256, 'Bajala', 101, 1563),
(16257, 'Bajpe', 101, 1563),
(16258, 'Banavar', 101, 1563),
(16259, 'Bangarapet', 101, 1563),
(16260, 'Bankapura', 101, 1563),
(16261, 'Bannur', 101, 1563),
(16262, 'Bantwal', 101, 1563),
(16263, 'Basavakalyan', 101, 1563),
(16264, 'Basavana Bagevadi', 101, 1563),
(16265, 'Belagula', 101, 1563),
(16266, 'Belakavadiq', 101, 1563),
(16267, 'Belgaum', 101, 1563),
(16268, 'Belgaum Cantonment', 101, 1563),
(16269, 'Bellary', 101, 1563),
(16270, 'Belluru', 101, 1563),
(16271, 'Beltangadi', 101, 1563),
(16272, 'Belur', 101, 1563),
(16273, 'Belvata', 101, 1563),
(16274, 'Bengaluru', 101, 1563),
(16275, 'Bhadravati', 101, 1563),
(16276, 'Bhalki', 101, 1563),
(16277, 'Bhatkal', 101, 1563),
(16278, 'Bhimarayanagudi', 101, 1563),
(16279, 'Bhogadi', 101, 1563),
(16280, 'Bidar', 101, 1563),
(16281, 'Bijapur', 101, 1563),
(16282, 'Bilgi', 101, 1563),
(16283, 'Birur', 101, 1563),
(16284, 'Bommanahalli', 101, 1563),
(16285, 'Bommasandra', 101, 1563),
(16286, 'Byadgi', 101, 1563),
(16287, 'Byatarayanapura', 101, 1563),
(16288, 'Chakranagar Colony', 101, 1563),
(16289, 'Challakere', 101, 1563),
(16290, 'Chamrajnagar', 101, 1563),
(16291, 'Chamundi Betta', 101, 1563),
(16292, 'Channagiri', 101, 1563),
(16293, 'Channapatna', 101, 1563),
(16294, 'Channarayapatna', 101, 1563),
(16295, 'Chickballapur', 101, 1563),
(16296, 'Chik Ballapur', 101, 1563),
(16297, 'Chikkaballapur', 101, 1563),
(16298, 'Chikmagalur', 101, 1563),
(16299, 'Chiknayakanhalli', 101, 1563),
(16300, 'Chikodi', 101, 1563),
(16301, 'Chincholi', 101, 1563),
(16302, 'Chintamani', 101, 1563),
(16303, 'Chitaguppa', 101, 1563),
(16304, 'Chitapur', 101, 1563),
(16305, 'Chitradurga', 101, 1563),
(16306, 'Coorg', 101, 1563),
(16307, 'Dandeli', 101, 1563),
(16308, 'Dargajogihalli', 101, 1563),
(16309, 'Dasarahalli', 101, 1563),
(16310, 'Davangere', 101, 1563),
(16311, 'Devadurga', 101, 1563),
(16312, 'Devagiri', 101, 1563),
(16313, 'Devanhalli', 101, 1563),
(16314, 'Dharwar', 101, 1563),
(16315, 'Dhupdal', 101, 1563),
(16316, 'Dod Ballapur', 101, 1563),
(16317, 'Donimalai', 101, 1563),
(16318, 'Gadag', 101, 1563),
(16319, 'Gajendragarh', 101, 1563),
(16320, 'Ganeshgudi', 101, 1563),
(16321, 'Gangawati', 101, 1563),
(16322, 'Gangoli', 101, 1563),
(16323, 'Gauribidanur', 101, 1563),
(16324, 'Gokak', 101, 1563),
(16325, 'Gokak Falls', 101, 1563),
(16326, 'Gonikoppal', 101, 1563),
(16327, 'Gorur', 101, 1563),
(16328, 'Gottikere', 101, 1563),
(16329, 'Gubbi', 101, 1563),
(16330, 'Gudibanda', 101, 1563),
(16331, 'Gulbarga', 101, 1563),
(16332, 'Guledgudda', 101, 1563),
(16333, 'Gundlupet', 101, 1563),
(16334, 'Gurmatkal', 101, 1563),
(16335, 'Haliyal', 101, 1563),
(16336, 'Hangal', 101, 1563),
(16337, 'Harihar', 101, 1563),
(16338, 'Harpanahalli', 101, 1563),
(16339, 'Hassan', 101, 1563),
(16340, 'Hatti', 101, 1563),
(16341, 'Hatti Gold Mines', 101, 1563),
(16342, 'Haveri', 101, 1563),
(16343, 'Hebbagodi', 101, 1563),
(16344, 'Hebbalu', 101, 1563),
(16345, 'Hebri', 101, 1563),
(16346, 'Heggadadevanakote', 101, 1563),
(16347, 'Herohalli', 101, 1563),
(16348, 'Hidkal', 101, 1563),
(16349, 'Hindalgi', 101, 1563),
(16350, 'Hirekerur', 101, 1563),
(16351, 'Hiriyur', 101, 1563),
(16352, 'Holalkere', 101, 1563),
(16353, 'Hole Narsipur', 101, 1563),
(16354, 'Homnabad', 101, 1563),
(16355, 'Honavar', 101, 1563),
(16356, 'Honnali', 101, 1563),
(16357, 'Hosakote', 101, 1563),
(16358, 'Hosanagara', 101, 1563),
(16359, 'Hosangadi', 101, 1563),
(16360, 'Hosdurga', 101, 1563),
(16361, 'Hoskote', 101, 1563),
(16362, 'Hospet', 101, 1563),
(16363, 'Hubli', 101, 1563),
(16364, 'Hukeri', 101, 1563),
(16365, 'Hunasagi', 101, 1563),
(16366, 'Hunasamaranahalli', 101, 1563),
(16367, 'Hungund', 101, 1563),
(16368, 'Hunsur', 101, 1563),
(16369, 'Huvina Hadagalli', 101, 1563);
INSERT INTO `cities` (`id`, `name`, `country_id`, `state_id`) VALUES
(16370, 'Ilkal', 101, 1563),
(16371, 'Indi', 101, 1563),
(16372, 'Jagalur', 101, 1563),
(16373, 'Jamkhandi', 101, 1563),
(16374, 'Jevargi', 101, 1563),
(16375, 'Jog Falls', 101, 1563),
(16376, 'Kabini Colony', 101, 1563),
(16377, 'Kadur', 101, 1563),
(16378, 'Kalghatgi', 101, 1563),
(16379, 'Kamalapuram', 101, 1563),
(16380, 'Kampli', 101, 1563),
(16381, 'Kanakapura', 101, 1563),
(16382, 'Kangrali BK', 101, 1563),
(16383, 'Kangrali KH', 101, 1563),
(16384, 'Kannur', 101, 1563),
(16385, 'Karkala', 101, 1563),
(16386, 'Karwar', 101, 1563),
(16387, 'Kemminja', 101, 1563),
(16388, 'Kengeri', 101, 1563),
(16389, 'Kerur', 101, 1563),
(16390, 'Khanapur', 101, 1563),
(16391, 'Kodigenahalli', 101, 1563),
(16392, 'Kodiyal', 101, 1563),
(16393, 'Kodlipet', 101, 1563),
(16394, 'Kolar', 101, 1563),
(16395, 'Kollegal', 101, 1563),
(16396, 'Konanakunte', 101, 1563),
(16397, 'Konanur', 101, 1563),
(16398, 'Konnur', 101, 1563),
(16399, 'Koppa', 101, 1563),
(16400, 'Koppal', 101, 1563),
(16401, 'Koratagere', 101, 1563),
(16402, 'Kotekara', 101, 1563),
(16403, 'Kothnur', 101, 1563),
(16404, 'Kotturu', 101, 1563),
(16405, 'Krishnapura', 101, 1563),
(16406, 'Krishnarajanagar', 101, 1563),
(16407, 'Krishnarajapura', 101, 1563),
(16408, 'Krishnarajasagara', 101, 1563),
(16409, 'Krishnarajpet', 101, 1563),
(16410, 'Kudchi', 101, 1563),
(16411, 'Kudligi', 101, 1563),
(16412, 'Kudremukh', 101, 1563),
(16413, 'Kumsi', 101, 1563),
(16414, 'Kumta', 101, 1563),
(16415, 'Kundapura', 101, 1563),
(16416, 'Kundgol', 101, 1563),
(16417, 'Kunigal', 101, 1563),
(16418, 'Kurgunta', 101, 1563),
(16419, 'Kushalnagar', 101, 1563),
(16420, 'Kushtagi', 101, 1563),
(16421, 'Kyathanahalli', 101, 1563),
(16422, 'Lakshmeshwar', 101, 1563),
(16423, 'Lingsugur', 101, 1563),
(16424, 'Londa', 101, 1563),
(16425, 'Maddur', 101, 1563),
(16426, 'Madhugiri', 101, 1563),
(16427, 'Madikeri', 101, 1563),
(16428, 'Magadi', 101, 1563),
(16429, 'Magod Falls', 101, 1563),
(16430, 'Mahadeswara Hills', 101, 1563),
(16431, 'Mahadevapura', 101, 1563),
(16432, 'Mahalingpur', 101, 1563),
(16433, 'Maisuru', 101, 1563),
(16434, 'Maisuru Cantonment', 101, 1563),
(16435, 'Malavalli', 101, 1563),
(16436, 'Mallar', 101, 1563),
(16437, 'Malpe', 101, 1563),
(16438, 'Malur', 101, 1563),
(16439, 'Manchenahalli', 101, 1563),
(16440, 'Mandya', 101, 1563),
(16441, 'Mangalore', 101, 1563),
(16442, 'Mangaluru', 101, 1563),
(16443, 'Manipal', 101, 1563),
(16444, 'Manvi', 101, 1563),
(16445, 'Maski', 101, 1563),
(16446, 'Mastikatte Colony', 101, 1563),
(16447, 'Mayakonda', 101, 1563),
(16448, 'Melukote', 101, 1563),
(16449, 'Molakalmuru', 101, 1563),
(16450, 'Mudalgi', 101, 1563),
(16451, 'Mudbidri', 101, 1563),
(16452, 'Muddebihal', 101, 1563),
(16453, 'Mudgal', 101, 1563),
(16454, 'Mudhol', 101, 1563),
(16455, 'Mudigere', 101, 1563),
(16456, 'Mudushedde', 101, 1563),
(16457, 'Mulbagal', 101, 1563),
(16458, 'Mulgund', 101, 1563),
(16459, 'Mulki', 101, 1563),
(16460, 'Mulur', 101, 1563),
(16461, 'Mundargi', 101, 1563),
(16462, 'Mundgod', 101, 1563),
(16463, 'Munirabad', 101, 1563),
(16464, 'Munnur', 101, 1563),
(16465, 'Murudeshwara', 101, 1563),
(16466, 'Mysore', 101, 1563),
(16467, 'Nagamangala', 101, 1563),
(16468, 'Nanjangud', 101, 1563),
(16469, 'Naragund', 101, 1563),
(16470, 'Narasimharajapura', 101, 1563),
(16471, 'Naravi', 101, 1563),
(16472, 'Narayanpur', 101, 1563),
(16473, 'Naregal', 101, 1563),
(16474, 'Navalgund', 101, 1563),
(16475, 'Nelmangala', 101, 1563),
(16476, 'Nipani', 101, 1563),
(16477, 'Nitte', 101, 1563),
(16478, 'Nyamati', 101, 1563),
(16479, 'Padu', 101, 1563),
(16480, 'Pandavapura', 101, 1563),
(16481, 'Pattanagere', 101, 1563),
(16482, 'Pavagada', 101, 1563),
(16483, 'Piriyapatna', 101, 1563),
(16484, 'Ponnampet', 101, 1563),
(16485, 'Puttur', 101, 1563),
(16486, 'Rabkavi', 101, 1563),
(16487, 'Raichur', 101, 1563),
(16488, 'Ramanagaram', 101, 1563),
(16489, 'Ramdurg', 101, 1563),
(16490, 'Ranibennur', 101, 1563),
(16491, 'Raybag', 101, 1563),
(16492, 'Robertsonpet', 101, 1563),
(16493, 'Ron', 101, 1563),
(16494, 'Sadalgi', 101, 1563),
(16495, 'Sagar', 101, 1563),
(16496, 'Sakleshpur', 101, 1563),
(16497, 'Saligram', 101, 1563),
(16498, 'Sandur', 101, 1563),
(16499, 'Sanivarsante', 101, 1563),
(16500, 'Sankeshwar', 101, 1563),
(16501, 'Sargur', 101, 1563),
(16502, 'Sathyamangala', 101, 1563),
(16503, 'Saundatti Yellamma', 101, 1563),
(16504, 'Savanur', 101, 1563),
(16505, 'Sedam', 101, 1563),
(16506, 'Shahabad', 101, 1563),
(16507, 'Shahabad A.C.C.', 101, 1563),
(16508, 'Shahapur', 101, 1563),
(16509, 'Shahpur', 101, 1563),
(16510, 'Shaktinagar', 101, 1563),
(16511, 'Shiggaon', 101, 1563),
(16512, 'Shikarpur', 101, 1563),
(16513, 'Shimoga', 101, 1563),
(16514, 'Shirhatti', 101, 1563),
(16515, 'Shorapur', 101, 1563),
(16516, 'Shravanabelagola', 101, 1563),
(16517, 'Shrirangapattana', 101, 1563),
(16518, 'Siddapur', 101, 1563),
(16519, 'Sidlaghatta', 101, 1563),
(16520, 'Sindgi', 101, 1563),
(16521, 'Sindhnur', 101, 1563),
(16522, 'Sira', 101, 1563),
(16523, 'Sirakoppa', 101, 1563),
(16524, 'Sirsi', 101, 1563),
(16525, 'Siruguppa', 101, 1563),
(16526, 'Someshwar', 101, 1563),
(16527, 'Somvarpet', 101, 1563),
(16528, 'Sorab', 101, 1563),
(16529, 'Sringeri', 101, 1563),
(16530, 'Srinivaspur', 101, 1563),
(16531, 'Sulya', 101, 1563),
(16532, 'Suntikopa', 101, 1563),
(16533, 'Talikota', 101, 1563),
(16534, 'Tarikera', 101, 1563),
(16535, 'Tekkalakota', 101, 1563),
(16536, 'Terdal', 101, 1563),
(16537, 'Thokur', 101, 1563),
(16538, 'Thumbe', 101, 1563),
(16539, 'Tiptur', 101, 1563),
(16540, 'Tirthahalli', 101, 1563),
(16541, 'Tirumakudal Narsipur', 101, 1563),
(16542, 'Tonse', 101, 1563),
(16543, 'Tumkur', 101, 1563),
(16544, 'Turuvekere', 101, 1563),
(16545, 'Udupi', 101, 1563),
(16546, 'Ullal', 101, 1563),
(16547, 'Uttarahalli', 101, 1563),
(16548, 'Venkatapura', 101, 1563),
(16549, 'Vijayapura', 101, 1563),
(16550, 'Virarajendrapet', 101, 1563),
(16551, 'Wadi', 101, 1563),
(16552, 'Wadi A.C.C.', 101, 1563),
(16553, 'Yadgir', 101, 1563),
(16554, 'Yelahanka', 101, 1563),
(16555, 'Yelandur', 101, 1563),
(16556, 'Yelbarga', 101, 1563),
(16557, 'Yellapur', 101, 1563),
(16558, 'Yenagudde', 101, 1563),
(16559, 'Adimaly', 101, 1565),
(16560, 'Adoor', 101, 1565),
(16561, 'Adur', 101, 1565),
(16562, 'Akathiyur', 101, 1565),
(16563, 'Alangad', 101, 1565),
(16564, 'Alappuzha', 101, 1565),
(16565, 'Aluva', 101, 1565),
(16566, 'Ancharakandy', 101, 1565),
(16567, 'Angamaly', 101, 1565),
(16568, 'Aroor', 101, 1565),
(16569, 'Arukutti', 101, 1565),
(16570, 'Attingal', 101, 1565),
(16571, 'Avinissery', 101, 1565),
(16572, 'Azhikode North', 101, 1565),
(16573, 'Azhikode South', 101, 1565),
(16574, 'Azhiyur', 101, 1565),
(16575, 'Balussery', 101, 1565),
(16576, 'Bangramanjeshwar', 101, 1565),
(16577, 'Beypur', 101, 1565),
(16578, 'Brahmakulam', 101, 1565),
(16579, 'Chala', 101, 1565),
(16580, 'Chalakudi', 101, 1565),
(16581, 'Changanacheri', 101, 1565),
(16582, 'Chauwara', 101, 1565),
(16583, 'Chavakkad', 101, 1565),
(16584, 'Chelakkara', 101, 1565),
(16585, 'Chelora', 101, 1565),
(16586, 'Chendamangalam', 101, 1565),
(16587, 'Chengamanad', 101, 1565),
(16588, 'Chengannur', 101, 1565),
(16589, 'Cheranallur', 101, 1565),
(16590, 'Cheriyakadavu', 101, 1565),
(16591, 'Cherthala', 101, 1565),
(16592, 'Cherukunnu', 101, 1565),
(16593, 'Cheruthazham', 101, 1565),
(16594, 'Cheruvannur', 101, 1565),
(16595, 'Cheruvattur', 101, 1565),
(16596, 'Chevvur', 101, 1565),
(16597, 'Chirakkal', 101, 1565),
(16598, 'Chittur', 101, 1565),
(16599, 'Chockli', 101, 1565),
(16600, 'Churnikkara', 101, 1565),
(16601, 'Dharmadam', 101, 1565),
(16602, 'Edappal', 101, 1565),
(16603, 'Edathala', 101, 1565),
(16604, 'Elayavur', 101, 1565),
(16605, 'Elur', 101, 1565),
(16606, 'Eranholi', 101, 1565),
(16607, 'Erattupetta', 101, 1565),
(16608, 'Ernakulam', 101, 1565),
(16609, 'Eruvatti', 101, 1565),
(16610, 'Ettumanoor', 101, 1565),
(16611, 'Feroke', 101, 1565),
(16612, 'Guruvayur', 101, 1565),
(16613, 'Haripad', 101, 1565),
(16614, 'Hosabettu', 101, 1565),
(16615, 'Idukki', 101, 1565),
(16616, 'Iringaprom', 101, 1565),
(16617, 'Irinjalakuda', 101, 1565),
(16618, 'Iriveri', 101, 1565),
(16619, 'Kadachira', 101, 1565),
(16620, 'Kadalundi', 101, 1565),
(16621, 'Kadamakkudy', 101, 1565),
(16622, 'Kadirur', 101, 1565),
(16623, 'Kadungallur', 101, 1565),
(16624, 'Kakkodi', 101, 1565),
(16625, 'Kalady', 101, 1565),
(16626, 'Kalamassery', 101, 1565),
(16627, 'Kalliasseri', 101, 1565),
(16628, 'Kalpetta', 101, 1565),
(16629, 'Kanhangad', 101, 1565),
(16630, 'Kanhirode', 101, 1565),
(16631, 'Kanjikkuzhi', 101, 1565),
(16632, 'Kanjikode', 101, 1565),
(16633, 'Kanjirappalli', 101, 1565),
(16634, 'Kannadiparamba', 101, 1565),
(16635, 'Kannangad', 101, 1565),
(16636, 'Kannapuram', 101, 1565),
(16637, 'Kannur', 101, 1565),
(16638, 'Kannur Cantonment', 101, 1565),
(16639, 'Karunagappally', 101, 1565),
(16640, 'Karuvamyhuruthy', 101, 1565),
(16641, 'Kasaragod', 101, 1565),
(16642, 'Kasargod', 101, 1565),
(16643, 'Kattappana', 101, 1565),
(16644, 'Kayamkulam', 101, 1565),
(16645, 'Kedamangalam', 101, 1565),
(16646, 'Kochi', 101, 1565),
(16647, 'Kodamthuruthu', 101, 1565),
(16648, 'Kodungallur', 101, 1565),
(16649, 'Koduvally', 101, 1565),
(16650, 'Koduvayur', 101, 1565),
(16651, 'Kokkothamangalam', 101, 1565),
(16652, 'Kolazhy', 101, 1565),
(16653, 'Kollam', 101, 1565),
(16654, 'Komalapuram', 101, 1565),
(16655, 'Koothattukulam', 101, 1565),
(16656, 'Koratty', 101, 1565),
(16657, 'Kothamangalam', 101, 1565),
(16658, 'Kottarakkara', 101, 1565),
(16659, 'Kottayam', 101, 1565),
(16660, 'Kottayam Malabar', 101, 1565),
(16661, 'Kottuvally', 101, 1565),
(16662, 'Koyilandi', 101, 1565),
(16663, 'Kozhikode', 101, 1565),
(16664, 'Kudappanakunnu', 101, 1565),
(16665, 'Kudlu', 101, 1565),
(16666, 'Kumarakom', 101, 1565),
(16667, 'Kumily', 101, 1565),
(16668, 'Kunnamangalam', 101, 1565),
(16669, 'Kunnamkulam', 101, 1565),
(16670, 'Kurikkad', 101, 1565),
(16671, 'Kurkkanchery', 101, 1565),
(16672, 'Kuthuparamba', 101, 1565),
(16673, 'Kuttakulam', 101, 1565),
(16674, 'Kuttikkattur', 101, 1565),
(16675, 'Kuttur', 101, 1565),
(16676, 'Malappuram', 101, 1565),
(16677, 'Mallappally', 101, 1565),
(16678, 'Manjeri', 101, 1565),
(16679, 'Manjeshwar', 101, 1565),
(16680, 'Mannancherry', 101, 1565),
(16681, 'Mannar', 101, 1565),
(16682, 'Mannarakkat', 101, 1565),
(16683, 'Maradu', 101, 1565),
(16684, 'Marathakkara', 101, 1565),
(16685, 'Marutharod', 101, 1565),
(16686, 'Mattannur', 101, 1565),
(16687, 'Mavelikara', 101, 1565),
(16688, 'Mavilayi', 101, 1565),
(16689, 'Mavur', 101, 1565),
(16690, 'Methala', 101, 1565),
(16691, 'Muhamma', 101, 1565),
(16692, 'Mulavukad', 101, 1565),
(16693, 'Mundakayam', 101, 1565),
(16694, 'Munderi', 101, 1565),
(16695, 'Munnar', 101, 1565),
(16696, 'Muthakunnam', 101, 1565),
(16697, 'Muvattupuzha', 101, 1565),
(16698, 'Muzhappilangad', 101, 1565),
(16699, 'Nadapuram', 101, 1565),
(16700, 'Nadathara', 101, 1565),
(16701, 'Narath', 101, 1565),
(16702, 'Nattakam', 101, 1565),
(16703, 'Nedumangad', 101, 1565),
(16704, 'Nenmenikkara', 101, 1565),
(16705, 'New Mahe', 101, 1565),
(16706, 'Neyyattinkara', 101, 1565),
(16707, 'Nileshwar', 101, 1565),
(16708, 'Olavanna', 101, 1565),
(16709, 'Ottapalam', 101, 1565),
(16710, 'Ottappalam', 101, 1565),
(16711, 'Paduvilayi', 101, 1565),
(16712, 'Palai', 101, 1565),
(16713, 'Palakkad', 101, 1565),
(16714, 'Palayad', 101, 1565),
(16715, 'Palissery', 101, 1565),
(16716, 'Pallikkunnu', 101, 1565),
(16717, 'Paluvai', 101, 1565),
(16718, 'Panniyannur', 101, 1565),
(16719, 'Pantalam', 101, 1565),
(16720, 'Panthiramkavu', 101, 1565),
(16721, 'Panur', 101, 1565),
(16722, 'Pappinisseri', 101, 1565),
(16723, 'Parassala', 101, 1565),
(16724, 'Paravur', 101, 1565),
(16725, 'Pathanamthitta', 101, 1565),
(16726, 'Pathanapuram', 101, 1565),
(16727, 'Pathiriyad', 101, 1565),
(16728, 'Pattambi', 101, 1565),
(16729, 'Pattiom', 101, 1565),
(16730, 'Pavaratty', 101, 1565),
(16731, 'Payyannur', 101, 1565),
(16732, 'Peermade', 101, 1565),
(16733, 'Perakam', 101, 1565),
(16734, 'Peralasseri', 101, 1565),
(16735, 'Peringathur', 101, 1565),
(16736, 'Perinthalmanna', 101, 1565),
(16737, 'Perole', 101, 1565),
(16738, 'Perumanna', 101, 1565),
(16739, 'Perumbaikadu', 101, 1565),
(16740, 'Perumbavoor', 101, 1565),
(16741, 'Pinarayi', 101, 1565),
(16742, 'Piravam', 101, 1565),
(16743, 'Ponnani', 101, 1565),
(16744, 'Pottore', 101, 1565),
(16745, 'Pudukad', 101, 1565),
(16746, 'Punalur', 101, 1565),
(16747, 'Puranattukara', 101, 1565),
(16748, 'Puthunagaram', 101, 1565),
(16749, 'Puthuppariyaram', 101, 1565),
(16750, 'Puzhathi', 101, 1565),
(16751, 'Ramanattukara', 101, 1565),
(16752, 'Shoranur', 101, 1565),
(16753, 'Sultans Battery', 101, 1565),
(16754, 'Sulthan Bathery', 101, 1565),
(16755, 'Talipparamba', 101, 1565),
(16756, 'Thaikkad', 101, 1565),
(16757, 'Thalassery', 101, 1565),
(16758, 'Thannirmukkam', 101, 1565),
(16759, 'Theyyalingal', 101, 1565),
(16760, 'Thiruvalla', 101, 1565),
(16761, 'Thiruvananthapuram', 101, 1565),
(16762, 'Thiruvankulam', 101, 1565),
(16763, 'Thodupuzha', 101, 1565),
(16764, 'Thottada', 101, 1565),
(16765, 'Thrippunithura', 101, 1565),
(16766, 'Thrissur', 101, 1565),
(16767, 'Tirur', 101, 1565),
(16768, 'Udma', 101, 1565),
(16769, 'Vadakara', 101, 1565),
(16770, 'Vaikam', 101, 1565),
(16771, 'Valapattam', 101, 1565),
(16772, 'Vallachira', 101, 1565),
(16773, 'Varam', 101, 1565),
(16774, 'Varappuzha', 101, 1565),
(16775, 'Varkala', 101, 1565),
(16776, 'Vayalar', 101, 1565),
(16777, 'Vazhakkala', 101, 1565),
(16778, 'Venmanad', 101, 1565),
(16779, 'Villiappally', 101, 1565),
(16780, 'Wayanad', 101, 1565),
(16781, 'Agethi', 101, 1566),
(16782, 'Amini', 101, 1566),
(16783, 'Androth Island', 101, 1566),
(16784, 'Kavaratti', 101, 1566),
(16785, 'Minicoy', 101, 1566),
(16786, 'Agar', 101, 1567),
(16787, 'Ajaigarh', 101, 1567),
(16788, 'Akoda', 101, 1567),
(16789, 'Akodia', 101, 1567),
(16790, 'Alampur', 101, 1567),
(16791, 'Alirajpur', 101, 1567),
(16792, 'Alot', 101, 1567),
(16793, 'Amanganj', 101, 1567),
(16794, 'Amarkantak', 101, 1567),
(16795, 'Amarpatan', 101, 1567),
(16796, 'Amarwara', 101, 1567),
(16797, 'Ambada', 101, 1567),
(16798, 'Ambah', 101, 1567),
(16799, 'Amla', 101, 1567),
(16800, 'Amlai', 101, 1567),
(16801, 'Anjad', 101, 1567),
(16802, 'Antri', 101, 1567),
(16803, 'Anuppur', 101, 1567),
(16804, 'Aron', 101, 1567),
(16805, 'Ashoknagar', 101, 1567),
(16806, 'Ashta', 101, 1567),
(16807, 'Babai', 101, 1567),
(16808, 'Bada Malhera', 101, 1567),
(16809, 'Badagaon', 101, 1567),
(16810, 'Badagoan', 101, 1567),
(16811, 'Badarwas', 101, 1567),
(16812, 'Badawada', 101, 1567),
(16813, 'Badi', 101, 1567),
(16814, 'Badkuhi', 101, 1567),
(16815, 'Badnagar', 101, 1567),
(16816, 'Badnawar', 101, 1567),
(16817, 'Badod', 101, 1567),
(16818, 'Badoda', 101, 1567),
(16819, 'Badra', 101, 1567),
(16820, 'Bagh', 101, 1567),
(16821, 'Bagli', 101, 1567),
(16822, 'Baihar', 101, 1567),
(16823, 'Baikunthpur', 101, 1567),
(16824, 'Bakswaha', 101, 1567),
(16825, 'Balaghat', 101, 1567),
(16826, 'Baldeogarh', 101, 1567),
(16827, 'Bamaniya', 101, 1567),
(16828, 'Bamhani', 101, 1567),
(16829, 'Bamor', 101, 1567),
(16830, 'Bamora', 101, 1567),
(16831, 'Banda', 101, 1567),
(16832, 'Bangawan', 101, 1567),
(16833, 'Bansatar Kheda', 101, 1567),
(16834, 'Baraily', 101, 1567),
(16835, 'Barela', 101, 1567),
(16836, 'Barghat', 101, 1567),
(16837, 'Bargi', 101, 1567),
(16838, 'Barhi', 101, 1567),
(16839, 'Barigarh', 101, 1567),
(16840, 'Barwaha', 101, 1567),
(16841, 'Barwani', 101, 1567),
(16842, 'Basoda', 101, 1567),
(16843, 'Begamganj', 101, 1567),
(16844, 'Beohari', 101, 1567),
(16845, 'Berasia', 101, 1567),
(16846, 'Betma', 101, 1567),
(16847, 'Betul', 101, 1567),
(16848, 'Betul Bazar', 101, 1567),
(16849, 'Bhainsdehi', 101, 1567),
(16850, 'Bhamodi', 101, 1567),
(16851, 'Bhander', 101, 1567),
(16852, 'Bhanpura', 101, 1567),
(16853, 'Bharveli', 101, 1567),
(16854, 'Bhaurasa', 101, 1567),
(16855, 'Bhavra', 101, 1567),
(16856, 'Bhedaghat', 101, 1567),
(16857, 'Bhikangaon', 101, 1567),
(16858, 'Bhilakhedi', 101, 1567),
(16859, 'Bhind', 101, 1567),
(16860, 'Bhitarwar', 101, 1567),
(16861, 'Bhopal', 101, 1567),
(16862, 'Bhuibandh', 101, 1567),
(16863, 'Biaora', 101, 1567),
(16864, 'Bijawar', 101, 1567),
(16865, 'Bijeypur', 101, 1567),
(16866, 'Bijrauni', 101, 1567),
(16867, 'Bijuri', 101, 1567),
(16868, 'Bilaua', 101, 1567),
(16869, 'Bilpura', 101, 1567),
(16870, 'Bina Railway Colony', 101, 1567),
(16871, 'Bina-Etawa', 101, 1567),
(16872, 'Birsinghpur', 101, 1567),
(16873, 'Boda', 101, 1567),
(16874, 'Budhni', 101, 1567),
(16875, 'Burhanpur', 101, 1567),
(16876, 'Burhar', 101, 1567),
(16877, 'Chachaura Binaganj', 101, 1567),
(16878, 'Chakghat', 101, 1567),
(16879, 'Chandameta Butar', 101, 1567),
(16880, 'Chanderi', 101, 1567),
(16881, 'Chandia', 101, 1567),
(16882, 'Chandla', 101, 1567),
(16883, 'Chaurai Khas', 101, 1567),
(16884, 'Chhatarpur', 101, 1567),
(16885, 'Chhindwara', 101, 1567),
(16886, 'Chhota Chhindwara', 101, 1567),
(16887, 'Chichli', 101, 1567),
(16888, 'Chitrakut', 101, 1567),
(16889, 'Churhat', 101, 1567),
(16890, 'Daboh', 101, 1567),
(16891, 'Dabra', 101, 1567),
(16892, 'Damoh', 101, 1567),
(16893, 'Damua', 101, 1567),
(16894, 'Datia', 101, 1567),
(16895, 'Deodara', 101, 1567),
(16896, 'Deori', 101, 1567),
(16897, 'Deori Khas', 101, 1567),
(16898, 'Depalpur', 101, 1567),
(16899, 'Devendranagar', 101, 1567),
(16900, 'Devhara', 101, 1567),
(16901, 'Dewas', 101, 1567),
(16902, 'Dhamnod', 101, 1567),
(16903, 'Dhana', 101, 1567),
(16904, 'Dhanpuri', 101, 1567),
(16905, 'Dhar', 101, 1567),
(16906, 'Dharampuri', 101, 1567),
(16907, 'Dighawani', 101, 1567),
(16908, 'Diken', 101, 1567),
(16909, 'Dindori', 101, 1567),
(16910, 'Dola', 101, 1567),
(16911, 'Dumar Kachhar', 101, 1567),
(16912, 'Dungariya Chhapara', 101, 1567),
(16913, 'Gadarwara', 101, 1567),
(16914, 'Gairatganj', 101, 1567),
(16915, 'Gandhi Sagar Hydel Colony', 101, 1567),
(16916, 'Ganjbasoda', 101, 1567),
(16917, 'Garhakota', 101, 1567),
(16918, 'Garhi Malhara', 101, 1567),
(16919, 'Garoth', 101, 1567),
(16920, 'Gautapura', 101, 1567),
(16921, 'Ghansor', 101, 1567),
(16922, 'Ghuwara', 101, 1567),
(16923, 'Gogaon', 101, 1567),
(16924, 'Gogapur', 101, 1567),
(16925, 'Gohad', 101, 1567),
(16926, 'Gormi', 101, 1567),
(16927, 'Govindgarh', 101, 1567),
(16928, 'Guna', 101, 1567),
(16929, 'Gurh', 101, 1567),
(16930, 'Gwalior', 101, 1567),
(16931, 'Hanumana', 101, 1567),
(16932, 'Harda', 101, 1567),
(16933, 'Harpalpur', 101, 1567),
(16934, 'Harrai', 101, 1567),
(16935, 'Harsud', 101, 1567),
(16936, 'Hatod', 101, 1567),
(16937, 'Hatpipalya', 101, 1567),
(16938, 'Hatta', 101, 1567),
(16939, 'Hindoria', 101, 1567),
(16940, 'Hirapur', 101, 1567),
(16941, 'Hoshangabad', 101, 1567),
(16942, 'Ichhawar', 101, 1567),
(16943, 'Iklehra', 101, 1567),
(16944, 'Indergarh', 101, 1567),
(16945, 'Indore', 101, 1567),
(16946, 'Isagarh', 101, 1567),
(16947, 'Itarsi', 101, 1567),
(16948, 'Jabalpur', 101, 1567),
(16949, 'Jabalpur Cantonment', 101, 1567),
(16950, 'Jabalpur G.C.F', 101, 1567),
(16951, 'Jaisinghnagar', 101, 1567),
(16952, 'Jaithari', 101, 1567),
(16953, 'Jaitwara', 101, 1567),
(16954, 'Jamai', 101, 1567),
(16955, 'Jaora', 101, 1567),
(16956, 'Jatachhapar', 101, 1567),
(16957, 'Jatara', 101, 1567),
(16958, 'Jawad', 101, 1567),
(16959, 'Jawar', 101, 1567),
(16960, 'Jeronkhalsa', 101, 1567),
(16961, 'Jhabua', 101, 1567),
(16962, 'Jhundpura', 101, 1567),
(16963, 'Jiran', 101, 1567),
(16964, 'Jirapur', 101, 1567),
(16965, 'Jobat', 101, 1567),
(16966, 'Joura', 101, 1567),
(16967, 'Kailaras', 101, 1567),
(16968, 'Kaimur', 101, 1567),
(16969, 'Kakarhati', 101, 1567),
(16970, 'Kalichhapar', 101, 1567),
(16971, 'Kanad', 101, 1567),
(16972, 'Kannod', 101, 1567),
(16973, 'Kantaphod', 101, 1567),
(16974, 'Kareli', 101, 1567),
(16975, 'Karera', 101, 1567),
(16976, 'Kari', 101, 1567),
(16977, 'Karnawad', 101, 1567),
(16978, 'Karrapur', 101, 1567),
(16979, 'Kasrawad', 101, 1567),
(16980, 'Katangi', 101, 1567),
(16981, 'Katni', 101, 1567),
(16982, 'Kelhauri', 101, 1567),
(16983, 'Khachrod', 101, 1567),
(16984, 'Khajuraho', 101, 1567),
(16985, 'Khamaria', 101, 1567),
(16986, 'Khand', 101, 1567),
(16987, 'Khandwa', 101, 1567),
(16988, 'Khaniyadhana', 101, 1567),
(16989, 'Khargapur', 101, 1567),
(16990, 'Khargone', 101, 1567),
(16991, 'Khategaon', 101, 1567),
(16992, 'Khetia', 101, 1567),
(16993, 'Khilchipur', 101, 1567),
(16994, 'Khirkiya', 101, 1567),
(16995, 'Khujner', 101, 1567),
(16996, 'Khurai', 101, 1567),
(16997, 'Kolaras', 101, 1567),
(16998, 'Kotar', 101, 1567),
(16999, 'Kothi', 101, 1567),
(17000, 'Kotma', 101, 1567),
(17001, 'Kukshi', 101, 1567),
(17002, 'Kumbhraj', 101, 1567),
(17003, 'Kurwai', 101, 1567),
(17004, 'Lahar', 101, 1567),
(17005, 'Lakhnadon', 101, 1567),
(17006, 'Lateri', 101, 1567),
(17007, 'Laundi', 101, 1567),
(17008, 'Lidhora Khas', 101, 1567),
(17009, 'Lodhikheda', 101, 1567),
(17010, 'Loharda', 101, 1567),
(17011, 'Machalpur', 101, 1567),
(17012, 'Madhogarh', 101, 1567),
(17013, 'Maharajpur', 101, 1567),
(17014, 'Maheshwar', 101, 1567),
(17015, 'Mahidpur', 101, 1567),
(17016, 'Maihar', 101, 1567),
(17017, 'Majholi', 101, 1567),
(17018, 'Makronia', 101, 1567),
(17019, 'Maksi', 101, 1567),
(17020, 'Malaj Khand', 101, 1567),
(17021, 'Malanpur', 101, 1567),
(17022, 'Malhargarh', 101, 1567),
(17023, 'Manasa', 101, 1567),
(17024, 'Manawar', 101, 1567),
(17025, 'Mandav', 101, 1567),
(17026, 'Mandideep', 101, 1567),
(17027, 'Mandla', 101, 1567),
(17028, 'Mandleshwar', 101, 1567),
(17029, 'Mandsaur', 101, 1567),
(17030, 'Manegaon', 101, 1567),
(17031, 'Mangawan', 101, 1567),
(17032, 'Manglaya Sadak', 101, 1567),
(17033, 'Manpur', 101, 1567),
(17034, 'Mau', 101, 1567),
(17035, 'Mauganj', 101, 1567),
(17036, 'Meghnagar', 101, 1567),
(17037, 'Mehara Gaon', 101, 1567),
(17038, 'Mehgaon', 101, 1567),
(17039, 'Mhaugaon', 101, 1567),
(17040, 'Mhow', 101, 1567),
(17041, 'Mihona', 101, 1567),
(17042, 'Mohgaon', 101, 1567),
(17043, 'Morar', 101, 1567),
(17044, 'Morena', 101, 1567),
(17045, 'Morwa', 101, 1567),
(17046, 'Multai', 101, 1567),
(17047, 'Mundi', 101, 1567),
(17048, 'Mungaoli', 101, 1567),
(17049, 'Murwara', 101, 1567),
(17050, 'Nagda', 101, 1567),
(17051, 'Nagod', 101, 1567),
(17052, 'Nagri', 101, 1567),
(17053, 'Naigarhi', 101, 1567),
(17054, 'Nainpur', 101, 1567),
(17055, 'Nalkheda', 101, 1567),
(17056, 'Namli', 101, 1567),
(17057, 'Narayangarh', 101, 1567),
(17058, 'Narsimhapur', 101, 1567),
(17059, 'Narsingarh', 101, 1567),
(17060, 'Narsinghpur', 101, 1567),
(17061, 'Narwar', 101, 1567),
(17062, 'Nasrullaganj', 101, 1567),
(17063, 'Naudhia', 101, 1567),
(17064, 'Naugaon', 101, 1567),
(17065, 'Naurozabad', 101, 1567),
(17066, 'Neemuch', 101, 1567),
(17067, 'Nepa Nagar', 101, 1567),
(17068, 'Neuton Chikhli Kalan', 101, 1567),
(17069, 'Nimach', 101, 1567),
(17070, 'Niwari', 101, 1567),
(17071, 'Obedullaganj', 101, 1567),
(17072, 'Omkareshwar', 101, 1567),
(17073, 'Orachha', 101, 1567),
(17074, 'Ordinance Factory Itarsi', 101, 1567),
(17075, 'Pachmarhi', 101, 1567),
(17076, 'Pachmarhi Cantonment', 101, 1567),
(17077, 'Pachore', 101, 1567),
(17078, 'Palchorai', 101, 1567),
(17079, 'Palda', 101, 1567),
(17080, 'Palera', 101, 1567),
(17081, 'Pali', 101, 1567),
(17082, 'Panagar', 101, 1567),
(17083, 'Panara', 101, 1567),
(17084, 'Pandaria', 101, 1567),
(17085, 'Pandhana', 101, 1567),
(17086, 'Pandhurna', 101, 1567),
(17087, 'Panna', 101, 1567),
(17088, 'Pansemal', 101, 1567),
(17089, 'Parasia', 101, 1567),
(17090, 'Pasan', 101, 1567),
(17091, 'Patan', 101, 1567),
(17092, 'Patharia', 101, 1567),
(17093, 'Pawai', 101, 1567),
(17094, 'Petlawad', 101, 1567),
(17095, 'Phuph Kalan', 101, 1567),
(17096, 'Pichhore', 101, 1567),
(17097, 'Pipariya', 101, 1567),
(17098, 'Pipliya Mandi', 101, 1567),
(17099, 'Piploda', 101, 1567),
(17100, 'Pithampur', 101, 1567),
(17101, 'Polay Kalan', 101, 1567),
(17102, 'Porsa', 101, 1567),
(17103, 'Prithvipur', 101, 1567),
(17104, 'Raghogarh', 101, 1567),
(17105, 'Rahatgarh', 101, 1567),
(17106, 'Raisen', 101, 1567),
(17107, 'Rajakhedi', 101, 1567),
(17108, 'Rajgarh', 101, 1567),
(17109, 'Rajnagar', 101, 1567),
(17110, 'Rajpur', 101, 1567),
(17111, 'Rampur Baghelan', 101, 1567),
(17112, 'Rampur Naikin', 101, 1567),
(17113, 'Rampura', 101, 1567),
(17114, 'Ranapur', 101, 1567),
(17115, 'Ranipura', 101, 1567),
(17116, 'Ratangarh', 101, 1567),
(17117, 'Ratlam', 101, 1567),
(17118, 'Ratlam Kasba', 101, 1567),
(17119, 'Rau', 101, 1567),
(17120, 'Rehli', 101, 1567),
(17121, 'Rehti', 101, 1567),
(17122, 'Rewa', 101, 1567),
(17123, 'Sabalgarh', 101, 1567),
(17124, 'Sagar', 101, 1567),
(17125, 'Sagar Cantonment', 101, 1567),
(17126, 'Sailana', 101, 1567),
(17127, 'Sanawad', 101, 1567),
(17128, 'Sanchi', 101, 1567),
(17129, 'Sanwer', 101, 1567),
(17130, 'Sarangpur', 101, 1567),
(17131, 'Sardarpur', 101, 1567),
(17132, 'Sarni', 101, 1567),
(17133, 'Satai', 101, 1567),
(17134, 'Satna', 101, 1567),
(17135, 'Satwas', 101, 1567),
(17136, 'Sausar', 101, 1567),
(17137, 'Sehore', 101, 1567),
(17138, 'Semaria', 101, 1567),
(17139, 'Sendhwa', 101, 1567),
(17140, 'Seondha', 101, 1567),
(17141, 'Seoni', 101, 1567),
(17142, 'Seoni Malwa', 101, 1567),
(17143, 'Sethia', 101, 1567),
(17144, 'Shahdol', 101, 1567),
(17145, 'Shahgarh', 101, 1567),
(17146, 'Shahpur', 101, 1567),
(17147, 'Shahpura', 101, 1567),
(17148, 'Shajapur', 101, 1567),
(17149, 'Shamgarh', 101, 1567),
(17150, 'Sheopur', 101, 1567),
(17151, 'Shivpuri', 101, 1567),
(17152, 'Shujalpur', 101, 1567),
(17153, 'Sidhi', 101, 1567),
(17154, 'Sihora', 101, 1567),
(17155, 'Singolo', 101, 1567),
(17156, 'Singrauli', 101, 1567),
(17157, 'Sinhasa', 101, 1567),
(17158, 'Sirgora', 101, 1567),
(17159, 'Sirmaur', 101, 1567),
(17160, 'Sironj', 101, 1567),
(17161, 'Sitamau', 101, 1567),
(17162, 'Sohagpur', 101, 1567),
(17163, 'Sonkatch', 101, 1567),
(17164, 'Soyatkalan', 101, 1567),
(17165, 'Suhagi', 101, 1567),
(17166, 'Sultanpur', 101, 1567),
(17167, 'Susner', 101, 1567),
(17168, 'Suthaliya', 101, 1567),
(17169, 'Tal', 101, 1567),
(17170, 'Talen', 101, 1567),
(17171, 'Tarana', 101, 1567),
(17172, 'Taricharkalan', 101, 1567),
(17173, 'Tekanpur', 101, 1567),
(17174, 'Tendukheda', 101, 1567),
(17175, 'Teonthar', 101, 1567),
(17176, 'Thandia', 101, 1567),
(17177, 'Tikamgarh', 101, 1567),
(17178, 'Timarni', 101, 1567),
(17179, 'Tirodi', 101, 1567),
(17180, 'Udaipura', 101, 1567),
(17181, 'Ujjain', 101, 1567),
(17182, 'Ukwa', 101, 1567),
(17183, 'Umaria', 101, 1567),
(17184, 'Unchahara', 101, 1567),
(17185, 'Unhel', 101, 1567),
(17186, 'Vehicle Factory Jabalpur', 101, 1567),
(17187, 'Vidisha', 101, 1567),
(17188, 'Vijayraghavgarh', 101, 1567),
(17189, 'Waraseoni', 101, 1567),
(17190, 'Achalpur', 101, 1568),
(17191, 'Aheri', 101, 1568),
(17192, 'Ahmadnagar Cantonment', 101, 1568),
(17193, 'Ahmadpur', 101, 1568),
(17194, 'Ahmednagar', 101, 1568),
(17195, 'Ajra', 101, 1568),
(17196, 'Akalkot', 101, 1568),
(17197, 'Akkalkuwa', 101, 1568),
(17198, 'Akola', 101, 1568),
(17199, 'Akot', 101, 1568),
(17200, 'Alandi', 101, 1568),
(17201, 'Alibag', 101, 1568),
(17202, 'Allapalli', 101, 1568),
(17203, 'Alore', 101, 1568),
(17204, 'Amalner', 101, 1568),
(17205, 'Ambad', 101, 1568),
(17206, 'Ambajogai', 101, 1568),
(17207, 'Ambernath', 101, 1568),
(17208, 'Ambivali Tarf Wankhal', 101, 1568),
(17209, 'Amgaon', 101, 1568),
(17210, 'Amravati', 101, 1568),
(17211, 'Anjangaon', 101, 1568),
(17212, 'Arvi', 101, 1568),
(17213, 'Ashta', 101, 1568),
(17214, 'Ashti', 101, 1568),
(17215, 'Aurangabad', 101, 1568),
(17216, 'Aurangabad Cantonment', 101, 1568),
(17217, 'Ausa', 101, 1568),
(17218, 'Babhulgaon', 101, 1568),
(17219, 'Badlapur', 101, 1568),
(17220, 'Balapur', 101, 1568),
(17221, 'Ballarpur', 101, 1568),
(17222, 'Baramati', 101, 1568),
(17223, 'Barshi', 101, 1568),
(17224, 'Basmat', 101, 1568),
(17225, 'Beed', 101, 1568),
(17226, 'Bhadravati', 101, 1568),
(17227, 'Bhagur', 101, 1568),
(17228, 'Bhandara', 101, 1568),
(17229, 'Bhigvan', 101, 1568),
(17230, 'Bhingar', 101, 1568),
(17231, 'Bhiwandi', 101, 1568),
(17232, 'Bhokhardan', 101, 1568),
(17233, 'Bhor', 101, 1568),
(17234, 'Bhosari', 101, 1568),
(17235, 'Bhum', 101, 1568),
(17236, 'Bhusawal', 101, 1568),
(17237, 'Bid', 101, 1568),
(17238, 'Biloli', 101, 1568),
(17239, 'Birwadi', 101, 1568),
(17240, 'Boisar', 101, 1568),
(17241, 'Bop Khel', 101, 1568),
(17242, 'Brahmapuri', 101, 1568),
(17243, 'Budhgaon', 101, 1568),
(17244, 'Buldana', 101, 1568),
(17245, 'Buldhana', 101, 1568),
(17246, 'Butibori', 101, 1568),
(17247, 'Chakan', 101, 1568),
(17248, 'Chalisgaon', 101, 1568),
(17249, 'Chandrapur', 101, 1568),
(17250, 'Chandur', 101, 1568),
(17251, 'Chandur Bazar', 101, 1568),
(17252, 'Chandvad', 101, 1568),
(17253, 'Chicholi', 101, 1568),
(17254, 'Chikhala', 101, 1568),
(17255, 'Chikhaldara', 101, 1568),
(17256, 'Chikhli', 101, 1568),
(17257, 'Chinchani', 101, 1568),
(17258, 'Chinchwad', 101, 1568),
(17259, 'Chiplun', 101, 1568),
(17260, 'Chopda', 101, 1568),
(17261, 'Dabhol', 101, 1568),
(17262, 'Dahance', 101, 1568),
(17263, 'Dahanu', 101, 1568),
(17264, 'Daharu', 101, 1568),
(17265, 'Dapoli Camp', 101, 1568),
(17266, 'Darwa', 101, 1568),
(17267, 'Daryapur', 101, 1568),
(17268, 'Dattapur', 101, 1568),
(17269, 'Daund', 101, 1568),
(17270, 'Davlameti', 101, 1568),
(17271, 'Deglur', 101, 1568),
(17272, 'Dehu Road', 101, 1568),
(17273, 'Deolali', 101, 1568),
(17274, 'Deolali Pravara', 101, 1568),
(17275, 'Deoli', 101, 1568),
(17276, 'Desaiganj', 101, 1568),
(17277, 'Deulgaon Raja', 101, 1568),
(17278, 'Dewhadi', 101, 1568),
(17279, 'Dharangaon', 101, 1568),
(17280, 'Dharmabad', 101, 1568),
(17281, 'Dharur', 101, 1568),
(17282, 'Dhatau', 101, 1568),
(17283, 'Dhule', 101, 1568),
(17284, 'Digdoh', 101, 1568),
(17285, 'Diglur', 101, 1568),
(17286, 'Digras', 101, 1568),
(17287, 'Dombivli', 101, 1568),
(17288, 'Dondaicha', 101, 1568),
(17289, 'Dudhani', 101, 1568),
(17290, 'Durgapur', 101, 1568),
(17291, 'Dyane', 101, 1568),
(17292, 'Edandol', 101, 1568),
(17293, 'Eklahare', 101, 1568),
(17294, 'Faizpur', 101, 1568),
(17295, 'Fekari', 101, 1568),
(17296, 'Gadchiroli', 101, 1568),
(17297, 'Gadhinghaj', 101, 1568),
(17298, 'Gandhi Nagar', 101, 1568),
(17299, 'Ganeshpur', 101, 1568),
(17300, 'Gangakher', 101, 1568),
(17301, 'Gangapur', 101, 1568),
(17302, 'Gevrai', 101, 1568),
(17303, 'Ghatanji', 101, 1568),
(17304, 'Ghoti', 101, 1568),
(17305, 'Ghugus', 101, 1568),
(17306, 'Ghulewadi', 101, 1568),
(17307, 'Godoli', 101, 1568),
(17308, 'Gondia', 101, 1568),
(17309, 'Guhagar', 101, 1568),
(17310, 'Hadgaon', 101, 1568),
(17311, 'Harnai Beach', 101, 1568),
(17312, 'Hinganghat', 101, 1568),
(17313, 'Hingoli', 101, 1568),
(17314, 'Hupari', 101, 1568),
(17315, 'Ichalkaranji', 101, 1568),
(17316, 'Igatpuri', 101, 1568),
(17317, 'Indapur', 101, 1568),
(17318, 'Jaisinghpur', 101, 1568),
(17319, 'Jalgaon', 101, 1568),
(17320, 'Jalna', 101, 1568),
(17321, 'Jamkhed', 101, 1568),
(17322, 'Jawhar', 101, 1568),
(17323, 'Jaysingpur', 101, 1568),
(17324, 'Jejuri', 101, 1568),
(17325, 'Jintur', 101, 1568),
(17326, 'Junnar', 101, 1568),
(17327, 'Kabnur', 101, 1568),
(17328, 'Kagal', 101, 1568),
(17329, 'Kalamb', 101, 1568),
(17330, 'Kalamnuri', 101, 1568),
(17331, 'Kalas', 101, 1568),
(17332, 'Kalmeshwar', 101, 1568),
(17333, 'Kalundre', 101, 1568),
(17334, 'Kalyan', 101, 1568),
(17335, 'Kamthi', 101, 1568),
(17336, 'Kamthi Cantonment', 101, 1568),
(17337, 'Kandari', 101, 1568),
(17338, 'Kandhar', 101, 1568),
(17339, 'Kandri', 101, 1568),
(17340, 'Kandri II', 101, 1568),
(17341, 'Kanhan', 101, 1568),
(17342, 'Kankavli', 101, 1568),
(17343, 'Kannad', 101, 1568),
(17344, 'Karad', 101, 1568),
(17345, 'Karanja', 101, 1568),
(17346, 'Karanje Tarf', 101, 1568),
(17347, 'Karivali', 101, 1568),
(17348, 'Karjat', 101, 1568),
(17349, 'Karmala', 101, 1568),
(17350, 'Kasara Budruk', 101, 1568),
(17351, 'Katai', 101, 1568),
(17352, 'Katkar', 101, 1568),
(17353, 'Katol', 101, 1568),
(17354, 'Kegaon', 101, 1568),
(17355, 'Khadkale', 101, 1568),
(17356, 'Khadki', 101, 1568),
(17357, 'Khamgaon', 101, 1568),
(17358, 'Khapa', 101, 1568),
(17359, 'Kharadi', 101, 1568),
(17360, 'Kharakvasla', 101, 1568),
(17361, 'Khed', 101, 1568),
(17362, 'Kherdi', 101, 1568),
(17363, 'Khoni', 101, 1568),
(17364, 'Khopoli', 101, 1568),
(17365, 'Khuldabad', 101, 1568),
(17366, 'Kinwat', 101, 1568),
(17367, 'Kodoli', 101, 1568),
(17368, 'Kolhapur', 101, 1568),
(17369, 'Kon', 101, 1568),
(17370, 'Kondumal', 101, 1568),
(17371, 'Kopargaon', 101, 1568),
(17372, 'Kopharad', 101, 1568),
(17373, 'Koradi', 101, 1568),
(17374, 'Koregaon', 101, 1568),
(17375, 'Korochi', 101, 1568),
(17376, 'Kudal', 101, 1568),
(17377, 'Kundaim', 101, 1568),
(17378, 'Kundalwadi', 101, 1568),
(17379, 'Kurandvad', 101, 1568),
(17380, 'Kurduvadi', 101, 1568),
(17381, 'Kusgaon Budruk', 101, 1568),
(17382, 'Lanja', 101, 1568),
(17383, 'Lasalgaon', 101, 1568),
(17384, 'Latur', 101, 1568),
(17385, 'Loha', 101, 1568),
(17386, 'Lohegaon', 101, 1568),
(17387, 'Lonar', 101, 1568),
(17388, 'Lonavala', 101, 1568),
(17389, 'Madhavnagar', 101, 1568),
(17390, 'Mahabaleshwar', 101, 1568),
(17391, 'Mahad', 101, 1568),
(17392, 'Mahadula', 101, 1568),
(17393, 'Maindargi', 101, 1568),
(17394, 'Majalgaon', 101, 1568),
(17395, 'Malegaon', 101, 1568),
(17396, 'Malgaon', 101, 1568),
(17397, 'Malkapur', 101, 1568),
(17398, 'Malwan', 101, 1568),
(17399, 'Manadur', 101, 1568),
(17400, 'Manchar', 101, 1568),
(17401, 'Mangalvedhe', 101, 1568),
(17402, 'Mangrul Pir', 101, 1568),
(17403, 'Manmad', 101, 1568),
(17404, 'Manor', 101, 1568),
(17405, 'Mansar', 101, 1568),
(17406, 'Manwath', 101, 1568),
(17407, 'Mapuca', 101, 1568),
(17408, 'Matheran', 101, 1568),
(17409, 'Mehkar', 101, 1568),
(17410, 'Mhasla', 101, 1568),
(17411, 'Mhaswad', 101, 1568),
(17412, 'Mira Bhayandar', 101, 1568),
(17413, 'Miraj', 101, 1568),
(17414, 'Mohpa', 101, 1568),
(17415, 'Mohpada', 101, 1568),
(17416, 'Moram', 101, 1568),
(17417, 'Morshi', 101, 1568),
(17418, 'Mowad', 101, 1568),
(17419, 'Mudkhed', 101, 1568),
(17420, 'Mukhed', 101, 1568),
(17421, 'Mul', 101, 1568),
(17422, 'Mulshi', 101, 1568),
(17423, 'Mumbai', 101, 1568),
(17424, 'Murbad', 101, 1568),
(17425, 'Murgud', 101, 1568),
(17426, 'Murtijapur', 101, 1568),
(17427, 'Murud', 101, 1568),
(17428, 'Nachane', 101, 1568),
(17429, 'Nagardeole', 101, 1568),
(17430, 'Nagothane', 101, 1568),
(17431, 'Nagpur', 101, 1568),
(17432, 'Nakoda', 101, 1568),
(17433, 'Nalasopara', 101, 1568),
(17434, 'Naldurg', 101, 1568),
(17435, 'Nanded', 101, 1568),
(17436, 'Nandgaon', 101, 1568),
(17437, 'Nandura', 101, 1568),
(17438, 'Nandurbar', 101, 1568),
(17439, 'Narkhed', 101, 1568),
(17440, 'Nashik', 101, 1568),
(17441, 'Navapur', 101, 1568),
(17442, 'Navi Mumbai', 101, 1568),
(17443, 'Navi Mumbai Panvel', 101, 1568),
(17444, 'Neral', 101, 1568),
(17445, 'Nigdi', 101, 1568),
(17446, 'Nilanga', 101, 1568),
(17447, 'Nildoh', 101, 1568),
(17448, 'Nimbhore', 101, 1568),
(17449, 'Ojhar', 101, 1568),
(17450, 'Osmanabad', 101, 1568),
(17451, 'Pachgaon', 101, 1568),
(17452, 'Pachora', 101, 1568),
(17453, 'Padagha', 101, 1568),
(17454, 'Paithan', 101, 1568),
(17455, 'Palghar', 101, 1568),
(17456, 'Pali', 101, 1568),
(17457, 'Panchgani', 101, 1568),
(17458, 'Pandhakarwada', 101, 1568),
(17459, 'Pandharpur', 101, 1568),
(17460, 'Panhala', 101, 1568),
(17461, 'Panvel', 101, 1568),
(17462, 'Paranda', 101, 1568),
(17463, 'Parbhani', 101, 1568),
(17464, 'Parli', 101, 1568),
(17465, 'Parola', 101, 1568),
(17466, 'Partur', 101, 1568),
(17467, 'Pasthal', 101, 1568),
(17468, 'Patan', 101, 1568),
(17469, 'Pathardi', 101, 1568),
(17470, 'Pathri', 101, 1568),
(17471, 'Patur', 101, 1568),
(17472, 'Pawni', 101, 1568),
(17473, 'Pen', 101, 1568),
(17474, 'Pethumri', 101, 1568),
(17475, 'Phaltan', 101, 1568),
(17476, 'Pimpri', 101, 1568),
(17477, 'Poladpur', 101, 1568),
(17478, 'Pulgaon', 101, 1568),
(17479, 'Pune', 101, 1568),
(17480, 'Pune Cantonment', 101, 1568),
(17481, 'Purna', 101, 1568),
(17482, 'Purushottamnagar', 101, 1568),
(17483, 'Pusad', 101, 1568),
(17484, 'Rahimatpur', 101, 1568),
(17485, 'Rahta Pimplas', 101, 1568),
(17486, 'Rahuri', 101, 1568),
(17487, 'Raigad', 101, 1568),
(17488, 'Rajapur', 101, 1568),
(17489, 'Rajgurunagar', 101, 1568),
(17490, 'Rajur', 101, 1568),
(17491, 'Rajura', 101, 1568),
(17492, 'Ramtek', 101, 1568),
(17493, 'Ratnagiri', 101, 1568),
(17494, 'Ravalgaon', 101, 1568),
(17495, 'Raver', 101, 1568),
(17496, 'Revadanda', 101, 1568),
(17497, 'Risod', 101, 1568),
(17498, 'Roha Ashtami', 101, 1568),
(17499, 'Sakri', 101, 1568),
(17500, 'Sandor', 101, 1568),
(17501, 'Sangamner', 101, 1568),
(17502, 'Sangli', 101, 1568),
(17503, 'Sangole', 101, 1568),
(17504, 'Sasti', 101, 1568),
(17505, 'Sasvad', 101, 1568),
(17506, 'Satana', 101, 1568),
(17507, 'Satara', 101, 1568),
(17508, 'Savantvadi', 101, 1568),
(17509, 'Savda', 101, 1568),
(17510, 'Savner', 101, 1568),
(17511, 'Sawari Jawharnagar', 101, 1568),
(17512, 'Selu', 101, 1568),
(17513, 'Shahada', 101, 1568),
(17514, 'Shahapur', 101, 1568),
(17515, 'Shegaon', 101, 1568),
(17516, 'Shelar', 101, 1568),
(17517, 'Shendurjana', 101, 1568),
(17518, 'Shirdi', 101, 1568),
(17519, 'Shirgaon', 101, 1568),
(17520, 'Shirpur', 101, 1568),
(17521, 'Shirur', 101, 1568),
(17522, 'Shirwal', 101, 1568),
(17523, 'Shivatkar', 101, 1568),
(17524, 'Shrigonda', 101, 1568),
(17525, 'Shrirampur', 101, 1568),
(17526, 'Shrirampur Rural', 101, 1568),
(17527, 'Sillewada', 101, 1568),
(17528, 'Sillod', 101, 1568),
(17529, 'Sindhudurg', 101, 1568),
(17530, 'Sindi', 101, 1568),
(17531, 'Sindi Turf Hindnagar', 101, 1568),
(17532, 'Sindkhed Raja', 101, 1568),
(17533, 'Singnapur', 101, 1568),
(17534, 'Sinnar', 101, 1568),
(17535, 'Sirur', 101, 1568),
(17536, 'Sitasawangi', 101, 1568),
(17537, 'Solapur', 101, 1568),
(17538, 'Sonai', 101, 1568),
(17539, 'Sonegaon', 101, 1568),
(17540, 'Soyagaon', 101, 1568),
(17541, 'Srivardhan', 101, 1568),
(17542, 'Surgana', 101, 1568),
(17543, 'Talegaon Dabhade', 101, 1568),
(17544, 'Taloda', 101, 1568),
(17545, 'Taloja', 101, 1568),
(17546, 'Talwade', 101, 1568),
(17547, 'Tarapur', 101, 1568),
(17548, 'Tasgaon', 101, 1568),
(17549, 'Tathavade', 101, 1568),
(17550, 'Tekadi', 101, 1568),
(17551, 'Telhara', 101, 1568),
(17552, 'Thane', 101, 1568),
(17553, 'Tirira', 101, 1568),
(17554, 'Totaladoh', 101, 1568),
(17555, 'Trimbak', 101, 1568),
(17556, 'Tuljapur', 101, 1568),
(17557, 'Tumsar', 101, 1568),
(17558, 'Uchgaon', 101, 1568),
(17559, 'Udgir', 101, 1568),
(17560, 'Ulhasnagar', 101, 1568),
(17561, 'Umarga', 101, 1568),
(17562, 'Umarkhed', 101, 1568),
(17563, 'Umarsara', 101, 1568),
(17564, 'Umbar Pada Nandade', 101, 1568),
(17565, 'Umred', 101, 1568),
(17566, 'Umri Pragane Balapur', 101, 1568),
(17567, 'Uran', 101, 1568),
(17568, 'Uran Islampur', 101, 1568),
(17569, 'Utekhol', 101, 1568),
(17570, 'Vada', 101, 1568),
(17571, 'Vadgaon', 101, 1568),
(17572, 'Vadgaon Kasba', 101, 1568),
(17573, 'Vaijapur', 101, 1568),
(17574, 'Vanvadi', 101, 1568),
(17575, 'Varangaon', 101, 1568),
(17576, 'Vasai', 101, 1568),
(17577, 'Vasantnagar', 101, 1568),
(17578, 'Vashind', 101, 1568),
(17579, 'Vengurla', 101, 1568),
(17580, 'Virar', 101, 1568),
(17581, 'Visapur', 101, 1568),
(17582, 'Vite', 101, 1568),
(17583, 'Vithalwadi', 101, 1568),
(17584, 'Wadi', 101, 1568),
(17585, 'Waghapur', 101, 1568),
(17586, 'Wai', 101, 1568),
(17587, 'Wajegaon', 101, 1568),
(17588, 'Walani', 101, 1568),
(17589, 'Wanadongri', 101, 1568),
(17590, 'Wani', 101, 1568),
(17591, 'Wardha', 101, 1568),
(17592, 'Warora', 101, 1568),
(17593, 'Warthi', 101, 1568),
(17594, 'Warud', 101, 1568),
(17595, 'Washim', 101, 1568),
(17596, 'Yaval', 101, 1568),
(17597, 'Yavatmal', 101, 1568),
(17598, 'Yeola', 101, 1568),
(17599, 'Yerkheda', 101, 1568),
(17600, 'Andro', 101, 1569),
(17601, 'Bijoy Govinda', 101, 1569),
(17602, 'Bishnupur', 101, 1569),
(17603, 'Churachandpur', 101, 1569),
(17604, 'Heriok', 101, 1569),
(17605, 'Imphal', 101, 1569),
(17606, 'Jiribam', 101, 1569),
(17607, 'Kakching', 101, 1569),
(17608, 'Kakching Khunou', 101, 1569),
(17609, 'Khongman', 101, 1569),
(17610, 'Kumbi', 101, 1569),
(17611, 'Kwakta', 101, 1569),
(17612, 'Lamai', 101, 1569),
(17613, 'Lamjaotongba', 101, 1569),
(17614, 'Lamshang', 101, 1569),
(17615, 'Lilong', 101, 1569),
(17616, 'Mayang Imphal', 101, 1569),
(17617, 'Moirang', 101, 1569),
(17618, 'Moreh', 101, 1569),
(17619, 'Nambol', 101, 1569),
(17620, 'Naoriya Pakhanglakpa', 101, 1569),
(17621, 'Ningthoukhong', 101, 1569),
(17622, 'Oinam', 101, 1569),
(17623, 'Porompat', 101, 1569),
(17624, 'Samurou', 101, 1569),
(17625, 'Sekmai Bazar', 101, 1569),
(17626, 'Senapati', 101, 1569),
(17627, 'Sikhong Sekmai', 101, 1569),
(17628, 'Sugnu', 101, 1569),
(17629, 'Thongkhong Laxmi Bazar', 101, 1569),
(17630, 'Thoubal', 101, 1569),
(17631, 'Torban', 101, 1569),
(17632, 'Wangjing', 101, 1569),
(17633, 'Wangoi', 101, 1569),
(17634, 'Yairipok', 101, 1569),
(17635, 'Baghmara', 101, 1570),
(17636, 'Cherrapunji', 101, 1570),
(17637, 'Jawai', 101, 1570),
(17638, 'Madanrting', 101, 1570),
(17639, 'Mairang', 101, 1570),
(17640, 'Mawlai', 101, 1570),
(17641, 'Nongmynsong', 101, 1570),
(17642, 'Nongpoh', 101, 1570),
(17643, 'Nongstoin', 101, 1570),
(17644, 'Nongthymmai', 101, 1570),
(17645, 'Pynthorumkhrah', 101, 1570),
(17646, 'Resubelpara', 101, 1570),
(17647, 'Shillong', 101, 1570),
(17648, 'Shillong Cantonment', 101, 1570),
(17649, 'Tura', 101, 1570),
(17650, 'Williamnagar', 101, 1570),
(17651, 'Aizawl', 101, 1571),
(17652, 'Bairabi', 101, 1571),
(17653, 'Biate', 101, 1571),
(17654, 'Champhai', 101, 1571),
(17655, 'Darlawn', 101, 1571),
(17656, 'Hnahthial', 101, 1571),
(17657, 'Kawnpui', 101, 1571),
(17658, 'Khawhai', 101, 1571),
(17659, 'Khawzawl', 101, 1571),
(17660, 'Kolasib', 101, 1571),
(17661, 'Lengpui', 101, 1571),
(17662, 'Lunglei', 101, 1571),
(17663, 'Mamit', 101, 1571),
(17664, 'North Vanlaiphai', 101, 1571),
(17665, 'Saiha', 101, 1571),
(17666, 'Sairang', 101, 1571),
(17667, 'Saitul', 101, 1571),
(17668, 'Serchhip', 101, 1571),
(17669, 'Thenzawl', 101, 1571),
(17670, 'Tlabung', 101, 1571),
(17671, 'Vairengte', 101, 1571),
(17672, 'Zawlnuam', 101, 1571),
(17673, 'Chumukedima', 101, 1572),
(17674, 'Dimapur', 101, 1572),
(17675, 'Kohima', 101, 1572),
(17676, 'Mokokchung', 101, 1572),
(17677, 'Mon', 101, 1572),
(17678, 'Phek', 101, 1572),
(17679, 'Tuensang', 101, 1572),
(17680, 'Wokha', 101, 1572),
(17681, 'Zunheboto', 101, 1572),
(17682, 'Anandapur', 101, 1575),
(17683, 'Angul', 101, 1575),
(17684, 'Aska', 101, 1575),
(17685, 'Athgarh', 101, 1575),
(17686, 'Athmallik', 101, 1575),
(17687, 'Balagoda', 101, 1575),
(17688, 'Balangir', 101, 1575),
(17689, 'Balasore', 101, 1575),
(17690, 'Baleshwar', 101, 1575),
(17691, 'Balimeta', 101, 1575),
(17692, 'Balugaon', 101, 1575),
(17693, 'Banapur', 101, 1575),
(17694, 'Bangura', 101, 1575),
(17695, 'Banki', 101, 1575),
(17696, 'Banposh', 101, 1575),
(17697, 'Barbil', 101, 1575),
(17698, 'Bargarh', 101, 1575),
(17699, 'Baripada', 101, 1575),
(17700, 'Barpali', 101, 1575),
(17701, 'Basudebpur', 101, 1575),
(17702, 'Baudh', 101, 1575),
(17703, 'Belagachhia', 101, 1575),
(17704, 'Belaguntha', 101, 1575),
(17705, 'Belpahar', 101, 1575),
(17706, 'Berhampur', 101, 1575),
(17707, 'Bhadrak', 101, 1575),
(17708, 'Bhanjanagar', 101, 1575),
(17709, 'Bhawanipatna', 101, 1575),
(17710, 'Bhuban', 101, 1575),
(17711, 'Bhubaneswar', 101, 1575),
(17712, 'Binika', 101, 1575),
(17713, 'Birmitrapur', 101, 1575),
(17714, 'Bishama Katek', 101, 1575),
(17715, 'Bolangir', 101, 1575),
(17716, 'Brahmapur', 101, 1575),
(17717, 'Brajrajnagar', 101, 1575),
(17718, 'Buguda', 101, 1575),
(17719, 'Burla', 101, 1575),
(17720, 'Byasanagar', 101, 1575),
(17721, 'Champua', 101, 1575),
(17722, 'Chandapur', 101, 1575),
(17723, 'Chandbali', 101, 1575),
(17724, 'Chandili', 101, 1575),
(17725, 'Charibatia', 101, 1575),
(17726, 'Chatrapur', 101, 1575),
(17727, 'Chikitigarh', 101, 1575),
(17728, 'Chitrakonda', 101, 1575),
(17729, 'Choudwar', 101, 1575),
(17730, 'Cuttack', 101, 1575),
(17731, 'Dadhapatna', 101, 1575),
(17732, 'Daitari', 101, 1575),
(17733, 'Damanjodi', 101, 1575),
(17734, 'Deogarh', 101, 1575),
(17735, 'Deracolliery', 101, 1575),
(17736, 'Dhamanagar', 101, 1575),
(17737, 'Dhenkanal', 101, 1575),
(17738, 'Digapahandi', 101, 1575),
(17739, 'Dungamal', 101, 1575),
(17740, 'Fertilizer Corporation of Indi', 101, 1575),
(17741, 'Ganjam', 101, 1575),
(17742, 'Ghantapada', 101, 1575),
(17743, 'Gopalpur', 101, 1575),
(17744, 'Gudari', 101, 1575),
(17745, 'Gunupur', 101, 1575),
(17746, 'Hatibandha', 101, 1575),
(17747, 'Hinjilikatu', 101, 1575),
(17748, 'Hirakud', 101, 1575),
(17749, 'Jagatsinghapur', 101, 1575),
(17750, 'Jajpur', 101, 1575),
(17751, 'Jalda', 101, 1575),
(17752, 'Jaleswar', 101, 1575),
(17753, 'Jatni', 101, 1575),
(17754, 'Jaypur', 101, 1575),
(17755, 'Jeypore', 101, 1575),
(17756, 'Jharsuguda', 101, 1575),
(17757, 'Jhumpura', 101, 1575),
(17758, 'Joda', 101, 1575),
(17759, 'Junagarh', 101, 1575),
(17760, 'Kamakhyanagar', 101, 1575),
(17761, 'Kantabanji', 101, 1575),
(17762, 'Kantilo', 101, 1575),
(17763, 'Karanja', 101, 1575),
(17764, 'Kashinagara', 101, 1575),
(17765, 'Kataka', 101, 1575),
(17766, 'Kavisuryanagar', 101, 1575),
(17767, 'Kendrapara', 101, 1575),
(17768, 'Kendujhar', 101, 1575),
(17769, 'Keonjhar', 101, 1575),
(17770, 'Kesinga', 101, 1575),
(17771, 'Khaliapali', 101, 1575),
(17772, 'Khalikote', 101, 1575),
(17773, 'Khandaparha', 101, 1575),
(17774, 'Kharhial', 101, 1575),
(17775, 'Kharhial Road', 101, 1575),
(17776, 'Khatiguda', 101, 1575),
(17777, 'Khurda', 101, 1575),
(17778, 'Kochinda', 101, 1575),
(17779, 'Kodala', 101, 1575),
(17780, 'Konark', 101, 1575),
(17781, 'Koraput', 101, 1575),
(17782, 'Kotaparh', 101, 1575),
(17783, 'Lanjigarh', 101, 1575),
(17784, 'Lattikata', 101, 1575),
(17785, 'Makundapur', 101, 1575),
(17786, 'Malkangiri', 101, 1575),
(17787, 'Mukhiguda', 101, 1575),
(17788, 'Nabarangpur', 101, 1575),
(17789, 'Nalco', 101, 1575),
(17790, 'Naurangapur', 101, 1575),
(17791, 'Nayagarh', 101, 1575),
(17792, 'Nilagiri', 101, 1575),
(17793, 'Nimaparha', 101, 1575),
(17794, 'Nuapada', 101, 1575),
(17795, 'Nuapatna', 101, 1575),
(17796, 'OCL Industrialship', 101, 1575),
(17797, 'Padampur', 101, 1575),
(17798, 'Paradip', 101, 1575),
(17799, 'Paradwip', 101, 1575),
(17800, 'Parlakimidi', 101, 1575),
(17801, 'Patamundai', 101, 1575),
(17802, 'Patnagarh', 101, 1575),
(17803, 'Phulabani', 101, 1575),
(17804, 'Pipili', 101, 1575),
(17805, 'Polasara', 101, 1575),
(17806, 'Pratapsasan', 101, 1575),
(17807, 'Puri', 101, 1575),
(17808, 'Purushottampur', 101, 1575),
(17809, 'Rairangpur', 101, 1575),
(17810, 'Raj Gangpur', 101, 1575),
(17811, 'Rambha', 101, 1575),
(17812, 'Raurkela', 101, 1575),
(17813, 'Raurkela Civil Township', 101, 1575),
(17814, 'Rayagada', 101, 1575),
(17815, 'Redhakhol', 101, 1575),
(17816, 'Remuna', 101, 1575),
(17817, 'Rengali', 101, 1575),
(17818, 'Rourkela', 101, 1575),
(17819, 'Sambalpur', 101, 1575),
(17820, 'Sinapali', 101, 1575),
(17821, 'Sonepur', 101, 1575),
(17822, 'Sorada', 101, 1575),
(17823, 'Soro', 101, 1575),
(17824, 'Sunabeda', 101, 1575),
(17825, 'Sundargarh', 101, 1575),
(17826, 'Talcher', 101, 1575),
(17827, 'Talcher Thermal Power Station ', 101, 1575),
(17828, 'Tarabha', 101, 1575),
(17829, 'Tensa', 101, 1575),
(17830, 'Titlagarh', 101, 1575),
(17831, 'Udala', 101, 1575),
(17832, 'Udayagiri', 101, 1575),
(17833, 'Umarkot', 101, 1575),
(17834, 'Vikrampur', 101, 1575),
(17835, 'Ariankuppam', 101, 1577),
(17836, 'Karaikal', 101, 1577),
(17837, 'Kurumbapet', 101, 1577),
(17838, 'Mahe', 101, 1577),
(17839, 'Ozhukarai', 101, 1577),
(17840, 'Pondicherry', 101, 1577),
(17841, 'Villianur', 101, 1577),
(17842, 'Yanam', 101, 1577),
(17843, 'Abohar', 101, 1578),
(17844, 'Adampur', 101, 1578),
(17845, 'Ahmedgarh', 101, 1578),
(17846, 'Ajnala', 101, 1578),
(17847, 'Akalgarh', 101, 1578),
(17848, 'Alawalpur', 101, 1578),
(17849, 'Amloh', 101, 1578),
(17850, 'Amritsar', 101, 1578),
(17851, 'Amritsar Cantonment', 101, 1578),
(17852, 'Anandpur Sahib', 101, 1578),
(17853, 'Badhni Kalan', 101, 1578),
(17854, 'Bagh Purana', 101, 1578),
(17855, 'Balachaur', 101, 1578),
(17856, 'Banaur', 101, 1578),
(17857, 'Banga', 101, 1578),
(17858, 'Banur', 101, 1578),
(17859, 'Baretta', 101, 1578),
(17860, 'Bariwala', 101, 1578),
(17861, 'Barnala', 101, 1578),
(17862, 'Bassi Pathana', 101, 1578),
(17863, 'Batala', 101, 1578),
(17864, 'Bathinda', 101, 1578),
(17865, 'Begowal', 101, 1578),
(17866, 'Behrampur', 101, 1578),
(17867, 'Bhabat', 101, 1578),
(17868, 'Bhadur', 101, 1578),
(17869, 'Bhankharpur', 101, 1578),
(17870, 'Bharoli Kalan', 101, 1578),
(17871, 'Bhawanigarh', 101, 1578),
(17872, 'Bhikhi', 101, 1578),
(17873, 'Bhikhiwind', 101, 1578),
(17874, 'Bhisiana', 101, 1578),
(17875, 'Bhogpur', 101, 1578),
(17876, 'Bhuch', 101, 1578),
(17877, 'Bhulath', 101, 1578),
(17878, 'Budha Theh', 101, 1578),
(17879, 'Budhlada', 101, 1578),
(17880, 'Chima', 101, 1578),
(17881, 'Chohal', 101, 1578),
(17882, 'Dasuya', 101, 1578),
(17883, 'Daulatpur', 101, 1578),
(17884, 'Dera Baba Nanak', 101, 1578),
(17885, 'Dera Bassi', 101, 1578),
(17886, 'Dhanaula', 101, 1578),
(17887, 'Dharam Kot', 101, 1578),
(17888, 'Dhariwal', 101, 1578),
(17889, 'Dhilwan', 101, 1578),
(17890, 'Dhuri', 101, 1578),
(17891, 'Dinanagar', 101, 1578),
(17892, 'Dirba', 101, 1578),
(17893, 'Doraha', 101, 1578),
(17894, 'Faridkot', 101, 1578),
(17895, 'Fateh Nangal', 101, 1578),
(17896, 'Fatehgarh Churian', 101, 1578),
(17897, 'Fatehgarh Sahib', 101, 1578),
(17898, 'Fazilka', 101, 1578),
(17899, 'Firozpur', 101, 1578),
(17900, 'Firozpur Cantonment', 101, 1578),
(17901, 'Gardhiwala', 101, 1578),
(17902, 'Garhshankar', 101, 1578),
(17903, 'Ghagga', 101, 1578),
(17904, 'Ghanaur', 101, 1578),
(17905, 'Giddarbaha', 101, 1578),
(17906, 'Gobindgarh', 101, 1578),
(17907, 'Goniana', 101, 1578),
(17908, 'Goraya', 101, 1578),
(17909, 'Gurdaspur', 101, 1578),
(17910, 'Guru Har Sahai', 101, 1578),
(17911, 'Hajipur', 101, 1578),
(17912, 'Handiaya', 101, 1578),
(17913, 'Hariana', 101, 1578),
(17914, 'Hoshiarpur', 101, 1578),
(17915, 'Hussainpur', 101, 1578),
(17916, 'Jagraon', 101, 1578),
(17917, 'Jaitu', 101, 1578),
(17918, 'Jalalabad', 101, 1578),
(17919, 'Jalandhar', 101, 1578),
(17920, 'Jalandhar Cantonment', 101, 1578),
(17921, 'Jandiala', 101, 1578),
(17922, 'Jugial', 101, 1578),
(17923, 'Kalanaur', 101, 1578),
(17924, 'Kapurthala', 101, 1578),
(17925, 'Karoran', 101, 1578),
(17926, 'Kartarpur', 101, 1578),
(17927, 'Khamanon', 101, 1578),
(17928, 'Khanauri', 101, 1578),
(17929, 'Khanna', 101, 1578),
(17930, 'Kharar', 101, 1578),
(17931, 'Khem Karan', 101, 1578),
(17932, 'Kot Fatta', 101, 1578),
(17933, 'Kot Isa Khan', 101, 1578),
(17934, 'Kot Kapura', 101, 1578),
(17935, 'Kotkapura', 101, 1578),
(17936, 'Kurali', 101, 1578),
(17937, 'Lalru', 101, 1578),
(17938, 'Lehra Gaga', 101, 1578),
(17939, 'Lodhian Khas', 101, 1578),
(17940, 'Longowal', 101, 1578),
(17941, 'Ludhiana', 101, 1578),
(17942, 'Machhiwara', 101, 1578),
(17943, 'Mahilpur', 101, 1578),
(17944, 'Majitha', 101, 1578),
(17945, 'Makhu', 101, 1578),
(17946, 'Malaut', 101, 1578),
(17947, 'Malerkotla', 101, 1578),
(17948, 'Maloud', 101, 1578),
(17949, 'Mandi Gobindgarh', 101, 1578),
(17950, 'Mansa', 101, 1578),
(17951, 'Maur', 101, 1578),
(17952, 'Moga', 101, 1578),
(17953, 'Mohali', 101, 1578),
(17954, 'Moonak', 101, 1578),
(17955, 'Morinda', 101, 1578),
(17956, 'Mukerian', 101, 1578),
(17957, 'Muktsar', 101, 1578),
(17958, 'Mullanpur Dakha', 101, 1578),
(17959, 'Mullanpur Garibdas', 101, 1578),
(17960, 'Munak', 101, 1578),
(17961, 'Muradpura', 101, 1578),
(17962, 'Nabha', 101, 1578),
(17963, 'Nakodar', 101, 1578),
(17964, 'Nangal', 101, 1578),
(17965, 'Nawashahr', 101, 1578),
(17966, 'Naya Nangal', 101, 1578),
(17967, 'Nehon', 101, 1578),
(17968, 'Nurmahal', 101, 1578),
(17969, 'Pathankot', 101, 1578),
(17970, 'Patiala', 101, 1578),
(17971, 'Patti', 101, 1578),
(17972, 'Pattran', 101, 1578),
(17973, 'Payal', 101, 1578),
(17974, 'Phagwara', 101, 1578),
(17975, 'Phillaur', 101, 1578),
(17976, 'Qadian', 101, 1578),
(17977, 'Rahon', 101, 1578),
(17978, 'Raikot', 101, 1578),
(17979, 'Raja Sansi', 101, 1578),
(17980, 'Rajpura', 101, 1578),
(17981, 'Ram Das', 101, 1578),
(17982, 'Raman', 101, 1578),
(17983, 'Rampura', 101, 1578),
(17984, 'Rayya', 101, 1578),
(17985, 'Rupnagar', 101, 1578),
(17986, 'Rurki Kasba', 101, 1578),
(17987, 'Sahnewal', 101, 1578),
(17988, 'Samana', 101, 1578),
(17989, 'Samrala', 101, 1578),
(17990, 'Sanaur', 101, 1578),
(17991, 'Sangat', 101, 1578),
(17992, 'Sangrur', 101, 1578),
(17993, 'Sansarpur', 101, 1578),
(17994, 'Sardulgarh', 101, 1578),
(17995, 'Shahkot', 101, 1578),
(17996, 'Sham Churasi', 101, 1578),
(17997, 'Shekhpura', 101, 1578),
(17998, 'Sirhind', 101, 1578),
(17999, 'Sri Hargobindpur', 101, 1578),
(18000, 'Sujanpur', 101, 1578),
(18001, 'Sultanpur Lodhi', 101, 1578),
(18002, 'Sunam', 101, 1578),
(18003, 'Talwandi Bhai', 101, 1578),
(18004, 'Talwara', 101, 1578),
(18005, 'Tappa', 101, 1578),
(18006, 'Tarn Taran', 101, 1578),
(18007, 'Urmar Tanda', 101, 1578),
(18008, 'Zira', 101, 1578),
(18009, 'Zirakpur', 101, 1578),
(18010, 'Abu Road', 101, 1579),
(18011, 'Ajmer', 101, 1579),
(18012, 'Aklera', 101, 1579),
(18013, 'Alwar', 101, 1579),
(18014, 'Amet', 101, 1579),
(18015, 'Antah', 101, 1579),
(18016, 'Anupgarh', 101, 1579),
(18017, 'Asind', 101, 1579),
(18018, 'Bagar', 101, 1579),
(18019, 'Bagru', 101, 1579),
(18020, 'Bahror', 101, 1579),
(18021, 'Bakani', 101, 1579);
INSERT INTO `cities` (`id`, `name`, `country_id`, `state_id`) VALUES
(18022, 'Bali', 101, 1579),
(18023, 'Balotra', 101, 1579),
(18024, 'Bandikui', 101, 1579),
(18025, 'Banswara', 101, 1579),
(18026, 'Baran', 101, 1579),
(18027, 'Bari', 101, 1579),
(18028, 'Bari Sadri', 101, 1579),
(18029, 'Barmer', 101, 1579),
(18030, 'Basi', 101, 1579),
(18031, 'Basni Belima', 101, 1579),
(18032, 'Baswa', 101, 1579),
(18033, 'Bayana', 101, 1579),
(18034, 'Beawar', 101, 1579),
(18035, 'Begun', 101, 1579),
(18036, 'Bhadasar', 101, 1579),
(18037, 'Bhadra', 101, 1579),
(18038, 'Bhalariya', 101, 1579),
(18039, 'Bharatpur', 101, 1579),
(18040, 'Bhasawar', 101, 1579),
(18041, 'Bhawani Mandi', 101, 1579),
(18042, 'Bhawri', 101, 1579),
(18043, 'Bhilwara', 101, 1579),
(18044, 'Bhindar', 101, 1579),
(18045, 'Bhinmal', 101, 1579),
(18046, 'Bhiwadi', 101, 1579),
(18047, 'Bijoliya Kalan', 101, 1579),
(18048, 'Bikaner', 101, 1579),
(18049, 'Bilara', 101, 1579),
(18050, 'Bissau', 101, 1579),
(18051, 'Borkhera', 101, 1579),
(18052, 'Budhpura', 101, 1579),
(18053, 'Bundi', 101, 1579),
(18054, 'Chatsu', 101, 1579),
(18055, 'Chechat', 101, 1579),
(18056, 'Chhabra', 101, 1579),
(18057, 'Chhapar', 101, 1579),
(18058, 'Chhipa Barod', 101, 1579),
(18059, 'Chhoti Sadri', 101, 1579),
(18060, 'Chirawa', 101, 1579),
(18061, 'Chittaurgarh', 101, 1579),
(18062, 'Chittorgarh', 101, 1579),
(18063, 'Chomun', 101, 1579),
(18064, 'Churu', 101, 1579),
(18065, 'Daosa', 101, 1579),
(18066, 'Dariba', 101, 1579),
(18067, 'Dausa', 101, 1579),
(18068, 'Deoli', 101, 1579),
(18069, 'Deshnok', 101, 1579),
(18070, 'Devgarh', 101, 1579),
(18071, 'Devli', 101, 1579),
(18072, 'Dhariawad', 101, 1579),
(18073, 'Dhaulpur', 101, 1579),
(18074, 'Dholpur', 101, 1579),
(18075, 'Didwana', 101, 1579),
(18076, 'Dig', 101, 1579),
(18077, 'Dungargarh', 101, 1579),
(18078, 'Dungarpur', 101, 1579),
(18079, 'Falna', 101, 1579),
(18080, 'Fatehnagar', 101, 1579),
(18081, 'Fatehpur', 101, 1579),
(18082, 'Gajsinghpur', 101, 1579),
(18083, 'Galiakot', 101, 1579),
(18084, 'Ganganagar', 101, 1579),
(18085, 'Gangapur', 101, 1579),
(18086, 'Goredi Chancha', 101, 1579),
(18087, 'Gothra', 101, 1579),
(18088, 'Govindgarh', 101, 1579),
(18089, 'Gulabpura', 101, 1579),
(18090, 'Hanumangarh', 101, 1579),
(18091, 'Hindaun', 101, 1579),
(18092, 'Indragarh', 101, 1579),
(18093, 'Jahazpur', 101, 1579),
(18094, 'Jaipur', 101, 1579),
(18095, 'Jaisalmer', 101, 1579),
(18096, 'Jaiselmer', 101, 1579),
(18097, 'Jaitaran', 101, 1579),
(18098, 'Jalore', 101, 1579),
(18099, 'Jhalawar', 101, 1579),
(18100, 'Jhalrapatan', 101, 1579),
(18101, 'Jhunjhunun', 101, 1579),
(18102, 'Jobner', 101, 1579),
(18103, 'Jodhpur', 101, 1579),
(18104, 'Kaithun', 101, 1579),
(18105, 'Kaman', 101, 1579),
(18106, 'Kankroli', 101, 1579),
(18107, 'Kanor', 101, 1579),
(18108, 'Kapasan', 101, 1579),
(18109, 'Kaprain', 101, 1579),
(18110, 'Karanpura', 101, 1579),
(18111, 'Karauli', 101, 1579),
(18112, 'Kekri', 101, 1579),
(18113, 'Keshorai Patan', 101, 1579),
(18114, 'Kesrisinghpur', 101, 1579),
(18115, 'Khairthal', 101, 1579),
(18116, 'Khandela', 101, 1579),
(18117, 'Khanpur', 101, 1579),
(18118, 'Kherli', 101, 1579),
(18119, 'Kherliganj', 101, 1579),
(18120, 'Kherwara Chhaoni', 101, 1579),
(18121, 'Khetri', 101, 1579),
(18122, 'Kiranipura', 101, 1579),
(18123, 'Kishangarh', 101, 1579),
(18124, 'Kishangarh Ranwal', 101, 1579),
(18125, 'Kolvi Rajendrapura', 101, 1579),
(18126, 'Kot Putli', 101, 1579),
(18127, 'Kota', 101, 1579),
(18128, 'Kuchaman', 101, 1579),
(18129, 'Kuchera', 101, 1579),
(18130, 'Kumbhalgarh', 101, 1579),
(18131, 'Kumbhkot', 101, 1579),
(18132, 'Kumher', 101, 1579),
(18133, 'Kushalgarh', 101, 1579),
(18134, 'Lachhmangarh', 101, 1579),
(18135, 'Ladnun', 101, 1579),
(18136, 'Lakheri', 101, 1579),
(18137, 'Lalsot', 101, 1579),
(18138, 'Losal', 101, 1579),
(18139, 'Madanganj', 101, 1579),
(18140, 'Mahu Kalan', 101, 1579),
(18141, 'Mahwa', 101, 1579),
(18142, 'Makrana', 101, 1579),
(18143, 'Malpura', 101, 1579),
(18144, 'Mandal', 101, 1579),
(18145, 'Mandalgarh', 101, 1579),
(18146, 'Mandawar', 101, 1579),
(18147, 'Mandwa', 101, 1579),
(18148, 'Mangrol', 101, 1579),
(18149, 'Manohar Thana', 101, 1579),
(18150, 'Manoharpur', 101, 1579),
(18151, 'Marwar', 101, 1579),
(18152, 'Merta', 101, 1579),
(18153, 'Modak', 101, 1579),
(18154, 'Mount Abu', 101, 1579),
(18155, 'Mukandgarh', 101, 1579),
(18156, 'Mundwa', 101, 1579),
(18157, 'Nadbai', 101, 1579),
(18158, 'Naenwa', 101, 1579),
(18159, 'Nagar', 101, 1579),
(18160, 'Nagaur', 101, 1579),
(18161, 'Napasar', 101, 1579),
(18162, 'Naraina', 101, 1579),
(18163, 'Nasirabad', 101, 1579),
(18164, 'Nathdwara', 101, 1579),
(18165, 'Nawa', 101, 1579),
(18166, 'Nawalgarh', 101, 1579),
(18167, 'Neem Ka Thana', 101, 1579),
(18168, 'Neemrana', 101, 1579),
(18169, 'Newa Talai', 101, 1579),
(18170, 'Nimaj', 101, 1579),
(18171, 'Nimbahera', 101, 1579),
(18172, 'Niwai', 101, 1579),
(18173, 'Nohar', 101, 1579),
(18174, 'Nokha', 101, 1579),
(18175, 'One SGM', 101, 1579),
(18176, 'Padampur', 101, 1579),
(18177, 'Pali', 101, 1579),
(18178, 'Partapur', 101, 1579),
(18179, 'Parvatsar', 101, 1579),
(18180, 'Pasoond', 101, 1579),
(18181, 'Phalna', 101, 1579),
(18182, 'Phalodi', 101, 1579),
(18183, 'Phulera', 101, 1579),
(18184, 'Pilani', 101, 1579),
(18185, 'Pilibanga', 101, 1579),
(18186, 'Pindwara', 101, 1579),
(18187, 'Pipalia Kalan', 101, 1579),
(18188, 'Pipar', 101, 1579),
(18189, 'Pirawa', 101, 1579),
(18190, 'Pokaran', 101, 1579),
(18191, 'Pratapgarh', 101, 1579),
(18192, 'Pushkar', 101, 1579),
(18193, 'Raipur', 101, 1579),
(18194, 'Raisinghnagar', 101, 1579),
(18195, 'Rajakhera', 101, 1579),
(18196, 'Rajaldesar', 101, 1579),
(18197, 'Rajgarh', 101, 1579),
(18198, 'Rajsamand', 101, 1579),
(18199, 'Ramganj Mandi', 101, 1579),
(18200, 'Ramgarh', 101, 1579),
(18201, 'Rani', 101, 1579),
(18202, 'Raniwara', 101, 1579),
(18203, 'Ratan Nagar', 101, 1579),
(18204, 'Ratangarh', 101, 1579),
(18205, 'Rawatbhata', 101, 1579),
(18206, 'Rawatsar', 101, 1579),
(18207, 'Rikhabdev', 101, 1579),
(18208, 'Ringas', 101, 1579),
(18209, 'Sadri', 101, 1579),
(18210, 'Sadulshahar', 101, 1579),
(18211, 'Sagwara', 101, 1579),
(18212, 'Salumbar', 101, 1579),
(18213, 'Sambhar', 101, 1579),
(18214, 'Samdari', 101, 1579),
(18215, 'Sanchor', 101, 1579),
(18216, 'Sangariya', 101, 1579),
(18217, 'Sangod', 101, 1579),
(18218, 'Sardarshahr', 101, 1579),
(18219, 'Sarwar', 101, 1579),
(18220, 'Satal Kheri', 101, 1579),
(18221, 'Sawai Madhopur', 101, 1579),
(18222, 'Sewan Kalan', 101, 1579),
(18223, 'Shahpura', 101, 1579),
(18224, 'Sheoganj', 101, 1579),
(18225, 'Sikar', 101, 1579),
(18226, 'Sirohi', 101, 1579),
(18227, 'Siwana', 101, 1579),
(18228, 'Sogariya', 101, 1579),
(18229, 'Sojat', 101, 1579),
(18230, 'Sojat Road', 101, 1579),
(18231, 'Sri Madhopur', 101, 1579),
(18232, 'Sriganganagar', 101, 1579),
(18233, 'Sujangarh', 101, 1579),
(18234, 'Suket', 101, 1579),
(18235, 'Sumerpur', 101, 1579),
(18236, 'Sunel', 101, 1579),
(18237, 'Surajgarh', 101, 1579),
(18238, 'Suratgarh', 101, 1579),
(18239, 'Swaroopganj', 101, 1579),
(18240, 'Takhatgarh', 101, 1579),
(18241, 'Taranagar', 101, 1579),
(18242, 'Three STR', 101, 1579),
(18243, 'Tijara', 101, 1579),
(18244, 'Toda Bhim', 101, 1579),
(18245, 'Toda Raisingh', 101, 1579),
(18246, 'Todra', 101, 1579),
(18247, 'Tonk', 101, 1579),
(18248, 'Udaipur', 101, 1579),
(18249, 'Udpura', 101, 1579),
(18250, 'Uniara', 101, 1579),
(18251, 'Vanasthali', 101, 1579),
(18252, 'Vidyavihar', 101, 1579),
(18253, 'Vijainagar', 101, 1579),
(18254, 'Viratnagar', 101, 1579),
(18255, 'Wer', 101, 1579),
(18256, 'Gangtok', 101, 1580),
(18257, 'Gezing', 101, 1580),
(18258, 'Jorethang', 101, 1580),
(18259, 'Mangan', 101, 1580),
(18260, 'Namchi', 101, 1580),
(18261, 'Naya Bazar', 101, 1580),
(18262, 'No City', 101, 1580),
(18263, 'Rangpo', 101, 1580),
(18264, 'Sikkim', 101, 1580),
(18265, 'Singtam', 101, 1580),
(18266, 'Upper Tadong', 101, 1580),
(18267, 'Abiramam', 101, 1581),
(18268, 'Achampudur', 101, 1581),
(18269, 'Acharapakkam', 101, 1581),
(18270, 'Acharipallam', 101, 1581),
(18271, 'Achipatti', 101, 1581),
(18272, 'Adikaratti', 101, 1581),
(18273, 'Adiramapattinam', 101, 1581),
(18274, 'Aduturai', 101, 1581),
(18275, 'Adyar', 101, 1581),
(18276, 'Agaram', 101, 1581),
(18277, 'Agasthiswaram', 101, 1581),
(18278, 'Akkaraipettai', 101, 1581),
(18279, 'Alagappapuram', 101, 1581),
(18280, 'Alagapuri', 101, 1581),
(18281, 'Alampalayam', 101, 1581),
(18282, 'Alandur', 101, 1581),
(18283, 'Alanganallur', 101, 1581),
(18284, 'Alangayam', 101, 1581),
(18285, 'Alangudi', 101, 1581),
(18286, 'Alangulam', 101, 1581),
(18287, 'Alanthurai', 101, 1581),
(18288, 'Alapakkam', 101, 1581),
(18289, 'Allapuram', 101, 1581),
(18290, 'Alur', 101, 1581),
(18291, 'Alwar Tirunagari', 101, 1581),
(18292, 'Alwarkurichi', 101, 1581),
(18293, 'Ambasamudram', 101, 1581),
(18294, 'Ambur', 101, 1581),
(18295, 'Ammainaickanur', 101, 1581),
(18296, 'Ammaparikuppam', 101, 1581),
(18297, 'Ammapettai', 101, 1581),
(18298, 'Ammavarikuppam', 101, 1581),
(18299, 'Ammur', 101, 1581),
(18300, 'Anaimalai', 101, 1581),
(18301, 'Anaiyur', 101, 1581),
(18302, 'Anakaputhur', 101, 1581),
(18303, 'Ananthapuram', 101, 1581),
(18304, 'Andanappettai', 101, 1581),
(18305, 'Andipalayam', 101, 1581),
(18306, 'Andippatti', 101, 1581),
(18307, 'Anjugramam', 101, 1581),
(18308, 'Annamalainagar', 101, 1581),
(18309, 'Annavasal', 101, 1581),
(18310, 'Annur', 101, 1581),
(18311, 'Anthiyur', 101, 1581),
(18312, 'Appakudal', 101, 1581),
(18313, 'Arachalur', 101, 1581),
(18314, 'Arakandanallur', 101, 1581),
(18315, 'Arakonam', 101, 1581),
(18316, 'Aralvaimozhi', 101, 1581),
(18317, 'Arani', 101, 1581),
(18318, 'Arani Road', 101, 1581),
(18319, 'Arantangi', 101, 1581),
(18320, 'Arasiramani', 101, 1581),
(18321, 'Aravakurichi', 101, 1581),
(18322, 'Aravankadu', 101, 1581),
(18323, 'Arcot', 101, 1581),
(18324, 'Arimalam', 101, 1581),
(18325, 'Ariyalur', 101, 1581),
(18326, 'Ariyappampalayam', 101, 1581),
(18327, 'Ariyur', 101, 1581),
(18328, 'Arni', 101, 1581),
(18329, 'Arulmigu Thirumuruganpundi', 101, 1581),
(18330, 'Arumanai', 101, 1581),
(18331, 'Arumbavur', 101, 1581),
(18332, 'Arumuganeri', 101, 1581),
(18333, 'Aruppukkottai', 101, 1581),
(18334, 'Ashokapuram', 101, 1581),
(18335, 'Athani', 101, 1581),
(18336, 'Athanur', 101, 1581),
(18337, 'Athimarapatti', 101, 1581),
(18338, 'Athipattu', 101, 1581),
(18339, 'Athur', 101, 1581),
(18340, 'Attayyampatti', 101, 1581),
(18341, 'Attur', 101, 1581),
(18342, 'Auroville', 101, 1581),
(18343, 'Avadattur', 101, 1581),
(18344, 'Avadi', 101, 1581),
(18345, 'Avalpundurai', 101, 1581),
(18346, 'Avaniapuram', 101, 1581),
(18347, 'Avinashi', 101, 1581),
(18348, 'Ayakudi', 101, 1581),
(18349, 'Ayanadaippu', 101, 1581),
(18350, 'Aygudi', 101, 1581),
(18351, 'Ayothiapattinam', 101, 1581),
(18352, 'Ayyalur', 101, 1581),
(18353, 'Ayyampalayam', 101, 1581),
(18354, 'Ayyampettai', 101, 1581),
(18355, 'Azhagiapandiapuram', 101, 1581),
(18356, 'Balakrishnampatti', 101, 1581),
(18357, 'Balakrishnapuram', 101, 1581),
(18358, 'Balapallam', 101, 1581),
(18359, 'Balasamudram', 101, 1581),
(18360, 'Bargur', 101, 1581),
(18361, 'Belur', 101, 1581),
(18362, 'Berhatty', 101, 1581),
(18363, 'Bhavani', 101, 1581),
(18364, 'Bhawanisagar', 101, 1581),
(18365, 'Bhuvanagiri', 101, 1581),
(18366, 'Bikketti', 101, 1581),
(18367, 'Bodinayakkanur', 101, 1581),
(18368, 'Brahmana Periya Agraharam', 101, 1581),
(18369, 'Buthapandi', 101, 1581),
(18370, 'Buthipuram', 101, 1581),
(18371, 'Chatrapatti', 101, 1581),
(18372, 'Chembarambakkam', 101, 1581),
(18373, 'Chengalpattu', 101, 1581),
(18374, 'Chengam', 101, 1581),
(18375, 'Chennai', 101, 1581),
(18376, 'Chennasamudram', 101, 1581),
(18377, 'Chennimalai', 101, 1581),
(18378, 'Cheranmadevi', 101, 1581),
(18379, 'Cheruvanki', 101, 1581),
(18380, 'Chetpet', 101, 1581),
(18381, 'Chettiarpatti', 101, 1581),
(18382, 'Chettipalaiyam', 101, 1581),
(18383, 'Chettipalayam Cantonment', 101, 1581),
(18384, 'Chettithangal', 101, 1581),
(18385, 'Cheyur', 101, 1581),
(18386, 'Cheyyar', 101, 1581),
(18387, 'Chidambaram', 101, 1581),
(18388, 'Chinalapatti', 101, 1581),
(18389, 'Chinna Anuppanadi', 101, 1581),
(18390, 'Chinna Salem', 101, 1581),
(18391, 'Chinnakkampalayam', 101, 1581),
(18392, 'Chinnammanur', 101, 1581),
(18393, 'Chinnampalaiyam', 101, 1581),
(18394, 'Chinnasekkadu', 101, 1581),
(18395, 'Chinnavedampatti', 101, 1581),
(18396, 'Chitlapakkam', 101, 1581),
(18397, 'Chittodu', 101, 1581),
(18398, 'Cholapuram', 101, 1581),
(18399, 'Coimbatore', 101, 1581),
(18400, 'Coonoor', 101, 1581),
(18401, 'Courtalam', 101, 1581),
(18402, 'Cuddalore', 101, 1581),
(18403, 'Dalavaipatti', 101, 1581),
(18404, 'Darasuram', 101, 1581),
(18405, 'Denkanikottai', 101, 1581),
(18406, 'Desur', 101, 1581),
(18407, 'Devadanapatti', 101, 1581),
(18408, 'Devakkottai', 101, 1581),
(18409, 'Devakottai', 101, 1581),
(18410, 'Devanangurichi', 101, 1581),
(18411, 'Devarshola', 101, 1581),
(18412, 'Devasthanam', 101, 1581),
(18413, 'Dhalavoipuram', 101, 1581),
(18414, 'Dhali', 101, 1581),
(18415, 'Dhaliyur', 101, 1581),
(18416, 'Dharapadavedu', 101, 1581),
(18417, 'Dharapuram', 101, 1581),
(18418, 'Dharmapuri', 101, 1581),
(18419, 'Dindigul', 101, 1581),
(18420, 'Dusi', 101, 1581),
(18421, 'Edaganasalai', 101, 1581),
(18422, 'Edaikodu', 101, 1581),
(18423, 'Edakalinadu', 101, 1581),
(18424, 'Elathur', 101, 1581),
(18425, 'Elayirampannai', 101, 1581),
(18426, 'Elumalai', 101, 1581),
(18427, 'Eral', 101, 1581),
(18428, 'Eraniel', 101, 1581),
(18429, 'Eriodu', 101, 1581),
(18430, 'Erode', 101, 1581),
(18431, 'Erumaipatti', 101, 1581),
(18432, 'Eruvadi', 101, 1581),
(18433, 'Ethapur', 101, 1581),
(18434, 'Ettaiyapuram', 101, 1581),
(18435, 'Ettimadai', 101, 1581),
(18436, 'Ezhudesam', 101, 1581),
(18437, 'Ganapathipuram', 101, 1581),
(18438, 'Gandhi Nagar', 101, 1581),
(18439, 'Gangaikondan', 101, 1581),
(18440, 'Gangavalli', 101, 1581),
(18441, 'Ganguvarpatti', 101, 1581),
(18442, 'Gingi', 101, 1581),
(18443, 'Gopalasamudram', 101, 1581),
(18444, 'Gopichettipalaiyam', 101, 1581),
(18445, 'Gudalur', 101, 1581),
(18446, 'Gudiyattam', 101, 1581),
(18447, 'Guduvanchery', 101, 1581),
(18448, 'Gummidipoondi', 101, 1581),
(18449, 'Hanumanthampatti', 101, 1581),
(18450, 'Harur', 101, 1581),
(18451, 'Harveypatti', 101, 1581),
(18452, 'Highways', 101, 1581),
(18453, 'Hosur', 101, 1581),
(18454, 'Hubbathala', 101, 1581),
(18455, 'Huligal', 101, 1581),
(18456, 'Idappadi', 101, 1581),
(18457, 'Idikarai', 101, 1581),
(18458, 'Ilampillai', 101, 1581),
(18459, 'Ilanji', 101, 1581),
(18460, 'Iluppaiyurani', 101, 1581),
(18461, 'Iluppur', 101, 1581),
(18462, 'Inam Karur', 101, 1581),
(18463, 'Injambakkam', 101, 1581),
(18464, 'Irugur', 101, 1581),
(18465, 'Jaffrabad', 101, 1581),
(18466, 'Jagathala', 101, 1581),
(18467, 'Jalakandapuram', 101, 1581),
(18468, 'Jalladiampet', 101, 1581),
(18469, 'Jambai', 101, 1581),
(18470, 'Jayankondam', 101, 1581),
(18471, 'Jolarpet', 101, 1581),
(18472, 'Kadambur', 101, 1581),
(18473, 'Kadathur', 101, 1581),
(18474, 'Kadayal', 101, 1581),
(18475, 'Kadayampatti', 101, 1581),
(18476, 'Kadayanallur', 101, 1581),
(18477, 'Kadiapatti', 101, 1581),
(18478, 'Kalakkad', 101, 1581),
(18479, 'Kalambur', 101, 1581),
(18480, 'Kalapatti', 101, 1581),
(18481, 'Kalappanaickenpatti', 101, 1581),
(18482, 'Kalavai', 101, 1581),
(18483, 'Kalinjur', 101, 1581),
(18484, 'Kaliyakkavilai', 101, 1581),
(18485, 'Kallakkurichi', 101, 1581),
(18486, 'Kallakudi', 101, 1581),
(18487, 'Kallidaikurichchi', 101, 1581),
(18488, 'Kallukuttam', 101, 1581),
(18489, 'Kallupatti', 101, 1581),
(18490, 'Kalpakkam', 101, 1581),
(18491, 'Kalugumalai', 101, 1581),
(18492, 'Kamayagoundanpatti', 101, 1581),
(18493, 'Kambainallur', 101, 1581),
(18494, 'Kambam', 101, 1581),
(18495, 'Kamuthi', 101, 1581),
(18496, 'Kanadukathan', 101, 1581),
(18497, 'Kanakkampalayam', 101, 1581),
(18498, 'Kanam', 101, 1581),
(18499, 'Kanchipuram', 101, 1581),
(18500, 'Kandanur', 101, 1581),
(18501, 'Kangayam', 101, 1581),
(18502, 'Kangayampalayam', 101, 1581),
(18503, 'Kangeyanallur', 101, 1581),
(18504, 'Kaniyur', 101, 1581),
(18505, 'Kanjikoil', 101, 1581),
(18506, 'Kannadendal', 101, 1581),
(18507, 'Kannamangalam', 101, 1581),
(18508, 'Kannampalayam', 101, 1581),
(18509, 'Kannankurichi', 101, 1581),
(18510, 'Kannapalaiyam', 101, 1581),
(18511, 'Kannivadi', 101, 1581),
(18512, 'Kanyakumari', 101, 1581),
(18513, 'Kappiyarai', 101, 1581),
(18514, 'Karaikkudi', 101, 1581),
(18515, 'Karamadai', 101, 1581),
(18516, 'Karambakkam', 101, 1581),
(18517, 'Karambakkudi', 101, 1581),
(18518, 'Kariamangalam', 101, 1581),
(18519, 'Kariapatti', 101, 1581),
(18520, 'Karugampattur', 101, 1581),
(18521, 'Karumandi Chellipalayam', 101, 1581),
(18522, 'Karumathampatti', 101, 1581),
(18523, 'Karumbakkam', 101, 1581),
(18524, 'Karungal', 101, 1581),
(18525, 'Karunguzhi', 101, 1581),
(18526, 'Karuppur', 101, 1581),
(18527, 'Karur', 101, 1581),
(18528, 'Kasipalaiyam', 101, 1581),
(18529, 'Kasipalayam G', 101, 1581),
(18530, 'Kathirvedu', 101, 1581),
(18531, 'Kathujuganapalli', 101, 1581),
(18532, 'Katpadi', 101, 1581),
(18533, 'Kattivakkam', 101, 1581),
(18534, 'Kattumannarkoil', 101, 1581),
(18535, 'Kattupakkam', 101, 1581),
(18536, 'Kattuputhur', 101, 1581),
(18537, 'Kaveripakkam', 101, 1581),
(18538, 'Kaveripattinam', 101, 1581),
(18539, 'Kavundampalaiyam', 101, 1581),
(18540, 'Kavundampalayam', 101, 1581),
(18541, 'Kayalpattinam', 101, 1581),
(18542, 'Kayattar', 101, 1581),
(18543, 'Kelamangalam', 101, 1581),
(18544, 'Kelambakkam', 101, 1581),
(18545, 'Kembainaickenpalayam', 101, 1581),
(18546, 'Kethi', 101, 1581),
(18547, 'Kilakarai', 101, 1581),
(18548, 'Kilampadi', 101, 1581),
(18549, 'Kilkulam', 101, 1581),
(18550, 'Kilkunda', 101, 1581),
(18551, 'Killiyur', 101, 1581),
(18552, 'Killlai', 101, 1581),
(18553, 'Kilpennathur', 101, 1581),
(18554, 'Kilvelur', 101, 1581),
(18555, 'Kinathukadavu', 101, 1581),
(18556, 'Kiramangalam', 101, 1581),
(18557, 'Kiranur', 101, 1581),
(18558, 'Kiripatti', 101, 1581),
(18559, 'Kizhapavur', 101, 1581),
(18560, 'Kmarasamipatti', 101, 1581),
(18561, 'Kochadai', 101, 1581),
(18562, 'Kodaikanal', 101, 1581),
(18563, 'Kodambakkam', 101, 1581),
(18564, 'Kodavasal', 101, 1581),
(18565, 'Kodumudi', 101, 1581),
(18566, 'Kolachal', 101, 1581),
(18567, 'Kolappalur', 101, 1581),
(18568, 'Kolathupalayam', 101, 1581),
(18569, 'Kolathur', 101, 1581),
(18570, 'Kollankodu', 101, 1581),
(18571, 'Kollankoil', 101, 1581),
(18572, 'Komaralingam', 101, 1581),
(18573, 'Komarapalayam', 101, 1581),
(18574, 'Kombai', 101, 1581),
(18575, 'Konakkarai', 101, 1581),
(18576, 'Konavattam', 101, 1581),
(18577, 'Kondalampatti', 101, 1581),
(18578, 'Konganapuram', 101, 1581),
(18579, 'Koradacheri', 101, 1581),
(18580, 'Korampallam', 101, 1581),
(18581, 'Kotagiri', 101, 1581),
(18582, 'Kothinallur', 101, 1581),
(18583, 'Kottaiyur', 101, 1581),
(18584, 'Kottakuppam', 101, 1581),
(18585, 'Kottaram', 101, 1581),
(18586, 'Kottivakkam', 101, 1581),
(18587, 'Kottur', 101, 1581),
(18588, 'Kovilpatti', 101, 1581),
(18589, 'Koyampattur', 101, 1581),
(18590, 'Krishnagiri', 101, 1581),
(18591, 'Krishnarayapuram', 101, 1581),
(18592, 'Krishnasamudram', 101, 1581),
(18593, 'Kuchanur', 101, 1581),
(18594, 'Kuhalur', 101, 1581),
(18595, 'Kulasekarappattinam', 101, 1581),
(18596, 'Kulasekarapuram', 101, 1581),
(18597, 'Kulithalai', 101, 1581),
(18598, 'Kumarapalaiyam', 101, 1581),
(18599, 'Kumarapalayam', 101, 1581),
(18600, 'Kumarapuram', 101, 1581),
(18601, 'Kumbakonam', 101, 1581),
(18602, 'Kundrathur', 101, 1581),
(18603, 'Kuniyamuthur', 101, 1581),
(18604, 'Kunnathur', 101, 1581),
(18605, 'Kunur', 101, 1581),
(18606, 'Kuraikundu', 101, 1581),
(18607, 'Kurichi', 101, 1581),
(18608, 'Kurinjippadi', 101, 1581),
(18609, 'Kurudampalaiyam', 101, 1581),
(18610, 'Kurumbalur', 101, 1581),
(18611, 'Kuthalam', 101, 1581),
(18612, 'Kuthappar', 101, 1581),
(18613, 'Kuttalam', 101, 1581),
(18614, 'Kuttanallur', 101, 1581),
(18615, 'Kuzhithurai', 101, 1581),
(18616, 'Labbaikudikadu', 101, 1581),
(18617, 'Lakkampatti', 101, 1581),
(18618, 'Lalgudi', 101, 1581),
(18619, 'Lalpet', 101, 1581),
(18620, 'Llayangudi', 101, 1581),
(18621, 'Madambakkam', 101, 1581),
(18622, 'Madanur', 101, 1581),
(18623, 'Madathukulam', 101, 1581),
(18624, 'Madhavaram', 101, 1581),
(18625, 'Madippakkam', 101, 1581),
(18626, 'Madukkarai', 101, 1581),
(18627, 'Madukkur', 101, 1581),
(18628, 'Madurai', 101, 1581),
(18629, 'Maduranthakam', 101, 1581),
(18630, 'Maduravoyal', 101, 1581),
(18631, 'Mahabalipuram', 101, 1581),
(18632, 'Makkinanpatti', 101, 1581),
(18633, 'Mallamuppampatti', 101, 1581),
(18634, 'Mallankinaru', 101, 1581),
(18635, 'Mallapuram', 101, 1581),
(18636, 'Mallasamudram', 101, 1581),
(18637, 'Mallur', 101, 1581),
(18638, 'Mamallapuram', 101, 1581),
(18639, 'Mamsapuram', 101, 1581),
(18640, 'Manachanallur', 101, 1581),
(18641, 'Manali', 101, 1581),
(18642, 'Manalmedu', 101, 1581),
(18643, 'Manalurpet', 101, 1581),
(18644, 'Manamadurai', 101, 1581),
(18645, 'Manapakkam', 101, 1581),
(18646, 'Manapparai', 101, 1581),
(18647, 'Manavalakurichi', 101, 1581),
(18648, 'Mandaikadu', 101, 1581),
(18649, 'Mandapam', 101, 1581),
(18650, 'Mangadu', 101, 1581),
(18651, 'Mangalam', 101, 1581),
(18652, 'Mangalampet', 101, 1581),
(18653, 'Manimutharu', 101, 1581),
(18654, 'Mannargudi', 101, 1581),
(18655, 'Mappilaiurani', 101, 1581),
(18656, 'Maraimalai Nagar', 101, 1581),
(18657, 'Marakkanam', 101, 1581),
(18658, 'Maramangalathupatti', 101, 1581),
(18659, 'Marandahalli', 101, 1581),
(18660, 'Markayankottai', 101, 1581),
(18661, 'Marudur', 101, 1581),
(18662, 'Marungur', 101, 1581),
(18663, 'Masinigudi', 101, 1581),
(18664, 'Mathigiri', 101, 1581),
(18665, 'Mattur', 101, 1581),
(18666, 'Mayiladuthurai', 101, 1581),
(18667, 'Mecheri', 101, 1581),
(18668, 'Melacheval', 101, 1581),
(18669, 'Melachokkanathapuram', 101, 1581),
(18670, 'Melagaram', 101, 1581),
(18671, 'Melamadai', 101, 1581),
(18672, 'Melamaiyur', 101, 1581),
(18673, 'Melanattam', 101, 1581),
(18674, 'Melathiruppanthuruthi', 101, 1581),
(18675, 'Melattur', 101, 1581),
(18676, 'Melmananbedu', 101, 1581),
(18677, 'Melpattampakkam', 101, 1581),
(18678, 'Melur', 101, 1581),
(18679, 'Melvisharam', 101, 1581),
(18680, 'Mettupalayam', 101, 1581),
(18681, 'Mettur', 101, 1581),
(18682, 'Meyyanur', 101, 1581),
(18683, 'Milavittan', 101, 1581),
(18684, 'Minakshipuram', 101, 1581),
(18685, 'Minambakkam', 101, 1581),
(18686, 'Minjur', 101, 1581),
(18687, 'Modakurichi', 101, 1581),
(18688, 'Mohanur', 101, 1581),
(18689, 'Mopperipalayam', 101, 1581),
(18690, 'Mudalur', 101, 1581),
(18691, 'Mudichur', 101, 1581),
(18692, 'Mudukulathur', 101, 1581),
(18693, 'Mukasipidariyur', 101, 1581),
(18694, 'Mukkudal', 101, 1581),
(18695, 'Mulagumudu', 101, 1581),
(18696, 'Mulakaraipatti', 101, 1581),
(18697, 'Mulanur', 101, 1581),
(18698, 'Mullakkadu', 101, 1581),
(18699, 'Muruganpalayam', 101, 1581),
(18700, 'Musiri', 101, 1581),
(18701, 'Muthupet', 101, 1581),
(18702, 'Muthur', 101, 1581),
(18703, 'Muttayyapuram', 101, 1581),
(18704, 'Muttupet', 101, 1581),
(18705, 'Muvarasampettai', 101, 1581),
(18706, 'Myladi', 101, 1581),
(18707, 'Mylapore', 101, 1581),
(18708, 'Nadukkuthagai', 101, 1581),
(18709, 'Naduvattam', 101, 1581),
(18710, 'Nagapattinam', 101, 1581),
(18711, 'Nagavakulam', 101, 1581),
(18712, 'Nagercoil', 101, 1581),
(18713, 'Nagojanahalli', 101, 1581),
(18714, 'Nallampatti', 101, 1581),
(18715, 'Nallur', 101, 1581),
(18716, 'Namagiripettai', 101, 1581),
(18717, 'Namakkal', 101, 1581),
(18718, 'Nambiyur', 101, 1581),
(18719, 'Nambutalai', 101, 1581),
(18720, 'Nandambakkam', 101, 1581),
(18721, 'Nandivaram', 101, 1581),
(18722, 'Nangavalli', 101, 1581),
(18723, 'Nangavaram', 101, 1581),
(18724, 'Nanguneri', 101, 1581),
(18725, 'Nanjikottai', 101, 1581),
(18726, 'Nannilam', 101, 1581),
(18727, 'Naranammalpuram', 101, 1581),
(18728, 'Naranapuram', 101, 1581),
(18729, 'Narasimhanaickenpalayam', 101, 1581),
(18730, 'Narasingapuram', 101, 1581),
(18731, 'Narasojipatti', 101, 1581),
(18732, 'Naravarikuppam', 101, 1581),
(18733, 'Nasiyanur', 101, 1581),
(18734, 'Natham', 101, 1581),
(18735, 'Nathampannai', 101, 1581),
(18736, 'Natrampalli', 101, 1581),
(18737, 'Nattam', 101, 1581),
(18738, 'Nattapettai', 101, 1581),
(18739, 'Nattarasankottai', 101, 1581),
(18740, 'Navalpattu', 101, 1581),
(18741, 'Nazarethpettai', 101, 1581),
(18742, 'Nazerath', 101, 1581),
(18743, 'Neikkarapatti', 101, 1581),
(18744, 'Neiyyur', 101, 1581),
(18745, 'Nellikkuppam', 101, 1581),
(18746, 'Nelliyalam', 101, 1581),
(18747, 'Nemili', 101, 1581),
(18748, 'Nemilicheri', 101, 1581),
(18749, 'Neripperichal', 101, 1581),
(18750, 'Nerkunram', 101, 1581),
(18751, 'Nerkuppai', 101, 1581),
(18752, 'Nerunjipettai', 101, 1581),
(18753, 'Neykkarappatti', 101, 1581),
(18754, 'Neyveli', 101, 1581),
(18755, 'Nidamangalam', 101, 1581),
(18756, 'Nilagiri', 101, 1581),
(18757, 'Nilakkottai', 101, 1581),
(18758, 'Nilankarai', 101, 1581),
(18759, 'Odaipatti', 101, 1581),
(18760, 'Odaiyakulam', 101, 1581),
(18761, 'Oddanchatram', 101, 1581),
(18762, 'Odugathur', 101, 1581),
(18763, 'Oggiyamduraipakkam', 101, 1581),
(18764, 'Olagadam', 101, 1581),
(18765, 'Omalur', 101, 1581),
(18766, 'Ooty', 101, 1581),
(18767, 'Orathanadu', 101, 1581),
(18768, 'Othakadai', 101, 1581),
(18769, 'Othakalmandapam', 101, 1581),
(18770, 'Ottapparai', 101, 1581),
(18771, 'Pacode', 101, 1581),
(18772, 'Padaividu', 101, 1581),
(18773, 'Padianallur', 101, 1581),
(18774, 'Padirikuppam', 101, 1581),
(18775, 'Padmanabhapuram', 101, 1581),
(18776, 'Padririvedu', 101, 1581),
(18777, 'Palaganangudy', 101, 1581),
(18778, 'Palaimpatti', 101, 1581),
(18779, 'Palakkodu', 101, 1581),
(18780, 'Palamedu', 101, 1581),
(18781, 'Palani', 101, 1581),
(18782, 'Palani Chettipatti', 101, 1581),
(18783, 'Palavakkam', 101, 1581),
(18784, 'Palavansathu', 101, 1581),
(18785, 'Palayakayal', 101, 1581),
(18786, 'Palayam', 101, 1581),
(18787, 'Palayamkottai', 101, 1581),
(18788, 'Palladam', 101, 1581),
(18789, 'Pallapalayam', 101, 1581),
(18790, 'Pallapatti', 101, 1581),
(18791, 'Pallattur', 101, 1581),
(18792, 'Pallavaram', 101, 1581),
(18793, 'Pallikaranai', 101, 1581),
(18794, 'Pallikonda', 101, 1581),
(18795, 'Pallipalaiyam', 101, 1581),
(18796, 'Pallipalaiyam Agraharam', 101, 1581),
(18797, 'Pallipattu', 101, 1581),
(18798, 'Pammal', 101, 1581),
(18799, 'Panagudi', 101, 1581),
(18800, 'Panaimarathupatti', 101, 1581),
(18801, 'Panapakkam', 101, 1581),
(18802, 'Panboli', 101, 1581),
(18803, 'Pandamangalam', 101, 1581),
(18804, 'Pannaikadu', 101, 1581),
(18805, 'Pannaipuram', 101, 1581),
(18806, 'Pannuratti', 101, 1581),
(18807, 'Panruti', 101, 1581),
(18808, 'Papanasam', 101, 1581),
(18809, 'Pappankurichi', 101, 1581),
(18810, 'Papparapatti', 101, 1581),
(18811, 'Pappireddipatti', 101, 1581),
(18812, 'Paramakkudi', 101, 1581),
(18813, 'Paramankurichi', 101, 1581),
(18814, 'Paramathi', 101, 1581),
(18815, 'Parangippettai', 101, 1581),
(18816, 'Paravai', 101, 1581),
(18817, 'Pasur', 101, 1581),
(18818, 'Pathamadai', 101, 1581),
(18819, 'Pattinam', 101, 1581),
(18820, 'Pattiviranpatti', 101, 1581),
(18821, 'Pattukkottai', 101, 1581),
(18822, 'Pazhugal', 101, 1581),
(18823, 'Pennadam', 101, 1581),
(18824, 'Pennagaram', 101, 1581),
(18825, 'Pennathur', 101, 1581),
(18826, 'Peraiyur', 101, 1581),
(18827, 'Peralam', 101, 1581),
(18828, 'Perambalur', 101, 1581),
(18829, 'Peranamallur', 101, 1581),
(18830, 'Peravurani', 101, 1581),
(18831, 'Periyakodiveri', 101, 1581),
(18832, 'Periyakulam', 101, 1581),
(18833, 'Periyanayakkanpalaiyam', 101, 1581),
(18834, 'Periyanegamam', 101, 1581),
(18835, 'Periyapatti', 101, 1581),
(18836, 'Periyasemur', 101, 1581),
(18837, 'Pernambut', 101, 1581),
(18838, 'Perumagalur', 101, 1581),
(18839, 'Perumandi', 101, 1581),
(18840, 'Perumuchi', 101, 1581),
(18841, 'Perundurai', 101, 1581),
(18842, 'Perungalathur', 101, 1581),
(18843, 'Perungudi', 101, 1581),
(18844, 'Perungulam', 101, 1581),
(18845, 'Perur', 101, 1581),
(18846, 'Perur Chettipalaiyam', 101, 1581),
(18847, 'Pethampalayam', 101, 1581),
(18848, 'Pethanaickenpalayam', 101, 1581),
(18849, 'Pillanallur', 101, 1581),
(18850, 'Pirkankaranai', 101, 1581),
(18851, 'Polichalur', 101, 1581),
(18852, 'Pollachi', 101, 1581),
(18853, 'Polur', 101, 1581),
(18854, 'Ponmani', 101, 1581),
(18855, 'Ponnamaravathi', 101, 1581),
(18856, 'Ponnampatti', 101, 1581),
(18857, 'Ponneri', 101, 1581),
(18858, 'Porur', 101, 1581),
(18859, 'Pothanur', 101, 1581),
(18860, 'Pothatturpettai', 101, 1581),
(18861, 'Pudukadai', 101, 1581),
(18862, 'Pudukkottai Cantonment', 101, 1581),
(18863, 'Pudukottai', 101, 1581),
(18864, 'Pudupalaiyam Aghraharam', 101, 1581),
(18865, 'Pudupalayam', 101, 1581),
(18866, 'Pudupatti', 101, 1581),
(18867, 'Pudupattinam', 101, 1581),
(18868, 'Pudur', 101, 1581),
(18869, 'Puduvayal', 101, 1581),
(18870, 'Pulambadi', 101, 1581),
(18871, 'Pulampatti', 101, 1581),
(18872, 'Puliyampatti', 101, 1581),
(18873, 'Puliyankudi', 101, 1581),
(18874, 'Puliyur', 101, 1581),
(18875, 'Pullampadi', 101, 1581),
(18876, 'Puluvapatti', 101, 1581),
(18877, 'Punamalli', 101, 1581),
(18878, 'Punjai Puliyampatti', 101, 1581),
(18879, 'Punjai Thottakurichi', 101, 1581),
(18880, 'Punjaipugalur', 101, 1581),
(18881, 'Puthalam', 101, 1581),
(18882, 'Putteri', 101, 1581),
(18883, 'Puvalur', 101, 1581),
(18884, 'Puzhal', 101, 1581),
(18885, 'Puzhithivakkam', 101, 1581),
(18886, 'Rajapalayam', 101, 1581),
(18887, 'Ramanathapuram', 101, 1581),
(18888, 'Ramapuram', 101, 1581),
(18889, 'Rameswaram', 101, 1581),
(18890, 'Ranipet', 101, 1581),
(18891, 'Rasipuram', 101, 1581),
(18892, 'Rayagiri', 101, 1581),
(18893, 'Rithapuram', 101, 1581),
(18894, 'Rosalpatti', 101, 1581),
(18895, 'Rudravathi', 101, 1581),
(18896, 'Sadayankuppam', 101, 1581),
(18897, 'Saint Thomas Mount', 101, 1581),
(18898, 'Salangapalayam', 101, 1581),
(18899, 'Salem', 101, 1581),
(18900, 'Samalapuram', 101, 1581),
(18901, 'Samathur', 101, 1581),
(18902, 'Sambavar Vadagarai', 101, 1581),
(18903, 'Sankaramanallur', 101, 1581),
(18904, 'Sankarankoil', 101, 1581),
(18905, 'Sankarapuram', 101, 1581),
(18906, 'Sankari', 101, 1581),
(18907, 'Sankarnagar', 101, 1581),
(18908, 'Saravanampatti', 101, 1581),
(18909, 'Sarcarsamakulam', 101, 1581),
(18910, 'Sathiyavijayanagaram', 101, 1581),
(18911, 'Sathuvachari', 101, 1581),
(18912, 'Sathyamangalam', 101, 1581),
(18913, 'Sattankulam', 101, 1581),
(18914, 'Sattur', 101, 1581),
(18915, 'Sayalgudi', 101, 1581),
(18916, 'Sayapuram', 101, 1581),
(18917, 'Seithur', 101, 1581),
(18918, 'Sembakkam', 101, 1581),
(18919, 'Semmipalayam', 101, 1581),
(18920, 'Sennirkuppam', 101, 1581),
(18921, 'Senthamangalam', 101, 1581),
(18922, 'Sentharapatti', 101, 1581),
(18923, 'Senur', 101, 1581),
(18924, 'Sethiathoppu', 101, 1581),
(18925, 'Sevilimedu', 101, 1581),
(18926, 'Sevugampatti', 101, 1581),
(18927, 'Shenbakkam', 101, 1581),
(18928, 'Shencottai', 101, 1581),
(18929, 'Shenkottai', 101, 1581),
(18930, 'Sholavandan', 101, 1581),
(18931, 'Sholinganallur', 101, 1581),
(18932, 'Sholingur', 101, 1581),
(18933, 'Sholur', 101, 1581),
(18934, 'Sikkarayapuram', 101, 1581),
(18935, 'Singampuneri', 101, 1581),
(18936, 'Singanallur', 101, 1581),
(18937, 'Singaperumalkoil', 101, 1581),
(18938, 'Sirapalli', 101, 1581),
(18939, 'Sirkali', 101, 1581),
(18940, 'Sirugamani', 101, 1581),
(18941, 'Sirumugai', 101, 1581),
(18942, 'Sithayankottai', 101, 1581),
(18943, 'Sithurajapuram', 101, 1581),
(18944, 'Sivaganga', 101, 1581),
(18945, 'Sivagiri', 101, 1581),
(18946, 'Sivakasi', 101, 1581),
(18947, 'Sivanthipuram', 101, 1581),
(18948, 'Sivur', 101, 1581),
(18949, 'Soranjeri', 101, 1581),
(18950, 'South Kannanur', 101, 1581),
(18951, 'South Kodikulam', 101, 1581),
(18952, 'Srimushnam', 101, 1581),
(18953, 'Sriperumpudur', 101, 1581),
(18954, 'Sriramapuram', 101, 1581),
(18955, 'Srirangam', 101, 1581),
(18956, 'Srivaikuntam', 101, 1581),
(18957, 'Srivilliputtur', 101, 1581),
(18958, 'Suchindram', 101, 1581),
(18959, 'Suliswaranpatti', 101, 1581),
(18960, 'Sulur', 101, 1581),
(18961, 'Sundarapandiam', 101, 1581),
(18962, 'Sundarapandiapuram', 101, 1581),
(18963, 'Surampatti', 101, 1581),
(18964, 'Surandai', 101, 1581),
(18965, 'Suriyampalayam', 101, 1581),
(18966, 'Swamimalai', 101, 1581),
(18967, 'TNPL Pugalur', 101, 1581),
(18968, 'Tambaram', 101, 1581),
(18969, 'Taramangalam', 101, 1581),
(18970, 'Tattayyangarpettai', 101, 1581),
(18971, 'Tayilupatti', 101, 1581),
(18972, 'Tenkasi', 101, 1581),
(18973, 'Thadikombu', 101, 1581),
(18974, 'Thakkolam', 101, 1581),
(18975, 'Thalainayar', 101, 1581),
(18976, 'Thalakudi', 101, 1581),
(18977, 'Thamaraikulam', 101, 1581),
(18978, 'Thammampatti', 101, 1581),
(18979, 'Thanjavur', 101, 1581),
(18980, 'Thanthoni', 101, 1581),
(18981, 'Tharangambadi', 101, 1581),
(18982, 'Thedavur', 101, 1581),
(18983, 'Thenambakkam', 101, 1581),
(18984, 'Thengampudur', 101, 1581),
(18985, 'Theni', 101, 1581),
(18986, 'Theni Allinagaram', 101, 1581),
(18987, 'Thenkarai', 101, 1581),
(18988, 'Thenthamaraikulam', 101, 1581),
(18989, 'Thenthiruperai', 101, 1581),
(18990, 'Thesur', 101, 1581),
(18991, 'Thevaram', 101, 1581),
(18992, 'Thevur', 101, 1581),
(18993, 'Thiagadurgam', 101, 1581),
(18994, 'Thiagarajar Colony', 101, 1581),
(18995, 'Thingalnagar', 101, 1581),
(18996, 'Thiruchirapalli', 101, 1581),
(18997, 'Thirukarungudi', 101, 1581),
(18998, 'Thirukazhukundram', 101, 1581),
(18999, 'Thirumalayampalayam', 101, 1581),
(19000, 'Thirumazhisai', 101, 1581),
(19001, 'Thirunagar', 101, 1581),
(19002, 'Thirunageswaram', 101, 1581),
(19003, 'Thirunindravur', 101, 1581),
(19004, 'Thirunirmalai', 101, 1581),
(19005, 'Thiruparankundram', 101, 1581),
(19006, 'Thiruparappu', 101, 1581),
(19007, 'Thiruporur', 101, 1581),
(19008, 'Thiruppanandal', 101, 1581),
(19009, 'Thirupuvanam', 101, 1581),
(19010, 'Thiruthangal', 101, 1581),
(19011, 'Thiruthuraipundi', 101, 1581),
(19012, 'Thiruvaivaru', 101, 1581),
(19013, 'Thiruvalam', 101, 1581),
(19014, 'Thiruvarur', 101, 1581),
(19015, 'Thiruvattaru', 101, 1581),
(19016, 'Thiruvenkatam', 101, 1581),
(19017, 'Thiruvennainallur', 101, 1581),
(19018, 'Thiruvithankodu', 101, 1581),
(19019, 'Thisayanvilai', 101, 1581),
(19020, 'Thittacheri', 101, 1581),
(19021, 'Thondamuthur', 101, 1581),
(19022, 'Thorapadi', 101, 1581),
(19023, 'Thottipalayam', 101, 1581),
(19024, 'Thottiyam', 101, 1581),
(19025, 'Thudiyalur', 101, 1581),
(19026, 'Thuthipattu', 101, 1581),
(19027, 'Thuvakudi', 101, 1581),
(19028, 'Timiri', 101, 1581),
(19029, 'Tindivanam', 101, 1581),
(19030, 'Tinnanur', 101, 1581),
(19031, 'Tiruchchendur', 101, 1581),
(19032, 'Tiruchengode', 101, 1581),
(19033, 'Tirukkalukkundram', 101, 1581),
(19034, 'Tirukkattuppalli', 101, 1581),
(19035, 'Tirukkoyilur', 101, 1581),
(19036, 'Tirumangalam', 101, 1581),
(19037, 'Tirumullaivasal', 101, 1581),
(19038, 'Tirumuruganpundi', 101, 1581),
(19039, 'Tirunageswaram', 101, 1581),
(19040, 'Tirunelveli', 101, 1581),
(19041, 'Tirupathur', 101, 1581),
(19042, 'Tirupattur', 101, 1581),
(19043, 'Tiruppuvanam', 101, 1581),
(19044, 'Tirupur', 101, 1581),
(19045, 'Tirusulam', 101, 1581),
(19046, 'Tiruttani', 101, 1581),
(19047, 'Tiruvallur', 101, 1581),
(19048, 'Tiruvannamalai', 101, 1581),
(19049, 'Tiruverambur', 101, 1581),
(19050, 'Tiruverkadu', 101, 1581),
(19051, 'Tiruvethipuram', 101, 1581),
(19052, 'Tiruvidaimarudur', 101, 1581),
(19053, 'Tiruvottiyur', 101, 1581),
(19054, 'Tittakudi', 101, 1581),
(19055, 'Tondi', 101, 1581),
(19056, 'Turaiyur', 101, 1581),
(19057, 'Tuticorin', 101, 1581),
(19058, 'Udagamandalam', 101, 1581),
(19059, 'Udagamandalam Valley', 101, 1581),
(19060, 'Udankudi', 101, 1581),
(19061, 'Udayarpalayam', 101, 1581),
(19062, 'Udumalaipettai', 101, 1581),
(19063, 'Udumalpet', 101, 1581),
(19064, 'Ullur', 101, 1581),
(19065, 'Ulundurpettai', 101, 1581),
(19066, 'Unjalaur', 101, 1581),
(19067, 'Unnamalaikadai', 101, 1581),
(19068, 'Uppidamangalam', 101, 1581),
(19069, 'Uppiliapuram', 101, 1581),
(19070, 'Urachikkottai', 101, 1581),
(19071, 'Urapakkam', 101, 1581),
(19072, 'Usilampatti', 101, 1581),
(19073, 'Uthangarai', 101, 1581),
(19074, 'Uthayendram', 101, 1581),
(19075, 'Uthiramerur', 101, 1581),
(19076, 'Uthukkottai', 101, 1581),
(19077, 'Uttamapalaiyam', 101, 1581),
(19078, 'Uttukkuli', 101, 1581),
(19079, 'Vadakarai Kizhpadugai', 101, 1581),
(19080, 'Vadakkanandal', 101, 1581),
(19081, 'Vadakku Valliyur', 101, 1581),
(19082, 'Vadalur', 101, 1581),
(19083, 'Vadamadurai', 101, 1581),
(19084, 'Vadavalli', 101, 1581),
(19085, 'Vadipatti', 101, 1581),
(19086, 'Vadugapatti', 101, 1581),
(19087, 'Vaithiswarankoil', 101, 1581),
(19088, 'Valangaiman', 101, 1581),
(19089, 'Valasaravakkam', 101, 1581),
(19090, 'Valavanur', 101, 1581),
(19091, 'Vallam', 101, 1581),
(19092, 'Valparai', 101, 1581),
(19093, 'Valvaithankoshtam', 101, 1581),
(19094, 'Vanavasi', 101, 1581),
(19095, 'Vandalur', 101, 1581),
(19096, 'Vandavasi', 101, 1581),
(19097, 'Vandiyur', 101, 1581),
(19098, 'Vaniputhur', 101, 1581),
(19099, 'Vaniyambadi', 101, 1581),
(19100, 'Varadarajanpettai', 101, 1581),
(19101, 'Varadharajapuram', 101, 1581),
(19102, 'Vasudevanallur', 101, 1581),
(19103, 'Vathirairuppu', 101, 1581),
(19104, 'Vattalkundu', 101, 1581),
(19105, 'Vazhapadi', 101, 1581),
(19106, 'Vedapatti', 101, 1581),
(19107, 'Vedaranniyam', 101, 1581),
(19108, 'Vedasandur', 101, 1581),
(19109, 'Velampalaiyam', 101, 1581),
(19110, 'Velankanni', 101, 1581),
(19111, 'Vellakinar', 101, 1581),
(19112, 'Vellakoil', 101, 1581),
(19113, 'Vellalapatti', 101, 1581),
(19114, 'Vellalur', 101, 1581),
(19115, 'Vellanur', 101, 1581),
(19116, 'Vellimalai', 101, 1581),
(19117, 'Vellore', 101, 1581),
(19118, 'Vellottamparappu', 101, 1581),
(19119, 'Velluru', 101, 1581),
(19120, 'Vengampudur', 101, 1581),
(19121, 'Vengathur', 101, 1581),
(19122, 'Vengavasal', 101, 1581),
(19123, 'Venghatur', 101, 1581),
(19124, 'Venkarai', 101, 1581),
(19125, 'Vennanthur', 101, 1581),
(19126, 'Veppathur', 101, 1581),
(19127, 'Verkilambi', 101, 1581),
(19128, 'Vettaikaranpudur', 101, 1581),
(19129, 'Vettavalam', 101, 1581),
(19130, 'Vijayapuri', 101, 1581),
(19131, 'Vikramasingapuram', 101, 1581),
(19132, 'Vikravandi', 101, 1581),
(19133, 'Vilangudi', 101, 1581),
(19134, 'Vilankurichi', 101, 1581),
(19135, 'Vilapakkam', 101, 1581),
(19136, 'Vilathikulam', 101, 1581),
(19137, 'Vilavur', 101, 1581),
(19138, 'Villukuri', 101, 1581),
(19139, 'Villupuram', 101, 1581),
(19140, 'Viraganur', 101, 1581),
(19141, 'Virakeralam', 101, 1581),
(19142, 'Virakkalpudur', 101, 1581),
(19143, 'Virapandi', 101, 1581),
(19144, 'Virapandi Cantonment', 101, 1581),
(19145, 'Virappanchatram', 101, 1581),
(19146, 'Viravanallur', 101, 1581),
(19147, 'Virudambattu', 101, 1581),
(19148, 'Virudhachalam', 101, 1581),
(19149, 'Virudhunagar', 101, 1581),
(19150, 'Virupakshipuram', 101, 1581),
(19151, 'Viswanatham', 101, 1581),
(19152, 'Vriddhachalam', 101, 1581),
(19153, 'Walajabad', 101, 1581),
(19154, 'Walajapet', 101, 1581),
(19155, 'Wellington', 101, 1581),
(19156, 'Yercaud', 101, 1581),
(19157, 'Zamin Uthukuli', 101, 1581),
(19158, 'Achampet', 101, 1582),
(19159, 'Adilabad', 101, 1582),
(19160, 'Armoor', 101, 1582),
(19161, 'Asifabad', 101, 1582),
(19162, 'Badepally', 101, 1582),
(19163, 'Banswada', 101, 1582),
(19164, 'Bellampalli', 101, 1582),
(19165, 'Bhadrachalam', 101, 1582),
(19166, 'Bhainsa', 101, 1582),
(19167, 'Bhongir', 101, 1582),
(19168, 'Bhupalpally', 101, 1582),
(19169, 'Bodhan', 101, 1582),
(19170, 'Bollaram', 101, 1582),
(19171, 'Devarkonda', 101, 1582),
(19172, 'Farooqnagar', 101, 1582),
(19173, 'Gadwal', 101, 1582),
(19174, 'Gajwel', 101, 1582),
(19175, 'Ghatkesar', 101, 1582),
(19176, 'Hyderabad', 101, 1582),
(19177, 'Jagtial', 101, 1582),
(19178, 'Jangaon', 101, 1582),
(19179, 'Kagaznagar', 101, 1582),
(19180, 'Kalwakurthy', 101, 1582),
(19181, 'Kamareddy', 101, 1582),
(19182, 'Karimnagar', 101, 1582),
(19183, 'Khammam', 101, 1582),
(19184, 'Kodada', 101, 1582),
(19185, 'Koratla', 101, 1582),
(19186, 'Kottagudem', 101, 1582),
(19187, 'Kyathampalle', 101, 1582),
(19188, 'Madhira', 101, 1582),
(19189, 'Mahabubabad', 101, 1582),
(19190, 'Mahbubnagar', 101, 1582),
(19191, 'Mancherial', 101, 1582),
(19192, 'Mandamarri', 101, 1582),
(19193, 'Manuguru', 101, 1582),
(19194, 'Medak', 101, 1582),
(19195, 'Medchal', 101, 1582),
(19196, 'Miryalaguda', 101, 1582),
(19197, 'Nagar Karnul', 101, 1582),
(19198, 'Nakrekal', 101, 1582),
(19199, 'Nalgonda', 101, 1582),
(19200, 'Narayanpet', 101, 1582),
(19201, 'Narsampet', 101, 1582),
(19202, 'Nirmal', 101, 1582),
(19203, 'Nizamabad', 101, 1582),
(19204, 'Palwancha', 101, 1582),
(19205, 'Peddapalli', 101, 1582),
(19206, 'Ramagundam', 101, 1582),
(19207, 'Ranga Reddy district', 101, 1582),
(19208, 'Sadasivpet', 101, 1582),
(19209, 'Sangareddy', 101, 1582),
(19210, 'Sarapaka', 101, 1582),
(19211, 'Sathupalle', 101, 1582),
(19212, 'Secunderabad', 101, 1582),
(19213, 'Siddipet', 101, 1582),
(19214, 'Singapur', 101, 1582),
(19215, 'Sircilla', 101, 1582),
(19216, 'Suryapet', 101, 1582),
(19217, 'Tandur', 101, 1582),
(19218, 'Vemulawada', 101, 1582),
(19219, 'Vikarabad', 101, 1582),
(19220, 'Wanaparthy', 101, 1582),
(19221, 'Warangal', 101, 1582),
(19222, 'Yellandu', 101, 1582),
(19223, 'Zahirabad', 101, 1582),
(19224, 'Agartala', 101, 1583),
(19225, 'Amarpur', 101, 1583),
(19226, 'Ambassa', 101, 1583),
(19227, 'Badharghat', 101, 1583),
(19228, 'Belonia', 101, 1583),
(19229, 'Dharmanagar', 101, 1583),
(19230, 'Gakulnagar', 101, 1583),
(19231, 'Gandhigram', 101, 1583),
(19232, 'Indranagar', 101, 1583),
(19233, 'Jogendranagar', 101, 1583),
(19234, 'Kailasahar', 101, 1583),
(19235, 'Kamalpur', 101, 1583),
(19236, 'Kanchanpur', 101, 1583),
(19237, 'Khowai', 101, 1583),
(19238, 'Kumarghat', 101, 1583),
(19239, 'Kunjaban', 101, 1583),
(19240, 'Narsingarh', 101, 1583),
(19241, 'Pratapgarh', 101, 1583),
(19242, 'Ranir Bazar', 101, 1583),
(19243, 'Sabrum', 101, 1583),
(19244, 'Sonamura', 101, 1583),
(19245, 'Teliamura', 101, 1583),
(19246, 'Udaipur', 101, 1583),
(19247, 'Achhalda', 101, 1584),
(19248, 'Achhnera', 101, 1584),
(19249, 'Adari', 101, 1584),
(19250, 'Afzalgarh', 101, 1584),
(19251, 'Agarwal Mandi', 101, 1584),
(19252, 'Agra', 101, 1584),
(19253, 'Agra Cantonment', 101, 1584),
(19254, 'Ahraura', 101, 1584),
(19255, 'Ailum', 101, 1584),
(19256, 'Air Force Area', 101, 1584),
(19257, 'Ajhuwa', 101, 1584),
(19258, 'Akbarpur', 101, 1584),
(19259, 'Alapur', 101, 1584),
(19260, 'Aliganj', 101, 1584),
(19261, 'Aligarh', 101, 1584),
(19262, 'Allahabad', 101, 1584),
(19263, 'Allahabad Cantonment', 101, 1584),
(19264, 'Allahganj', 101, 1584),
(19265, 'Amanpur', 101, 1584),
(19266, 'Ambahta', 101, 1584),
(19267, 'Amethi', 101, 1584),
(19268, 'Amila', 101, 1584),
(19269, 'Amilo', 101, 1584),
(19270, 'Aminagar Sarai', 101, 1584),
(19271, 'Aminagar Urf Bhurbaral', 101, 1584),
(19272, 'Amraudha', 101, 1584),
(19273, 'Amroha', 101, 1584),
(19274, 'Anandnagar', 101, 1584),
(19275, 'Anpara', 101, 1584),
(19276, 'Antu', 101, 1584),
(19277, 'Anupshahr', 101, 1584),
(19278, 'Aonla', 101, 1584),
(19279, 'Armapur Estate', 101, 1584),
(19280, 'Ashokpuram', 101, 1584),
(19281, 'Ashrafpur Kichhauchha', 101, 1584),
(19282, 'Atarra', 101, 1584),
(19283, 'Atasu', 101, 1584),
(19284, 'Atrauli', 101, 1584),
(19285, 'Atraulia', 101, 1584),
(19286, 'Auraiya', 101, 1584),
(19287, 'Aurangabad', 101, 1584),
(19288, 'Aurangabad Bangar', 101, 1584),
(19289, 'Auras', 101, 1584),
(19290, 'Awagarh', 101, 1584),
(19291, 'Ayodhya', 101, 1584),
(19292, 'Azamgarh', 101, 1584),
(19293, 'Azizpur', 101, 1584),
(19294, 'Azmatgarh', 101, 1584),
(19295, 'Babarpur Ajitmal', 101, 1584),
(19296, 'Baberu', 101, 1584),
(19297, 'Babina', 101, 1584),
(19298, 'Babrala', 101, 1584),
(19299, 'Babugarh', 101, 1584),
(19300, 'Bachhiowan', 101, 1584),
(19301, 'Bachhraon', 101, 1584),
(19302, 'Bad', 101, 1584),
(19303, 'Badaun', 101, 1584),
(19304, 'Baghpat', 101, 1584),
(19305, 'Bah', 101, 1584),
(19306, 'Bahadurganj', 101, 1584),
(19307, 'Baheri', 101, 1584),
(19308, 'Bahjoi', 101, 1584),
(19309, 'Bahraich', 101, 1584),
(19310, 'Bahsuma', 101, 1584),
(19311, 'Bahua', 101, 1584),
(19312, 'Bajna', 101, 1584),
(19313, 'Bakewar', 101, 1584),
(19314, 'Bakiabad', 101, 1584),
(19315, 'Baldeo', 101, 1584),
(19316, 'Ballia', 101, 1584),
(19317, 'Balrampur', 101, 1584),
(19318, 'Banat', 101, 1584),
(19319, 'Banda', 101, 1584),
(19320, 'Bangarmau', 101, 1584),
(19321, 'Banki', 101, 1584),
(19322, 'Bansdih', 101, 1584),
(19323, 'Bansgaon', 101, 1584),
(19324, 'Bansi', 101, 1584),
(19325, 'Barabanki', 101, 1584),
(19326, 'Baragaon', 101, 1584),
(19327, 'Baraut', 101, 1584),
(19328, 'Bareilly', 101, 1584),
(19329, 'Bareilly Cantonment', 101, 1584),
(19330, 'Barhalganj', 101, 1584),
(19331, 'Barhani', 101, 1584),
(19332, 'Barhapur', 101, 1584),
(19333, 'Barkhera', 101, 1584),
(19334, 'Barsana', 101, 1584),
(19335, 'Barva Sagar', 101, 1584),
(19336, 'Barwar', 101, 1584),
(19337, 'Basti', 101, 1584),
(19338, 'Begumabad Budhana', 101, 1584),
(19339, 'Behat', 101, 1584),
(19340, 'Behta Hajipur', 101, 1584),
(19341, 'Bela', 101, 1584),
(19342, 'Belthara', 101, 1584),
(19343, 'Beniganj', 101, 1584),
(19344, 'Beswan', 101, 1584),
(19345, 'Bewar', 101, 1584),
(19346, 'Bhadarsa', 101, 1584),
(19347, 'Bhadohi', 101, 1584),
(19348, 'Bhagwantnagar', 101, 1584),
(19349, 'Bharatganj', 101, 1584),
(19350, 'Bhargain', 101, 1584),
(19351, 'Bharthana', 101, 1584),
(19352, 'Bharuhana', 101, 1584),
(19353, 'Bharwari', 101, 1584),
(19354, 'Bhatni Bazar', 101, 1584),
(19355, 'Bhatpar Rani', 101, 1584),
(19356, 'Bhawan Bahadurnagar', 101, 1584),
(19357, 'Bhinga', 101, 1584),
(19358, 'Bhojpur Dharampur', 101, 1584),
(19359, 'Bhokarhedi', 101, 1584),
(19360, 'Bhongaon', 101, 1584),
(19361, 'Bhulepur', 101, 1584),
(19362, 'Bidhuna', 101, 1584),
(19363, 'Bighapur', 101, 1584),
(19364, 'Bijnor', 101, 1584),
(19365, 'Bijpur', 101, 1584),
(19366, 'Bikapur', 101, 1584),
(19367, 'Bilari', 101, 1584),
(19368, 'Bilaspur', 101, 1584),
(19369, 'Bilgram', 101, 1584),
(19370, 'Bilhaur', 101, 1584),
(19371, 'Bilram', 101, 1584),
(19372, 'Bilrayaganj', 101, 1584),
(19373, 'Bilsanda', 101, 1584),
(19374, 'Bilsi', 101, 1584),
(19375, 'Bindki', 101, 1584),
(19376, 'Bisalpur', 101, 1584),
(19377, 'Bisanda Buzurg', 101, 1584),
(19378, 'Bisauli', 101, 1584),
(19379, 'Bisharatganj', 101, 1584),
(19380, 'Bisokhar', 101, 1584),
(19381, 'Biswan', 101, 1584),
(19382, 'Bithur', 101, 1584),
(19383, 'Budaun', 101, 1584),
(19384, 'Bugrasi', 101, 1584),
(19385, 'Bulandshahar', 101, 1584),
(19386, 'Burhana', 101, 1584),
(19387, 'Chail', 101, 1584),
(19388, 'Chak Imam Ali', 101, 1584),
(19389, 'Chakeri', 101, 1584),
(19390, 'Chakia', 101, 1584),
(19391, 'Chandauli', 101, 1584),
(19392, 'Chandausi', 101, 1584),
(19393, 'Chandpur', 101, 1584),
(19394, 'Charkhari', 101, 1584),
(19395, 'Charthawal', 101, 1584),
(19396, 'Chaumuhan', 101, 1584),
(19397, 'Chhaprauli', 101, 1584),
(19398, 'Chhara Rafatpur', 101, 1584),
(19399, 'Chharprauli', 101, 1584),
(19400, 'Chhata', 101, 1584),
(19401, 'Chhatari', 101, 1584),
(19402, 'Chhibramau', 101, 1584),
(19403, 'Chhutmalpur', 101, 1584),
(19404, 'Chilkana Sultanpur', 101, 1584),
(19405, 'Chirgaon', 101, 1584),
(19406, 'Chit Baragaon', 101, 1584),
(19407, 'Chitrakut Dham', 101, 1584),
(19408, 'Chopan', 101, 1584),
(19409, 'Choubepur Kalan', 101, 1584),
(19410, 'Chunar', 101, 1584),
(19411, 'Churk Ghurma', 101, 1584),
(19412, 'Colonelganj', 101, 1584),
(19413, 'Dadri', 101, 1584),
(19414, 'Dalmau', 101, 1584),
(19415, 'Dankaur', 101, 1584),
(19416, 'Dariyabad', 101, 1584),
(19417, 'Dasna', 101, 1584),
(19418, 'Dataganj', 101, 1584),
(19419, 'Daurala', 101, 1584),
(19420, 'Dayal Bagh', 101, 1584),
(19421, 'Deoband', 101, 1584),
(19422, 'Deoranian', 101, 1584),
(19423, 'Deoria', 101, 1584),
(19424, 'Dewa', 101, 1584),
(19425, 'Dhampur', 101, 1584),
(19426, 'Dhanauha', 101, 1584),
(19427, 'Dhanauli', 101, 1584),
(19428, 'Dhanaura', 101, 1584),
(19429, 'Dharoti Khurd', 101, 1584),
(19430, 'Dhauratanda', 101, 1584),
(19431, 'Dhaurhra', 101, 1584),
(19432, 'Dibai', 101, 1584),
(19433, 'Dibiyapur', 101, 1584),
(19434, 'Dildarnagar Fatehpur', 101, 1584),
(19435, 'Do Ghat', 101, 1584),
(19436, 'Dohrighat', 101, 1584),
(19437, 'Dostpur', 101, 1584),
(19438, 'Dudhinagar', 101, 1584),
(19439, 'Dulhipur', 101, 1584),
(19440, 'Dundwaraganj', 101, 1584),
(19441, 'Ekdil', 101, 1584),
(19442, 'Erich', 101, 1584),
(19443, 'Etah', 101, 1584),
(19444, 'Etawah', 101, 1584),
(19445, 'Faizabad', 101, 1584),
(19446, 'Faizabad Cantonment', 101, 1584),
(19447, 'Faizganj', 101, 1584),
(19448, 'Farah', 101, 1584),
(19449, 'Faridnagar', 101, 1584),
(19450, 'Faridpur', 101, 1584),
(19451, 'Faridpur Cantonment', 101, 1584),
(19452, 'Fariha', 101, 1584),
(19453, 'Farrukhabad', 101, 1584),
(19454, 'Fatehabad', 101, 1584),
(19455, 'Fatehganj Pashchimi', 101, 1584),
(19456, 'Fatehganj Purvi', 101, 1584),
(19457, 'Fatehgarh', 101, 1584),
(19458, 'Fatehpur', 101, 1584),
(19459, 'Fatehpur Chaurasi', 101, 1584),
(19460, 'Fatehpur Sikri', 101, 1584),
(19461, 'Firozabad', 101, 1584),
(19462, 'Gajraula', 101, 1584),
(19463, 'Ganga Ghat', 101, 1584),
(19464, 'Gangapur', 101, 1584),
(19465, 'Gangoh', 101, 1584),
(19466, 'Ganj Muradabad', 101, 1584),
(19467, 'Garautha', 101, 1584),
(19468, 'Garhi Pukhta', 101, 1584),
(19469, 'Garhmukteshwar', 101, 1584),
(19470, 'Gaura Barahaj', 101, 1584),
(19471, 'Gauri Bazar', 101, 1584),
(19472, 'Gausganj', 101, 1584),
(19473, 'Gawan', 101, 1584),
(19474, 'Ghatampur', 101, 1584),
(19475, 'Ghaziabad', 101, 1584),
(19476, 'Ghazipur', 101, 1584),
(19477, 'Ghiror', 101, 1584),
(19478, 'Ghorawal', 101, 1584),
(19479, 'Ghosi', 101, 1584),
(19480, 'Ghosia Bazar', 101, 1584),
(19481, 'Ghughuli', 101, 1584),
(19482, 'Gohand', 101, 1584),
(19483, 'Gokul', 101, 1584),
(19484, 'Gola Bazar', 101, 1584),
(19485, 'Gola Gokarannath', 101, 1584),
(19486, 'Gonda', 101, 1584),
(19487, 'Gopamau', 101, 1584),
(19488, 'Gopiganj', 101, 1584),
(19489, 'Gorakhpur', 101, 1584),
(19490, 'Gosainganj', 101, 1584),
(19491, 'Govardhan', 101, 1584),
(19492, 'Greater Noida', 101, 1584),
(19493, 'Gulaothi', 101, 1584),
(19494, 'Gulariya', 101, 1584),
(19495, 'Gulariya Bhindara', 101, 1584),
(19496, 'Gunnaur', 101, 1584),
(19497, 'Gursahaiganj', 101, 1584),
(19498, 'Gursarai', 101, 1584),
(19499, 'Gyanpur', 101, 1584),
(19500, 'Hafizpur', 101, 1584),
(19501, 'Haidergarh', 101, 1584),
(19502, 'Haldaur', 101, 1584),
(19503, 'Hamirpur', 101, 1584),
(19504, 'Handia', 101, 1584),
(19505, 'Hapur', 101, 1584),
(19506, 'Hardoi', 101, 1584),
(19507, 'Harduaganj', 101, 1584),
(19508, 'Hargaon', 101, 1584),
(19509, 'Hariharpur', 101, 1584),
(19510, 'Harraiya', 101, 1584),
(19511, 'Hasanpur', 101, 1584),
(19512, 'Hasayan', 101, 1584),
(19513, 'Hastinapur', 101, 1584),
(19514, 'Hata', 101, 1584),
(19515, 'Hathras', 101, 1584),
(19517, 'Ibrahimpur', 101, 1584),
(19518, 'Iglas', 101, 1584),
(19519, 'Ikauna', 101, 1584),
(19520, 'Iltifatganj Bazar', 101, 1584),
(19521, 'Indian Telephone Industry Mank', 101, 1584),
(19522, 'Islamnagar', 101, 1584),
(19523, 'Itaunja', 101, 1584),
(19524, 'Itimadpur', 101, 1584),
(19525, 'Jagner', 101, 1584),
(19526, 'Jahanabad', 101, 1584),
(19527, 'Jahangirabad', 101, 1584),
(19528, 'Jahangirpur', 101, 1584),
(19529, 'Jais', 101, 1584),
(19530, 'Jaithara', 101, 1584),
(19531, 'Jalalabad', 101, 1584),
(19532, 'Jalali', 101, 1584),
(19533, 'Jalalpur', 101, 1584),
(19534, 'Jalaun', 101, 1584),
(19535, 'Jalesar', 101, 1584),
(19536, 'Jamshila', 101, 1584),
(19537, 'Jangipur', 101, 1584),
(19538, 'Jansath', 101, 1584),
(19539, 'Jarwal', 101, 1584),
(19540, 'Jasrana', 101, 1584),
(19541, 'Jaswantnagar', 101, 1584),
(19542, 'Jatari', 101, 1584),
(19543, 'Jaunpur', 101, 1584),
(19544, 'Jewar', 101, 1584),
(19545, 'Jhalu', 101, 1584),
(19546, 'Jhansi', 101, 1584),
(19547, 'Jhansi Cantonment', 101, 1584),
(19548, 'Jhansi Railway Settlement', 101, 1584),
(19549, 'Jhinjhak', 101, 1584),
(19550, 'Jhinjhana', 101, 1584),
(19551, 'Jhusi', 101, 1584),
(19552, 'Jhusi Kohna', 101, 1584),
(19553, 'Jiyanpur', 101, 1584),
(19554, 'Joya', 101, 1584),
(19555, 'Jyoti Khuria', 101, 1584),
(19556, 'Jyotiba Phule Nagar', 101, 1584),
(19557, 'Kabrai', 101, 1584),
(19558, 'Kachhauna Patseni', 101, 1584),
(19559, 'Kachhla', 101, 1584),
(19560, 'Kachhwa', 101, 1584),
(19561, 'Kadaura', 101, 1584),
(19562, 'Kadipur', 101, 1584),
(19563, 'Kailashpur', 101, 1584),
(19564, 'Kaimganj', 101, 1584),
(19565, 'Kairana', 101, 1584),
(19566, 'Kakgaina', 101, 1584),
(19567, 'Kakod', 101, 1584),
(19568, 'Kakori', 101, 1584),
(19569, 'Kakrala', 101, 1584),
(19570, 'Kalinagar', 101, 1584),
(19571, 'Kalpi', 101, 1584),
(19572, 'Kamalganj', 101, 1584),
(19573, 'Kampil', 101, 1584),
(19574, 'Kandhla', 101, 1584),
(19575, 'Kandwa', 101, 1584),
(19576, 'Kannauj', 101, 1584),
(19577, 'Kanpur', 101, 1584),
(19578, 'Kant', 101, 1584),
(19579, 'Kanth', 101, 1584),
(19580, 'Kaptanganj', 101, 1584),
(19581, 'Karaon', 101, 1584),
(19582, 'Karari', 101, 1584),
(19583, 'Karhal', 101, 1584),
(19584, 'Karnawal', 101, 1584),
(19585, 'Kasganj', 101, 1584),
(19586, 'Katariya', 101, 1584),
(19587, 'Katghar Lalganj', 101, 1584),
(19588, 'Kathera', 101, 1584),
(19589, 'Katra', 101, 1584),
(19590, 'Katra Medniganj', 101, 1584),
(19591, 'Kauriaganj', 101, 1584),
(19592, 'Kemri', 101, 1584),
(19593, 'Kerakat', 101, 1584),
(19594, 'Khadda', 101, 1584);
INSERT INTO `cities` (`id`, `name`, `country_id`, `state_id`) VALUES
(19595, 'Khaga', 101, 1584),
(19596, 'Khailar', 101, 1584),
(19597, 'Khair', 101, 1584),
(19598, 'Khairabad', 101, 1584),
(19599, 'Khairagarh', 101, 1584),
(19600, 'Khalilabad', 101, 1584),
(19601, 'Khamaria', 101, 1584),
(19602, 'Khanpur', 101, 1584),
(19603, 'Kharela', 101, 1584),
(19604, 'Khargupur', 101, 1584),
(19605, 'Khariya', 101, 1584),
(19606, 'Kharkhoda', 101, 1584),
(19607, 'Khatauli', 101, 1584),
(19608, 'Khatauli Rural', 101, 1584),
(19609, 'Khekra', 101, 1584),
(19610, 'Kheri', 101, 1584),
(19611, 'Kheta Sarai', 101, 1584),
(19612, 'Khudaganj', 101, 1584),
(19613, 'Khurja', 101, 1584),
(19614, 'Khutar', 101, 1584),
(19615, 'Kiraoli', 101, 1584),
(19616, 'Kiratpur', 101, 1584),
(19617, 'Kishanpur', 101, 1584),
(19618, 'Kishni', 101, 1584),
(19619, 'Kithaur', 101, 1584),
(19620, 'Koiripur', 101, 1584),
(19621, 'Konch', 101, 1584),
(19622, 'Kopaganj', 101, 1584),
(19623, 'Kora Jahanabad', 101, 1584),
(19624, 'Korwa', 101, 1584),
(19625, 'Kosi Kalan', 101, 1584),
(19626, 'Kota', 101, 1584),
(19627, 'Kotra', 101, 1584),
(19628, 'Kotwa', 101, 1584),
(19629, 'Kulpahar', 101, 1584),
(19630, 'Kunda', 101, 1584),
(19631, 'Kundarki', 101, 1584),
(19632, 'Kunwargaon', 101, 1584),
(19633, 'Kurara', 101, 1584),
(19634, 'Kurawali', 101, 1584),
(19635, 'Kursath', 101, 1584),
(19636, 'Kurthi Jafarpur', 101, 1584),
(19637, 'Kushinagar', 101, 1584),
(19638, 'Kusmara', 101, 1584),
(19639, 'Laharpur', 101, 1584),
(19640, 'Lakhimpur', 101, 1584),
(19641, 'Lakhna', 101, 1584),
(19642, 'Lalganj', 101, 1584),
(19643, 'Lalitpur', 101, 1584),
(19644, 'Lar', 101, 1584),
(19645, 'Lawar', 101, 1584),
(19646, 'Ledwa Mahuwa', 101, 1584),
(19647, 'Lohta', 101, 1584),
(19648, 'Loni', 101, 1584),
(19649, 'Lucknow', 101, 1584),
(19650, 'Machhlishahr', 101, 1584),
(19651, 'Madhoganj', 101, 1584),
(19652, 'Madhogarh', 101, 1584),
(19653, 'Maghar', 101, 1584),
(19654, 'Mahaban', 101, 1584),
(19655, 'Maharajganj', 101, 1584),
(19656, 'Mahmudabad', 101, 1584),
(19657, 'Mahoba', 101, 1584),
(19658, 'Maholi', 101, 1584),
(19659, 'Mahona', 101, 1584),
(19660, 'Mahroni', 101, 1584),
(19661, 'Mailani', 101, 1584),
(19662, 'Mainpuri', 101, 1584),
(19663, 'Majhara Pipar Ehatmali', 101, 1584),
(19664, 'Majhauli Raj', 101, 1584),
(19665, 'Malihabad', 101, 1584),
(19666, 'Mallanwam', 101, 1584),
(19667, 'Mandawar', 101, 1584),
(19668, 'Manikpur', 101, 1584),
(19669, 'Maniyar', 101, 1584),
(19670, 'Manjhanpur', 101, 1584),
(19671, 'Mankapur', 101, 1584),
(19672, 'Marehra', 101, 1584),
(19673, 'Mariahu', 101, 1584),
(19674, 'Maruadih', 101, 1584),
(19675, 'Maswasi', 101, 1584),
(19676, 'Mataundh', 101, 1584),
(19677, 'Mathu', 101, 1584),
(19678, 'Mathura', 101, 1584),
(19679, 'Mathura Cantonment', 101, 1584),
(19680, 'Mau', 101, 1584),
(19681, 'Mau Aima', 101, 1584),
(19682, 'Maudaha', 101, 1584),
(19683, 'Mauranipur', 101, 1584),
(19684, 'Maurawan', 101, 1584),
(19685, 'Mawana', 101, 1584),
(19686, 'Meerut', 101, 1584),
(19687, 'Mehnagar', 101, 1584),
(19688, 'Mehndawal', 101, 1584),
(19689, 'Mendu', 101, 1584),
(19690, 'Milak', 101, 1584),
(19691, 'Miranpur', 101, 1584),
(19692, 'Mirat', 101, 1584),
(19693, 'Mirat Cantonment', 101, 1584),
(19694, 'Mirganj', 101, 1584),
(19695, 'Mirzapur', 101, 1584),
(19696, 'Misrikh', 101, 1584),
(19697, 'Modinagar', 101, 1584),
(19698, 'Mogra Badshahpur', 101, 1584),
(19699, 'Mohan', 101, 1584),
(19700, 'Mohanpur', 101, 1584),
(19701, 'Mohiuddinpur', 101, 1584),
(19702, 'Moradabad', 101, 1584),
(19703, 'Moth', 101, 1584),
(19704, 'Mubarakpur', 101, 1584),
(19705, 'Mughal Sarai', 101, 1584),
(19706, 'Mughal Sarai Railway Settlemen', 101, 1584),
(19707, 'Muhammadabad', 101, 1584),
(19708, 'Muhammadi', 101, 1584),
(19709, 'Mukrampur Khema', 101, 1584),
(19710, 'Mundia', 101, 1584),
(19711, 'Mundora', 101, 1584),
(19712, 'Muradnagar', 101, 1584),
(19713, 'Mursan', 101, 1584),
(19714, 'Musafirkhana', 101, 1584),
(19715, 'Muzaffarnagar', 101, 1584),
(19716, 'Nadigaon', 101, 1584),
(19717, 'Nagina', 101, 1584),
(19718, 'Nagram', 101, 1584),
(19719, 'Nai Bazar', 101, 1584),
(19720, 'Nainana Jat', 101, 1584),
(19721, 'Najibabad', 101, 1584),
(19722, 'Nakur', 101, 1584),
(19723, 'Nanaunta', 101, 1584),
(19724, 'Nandgaon', 101, 1584),
(19725, 'Nanpara', 101, 1584),
(19726, 'Naraini', 101, 1584),
(19727, 'Narauli', 101, 1584),
(19728, 'Naraura', 101, 1584),
(19729, 'Naugawan Sadat', 101, 1584),
(19730, 'Nautanwa', 101, 1584),
(19731, 'Nawabganj', 101, 1584),
(19732, 'Nichlaul', 101, 1584),
(19733, 'Nidhauli Kalan', 101, 1584),
(19734, 'Nihtaur', 101, 1584),
(19735, 'Nindaura', 101, 1584),
(19736, 'Niwari', 101, 1584),
(19737, 'Nizamabad', 101, 1584),
(19738, 'Noida', 101, 1584),
(19739, 'Northern Railway Colony', 101, 1584),
(19740, 'Nurpur', 101, 1584),
(19741, 'Nyoria Husenpur', 101, 1584),
(19742, 'Nyotini', 101, 1584),
(19743, 'Obra', 101, 1584),
(19744, 'Oel Dhakwa', 101, 1584),
(19745, 'Orai', 101, 1584),
(19746, 'Oran', 101, 1584),
(19747, 'Ordinance Factory Muradnagar', 101, 1584),
(19748, 'Pachperwa', 101, 1584),
(19749, 'Padrauna', 101, 1584),
(19750, 'Pahasu', 101, 1584),
(19751, 'Paintepur', 101, 1584),
(19752, 'Pali', 101, 1584),
(19753, 'Palia Kalan', 101, 1584),
(19754, 'Parasi', 101, 1584),
(19755, 'Parichha', 101, 1584),
(19756, 'Parichhatgarh', 101, 1584),
(19757, 'Parsadepur', 101, 1584),
(19758, 'Patala', 101, 1584),
(19759, 'Patiyali', 101, 1584),
(19760, 'Patti', 101, 1584),
(19761, 'Pawayan', 101, 1584),
(19762, 'Phalauda', 101, 1584),
(19763, 'Phaphund', 101, 1584),
(19764, 'Phulpur', 101, 1584),
(19765, 'Phulwaria', 101, 1584),
(19766, 'Pihani', 101, 1584),
(19767, 'Pilibhit', 101, 1584),
(19768, 'Pilkana', 101, 1584),
(19769, 'Pilkhuwa', 101, 1584),
(19770, 'Pinahat', 101, 1584),
(19771, 'Pipalsana Chaudhari', 101, 1584),
(19772, 'Pipiganj', 101, 1584),
(19773, 'Pipraich', 101, 1584),
(19774, 'Pipri', 101, 1584),
(19775, 'Pratapgarh', 101, 1584),
(19776, 'Pukhrayan', 101, 1584),
(19777, 'Puranpur', 101, 1584),
(19778, 'Purdil Nagar', 101, 1584),
(19779, 'Purqazi', 101, 1584),
(19780, 'Purwa', 101, 1584),
(19781, 'Qasimpur', 101, 1584),
(19782, 'Rabupura', 101, 1584),
(19783, 'Radha Kund', 101, 1584),
(19784, 'Rae Bareilly', 101, 1584),
(19785, 'Raja Ka Rampur', 101, 1584),
(19786, 'Rajapur', 101, 1584),
(19787, 'Ramkola', 101, 1584),
(19788, 'Ramnagar', 101, 1584),
(19789, 'Rampur', 101, 1584),
(19790, 'Rampur Bhawanipur', 101, 1584),
(19791, 'Rampur Karkhana', 101, 1584),
(19792, 'Rampur Maniharan', 101, 1584),
(19793, 'Rampura', 101, 1584),
(19794, 'Ranipur', 101, 1584),
(19795, 'Rashidpur Garhi', 101, 1584),
(19796, 'Rasra', 101, 1584),
(19797, 'Rasulabad', 101, 1584),
(19798, 'Rath', 101, 1584),
(19799, 'Raya', 101, 1584),
(19800, 'Renukut', 101, 1584),
(19801, 'Reoti', 101, 1584),
(19802, 'Richha', 101, 1584),
(19803, 'Risia Bazar', 101, 1584),
(19804, 'Rithora', 101, 1584),
(19805, 'Robertsganj', 101, 1584),
(19806, 'Roza', 101, 1584),
(19807, 'Rudarpur', 101, 1584),
(19808, 'Rudauli', 101, 1584),
(19809, 'Rudayan', 101, 1584),
(19810, 'Rura', 101, 1584),
(19811, 'Rustamnagar Sahaspur', 101, 1584),
(19812, 'Sabatwar', 101, 1584),
(19813, 'Sadabad', 101, 1584),
(19814, 'Sadat', 101, 1584),
(19815, 'Safipur', 101, 1584),
(19816, 'Sahanpur', 101, 1584),
(19817, 'Saharanpur', 101, 1584),
(19818, 'Sahaspur', 101, 1584),
(19819, 'Sahaswan', 101, 1584),
(19820, 'Sahawar', 101, 1584),
(19821, 'Sahibabad', 101, 1584),
(19822, 'Sahjanwa', 101, 1584),
(19823, 'Sahpau', 101, 1584),
(19824, 'Saidpur', 101, 1584),
(19825, 'Sainthal', 101, 1584),
(19826, 'Saiyadraja', 101, 1584),
(19827, 'Sakhanu', 101, 1584),
(19828, 'Sakit', 101, 1584),
(19829, 'Salarpur Khadar', 101, 1584),
(19830, 'Salimpur', 101, 1584),
(19831, 'Salon', 101, 1584),
(19832, 'Sambhal', 101, 1584),
(19833, 'Sambhawali', 101, 1584),
(19834, 'Samdhan', 101, 1584),
(19835, 'Samthar', 101, 1584),
(19836, 'Sandi', 101, 1584),
(19837, 'Sandila', 101, 1584),
(19838, 'Sarai Mir', 101, 1584),
(19839, 'Sarai akil', 101, 1584),
(19840, 'Sarauli', 101, 1584),
(19841, 'Sardhana', 101, 1584),
(19842, 'Sarila', 101, 1584),
(19843, 'Sarsawan', 101, 1584),
(19844, 'Sasni', 101, 1584),
(19845, 'Satrikh', 101, 1584),
(19846, 'Saunkh', 101, 1584),
(19847, 'Saurikh', 101, 1584),
(19848, 'Seohara', 101, 1584),
(19849, 'Sewal Khas', 101, 1584),
(19850, 'Sewarhi', 101, 1584),
(19851, 'Shahabad', 101, 1584),
(19852, 'Shahganj', 101, 1584),
(19853, 'Shahi', 101, 1584),
(19854, 'Shahjahanpur', 101, 1584),
(19855, 'Shahjahanpur Cantonment', 101, 1584),
(19856, 'Shahpur', 101, 1584),
(19857, 'Shamli', 101, 1584),
(19858, 'Shamsabad', 101, 1584),
(19859, 'Shankargarh', 101, 1584),
(19860, 'Shergarh', 101, 1584),
(19861, 'Sherkot', 101, 1584),
(19862, 'Shikarpur', 101, 1584),
(19863, 'Shikohabad', 101, 1584),
(19864, 'Shisgarh', 101, 1584),
(19865, 'Shivdaspur', 101, 1584),
(19866, 'Shivli', 101, 1584),
(19867, 'Shivrajpur', 101, 1584),
(19868, 'Shohratgarh', 101, 1584),
(19869, 'Siddhanur', 101, 1584),
(19870, 'Siddharthnagar', 101, 1584),
(19871, 'Sidhauli', 101, 1584),
(19872, 'Sidhpura', 101, 1584),
(19873, 'Sikandarabad', 101, 1584),
(19874, 'Sikandarpur', 101, 1584),
(19875, 'Sikandra', 101, 1584),
(19876, 'Sikandra Rao', 101, 1584),
(19877, 'Singahi Bhiraura', 101, 1584),
(19878, 'Sirathu', 101, 1584),
(19879, 'Sirsa', 101, 1584),
(19880, 'Sirsaganj', 101, 1584),
(19881, 'Sirsi', 101, 1584),
(19882, 'Sisauli', 101, 1584),
(19883, 'Siswa Bazar', 101, 1584),
(19884, 'Sitapur', 101, 1584),
(19885, 'Siyana', 101, 1584),
(19886, 'Som', 101, 1584),
(19887, 'Sonbhadra', 101, 1584),
(19888, 'Soron', 101, 1584),
(19889, 'Suar', 101, 1584),
(19890, 'Sukhmalpur Nizamabad', 101, 1584),
(19891, 'Sultanpur', 101, 1584),
(19892, 'Sumerpur', 101, 1584),
(19893, 'Suriyawan', 101, 1584),
(19894, 'Swamibagh', 101, 1584),
(19895, 'Tajpur', 101, 1584),
(19896, 'Talbahat', 101, 1584),
(19897, 'Talgram', 101, 1584),
(19898, 'Tambaur', 101, 1584),
(19899, 'Tanda', 101, 1584),
(19900, 'Tatarpur Lallu', 101, 1584),
(19901, 'Tetribazar', 101, 1584),
(19902, 'Thakurdwara', 101, 1584),
(19903, 'Thana Bhawan', 101, 1584),
(19904, 'Thiriya Nizamat Khan', 101, 1584),
(19905, 'Tikaitnagar', 101, 1584),
(19906, 'Tikri', 101, 1584),
(19907, 'Tilhar', 101, 1584),
(19908, 'Tindwari', 101, 1584),
(19909, 'Tirwaganj', 101, 1584),
(19910, 'Titron', 101, 1584),
(19911, 'Tori Fatehpur', 101, 1584),
(19912, 'Tulsipur', 101, 1584),
(19913, 'Tundla', 101, 1584),
(19914, 'Tundla Kham', 101, 1584),
(19915, 'Tundla Railway Colony', 101, 1584),
(19916, 'Ugu', 101, 1584),
(19917, 'Ujhani', 101, 1584),
(19918, 'Ujhari', 101, 1584),
(19919, 'Umri', 101, 1584),
(19920, 'Umri Kalan', 101, 1584),
(19921, 'Un', 101, 1584),
(19922, 'Unchahar', 101, 1584),
(19923, 'Unnao', 101, 1584),
(19924, 'Usaihat', 101, 1584),
(19925, 'Usawan', 101, 1584),
(19926, 'Utraula', 101, 1584),
(19927, 'Varanasi', 101, 1584),
(19928, 'Varanasi Cantonment', 101, 1584),
(19929, 'Vijaigarh', 101, 1584),
(19930, 'Vrindavan', 101, 1584),
(19931, 'Wazirganj', 101, 1584),
(19932, 'Zafarabad', 101, 1584),
(19933, 'Zaidpur', 101, 1584),
(19934, 'Zamania', 101, 1584),
(19935, 'Almora', 101, 1585),
(19936, 'Almora Cantonment', 101, 1585),
(19937, 'Badrinathpuri', 101, 1585),
(19938, 'Bageshwar', 101, 1585),
(19939, 'Bah Bazar', 101, 1585),
(19940, 'Banbasa', 101, 1585),
(19941, 'Bandia', 101, 1585),
(19942, 'Barkot', 101, 1585),
(19943, 'Bazpur', 101, 1585),
(19944, 'Bhim Tal', 101, 1585),
(19945, 'Bhowali', 101, 1585),
(19946, 'Chakrata', 101, 1585),
(19947, 'Chamba', 101, 1585),
(19948, 'Chamoli and Gopeshwar', 101, 1585),
(19949, 'Champawat', 101, 1585),
(19950, 'Clement Town', 101, 1585),
(19951, 'Dehra Dun Cantonment', 101, 1585),
(19952, 'Dehradun', 101, 1585),
(19953, 'Dehrakhas', 101, 1585),
(19954, 'Devaprayag', 101, 1585),
(19955, 'Dhaluwala', 101, 1585),
(19956, 'Dhandera', 101, 1585),
(19957, 'Dharchula', 101, 1585),
(19958, 'Dharchula Dehat', 101, 1585),
(19959, 'Didihat', 101, 1585),
(19960, 'Dineshpur', 101, 1585),
(19961, 'Doiwala', 101, 1585),
(19962, 'Dugadda', 101, 1585),
(19963, 'Dwarahat', 101, 1585),
(19964, 'Gadarpur', 101, 1585),
(19965, 'Gangotri', 101, 1585),
(19966, 'Gauchar', 101, 1585),
(19967, 'Haldwani', 101, 1585),
(19968, 'Haridwar', 101, 1585),
(19969, 'Herbertpur', 101, 1585),
(19970, 'Jaspur', 101, 1585),
(19971, 'Jhabrera', 101, 1585),
(19972, 'Joshimath', 101, 1585),
(19973, 'Kachnal Gosain', 101, 1585),
(19974, 'Kaladungi', 101, 1585),
(19975, 'Kalagarh', 101, 1585),
(19976, 'Karnaprayang', 101, 1585),
(19977, 'Kashipur', 101, 1585),
(19978, 'Kashirampur', 101, 1585),
(19979, 'Kausani', 101, 1585),
(19980, 'Kedarnath', 101, 1585),
(19981, 'Kelakhera', 101, 1585),
(19982, 'Khatima', 101, 1585),
(19983, 'Kichha', 101, 1585),
(19984, 'Kirtinagar', 101, 1585),
(19985, 'Kotdwara', 101, 1585),
(19986, 'Laksar', 101, 1585),
(19987, 'Lalkuan', 101, 1585),
(19988, 'Landaura', 101, 1585),
(19989, 'Landhaura Cantonment', 101, 1585),
(19990, 'Lensdaun', 101, 1585),
(19991, 'Logahat', 101, 1585),
(19992, 'Mahua Dabra Haripura', 101, 1585),
(19993, 'Mahua Kheraganj', 101, 1585),
(19994, 'Manglaur', 101, 1585),
(19995, 'Masuri', 101, 1585),
(19996, 'Mohanpur Mohammadpur', 101, 1585),
(19997, 'Muni Ki Reti', 101, 1585),
(19998, 'Nagla', 101, 1585),
(19999, 'Nainital', 101, 1585),
(20000, 'Nainital Cantonment', 101, 1585),
(20001, 'Nandaprayang', 101, 1585),
(20002, 'Narendranagar', 101, 1585),
(20003, 'Pauri', 101, 1585),
(20004, 'Pithoragarh', 101, 1585),
(20005, 'Pratitnagar', 101, 1585),
(20006, 'Raipur', 101, 1585),
(20007, 'Raiwala', 101, 1585),
(20008, 'Ramnagar', 101, 1585),
(20009, 'Ranikhet', 101, 1585),
(20010, 'Ranipur', 101, 1585),
(20011, 'Rishikesh', 101, 1585),
(20012, 'Rishikesh Cantonment', 101, 1585),
(20013, 'Roorkee', 101, 1585),
(20014, 'Rudraprayag', 101, 1585),
(20015, 'Rudrapur', 101, 1585),
(20016, 'Rurki', 101, 1585),
(20017, 'Rurki Cantonment', 101, 1585),
(20018, 'Shaktigarh', 101, 1585),
(20019, 'Sitarganj', 101, 1585),
(20020, 'Srinagar', 101, 1585),
(20021, 'Sultanpur', 101, 1585),
(20022, 'Tanakpur', 101, 1585),
(20023, 'Tehri', 101, 1585),
(20024, 'Udham Singh Nagar', 101, 1585),
(20025, 'Uttarkashi', 101, 1585),
(20026, 'Vikasnagar', 101, 1585),
(20027, 'Virbhadra', 101, 1585),
(20028, '24 Parganas (n)', 101, 1587),
(20029, '24 Parganas (s)', 101, 1587),
(20030, 'Adra', 101, 1587),
(20031, 'Ahmadpur', 101, 1587),
(20032, 'Aiho', 101, 1587),
(20033, 'Aistala', 101, 1587),
(20034, 'Alipur Duar', 101, 1587),
(20035, 'Alipur Duar Railway Junction', 101, 1587),
(20036, 'Alpur', 101, 1587),
(20037, 'Amalhara', 101, 1587),
(20038, 'Amkula', 101, 1587),
(20039, 'Amlagora', 101, 1587),
(20040, 'Amodghata', 101, 1587),
(20041, 'Amtala', 101, 1587),
(20042, 'Andul', 101, 1587),
(20043, 'Anksa', 101, 1587),
(20044, 'Ankurhati', 101, 1587),
(20045, 'Anup Nagar', 101, 1587),
(20046, 'Arambagh', 101, 1587),
(20047, 'Argari', 101, 1587),
(20048, 'Arsha', 101, 1587),
(20049, 'Asansol', 101, 1587),
(20050, 'Ashoknagar Kalyangarh', 101, 1587),
(20051, 'Aurangabad', 101, 1587),
(20052, 'Bablari Dewanganj', 101, 1587),
(20053, 'Badhagachhi', 101, 1587),
(20054, 'Baduria', 101, 1587),
(20055, 'Baghdogra', 101, 1587),
(20056, 'Bagnan', 101, 1587),
(20057, 'Bagra', 101, 1587),
(20058, 'Bagula', 101, 1587),
(20059, 'Baharampur', 101, 1587),
(20060, 'Bahirgram', 101, 1587),
(20061, 'Bahula', 101, 1587),
(20062, 'Baidyabati', 101, 1587),
(20063, 'Bairatisal', 101, 1587),
(20064, 'Baj Baj', 101, 1587),
(20065, 'Bakreswar', 101, 1587),
(20066, 'Balaram Pota', 101, 1587),
(20067, 'Balarampur', 101, 1587),
(20068, 'Bali Chak', 101, 1587),
(20069, 'Ballavpur', 101, 1587),
(20070, 'Bally', 101, 1587),
(20071, 'Balurghat', 101, 1587),
(20072, 'Bamunari', 101, 1587),
(20073, 'Banarhat Tea Garden', 101, 1587),
(20074, 'Bandel', 101, 1587),
(20075, 'Bangaon', 101, 1587),
(20076, 'Bankra', 101, 1587),
(20077, 'Bankura', 101, 1587),
(20078, 'Bansbaria', 101, 1587),
(20079, 'Banshra', 101, 1587),
(20080, 'Banupur', 101, 1587),
(20081, 'Bara Bamonia', 101, 1587),
(20082, 'Barakpur', 101, 1587),
(20083, 'Barakpur Cantonment', 101, 1587),
(20084, 'Baranagar', 101, 1587),
(20085, 'Barasat', 101, 1587),
(20086, 'Barddhaman', 101, 1587),
(20087, 'Barijhati', 101, 1587),
(20088, 'Barjora', 101, 1587),
(20089, 'Barrackpore', 101, 1587),
(20090, 'Baruihuda', 101, 1587),
(20091, 'Baruipur', 101, 1587),
(20092, 'Barunda', 101, 1587),
(20093, 'Basirhat', 101, 1587),
(20094, 'Baska', 101, 1587),
(20095, 'Begampur', 101, 1587),
(20096, 'Beldanga', 101, 1587),
(20097, 'Beldubi', 101, 1587),
(20098, 'Belebathan', 101, 1587),
(20099, 'Beliator', 101, 1587),
(20100, 'Bhadreswar', 101, 1587),
(20101, 'Bhandardaha', 101, 1587),
(20102, 'Bhangar Raghunathpur', 101, 1587),
(20103, 'Bhangri Pratham Khanda', 101, 1587),
(20104, 'Bhanowara', 101, 1587),
(20105, 'Bhatpara', 101, 1587),
(20106, 'Bholar Dabri', 101, 1587),
(20107, 'Bidhannagar', 101, 1587),
(20108, 'Bidyadharpur', 101, 1587),
(20109, 'Biki Hakola', 101, 1587),
(20110, 'Bilandapur', 101, 1587),
(20111, 'Bilpahari', 101, 1587),
(20112, 'Bipra Noapara', 101, 1587),
(20113, 'Birlapur', 101, 1587),
(20114, 'Birnagar', 101, 1587),
(20115, 'Bisarpara', 101, 1587),
(20116, 'Bishnupur', 101, 1587),
(20117, 'Bolpur', 101, 1587),
(20118, 'Bongaon', 101, 1587),
(20119, 'Bowali', 101, 1587),
(20120, 'Burdwan', 101, 1587),
(20121, 'Canning', 101, 1587),
(20122, 'Cart Road', 101, 1587),
(20123, 'Chachanda', 101, 1587),
(20124, 'Chak Bankola', 101, 1587),
(20125, 'Chak Enayetnagar', 101, 1587),
(20126, 'Chak Kashipur', 101, 1587),
(20127, 'Chakalampur', 101, 1587),
(20128, 'Chakbansberia', 101, 1587),
(20129, 'Chakdaha', 101, 1587),
(20130, 'Chakpara', 101, 1587),
(20131, 'Champahati', 101, 1587),
(20132, 'Champdani', 101, 1587),
(20133, 'Chamrail', 101, 1587),
(20134, 'Chandannagar', 101, 1587),
(20135, 'Chandpur', 101, 1587),
(20136, 'Chandrakona', 101, 1587),
(20137, 'Chapari', 101, 1587),
(20138, 'Chapui', 101, 1587),
(20139, 'Char Brahmanagar', 101, 1587),
(20140, 'Char Maijdia', 101, 1587),
(20141, 'Charka', 101, 1587),
(20142, 'Chata Kalikapur', 101, 1587),
(20143, 'Chauhati', 101, 1587),
(20144, 'Checha Khata', 101, 1587),
(20145, 'Chelad', 101, 1587),
(20146, 'Chhora', 101, 1587),
(20147, 'Chikrand', 101, 1587),
(20148, 'Chittaranjan', 101, 1587),
(20149, 'Contai', 101, 1587),
(20150, 'Cooch Behar', 101, 1587),
(20151, 'Dainhat', 101, 1587),
(20152, 'Dakshin Baguan', 101, 1587),
(20153, 'Dakshin Jhapardaha', 101, 1587),
(20154, 'Dakshin Rajyadharpur', 101, 1587),
(20155, 'Dakshin Raypur', 101, 1587),
(20156, 'Dalkola', 101, 1587),
(20157, 'Dalurband', 101, 1587),
(20158, 'Darap Pur', 101, 1587),
(20159, 'Darjiling', 101, 1587),
(20160, 'Daulatpur', 101, 1587),
(20161, 'Debipur', 101, 1587),
(20162, 'Defahat', 101, 1587),
(20163, 'Deora', 101, 1587),
(20164, 'Deulia', 101, 1587),
(20165, 'Dhakuria', 101, 1587),
(20166, 'Dhandadihi', 101, 1587),
(20167, 'Dhanyakuria', 101, 1587),
(20168, 'Dharmapur', 101, 1587),
(20169, 'Dhatri Gram', 101, 1587),
(20170, 'Dhuilya', 101, 1587),
(20171, 'Dhulagari', 101, 1587),
(20172, 'Dhulian', 101, 1587),
(20173, 'Dhupgari', 101, 1587),
(20174, 'Dhusaripara', 101, 1587),
(20175, 'Diamond Harbour', 101, 1587),
(20176, 'Digha', 101, 1587),
(20177, 'Dignala', 101, 1587),
(20178, 'Dinhata', 101, 1587),
(20179, 'Dubrajpur', 101, 1587),
(20180, 'Dumjor', 101, 1587),
(20181, 'Durgapur', 101, 1587),
(20182, 'Durllabhganj', 101, 1587),
(20183, 'Egra', 101, 1587),
(20184, 'Eksara', 101, 1587),
(20185, 'Falakata', 101, 1587),
(20186, 'Farakka', 101, 1587),
(20187, 'Fatellapur', 101, 1587),
(20188, 'Fort Gloster', 101, 1587),
(20189, 'Gabberia', 101, 1587),
(20190, 'Gadigachha', 101, 1587),
(20191, 'Gairkata', 101, 1587),
(20192, 'Gangarampur', 101, 1587),
(20193, 'Garalgachha', 101, 1587),
(20194, 'Garbeta Amlagora', 101, 1587),
(20195, 'Garhbeta', 101, 1587),
(20196, 'Garshyamnagar', 101, 1587),
(20197, 'Garui', 101, 1587),
(20198, 'Garulia', 101, 1587),
(20199, 'Gayespur', 101, 1587),
(20200, 'Ghatal', 101, 1587),
(20201, 'Ghorsala', 101, 1587),
(20202, 'Goaljan', 101, 1587),
(20203, 'Goasafat', 101, 1587),
(20204, 'Gobardanga', 101, 1587),
(20205, 'Gobindapur', 101, 1587),
(20206, 'Gopalpur', 101, 1587),
(20207, 'Gopinathpur', 101, 1587),
(20208, 'Gora Bazar', 101, 1587),
(20209, 'Guma', 101, 1587),
(20210, 'Gurdaha', 101, 1587),
(20211, 'Guriahati', 101, 1587),
(20212, 'Guskhara', 101, 1587),
(20213, 'Habra', 101, 1587),
(20214, 'Haldia', 101, 1587),
(20215, 'Haldibari', 101, 1587),
(20216, 'Halisahar', 101, 1587),
(20217, 'Haora', 101, 1587),
(20218, 'Harharia Chak', 101, 1587),
(20219, 'Harindanga', 101, 1587),
(20220, 'Haringhata', 101, 1587),
(20221, 'Haripur', 101, 1587),
(20222, 'Harishpur', 101, 1587),
(20223, 'Hatgachha', 101, 1587),
(20224, 'Hatsimla', 101, 1587),
(20225, 'Hijuli', 101, 1587),
(20226, 'Hindustan Cables Town', 101, 1587),
(20227, 'Hooghly', 101, 1587),
(20228, 'Howrah', 101, 1587),
(20229, 'Hugli-Chunchura', 101, 1587),
(20230, 'Humaipur', 101, 1587),
(20231, 'Ichha Pur Defence Estate', 101, 1587),
(20232, 'Ingraj Bazar', 101, 1587),
(20233, 'Islampur', 101, 1587),
(20234, 'Jafarpur', 101, 1587),
(20235, 'Jagadanandapur', 101, 1587),
(20236, 'Jagdishpur', 101, 1587),
(20237, 'Jagtaj', 101, 1587),
(20238, 'Jala Kendua', 101, 1587),
(20239, 'Jaldhaka', 101, 1587),
(20240, 'Jalkhura', 101, 1587),
(20241, 'Jalpaiguri', 101, 1587),
(20242, 'Jamuria', 101, 1587),
(20243, 'Jangipur', 101, 1587),
(20244, 'Jaygaon', 101, 1587),
(20245, 'Jaynagar-Majilpur', 101, 1587),
(20246, 'Jemari', 101, 1587),
(20247, 'Jemari Township', 101, 1587),
(20248, 'Jetia', 101, 1587),
(20249, 'Jhalida', 101, 1587),
(20250, 'Jhargram', 101, 1587),
(20251, 'Jhorhat', 101, 1587),
(20252, 'Jiaganj-Azimganj', 101, 1587),
(20253, 'Joka', 101, 1587),
(20254, 'Jot Kamal', 101, 1587),
(20255, 'Kachu Pukur', 101, 1587),
(20256, 'Kajora', 101, 1587),
(20257, 'Kakdihi', 101, 1587),
(20258, 'Kakdwip', 101, 1587),
(20259, 'Kalaikunda', 101, 1587),
(20260, 'Kalara', 101, 1587),
(20261, 'Kalimpong', 101, 1587),
(20262, 'Kaliyaganj', 101, 1587),
(20263, 'Kalna', 101, 1587),
(20264, 'Kalyani', 101, 1587),
(20265, 'Kamarhati', 101, 1587),
(20266, 'Kanaipur', 101, 1587),
(20267, 'Kanchrapara', 101, 1587),
(20268, 'Kandi', 101, 1587),
(20269, 'Kanki', 101, 1587),
(20270, 'Kankuria', 101, 1587),
(20271, 'Kantlia', 101, 1587),
(20272, 'Kanyanagar', 101, 1587),
(20273, 'Karimpur', 101, 1587),
(20274, 'Karsiyang', 101, 1587),
(20275, 'Kasba', 101, 1587),
(20276, 'Kasimbazar', 101, 1587),
(20277, 'Katwa', 101, 1587),
(20278, 'Kaugachhi', 101, 1587),
(20279, 'Kenda', 101, 1587),
(20280, 'Kendra Khottamdi', 101, 1587),
(20281, 'Kendua', 101, 1587),
(20282, 'Kesabpur', 101, 1587),
(20283, 'Khagrabari', 101, 1587),
(20284, 'Khalia', 101, 1587),
(20285, 'Khalor', 101, 1587),
(20286, 'Khandra', 101, 1587),
(20287, 'Khantora', 101, 1587),
(20288, 'Kharagpur', 101, 1587),
(20289, 'Kharagpur Railway Settlement', 101, 1587),
(20290, 'Kharar', 101, 1587),
(20291, 'Khardaha', 101, 1587),
(20292, 'Khari Mala Khagrabari', 101, 1587),
(20293, 'Kharsarai', 101, 1587),
(20294, 'Khatra', 101, 1587),
(20295, 'Khodarampur', 101, 1587),
(20296, 'Kodalia', 101, 1587),
(20297, 'Kolaghat', 101, 1587),
(20298, 'Kolaghat Thermal Power Project', 101, 1587),
(20299, 'Kolkata', 101, 1587),
(20300, 'Konardihi', 101, 1587),
(20301, 'Konnogar', 101, 1587),
(20302, 'Krishnanagar', 101, 1587),
(20303, 'Krishnapur', 101, 1587),
(20304, 'Kshidirpur', 101, 1587),
(20305, 'Kshirpai', 101, 1587),
(20306, 'Kulihanda', 101, 1587),
(20307, 'Kulti', 101, 1587),
(20308, 'Kunustara', 101, 1587),
(20309, 'Kuperskem', 101, 1587),
(20310, 'Madanpur', 101, 1587),
(20311, 'Madhusudanpur', 101, 1587),
(20312, 'Madhyamgram', 101, 1587),
(20313, 'Maheshtala', 101, 1587),
(20314, 'Mahiari', 101, 1587),
(20315, 'Mahikpur', 101, 1587),
(20316, 'Mahira', 101, 1587),
(20317, 'Mahishadal', 101, 1587),
(20318, 'Mainaguri', 101, 1587),
(20319, 'Makardaha', 101, 1587),
(20320, 'Mal', 101, 1587),
(20321, 'Malda', 101, 1587),
(20322, 'Mandarbani', 101, 1587),
(20323, 'Mansinhapur', 101, 1587),
(20324, 'Masila', 101, 1587),
(20325, 'Maslandapur', 101, 1587),
(20326, 'Mathabhanga', 101, 1587),
(20327, 'Mekliganj', 101, 1587),
(20328, 'Memari', 101, 1587),
(20329, 'Midnapur', 101, 1587),
(20330, 'Mirik', 101, 1587),
(20331, 'Monoharpur', 101, 1587),
(20332, 'Mrigala', 101, 1587),
(20333, 'Muragachha', 101, 1587),
(20334, 'Murgathaul', 101, 1587),
(20335, 'Murshidabad', 101, 1587),
(20336, 'Nabadhai Dutta Pukur', 101, 1587),
(20337, 'Nabagram', 101, 1587),
(20338, 'Nabgram', 101, 1587),
(20339, 'Nachhratpur Katabari', 101, 1587),
(20340, 'Nadia', 101, 1587),
(20341, 'Naihati', 101, 1587),
(20342, 'Nalhati', 101, 1587),
(20343, 'Nasra', 101, 1587),
(20344, 'Natibpur', 101, 1587),
(20345, 'Naupala', 101, 1587),
(20346, 'Navadwip', 101, 1587),
(20347, 'Nebadhai Duttapukur', 101, 1587),
(20348, 'New Barrackpore', 101, 1587),
(20349, 'Ni Barakpur', 101, 1587),
(20350, 'Nibra', 101, 1587),
(20351, 'Noapara', 101, 1587),
(20352, 'Nokpul', 101, 1587),
(20353, 'North Barakpur', 101, 1587),
(20354, 'Odlabari', 101, 1587),
(20355, 'Old Maldah', 101, 1587),
(20356, 'Ondal', 101, 1587),
(20357, 'Pairagachha', 101, 1587),
(20358, 'Palashban', 101, 1587),
(20359, 'Panchla', 101, 1587),
(20360, 'Panchpara', 101, 1587),
(20361, 'Pandua', 101, 1587),
(20362, 'Pangachhiya', 101, 1587),
(20363, 'Paniara', 101, 1587),
(20364, 'Panihati', 101, 1587),
(20365, 'Panuhat', 101, 1587),
(20366, 'Par Beliya', 101, 1587),
(20367, 'Parashkol', 101, 1587),
(20368, 'Parasia', 101, 1587),
(20369, 'Parbbatipur', 101, 1587),
(20370, 'Parui', 101, 1587),
(20371, 'Paschim Jitpur', 101, 1587),
(20372, 'Paschim Punro Para', 101, 1587),
(20373, 'Patrasaer', 101, 1587),
(20374, 'Pattabong Tea Garden', 101, 1587),
(20375, 'Patuli', 101, 1587),
(20376, 'Patulia', 101, 1587),
(20377, 'Phulia', 101, 1587),
(20378, 'Podara', 101, 1587),
(20379, 'Port Blair', 101, 1587),
(20380, 'Prayagpur', 101, 1587),
(20381, 'Pujali', 101, 1587),
(20382, 'Purba Medinipur', 101, 1587),
(20383, 'Purba Tajpur', 101, 1587),
(20384, 'Purulia', 101, 1587),
(20385, 'Raghudebbati', 101, 1587),
(20386, 'Raghudebpur', 101, 1587),
(20387, 'Raghunathchak', 101, 1587),
(20388, 'Raghunathpur', 101, 1587),
(20389, 'Raghunathpur-Dankuni', 101, 1587),
(20390, 'Raghunathpur-Magra', 101, 1587),
(20391, 'Raigachhi', 101, 1587),
(20392, 'Raiganj', 101, 1587),
(20393, 'Raipur', 101, 1587),
(20394, 'Rajarhat Gopalpur', 101, 1587),
(20395, 'Rajpur', 101, 1587),
(20396, 'Ramchandrapur', 101, 1587),
(20397, 'Ramjibanpur', 101, 1587),
(20398, 'Ramnagar', 101, 1587),
(20399, 'Rampur Hat', 101, 1587),
(20400, 'Ranaghat', 101, 1587),
(20401, 'Raniganj', 101, 1587),
(20402, 'Ratibati', 101, 1587),
(20403, 'Raypur', 101, 1587),
(20404, 'Rishra', 101, 1587),
(20405, 'Rishra Cantonment', 101, 1587),
(20406, 'Ruiya', 101, 1587),
(20407, 'Sahajadpur', 101, 1587),
(20408, 'Sahapur', 101, 1587),
(20409, 'Sainthia', 101, 1587),
(20410, 'Salap', 101, 1587),
(20411, 'Sankarpur', 101, 1587),
(20412, 'Sankrail', 101, 1587),
(20413, 'Santoshpur', 101, 1587),
(20414, 'Saontaidih', 101, 1587),
(20415, 'Sarenga', 101, 1587),
(20416, 'Sarpi', 101, 1587),
(20417, 'Satigachha', 101, 1587),
(20418, 'Serpur', 101, 1587),
(20419, 'Shankhanagar', 101, 1587),
(20420, 'Shantipur', 101, 1587),
(20421, 'Shrirampur', 101, 1587),
(20422, 'Siduli', 101, 1587),
(20423, 'Siliguri', 101, 1587),
(20424, 'Simla', 101, 1587),
(20425, 'Singur', 101, 1587),
(20426, 'Sirsha', 101, 1587),
(20427, 'Siuri', 101, 1587),
(20428, 'Sobhaganj', 101, 1587),
(20429, 'Sodpur', 101, 1587),
(20430, 'Sonamukhi', 101, 1587),
(20431, 'Sonatikiri', 101, 1587),
(20432, 'Srikantabati', 101, 1587),
(20433, 'Srirampur', 101, 1587),
(20434, 'Sukdal', 101, 1587),
(20435, 'Taherpur', 101, 1587),
(20436, 'Taki', 101, 1587),
(20437, 'Talbandha', 101, 1587),
(20438, 'Tamluk', 101, 1587),
(20439, 'Tarakeswar', 101, 1587),
(20440, 'Tentulberia', 101, 1587),
(20441, 'Tentulkuli', 101, 1587),
(20442, 'Thermal Power Project', 101, 1587),
(20443, 'Tinsukia', 101, 1587),
(20444, 'Titagarh', 101, 1587),
(20445, 'Tufanganj', 101, 1587),
(20446, 'Ukhra', 101, 1587),
(20447, 'Ula', 101, 1587),
(20448, 'Ulubaria', 101, 1587),
(20449, 'Uttar Durgapur', 101, 1587),
(20450, 'Uttar Goara', 101, 1587),
(20451, 'Uttar Kalas', 101, 1587),
(20452, 'Uttar Kamakhyaguri', 101, 1587),
(20453, 'Uttar Latabari', 101, 1587),
(20454, 'Uttar Mahammadpur', 101, 1587),
(20455, 'Uttar Pirpur', 101, 1587),
(20456, 'Uttar Raypur', 101, 1587),
(20457, 'Uttarpara-Kotrung', 101, 1587),
(47954, 'Sawantwadi', 101, 1568),
(47955, 'Kurkumbh', 101, 1568),
(47956, 'Fursungi', 101, 1568),
(47957, 'Ranjangaon', 101, 1568),
(47958, 'Serampore', 101, 1568),
(47959, 'Tembhurni', 101, 1568);

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_settings`
--

CREATE TABLE `client_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_users`
--

CREATE TABLE `client_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  `iso_code` varchar(2) NOT NULL COMMENT 'ISO 3166-1 alpha-2 code',
  `isd_code` varchar(7) DEFAULT NULL COMMENT 'International Subscriber Dialing code',
  `flag` varchar(250) DEFAULT NULL COMMENT 'Flag image filename'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Countries';

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso_code`, `isd_code`, `flag`) VALUES
(101, 'India', 'IN', '91', 'in.png');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `country` varchar(50) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `symbol` varchar(10) DEFAULT NULL,
  `currency_format` varchar(30) DEFAULT 'us',
  `symbol_direction` varchar(30) DEFAULT 'left',
  `space_money_symbol` tinyint(1) DEFAULT 0,
  `exchange_rate` decimal(16,4) DEFAULT 0.0000,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_booksets`
--

CREATE TABLE `erp_booksets` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `school_id` int(11) NOT NULL COMMENT 'Foreign key to erp_schools table',
  `board_id` int(11) NOT NULL COMMENT 'Foreign key to erp_school_boards table',
  `grade_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_grades table',
  `bookset_name` varchar(255) DEFAULT NULL COMMENT 'Bookset Name (optional)',
  `has_products` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Bookset with products, 0 = Bookset without products',
  `mandatory_packages` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of mandatory packages required',
  `total_packages` int(11) NOT NULL DEFAULT 0 COMMENT 'Total number of packages in this bookset',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Booksets - Groups of Packages';

-- --------------------------------------------------------

--
-- Table structure for table `erp_bookset_packages`
--

CREATE TABLE `erp_bookset_packages` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `bookset_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Foreign key to erp_booksets table',
  `school_id` int(11) NOT NULL COMMENT 'Foreign key to erp_schools table',
  `board_id` int(11) NOT NULL COMMENT 'Foreign key to erp_school_boards table',
  `grade_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_grades table',
  `category_id` int(11) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL COMMENT 'Package category type: textbook, notebook, or stationery',
  `package_name` varchar(255) NOT NULL COMMENT 'Package Name',
  `package_price` decimal(10,2) DEFAULT 0.00 COMMENT 'Package Price',
  `package_offer_price` decimal(10,2) DEFAULT 0.00 COMMENT 'Package Offer Price',
  `gst` decimal(5,2) DEFAULT 0.00 COMMENT 'GST Percentage',
  `hsn` varchar(50) DEFAULT NULL COMMENT 'HSN Code',
  `package_weight` decimal(10,2) NOT NULL COMMENT 'Weight of Package (in gm)',
  `is_it` enum('mandatory','optional','mandatory+optional') NOT NULL DEFAULT 'mandatory' COMMENT 'Is It? (mandatory, optional, or mandatory+optional)',
  `note` text DEFAULT NULL COMMENT 'Note',
  `mandatory_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of mandatory products',
  `optional_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of optional products',
  `mandatory_optional_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of mandatory+optional products',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `with_product` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Package with products, 0 = Package without products',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bookset Packages';

-- --------------------------------------------------------

--
-- Table structure for table `erp_bookset_package_products`
--

CREATE TABLE `erp_bookset_package_products` (
  `id` int(11) UNSIGNED NOT NULL,
  `package_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_bookset_packages table',
  `product_type` enum('textbook','notebook') NOT NULL COMMENT 'Type of product (textbook or notebook)',
  `product_id` int(11) UNSIGNED NOT NULL COMMENT 'Product ID (textbook_id or notebook_id)',
  `display_name` varchar(255) NOT NULL COMMENT 'Display Name for this product in the package',
  `quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Quantity',
  `discounted_mrp` decimal(10,2) NOT NULL COMMENT 'Discounted MRP',
  `is_it` enum('mandatory','optional','mandatory+optional') NOT NULL DEFAULT 'mandatory' COMMENT 'Is It? (mandatory, optional, or mandatory+optional)',
  `weight` decimal(10,2) NOT NULL COMMENT 'Weight of this product (in gm)',
  `note` text DEFAULT NULL COMMENT 'Note for this product',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Products within Bookset Packages';

-- --------------------------------------------------------

--
-- Table structure for table `erp_clients`
--

CREATE TABLE `erp_clients` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Client/Vendor name',
  `domain` varchar(255) NOT NULL COMMENT 'Client domain (e.g., shyam.com)',
  `username` varchar(100) DEFAULT NULL COMMENT 'Vendor login username',
  `password` varchar(255) DEFAULT NULL COMMENT 'Vendor login password (SHA1 hash)',
  `database_name` varchar(100) NOT NULL COMMENT 'Client database name',
  `db_username` varchar(100) DEFAULT NULL,
  `status` enum('active','suspended','inactive') NOT NULL DEFAULT 'active' COMMENT 'Client status',
  `sidebar_color` varchar(50) DEFAULT 'sidebarbg1' COMMENT 'Sidebar color theme (sidebarbg1-sidebarbg6)',
  `logo` varchar(255) DEFAULT NULL COMMENT 'Vendor logo file path',
  `favicon` varchar(255) DEFAULT NULL,
  `site_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `payment_gateway` enum('razorpay','ccavenue','') DEFAULT '' COMMENT 'Payment gateway provider (razorpay or ccavenue)',
  `razorpay_key_id` varchar(255) DEFAULT NULL COMMENT 'Razorpay Key ID (Live)',
  `razorpay_key_secret` varchar(255) DEFAULT NULL COMMENT 'Razorpay Key Secret (Live)',
  `ccavenue_merchant_id` varchar(255) DEFAULT NULL COMMENT 'CCAvenue Merchant ID (Live)',
  `ccavenue_access_code` varchar(255) DEFAULT NULL COMMENT 'CCAvenue Access Code (Live)',
  `ccavenue_working_key` varchar(255) DEFAULT NULL COMMENT 'CCAvenue Working Key (Live)',
  `zepto_mail_api_key` varchar(255) DEFAULT NULL COMMENT 'Zepto Mail API Key',
  `zepto_mail_from_email` varchar(255) DEFAULT NULL COMMENT 'Zepto Mail From Email Address',
  `zepto_mail_from_name` varchar(255) DEFAULT NULL COMMENT 'Zepto Mail From Name',
  `firebase_api_key` varchar(255) DEFAULT NULL COMMENT 'Firebase API Key',
  `firebase_auth_domain` varchar(255) DEFAULT NULL COMMENT 'Firebase Auth Domain',
  `firebase_project_id` varchar(255) DEFAULT NULL COMMENT 'Firebase Project ID',
  `firebase_storage_bucket` varchar(255) DEFAULT NULL COMMENT 'Firebase Storage Bucket',
  `firebase_messaging_sender_id` varchar(255) DEFAULT NULL COMMENT 'Firebase Messaging Sender ID',
  `firebase_app_id` varchar(255) DEFAULT NULL COMMENT 'Firebase App ID',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Client/Vendor information';

--
-- Dumping data for table `erp_clients`
--

INSERT INTO `erp_clients` (`id`, `name`, `domain`, `username`, `password`, `database_name`, `db_username`, `status`, `sidebar_color`, `logo`, `favicon`, `site_title`, `meta_description`, `meta_keywords`, `payment_gateway`, `razorpay_key_id`, `razorpay_key_secret`, `ccavenue_merchant_id`, `ccavenue_access_code`, `ccavenue_working_key`, `zepto_mail_api_key`, `zepto_mail_from_email`, `zepto_mail_from_name`, `firebase_api_key`, `firebase_auth_domain`, `firebase_project_id`, `firebase_storage_bucket`, `firebase_messaging_sender_id`, `firebase_app_id`, `created_at`, `updated_at`) VALUES
(34, '0', 'varitty.in', 'varitty', NULL, '', NULL, 'active', '#197641', 'uploads/vendors/logos/vendor_38_1769062350.png', 'uploads/vendors/favicons/favicon_38_1768913522.png', 'Varitty', 'Varitty', 'Varitty', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-15 14:11:25', '2026-01-22 06:40:50');

-- --------------------------------------------------------

--
-- Table structure for table `erp_client_features`
--

CREATE TABLE `erp_client_features` (
  `id` int(11) UNSIGNED NOT NULL,
  `client_id` int(11) UNSIGNED NOT NULL COMMENT 'Client ID',
  `feature_id` int(11) UNSIGNED NOT NULL COMMENT 'Feature ID',
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Is feature enabled for this client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Feature assignments to clients';

-- --------------------------------------------------------

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

-- --------------------------------------------------------

--
-- Table structure for table `erp_client_settings`
--

CREATE TABLE `erp_client_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `client_id` int(11) UNSIGNED NOT NULL COMMENT 'Client ID',
  `logo` varchar(255) DEFAULT NULL COMMENT 'Client logo path',
  `primary_color` varchar(7) DEFAULT '#007bff' COMMENT 'Primary brand color (hex)',
  `secondary_color` varchar(7) DEFAULT '#6c757d' COMMENT 'Secondary brand color (hex)',
  `theme` varchar(50) DEFAULT 'default' COMMENT 'Theme name',
  `sms_provider` varchar(50) DEFAULT NULL COMMENT 'SMS provider name',
  `sms_credentials` text DEFAULT NULL COMMENT 'SMS credentials (JSON)',
  `email_smtp_config` text DEFAULT NULL COMMENT 'Email SMTP configuration (JSON)',
  `whatsapp_config` text DEFAULT NULL COMMENT 'WhatsApp configuration (JSON)',
  `firebase_config` text DEFAULT NULL COMMENT 'Firebase configuration (JSON)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Client branding and configuration';

-- --------------------------------------------------------

--
-- Table structure for table `erp_features`
--

CREATE TABLE `erp_features` (
  `id` int(11) UNSIGNED NOT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Parent feature ID (NULL for main category, feature_id for sub-category)',
  `name` varchar(100) NOT NULL COMMENT 'Feature name',
  `slug` varchar(100) NOT NULL COMMENT 'Feature slug (e.g., books, bookset, uniforms)',
  `description` text DEFAULT NULL COMMENT 'Feature description',
  `is_school` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is this a school-specific feature',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Is feature active globally',
  `has_variations` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Feature supports product variations',
  `has_size` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Feature supports size options',
  `has_colour` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Feature supports colour options',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Available features';

-- --------------------------------------------------------

--
-- Table structure for table `erp_individual_products`
--

CREATE TABLE `erp_individual_products` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `isbn` varchar(100) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `min_quantity` int(11) NOT NULL DEFAULT 1,
  `days_to_exchange` int(11) DEFAULT NULL,
  `product_origin` varchar(255) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `packaging_length` decimal(10,2) DEFAULT NULL,
  `packaging_width` decimal(10,2) DEFAULT NULL,
  `packaging_height` decimal(10,2) DEFAULT NULL,
  `packaging_weight` decimal(10,2) DEFAULT NULL,
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `hsn` varchar(50) DEFAULT NULL,
  `mrp` decimal(10,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `variation_type` enum('none','size','color','size_color') NOT NULL DEFAULT 'none',
  `size_chart_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_individual_product_categories`
--

CREATE TABLE `erp_individual_product_categories` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_individual_product_category_mapping`
--

CREATE TABLE `erp_individual_product_category_mapping` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_individual_product_colors`
--

CREATE TABLE `erp_individual_product_colors` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color_code` varchar(7) DEFAULT NULL COMMENT 'Hex color code',
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_individual_product_color_mapping`
--

CREATE TABLE `erp_individual_product_color_mapping` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_individual_product_images`
--

CREATE TABLE `erp_individual_product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(500) NOT NULL,
  `image_order` int(11) NOT NULL DEFAULT 0,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_individual_product_size_color_prices`
--

CREATE TABLE `erp_individual_product_size_color_prices` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `mrp` decimal(10,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_materials`
--

CREATE TABLE `erp_materials` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Product Materials';

--
-- Dumping data for table `erp_materials`
--

INSERT INTO `erp_materials` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cotton', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(2, 'Cotton Jersey', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(3, 'Sinker', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(4, 'Polyester', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(5, 'Cotton Mixes or Cotton Poly', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(6, 'Single Jersey', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(7, 'Cotton Lycra', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(8, 'Blended', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(9, 'Viscose', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(10, 'Lycra', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(11, 'Rayon', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(12, 'Organic Cotton', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(13, 'Knitted', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(14, 'Fleece', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(15, 'Cotton Elastane', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(16, 'Looper', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(17, 'Interlock', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(18, 'Cotton', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(19, 'Cotton Looper', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(20, 'Quilted', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(21, 'Acrylic Blend', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(22, 'Embosis Hosiery', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(23, 'Poplin', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(24, 'Cotton Polyester', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(25, 'Denim', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(26, 'Poly', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(27, 'Cotton Modal', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(28, 'Linen', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(29, 'Cotton Lurex', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(30, 'Chambray', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(31, 'Terry', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(32, 'Melange', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(33, 'Cotton Polyester Lycra', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(34, 'Schiffili', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(35, 'Chiffon', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(36, 'Cotton Rayon', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(37, 'Poly Blends', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(38, 'Jacquard', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(39, 'Cotton Mixes or Cotton Poly', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(40, 'Acrylic', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(41, 'Nylon', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(42, 'Polyester', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(43, 'Silicon', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(44, 'test 2', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(46, 'Steel insulator', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(47, 'Textile', NULL, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `erp_notebooks`
--

CREATE TABLE `erp_notebooks` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `brand_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_publishers table (used as brand)',
  `product_name` varchar(255) NOT NULL COMMENT 'Product Name/Display Name',
  `isbn` varchar(100) DEFAULT NULL COMMENT 'ISBN/Bar Code No./SKU',
  `size` varchar(100) DEFAULT NULL COMMENT 'Size',
  `binding_type` enum('center_binding','perfect_binding','spiral_binding') DEFAULT NULL COMMENT 'Binding Type',
  `no_of_pages` int(11) DEFAULT NULL COMMENT 'No. Of Pages',
  `min_quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Min Quantity',
  `days_to_exchange` int(11) DEFAULT NULL COMMENT 'Days To Exchange',
  `pointers` text DEFAULT NULL COMMENT 'Pointers / Highlights (CKEditor)',
  `product_description` text NOT NULL COMMENT 'Product Description (CKEditor)',
  `packaging_length` decimal(10,2) DEFAULT NULL COMMENT 'Length (in cm)',
  `packaging_width` decimal(10,2) DEFAULT NULL COMMENT 'Width (in cm)',
  `packaging_height` decimal(10,2) DEFAULT NULL COMMENT 'Height (in cm)',
  `packaging_weight` decimal(10,2) DEFAULT NULL COMMENT 'Weight (in gm)',
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'GST (%)',
  `hsn` varchar(50) DEFAULT NULL COMMENT 'HSN',
  `product_code` varchar(100) DEFAULT NULL COMMENT 'Product Code (For control of school set)',
  `sku` varchar(100) DEFAULT NULL COMMENT 'SKU /Product Code',
  `mrp` decimal(10,2) NOT NULL COMMENT 'MRP',
  `selling_price` decimal(10,2) NOT NULL COMMENT 'Selling Price',
  `meta_title` varchar(255) DEFAULT NULL COMMENT 'Meta Title',
  `meta_keywords` text DEFAULT NULL COMMENT 'Meta Keywords',
  `meta_description` text DEFAULT NULL COMMENT 'Meta Description',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_individual` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Individual Product',
  `is_set` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Set Product',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notebooks';

-- --------------------------------------------------------

--
-- Table structure for table `erp_notebook_images`
--

CREATE TABLE `erp_notebook_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `notebook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_notebooks table',
  `image_path` varchar(500) NOT NULL COMMENT 'Image path',
  `image_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Image order for display',
  `is_main` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Main Image',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notebook Images';

-- --------------------------------------------------------

--
-- Table structure for table `erp_notebook_type_mapping`
--

CREATE TABLE `erp_notebook_type_mapping` (
  `id` int(11) UNSIGNED NOT NULL,
  `notebook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_notebooks table',
  `type_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_types table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Notebook Type Mapping';

-- --------------------------------------------------------

--
-- Table structure for table `erp_orders`
--

CREATE TABLE `erp_orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_vendors table',
  `school_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_schools table',
  `customer_name` varchar(255) DEFAULT NULL COMMENT 'Customer name',
  `customer_email` varchar(255) DEFAULT NULL COMMENT 'Customer email',
  `customer_address` text DEFAULT NULL COMMENT 'Customer address',
  `order_number` varchar(50) NOT NULL COMMENT 'Unique order number/invoice number',
  `order_date` datetime NOT NULL COMMENT 'Date when order was placed',
  `delivery_date` datetime DEFAULT NULL COMMENT 'Expected or actual delivery date',
  `payment_status` enum('pending','failed','success') NOT NULL DEFAULT 'pending' COMMENT 'Payment status: pending (yellow), failed (red), success (green)',
  `order_status` enum('pending','processing','delivered','cancelled') NOT NULL DEFAULT 'pending' COMMENT 'Order status',
  `payment_method` varchar(50) DEFAULT NULL COMMENT 'Payment method (cash, card, online, etc.)',
  `payment_date` datetime DEFAULT NULL COMMENT 'Date when payment was made',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Subtotal amount before tax',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Tax/GST amount',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Discount amount',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Total order amount',
  `delivery_address` text DEFAULT NULL COMMENT 'Delivery address',
  `delivery_city` varchar(100) DEFAULT NULL,
  `delivery_state` varchar(100) DEFAULT NULL,
  `delivery_pincode` varchar(20) DEFAULT NULL,
  `delivery_phone` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL COMMENT 'Order notes/comments',
  `cancelled_at` datetime DEFAULT NULL COMMENT 'Date when order was cancelled',
  `cancellation_reason` text DEFAULT NULL COMMENT 'Reason for cancellation',
  `delivered_at` datetime DEFAULT NULL COMMENT 'Date when order was delivered',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Main orders table';

-- --------------------------------------------------------

--
-- Table structure for table `erp_order_items`
--

CREATE TABLE `erp_order_items` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_orders table',
  `product_type` enum('textbook','notebook','stationery','bookset','package') NOT NULL COMMENT 'Type of product',
  `product_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'ID of the product (varies by product_type)',
  `bookset_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'If product_type is bookset or package',
  `package_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'If product_type is package',
  `product_name` varchar(255) NOT NULL COMMENT 'Product name at time of order',
  `display_name` varchar(255) DEFAULT NULL COMMENT 'Display name if different',
  `sku` varchar(100) DEFAULT NULL COMMENT 'Product SKU/ISBN',
  `quantity` int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Quantity ordered',
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Unit price at time of order',
  `discounted_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Discounted price per unit',
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Tax/GST percentage',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Tax amount for this item',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Discount amount for this item',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Subtotal before tax',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Total amount for this item',
  `weight` decimal(10,2) DEFAULT NULL COMMENT 'Product weight',
  `notes` text DEFAULT NULL COMMENT 'Item-specific notes',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Order items table';

-- --------------------------------------------------------

--
-- Table structure for table `erp_order_status_history`
--

CREATE TABLE `erp_order_status_history` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_orders table',
  `status_type` enum('payment_status','order_status') NOT NULL COMMENT 'Type of status change',
  `old_status` varchar(50) DEFAULT NULL COMMENT 'Previous status',
  `new_status` varchar(50) NOT NULL COMMENT 'New status',
  `changed_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'User/vendor who made the change',
  `notes` text DEFAULT NULL COMMENT 'Notes about the status change',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Order status change history';

-- --------------------------------------------------------

--
-- Table structure for table `erp_product_variation_types`
--

CREATE TABLE `erp_product_variation_types` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variation_type_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_schools`
--

CREATE TABLE `erp_schools` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Vendor who created this school',
  `school_name` varchar(255) NOT NULL COMMENT 'School Name',
  `slug` varchar(255) NOT NULL,
  `school_board` varchar(100) NOT NULL COMMENT 'School Board (CBSE, ICSE, State Board, etc.)',
  `total_strength` int(11) DEFAULT NULL COMMENT 'Total School Strength',
  `school_description` text DEFAULT NULL COMMENT 'School Description',
  `affiliation_no` varchar(100) DEFAULT NULL COMMENT 'Affiliation Number',
  `address` text NOT NULL COMMENT 'Address',
  `country_id` int(11) NOT NULL DEFAULT 101 COMMENT 'Country ID (default: 101 = India)',
  `state_id` mediumint(9) NOT NULL COMMENT 'State ID from states table',
  `city_id` mediumint(9) NOT NULL COMMENT 'City ID from cities table',
  `pincode` varchar(10) NOT NULL COMMENT 'Pincode',
  `admin_name` varchar(255) NOT NULL COMMENT 'Admin Name',
  `admin_phone` varchar(20) NOT NULL COMMENT 'Admin Phone',
  `admin_email` varchar(255) NOT NULL COMMENT 'Admin Email',
  `admin_password` varchar(255) NOT NULL COMMENT 'Admin Password (SHA1 hash)',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `is_branch` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is this school a branch? (1=yes, 0=no)',
  `parent_school_id` int(11) DEFAULT NULL COMMENT 'Parent school ID if this is a branch',
  `is_block_payment` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Payment Block Status (0=Active, 1=Blocked)',
  `is_national_block` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'National Delivery Block Status (0=Active, 1=Blocked)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Schools managed by vendors';

--
-- Dumping data for table `erp_schools`
--

INSERT INTO `erp_schools` (`id`, `vendor_id`, `school_name`, `slug`, `school_board`, `total_strength`, `school_description`, `affiliation_no`, `address`, `country_id`, `state_id`, `city_id`, `pincode`, `admin_name`, `admin_phone`, `admin_email`, `admin_password`, `status`, `is_branch`, `parent_school_id`, `is_block_payment`, `is_national_block`, `created_at`, `updated_at`) VALUES
(1, 38, 'Thakur Vidya Mandir High School & Junior College', 'thakur-vidya-mandir-high-school-junior-college', '5', NULL, 'Kandivali', '', '', 101, 1568, 17423, '', 'Thakur Vidya Mandir High School & Junior College', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-07 17:23:03', '2026-01-21 16:37:54'),
(2, 38, 'Seth Vidya Mandir High School', 'seth-vidya-mandir-high-school', '1,5', NULL, 'Seth Vidya Mandir High School - Vasai', '', '', 101, 1568, 17423, '', 'Seth Vidya Mandir High School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-20 17:19:19', '2026-01-21 16:37:54'),
(3, 38, 'Expert International School', 'expert-international-school', '5', NULL, 'Expert International School - Virar', '', '', 101, 1568, 17423, '', 'Expert International School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-26 17:28:57', '2026-01-21 16:37:54'),
(4, 38, 'Twinkle Star English High School', 'twinkle-star-english-high-school', '5', NULL, 'Twinkle Star English High School - Palghar', '', '', 101, 1568, 17423, '', 'Twinkle Star English High School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-29 17:40:17', '2026-01-21 16:37:54'),
(5, 38, 'Radcliffe', 'radcliffe', '1', NULL, '', '', '', 101, 1568, 17423, '', 'Radcliffe', '', 'radcliffe@mail.com', 'f4fc8a416f8be148db91d57412cc34a0', 'active', 0, NULL, 0, 0, '2023-06-02 19:09:09', '2026-01-21 16:37:54'),
(6, 38, 'CP Goenka International School', 'cp-goenka-international-school', '2', NULL, '', '', '', 101, 1568, 17423, '', 'CP Goenka International School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-27 18:48:15', '2026-01-21 16:37:54'),
(7, 38, 'Spring Buds International (SVIS)', 'spring-buds-international-svis', '7', NULL, '', '', '', 101, 1568, 17423, '', 'Spring Buds International (SVIS)', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-05-10 17:07:29', '2026-01-21 16:37:54'),
(8, 38, 'Ajmera Global School', 'ajmera-global-school', '4', NULL, '', '', '', 101, 1568, 17423, '', 'Ajmera Global School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-08 10:18:11', '2026-01-21 16:37:54'),
(9, 38, 'Swami Vivekanand International School & Junior College', 'swami-vivekanand-international-school-junior-college', '1', NULL, '', '', '', 101, 1568, 17423, '', 'Swami Vivekanand International School & Junior College', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-20 18:15:24', '2026-01-21 16:37:54'),
(10, 38, 'Guru Rajendra Jain International School', 'guru-rajendra-jain-international-school', '1', NULL, '', '', '', 101, 1568, 17423, '', 'Guru Rajendra Jain International School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-20 18:17:04', '2026-01-21 16:37:54'),
(11, 38, 'Children\'s House Pre School', 'children-s-house-pre-school', '7', NULL, '', '', '', 101, 1568, 17423, '', 'Children\'s House Pre School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-25 12:59:25', '2026-01-21 16:37:54'),
(12, 38, 'Children\'s House School', 'children-s-house-school', '1', NULL, '', '', '', 101, 1568, 17423, '', 'Children\'s House School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-25 12:58:54', '2026-01-21 16:37:54'),
(13, 38, 'C P Goenka\'s Spring Buds International PreSchool', 'c-p-goenka-s-spring-buds-international-preschool', '7', NULL, '', '', '', 101, 1568, 17423, '', 'C P Goenka\'s Spring Buds International PreSchool', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-27 18:49:32', '2026-01-21 16:37:54'),
(14, 38, 'Podar World School', 'podar-world-school', '1', NULL, '', '', '', 101, 1568, 17423, '', 'Podar World School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-27 18:58:50', '2026-01-21 16:37:54'),
(15, 38, 'Ajmera School', 'ajmera-school', '2', NULL, '', '', '', 101, 1568, 17423, '', 'Ajmera School', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-04-27 19:01:56', '2026-01-21 16:37:54'),
(16, 38, 'Jolly kids Pre-school', 'jolly-kids-pre-school', '1', NULL, '', '', '', 101, 1568, 17423, '', 'Jolly kids Pre-school', '', '', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, '2023-05-19 17:20:47', '2026-01-21 16:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `erp_school_boards`
--

CREATE TABLE `erp_school_boards` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Vendor who created this board',
  `board_name` varchar(100) NOT NULL COMMENT 'Board Name (e.g., CBSE, ICSE, etc.)',
  `description` text DEFAULT NULL COMMENT 'Board Description',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='School boards managed by vendors';

--
-- Dumping data for table `erp_school_boards`
--

INSERT INTO `erp_school_boards` (`id`, `vendor_id`, `board_name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 38, 'CBSE', '', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(2, 38, 'ICSE', '', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(3, 38, 'IGCSE', '', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(4, 38, 'IB', '', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(5, 38, 'STATE BOARD', '', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(6, 38, 'CBSE Curriculum', '', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(7, 38, 'Pre School', '', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53');

-- --------------------------------------------------------

--
-- Table structure for table `erp_school_boards_mapping`
--

CREATE TABLE `erp_school_boards_mapping` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL COMMENT 'School ID',
  `board_id` int(11) NOT NULL COMMENT 'Board ID from erp_school_boards',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Mapping table for schools and boards (many-to-many)';

--
-- Dumping data for table `erp_school_boards_mapping`
--

INSERT INTO `erp_school_boards_mapping` (`id`, `school_id`, `board_id`, `created_at`) VALUES
(1, 1, 5, '2026-01-21 16:37:54'),
(2, 2, 1, '2026-01-21 16:37:54'),
(3, 2, 5, '2026-01-21 16:37:54'),
(4, 3, 5, '2026-01-21 16:37:54'),
(5, 4, 5, '2026-01-21 16:37:54'),
(6, 5, 1, '2026-01-21 16:37:54'),
(7, 6, 2, '2026-01-21 16:37:54'),
(8, 7, 7, '2026-01-21 16:37:54'),
(9, 8, 4, '2026-01-21 16:37:54'),
(10, 9, 1, '2026-01-21 16:37:54'),
(11, 10, 1, '2026-01-21 16:37:54'),
(12, 11, 7, '2026-01-21 16:37:54'),
(13, 12, 1, '2026-01-21 16:37:54'),
(14, 13, 7, '2026-01-21 16:37:54'),
(15, 14, 1, '2026-01-21 16:37:54'),
(16, 15, 2, '2026-01-21 16:37:54'),
(17, 16, 1, '2026-01-21 16:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `erp_school_branches`
--

CREATE TABLE `erp_school_branches` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL COMMENT 'School ID from erp_schools table',
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Vendor who created this branch',
  `branch_name` varchar(255) NOT NULL COMMENT 'Branch Name',
  `slug` varchar(255) DEFAULT NULL,
  `address` text NOT NULL COMMENT 'Branch Address',
  `country_id` int(11) NOT NULL DEFAULT 101 COMMENT 'Country ID (default: 101 = India)',
  `state_id` mediumint(9) NOT NULL COMMENT 'State ID from states table',
  `city_id` mediumint(9) NOT NULL COMMENT 'City ID from cities table',
  `pincode` varchar(10) NOT NULL COMMENT 'Pincode',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='School branches managed by vendors';

--
-- Dumping data for table `erp_school_branches`
--

INSERT INTO `erp_school_branches` (`id`, `school_id`, `vendor_id`, `branch_name`, `slug`, `address`, `country_id`, `state_id`, `city_id`, `pincode`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 38, 'Khargahr', 'khargahr', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(2, 5, 38, 'Dighi', 'dighi', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(3, 5, 38, 'Hyderabad', 'hyderabad', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(4, 5, 38, 'Bengaluru', 'bengaluru', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(5, 5, 38, 'Kochi', 'kochi', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(6, 5, 38, 'Bhathinda', 'bhathinda', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(7, 5, 38, 'Patiala', 'patiala', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(8, 5, 38, 'Bhopal', 'bhopal', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(9, 5, 38, 'Jaipur', 'jaipur', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(10, 7, 38, 'Borivali', 'borivali', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(11, 7, 38, 'Kandivali', 'kandivali', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(12, 5, 38, 'Thane', 'thane', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(13, 5, 38, 'Taloja', 'taloja', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(14, 5, 38, 'Ulwe', 'ulwe', '', 101, 1568, 17423, '', 'inactive', '2026-01-21 16:37:54', '2026-01-21 16:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `erp_school_images`
--

CREATE TABLE `erp_school_images` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL COMMENT 'School ID',
  `image_path` varchar(255) NOT NULL COMMENT 'Image file path',
  `image_name` varchar(255) DEFAULT NULL COMMENT 'Original image name',
  `display_order` int(11) DEFAULT 0 COMMENT 'Display order for sorting',
  `is_primary` tinyint(1) DEFAULT 0 COMMENT 'Is primary image (1=yes, 0=no)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='School images';

--
-- Dumping data for table `erp_school_images`
--

INSERT INTO `erp_school_images` (`id`, `school_id`, `image_path`, `image_name`, `display_order`, `is_primary`, `created_at`) VALUES
(1, 1, 'uploads/school/img_9c1ANtuS47.jpg', 'img_9c1ANtuS47.jpg', 0, 1, '2026-01-21 16:37:54'),
(2, 2, 'uploads/school/img_ijqUGbHAtT.jpg', 'img_ijqUGbHAtT.jpg', 0, 1, '2026-01-21 16:37:54'),
(3, 3, 'uploads/school/img_dMf8bDeIE6.jpg', 'img_dMf8bDeIE6.jpg', 0, 1, '2026-01-21 16:37:54'),
(4, 4, 'uploads/school/img_AisbvMX1NH.jpg', 'img_AisbvMX1NH.jpg', 0, 1, '2026-01-21 16:37:54'),
(5, 5, 'uploads/school/img_LEnRc7GHsp.jpg', 'img_LEnRc7GHsp.jpg', 0, 1, '2026-01-21 16:37:54'),
(6, 6, 'uploads/school/img_gkL7dJSvOW.jpg', 'img_gkL7dJSvOW.jpg', 0, 1, '2026-01-21 16:37:54'),
(7, 7, 'uploads/school/img_bzoU8D3s5H.jpg', 'img_bzoU8D3s5H.jpg', 0, 1, '2026-01-21 16:37:54'),
(8, 8, 'uploads/school/img_6ItadfLj8V.jpg', 'img_6ItadfLj8V.jpg', 0, 1, '2026-01-21 16:37:54'),
(9, 9, 'uploads/school/img_KvsEVTztmJ.jpg', 'img_KvsEVTztmJ.jpg', 0, 1, '2026-01-21 16:37:54'),
(10, 10, 'uploads/school/img_z9xoiIGl27.jpg', 'img_z9xoiIGl27.jpg', 0, 1, '2026-01-21 16:37:54'),
(11, 11, 'uploads/school/img_YHZK3JyRMG.jpg', 'img_YHZK3JyRMG.jpg', 0, 1, '2026-01-21 16:37:54'),
(12, 12, 'uploads/school/img_lLVtfBZiRK.jpg', 'img_lLVtfBZiRK.jpg', 0, 1, '2026-01-21 16:37:54'),
(13, 13, 'uploads/school/img_Bv4i1HpG5J.jpg', 'img_Bv4i1HpG5J.jpg', 0, 1, '2026-01-21 16:37:54'),
(14, 14, 'uploads/school/img_2K9wcWeY31.jpg', 'img_2K9wcWeY31.jpg', 0, 1, '2026-01-21 16:37:54'),
(15, 15, 'uploads/school/img_6ItadfLj8V.jpg', 'img_6ItadfLj8V.jpg', 0, 1, '2026-01-21 16:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `erp_sizes`
--

CREATE TABLE `erp_sizes` (
  `id` int(11) UNSIGNED NOT NULL,
  `size_chart_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_size_charts table',
  `name` varchar(100) NOT NULL COMMENT 'Size Name (e.g., S, M, L, XL, 28, 30, etc.)',
  `display_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Order for display',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sizes within Size Charts';

--
-- Dumping data for table `erp_sizes`
--

INSERT INTO `erp_sizes` (`id`, `size_chart_id`, `name`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '26', 1, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(2, 1, '28', 2, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(3, 1, '30', 3, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(4, 1, '32', 4, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(5, 1, '34', 5, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(6, 1, '36', 6, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(7, 1, '2', 7, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(8, 1, '3', 8, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(9, 1, '4', 9, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(10, 1, '5', 10, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(11, 1, '6', 11, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(12, 1, '7', 12, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(13, 1, '8', 13, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(14, 1, '9', 14, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(15, 1, '10', 15, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(16, 1, '11', 16, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(17, 1, '12', 17, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(18, 1, '13', 18, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(19, 1, '14', 19, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(20, 1, '16', 20, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(21, 1, '18', 21, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(22, 1, '20', 22, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(23, 1, '2', 23, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(24, 1, '3', 24, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(25, 1, '4', 25, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(26, 1, '5', 26, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(27, 1, '6', 27, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(28, 1, '7', 28, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(29, 1, '8', 29, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(30, 1, '9', 30, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(31, 1, '10', 31, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(32, 1, '11', 32, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(33, 1, '12', 33, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(34, 1, '13', 34, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(35, 1, '14', 35, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(36, 1, '16', 36, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(37, 1, '18', 37, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(38, 1, '20', 38, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(39, 1, '22', 39, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(40, 1, '24', 40, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(41, 1, '25', 41, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(42, 1, '34', 42, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(43, 1, '40', 43, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(44, 1, '42', 44, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(45, 1, 'S', 45, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(46, 1, 'M', 46, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(47, 1, 'L', 47, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(48, 1, 'OS', 48, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(49, 1, 'OM', 49, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(50, 1, 'XL', 50, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(51, 1, 'XXL', 51, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(52, 1, '3XL', 52, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(53, 1, '20', 53, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(54, 1, '22', 54, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(55, 1, '24', 55, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(56, 1, '26', 56, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(57, 1, '28', 57, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(58, 1, '30', 58, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(59, 1, '32', 59, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(60, 1, '34', 60, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(61, 1, '36', 61, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(62, 1, '38', 62, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(63, 1, '40', 63, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(64, 1, '18', 64, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(65, 1, '20', 65, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(66, 1, '22', 66, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(67, 1, '24', 67, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(68, 1, '26', 68, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(69, 1, '28', 69, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(70, 1, '30', 70, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(71, 1, '32', 71, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(72, 1, '34', 72, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(73, 1, '36', 73, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(74, 1, '38', 74, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(75, 1, '40', 75, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(76, 1, '42', 76, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(77, 1, '44', 77, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(78, 1, '46', 78, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(79, 1, '18', 79, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(80, 1, '20', 80, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(81, 1, '22', 81, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(82, 1, '24', 82, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(83, 1, '26', 83, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(84, 1, '28', 84, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(85, 1, '30', 85, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(86, 1, '32', 86, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(87, 1, '34', 87, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(88, 1, '36', 88, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(89, 1, '38', 89, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(90, 1, '40', 90, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(91, 1, '42', 91, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(92, 1, '44', 92, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(93, 1, '46', 93, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(94, 1, '18', 94, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(95, 1, '20', 95, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(96, 1, '22', 96, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(97, 1, '24', 97, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(98, 1, '26', 98, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(99, 1, '28', 99, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(100, 1, '30', 100, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(101, 1, '32', 101, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(102, 1, '34', 102, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(103, 1, '36', 103, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(104, 1, '38', 104, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(105, 1, '40', 105, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(106, 1, '42', 106, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(107, 1, '44', 107, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(108, 1, '46', 108, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(109, 1, '18', 109, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(110, 1, '20', 110, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(111, 1, '22', 111, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(112, 1, '24', 112, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(113, 1, '26', 113, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(114, 1, '28', 114, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(115, 1, '30', 115, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(116, 1, '32', 116, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(117, 1, '34', 117, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(118, 1, '36', 118, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(119, 1, '38', 119, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(120, 1, '40', 120, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(121, 1, '42', 121, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(122, 1, '20', 122, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(123, 1, '22', 123, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(124, 1, '24', 124, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(125, 1, '26', 125, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(126, 1, '28', 126, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(127, 1, '30', 127, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(128, 1, '32', 128, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(129, 1, '34', 129, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(130, 1, '36', 130, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(131, 1, '38', 131, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(132, 1, '40', 132, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(133, 1, '42', 133, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(134, 1, '10', 134, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(135, 1, '11', 135, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(136, 1, '12', 136, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(137, 1, '13', 137, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(138, 1, '14', 138, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(139, 1, '15', 139, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(140, 1, '16', 140, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(141, 1, '17', 141, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(142, 1, '18', 142, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(143, 1, '2', 143, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(144, 1, '3', 144, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(145, 1, '4', 145, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(146, 1, '5', 146, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(147, 1, '6', 147, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(148, 1, 'Junior', 148, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(149, 1, 'Free', 149, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(150, 1, '9', 150, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(151, 1, '10', 151, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(152, 1, '11', 152, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(153, 1, '12', 153, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(154, 1, '13', 154, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(155, 1, '14', 155, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(156, 1, '15', 156, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(157, 1, '16', 157, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(158, 1, '17', 158, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(159, 1, '18', 159, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(160, 1, '24', 160, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(161, 1, '26', 161, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(162, 1, '28', 162, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(163, 1, '30', 163, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(164, 1, '32', 164, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(165, 1, '34', 165, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(166, 1, '36', 166, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(167, 1, '38', 167, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(168, 1, '40', 168, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(169, 1, '9', 169, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(170, 1, '10', 170, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(171, 1, '11', 171, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(172, 1, '12', 172, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(173, 1, '13', 173, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(174, 1, '14', 174, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(175, 1, '15', 175, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(176, 1, '16', 176, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(177, 1, '17', 177, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(178, 1, '18', 178, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(179, 1, '10x22', 179, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(180, 1, '13x23', 180, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(181, 1, '13x26', 181, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(182, 1, '16x18', 182, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(183, 1, '26x13', 183, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(184, 1, '42', 184, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(185, 1, '16x32', 185, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(186, 1, '17x28', 186, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(187, 1, '13x30', 187, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(188, 1, '17x18', 188, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(189, 1, '18x28', 189, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(190, 1, '18x32', 190, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(191, 1, '16x20', 191, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(192, 1, '18x30', 192, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(193, 1, '44', 193, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(194, 1, '21', 194, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(195, 1, '22', 195, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(196, 1, '44', 196, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(197, 1, '46', 197, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(198, 1, '46', 198, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(199, 1, '42x36', 199, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(200, 1, '34x36', 200, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(201, 1, '38x32', 201, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(202, 1, '38x38', 202, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(203, 1, '40x32', 203, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(204, 1, '40x38', 204, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(205, 1, '40x40', 205, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(206, 1, '40x42', 206, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(207, 1, '19', 207, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(208, 1, '20', 208, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(209, 1, '42', 209, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(210, 1, '44', 210, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(211, 1, '46', 211, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(212, 1, '19', 212, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(213, 1, '20', 213, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(214, 1, '9', 214, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(215, 1, '22', 215, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(216, 1, '24', 216, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(217, 1, '26', 217, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(218, 1, '28', 218, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(219, 1, '30', 219, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(220, 1, '32', 220, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(221, 1, '34', 221, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(222, 1, '36', 222, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(223, 1, '38', 223, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(224, 1, '40', 224, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(225, 1, '22', 225, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(226, 1, '27', 226, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(227, 1, '33', 227, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(228, 1, '48', 228, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(229, 1, '22', 229, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(230, 1, '24', 230, 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `erp_size_charts`
--

CREATE TABLE `erp_size_charts` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL COMMENT 'Size Chart Name',
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Size Charts for Uniforms';

--
-- Dumping data for table `erp_size_charts`
--

INSERT INTO `erp_size_charts` (`id`, `vendor_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 38, 'Default Size Chart', 'Default size chart for migrated sizes', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(2, 38, 'Shirt Half Sleeve', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(3, 38, 'Shirt Full Sleeve', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(4, 38, 'Half Shirt Podar', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(5, 38, 'Skirt Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(6, 38, 'Bloomer Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(7, 38, 'Trackpant Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(8, 38, 'T-Shirt Half Sleeve Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(9, 38, 'T-Shirt Full Sleeve Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(10, 38, 'T-Shirt Collar Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(11, 38, 'Pinefore Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(12, 38, 'Jacket Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(13, 38, 'Half Pant Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(14, 38, 'Socks Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(15, 38, 'Half Pant With Elastic Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(16, 38, 'Full Pant Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(17, 38, 'Half Track Shorts Redcliffe Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54'),
(18, 38, 'Dungri Size Chart', 'Migrated from size_chart category_id: 22', 'active', '2026-01-21 16:37:54', '2026-01-21 16:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `erp_stationery`
--

CREATE TABLE `erp_stationery` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `category_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_stationery_categories table',
  `brand_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Foreign key to erp_stationery_brands table',
  `colour_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Foreign key to erp_stationery_colours table',
  `product_name` varchar(255) NOT NULL COMMENT 'Product Name/Display Name',
  `isbn` varchar(100) DEFAULT NULL COMMENT 'ISBN/Bar Code No./SKU',
  `sku` varchar(100) DEFAULT NULL COMMENT 'SKU /Product Code',
  `product_code` varchar(100) DEFAULT NULL COMMENT 'Product Code (For control of school set)',
  `min_quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Min Quantity',
  `days_to_exchange` int(11) DEFAULT NULL COMMENT 'Days To Exchange',
  `pointers` text DEFAULT NULL COMMENT 'Pointers / Highlights (CKEditor)',
  `product_description` text NOT NULL COMMENT 'Product Description (CKEditor)',
  `packaging_length` decimal(10,2) DEFAULT NULL COMMENT 'Length (in cm)',
  `packaging_width` decimal(10,2) DEFAULT NULL COMMENT 'Width (in cm)',
  `packaging_height` decimal(10,2) DEFAULT NULL COMMENT 'Height (in cm)',
  `packaging_weight` decimal(10,2) DEFAULT NULL COMMENT 'Weight (in gm)',
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'GST (%)',
  `gst_type` enum('igst','cgst_sgst') DEFAULT NULL COMMENT 'Select GST',
  `hsn` varchar(50) DEFAULT NULL COMMENT 'HSN',
  `mrp` decimal(10,2) NOT NULL COMMENT 'MRP',
  `selling_price` decimal(10,2) NOT NULL COMMENT 'Selling Price',
  `meta_title` varchar(255) DEFAULT NULL COMMENT 'Meta Title',
  `meta_keywords` text DEFAULT NULL COMMENT 'Meta Keywords',
  `meta_description` text DEFAULT NULL COMMENT 'Meta Description',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_individual` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Individual Product',
  `is_set` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Set Product',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery Products';

-- --------------------------------------------------------

--
-- Table structure for table `erp_stationery_brands`
--

CREATE TABLE `erp_stationery_brands` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery Brands';

-- --------------------------------------------------------

--
-- Table structure for table `erp_stationery_brand_mapping`
--

CREATE TABLE `erp_stationery_brand_mapping` (
  `id` int(11) UNSIGNED NOT NULL,
  `stationery_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_stationery table',
  `brand_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_stationery_brands table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery-Brand Mapping';

-- --------------------------------------------------------

--
-- Table structure for table `erp_stationery_categories`
--

CREATE TABLE `erp_stationery_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery Categories';

-- --------------------------------------------------------

--
-- Table structure for table `erp_stationery_colours`
--

CREATE TABLE `erp_stationery_colours` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery Colours';

-- --------------------------------------------------------

--
-- Table structure for table `erp_stationery_colour_mapping`
--

CREATE TABLE `erp_stationery_colour_mapping` (
  `id` int(11) UNSIGNED NOT NULL,
  `stationery_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_stationery table',
  `colour_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_stationery_colours table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery-Colour Mapping';

-- --------------------------------------------------------

--
-- Table structure for table `erp_stationery_images`
--

CREATE TABLE `erp_stationery_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `stationery_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_stationery table',
  `image_path` varchar(500) NOT NULL,
  `image_order` int(11) NOT NULL DEFAULT 0,
  `is_main` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Main Image',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stationery Images';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbooks`
--

CREATE TABLE `erp_textbooks` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `publisher_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_publishers table',
  `board_id` int(11) NOT NULL COMMENT 'Foreign key to erp_school_boards table',
  `grade_age_type` enum('grade','age') DEFAULT NULL COMMENT 'Grade or Age selection type',
  `product_name` varchar(255) NOT NULL COMMENT 'Product Name/Display Name',
  `isbn` varchar(100) NOT NULL COMMENT 'ISBN/Bar Code No./SKU',
  `min_quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Min Quantity',
  `days_to_exchange` int(11) DEFAULT NULL COMMENT 'Days To Exchange',
  `pointers` text DEFAULT NULL COMMENT 'Pointers / Highlights (CKEditor)',
  `product_description` text NOT NULL COMMENT 'Product Description (CKEditor)',
  `packaging_length` decimal(10,2) DEFAULT NULL COMMENT 'Length (in cm)',
  `packaging_width` decimal(10,2) DEFAULT NULL COMMENT 'Width (in cm)',
  `packaging_height` decimal(10,2) DEFAULT NULL COMMENT 'Height (in cm)',
  `packaging_weight` decimal(10,2) DEFAULT NULL COMMENT 'Weight (in gm)',
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'GST (%)',
  `gst_type` enum('igst','cgst_sgst') DEFAULT NULL COMMENT 'Select GST',
  `hsn` varchar(50) DEFAULT NULL COMMENT 'HSN',
  `product_code` varchar(100) DEFAULT NULL COMMENT 'Product Code (For control of school set)',
  `sku` varchar(100) DEFAULT NULL COMMENT 'SKU /Product Code',
  `mrp` decimal(10,2) NOT NULL COMMENT 'MRP',
  `selling_price` decimal(10,2) NOT NULL COMMENT 'Selling Price',
  `meta_title` varchar(255) DEFAULT NULL COMMENT 'Meta Title',
  `meta_keywords` text DEFAULT NULL COMMENT 'Meta Keywords',
  `meta_description` text DEFAULT NULL COMMENT 'Meta Description',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_individual` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Individual Product',
  `is_set` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Set Product',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbooks';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbook_ages`
--

CREATE TABLE `erp_textbook_ages` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Ages';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbook_age_mapping`
--

CREATE TABLE `erp_textbook_age_mapping` (
  `id` int(11) UNSIGNED NOT NULL,
  `textbook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbooks table',
  `age_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_ages table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook-Age Mapping';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbook_grades`
--

CREATE TABLE `erp_textbook_grades` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Grades';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbook_grade_mapping`
--

CREATE TABLE `erp_textbook_grade_mapping` (
  `id` int(11) UNSIGNED NOT NULL,
  `textbook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbooks table',
  `grade_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_grades table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook-Grade Mapping';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbook_images`
--

CREATE TABLE `erp_textbook_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `textbook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbooks table',
  `image_path` varchar(500) NOT NULL,
  `image_order` int(11) NOT NULL DEFAULT 0,
  `is_main` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Main Image',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Images';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbook_publishers`
--

CREATE TABLE `erp_textbook_publishers` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Publishers';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbook_subjects`
--

CREATE TABLE `erp_textbook_subjects` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Subjects';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbook_subject_mapping`
--

CREATE TABLE `erp_textbook_subject_mapping` (
  `id` int(11) UNSIGNED NOT NULL,
  `textbook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbooks table',
  `subject_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_subjects table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook-Subject Mapping';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbook_types`
--

CREATE TABLE `erp_textbook_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook Types';

-- --------------------------------------------------------

--
-- Table structure for table `erp_textbook_type_mapping`
--

CREATE TABLE `erp_textbook_type_mapping` (
  `id` int(11) UNSIGNED NOT NULL,
  `textbook_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbooks table',
  `type_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_textbook_types table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Textbook-Type Mapping';

-- --------------------------------------------------------

--
-- Table structure for table `erp_uniforms`
--

CREATE TABLE `erp_uniforms` (
  `id` int(11) UNSIGNED NOT NULL,
  `vendor_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_clients table',
  `uniform_type_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_uniform_types table',
  `school_id` int(11) NOT NULL COMMENT 'Foreign key to erp_schools table',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Foreign key to erp_school_branches table',
  `board_id` int(11) DEFAULT NULL COMMENT 'Foreign key to erp_school_boards table',
  `gender` enum('male','female','unisex') NOT NULL,
  `color` varchar(100) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL COMMENT 'Product Name/Display Name',
  `slug` varchar(255) DEFAULT NULL,
  `isbn` varchar(100) DEFAULT NULL COMMENT 'ISBN / Bar Code No./SKU No.',
  `min_quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Min Quantity',
  `days_to_exchange` int(11) DEFAULT NULL COMMENT 'Days To Exchange',
  `material_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_materials table',
  `product_origin` varchar(255) DEFAULT NULL,
  `product_description` text DEFAULT NULL COMMENT 'Product Description (CKEditor)',
  `manufacturer_details` text DEFAULT NULL COMMENT 'Manufacturer Details (CKEditor)',
  `packer_details` text DEFAULT NULL COMMENT 'Packer Details (CKEditor)',
  `customer_details` text DEFAULT NULL COMMENT 'Customer Details (CKEditor)',
  `price` decimal(10,2) DEFAULT NULL,
  `size_chart_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Size Chart (for future use - leave empty for now)',
  `size_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Size (for future use - leave empty for now)',
  `packaging_length` decimal(10,2) DEFAULT NULL COMMENT 'Length (in cm)',
  `packaging_width` decimal(10,2) DEFAULT NULL COMMENT 'Width (in cm)',
  `packaging_height` decimal(10,2) DEFAULT NULL COMMENT 'Height (in cm)',
  `packaging_weight` decimal(10,2) DEFAULT NULL COMMENT 'Weight (in gm)',
  `tax` decimal(10,2) DEFAULT NULL,
  `gst_percentage` decimal(5,2) NOT NULL DEFAULT 0.00 COMMENT 'GST (%)',
  `school_margin` decimal(10,2) DEFAULT NULL,
  `hsn` varchar(50) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_individual` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Individual Product',
  `is_set` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Set Product',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `school_commission_type` enum('fixed','percentage') DEFAULT NULL,
  `school_commission_value` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Uniforms Products';

--
-- Dumping data for table `erp_uniforms`
--

INSERT INTO `erp_uniforms` (`id`, `vendor_id`, `uniform_type_id`, `school_id`, `branch_id`, `board_id`, `gender`, `color`, `product_name`, `slug`, `isbn`, `min_quantity`, `days_to_exchange`, `material_id`, `product_origin`, `product_description`, `manufacturer_details`, `packer_details`, `customer_details`, `price`, `size_chart_id`, `size_id`, `packaging_length`, `packaging_width`, `packaging_height`, `packaging_weight`, `tax`, `gst_percentage`, `school_margin`, `hsn`, `meta_title`, `meta_keywords`, `meta_description`, `status`, `is_individual`, `is_set`, `created_at`, `updated_at`, `school_commission_type`, `school_commission_value`) VALUES
(1, 38, 2, 6, NULL, 1, 'unisex', NULL, 'RADCLIFFE YELLOW T-SHIRT', 'radcliffe-yellow-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\">Brand- Varitty</p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Age Range - Kid</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted </span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Half Sleeve </span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck - Collar Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit </span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Yellow-Radcliffe-RADCLIFFE YELLOW T-SHIRT', 'T-Shirt-Yellow-Radcliffe-RADCLIFFE YELLOW T-SHIRT', 'T-Shirt-Yellow-Radcliffe-RADCLIFFE YELLOW T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(2, 38, 2, 6, NULL, 1, 'unisex', NULL, '  RADCLIFF BLUE T- SHIRT', 'radcliff-blue-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Age Range - Kid</p>\n<p>Material- Knitted</p>\n<p>Sleeve - Half Sleeve</p>\n<p>Neck - Collar Neck</p>\n<p>Fit - Regular Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Blue-Radcliffe-  RADCLIFF BLUE T- SHIRT', 'T-Shirt-Blue-Radcliffe-  RADCLIFF BLUE T- SHIRT', 'T-Shirt-Blue-Radcliffe-  RADCLIFF BLUE T- SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(3, 38, 2, 6, NULL, 1, 'unisex', NULL, 'RADCLIFFE PINK  T- SHIRT', 'radcliffe-pink-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Age Range - Kid</p>\n<p>Material- Knitted</p>\n<p>Sleeve - Half Sleeve</p>\n<p>Neck - Collar Neck</p>\n<p>Fit - Regular Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Pink-Radcliffe-RADCLIFFE PINK  T- SHIRT', 'T-Shirt-Pink-Radcliffe-RADCLIFFE PINK  T- SHIRT', 'T-Shirt-Pink-Radcliffe-RADCLIFFE PINK  T- SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(4, 38, 2, 6, NULL, 1, 'unisex', NULL, 'RADCLIFFE GREEN T-SHIRT', 'radcliffe-green-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Age Range - Kid</p>\n<p>Material- Knitted</p>\n<p>Sleeve - Half Sleeve</p>\n<p>Neck - Collar Neck</p>\n<p>Fit - Regular Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Green-Radcliffe-RADCLIFFE GREEN T-SHIRT', 'T-Shirt-Green-Radcliffe-RADCLIFFE GREEN T-SHIRT', 'T-Shirt-Green-Radcliffe-RADCLIFFE GREEN T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(5, 38, 16, 6, NULL, 1, 'unisex', NULL, 'RADCLIFFE NAVY BLUE -TRACKPANT', 'radcliffe-navy-blue-trackpant', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Age Range - Kid</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Trackpant </span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Length - Full Length</span></p>\n<p style=\"text-align:start;\"></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Trackpant-Navy blue-Radcliffe-RADCLIFFE NAVY BLUE -TRACKPANT', 'Trackpant-Navy blue-Radcliffe-RADCLIFFE NAVY BLUE -TRACKPANT', 'Trackpant-Navy blue-Radcliffe-RADCLIFFE NAVY BLUE -TRACKPANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(6, 38, 10, 6, NULL, 1, 'unisex', NULL, 'RADCLIFFE NAVY BLUE ZIPPEER HOODIE', 'radcliffe-navy-blue-zippeer-hoodie', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Age Range - Kid</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- kintted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleevee- Full Sleeve</span></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Hoodie-Navy blue-Radcliffe-RADCLIFFE NAVY BLUE ZIPPEER HOODIE', 'Hoodie-Navy blue-Radcliffe-RADCLIFFE NAVY BLUE ZIPPEER HOODIE', 'Hoodie-Navy blue-Radcliffe-RADCLIFFE NAVY BLUE ZIPPEER HOODIE', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(7, 38, 1, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE YELLOW CHECK SHIRT', 'svm-white-yellow-check-shirt', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Class- Pre Primary</p>\n<p>Material - Knitted</p>\n<p>Neck- Collar Neck</p>\n<p>Sleeve- Half Sleeve&nbsp;</p>\n<p>Fit - Regluar Fit</p>\n<p></p>\n<p></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-Blue & Yellow-Seth Vidya Mandir High School-SVM WHITE YELLOW CHECK SHIRT', 'Shirt-Blue & Yellow-Seth Vidya Mandir High School-SVM WHITE YELLOW CHECK SHIRT', 'Shirt-Blue & Yellow-Seth Vidya Mandir High School-SVM WHITE YELLOW CHECK SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(8, 38, 1, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE BLUE SHIRT PRIMARY SECTION ', 'svm-white-blue-shirt-primary-section', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Class- Primary Section (1To 5)Std</p>\n<p>Material - Knitted</p>\n<p>Neck- Collar Neck</p>\n<p>Sleeve- Half Sleeve</p>\n<p>Fit - Regluar Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-Blue & Black-Seth Vidya Mandir High School-SVM WHITE BLUE SHIRT PRIMARY SECTION ', 'Shirt-Blue & Black-Seth Vidya Mandir High School-SVM WHITE BLUE SHIRT PRIMARY SECTION ', 'Shirt-Blue & Black-Seth Vidya Mandir High School-SVM WHITE BLUE SHIRT PRIMARY SECTION ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(9, 38, 18, 2, NULL, 5, 'unisex', NULL, 'SVM BLUE &YELLOW HALFPANT KG SECTION ', 'svm-blue-yellow-halfpant-kg-section', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Class- Pre- Primary Section</p>\n<p>Material - Knitted</p>\n<p>Type- Half pant</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Half pant-Blue & Yellow-Seth Vidya Mandir High School-SVM BLUE &YELLOW HALFPANT KG SECTION ', 'Half pant-Blue & Yellow-Seth Vidya Mandir High School-SVM BLUE &YELLOW HALFPANT KG SECTION ', 'Half pant-Blue & Yellow-Seth Vidya Mandir High School-SVM BLUE &YELLOW HALFPANT KG SECTION ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(10, 38, 18, 2, NULL, 5, 'unisex', NULL, 'SVM BLUE BLACK HALFPANT  PRIMARY SECTION', 'svm-blue-black-halfpant-primary-section', NULL, 1, 3, 1, '1', '<p>Brand -Varitty</p>\n<p>Class- Primary Section</p>\n<p>Material- Knitted</p>\n<p>Type- Half Pant</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Half pant-Blue & Black-Seth Vidya Mandir High School-SVM BLUE BLACK HALFPANT  PRIMARY SECTION', 'Half pant-Blue & Black-Seth Vidya Mandir High School-SVM BLUE BLACK HALFPANT  PRIMARY SECTION', 'Half pant-Blue & Black-Seth Vidya Mandir High School-SVM BLUE BLACK HALFPANT  PRIMARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(11, 38, 10, 0, NULL, NULL, 'unisex', NULL, 'SPRINGBUDS GREY  ZIPPER HOODIE ', 'springbuds-grey-zipper-hoodie', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Class- Pre -Primary</p>\n<p>Material- Knitted</p>\n<p>Sleeve- Full Sleeve</p>\n<p>Purpose- Winter Hoodie  With Zipper&nbsp;</p>\n<p></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Hoodie-Grey-C P Goenka\'s Spring Buds International PreSchool-SPRINGBUDS GREY  ZIPPER HOODIE ', 'Hoodie-Grey-C P Goenka\'s Spring Buds International PreSchool-SPRINGBUDS GREY  ZIPPER HOODIE ', 'Hoodie-Grey-C P Goenka\'s Spring Buds International PreSchool-SPRINGBUDS GREY  ZIPPER HOODIE ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(12, 38, 8, 0, NULL, NULL, 'unisex', NULL, 'SPINGBUDS BLUE DENIM SKIRT', 'spingbuds-blue-denim-skirt', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Class- Pre School</p>\n<p>Material- Denim</p>\n<p>Type- Skirt</p>\n<p></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Skirt-Blue-C P Goenka\'s Spring Buds International PreSchool-SPINGBUDS BLUE DENIM SKIRT', 'Skirt-Blue-C P Goenka\'s Spring Buds International PreSchool-SPINGBUDS BLUE DENIM SKIRT', 'Skirt-Blue-C P Goenka\'s Spring Buds International PreSchool-SPINGBUDS BLUE DENIM SKIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(13, 38, 6, 6, NULL, 1, 'unisex', NULL, 'RADCLIFFE NAVY BLUE SHORTS', 'radcliffe-navy-blue-shorts', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Type- shorts</p>\n<p>Length- Half length</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shorts-Navy blue-Radcliffe-RADCLIFFE NAVY BLUE SHORTS', 'Shorts-Navy blue-Radcliffe-RADCLIFFE NAVY BLUE SHORTS', 'Shorts-Navy blue-Radcliffe-RADCLIFFE NAVY BLUE SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(14, 38, 18, 0, NULL, NULL, 'unisex', NULL, 'SPRING BUDS NAVYBLUE DENIM SHORTS', 'spring-buds-navyblue-denim-shorts', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Material-Denim</p>\n<p>Class- Preschool</p>\n<p>Type- Half Shorts With Elastic</p>\n<p></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Half pant-Navy blue-C P Goenka\'s Spring Buds International PreSchool-SPRING BUDS NAVYBLUE DENIM SHORTS', 'Half pant-Navy blue-C P Goenka\'s Spring Buds International PreSchool-SPRING BUDS NAVYBLUE DENIM SHORTS', 'Half pant-Navy blue-C P Goenka\'s Spring Buds International PreSchool-SPRING BUDS NAVYBLUE DENIM SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(15, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'SPRING BUDS GREEN T-SHIRT', 'spring-buds-green-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Neck - Collar</p>\n<p>Class- Preschool</p>\n<p>Sleeve- Half sleeve</p>\n<p>Fit-Regluar Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Blue-C P Goenka\'s Spring Buds International PreSchool-SPRING BUDS GREEN T-SHIRT', 'T-Shirt-Blue-C P Goenka\'s Spring Buds International PreSchool-SPRING BUDS GREEN T-SHIRT', 'T-Shirt-Blue-C P Goenka\'s Spring Buds International PreSchool-SPRING BUDS GREEN T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(16, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA BLUE YELLOW T- SHIRT', 'cp-goenka-blue-yellow-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Neck - Round Neck</p>\n<p>Class- Primary &amp; Secondary</p>\n<p>Sleeve- Half sleeve</p>\n<p>Fit-Regluar Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Blue-CP Goenka International School-CP GOENKA BLUE YELLOW T- SHIRT', 'T-Shirt-Blue-CP Goenka International School-CP GOENKA BLUE YELLOW T- SHIRT', 'T-Shirt-Blue-CP Goenka International School-CP GOENKA BLUE YELLOW T- SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(17, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA BLUE STRIPE T-SHIRT', 'cp-goenka-blue-stripe-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Neck - Round Neck</p>\n<p>Class- Primary &amp; Secondary</p>\n<p>Sleeve- Half sleeve</p>\n<p>Fit-Regluar Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Blue-CP Goenka International School-CP GOENKA BLUE STRIPE T-SHIRT', 'T-Shirt-Blue-CP Goenka International School-CP GOENKA BLUE STRIPE T-SHIRT', 'T-Shirt-Blue-CP Goenka International School-CP GOENKA BLUE STRIPE T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(18, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'SPRING BUDS ORANGE T-SHIRT', 'spring-buds-orange-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Preschool</p>\n<p>Neck- Collar</p>\n<p>Sleeve- Half Sleeve</p>\n<p>Fit- Regular Fit</p>\n<p></p>\n<p></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Orange-C P Goenka\'s Spring Buds International PreSchool-SPRING BUDS ORANGE T-SHIRT', 'T-Shirt-Orange-C P Goenka\'s Spring Buds International PreSchool-SPRING BUDS ORANGE T-SHIRT', 'T-Shirt-Orange-C P Goenka\'s Spring Buds International PreSchool-SPRING BUDS ORANGE T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(19, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA BLUE GREEN T-SHIRT', 'cp-goenka-blue-green-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Primary &amp; Secondary</p>\n<p>Neck- Round Neck</p>\n<p>Sleeve- Half Sleeve</p>\n<p>Fit- Regular Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Blue & Green-CP Goenka International School-CP GOENKA BLUE GREEN T-SHIRT', 'T-Shirt-Blue & Green-CP Goenka International School-CP GOENKA BLUE GREEN T-SHIRT', 'T-Shirt-Blue & Green-CP Goenka International School-CP GOENKA BLUE GREEN T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(20, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA BLUE & WHITE T-SHIRT', 'cp-goenka-blue-white-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary &amp; Secondary</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve- Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit- Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Blue & White-CP Goenka International School-CP GOENKA BLUE & WHITE T-SHIRT', 'T-Shirt-Blue & White-CP Goenka International School-CP GOENKA BLUE & WHITE T-SHIRT', 'T-Shirt-Blue & White-CP Goenka International School-CP GOENKA BLUE & WHITE T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(21, 38, 6, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA NAVY BLUE WITH GREEN LINE SHORTS', 'cp-goenka-navy-blue-with-green-line-shorts', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Primary&nbsp;</p>\n<p>Type - Half Shorts</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shorts-Blue & Green-CP Goenka International School-CP GOENKA NAVY BLUE WITH GREEN LINE SHORTS', 'Shorts-Blue & Green-CP Goenka International School-CP GOENKA NAVY BLUE WITH GREEN LINE SHORTS', 'Shorts-Blue & Green-CP Goenka International School-CP GOENKA NAVY BLUE WITH GREEN LINE SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(22, 38, 6, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA NAVY BLUE WITH YELLOW LINE SHORTS', 'cp-goenka-navy-blue-with-yellow-line-shorts', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Primary&nbsp;</p>\n<p>Type - Half Shorts</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shorts-Blue & Yellow-CP Goenka International School-CP GOENKA NAVY BLUE WITH YELLOW LINE SHORTS', 'Shorts-Blue & Yellow-CP Goenka International School-CP GOENKA NAVY BLUE WITH YELLOW LINE SHORTS', 'Shorts-Blue & Yellow-CP Goenka International School-CP GOENKA NAVY BLUE WITH YELLOW LINE SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(23, 38, 6, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA NAVY BLUE WITH WHITE LINE SHORTS', 'cp-goenka-navy-blue-with-white-line-shorts', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Primary&nbsp;</p>\n<p>Type - Half Shorts</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shorts-Blue & White-CP Goenka International School-CP GOENKA NAVY BLUE WITH WHITE LINE SHORTS', 'Shorts-Blue & White-CP Goenka International School-CP GOENKA NAVY BLUE WITH WHITE LINE SHORTS', 'Shorts-Blue & White-CP Goenka International School-CP GOENKA NAVY BLUE WITH WHITE LINE SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(24, 38, 6, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA NAVY BLUE WITH BLUE LINE SHORTS', 'cp-goenka-navy-blue-with-blue-line-shorts', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Primary&nbsp;</p>\n<p>Type - Half Shorts</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shorts-Blue-CP Goenka International School-CP GOENKA NAVY BLUE WITH BLUE LINE SHORTS', 'Shorts-Blue-CP Goenka International School-CP GOENKA NAVY BLUE WITH BLUE LINE SHORTS', 'Shorts-Blue-CP Goenka International School-CP GOENKA NAVY BLUE WITH BLUE LINE SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(25, 38, 1, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA  HALF WHITE  SHIRT', 'cp-goenka-half-white-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Textile</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary </span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck - V Neck</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve- Half sleeve</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit- Regular Fit</span></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-White-CP Goenka International School-CP GOENKA  HALF WHITE  SHIRT', 'Shirt-White-CP Goenka International School-CP GOENKA  HALF WHITE  SHIRT', 'Shirt-White-CP Goenka International School-CP GOENKA  HALF WHITE  SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(26, 38, 16, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA NAVY BLUE WITH BLUE LINE TRACKPANT', 'cp-goenka-navy-blue-with-blue-line-trackpant', NULL, 1, 3, 1, '1', '<p>Brand-Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Secondary</p>\n<p>Type-Trackpant</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Trackpant-Navy Blue & blue -CP Goenka International School-CP GOENKA NAVY BLUE WITH BLUE LINE TRACKPANT', 'Trackpant-Navy Blue & blue -CP Goenka International School-CP GOENKA NAVY BLUE WITH BLUE LINE TRACKPANT', 'Trackpant-Navy Blue & blue -CP Goenka International School-CP GOENKA NAVY BLUE WITH BLUE LINE TRACKPANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(27, 38, 16, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA NAVY BLUE WITH WHITE LINE TRACKPANT', 'cp-goenka-navy-blue-with-white-line-trackpant', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand-Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type-Trackpant</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Trackpant-Blue & White-CP Goenka International School-CP GOENKA NAVY BLUE WITH WHITE LINE TRACKPANT', 'Trackpant-Blue & White-CP Goenka International School-CP GOENKA NAVY BLUE WITH WHITE LINE TRACKPANT', 'Trackpant-Blue & White-CP Goenka International School-CP GOENKA NAVY BLUE WITH WHITE LINE TRACKPANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(28, 38, 16, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA NAVYBLUE WITH YELLOW LINE TRACKPANT', 'cp-goenka-navyblue-with-yellow-line-trackpant', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Secondary</p>\n<p>Type - Trackpant</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Trackpant-Blue & Yellow-CP Goenka International School-CP GOENKA NAVYBLUE WITH YELLOW LINE TRACKPANT', 'Trackpant-Blue & Yellow-CP Goenka International School-CP GOENKA NAVYBLUE WITH YELLOW LINE TRACKPANT', 'Trackpant-Blue & Yellow-CP Goenka International School-CP GOENKA NAVYBLUE WITH YELLOW LINE TRACKPANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(29, 38, 16, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA NAVYBLUE WITH GREEN LINE TRACKPANT', 'cp-goenka-navyblue-with-green-line-trackpant', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted&nbsp;</p>\n<p>Class- Secondary</p>\n<p>Type- Trackpant</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Trackpant-Blue & Green-CP Goenka International School-CP GOENKA NAVYBLUE WITH GREEN LINE TRACKPANT', 'Trackpant-Blue & Green-CP Goenka International School-CP GOENKA NAVYBLUE WITH GREEN LINE TRACKPANT', 'Trackpant-Blue & Green-CP Goenka International School-CP GOENKA NAVYBLUE WITH GREEN LINE TRACKPANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(30, 38, 1, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA WHITE FULL SLEEVES', 'cp-goenka-white-full-sleeves', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Textile</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck - V Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve- Full sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit- Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-White-CP Goenka International School-CP GOENKA WHITE FULL SLEEVES', 'Shirt-White-CP Goenka International School-CP GOENKA WHITE FULL SLEEVES', 'Shirt-White-CP Goenka International School-CP GOENKA WHITE FULL SLEEVES', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(31, 38, 15, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA GREY WINTER JACKET ', 'cp-goenka-grey-winter-jacket', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Primary &amp; Secondary</p>\n<p>Sleeve- Full Sleeve</p>\n<p>Type- Winter Jacket</p>\n<p></p>\n<p></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Jacket-Grey-CP Goenka International School-CP GOENKA GREY WINTER JACKET ', 'Jacket-Grey-CP Goenka International School-CP GOENKA GREY WINTER JACKET ', 'Jacket-Grey-CP Goenka International School-CP GOENKA GREY WINTER JACKET ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(32, 38, 1, 0, NULL, NULL, 'unisex', NULL, 'PODAR WORLD YELLOW  T-SHIRT', 'podar-world-yellow-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Textile</p>\n<p>Neck- Collar</p>\n<p>Sleeve- Half Sleeve</p>\n<p>Fit- Regular Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-Yellow-Podar World School-PODAR WORLD YELLOW  T-SHIRT', 'Shirt-Yellow-Podar World School-PODAR WORLD YELLOW  T-SHIRT', 'Shirt-Yellow-Podar World School-PODAR WORLD YELLOW  T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(33, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'PODAR WORLD YELLOW FULL SLEEVE T-SHIRT', 'podar-world-yellow-full-sleeve-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand-  Varitty</p>\n<p>Material- Textile</p>\n<p>Neck- Round Neck</p>\n<p>Sleeve-Full Sleeve</p>\n<p>Fit- Regular Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Yellow-Podar World School-PODAR WORLD YELLOW FULL SLEEVE T-SHIRT', 'T-Shirt-Yellow-Podar World School-PODAR WORLD YELLOW FULL SLEEVE T-SHIRT', 'T-Shirt-Yellow-Podar World School-PODAR WORLD YELLOW FULL SLEEVE T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(34, 38, 18, 0, NULL, NULL, 'unisex', NULL, 'PODAR WORLD BLUE SHORTS', 'podar-world-blue-shorts', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Denim</p>\n<p>Type- Half Shorts</p>\n<p>Class- Primary</p>\n<p></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Half pant-Navy blue-Podar World School-PODAR WORLD BLUE SHORTS', 'Half pant-Navy blue-Podar World School-PODAR WORLD BLUE SHORTS', 'Half pant-Navy blue-Podar World School-PODAR WORLD BLUE SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(35, 38, 18, 0, NULL, NULL, 'unisex', NULL, 'CP GOENKA NAVY BLUE CAPRI PANT', 'cp-goenka-navy-blue-capri-pant', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Material- Denim</p>\n<p>Class- Primary</p>\n<p>Type- Half Shorts&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Half pant-Navy blue-CP Goenka International School-CP GOENKA NAVY BLUE CAPRI PANT', 'Half pant-Navy blue-CP Goenka International School-CP GOENKA NAVY BLUE CAPRI PANT', 'Half pant-Navy blue-CP Goenka International School-CP GOENKA NAVY BLUE CAPRI PANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(36, 38, 1, 0, NULL, NULL, 'unisex', NULL, 'PODAR WORLD BLUE WHITE SHIRT', 'podar-world-blue-white-shirt', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Class- Primary &amp; Secondary</p>\n<p>Material - Knitted</p>\n<p>Neck- V Neck</p>\n<p>Sleeve- Half Sleeve</p>\n<p>Fit - Regluar Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-Blue & White-Podar World School-PODAR WORLD BLUE WHITE SHIRT', 'Shirt-Blue & White-Podar World School-PODAR WORLD BLUE WHITE SHIRT', 'Shirt-Blue & White-Podar World School-PODAR WORLD BLUE WHITE SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(37, 38, 18, 3, NULL, 5, 'unisex', NULL, 'EXPERT NAVYBLUE DENIM HALF PANT', 'expert-navyblue-denim-half-pant', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Denim</p>\n<p>Class- Primary</p>\n<p>Type- Denim</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Half pant-Navy blue-Expert International School-EXPERT NAVYBLUE DENIM HALF PANT', 'Half pant-Navy blue-Expert International School-EXPERT NAVYBLUE DENIM HALF PANT', 'Half pant-Navy blue-Expert International School-EXPERT NAVYBLUE DENIM HALF PANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:05:47', NULL, NULL),
(38, 38, 18, 0, NULL, NULL, 'unisex', NULL, 'PODAR WORLD NAVY BLUE HALF SHORTS', 'podar-world-navy-blue-half-shorts', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Denim</p>\n<p>Class- Primary&nbsp;</p>\n<p>Type- Denim</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Half pant-Navy blue-Podar World School-PODAR WORLD NAVY BLUE HALF SHORTS', 'Half pant-Navy blue-Podar World School-PODAR WORLD NAVY BLUE HALF SHORTS', 'Half pant-Navy blue-Podar World School-PODAR WORLD NAVY BLUE HALF SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(39, 38, 21, 0, NULL, NULL, 'unisex', NULL, 'PODAR WORLD NAVY BLUE FULL PANT', 'podar-world-navy-blue-full-pant', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Hetrik</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Full Jeans</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Denim</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Slim Fit</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Length- Long</span>&nbsp;</p>\n<p>Class- Secondary</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Full Pant-Navy blue-Podar World School-PODAR WORLD NAVY BLUE FULL PANT', 'Full Pant-Navy blue-Podar World School-PODAR WORLD NAVY BLUE FULL PANT', 'Full Pant-Navy blue-Podar World School-PODAR WORLD NAVY BLUE FULL PANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(40, 38, 10, 0, NULL, NULL, 'unisex', NULL, 'SVIS SCHOOL GREY ZIPPER HOODIE  ( BORIWALI)', 'svis-school-grey-zipper-hoodie-boriwali', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Age Range - Kid</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- kintted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleevee- Full Sleeve</span>&nbsp;</p>\n<p>Description- School Zipper Hoodie With Side Pocket</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Hoodie-Grey-Swami Vivekanand International School & Junior College-SVIS SCHOOL GREY ZIPPER HOODIE  ( BORIWALI)', 'Hoodie-Grey-Swami Vivekanand International School & Junior College-SVIS SCHOOL GREY ZIPPER HOODIE  ( BORIWALI)', 'Hoodie-Grey-Swami Vivekanand International School & Junior College-SVIS SCHOOL GREY ZIPPER HOODIE  ( BORIWALI)', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(41, 38, 10, 0, NULL, NULL, 'unisex', NULL, 'SVIS SCHOOL & COLLEGE  GREY ZIPPER HOODIE (KANDIVALI)', 'svis-school-college-grey-zipper-hoodie-kandivali', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Age Range - Kid</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- kintted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleevee- Full Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Description- School Zipper Hoodie With Side Pocket</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Hoodie-Grey-Swami Vivekanand International School & Junior College-SVIS SCHOOL & COLLEGE  GREY ZIPPER HOODIE (KANDIVALI)', 'Hoodie-Grey-Swami Vivekanand International School & Junior College-SVIS SCHOOL & COLLEGE  GREY ZIPPER HOODIE (KANDIVALI)', 'Hoodie-Grey-Swami Vivekanand International School & Junior College-SVIS SCHOOL & COLLEGE  GREY ZIPPER HOODIE (KANDIVALI)', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(42, 38, 1, 2, NULL, 5, 'unisex', NULL, 'SVM BLUE YELLOW SHIRT KG SECTION', 'svm-blue-yellow-shirt-kg-section', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Nursery , Junior Kg , Senior Kg</p>\n<p>Neck- Collar Neck</p>\n<p>Sleeve - Half Sleeve</p>\n<p>Fit - Regular Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-White-Seth Vidya Mandir High School-SVM BLUE YELLOW SHIRT KG SECTION', 'Shirt-White-Seth Vidya Mandir High School-SVM BLUE YELLOW SHIRT KG SECTION', 'Shirt-White-Seth Vidya Mandir High School-SVM BLUE YELLOW SHIRT KG SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL);
INSERT INTO `erp_uniforms` (`id`, `vendor_id`, `uniform_type_id`, `school_id`, `branch_id`, `board_id`, `gender`, `color`, `product_name`, `slug`, `isbn`, `min_quantity`, `days_to_exchange`, `material_id`, `product_origin`, `product_description`, `manufacturer_details`, `packer_details`, `customer_details`, `price`, `size_chart_id`, `size_id`, `packaging_length`, `packaging_width`, `packaging_height`, `packaging_weight`, `tax`, `gst_percentage`, `school_margin`, `hsn`, `meta_title`, `meta_keywords`, `meta_description`, `status`, `is_individual`, `is_set`, `created_at`, `updated_at`, `school_commission_type`, `school_commission_value`) VALUES
(43, 38, 1, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE NAVY BLUE SHIRT SECONDARY SECTION ', 'svm-white-navy-blue-shirt-secondary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secndory  Section </span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Collar Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-Navy blue-Seth Vidya Mandir High School-SVM WHITE NAVY BLUE SHIRT SECONDARY SECTION ', 'Shirt-Navy blue-Seth Vidya Mandir High School-SVM WHITE NAVY BLUE SHIRT SECONDARY SECTION ', 'Shirt-Navy blue-Seth Vidya Mandir High School-SVM WHITE NAVY BLUE SHIRT SECONDARY SECTION ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(44, 38, 2, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE GREEN T -SHIRT SECONDARY SECTION ', 'svm-white-green-t-shirt-secondary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-White-Seth Vidya Mandir High School-SVM WHITE GREEN T -SHIRT SECONDARY SECTION ', 'T-Shirt-White-Seth Vidya Mandir High School-SVM WHITE GREEN T -SHIRT SECONDARY SECTION ', 'T-Shirt-White-Seth Vidya Mandir High School-SVM WHITE GREEN T -SHIRT SECONDARY SECTION ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(45, 38, 2, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE RED T-SHIRT SECONDARY SECTION ', 'svm-white-red-t-shirt-secondary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-White-Seth Vidya Mandir High School-SVM WHITE RED T-SHIRT SECONDARY SECTION ', 'T-Shirt-White-Seth Vidya Mandir High School-SVM WHITE RED T-SHIRT SECONDARY SECTION ', 'T-Shirt-White-Seth Vidya Mandir High School-SVM WHITE RED T-SHIRT SECONDARY SECTION ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(46, 38, 2, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE YELLOW T-SHIRT SECONDARY SECTION', 'svm-white-yellow-t-shirt-secondary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Yellow-Seth Vidya Mandir High School-SVM WHITE YELLOW T-SHIRT SECONDARY SECTION', 'T-Shirt-Yellow-Seth Vidya Mandir High School-SVM WHITE YELLOW T-SHIRT SECONDARY SECTION', 'T-Shirt-Yellow-Seth Vidya Mandir High School-SVM WHITE YELLOW T-SHIRT SECONDARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(47, 38, 2, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE BLUE T-SHIRT SECONDARY SECTION', 'svm-white-blue-t-shirt-secondary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Blue-Seth Vidya Mandir High School-SVM WHITE BLUE T-SHIRT SECONDARY SECTION', 'T-Shirt-Blue-Seth Vidya Mandir High School-SVM WHITE BLUE T-SHIRT SECONDARY SECTION', 'T-Shirt-Blue-Seth Vidya Mandir High School-SVM WHITE BLUE T-SHIRT SECONDARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(48, 38, 2, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE GREEN T-SHIRT SECONDARY SECTION', 'svm-white-green-t-shirt-secondary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Full Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Green-Seth Vidya Mandir High School-SVM WHITE GREEN T-SHIRT SECONDARY SECTION', 'T-Shirt-Green-Seth Vidya Mandir High School-SVM WHITE GREEN T-SHIRT SECONDARY SECTION', 'T-Shirt-Green-Seth Vidya Mandir High School-SVM WHITE GREEN T-SHIRT SECONDARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(49, 38, 2, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE YELLOW T-SHIRT SECONDARY SECTION ', 'svm-white-yellow-t-shirt-secondary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Full Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Yellow-Seth Vidya Mandir High School-SVM WHITE YELLOW T-SHIRT SECONDARY SECTION ', 'T-Shirt-Yellow-Seth Vidya Mandir High School-SVM WHITE YELLOW T-SHIRT SECONDARY SECTION ', 'T-Shirt-Yellow-Seth Vidya Mandir High School-SVM WHITE YELLOW T-SHIRT SECONDARY SECTION ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(50, 38, 2, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE BLUE T-SHIRT SECONDARY SECTION', 'svm-white-blue-t-shirt-secondary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Full Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Blue-Seth Vidya Mandir High School-SVM WHITE BLUE T-SHIRT SECONDARY SECTION', 'T-Shirt-Blue-Seth Vidya Mandir High School-SVM WHITE BLUE T-SHIRT SECONDARY SECTION', 'T-Shirt-Blue-Seth Vidya Mandir High School-SVM WHITE BLUE T-SHIRT SECONDARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(51, 38, 2, 2, NULL, 5, 'unisex', NULL, 'SVM WHITE RED T-SHIRT SECONDARY SECTION', 'svm-white-red-t-shirt-secondary-section', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Secondary Section</p>\n<p>Neck- Round Neck</p>\n<p>Sleeve - Full Sleeve</p>\n<p>Fit - Regular Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Red-Seth Vidya Mandir High School-SVM WHITE RED T-SHIRT SECONDARY SECTION', 'T-Shirt-Red-Seth Vidya Mandir High School-SVM WHITE RED T-SHIRT SECONDARY SECTION', 'T-Shirt-Red-Seth Vidya Mandir High School-SVM WHITE RED T-SHIRT SECONDARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(52, 38, 7, 2, NULL, 5, 'unisex', NULL, 'SVM BLUE BLOOMERS', 'svm-blue-bloomers', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Class-KG &amp; Primary Section</p>\n<p>Material- Hosiery</p>\n<p>Fit - Slim Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Lagging-Blue-Seth Vidya Mandir High School-SVM BLUE BLOOMERS', 'Lagging-Blue-Seth Vidya Mandir High School-SVM BLUE BLOOMERS', 'Lagging-Blue-Seth Vidya Mandir High School-SVM BLUE BLOOMERS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(53, 38, 17, 2, NULL, 5, 'unisex', NULL, 'SVM NAVY BLUE PINA FROCK  SECONDARY  SECTION ', 'svm-navy-blue-pina-frock-secondary-section', NULL, 1, 3, 1, '1', '<p>Brand - Varitty&nbsp;</p>\n<p>Material- Knitted&nbsp;</p>\n<p>Class- Secondary Section</p>\n<p>Description -  Navy Blue With White Stripe with Belt&nbsp;</p>\n<p></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Pina frock-Navy blue-Seth Vidya Mandir High School-SVM NAVY BLUE PINA FROCK  SECONDARY  SECTION ', 'Pina frock-Navy blue-Seth Vidya Mandir High School-SVM NAVY BLUE PINA FROCK  SECONDARY  SECTION ', 'Pina frock-Navy blue-Seth Vidya Mandir High School-SVM NAVY BLUE PINA FROCK  SECONDARY  SECTION ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(54, 38, 17, 2, NULL, 5, 'unisex', NULL, 'SVM BLUE PINA FROCK PRIMARY SECTION', 'svm-blue-pina-frock-primary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Description -   Blue With Black Stripe with Belt</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Pina frock-Blue & Black-Seth Vidya Mandir High School-SVM BLUE PINA FROCK PRIMARY SECTION', 'Pina frock-Blue & Black-Seth Vidya Mandir High School-SVM BLUE PINA FROCK PRIMARY SECTION', 'Pina frock-Blue & Black-Seth Vidya Mandir High School-SVM BLUE PINA FROCK PRIMARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(55, 38, 17, 2, NULL, 5, 'unisex', NULL, 'SVM BLUE WITH YELLOW CHECKS SHIRT PRE - PRIMARY SECTION', 'svm-blue-with-yellow-checks-shirt-pre-primary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Description -  Blue With Black Stripe with Belt</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Pina frock-Blue & Yellow-Seth Vidya Mandir High School-SVM BLUE WITH YELLOW CHECKS SHIRT PRE - PRIMARY SECTION', 'Pina frock-Blue & Yellow-Seth Vidya Mandir High School-SVM BLUE WITH YELLOW CHECKS SHIRT PRE - PRIMARY SECTION', 'Pina frock-Blue & Yellow-Seth Vidya Mandir High School-SVM BLUE WITH YELLOW CHECKS SHIRT PRE - PRIMARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(56, 38, 12, 2, NULL, 5, 'unisex', NULL, 'SVM BLUE SOCKS', 'svm-blue-socks', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Material- Knitted</p>\n<p>Class-Pre-Primary,  Primary &amp; Secondary Section</p>\n<p>Description - Good Comfort To Toe</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Socks-Blue & White-Seth Vidya Mandir High School-SVM BLUE SOCKS', 'Socks-Blue & White-Seth Vidya Mandir High School-SVM BLUE SOCKS', 'Socks-Blue & White-Seth Vidya Mandir High School-SVM BLUE SOCKS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(57, 38, 1, 2, NULL, 1, 'unisex', NULL, 'SVM RED  BLACK SHIRT PRIMARRY SECTION ', 'svm-red-black-shirt-primarry-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary  Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-White-Seth Vidya Mandir High School-SVM RED  BLACK SHIRT PRIMARRY SECTION ', 'Shirt-White-Seth Vidya Mandir High School-SVM RED  BLACK SHIRT PRIMARRY SECTION ', 'Shirt-White-Seth Vidya Mandir High School-SVM RED  BLACK SHIRT PRIMARRY SECTION ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(58, 38, 1, 2, NULL, 1, 'unisex', NULL, 'SVM YELLOW SHIRT PRE-PRIMARY SECTION', 'svm-yellow-shirt-pre-primary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Nursery , Junior &amp; Senior  Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-Yellow-Seth Vidya Mandir High School-SVM YELLOW SHIRT PRE-PRIMARY SECTION', 'Shirt-Yellow-Seth Vidya Mandir High School-SVM YELLOW SHIRT PRE-PRIMARY SECTION', 'Shirt-Yellow-Seth Vidya Mandir High School-SVM YELLOW SHIRT PRE-PRIMARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(59, 38, 1, 2, NULL, 1, 'unisex', NULL, 'SVM WHITE RED SHIRT SECONDARY SECTION', 'svm-white-red-shirt-secondary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve - Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-White-Seth Vidya Mandir High School-SVM WHITE RED SHIRT SECONDARY SECTION', 'Shirt-White-Seth Vidya Mandir High School-SVM WHITE RED SHIRT SECONDARY SECTION', 'Shirt-White-Seth Vidya Mandir High School-SVM WHITE RED SHIRT SECONDARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(60, 38, 2, 2, NULL, 1, 'unisex', NULL, 'SVM BLUE P.T  T- SHIRT', 'svm-blue-p-t-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class-Pre- Primary, Primary &amp;  Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve -Half  Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM BLUE P.T  T- SHIRT', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM BLUE P.T  T- SHIRT', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM BLUE P.T  T- SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(61, 38, 2, 2, NULL, 1, 'unisex', NULL, 'SVM RED P.T T-SHIRT', 'svm-red-p-t-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class-Pre- Primary, Primary &amp;  Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve -Half  Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM RED P.T T-SHIRT', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM RED P.T T-SHIRT', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM RED P.T T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(62, 38, 2, 2, NULL, 1, 'unisex', NULL, 'SVM GREEN P.T T-SHIRT', 'svm-green-p-t-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class-Pre- Primary, Primary &amp;  Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve -Half  Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM GREEN P.T T-SHIRT', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM GREEN P.T T-SHIRT', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM GREEN P.T T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL);
INSERT INTO `erp_uniforms` (`id`, `vendor_id`, `uniform_type_id`, `school_id`, `branch_id`, `board_id`, `gender`, `color`, `product_name`, `slug`, `isbn`, `min_quantity`, `days_to_exchange`, `material_id`, `product_origin`, `product_description`, `manufacturer_details`, `packer_details`, `customer_details`, `price`, `size_chart_id`, `size_id`, `packaging_length`, `packaging_width`, `packaging_height`, `packaging_weight`, `tax`, `gst_percentage`, `school_margin`, `hsn`, `meta_title`, `meta_keywords`, `meta_description`, `status`, `is_individual`, `is_set`, `created_at`, `updated_at`, `school_commission_type`, `school_commission_value`) VALUES
(63, 38, 2, 2, NULL, 1, 'unisex', NULL, 'SVM YELLOW P.T T-SHIRT', 'svm-yellow-p-t-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class-Pre- Primary, Primary &amp;  Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve -Half  Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Regular Fit</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM YELLOW P.T T-SHIRT', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM YELLOW P.T T-SHIRT', 'T-Shirt-Peach-Seth Vidya Mandir High School-SVM YELLOW P.T T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(64, 38, 16, 2, NULL, 1, 'unisex', NULL, 'SVM NAVY BLUE TRACKPANT', 'svm-navy-blue-trackpant', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class-Pre- Primary , Primary &amp; Secondary Section</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Full Pant</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Loose Fit</span></p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Trackpant-Navy blue-Seth Vidya Mandir High School-SVM NAVY BLUE TRACKPANT', 'Trackpant-Navy blue-Seth Vidya Mandir High School-SVM NAVY BLUE TRACKPANT', 'Trackpant-Navy blue-Seth Vidya Mandir High School-SVM NAVY BLUE TRACKPANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(65, 38, 12, 2, NULL, 1, 'unisex', NULL, 'SVM RED  YELLOW SOCKS', 'svm-red-yellow-socks', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Material- Knitted</p>\n<p>Class- Pre-Primary &amp; Secondary  Section</p>\n<p>Description - Good Comfort To Toe</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Socks-Red-Seth Vidya Mandir High School-SVM RED  YELLOW SOCKS', 'Socks-Red-Seth Vidya Mandir High School-SVM RED  YELLOW SOCKS', 'Socks-Red-Seth Vidya Mandir High School-SVM RED  YELLOW SOCKS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(66, 38, 12, 2, NULL, 1, 'unisex', NULL, 'SVM WHITE RED SOCKS ', 'svm-white-red-socks', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Material- Knitted</p>\n<p>Class-Primary Section</p>\n<p>Description - Good Comfort To Toe</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Socks-White & Red-Seth Vidya Mandir High School-SVM WHITE RED SOCKS ', 'Socks-White & Red-Seth Vidya Mandir High School-SVM WHITE RED SOCKS ', 'Socks-White & Red-Seth Vidya Mandir High School-SVM WHITE RED SOCKS ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(67, 38, 18, 2, NULL, 1, 'unisex', NULL, 'SVM RED BLACK HALF PANT PRIMARY SECTION', 'svm-red-black-half-pant-primary-section', NULL, 1, 3, 1, '1', '<p>Brand - Varitty</p>\n<p>Class- Pre- Primary Section</p>\n<p>Material - Knitted</p>\n<p>Type- Half pant&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Half pant-Red & Black -Seth Vidya Mandir High School-SVM RED BLACK HALF PANT PRIMARY SECTION', 'Half pant-Red & Black -Seth Vidya Mandir High School-SVM RED BLACK HALF PANT PRIMARY SECTION', 'Half pant-Red & Black -Seth Vidya Mandir High School-SVM RED BLACK HALF PANT PRIMARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(68, 38, 18, 2, NULL, 1, 'unisex', NULL, 'SVM RED YELLOW HALF PANT PRE-PRIMARY', 'svm-red-yellow-half-pant-pre-primary', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Pre- Primary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material - Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type- Half pant</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Half pant-Red & Yelllow-Seth Vidya Mandir High School-SVM RED YELLOW HALF PANT PRE-PRIMARY', 'Half pant-Red & Yelllow-Seth Vidya Mandir High School-SVM RED YELLOW HALF PANT PRE-PRIMARY', 'Half pant-Red & Yelllow-Seth Vidya Mandir High School-SVM RED YELLOW HALF PANT PRE-PRIMARY', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(69, 38, 21, 2, NULL, 1, 'unisex', NULL, 'SVM RED FULL PANT ', 'svm-red-full-pant', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Full Pant</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Slim Fit</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Length- Long</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Full Pant-Red & White-Seth Vidya Mandir High School-SVM RED FULL PANT ', 'Full Pant-Red & White-Seth Vidya Mandir High School-SVM RED FULL PANT ', 'Full Pant-Red & White-Seth Vidya Mandir High School-SVM RED FULL PANT ', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(70, 38, 17, 2, NULL, 1, 'unisex', NULL, 'SVM RED BLACK PINAFROCK PRIMARY SECTION', 'svm-red-black-pinafrock-primary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Description -  Red  With Black Stripe with Belt</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Pina frock-Red & Black -Seth Vidya Mandir High School-SVM RED BLACK PINAFROCK PRIMARY SECTION', 'Pina frock-Red & Black -Seth Vidya Mandir High School-SVM RED BLACK PINAFROCK PRIMARY SECTION', 'Pina frock-Red & Black -Seth Vidya Mandir High School-SVM RED BLACK PINAFROCK PRIMARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(71, 38, 17, 2, NULL, 1, 'unisex', NULL, 'SVM RED YELLOW PINAFROCK PRE- PRIMARY SECTION', 'svm-red-yellow-pinafrock-pre-primary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Pre Primary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Description -  Red With Yellow Checks with Belt</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Pina frock-Red & Yelllow-Seth Vidya Mandir High School-SVM RED YELLOW PINAFROCK PRE- PRIMARY SECTION', 'Pina frock-Red & Yelllow-Seth Vidya Mandir High School-SVM RED YELLOW PINAFROCK PRE- PRIMARY SECTION', 'Pina frock-Red & Yelllow-Seth Vidya Mandir High School-SVM RED YELLOW PINAFROCK PRE- PRIMARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(72, 38, 17, 2, NULL, 1, 'unisex', NULL, 'RED PINAFROCK  SECONDARY SECTION', 'red-pinafrock-secondary-section', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Description -  Red With White Line with Belt</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Pina frock-Red & White-Seth Vidya Mandir High School-RED PINAFROCK  SECONDARY SECTION', 'Pina frock-Red & White-Seth Vidya Mandir High School-RED PINAFROCK  SECONDARY SECTION', 'Pina frock-Red & White-Seth Vidya Mandir High School-RED PINAFROCK  SECONDARY SECTION', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(73, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL ORANGE T-SHIRT', 'ajmera-global-orange-t-shirt', NULL, 1, 3, 1, '1', '<p>Brand- Varitty</p>\n<p>Material- Knitted</p>\n<p>Neck- Collar Neck</p>\n<p>Sleeve- Half Sleeve</p>\n<p>Fit- Regular Fit</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Orange-Ajmera Global School-AJMERA GLOBAL ORANGE T-SHIRT', 'T-Shirt-Orange-Ajmera Global School-AJMERA GLOBAL ORANGE T-SHIRT', 'T-Shirt-Orange-Ajmera Global School-AJMERA GLOBAL ORANGE T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(74, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL GREEN T-SHIRT', 'ajmera-global-green-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Collar</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve- Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit- Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Green-Ajmera Global School-AJMERA GLOBAL GREEN T-SHIRT', 'T-Shirt-Green-Ajmera Global School-AJMERA GLOBAL GREEN T-SHIRT', 'T-Shirt-Green-Ajmera Global School-AJMERA GLOBAL GREEN T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(75, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL BLUE T-SHIRT', 'ajmera-global-blue-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Collar</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve- Half Sleeve</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit- Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Blue-Ajmera Global School-AJMERA GLOBAL BLUE T-SHIRT', 'T-Shirt-Blue-Ajmera Global School-AJMERA GLOBAL BLUE T-SHIRT', 'T-Shirt-Blue-Ajmera Global School-AJMERA GLOBAL BLUE T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(76, 38, 1, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL RED SHIRT', 'ajmera-global-red-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Collar Neck</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve- Half Sleeve</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit- Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shirt-Red-Ajmera Global School-AJMERA GLOBAL RED SHIRT', 'Shirt-Red-Ajmera Global School-AJMERA GLOBAL RED SHIRT', 'Shirt-Red-Ajmera Global School-AJMERA GLOBAL RED SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(77, 38, 16, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL GREY WITH GREEN LINE TRACKPANT', 'ajmera-global-grey-with-green-line-trackpant', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type- Trackpant</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Length - Full Length</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Trackpant-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH GREEN LINE TRACKPANT', 'Trackpant-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH GREEN LINE TRACKPANT', 'Trackpant-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH GREEN LINE TRACKPANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(78, 38, 16, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL GREY WITH ORANGE  LINE TRACKPANT', 'ajmera-global-grey-with-orange-line-trackpant', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type- Trackpant</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Length - Full Length</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Trackpant-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH ORANGE  LINE TRACKPANT', 'Trackpant-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH ORANGE  LINE TRACKPANT', 'Trackpant-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH ORANGE  LINE TRACKPANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(79, 38, 16, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL GREY WITH YELLOW LINE TRACKPANT', 'ajmera-global-grey-with-yellow-line-trackpant', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type- Trackpant</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Length - Full Length</span>&nbsp;&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Trackpant-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH YELLOW LINE TRACKPANT', 'Trackpant-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH YELLOW LINE TRACKPANT', 'Trackpant-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH YELLOW LINE TRACKPANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(80, 38, 6, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL GREY WITH BLUE LINE SHORTS', 'ajmera-global-grey-with-blue-line-shorts', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Half Shorts</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH BLUE LINE SHORTS', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH BLUE LINE SHORTS', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH BLUE LINE SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(81, 38, 6, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL GREY WITH ORANGE LINE SHORTS', 'ajmera-global-grey-with-orange-line-shorts', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Half Shorts</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, '61112000', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH ORANGE LINE SHORTS', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH ORANGE LINE SHORTS', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH ORANGE LINE SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(82, 38, 6, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL GREY WITH GREEN LINE SHORTS', 'ajmera-global-grey-with-green-line-shorts', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Half Shorts</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH GREEN LINE SHORTS', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH GREEN LINE SHORTS', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH GREEN LINE SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(83, 38, 6, 0, NULL, NULL, 'unisex', NULL, 'AJMERA GLOBAL GREY WITH YELLOW SHORTS', 'ajmera-global-grey-with-yellow-shorts', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Half Shorts</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH YELLOW SHORTS', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH YELLOW SHORTS', 'Shorts-Grey-Ajmera Global School-AJMERA GLOBAL GREY WITH YELLOW SHORTS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(84, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'AJMERA PEACH T-SHIRT', 'ajmera-peach-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Collar Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve- Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit- Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Peach-Ajmera School-AJMERA PEACH T-SHIRT', 'T-Shirt-Peach-Ajmera School-AJMERA PEACH T-SHIRT', 'T-Shirt-Peach-Ajmera School-AJMERA PEACH T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(85, 38, 18, 0, NULL, NULL, 'unisex', NULL, 'AJMERA BROWN HALF PANT', 'ajmera-brown-half-pant', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class-  Primary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material - Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type- Half pant</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Half pant-Brown-Ajmera School-AJMERA BROWN HALF PANT', 'Half pant-Brown-Ajmera School-AJMERA BROWN HALF PANT', 'Half pant-Brown-Ajmera School-AJMERA BROWN HALF PANT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(86, 38, 8, 0, NULL, NULL, 'unisex', NULL, 'AJMERA BROWN SKIRT', 'ajmera-brown-skirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Denim</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type- Skirt</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Skirt-Brown-Ajmera School-AJMERA BROWN SKIRT', 'Skirt-Brown-Ajmera School-AJMERA BROWN SKIRT', 'Skirt-Brown-Ajmera School-AJMERA BROWN SKIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL);
INSERT INTO `erp_uniforms` (`id`, `vendor_id`, `uniform_type_id`, `school_id`, `branch_id`, `board_id`, `gender`, `color`, `product_name`, `slug`, `isbn`, `min_quantity`, `days_to_exchange`, `material_id`, `product_origin`, `product_description`, `manufacturer_details`, `packer_details`, `customer_details`, `price`, `size_chart_id`, `size_id`, `packaging_length`, `packaging_width`, `packaging_height`, `packaging_weight`, `tax`, `gst_percentage`, `school_margin`, `hsn`, `meta_title`, `meta_keywords`, `meta_description`, `status`, `is_individual`, `is_set`, `created_at`, `updated_at`, `school_commission_type`, `school_commission_value`) VALUES
(87, 38, 2, 0, NULL, NULL, 'unisex', NULL, 'JOLLY KIDS GREEN T-SHIRT', 'jolly-kids-green-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Collar Neck</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve- Half Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Pre-School</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit- Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-Green-Jolly kids Pre-school-JOLLY KIDS GREEN T-SHIRT', 'T-Shirt-Green-Jolly kids Pre-school-JOLLY KIDS GREEN T-SHIRT', 'T-Shirt-Green-Jolly kids Pre-school-JOLLY KIDS GREEN T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(88, 38, 7, 2, NULL, 1, 'unisex', NULL, 'SVM RED BLOOMERS', 'svm-red-bloomers', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class-KG &amp; Primary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Hosiery</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Slim Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Lagging-Red-Seth Vidya Mandir High School-SVM RED BLOOMERS', 'Lagging-Red-Seth Vidya Mandir High School-SVM RED BLOOMERS', 'Lagging-Red-Seth Vidya Mandir High School-SVM RED BLOOMERS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(89, 38, 21, 2, NULL, 5, 'unisex', NULL, 'SVM BLUE DENIM JEANS', 'svm-blue-denim-jeans', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Full Jeans</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Denim</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Slim Fit</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Length- Long</span>&nbsp;</p>\n<p>Class- Secondory Section</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Full Pant-Blue-Seth Vidya Mandir High School-SVM BLUE DENIM JEANS', 'Full Pant-Blue-Seth Vidya Mandir High School-SVM BLUE DENIM JEANS', 'Full Pant-Blue-Seth Vidya Mandir High School-SVM BLUE DENIM JEANS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(90, 38, 21, 2, NULL, 5, 'unisex', NULL, 'SVM BLACK DENIM JEANS', 'svm-black-denim-jeans', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Full Jeans</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Denim</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Slim Fit</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Length- Long</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Full Pant-Black-Seth Vidya Mandir High School-SVM BLACK DENIM JEANS', 'Full Pant-Black-Seth Vidya Mandir High School-SVM BLACK DENIM JEANS', 'Full Pant-Black-Seth Vidya Mandir High School-SVM BLACK DENIM JEANS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(91, 38, 21, 2, NULL, 5, 'unisex', NULL, 'SVM NAVY BLUE DENIM JEANS', 'svm-navy-blue-denim-jeans', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand- Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Type - Full Jeans</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Denim</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Secondary Section</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit - Slim Fit</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Length- Long</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Full Pant-Navy blue-Seth Vidya Mandir High School-SVM NAVY BLUE DENIM JEANS', 'Full Pant-Navy blue-Seth Vidya Mandir High School-SVM NAVY BLUE DENIM JEANS', 'Full Pant-Navy blue-Seth Vidya Mandir High School-SVM NAVY BLUE DENIM JEANS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(92, 38, 12, 1, NULL, 5, 'unisex', NULL, 'TVM WHITE & BLUE SOCKS', 'tvm-white-blue-socks', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Primary &amp; Secondary  Section</span></p>\n<p><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Description - Good Comfort To Toe</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Socks-White and Blue -Thakur Vidya Mandir High School & Junior College-TVM WHITE & BLUE SOCKS', 'Socks-White and Blue -Thakur Vidya Mandir High School & Junior College-TVM WHITE & BLUE SOCKS', 'Socks-White and Blue -Thakur Vidya Mandir High School & Junior College-TVM WHITE & BLUE SOCKS', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(93, 38, 2, 1, NULL, 5, 'unisex', NULL, 'TVM WHITE FULL SLEEVE T-SHIRT', 'tvm-white-full-sleeve-t-shirt', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand-  Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Neck- Round Neck</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve- Full Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Fit- Regular Fit</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'T-Shirt-White & Black-Thakur Vidya Mandir High School & Junior College-TVM WHITE FULL SLEEVE T-SHIRT', 'T-Shirt-White & Black-Thakur Vidya Mandir High School & Junior College-TVM WHITE FULL SLEEVE T-SHIRT', 'T-Shirt-White & Black-Thakur Vidya Mandir High School & Junior College-TVM WHITE FULL SLEEVE T-SHIRT', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(94, 38, 16, 5, 1, 1, 'unisex', '', 'TVM WHITE BLACK TRACKPANT', 'tvm-white-black-trackpant-94', '', 1, 3, 1, '1', '<p xss=removed><span segoe=\"\" xss=removed ui=\"\">Brand- Varitty</span></p>\r\n\r\n<p xss=removed><span segoe=\"\" xss=removed ui=\"\">Material- Knitted</span></p>\r\n\r\n<p xss=removed><span segoe=\"\" xss=removed ui=\"\">Class-Pre- Primary, Primary & Secondary Section</span></p>\r\n\r\n<p xss=removed><span segoe=\"\" xss=removed ui=\"\">Fit -Loose Fit</span></p>', '', '', '', NULL, 1, NULL, 1.00, 1.00, 1.00, 1.00, NULL, 5.00, NULL, '61112000', 'Trackpant-White & Black-Thakur Vidya Mandir High School & Junior College-TVM WHITE BLACK TRACKPANT', 'Trackpant-White & Black-Thakur Vidya Mandir High School & Junior College-TVM WHITE BLACK TRACKPANT', 'Trackpant-White & Black-Thakur Vidya Mandir High School & Junior College-TVM WHITE BLACK TRACKPANT', 'active', 0, 0, '2026-01-21 16:37:54', '2026-01-22 14:07:47', NULL, NULL),
(95, 38, 10, 0, NULL, NULL, 'unisex', NULL, 'SPRING BUDS GREY ZIPPER HOODIE', 'spring-buds-grey-zipper-hoodie', NULL, 1, 3, 1, '1', '<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Brand - Varitty</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Class- Pre -Primary</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Material- Knitted</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Sleeve- Full Sleeve</span></p>\n<p style=\"text-align:start;\"><span style=\"color: rgb(34,34,34);background-color: rgb(255,255,255);font-size: 14px;font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"PingFang SC\", \"Hiragino Sans GB\", \"Microsoft YaHei\", \"Helvetica Neue\", Helvetica, Arial, sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol;\">Purpose- Winter Hoodie  With Zipper</span>&nbsp;</p>\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5.00, NULL, '61112000', 'Hoodie-Grey-Spring Buds International (SVIS)-SPRING BUDS GREY ZIPPER HOODIE', 'Hoodie-Grey-Spring Buds International (SVIS)-SPRING BUDS GREY ZIPPER HOODIE', 'Hoodie-Grey-Spring Buds International (SVIS)-SPRING BUDS GREY ZIPPER HOODIE', 'active', 1, 0, '2026-01-21 16:37:54', '2026-01-21 18:07:23', NULL, NULL),
(96, 38, 11, 5, 1, 1, 'male', 'Black ', 'Baby Frocks', 'baby-frocks-96', '74123', 1, 7, 30, 'India', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>', '', '', '', NULL, 1, NULL, 1.00, 1.00, 1.00, 1.00, NULL, 12.00, NULL, '74123', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'active', 0, 0, '2026-01-21 18:14:40', '2026-01-22 11:45:30', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `erp_uniform_images`
--

CREATE TABLE `erp_uniform_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `uniform_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_uniforms table',
  `image_path` varchar(500) NOT NULL,
  `image_order` int(11) NOT NULL DEFAULT 0,
  `is_main` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Main Image',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Uniform Images';

--
-- Dumping data for table `erp_uniform_images`
--

INSERT INTO `erp_uniform_images` (`id`, `uniform_id`, `image_path`, `image_order`, `is_main`, `created_at`) VALUES
(1, 1, 'uploads/products/2023/06/01/img_64788fea39ec69-55770084-38886916.jpg', 0, 1, '2026-01-21 16:37:54'),
(2, 1, 'uploads/products/2023/06/01/img_64788feb459698-03440737-85874279.jpg', 1, 0, '2026-01-21 16:37:54'),
(3, 1, 'uploads/products/2023/06/01/img_64788fec52e8a6-94491155-54811160.jpg', 2, 0, '2026-01-21 16:37:54'),
(4, 2, 'uploads/products/2023/06/02/img_64798bb1821257-09364455-80076024.jpg', 0, 1, '2026-01-21 16:37:54'),
(5, 2, 'uploads/products/2023/06/02/img_64798bb28bdce1-29840692-48042277.jpg', 1, 0, '2026-01-21 16:37:54'),
(6, 2, 'uploads/products/2023/06/02/img_64798bb3a117a4-71696941-13652040.jpg', 2, 0, '2026-01-21 16:37:54'),
(7, 3, 'uploads/products/2023/06/02/img_64798d29a9d485-64133759-94901929.jpg', 0, 1, '2026-01-21 16:37:54'),
(8, 3, 'uploads/products/2023/06/02/img_64798d2ab71102-78868106-10036243.jpg', 1, 0, '2026-01-21 16:37:54'),
(9, 3, 'uploads/products/2023/06/02/img_64798d2bc47a78-35021923-30482448.jpg', 2, 0, '2026-01-21 16:37:54'),
(10, 4, 'uploads/products/2023/06/02/img_64799064f25895-26814231-12654083.jpg', 0, 1, '2026-01-21 16:37:54'),
(11, 4, 'uploads/products/2023/06/02/img_647990660b9034-14458973-56000507.jpg', 1, 0, '2026-01-21 16:37:54'),
(12, 4, 'uploads/products/2023/06/02/img_64799067199304-02450876-50834551.jpg', 2, 0, '2026-01-21 16:37:54'),
(13, 5, 'uploads/products/2023/06/02/img_64799c717daba1-81442020-44039572.jpg', 0, 1, '2026-01-21 16:37:54'),
(14, 5, 'uploads/products/2023/06/02/img_64799c72688746-35964031-58523922.jpg', 1, 0, '2026-01-21 16:37:54'),
(15, 6, 'uploads/products/2023/06/02/img_6479ae8da68843-58260499-20054340.jpg', 0, 1, '2026-01-21 16:37:54'),
(16, 6, 'uploads/products/2023/06/02/img_6479ae8eab70a3-44843024-88435167.jpg', 1, 0, '2026-01-21 16:37:54'),
(17, 6, 'uploads/products/2023/06/02/img_6479ae8fa8cef5-38057818-40602167.jpg', 2, 0, '2026-01-21 16:37:54'),
(18, 7, 'uploads/products/2023/06/03/img_647b00f849d9a5-17476556-50454164.jpg', 0, 1, '2026-01-21 16:37:54'),
(19, 7, 'uploads/products/2023/06/03/img_647b00f9542c61-52844817-17578491.jpg', 1, 0, '2026-01-21 16:37:54'),
(20, 7, 'uploads/products/2023/06/03/img_647b00fa6d8838-55085110-88621138.jpg', 2, 0, '2026-01-21 16:37:54'),
(21, 8, 'uploads/products/2023/06/03/img_647b065befd040-63232576-19369113.jpg', 0, 1, '2026-01-21 16:37:54'),
(22, 8, 'uploads/products/2023/06/03/img_647b065d074ee3-24541223-18891358.jpg', 1, 0, '2026-01-21 16:37:54'),
(23, 8, 'uploads/products/2023/06/03/img_647b065e0c85f1-85530923-80350274.jpg', 2, 0, '2026-01-21 16:37:54'),
(24, 9, 'uploads/products/2023/06/03/img_647b0aa10eaf08-68425584-88353062.jpg', 0, 1, '2026-01-21 16:37:54'),
(25, 9, 'uploads/products/2023/06/03/img_647b0aa21a6da2-54500425-48420367.jpg', 1, 0, '2026-01-21 16:37:54'),
(26, 9, 'uploads/products/2023/06/03/img_647b0aa3267ea0-82904262-54041105.jpg', 2, 0, '2026-01-21 16:37:54'),
(27, 10, 'uploads/products/2023/06/03/img_647b0fcc479950-37980808-65650038.jpg', 0, 1, '2026-01-21 16:37:54'),
(28, 10, 'uploads/products/2023/06/03/img_647b0fcd598e98-73189795-95267710.jpg', 1, 0, '2026-01-21 16:37:54'),
(29, 10, 'uploads/products/2023/06/03/img_647b0fce64c026-91278799-68556339.jpg', 2, 0, '2026-01-21 16:37:54'),
(30, 11, 'uploads/products/2023/06/03/img_647b2d9b35a0d3-25354412-46356698.jpg', 0, 1, '2026-01-21 16:37:54'),
(31, 11, 'uploads/products/2023/06/03/img_647b2d9c3005d7-81042565-77600375.jpg', 1, 0, '2026-01-21 16:37:54'),
(32, 11, 'uploads/products/2023/06/03/img_647b2d9d466df8-87594350-34302244.jpg', 2, 0, '2026-01-21 16:37:54'),
(33, 12, 'uploads/products/2023/06/03/img_647b320042a114-50386495-38606945.jpg', 0, 1, '2026-01-21 16:37:54'),
(34, 12, 'uploads/products/2023/06/03/img_647b3201614637-58591088-46300438.jpg', 1, 0, '2026-01-21 16:37:54'),
(35, 12, 'uploads/products/2023/06/03/img_647b3202909327-10925353-59372936.jpg', 2, 0, '2026-01-21 16:37:54'),
(36, 13, 'uploads/products/2023/06/05/img_647da0a154afe3-63201756-43881941.jpg', 0, 1, '2026-01-21 16:37:54'),
(37, 13, 'uploads/products/2023/06/05/img_647da0a2638b50-04936149-16251829.jpg', 1, 0, '2026-01-21 16:37:54'),
(38, 13, 'uploads/products/2023/06/05/img_647da0a37bf8a1-74366608-21435511.jpg', 2, 0, '2026-01-21 16:37:54'),
(39, 14, 'uploads/products/2023/06/05/img_647dc1d2813f50-22929132-30935543.jpg', 0, 1, '2026-01-21 16:37:54'),
(40, 14, 'uploads/products/2023/06/05/img_647dc1d3916924-38372650-29515329.jpg', 1, 0, '2026-01-21 16:37:54'),
(41, 14, 'uploads/products/2023/06/05/img_647dc1d4a71d48-00554389-48867084.jpg', 2, 0, '2026-01-21 16:37:54'),
(42, 15, 'uploads/products/2023/06/05/img_647dc5f17c0417-64086866-12925863.jpg', 0, 1, '2026-01-21 16:37:54'),
(43, 15, 'uploads/products/2023/06/05/img_647dc5f2858029-50274897-60742814.jpg', 1, 0, '2026-01-21 16:37:54'),
(44, 15, 'uploads/products/2023/06/05/img_647dc5f3937a01-01779875-90761587.jpg', 2, 0, '2026-01-21 16:37:54'),
(45, 16, 'uploads/products/2023/06/05/img_647dd6a0d0eb38-53986641-39030071.jpg', 0, 1, '2026-01-21 16:37:54'),
(46, 16, 'uploads/products/2023/06/05/img_647dd6a1e15bd7-76433820-46158555.jpg', 1, 0, '2026-01-21 16:37:54'),
(47, 16, 'uploads/products/2023/06/05/img_647dd6a303aa35-73814884-63734748.jpg', 2, 0, '2026-01-21 16:37:54'),
(48, 17, 'uploads/products/2023/06/05/img_647dd81e789bf4-13859577-99528345.jpg', 0, 1, '2026-01-21 16:37:54'),
(49, 17, 'uploads/products/2023/06/05/img_647dd81f81daa3-94988548-23070589.jpg', 1, 0, '2026-01-21 16:37:54'),
(50, 17, 'uploads/products/2023/06/05/img_647dd8209244b7-36120452-66952453.jpg', 2, 0, '2026-01-21 16:37:54'),
(51, 18, 'uploads/products/2023/06/05/img_647de04702c030-45271589-16721490.jpg', 0, 1, '2026-01-21 16:37:54'),
(52, 18, 'uploads/products/2023/06/05/img_647de047ad26b1-87232595-28298154.jpg', 1, 0, '2026-01-21 16:37:54'),
(53, 18, 'uploads/products/2023/06/05/img_647de04868f137-59723233-61676145.jpg', 2, 0, '2026-01-21 16:37:54'),
(54, 19, 'uploads/products/2023/06/06/img_647ed31254e140-16051082-35240074.jpg', 0, 1, '2026-01-21 16:37:54'),
(55, 19, 'uploads/products/2023/06/06/img_647ef1a742b188-11116821-11141295.jpg', 1, 0, '2026-01-21 16:37:54'),
(56, 19, 'uploads/products/2023/06/06/img_647ef1a8506034-92016165-44139602.jpg', 2, 0, '2026-01-21 16:37:54'),
(57, 20, 'uploads/products/2023/06/06/img_647ef507c5c241-21582928-21625135.jpg', 0, 1, '2026-01-21 16:37:54'),
(58, 20, 'uploads/products/2023/06/06/img_647ef508d8da11-19738167-76461479.jpg', 1, 0, '2026-01-21 16:37:54'),
(59, 20, 'uploads/products/2023/06/06/img_647ef509ea0280-73603994-28441744.jpg', 2, 0, '2026-01-21 16:37:54'),
(60, 21, 'uploads/products/2023/06/06/img_647ef8ca0a5c01-84981184-46059235.jpg', 0, 1, '2026-01-21 16:37:54'),
(61, 21, 'uploads/products/2023/06/06/img_647ef8cb0eaa23-77300458-73955056.jpg', 1, 0, '2026-01-21 16:37:54'),
(62, 21, 'uploads/products/2023/06/06/img_647ef8cc1110a4-33099492-57580820.jpg', 2, 0, '2026-01-21 16:37:54'),
(63, 22, 'uploads/products/2023/06/06/img_647efb17064a81-35806532-35767619.jpg', 0, 1, '2026-01-21 16:37:54'),
(64, 22, 'uploads/products/2023/06/06/img_647efb17e47712-00281262-87154843.jpg', 1, 0, '2026-01-21 16:37:54'),
(65, 22, 'uploads/products/2023/06/06/img_647efb18d4b2c3-08912471-98591944.jpg', 2, 0, '2026-01-21 16:37:54'),
(66, 23, 'uploads/products/2023/06/06/img_647efc67b90a05-26030059-79407463.jpg', 0, 1, '2026-01-21 16:37:54'),
(67, 23, 'uploads/products/2023/06/06/img_647efc68a1f0a3-43338509-12943587.jpg', 1, 0, '2026-01-21 16:37:54'),
(68, 23, 'uploads/products/2023/06/06/img_647efc6990b929-25482384-75459071.jpg', 2, 0, '2026-01-21 16:37:54'),
(69, 23, 'uploads/products/2023/06/06/img_647efdc5b83710-13184715-90549556.jpg', 3, 0, '2026-01-21 16:37:54'),
(70, 24, 'uploads/products/2023/06/06/img_647efd5e9e7487-95097293-71436441.jpg', 0, 1, '2026-01-21 16:37:54'),
(71, 24, 'uploads/products/2023/06/06/img_647efd5f8ed222-06283655-77191628.jpg', 1, 0, '2026-01-21 16:37:54'),
(72, 24, 'uploads/products/2023/06/06/img_647efd607db8b0-98426204-91331739.jpg', 2, 0, '2026-01-21 16:37:54'),
(73, 25, 'uploads/products/2023/06/06/img_647f27be252d87-21452586-60757400.jpg', 0, 1, '2026-01-21 16:37:54'),
(74, 25, 'uploads/products/2023/06/06/img_647f27bf2542c4-08129917-11251718.jpg', 1, 0, '2026-01-21 16:37:54'),
(75, 25, 'uploads/products/2023/06/06/img_647f27c02367d0-70802213-54387366.jpg', 2, 0, '2026-01-21 16:37:54'),
(76, 26, 'uploads/products/2023/06/08/img_64817ab4e13d53-40071622-45884597.jpg', 0, 1, '2026-01-21 16:37:54'),
(77, 26, 'uploads/products/2023/06/08/img_64817ab5c8e2b9-19591615-30930459.jpg', 1, 0, '2026-01-21 16:37:54'),
(78, 26, 'uploads/products/2023/06/08/img_64817ab6b0d203-96389286-19429166.jpg', 2, 0, '2026-01-21 16:37:54'),
(79, 27, 'uploads/products/2023/06/08/img_64817c36353d26-18951171-51651501.jpg', 0, 1, '2026-01-21 16:37:54'),
(80, 27, 'uploads/products/2023/06/08/img_64817c37167be5-49405329-78594896.jpg', 1, 0, '2026-01-21 16:37:54'),
(81, 27, 'uploads/products/2023/06/08/img_64817c38031951-59426083-68455269.jpg', 2, 0, '2026-01-21 16:37:54'),
(82, 28, 'uploads/products/2023/06/08/img_64817ed6c08c99-69977477-12803981.jpg', 0, 1, '2026-01-21 16:37:54'),
(83, 28, 'uploads/products/2023/06/08/img_64817ed81208b7-33764502-14811580.jpg', 1, 0, '2026-01-21 16:37:54'),
(84, 28, 'uploads/products/2023/06/08/img_64817ed8e5e135-60480487-10923980.jpg', 2, 0, '2026-01-21 16:37:54'),
(85, 29, 'uploads/products/2023/06/08/img_64819952ca1578-67810722-39805012.jpg', 0, 1, '2026-01-21 16:37:54'),
(86, 29, 'uploads/products/2023/06/08/img_648199541dfda0-59943946-35509413.jpg', 1, 0, '2026-01-21 16:37:54'),
(87, 29, 'uploads/products/2023/06/08/img_64819954f2b9f2-82322632-95190019.jpg', 2, 0, '2026-01-21 16:37:54'),
(88, 30, 'uploads/products/2023/06/08/img_6481a2ac3386d5-93797999-71808001.jpg', 0, 1, '2026-01-21 16:37:54'),
(89, 30, 'uploads/products/2023/06/08/img_6481a2ad412ba8-38689406-92762848.jpg', 1, 0, '2026-01-21 16:37:54'),
(90, 30, 'uploads/products/2023/06/08/img_6481a2ae554786-85013205-81663364.jpg', 2, 0, '2026-01-21 16:37:54'),
(91, 31, 'uploads/products/2023/06/08/img_6481b5cc227805-42048809-17806254.jpg', 0, 1, '2026-01-21 16:37:54'),
(92, 31, 'uploads/products/2023/06/08/img_6481b5cd5f5527-43692470-27145699.jpg', 1, 0, '2026-01-21 16:37:54'),
(93, 31, 'uploads/products/2023/06/08/img_6481b5ce689f84-32784313-27767797.jpg', 2, 0, '2026-01-21 16:37:54'),
(94, 32, 'uploads/products/2023/06/09/img_648323067c8f54-54521254-61033553.jpg', 0, 1, '2026-01-21 16:37:54'),
(95, 32, 'uploads/products/2023/06/09/img_648323077f6bc2-79475705-73327132.jpg', 1, 0, '2026-01-21 16:37:54'),
(96, 32, 'uploads/products/2023/06/09/img_6483230889c8a3-81762316-43570568.jpg', 2, 0, '2026-01-21 16:37:54'),
(97, 33, 'uploads/products/2023/06/10/img_648437e5195a67-01242646-74481676.jpg', 0, 1, '2026-01-21 16:37:54'),
(98, 33, 'uploads/products/2023/06/10/img_648437e61b99f0-07441632-23145463.jpg', 1, 0, '2026-01-21 16:37:54'),
(99, 33, 'uploads/products/2023/06/10/img_648437e7296210-53079619-34281884.jpg', 2, 0, '2026-01-21 16:37:54'),
(100, 34, 'uploads/products/2023/06/10/img_64843ace9921f7-81649178-95547907.jpg', 0, 1, '2026-01-21 16:37:54'),
(101, 34, 'uploads/products/2023/06/10/img_64843acf9ac6a7-05041936-32767846.jpg', 1, 0, '2026-01-21 16:37:54'),
(102, 34, 'uploads/products/2023/06/10/img_64843ad0a57bd5-13315180-98696706.jpg', 2, 0, '2026-01-21 16:37:54'),
(103, 35, 'uploads/products/2023/06/12/img_6486e96be4f440-08377894-95660431.jpg', 0, 1, '2026-01-21 16:37:54'),
(104, 35, 'uploads/products/2023/06/12/img_6486e96ce69096-34470128-96374755.jpg', 1, 0, '2026-01-21 16:37:54'),
(105, 35, 'uploads/products/2023/06/12/img_6486e96dec4626-54043906-93565985.jpg', 2, 0, '2026-01-21 16:37:54'),
(106, 36, 'uploads/products/2023/06/17/img_648da029cd9be2-26553991-68372385.jpg', 0, 1, '2026-01-21 16:37:54'),
(107, 36, 'uploads/products/2023/06/17/img_648da02aeaf7f6-76395950-74835512.jpg', 1, 0, '2026-01-21 16:37:54'),
(108, 36, 'uploads/products/2023/06/17/img_648da02c132764-73149436-34141303.jpg', 2, 0, '2026-01-21 16:37:54'),
(109, 37, 'uploads/products/2023/06/17/img_648da678e16763-43076717-68708268.jpg', 0, 1, '2026-01-21 16:37:54'),
(110, 37, 'uploads/products/2023/06/17/img_648da679da6aa7-14488744-23730185.jpg', 1, 0, '2026-01-21 16:37:54'),
(111, 37, 'uploads/products/2023/06/17/img_648da67ada32a8-00950578-53874995.jpg', 2, 0, '2026-01-21 16:37:54'),
(112, 38, 'uploads/products/2023/06/17/img_648da8f60805a0-14141112-93796812.jpg', 0, 1, '2026-01-21 16:37:54'),
(113, 38, 'uploads/products/2023/06/17/img_648da8f728ff07-51084082-73948318.jpg', 1, 0, '2026-01-21 16:37:54'),
(114, 38, 'uploads/products/2023/06/17/img_648da8f8519a97-87780532-50925408.jpg', 2, 0, '2026-01-21 16:37:54'),
(115, 39, 'uploads/products/2023/06/29/img_649d208be6b293-26987309-74106719.jpg', 0, 1, '2026-01-21 16:37:54'),
(116, 39, 'uploads/products/2023/06/29/img_649d208cc29e82-10453651-29936102.jpg', 1, 0, '2026-01-21 16:37:54'),
(117, 39, 'uploads/products/2023/06/29/img_649d208dadfae3-81515893-34553775.jpg', 2, 0, '2026-01-21 16:37:54'),
(118, 40, 'uploads/products/2023/06/30/img_649e9a69e8c738-36272493-88277430.jpg', 0, 1, '2026-01-21 16:37:54'),
(119, 40, 'uploads/products/2023/06/30/img_649e9a6b081f96-67993891-30142252.jpg', 1, 0, '2026-01-21 16:37:54'),
(120, 40, 'uploads/products/2023/06/30/img_649e9a6c136fa7-50274430-66075020.jpg', 2, 0, '2026-01-21 16:37:54'),
(121, 41, 'uploads/products/2023/06/30/img_649e9de3d89b24-53497342-84580048.jpg', 0, 1, '2026-01-21 16:37:54'),
(122, 41, 'uploads/products/2023/06/30/img_649e9de4f0d823-08748259-92022244.jpg', 1, 0, '2026-01-21 16:37:54'),
(123, 41, 'uploads/products/2023/06/30/img_649e9de608f1b1-31230087-23050890.jpg', 2, 0, '2026-01-21 16:37:54'),
(124, 42, 'uploads/products/2023/07/01/img_649ff2a66a42a7-37245874-77676083.jpg', 0, 1, '2026-01-21 16:37:54'),
(125, 42, 'uploads/products/2023/07/01/img_649ff2a775d849-70722444-24221699.jpg', 1, 0, '2026-01-21 16:37:54'),
(126, 42, 'uploads/products/2023/07/01/img_649ff2a8cd1fa4-27095386-36663166.jpg', 2, 0, '2026-01-21 16:37:54'),
(127, 43, 'uploads/products/2023/07/03/img_64a269969b4579-25433781-65087189.jpg', 0, 1, '2026-01-21 16:37:54'),
(128, 43, 'uploads/products/2023/07/03/img_64a26997937501-31838645-13207120.jpg', 1, 0, '2026-01-21 16:37:54'),
(129, 43, 'uploads/products/2023/07/03/img_64a269989a26f5-15292466-70818916.jpg', 2, 0, '2026-01-21 16:37:54'),
(130, 45, 'uploads/products/2023/07/03/img_64a28dd3dbe720-06611347-53811142.jpg', 0, 1, '2026-01-21 16:37:54'),
(131, 45, 'uploads/products/2023/07/03/img_64a28dd4ebdb87-25660475-89179028.jpg', 1, 0, '2026-01-21 16:37:54'),
(132, 45, 'uploads/products/2023/07/03/img_64a28dd6082c57-55773711-72080694.jpg', 2, 0, '2026-01-21 16:37:54'),
(133, 44, 'uploads/products/2023/07/03/img_64a28e8b4a05c7-63252833-23992336.jpg', 0, 1, '2026-01-21 16:37:54'),
(134, 44, 'uploads/products/2023/07/03/img_64a28e8c563e35-54489171-60594464.jpg', 1, 0, '2026-01-21 16:37:54'),
(135, 44, 'uploads/products/2023/07/03/img_64a28e8d622433-10330930-94267404.jpg', 2, 0, '2026-01-21 16:37:54'),
(136, 46, 'uploads/products/2023/07/03/img_64a29052a4d021-56674415-41243661.jpg', 0, 1, '2026-01-21 16:37:54'),
(137, 46, 'uploads/products/2023/07/03/img_64a29053b8cc64-59542209-48204114.jpg', 1, 0, '2026-01-21 16:37:54'),
(138, 46, 'uploads/products/2023/07/03/img_64a29054d054a3-67063806-69014179.jpg', 2, 0, '2026-01-21 16:37:54'),
(139, 47, 'uploads/products/2023/07/03/img_64a290f029f340-07202596-92533223.jpg', 0, 1, '2026-01-21 16:37:54'),
(140, 47, 'uploads/products/2023/07/03/img_64a290f145d884-58601181-60252852.jpg', 1, 0, '2026-01-21 16:37:54'),
(141, 47, 'uploads/products/2023/07/03/img_64a290f259a569-05949568-86327173.jpg', 2, 0, '2026-01-21 16:37:54'),
(142, 48, 'uploads/products/2023/07/03/img_64a2939789ffc5-89315846-77789259.jpg', 0, 1, '2026-01-21 16:37:54'),
(143, 48, 'uploads/products/2023/07/03/img_64a293989ac4f9-76172951-97398124.jpg', 1, 0, '2026-01-21 16:37:54'),
(144, 48, 'uploads/products/2023/07/03/img_64a29399af2eb0-81365229-63483062.jpg', 2, 0, '2026-01-21 16:37:54'),
(145, 49, 'uploads/products/2023/07/03/img_64a29494da3190-98732349-82744823.jpg', 0, 1, '2026-01-21 16:37:54'),
(146, 49, 'uploads/products/2023/07/03/img_64a2949601ddd6-04274198-75974199.jpg', 1, 0, '2026-01-21 16:37:54'),
(147, 50, 'uploads/products/2023/07/03/img_64a2963bd6bf99-46502852-67375503.jpg', 0, 1, '2026-01-21 16:37:54'),
(148, 50, 'uploads/products/2023/07/03/img_64a2963d07ed11-96500654-25701632.jpg', 1, 0, '2026-01-21 16:37:54'),
(149, 50, 'uploads/products/2023/07/03/img_64a2963e2ac177-72924571-92056897.jpg', 2, 0, '2026-01-21 16:37:54'),
(150, 51, 'uploads/products/2023/07/03/img_64a2bfc15a66c8-11719790-10343332.jpg', 0, 1, '2026-01-21 16:37:54'),
(151, 51, 'uploads/products/2023/07/03/img_64a2bfc26665f1-98688756-27185597.jpg', 1, 0, '2026-01-21 16:37:54'),
(152, 51, 'uploads/products/2023/07/03/img_64a2bfc3752eb3-66135902-50891634.jpg', 2, 0, '2026-01-21 16:37:54'),
(153, 52, 'uploads/products/2023/07/03/img_64a2c6d9da83c9-24357814-76833588.jpg', 0, 1, '2026-01-21 16:37:54'),
(154, 52, 'uploads/products/2023/07/03/img_64a2c6daf24a93-97128324-11526209.jpg', 1, 0, '2026-01-21 16:37:54'),
(155, 52, 'uploads/products/2023/07/03/img_64a2c6dc1a05b3-81614235-75227154.jpg', 2, 0, '2026-01-21 16:37:54'),
(156, 53, 'uploads/products/2023/07/04/img_64a3c0ffa88213-90863598-60268542.jpg', 0, 1, '2026-01-21 16:37:54'),
(157, 53, 'uploads/products/2023/07/04/img_64a3c1009ecb36-15292964-22320447.jpg', 1, 0, '2026-01-21 16:37:54'),
(158, 53, 'uploads/products/2023/07/04/img_64a3c10198aba8-43009556-53386528.jpg', 2, 0, '2026-01-21 16:37:54'),
(159, 54, 'uploads/products/2023/07/05/img_64a509118d4737-27802943-89330591.jpg', 0, 1, '2026-01-21 16:37:54'),
(160, 54, 'uploads/products/2023/07/05/img_64a509128a18d6-60345964-50972111.jpg', 1, 0, '2026-01-21 16:37:54'),
(161, 54, 'uploads/products/2023/07/05/img_64a50913906be9-84458036-66371291.jpg', 2, 0, '2026-01-21 16:37:54'),
(162, 55, 'uploads/products/2023/07/05/img_64a50b52341c86-62674743-57623626.jpg', 0, 1, '2026-01-21 16:37:54'),
(163, 55, 'uploads/products/2023/07/05/img_64a50b532cbdf2-08974631-18684059.jpg', 1, 0, '2026-01-21 16:37:54'),
(164, 55, 'uploads/products/2023/07/05/img_64a50b542ce558-61675585-40219260.jpg', 2, 0, '2026-01-21 16:37:54'),
(165, 56, 'uploads/products/2023/07/05/img_64a50eae9dff23-56206098-31696596.jpg', 0, 1, '2026-01-21 16:37:54'),
(166, 56, 'uploads/products/2023/07/05/img_64a50eaf70c7d2-29010057-23995156.jpg', 1, 0, '2026-01-21 16:37:54'),
(167, 57, 'uploads/products/2023/07/05/img_64a539c33b7db3-70480472-62059032.jpg', 0, 1, '2026-01-21 16:37:54'),
(168, 57, 'uploads/products/2023/07/05/img_64a539c443cb83-04029638-70934768.jpg', 1, 0, '2026-01-21 16:37:54'),
(169, 57, 'uploads/products/2023/07/05/img_64a539c54dfe20-93246423-30891062.jpg', 2, 0, '2026-01-21 16:37:54'),
(170, 58, 'uploads/products/2023/07/05/img_64a53cd39e4329-12811479-92182683.jpg', 0, 1, '2026-01-21 16:37:54'),
(171, 58, 'uploads/products/2023/07/05/img_64a53cd482fd34-30825127-30948778.jpg', 1, 0, '2026-01-21 16:37:54'),
(172, 58, 'uploads/products/2023/07/05/img_64a53cd5700a81-44228957-41962651.jpg', 2, 0, '2026-01-21 16:37:54'),
(173, 59, 'uploads/products/2023/07/05/img_64a55c317ce601-01004981-45955654.jpg', 0, 1, '2026-01-21 16:37:54'),
(174, 59, 'uploads/products/2023/07/05/img_64a55c328d4225-40235326-34578581.jpg', 1, 0, '2026-01-21 16:37:54'),
(175, 59, 'uploads/products/2023/07/05/img_64a55c339f7c36-49543531-16751676.jpg', 2, 0, '2026-01-21 16:37:54'),
(176, 60, 'uploads/products/2023/07/07/img_64a7a9fc769315-99937728-52756698.jpg', 0, 1, '2026-01-21 16:37:54'),
(177, 60, 'uploads/products/2023/07/07/img_64a7a9fd8c7274-03731343-28066232.jpg', 1, 0, '2026-01-21 16:37:54'),
(178, 60, 'uploads/products/2023/07/07/img_64a7a9fea34a46-59039042-11495429.jpg', 2, 0, '2026-01-21 16:37:54'),
(179, 61, 'uploads/products/2023/07/07/img_64a7ac143ae733-58426347-79576163.jpg', 0, 1, '2026-01-21 16:37:54'),
(180, 61, 'uploads/products/2023/07/07/img_64a7ac154a7e51-72032254-37345363.jpg', 1, 0, '2026-01-21 16:37:54'),
(181, 61, 'uploads/products/2023/07/07/img_64a7ac165f30d7-54584751-59242067.jpg', 2, 0, '2026-01-21 16:37:54'),
(182, 62, 'uploads/products/2023/07/07/img_64a7af08ddf769-37955481-75907571.jpg', 0, 1, '2026-01-21 16:37:54'),
(183, 62, 'uploads/products/2023/07/07/img_64a7af0a052dc7-13867880-37628399.jpg', 1, 0, '2026-01-21 16:37:54'),
(184, 62, 'uploads/products/2023/07/07/img_64a7af0b1829f7-58759465-32831279.jpg', 2, 0, '2026-01-21 16:37:54'),
(185, 63, 'uploads/products/2023/07/07/img_64a7afc3392895-67467665-71408702.jpg', 0, 1, '2026-01-21 16:37:54'),
(186, 63, 'uploads/products/2023/07/07/img_64a7afc45fba32-39106908-45535360.jpg', 1, 0, '2026-01-21 16:37:54'),
(187, 63, 'uploads/products/2023/07/07/img_64a7afc5819433-93687986-73097256.jpg', 2, 0, '2026-01-21 16:37:54'),
(188, 64, 'uploads/products/2023/07/07/img_64a7b1e7c4a184-22597779-22263484.jpg', 0, 1, '2026-01-21 16:37:54'),
(189, 64, 'uploads/products/2023/07/07/img_64a7b1e8a89cb8-79326394-54364611.jpg', 1, 0, '2026-01-21 16:37:54'),
(190, 64, 'uploads/products/2023/07/07/img_64a7b1e98e8b91-85462085-43975149.jpg', 2, 0, '2026-01-21 16:37:54'),
(191, 65, 'uploads/products/2023/07/07/img_64a7b79d70d124-62005142-70294938.jpg', 0, 1, '2026-01-21 16:37:54'),
(192, 65, 'uploads/products/2023/07/07/img_64a7b79e11be00-41900750-11752863.jpg', 1, 0, '2026-01-21 16:37:54'),
(193, 66, 'uploads/products/2023/07/07/img_64a7b99adec8d4-79196384-22891432.jpg', 0, 1, '2026-01-21 16:37:54'),
(194, 67, 'uploads/products/2023/07/07/img_64a7d9e1af9993-00255723-51739174.jpg', 0, 1, '2026-01-21 16:37:54'),
(195, 67, 'uploads/products/2023/07/07/img_64a7d9e2b1f4a5-09493144-78053753.jpg', 1, 0, '2026-01-21 16:37:54'),
(196, 67, 'uploads/products/2023/07/07/img_64a7d9e3b88241-31263924-55157767.jpg', 2, 0, '2026-01-21 16:37:54'),
(197, 68, 'uploads/products/2023/07/07/img_64a7e2e5b432d2-03369726-99085696.jpg', 0, 1, '2026-01-21 16:37:54'),
(198, 68, 'uploads/products/2023/07/07/img_64a7e2e6c07db7-42359190-27577649.jpg', 1, 0, '2026-01-21 16:37:54'),
(199, 68, 'uploads/products/2023/07/07/img_64a7e2e7cfe179-65010088-92847092.jpg', 2, 0, '2026-01-21 16:37:54'),
(200, 69, 'uploads/products/2023/07/07/img_64a7ea174acd68-23048632-50314204.jpg', 0, 1, '2026-01-21 16:37:54'),
(201, 69, 'uploads/products/2023/07/07/img_64a7ea182e9243-27998799-68995642.jpg', 1, 0, '2026-01-21 16:37:54'),
(202, 69, 'uploads/products/2023/07/07/img_64a7ea191d0ca7-13530904-27548643.jpg', 2, 0, '2026-01-21 16:37:54'),
(203, 70, 'uploads/products/2023/07/07/img_64a7f2ca8dfff6-93046032-10308590.jpg', 0, 1, '2026-01-21 16:37:54'),
(204, 70, 'uploads/products/2023/07/07/img_64a7f2cb891b83-32702245-22152011.jpg', 1, 0, '2026-01-21 16:37:54'),
(205, 70, 'uploads/products/2023/07/07/img_64a7f2cc8655b3-66744141-74578656.jpg', 2, 0, '2026-01-21 16:37:54'),
(206, 71, 'uploads/products/2023/07/07/img_64a800db9242a6-29164260-40668705.jpg', 0, 1, '2026-01-21 16:37:54'),
(207, 71, 'uploads/products/2023/07/07/img_64a800dc909995-96547108-86447941.jpg', 1, 0, '2026-01-21 16:37:54'),
(208, 71, 'uploads/products/2023/07/07/img_64a800dd970787-49660802-54497814.jpg', 2, 0, '2026-01-21 16:37:54'),
(209, 72, 'uploads/products/2023/07/10/img_64ab9fa11d69b7-35840846-66525593.jpg', 0, 1, '2026-01-21 16:37:54'),
(210, 72, 'uploads/products/2023/07/10/img_64ab9fa21026b9-38835603-85863694.jpg', 1, 0, '2026-01-21 16:37:54'),
(211, 72, 'uploads/products/2023/07/10/img_64ab9fa30a1067-12443656-58999086.jpg', 2, 0, '2026-01-21 16:37:54'),
(212, 73, 'uploads/products/2023/07/11/img_64ad1c460f3b97-95682843-66433587.jpg', 0, 1, '2026-01-21 16:37:54'),
(213, 73, 'uploads/products/2023/07/11/img_64ad1c47178aa6-36759282-39808873.jpg', 1, 0, '2026-01-21 16:37:54'),
(214, 73, 'uploads/products/2023/07/11/img_64ad1c4826ecb2-56452320-28230294.jpg', 2, 0, '2026-01-21 16:37:54'),
(215, 74, 'uploads/products/2023/07/12/img_64ae3f8ad250c3-64598205-37784575.jpg', 0, 1, '2026-01-21 16:37:54'),
(216, 74, 'uploads/products/2023/07/12/img_64ae3f8be0d147-38092035-33609074.jpg', 1, 0, '2026-01-21 16:37:54'),
(217, 74, 'uploads/products/2023/07/12/img_64ae3f8cf2cf88-20199288-89397513.jpg', 2, 0, '2026-01-21 16:37:54'),
(218, 75, 'uploads/products/2023/07/12/img_64ae41649cd695-59927054-14382087.jpg', 0, 1, '2026-01-21 16:37:54'),
(219, 75, 'uploads/products/2023/07/12/img_64ae4165af2f47-05510975-73998192.jpg', 1, 0, '2026-01-21 16:37:54'),
(220, 75, 'uploads/products/2023/07/12/img_64ae4166c0d4e5-70793572-86828172.jpg', 2, 0, '2026-01-21 16:37:54'),
(221, 76, 'uploads/products/2023/07/12/img_64ae42603866f6-51051434-21331781.jpg', 0, 1, '2026-01-21 16:37:54'),
(222, 76, 'uploads/products/2023/07/12/img_64ae42614081a0-18519702-90160064.jpg', 1, 0, '2026-01-21 16:37:54'),
(223, 76, 'uploads/products/2023/07/12/img_64ae4262497879-37178784-85018781.jpg', 2, 0, '2026-01-21 16:37:54'),
(224, 77, 'uploads/products/2023/07/12/img_64ae438a64e820-16924580-82154808.jpg', 0, 1, '2026-01-21 16:37:54'),
(225, 77, 'uploads/products/2023/07/12/img_64ae438b5e73e0-08944244-15164627.jpg', 1, 0, '2026-01-21 16:37:54'),
(226, 77, 'uploads/products/2023/07/12/img_64ae438c5ed063-53789018-11637091.jpg', 2, 0, '2026-01-21 16:37:54'),
(227, 78, 'uploads/products/2023/07/12/img_64ae45e88c3392-13206018-38348836.jpg', 0, 1, '2026-01-21 16:37:54'),
(228, 78, 'uploads/products/2023/07/12/img_64ae45e97d5939-31130324-77991810.jpg', 1, 0, '2026-01-21 16:37:54'),
(229, 78, 'uploads/products/2023/07/12/img_64ae45ea711f49-00507255-71786680.jpg', 2, 0, '2026-01-21 16:37:54'),
(230, 79, 'uploads/products/2023/07/12/img_64ae49d0f31f69-44564004-35755098.jpg', 0, 1, '2026-01-21 16:37:54'),
(231, 79, 'uploads/products/2023/07/12/img_64ae49d1eb5560-89505315-46641236.jpg', 1, 0, '2026-01-21 16:37:54'),
(232, 79, 'uploads/products/2023/07/12/img_64ae49d2e71014-63160513-17189859.jpg', 2, 0, '2026-01-21 16:37:54'),
(233, 80, 'uploads/products/2023/07/12/img_64ae4dbdf27004-50547369-80095314.jpg', 0, 1, '2026-01-21 16:37:54'),
(234, 80, 'uploads/products/2023/07/12/img_64ae4dbf003a81-05949465-32712081.jpg', 1, 0, '2026-01-21 16:37:54'),
(235, 80, 'uploads/products/2023/07/12/img_64ae4dc00ca597-03376045-68490602.jpg', 2, 0, '2026-01-21 16:37:54'),
(236, 81, 'uploads/products/2023/07/12/img_64ae4f6cd90051-67543087-94049478.jpg', 0, 1, '2026-01-21 16:37:54'),
(237, 81, 'uploads/products/2023/07/12/img_64ae4f6de34893-07708142-66961788.jpg', 1, 0, '2026-01-21 16:37:54'),
(238, 81, 'uploads/products/2023/07/12/img_64ae4f6ef3bbe5-99582728-55059813.jpg', 2, 0, '2026-01-21 16:37:54'),
(239, 82, 'uploads/products/2023/07/12/img_64ae7e090557a8-12639667-34172181.jpg', 0, 1, '2026-01-21 16:37:54'),
(240, 82, 'uploads/products/2023/07/12/img_64ae7e0a171210-47105999-42970326.jpg', 1, 0, '2026-01-21 16:37:54'),
(241, 82, 'uploads/products/2023/07/12/img_64ae7e0b217381-92102448-61340350.jpg', 2, 0, '2026-01-21 16:37:54'),
(242, 83, 'uploads/products/2023/07/12/img_64ae7f14c81952-21691857-82241998.jpg', 0, 1, '2026-01-21 16:37:54'),
(243, 83, 'uploads/products/2023/07/12/img_64ae7f15d53660-63778314-23903347.jpg', 1, 0, '2026-01-21 16:37:54'),
(244, 83, 'uploads/products/2023/07/12/img_64ae7f16e53558-82119209-33566034.jpg', 2, 0, '2026-01-21 16:37:54'),
(245, 84, 'uploads/products/2023/07/12/img_64ae7ff7b25a68-36632740-33427739.jpg', 0, 1, '2026-01-21 16:37:54'),
(246, 84, 'uploads/products/2023/07/12/img_64ae7ff8be98f2-67225768-50646163.jpg', 1, 0, '2026-01-21 16:37:54'),
(247, 84, 'uploads/products/2023/07/12/img_64ae7ff9da8d77-19730881-94476059.jpg', 2, 0, '2026-01-21 16:37:54'),
(248, 85, 'uploads/products/2023/07/12/img_64ae96069bb8b3-04703296-65214679.jpg', 0, 1, '2026-01-21 16:37:54'),
(249, 85, 'uploads/products/2023/07/12/img_64ae9607bd3db2-19460414-53472903.jpg', 1, 0, '2026-01-21 16:37:54'),
(250, 85, 'uploads/products/2023/07/12/img_64ae9608dbdf56-83490604-53503239.jpg', 2, 0, '2026-01-21 16:37:54'),
(251, 86, 'uploads/products/2023/07/12/img_64aea12e4fab27-45280866-82164619.jpg', 0, 1, '2026-01-21 16:37:54'),
(252, 86, 'uploads/products/2023/07/12/img_64aea12f620098-38471050-64885382.jpg', 1, 0, '2026-01-21 16:37:54'),
(253, 86, 'uploads/products/2023/07/12/img_64aea1307c3273-09751818-69842155.jpg', 2, 0, '2026-01-21 16:37:54'),
(254, 87, 'uploads/products/2023/07/13/img_64afc1c164bbb7-15505617-64071708.jpg', 0, 1, '2026-01-21 16:37:54'),
(255, 87, 'uploads/products/2023/07/13/img_64afc1c50f96a5-78401312-56683248.jpg', 1, 0, '2026-01-21 16:37:54'),
(256, 88, 'uploads/products/2023/07/13/img_64afdd231c63a2-65868404-26832940.jpg', 0, 1, '2026-01-21 16:37:54'),
(257, 88, 'uploads/products/2023/07/13/img_64afdd240de0b4-13005158-42832717.jpg', 1, 0, '2026-01-21 16:37:54'),
(258, 88, 'uploads/products/2023/07/13/img_64afdd24e673d5-04016963-11040384.jpg', 2, 0, '2026-01-21 16:37:54'),
(259, 89, 'uploads/products/2023/07/14/img_64b10e62766620-86853061-50193097.jpg', 0, 1, '2026-01-21 16:37:54'),
(260, 89, 'uploads/products/2023/07/14/img_64b10e6373a405-95987216-61731647.jpg', 1, 0, '2026-01-21 16:37:54'),
(261, 89, 'uploads/products/2023/07/14/img_64b10e64735aa5-98306444-95275973.jpg', 2, 0, '2026-01-21 16:37:54'),
(262, 90, 'uploads/products/2023/07/14/img_64b113357b1775-73524747-61471684.jpg', 0, 1, '2026-01-21 16:37:54'),
(263, 90, 'uploads/products/2023/07/14/img_64b11336667689-93715718-73029541.jpg', 1, 0, '2026-01-21 16:37:54'),
(264, 90, 'uploads/products/2023/07/14/img_64b1133752f045-51781066-14720213.jpg', 2, 0, '2026-01-21 16:37:54'),
(265, 91, 'uploads/products/2023/07/14/img_64b116eb180285-39374220-86045425.jpg', 0, 1, '2026-01-21 16:37:54'),
(266, 91, 'uploads/products/2023/07/14/img_64b116ec00d4b8-32099659-60374608.jpg', 1, 0, '2026-01-21 16:37:54'),
(267, 91, 'uploads/products/2023/07/14/img_64b116ecde7129-34062926-83755972.jpg', 2, 0, '2026-01-21 16:37:54'),
(268, 92, 'uploads/products/2023/07/14/img_64b119f18c4b94-02926225-47343184.jpg', 0, 1, '2026-01-21 16:37:54'),
(269, 92, 'uploads/products/2023/07/14/img_64b119f272ecb2-55454891-63164768.jpg', 1, 0, '2026-01-21 16:37:54'),
(270, 93, 'uploads/products/2023/07/14/img_64b11db077ab98-25637499-30615845.jpg', 0, 1, '2026-01-21 16:37:54'),
(271, 93, 'uploads/products/2023/07/14/img_64b11db18f7841-58878435-11253418.jpg', 1, 0, '2026-01-21 16:37:54'),
(272, 93, 'uploads/products/2023/07/14/img_64b11db2a4a1d0-92309334-98803123.jpg', 2, 0, '2026-01-21 16:37:54'),
(273, 94, 'uploads/products/2023/07/14/img_64b121f7077256-62758071-12466256.jpg', 0, 1, '2026-01-21 16:37:54'),
(274, 94, 'uploads/products/2023/07/14/img_64b121f7edf752-07619812-72011617.jpg', 1, 0, '2026-01-21 16:37:54'),
(275, 94, 'uploads/products/2023/07/14/img_64b121f8e44a85-19311388-99532911.jpg', 2, 0, '2026-01-21 16:37:54'),
(276, 95, 'uploads/products/2023/07/14/img_64b123ccb8fbc1-31067077-66063982.jpg', 0, 1, '2026-01-21 16:37:54'),
(277, 95, 'uploads/products/2023/07/14/img_64b123cdcd69a7-15169452-81043303.jpg', 1, 0, '2026-01-21 16:37:54'),
(278, 95, 'uploads/products/2023/07/14/img_64b123ced96f38-17171680-79677367.jpg', 2, 0, '2026-01-21 16:37:54'),
(279, 96, 'vendors/38/uniforms/images/uniform_96_1768999480_0.avif', 0, 0, '2026-01-21 18:14:40'),
(280, 96, 'vendors/38/uniforms/images/uniform_96_1768999480_1.avif', 1, 0, '2026-01-21 18:14:40'),
(281, 96, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 2, 1, '2026-01-21 18:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `erp_uniform_size_prices`
--

CREATE TABLE `erp_uniform_size_prices` (
  `id` int(11) UNSIGNED NOT NULL,
  `uniform_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_uniforms table',
  `size_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to erp_sizes table',
  `mrp` decimal(10,2) NOT NULL COMMENT 'Maximum Retail Price',
  `selling_price` decimal(10,2) NOT NULL COMMENT 'Selling Price',
  `school_margin` decimal(10,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Size-specific prices for uniforms';

--
-- Dumping data for table `erp_uniform_size_prices`
--

INSERT INTO `erp_uniform_size_prices` (`id`, `uniform_id`, `size_id`, `mrp`, `selling_price`, `school_margin`, `created_at`, `updated_at`) VALUES
(7, 96, 2, 799.00, 699.00, NULL, '2026-01-22 11:45:30', '2026-01-22 11:45:30'),
(8, 96, 3, 799.00, 699.00, NULL, '2026-01-22 11:45:30', '2026-01-22 11:45:30'),
(9, 94, 2, 7999.00, 4999.00, NULL, '2026-01-22 14:07:47', '2026-01-22 14:07:47');

-- --------------------------------------------------------

--
-- Table structure for table `erp_uniform_types`
--

CREATE TABLE `erp_uniform_types` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Uniform Types';

--
-- Dumping data for table `erp_uniform_types`
--

INSERT INTO `erp_uniform_types` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Shirt', 'Migrated from category: Shirt', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(2, 'T-Shirt', 'Migrated from category: T-Shirt', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(3, 'Blazer', 'Migrated from category: Blazer', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(4, 'Tie', 'Migrated from category: Tie', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(5, 'Trouser', 'Migrated from category: Trouser', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(6, 'Shorts', 'Migrated from category: Shorts', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(7, 'Lagging', 'Migrated from category: Lagging', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(8, 'Skirt', 'Migrated from category: Skirt', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(9, 'Sweater', 'Migrated from category: Sweater', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(10, 'Hoodie', 'Migrated from category: Hoodie', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(11, 'Belt', 'Migrated from category: Belt', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(12, 'Socks', 'Migrated from category: Socks', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(13, 'Mask', 'Migrated from category: Mask', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(14, 'Frock', 'Migrated from category: Frock', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(15, 'Jacket', 'Migrated from category: Jacket', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(16, 'Trackpant', 'Migrated from category: Trackpant', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(17, 'Pina frock', 'Migrated from category: Pina frock', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(18, 'Half pant', 'Migrated from category: Half pant', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(19, 'Cap', 'Migrated from category: Cap', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(20, 'Bag', 'Migrated from category: Bag', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(21, 'Full Pant', 'Migrated from category: Full Pant', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(22, 'Badge', 'Migrated from category: Badge', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53'),
(23, 'White Shirt Black Pants', 'Migrated from category: White Shirt Black Pants', 'active', '2026-01-21 16:37:53', '2026-01-21 16:37:53');

-- --------------------------------------------------------

--
-- Table structure for table `erp_users`
--

CREATE TABLE `erp_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL COMMENT 'Username',
  `email` varchar(255) NOT NULL COMMENT 'Email address',
  `password` varchar(255) NOT NULL COMMENT 'Hashed password',
  `role_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Role ID',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'User status (1=active, 0=inactive)',
  `last_login` timestamp NULL DEFAULT NULL COMMENT 'Last login timestamp',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Super admin users';

--
-- Dumping data for table `erp_users`
--

INSERT INTO `erp_users` (`id`, `username`, `email`, `password`, `role_id`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(19, 'varitty', 'admin@varitty.local', '7c4a8d09ca3762af61e59520943dc26494f8941b', 4, 1, NULL, '2026-01-14 13:24:55', '2026-01-14 13:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `erp_user_roles`
--

CREATE TABLE `erp_user_roles` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Role name',
  `description` text DEFAULT NULL COMMENT 'Role description',
  `permissions` text DEFAULT NULL COMMENT 'Permissions (JSON)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Super admin role definitions';

--
-- Dumping data for table `erp_user_roles`
--

INSERT INTO `erp_user_roles` (`id`, `name`, `description`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'Full system access', '{\"clients\":[\"create\",\"read\",\"update\",\"delete\"],\"features\":[\"create\",\"read\",\"update\",\"delete\"],\"users\":[\"create\",\"read\",\"update\",\"delete\"],\"reports\":[\"read\"]}', '2025-12-19 09:34:13', '2025-12-19 09:34:13'),
(2, 'Admin', 'Administrative access', '{\"clients\":[\"read\",\"update\"],\"features\":[\"read\",\"update\"],\"users\":[\"read\"],\"reports\":[\"read\"]}', '2025-12-19 09:34:13', '2025-12-19 09:34:13'),
(3, 'Manager', 'Management access', '{\"clients\":[\"read\"],\"features\":[\"read\"],\"reports\":[\"read\"]}', '2025-12-19 09:34:13', '2025-12-19 09:34:13'),
(4, 'Vendor', 'Vendor role for vendor login access', '{\"dashboard\":[\"read\"],\"profile\":[\"read\",\"update\"]}', '2025-12-20 06:40:50', '2025-12-20 06:40:50');

-- --------------------------------------------------------

--
-- Table structure for table `erp_variation_combinations`
--

CREATE TABLE `erp_variation_combinations` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `combination_key` varchar(500) NOT NULL COMMENT 'Serialized key: type_id:value_id|type_id:value_id',
  `combination_data` text DEFAULT NULL COMMENT 'JSON representation of the combination',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_variation_combination_prices`
--

CREATE TABLE `erp_variation_combination_prices` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `combination_id` int(11) NOT NULL,
  `mrp` decimal(10,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL COMMENT 'Optional SKU for this combination',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_variation_combination_values`
--

CREATE TABLE `erp_variation_combination_values` (
  `id` int(11) NOT NULL,
  `combination_id` int(11) NOT NULL,
  `variation_type_id` int(11) NOT NULL,
  `variation_value_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_variation_types`
--

CREATE TABLE `erp_variation_types` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'e.g., Size, Color, Material, Style',
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_variation_values`
--

CREATE TABLE `erp_variation_values` (
  `id` int(11) NOT NULL,
  `variation_type_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'e.g., Small, Red, Cotton',
  `value` varchar(255) DEFAULT NULL COMMENT 'Optional value code',
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_slider`
--

CREATE TABLE `mobile_slider` (
  `id` int(11) NOT NULL,
  `type` varchar(10) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `app_path` varchar(20) DEFAULT NULL,
  `app_path_name` varchar(30) DEFAULT NULL,
  `app_path_id` int(11) NOT NULL,
  `link` text DEFAULT NULL,
  `item_order` int(11) DEFAULT 1,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT 0,
  `is_app` tinyint(1) NOT NULL DEFAULT 0,
  `max_per_user` int(11) NOT NULL,
  `no_coupon` int(11) NOT NULL,
  `is_new_only` tinyint(1) NOT NULL DEFAULT 0,
  `offer_type` enum('discount_code','automatic_discount') NOT NULL,
  `discount_code` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `min_type` enum('quantity','amount') DEFAULT NULL,
  `min_value` decimal(10,2) DEFAULT NULL,
  `item_type` enum('all','categories','products') DEFAULT NULL,
  `item_type_list` text DEFAULT NULL,
  `variation_ids` text DEFAULT NULL,
  `offer_value_type` enum('percentage','amount','free','cashback') NOT NULL,
  `offer_value` decimal(10,2) DEFAULT NULL,
  `free_quantity` int(11) DEFAULT NULL,
  `item_type_get` enum('all','categories','products') DEFAULT NULL,
  `item_type_list_get` text DEFAULT NULL,
  `variation_ids_get` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_cashback` tinyint(1) NOT NULL DEFAULT 0,
  `cashback_value` decimal(10,2) DEFAULT NULL,
  `cashback_type` enum('flat','percentage') DEFAULT NULL,
  `is_upto` tinyint(1) DEFAULT 0,
  `upto_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `is_show`, `is_app`, `max_per_user`, `no_coupon`, `is_new_only`, `offer_type`, `discount_code`, `title`, `description`, `min_type`, `min_value`, `item_type`, `item_type_list`, `variation_ids`, `offer_value_type`, `offer_value`, `free_quantity`, `item_type_get`, `item_type_list_get`, `variation_ids_get`, `status`, `created_at`, `updated_at`, `is_cashback`, `cashback_value`, `cashback_type`, `is_upto`, `upto_amount`) VALUES
(1, 0, 0, 1, 15000, 1, 'discount_code', 'AsliGift', '', 'AsliGift for You!\r\nClaim your FREE 7-day trial pack today!', 'quantity', 1.00, 'products', '84', '119', 'amount', 303.57, NULL, NULL, NULL, NULL, 0, '2025-05-06 07:39:58', '2025-05-20 07:02:44', 0, NULL, NULL, 0, NULL),
(2, 0, 0, 1, 0, 0, 'discount_code', 'Flat10', '', 'Flat 10% Off', 'quantity', 1.00, 'all', NULL, NULL, 'percentage', 10.00, NULL, NULL, NULL, NULL, 0, '2025-05-07 14:02:08', '2025-06-02 15:52:45', 0, NULL, NULL, 0, NULL),
(3, 0, 0, 1, 1, 0, 'discount_code', 'BUY1GET1', '', '', 'quantity', 1.00, 'all', NULL, NULL, 'free', NULL, 1, 'categories', '7', NULL, 0, '2025-05-17 10:18:41', '2025-06-02 15:52:49', 0, NULL, NULL, 0, NULL),
(4, 1, 0, 500, 500, 0, 'discount_code', 'MooD10', '', 'Enjoy an extra 10% off for the Mood On Forever Avaleh product', 'quantity', 1.00, 'products', '17', '23', 'cashback', 0.00, NULL, NULL, NULL, NULL, 1, '2025-06-02 15:56:03', '2026-01-13 12:15:34', 1, 10.00, 'percentage', 0, NULL),
(5, 1, 0, 500, 500, 0, 'discount_code', 'SH10', '', 'Enjoy an extra 10% off for Nari Amrut Capsule product', 'quantity', 1.00, 'products', '43,44', '58,59', 'cashback', 0.00, NULL, NULL, NULL, NULL, 1, '2025-06-06 11:22:14', '2026-01-13 12:16:04', 1, 10.00, 'percentage', 0, NULL),
(6, 1, 0, 500, 500, 0, 'discount_code', 'Early10', '', 'Enjoy an extra 10% off for Early Morning Churna product', 'quantity', 1.00, 'products', '28', '37', 'cashback', 0.00, NULL, NULL, NULL, NULL, 1, '2025-06-06 11:23:48', '2026-01-13 12:16:16', 1, 10.00, 'percentage', 0, NULL),
(7, 1, 0, 500, 500, 0, 'discount_code', 'Milk50', '', 'Enjoy an extra 50% off for Coconut Milk Multi Vitamin and Peptide Shampoo & Conditioner products', 'quantity', 1.00, 'products', '72,73', '101,102', 'percentage', 50.00, NULL, NULL, NULL, NULL, 0, '2025-06-06 11:26:42', '2025-07-25 16:06:53', 0, NULL, NULL, NULL, NULL),
(8, 1, 0, 500, 500, 0, 'discount_code', 'SKIN50', '', 'Enjoy an extra 50% off for Kashmiri Saffron and Neem Herbal Facial Cleanser product', 'quantity', 1.00, 'products', '68,69,70', '96,97,98', 'percentage', 50.00, NULL, NULL, NULL, NULL, 0, '2025-06-06 11:27:58', '2025-07-25 16:06:48', 0, NULL, NULL, NULL, NULL),
(10, 0, 0, 6, 6, 0, 'discount_code', 'WELCOME77', '', 'Flat 18% OFF + ₹100 Wallet Cashback. Minimum order value: ₹1000. T&C Apply', 'amount', 1000.00, 'all', NULL, NULL, 'percentage', 18.00, NULL, NULL, NULL, NULL, 0, '2025-07-02 12:10:08', '2025-08-25 06:34:52', 1, 100.00, 'flat', 0, NULL),
(11, 1, 0, 500, 500, 0, 'discount_code', 'RAS10', '', 'Get 10% of Rastofung Powder using code RAS10', 'quantity', 1.00, 'products', '40', '53', 'cashback', 0.00, NULL, NULL, NULL, NULL, 1, '2025-07-14 12:39:41', '2026-01-13 12:16:31', 1, 10.00, 'percentage', 0, NULL),
(12, 1, 0, 500, 500, 0, 'discount_code', 'PLUS10', '', 'Get 10% of Plate Plus Capsule using code PLUS10', 'quantity', 1.00, 'products', '63', '87', 'percentage', 10.00, NULL, NULL, NULL, NULL, 1, '2025-07-14 12:41:18', '2025-07-14 12:41:18', 0, NULL, NULL, 0, NULL),
(13, 0, 0, 1, 1000, 1, 'discount_code', 'CASH10', '', 'Grab Your CASH10 Discount', 'quantity', 1.00, 'all', NULL, NULL, 'cashback', 0.00, NULL, NULL, NULL, NULL, 1, '2025-08-26 07:33:35', '2026-01-13 12:16:55', 1, 10.00, 'percentage', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `customer_id` int(11) UNSIGNED NOT NULL,
  `school_id` int(11) UNSIGNED DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_user_invoice`
--

CREATE TABLE `order_user_invoice` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `user_invoice` varchar(500) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `invoice_url` varchar(500) DEFAULT NULL,
  `invoice_date` datetime DEFAULT NULL,
  `last_modified` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_user_invoice`
--

INSERT INTO `order_user_invoice` (`id`, `order_id`, `vendor_id`, `year`, `user_invoice`, `invoice_id`, `invoice_url`, `invoice_date`, `last_modified`) VALUES
(1, 1, NULL, 2026, 'MW/25-26/1', 1, NULL, '2026-01-21 18:56:48', '2026-01-21 13:26:48'),
(2, 3, NULL, 2026, 'MW/25-26/2', 2, NULL, '2026-01-22 12:51:38', '2026-01-22 07:21:38'),
(3, 4, NULL, 2026, 'MW/25-26/3', 3, NULL, '2026-01-22 12:52:19', '2026-01-22 07:22:19'),
(4, 5, NULL, 2026, 'MW/25-26/4', 4, NULL, '2026-01-22 13:24:29', '2026-01-22 07:54:29'),
(5, 6, NULL, 2026, 'MW/25-26/5', 5, NULL, '2026-01-22 14:04:33', '2026-01-22 08:34:34'),
(6, 7, NULL, 2026, 'MW/25-26/6', 6, NULL, '2026-01-22 15:23:50', '2026-01-22 09:53:50'),
(7, 8, NULL, 2026, 'MW/25-26/7', 7, NULL, '2026-01-22 15:24:26', '2026-01-22 09:54:26'),
(8, 9, NULL, 2026, 'MW/25-26/8', 8, NULL, '2026-01-22 19:12:39', '2026-01-22 13:42:40'),
(9, 10, NULL, 2026, 'MW/25-26/9', 9, NULL, '2026-01-22 19:38:07', '2026-01-22 14:08:07'),
(10, 11, NULL, 2026, 'MW/25-26/10', 10, NULL, '2026-01-23 12:38:19', '2026-01-23 07:08:20'),
(11, 12, NULL, 2026, 'MW/25-26/11', 11, NULL, '2026-01-24 16:36:30', '2026-01-24 11:06:30');

-- --------------------------------------------------------

--
-- Table structure for table `press_release`
--

CREATE TABLE `press_release` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` enum('book','bookset','uniform','individual') NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_category`
--

CREATE TABLE `products_category` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_warehouse_qty`
--

CREATE TABLE `products_warehouse_qty` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `warehouse_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variations`
--

CREATE TABLE `product_variations` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `slug` varchar(150) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `color` int(11) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `hsn` varchar(150) DEFAULT NULL,
  `product_mrp` double(20,2) NOT NULL DEFAULT 0.00,
  `disc_per` decimal(16,2) NOT NULL DEFAULT 0.00,
  `selling_price` double(10,2) NOT NULL DEFAULT 0.00,
  `out_of_stock` tinyint(1) NOT NULL DEFAULT 0,
  `stock` int(11) DEFAULT 0,
  `weight` decimal(16,2) DEFAULT NULL,
  `height` decimal(16,2) DEFAULT NULL,
  `length` decimal(16,2) DEFAULT NULL,
  `width` decimal(16,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_login`
--

CREATE TABLE `school_login` (
  `id` int(11) UNSIGNED NOT NULL,
  `school_id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) DEFAULT 'Book Store',
  `site_logo` varchar(255) DEFAULT NULL,
  `primary_color` varchar(7) DEFAULT '#007bff',
  `secondary_color` varchar(7) DEFAULT '#6c757d',
  `footer_text` text DEFAULT NULL,
  `show_search` tinyint(1) DEFAULT 1,
  `show_cart` tinyint(1) DEFAULT 1,
  `show_wishlist` tinyint(1) DEFAULT 0,
  `enable_checkout` tinyint(1) DEFAULT 1,
  `homepage_title` varchar(255) DEFAULT NULL,
  `homepage_description` text DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `contact_address` text DEFAULT NULL,
  `social_facebook` varchar(255) DEFAULT NULL,
  `social_twitter` varchar(255) DEFAULT NULL,
  `social_instagram` varchar(255) DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `item_order` int(11) DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `country_id` int(11) NOT NULL DEFAULT 101 COMMENT '101 = India'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='States';

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `code`, `country_id`) VALUES
(1547, 'Andaman and Nicobar Islands', 'AN', 101),
(1548, 'Andhra Pradesh', 'AP', 101),
(1549, 'Arunachal Pradesh', 'AR', 101),
(1550, 'Assam', 'AS', 101),
(1551, 'Bihar', 'BR', 101),
(1552, 'Chandigarh', 'CH', 101),
(1553, 'Chhattisgarh', 'CG', 101),
(1554, 'Dadra and Nagar Haveli', 'DN', 101),
(1555, 'Daman and Diu', 'DD', 101),
(1556, 'Delhi', 'DL', 101),
(1557, 'Goa', 'GA', 101),
(1558, 'Gujarat', 'GJ', 101),
(1559, 'Haryana', 'HR', 101),
(1560, 'Himachal Pradesh', 'HP', 101),
(1561, 'Jammu and Kashmir', 'JK', 101),
(1562, 'Jharkhand', 'JH', 101),
(1563, 'Karnataka', 'KA', 101),
(1565, 'Kerala', 'KL', 101),
(1566, 'Lakshadweep', 'LD', 101),
(1567, 'Madhya Pradesh', 'MP', 101),
(1568, 'Maharashtra', 'MH', 101),
(1569, 'Manipur', 'MN', 101),
(1570, 'Meghalaya', 'ML', 101),
(1571, 'Mizoram', 'MZ', 101),
(1572, 'Nagaland', 'NL', 101),
(1575, 'Odisha', 'OR', 101),
(1577, 'Pondicherry', 'PY', 101),
(1578, 'Punjab', 'PB', 101),
(1579, 'Rajasthan', 'RJ', 101),
(1580, 'Sikkim', 'SK', 101),
(1581, 'Tamil Nadu', 'TN', 101),
(1582, 'Telangana', 'TS', 101),
(1583, 'Tripura', 'TR', 101),
(1584, 'Uttar Pradesh', 'UP', 101),
(1585, 'Uttarakhand', 'UK', 101),
(1587, 'West Bengal', 'WB', 101);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT 0,
  `slug` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_address`
--

CREATE TABLE `tbl_order_address` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `order_unique_id` text DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `district` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `landmark` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(12) DEFAULT NULL,
  `dial_code` varchar(10) DEFAULT '+91',
  `alter_mobile_no` varchar(12) DEFAULT NULL,
  `alternate_dial_code` varchar(10) DEFAULT '',
  `lattitude` varchar(150) DEFAULT NULL,
  `longitude` varchar(150) DEFAULT NULL,
  `map_location` text DEFAULT NULL,
  `address_type` varchar(30) DEFAULT NULL,
  `is_default` varchar(10) DEFAULT 'false',
  `billing_address_id` int(11) DEFAULT NULL,
  `billing_name` varchar(150) DEFAULT NULL,
  `billing_mobile_no` varchar(20) DEFAULT NULL,
  `billing_country` varchar(150) DEFAULT NULL,
  `billing_pincode` varchar(10) DEFAULT NULL,
  `billing_state` varchar(150) DEFAULT NULL,
  `billing_city` varchar(150) DEFAULT NULL,
  `billing_address` varchar(500) DEFAULT NULL,
  `created_at` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_order_address`
--

INSERT INTO `tbl_order_address` (`id`, `order_id`, `order_unique_id`, `address_id`, `user_id`, `pincode`, `address`, `city`, `district`, `state`, `country`, `landmark`, `name`, `email`, `mobile_no`, `dial_code`, `alter_mobile_no`, `alternate_dial_code`, `lattitude`, `longitude`, `map_location`, `address_type`, `is_default`, `billing_address_id`, `billing_name`, `billing_mobile_no`, `billing_country`, `billing_pincode`, `billing_state`, `billing_city`, `billing_address`, `created_at`) VALUES
(1, 1, 'ORD20260121185648000001', 1, 1, '400075', '347 Blvd', 'Garacharma', '', 'Andaman and Nicobar Islands', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 1, 'Anass', '6476711319', 'India', '400075', 'Andaman and Nicobar Islands', 'Garacharma', '347 Blvd', '2026-01-21 18:56:48'),
(2, 2, 'RAPL202601221251282', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-22 12:51:28'),
(3, 3, 'ORD20260122125138000003', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-22 12:51:38'),
(4, 4, 'ORD20260122125219000004', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-22 12:52:19'),
(5, 5, 'ORD20260122132429000005', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-22 13:24:29'),
(6, 6, 'ORD20260122140433000006', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-22 14:04:33'),
(7, 7, 'ORD20260122152350000007', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-22 15:23:50'),
(8, 8, 'ORD20260122152426000008', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-22 15:24:26'),
(9, 9, 'ORD20260122191239000009', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-22 19:12:40'),
(10, 10, 'ORD260122807', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-22 19:38:07'),
(11, 11, 'ORD260123819', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-23 12:38:19'),
(12, 12, 'ORD260124630', 2, 1, '400075', '347 Blvd', 'Adoni', '', 'Andhra Pradesh', 'India', '', 'Anass', NULL, '6476711319', '+91', '', '', NULL, NULL, NULL, '', 'false', 2, 'Anass', '6476711319', 'India', '400075', 'Andhra Pradesh', 'Adoni', '347 Blvd', '2026-01-24 16:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_details`
--

CREATE TABLE `tbl_order_details` (
  `id` int(11) NOT NULL,
  `is_invoice` tinyint(1) NOT NULL DEFAULT 0,
  `invoice_url` varchar(150) DEFAULT NULL,
  `order_unique_id` varchar(100) DEFAULT NULL,
  `coupon_id` int(11) NOT NULL DEFAULT 0,
  `coupon_code` varchar(30) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(150) DEFAULT NULL,
  `user_email` varchar(150) DEFAULT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `dial_code` varchar(10) DEFAULT '+91',
  `order_date` datetime NOT NULL,
  `payment_status` varchar(15) DEFAULT NULL,
  `payment_method` varchar(15) DEFAULT 'cod',
  `order_type` enum('online','app') NOT NULL DEFAULT 'online',
  `source` varchar(10) NOT NULL DEFAULT 'web',
  `checkout_type` enum('user_checkout','guest_checkout','') NOT NULL DEFAULT 'user_checkout',
  `country` varchar(120) DEFAULT NULL,
  `currency_code` varchar(60) NOT NULL DEFAULT 'INR',
  `currency` varchar(60) NOT NULL DEFAULT '₹',
  `exchange_rate` double DEFAULT NULL,
  `payment_amount` double NOT NULL,
  `cf_order_id` varchar(15) DEFAULT NULL,
  `order_token` varchar(50) DEFAULT NULL,
  `payment_response` text DEFAULT NULL,
  `order_address` int(11) NOT NULL,
  `total_amt` double NOT NULL,
  `discount` varchar(10) DEFAULT '0',
  `discount_amt` double NOT NULL,
  `delivery_charge` varchar(5) NOT NULL DEFAULT '0',
  `freight_charges_excl` decimal(16,2) NOT NULL DEFAULT 0.00,
  `freight_gst` decimal(16,2) NOT NULL DEFAULT 0.00,
  `freight_gst_per` int(11) DEFAULT 0,
  `gst_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `round_off` decimal(16,2) NOT NULL DEFAULT 0.00,
  `payable_amt` double NOT NULL,
  `new_payable_amt` double NOT NULL COMMENT 'excl_gst taxable_value',
  `wallet_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `refund_amt` double NOT NULL DEFAULT 0,
  `refund_per` double NOT NULL DEFAULT 0,
  `processing_date` datetime DEFAULT NULL,
  `shipment_date` datetime DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `cancelled_date` datetime DEFAULT NULL,
  `return_date` datetime DEFAULT NULL,
  `order_status` int(11) NOT NULL DEFAULT -1,
  `is_seen` int(11) NOT NULL DEFAULT 0,
  `remark` text DEFAULT NULL,
  `refund_type` enum('wallet','bank_account','') DEFAULT NULL,
  `refund_txnid` varchar(50) DEFAULT NULL,
  `is_refund` tinyint(1) NOT NULL DEFAULT 0,
  `payment_id` varchar(30) DEFAULT NULL,
  `razorpay_order_id` varchar(30) DEFAULT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `invoice_date` datetime DEFAULT NULL,
  `is_wallet` tinyint(1) NOT NULL DEFAULT 0,
  `is_cron` tinyint(1) NOT NULL DEFAULT 0,
  `cron_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cron_data`)),
  `ship_order_id` varchar(150) DEFAULT NULL,
  `shipment_id` varchar(150) DEFAULT NULL,
  `tracking_id` varchar(250) DEFAULT NULL,
  `shipping_label` text DEFAULT NULL,
  `track_url` text DEFAULT NULL,
  `track_date` datetime DEFAULT NULL,
  `awb_no` varchar(30) DEFAULT NULL,
  `courier` enum('shiprocket','manual','') DEFAULT NULL,
  `cancelled_by` enum('user','admin','') DEFAULT 'user',
  `cancelled_by_id` int(11) DEFAULT NULL,
  `emb_price` decimal(10,0) DEFAULT 0,
  `del_city_id` int(11) DEFAULT NULL COMMENT 'price_id',
  `del_city` varchar(150) DEFAULT NULL,
  `del_state_id` int(11) DEFAULT NULL,
  `del_state` varchar(150) DEFAULT NULL,
  `is_reselling` tinyint(1) NOT NULL DEFAULT 0,
  `margin` decimal(16,2) NOT NULL DEFAULT 0.00,
  `is_mail_sent` tinyint(1) NOT NULL DEFAULT 0,
  `is_mail_date` datetime DEFAULT NULL,
  `is_wati` tinyint(4) NOT NULL DEFAULT 0,
  `wati_date` datetime DEFAULT NULL,
  `is_track_sent` tinyint(1) NOT NULL DEFAULT 0,
  `track_sent_date` datetime DEFAULT NULL,
  `cancel_invoice_url` varchar(250) DEFAULT NULL,
  `is_cancel_invoice` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_order_details`
--

INSERT INTO `tbl_order_details` (`id`, `is_invoice`, `invoice_url`, `order_unique_id`, `coupon_id`, `coupon_code`, `user_id`, `user_name`, `user_email`, `user_phone`, `dial_code`, `order_date`, `payment_status`, `payment_method`, `order_type`, `source`, `checkout_type`, `country`, `currency_code`, `currency`, `exchange_rate`, `payment_amount`, `cf_order_id`, `order_token`, `payment_response`, `order_address`, `total_amt`, `discount`, `discount_amt`, `delivery_charge`, `freight_charges_excl`, `freight_gst`, `freight_gst_per`, `gst_total`, `round_off`, `payable_amt`, `new_payable_amt`, `wallet_amount`, `refund_amt`, `refund_per`, `processing_date`, `shipment_date`, `delivery_date`, `cancelled_date`, `return_date`, `order_status`, `is_seen`, `remark`, `refund_type`, `refund_txnid`, `is_refund`, `payment_id`, `razorpay_order_id`, `invoice_no`, `invoice_date`, `is_wallet`, `is_cron`, `cron_data`, `ship_order_id`, `shipment_id`, `tracking_id`, `shipping_label`, `track_url`, `track_date`, `awb_no`, `courier`, `cancelled_by`, `cancelled_by_id`, `emb_price`, `del_city_id`, `del_city`, `del_state_id`, `del_state`, `is_reselling`, `margin`, `is_mail_sent`, `is_mail_date`, `is_wati`, `wati_date`, `is_track_sent`, `track_sent_date`, `cancel_invoice_url`, `is_cancel_invoice`) VALUES
(1, 0, NULL, 'ORD20260121185648000001', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-21 18:56:48', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 2097, NULL, NULL, NULL, 1, 2097, '0', 0, '0', 0.00, 0.00, 0, 224.68, 0.00, 2097, 1872.32, 0.00, 0, 0, '2026-01-22 07:45:06', '2026-01-22 07:45:14', '2026-01-22 07:54:16', NULL, NULL, 4, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/1', '2026-01-21 18:56:48', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(2, 0, NULL, 'RAPL202601221251282', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-22 12:51:28', 'pending', 'cash_free', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 1398, NULL, NULL, 'cf-{\"code\":\"order_meta.return_url_invalid\",\"message\":\"order_meta.return_url : url should be https. Value received: http:\\/\\/localhost\\/erp_books_live\\/book_erp_frontend\\/cashfree-order-process?order_id={order_id}\",\"type\":\"invalid_request_error\"}', 2, 1398, '0', 0, '0', 0.00, 0.00, 0, 149.78, 0.00, 1398, 1248.22, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(3, 0, NULL, 'ORD20260122125138000003', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-22 12:51:38', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 1398, NULL, NULL, NULL, 2, 1398, '0', 0, '0', 0.00, 0.00, 0, 149.79, 0.00, 1398, 1248.21, 0.00, 0, 0, '2026-01-22 08:25:17', '2026-01-22 08:26:45', '2026-01-22 08:27:51', NULL, NULL, 4, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/2', '2026-01-22 12:51:38', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(4, 0, NULL, 'ORD20260122125219000004', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-22 12:52:19', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 699, NULL, NULL, NULL, 2, 699, '0', 0, '0', 0.00, 0.00, 0, 74.89, 0.00, 699, 624.11, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/3', '2026-01-22 12:52:19', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(5, 0, NULL, 'ORD20260122132429000005', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-22 13:24:29', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 699, NULL, NULL, NULL, 2, 699, '0', 0, '0', 0.00, 0.00, 0, 74.89, 0.00, 699, 624.11, 0.00, 0, 0, '2026-01-22 08:56:39', NULL, NULL, NULL, NULL, 2, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/4', '2026-01-22 13:24:29', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(6, 0, NULL, 'ORD20260122140433000006', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-22 14:04:33', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 1398, NULL, NULL, NULL, 2, 1398, '0', 0, '0', 0.00, 0.00, 0, 149.79, 0.00, 1398, 1248.21, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/5', '2026-01-22 14:04:33', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(7, 0, NULL, 'ORD20260122152350000007', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-22 15:23:50', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 699, NULL, NULL, NULL, 2, 699, '0', 0, '0', 0.00, 0.00, 0, 74.89, 0.00, 699, 624.11, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/6', '2026-01-22 15:23:50', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(8, 0, NULL, 'ORD20260122152426000008', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-22 15:24:26', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 4194, NULL, NULL, NULL, 2, 4194, '0', 0, '0', 0.00, 0.00, 0, 449.36, 0.00, 4194, 3744.64, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/7', '2026-01-22 15:24:26', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(9, 0, NULL, 'ORD20260122191239000009', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-22 19:12:39', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 699, NULL, NULL, NULL, 2, 699, '0', 0, '0', 0.00, 0.00, 0, 74.89, 0.00, 699, 624.11, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/8', '2026-01-22 19:12:39', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(10, 0, NULL, 'ORD260122807', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-22 19:38:07', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 699, NULL, NULL, NULL, 2, 699, '0', 0, '0', 0.00, 0.00, 0, 74.89, 0.00, 699, 624.11, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/9', '2026-01-22 19:38:07', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(11, 0, NULL, 'ORD260123819', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-23 12:38:19', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 699, NULL, NULL, NULL, 2, 699, '0', 0, '0', 0.00, 0.00, 0, 74.89, 0.00, 699, 624.11, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/10', '2026-01-23 12:38:19', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0),
(12, 0, NULL, 'ORD260124630', 0, '', 1, 'Bookset', 'master@mail.com', '8898929759', '+91', '2026-01-24 16:36:30', 'cod', 'cod', 'online', 'web', 'user_checkout', 'India', 'INR', '₹', 1, 699, NULL, NULL, NULL, 2, 699, '0', 0, '0', 0.00, 0.00, 0, 74.89, 0.00, 699, 624.11, 0.00, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, '', NULL, NULL, 0, NULL, NULL, 'MW/25-26/11', '2026-01-24 16:36:30', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'user', NULL, 0, NULL, NULL, NULL, NULL, 0, 0.00, 0, NULL, 0, NULL, 0, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_items`
--

CREATE TABLE `tbl_order_items` (
  `id` int(11) NOT NULL,
  `inv_type` enum('self','easyecom','') DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` varchar(100) DEFAULT NULL,
  `category` varchar(250) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `product_title` varchar(100) NOT NULL,
  `product_qty` int(11) NOT NULL,
  `product_mrp` decimal(16,2) NOT NULL DEFAULT 0.00,
  `product_price` double NOT NULL,
  `product_sku` varchar(50) DEFAULT NULL,
  `hsn` varchar(150) DEFAULT NULL,
  `product_gst` decimal(16,2) DEFAULT NULL,
  `total_gst_amt` decimal(16,2) DEFAULT NULL,
  `total_price` double NOT NULL,
  `excl_price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `excl_price_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discounted_price_total` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount_amt` decimal(16,3) NOT NULL DEFAULT 0.000,
  `refund_amt` decimal(16,2) NOT NULL DEFAULT 0.00,
  `complaint_id` int(11) DEFAULT NULL,
  `pro_order_status` int(11) NOT NULL DEFAULT -1,
  `is_variation` int(11) NOT NULL DEFAULT 0,
  `variation_id` int(11) DEFAULT NULL,
  `variation_name` varchar(250) DEFAULT NULL,
  `weight` decimal(16,2) DEFAULT NULL,
  `length` decimal(16,2) DEFAULT NULL,
  `breadth` decimal(16,2) DEFAULT NULL,
  `height` decimal(16,2) DEFAULT NULL,
  `thumbnail_img` varchar(150) DEFAULT NULL,
  `is_embroidery` tinyint(4) NOT NULL DEFAULT 0,
  `emb_type` varchar(150) DEFAULT NULL,
  `embroidery_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `emb_rate` decimal(16,2) NOT NULL DEFAULT 0.00,
  `emb_qty` int(11) NOT NULL DEFAULT 0,
  `emb_price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `del_price` decimal(16,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_order_items`
--

INSERT INTO `tbl_order_items` (`id`, `inv_type`, `order_id`, `user_id`, `category_id`, `category`, `product_id`, `product_title`, `product_qty`, `product_mrp`, `product_price`, `product_sku`, `hsn`, `product_gst`, `total_gst_amt`, `total_price`, `excl_price`, `excl_price_total`, `discounted_price_total`, `discount_amt`, `refund_amt`, `complaint_id`, `pro_order_status`, `is_variation`, `variation_id`, `variation_name`, `weight`, `length`, `breadth`, `height`, `thumbnail_img`, `is_embroidery`, `emb_type`, `embroidery_json`, `emb_rate`, `emb_qty`, `emb_price`, `del_price`) VALUES
(1, NULL, 1, 1, '0', '', 96, 'Baby Frocks', 2, 799.00, 699, '74123', '74123', 12.00, 149.79, 1398, 624.11, 1248.22, 1248.21, 0.000, 0.00, NULL, 1, 1, 5, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(2, NULL, 1, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 6, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(3, NULL, 2, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 7, 'Size: 28', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(4, NULL, 2, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 8, 'Size: 30', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(5, NULL, 3, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 7, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(6, NULL, 3, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 8, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(7, NULL, 4, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 7, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(8, NULL, 5, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 7, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(9, NULL, 6, 1, '0', '', 96, 'Baby Frocks', 2, 799.00, 699, '74123', '74123', 12.00, 149.79, 1398, 624.11, 1248.22, 1248.21, 0.000, 0.00, NULL, 1, 1, 8, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(10, NULL, 7, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 7, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(11, NULL, 8, 1, '0', '', 96, 'Baby Frocks', 2, 799.00, 699, '74123', '74123', 12.00, 149.79, 1398, 624.11, 1248.22, 1248.21, 0.000, 0.00, NULL, 1, 1, 8, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(12, NULL, 8, 1, '0', '', 96, 'Baby Frocks', 4, 799.00, 699, '74123', '74123', 12.00, 299.57, 2796, 624.11, 2496.44, 2496.43, 0.000, 0.00, NULL, 1, 1, 7, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(13, NULL, 9, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 7, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(14, NULL, 10, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 7, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(15, NULL, 11, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 7, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00),
(16, NULL, 12, 1, '0', '', 96, 'Baby Frocks', 1, 799.00, 699, '74123', '74123', 12.00, 74.89, 699, 624.11, 624.11, 624.11, 0.000, 0.00, NULL, 1, 1, 7, 'Baby Frocks', 1.00, 1.00, 1.00, 1.00, 'vendors/38/uniforms/images/uniform_96_1768999480_2.jpg', 0, NULL, '', 0.00, 0, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_status`
--

CREATE TABLE `tbl_order_status` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status_title` varchar(100) NOT NULL,
  `status_desc` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_order_status`
--

INSERT INTO `tbl_order_status` (`id`, `order_id`, `user_id`, `product_id`, `status_title`, `status_desc`, `created_at`) VALUES
(0, 1, 1, 96, '1', 'Your Order has been placed.', '2026-01-21 18:56:48'),
(0, 1, 1, 96, '1', 'Your Order has been placed.', '2026-01-21 18:56:48'),
(0, 2, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 12:51:28'),
(0, 2, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 12:51:28'),
(0, 3, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 12:51:38'),
(0, 3, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 12:51:38'),
(0, 4, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 12:52:19'),
(0, 5, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 13:24:29'),
(0, 6, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 14:04:33'),
(0, 7, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 15:23:50'),
(0, 8, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 15:24:26'),
(0, 8, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 15:24:26'),
(0, 9, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 19:12:40'),
(0, 10, 1, 96, '1', 'Your Order has been placed.', '2026-01-22 19:38:07'),
(0, 11, 1, 96, '1', 'Your Order has been placed.', '2026-01-23 12:38:20'),
(0, 12, 1, 96, '1', 'Your Order has been placed.', '2026-01-24 16:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_popup`
--

CREATE TABLE `tbl_popup` (
  `id` int(11) NOT NULL,
  `image` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `app_order_email` varchar(150) NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `app_email` varchar(150) NOT NULL,
  `app_logo` varchar(255) NOT NULL,
  `web_favicon` varchar(150) NOT NULL,
  `app_author` varchar(255) NOT NULL,
  `app_contact` varchar(255) NOT NULL,
  `app_website` varchar(255) NOT NULL,
  `app_description` text NOT NULL,
  `app_developed_by` varchar(255) NOT NULL,
  `facebook_url` text NOT NULL,
  `twitter_url` text NOT NULL,
  `youtube_url` text NOT NULL,
  `instagram_url` text NOT NULL,
  `app_privacy_policy` text NOT NULL,
  `app_currency_code` varchar(30) NOT NULL,
  `app_currency_html_code` text NOT NULL,
  `cod_status` varchar(30) NOT NULL DEFAULT 'true',
  `paypal_status` varchar(30) NOT NULL DEFAULT 'true',
  `paypal_mode` varchar(10) NOT NULL,
  `paypal_client_id` text NOT NULL,
  `paypal_secret_key` text NOT NULL,
  `stripe_status` varchar(30) NOT NULL DEFAULT 'false',
  `stripe_key` text NOT NULL,
  `stripe_secret` text NOT NULL,
  `razorpay_status` varchar(20) NOT NULL DEFAULT 'false',
  `razorpay_key` text NOT NULL,
  `razorpay_secret` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_status_title`
--

CREATE TABLE `tbl_status_title` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `pickup` varchar(50) DEFAULT NULL,
  `msg` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transaction`
--

CREATE TABLE `tbl_transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_unique_id` text NOT NULL,
  `gateway` varchar(30) NOT NULL,
  `payment_amt` varchar(50) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `razorpay_order_id` varchar(255) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_transaction`
--

INSERT INTO `tbl_transaction` (`id`, `user_id`, `email`, `order_id`, `order_unique_id`, `gateway`, `payment_amt`, `payment_id`, `razorpay_order_id`, `date`, `status`) VALUES
(1, 1, 'master@mail.com', 1, 'ORD20260121185648000001', '2097', '2097', '0', '0', '2026-01-21 06:56:48', 1),
(2, 1, 'master@mail.com', 2, 'RAPL202601221251282', '1398.00', '1398.00', '0', '0', '2026-01-22 12:51:28', 1),
(3, 1, 'master@mail.com', 3, 'ORD20260122125138000003', '1398', '1398', '0', '0', '2026-01-22 12:51:38', 1),
(4, 1, 'master@mail.com', 4, 'ORD20260122125219000004', '699', '699', '0', '0', '2026-01-22 12:52:19', 1),
(5, 1, 'master@mail.com', 5, 'ORD20260122132429000005', '699', '699', '0', '0', '2026-01-22 01:24:29', 1),
(6, 1, 'master@mail.com', 6, 'ORD20260122140433000006', '1398', '1398', '0', '0', '2026-01-22 02:04:33', 1),
(7, 1, 'master@mail.com', 7, 'ORD20260122152350000007', '699', '699', '0', '0', '2026-01-22 03:23:50', 1),
(8, 1, 'master@mail.com', 8, 'ORD20260122152426000008', '4194', '4194', '0', '0', '2026-01-22 03:24:26', 1),
(9, 1, 'master@mail.com', 9, 'ORD20260122191239000009', '699', '699', '0', '0', '2026-01-22 07:12:40', 1),
(10, 1, 'master@mail.com', 10, 'ORD260122807', '699', '699', '0', '0', '2026-01-22 07:38:07', 1),
(11, 1, 'master@mail.com', 11, 'ORD260123819', '699', '699', '0', '0', '2026-01-23 12:38:19', 1),
(12, 1, 'master@mail.com', 12, 'ORD260124630', '699', '699', '0', '0', '2026-01-24 04:36:30', 1);

-- --------------------------------------------------------

--
-- Table structure for table `temp_user`
--

CREATE TABLE `temp_user` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_phone` varchar(15) NOT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `temp_user`
--

INSERT INTO `temp_user` (`id`, `user_name`, `user_email`, `user_phone`, `otp`, `created_at`) VALUES
(4, NULL, NULL, '9967700981', NULL, '2026-01-23 07:50:26');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `job_title` varchar(150) NOT NULL,
  `rating` varchar(20) NOT NULL,
  `profile_image` varchar(250) NOT NULL,
  `description` longtext DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `firm_name` varchar(250) DEFAULT NULL,
  `email` varchar(255) DEFAULT 'name@domain.com',
  `google_auth_id` text DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `dial_code` varchar(10) DEFAULT '+91',
  `country_code` varchar(2) DEFAULT 'IN',
  `password` varchar(255) DEFAULT NULL,
  `last_password` varchar(200) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `token` text DEFAULT NULL,
  `web_token` text DEFAULT NULL,
  `fcm_token` text DEFAULT NULL,
  `last_login` varchar(10) DEFAULT NULL,
  `last_login_date` varchar(30) DEFAULT NULL,
  `agent` varchar(10) NOT NULL DEFAULT 'web',
  `auth_token` text DEFAULT NULL,
  `order_series` varchar(100) DEFAULT NULL,
  `invoice_series` varchar(100) DEFAULT NULL,
  `slot_series` varchar(100) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT 0,
  `role` varchar(20) DEFAULT 'member',
  `slug` varchar(255) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `email_status` tinyint(1) DEFAULT 0,
  `approve` tinyint(1) DEFAULT 0,
  `approve_by` varchar(100) DEFAULT NULL,
  `approve_date` datetime DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `banned` tinyint(1) DEFAULT 0,
  `updated_date` datetime DEFAULT NULL,
  `access_manager` longtext DEFAULT NULL,
  `is_cod` tinyint(1) NOT NULL DEFAULT 1,
  `is_cod_free` tinyint(1) NOT NULL DEFAULT 0,
  `cod_amt` decimal(16,2) DEFAULT NULL,
  `wallet_amount` decimal(16,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `firm_name`, `email`, `google_auth_id`, `phone_number`, `dial_code`, `country_code`, `password`, `last_password`, `otp`, `created_at`, `status`, `token`, `web_token`, `fcm_token`, `last_login`, `last_login_date`, `agent`, `auth_token`, `order_series`, `invoice_series`, `slot_series`, `vendor_id`, `role`, `slug`, `logo`, `email_status`, `approve`, `approve_by`, `approve_date`, `avatar`, `banned`, `updated_date`, `access_manager`, `is_cod`, `is_cod_free`, `cod_amt`, `wallet_amount`) VALUES
(1, 'Bookset', NULL, 'master@mail.com', NULL, '8898929759', '+91', 'IN', NULL, NULL, 1234, '2026-01-21 08:56:17', 1, NULL, NULL, NULL, NULL, NULL, 'web', NULL, NULL, NULL, NULL, 0, 'member', NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, NULL, 1, 0, NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_features`
--

CREATE TABLE `vendor_features` (
  `id` int(11) UNSIGNED NOT NULL,
  `feature_id` int(11) UNSIGNED NOT NULL COMMENT 'Reference to erp_features.id in master',
  `feature_slug` varchar(255) NOT NULL COMMENT 'Feature identifier for quick lookup',
  `feature_name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL COMMENT 'Vendor-uploaded image for this feature',
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `has_variations` tinyint(1) NOT NULL DEFAULT 0,
  `has_size` tinyint(1) NOT NULL DEFAULT 0,
  `has_colour` tinyint(1) NOT NULL DEFAULT 0,
  `synced_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Last update timestamp for image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `vendor_features`
--

INSERT INTO `vendor_features` (`id`, `feature_id`, `feature_slug`, `feature_name`, `image`, `is_enabled`, `has_variations`, `has_size`, `has_colour`, `synced_at`, `updated_at`) VALUES
(1, 1, 'bookset', 'Bookset', NULL, 0, 0, 0, 0, '2026-01-22 11:10:49', '2026-01-22 11:10:49'),
(2, 3, 'uniforms', 'Uniforms', NULL, 1, 0, 0, 0, '2026-01-22 11:10:50', '2026-01-22 11:10:50'),
(3, 4, 'stationery', 'Stationery', NULL, 0, 0, 0, 0, '2026-01-22 11:10:50', '2026-01-22 11:10:50'),
(4, 5, 'bags', 'Bags', NULL, 0, 0, 0, 0, '2026-01-22 11:10:50', '2026-01-22 11:10:50'),
(5, 6, 'sports', 'Sports', NULL, 0, 0, 0, 0, '2026-01-22 11:10:50', '2026-01-22 11:10:50'),
(6, 11, 'textbook', 'textbook', NULL, 0, 0, 0, 0, '2026-01-22 11:10:50', '2026-01-22 11:10:50'),
(7, 12, 'notebooks', 'notebooks', NULL, 0, 0, 0, 0, '2026-01-22 11:10:50', '2026-01-22 11:10:50'),
(8, 13, 'individual-products', 'individual products', NULL, 1, 1, 1, 1, '2026-01-22 11:10:50', '2026-01-22 11:10:50');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_feature_subcategories`
--

CREATE TABLE `vendor_feature_subcategories` (
  `id` int(11) UNSIGNED NOT NULL,
  `feature_id` int(11) UNSIGNED NOT NULL,
  `subcategory_id` int(11) UNSIGNED NOT NULL,
  `subcategory_slug` varchar(255) NOT NULL,
  `subcategory_name` varchar(255) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `synced_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_site_settings`
--

CREATE TABLE `vendor_site_settings` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL COMMENT 'Vendor ID (each vendor has their own database)',
  `site_title` varchar(255) DEFAULT NULL,
  `site_description` text DEFAULT NULL,
  `logo_path` varchar(500) DEFAULT NULL,
  `favicon_path` varchar(500) DEFAULT NULL,
  `primary_color` varchar(7) DEFAULT '#116B31',
  `secondary_color` varchar(7) DEFAULT '#ffffff',
  `accent_color` varchar(7) DEFAULT '#28a745',
  `header_bg_color` varchar(7) DEFAULT '#ffffff',
  `footer_bg_color` varchar(7) DEFAULT '#f8f9fa',
  `text_primary_color` varchar(7) DEFAULT '#333333',
  `text_secondary_color` varchar(7) DEFAULT '#666666',
  `link_color` varchar(7) DEFAULT '#116B31',
  `link_hover_color` varchar(7) DEFAULT '#0d5a26',
  `button_primary_bg` varchar(7) DEFAULT '#116B31',
  `button_primary_text` varchar(7) DEFAULT '#ffffff',
  `button_secondary_bg` varchar(7) DEFAULT '#6c757d',
  `button_secondary_text` varchar(7) DEFAULT '#ffffff',
  `modal_bg_gradient_start` varchar(7) DEFAULT '#116B31' COMMENT 'Modal background gradient start color',
  `modal_bg_gradient_end` varchar(7) DEFAULT '#28a745' COMMENT 'Modal background gradient end color',
  `modal_button_bg` varchar(7) DEFAULT '#ffffff' COMMENT 'Modal button background color',
  `modal_button_text` varchar(7) DEFAULT '#116B31' COMMENT 'Modal button text color',
  `since_text` varchar(255) DEFAULT 'SINCE 1952' COMMENT 'Since text displayed in modal',
  `custom_css` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL COMMENT 'Custom meta title for SEO',
  `meta_keywords` text DEFAULT NULL COMMENT 'Custom meta keywords for SEO',
  `meta_description` text DEFAULT NULL COMMENT 'Custom meta description for SEO',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `banner_image` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_parent_id` (`parent_id`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_state_id` (`state_id`),
  ADD KEY `idx_country_id` (`country_id`);

--
-- Indexes for table `client_settings`
--
ALTER TABLE `client_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_setting_key` (`setting_key`);

--
-- Indexes for table `client_users`
--
ALTER TABLE `client_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_iso_code` (`iso_code`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `erp_booksets`
--
ALTER TABLE `erp_booksets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_board_id` (`board_id`),
  ADD KEY `idx_grade_id` (`grade_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_has_products` (`has_products`);

--
-- Indexes for table `erp_bookset_packages`
--
ALTER TABLE `erp_bookset_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_board_id` (`board_id`),
  ADD KEY `idx_grade_id` (`grade_id`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_is_it` (`is_it`),
  ADD KEY `idx_with_product` (`with_product`),
  ADD KEY `idx_bookset_id` (`bookset_id`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `erp_bookset_package_products`
--
ALTER TABLE `erp_bookset_package_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_package_id` (`package_id`),
  ADD KEY `idx_product_type` (`product_type`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_clients`
--
ALTER TABLE `erp_clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domain` (`domain`),
  ADD UNIQUE KEY `database_name` (`database_name`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_domain` (`domain`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_sidebar_color` (`sidebar_color`),
  ADD KEY `idx_logo` (`logo`),
  ADD KEY `idx_payment_gateway` (`payment_gateway`),
  ADD KEY `idx_site_title` (`site_title`);

--
-- Indexes for table `erp_client_features`
--
ALTER TABLE `erp_client_features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_client_feature` (`client_id`,`feature_id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_feature_id` (`feature_id`);

--
-- Indexes for table `erp_client_feature_subcategories`
--
ALTER TABLE `erp_client_feature_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_client_feature_subcategory` (`client_id`,`feature_id`,`subcategory_id`),
  ADD KEY `idx_client_id` (`client_id`),
  ADD KEY `idx_feature_id` (`feature_id`),
  ADD KEY `idx_subcategory_id` (`subcategory_id`);

--
-- Indexes for table `erp_client_settings`
--
ALTER TABLE `erp_client_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_id` (`client_id`),
  ADD KEY `idx_client_id` (`client_id`);

--
-- Indexes for table `erp_features`
--
ALTER TABLE `erp_features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_parent_id` (`parent_id`);

--
-- Indexes for table `erp_individual_products`
--
ALTER TABLE `erp_individual_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `size_chart_id` (`size_chart_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `erp_individual_product_categories`
--
ALTER TABLE `erp_individual_product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `status` (`status`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `erp_individual_product_category_mapping`
--
ALTER TABLE `erp_individual_product_category_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_category` (`product_id`,`category_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `erp_individual_product_colors`
--
ALTER TABLE `erp_individual_product_colors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `erp_individual_product_color_mapping`
--
ALTER TABLE `erp_individual_product_color_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_color` (`product_id`,`color_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `color_id` (`color_id`);

--
-- Indexes for table `erp_individual_product_images`
--
ALTER TABLE `erp_individual_product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `is_main` (`is_main`);

--
-- Indexes for table `erp_individual_product_size_color_prices`
--
ALTER TABLE `erp_individual_product_size_color_prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_size_color` (`product_id`,`size_id`,`color_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`),
  ADD KEY `color_id` (`color_id`);

--
-- Indexes for table `erp_materials`
--
ALTER TABLE `erp_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_notebooks`
--
ALTER TABLE `erp_notebooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_brand_id` (`brand_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_is_individual` (`is_individual`),
  ADD KEY `idx_is_set` (`is_set`);

--
-- Indexes for table `erp_notebook_images`
--
ALTER TABLE `erp_notebook_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notebook_id` (`notebook_id`),
  ADD KEY `idx_image_order` (`image_order`),
  ADD KEY `idx_is_main` (`is_main`);

--
-- Indexes for table `erp_notebook_type_mapping`
--
ALTER TABLE `erp_notebook_type_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_notebook_type` (`notebook_id`,`type_id`),
  ADD KEY `idx_notebook_id` (`notebook_id`),
  ADD KEY `idx_type_id` (`type_id`);

--
-- Indexes for table `erp_orders`
--
ALTER TABLE `erp_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_order_number` (`order_number`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_order_date` (`order_date`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_order_status` (`order_status`),
  ADD KEY `idx_payment_order_status` (`payment_status`,`order_status`),
  ADD KEY `idx_customer_email` (`customer_email`),
  ADD KEY `idx_customer_name` (`customer_name`);

--
-- Indexes for table `erp_order_items`
--
ALTER TABLE `erp_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_product_type_id` (`product_type`,`product_id`),
  ADD KEY `idx_bookset_id` (`bookset_id`),
  ADD KEY `idx_package_id` (`package_id`);

--
-- Indexes for table `erp_order_status_history`
--
ALTER TABLE `erp_order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_status_type` (`status_type`);

--
-- Indexes for table `erp_product_variation_types`
--
ALTER TABLE `erp_product_variation_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_variation_type` (`product_id`,`variation_type_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variation_type_id` (`variation_type_id`);

--
-- Indexes for table `erp_schools`
--
ALTER TABLE `erp_schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_country_id` (`country_id`),
  ADD KEY `idx_state_id` (`state_id`),
  ADD KEY `idx_city_id` (`city_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_parent_school_id` (`parent_school_id`),
  ADD KEY `idx_is_branch` (`is_branch`);

--
-- Indexes for table `erp_school_boards`
--
ALTER TABLE `erp_school_boards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_board_name` (`board_name`);

--
-- Indexes for table `erp_school_boards_mapping`
--
ALTER TABLE `erp_school_boards_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_school_board` (`school_id`,`board_id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_board_id` (`board_id`);

--
-- Indexes for table `erp_school_branches`
--
ALTER TABLE `erp_school_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_state_id` (`state_id`),
  ADD KEY `idx_city_id` (`city_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_school_branches_country` (`country_id`);

--
-- Indexes for table `erp_school_images`
--
ALTER TABLE `erp_school_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `erp_sizes`
--
ALTER TABLE `erp_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_size_chart_id` (`size_chart_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_display_order` (`display_order`);

--
-- Indexes for table `erp_size_charts`
--
ALTER TABLE `erp_size_charts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_stationery`
--
ALTER TABLE `erp_stationery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_category_id` (`category_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_sku` (`sku`),
  ADD KEY `idx_product_code` (`product_code`),
  ADD KEY `idx_brand_id` (`brand_id`),
  ADD KEY `idx_colour_id` (`colour_id`),
  ADD KEY `idx_is_individual` (`is_individual`),
  ADD KEY `idx_is_set` (`is_set`);

--
-- Indexes for table `erp_stationery_brands`
--
ALTER TABLE `erp_stationery_brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_stationery_brand_mapping`
--
ALTER TABLE `erp_stationery_brand_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_stationery_brand` (`stationery_id`,`brand_id`),
  ADD KEY `idx_stationery_id` (`stationery_id`),
  ADD KEY `idx_brand_id` (`brand_id`);

--
-- Indexes for table `erp_stationery_categories`
--
ALTER TABLE `erp_stationery_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_stationery_colours`
--
ALTER TABLE `erp_stationery_colours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_stationery_colour_mapping`
--
ALTER TABLE `erp_stationery_colour_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_stationery_colour` (`stationery_id`,`colour_id`),
  ADD KEY `idx_stationery_id` (`stationery_id`),
  ADD KEY `idx_colour_id` (`colour_id`);

--
-- Indexes for table `erp_stationery_images`
--
ALTER TABLE `erp_stationery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_stationery_id` (`stationery_id`),
  ADD KEY `idx_image_order` (`image_order`),
  ADD KEY `idx_is_main` (`is_main`);

--
-- Indexes for table `erp_textbooks`
--
ALTER TABLE `erp_textbooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_publisher_id` (`publisher_id`),
  ADD KEY `idx_board_id` (`board_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_isbn` (`isbn`),
  ADD KEY `idx_sku` (`sku`),
  ADD KEY `idx_product_code` (`product_code`),
  ADD KEY `idx_is_individual` (`is_individual`),
  ADD KEY `idx_is_set` (`is_set`);

--
-- Indexes for table `erp_textbook_ages`
--
ALTER TABLE `erp_textbook_ages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_textbook_age_mapping`
--
ALTER TABLE `erp_textbook_age_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_textbook_age` (`textbook_id`,`age_id`),
  ADD KEY `idx_textbook_id` (`textbook_id`),
  ADD KEY `idx_age_id` (`age_id`);

--
-- Indexes for table `erp_textbook_grades`
--
ALTER TABLE `erp_textbook_grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_textbook_grade_mapping`
--
ALTER TABLE `erp_textbook_grade_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_textbook_grade` (`textbook_id`,`grade_id`),
  ADD KEY `idx_textbook_id` (`textbook_id`),
  ADD KEY `idx_grade_id` (`grade_id`);

--
-- Indexes for table `erp_textbook_images`
--
ALTER TABLE `erp_textbook_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_textbook_id` (`textbook_id`),
  ADD KEY `idx_image_order` (`image_order`),
  ADD KEY `idx_is_main` (`is_main`);

--
-- Indexes for table `erp_textbook_publishers`
--
ALTER TABLE `erp_textbook_publishers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_textbook_subjects`
--
ALTER TABLE `erp_textbook_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_textbook_subject_mapping`
--
ALTER TABLE `erp_textbook_subject_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_textbook_subject` (`textbook_id`,`subject_id`),
  ADD KEY `idx_textbook_id` (`textbook_id`),
  ADD KEY `idx_subject_id` (`subject_id`);

--
-- Indexes for table `erp_textbook_types`
--
ALTER TABLE `erp_textbook_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_textbook_type_mapping`
--
ALTER TABLE `erp_textbook_type_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_textbook_type` (`textbook_id`,`type_id`),
  ADD KEY `idx_textbook_id` (`textbook_id`),
  ADD KEY `idx_type_id` (`type_id`);

--
-- Indexes for table `erp_uniforms`
--
ALTER TABLE `erp_uniforms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_uniform_type_id` (`uniform_type_id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_branch_id` (`branch_id`),
  ADD KEY `idx_board_id` (`board_id`),
  ADD KEY `idx_material_id` (`material_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_is_individual` (`is_individual`),
  ADD KEY `idx_is_set` (`is_set`);

--
-- Indexes for table `erp_uniform_images`
--
ALTER TABLE `erp_uniform_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uniform_id` (`uniform_id`),
  ADD KEY `idx_is_main` (`is_main`);

--
-- Indexes for table `erp_uniform_size_prices`
--
ALTER TABLE `erp_uniform_size_prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_uniform_size` (`uniform_id`,`size_id`),
  ADD KEY `idx_uniform_id` (`uniform_id`),
  ADD KEY `idx_size_id` (`size_id`);

--
-- Indexes for table `erp_uniform_types`
--
ALTER TABLE `erp_uniform_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_users`
--
ALTER TABLE `erp_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role_id` (`role_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `erp_user_roles`
--
ALTER TABLE `erp_user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_name` (`name`);

--
-- Indexes for table `erp_variation_combinations`
--
ALTER TABLE `erp_variation_combinations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_combination` (`product_id`,`combination_key`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `erp_variation_combination_prices`
--
ALTER TABLE `erp_variation_combination_prices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_combination_price` (`product_id`,`combination_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `combination_id` (`combination_id`);

--
-- Indexes for table `erp_variation_combination_values`
--
ALTER TABLE `erp_variation_combination_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_combination_variation` (`combination_id`,`variation_type_id`),
  ADD KEY `combination_id` (`combination_id`),
  ADD KEY `variation_type_id` (`variation_type_id`),
  ADD KEY `variation_value_id` (`variation_value_id`);

--
-- Indexes for table `erp_variation_types`
--
ALTER TABLE `erp_variation_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `erp_variation_values`
--
ALTER TABLE `erp_variation_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variation_type_id` (`variation_type_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discount_code` (`discount_code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_customer_id` (`customer_id`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `order_user_invoice`
--
ALTER TABLE `order_user_invoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `products_category`
--
ALTER TABLE `products_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_category` (`product_id`,`category_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_category_id` (`category_id`);

--
-- Indexes for table `products_warehouse_qty`
--
ALTER TABLE `products_warehouse_qty`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product_warehouse` (`product_id`,`warehouse_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_warehouse_id` (`warehouse_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_login`
--
ALTER TABLE `school_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_school_username` (`school_id`,`username`),
  ADD KEY `idx_school_id` (`school_id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_country_id` (`country_id`);

--
-- Indexes for table `tbl_order_address`
--
ALTER TABLE `tbl_order_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`,`address_id`,`user_id`,`is_default`,`created_at`);

--
-- Indexes for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`inv_type`,`order_id`,`user_id`,`category_id`,`product_id`),
  ADD KEY `inv_type` (`inv_type`,`order_id`,`user_id`,`category_id`,`product_id`);

--
-- Indexes for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`order_id`,`payment_id`,`razorpay_order_id`,`date`,`status`);

--
-- Indexes for table `temp_user`
--
ALTER TABLE `temp_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_phone` (`user_phone`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_features`
--
ALTER TABLE `vendor_features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_feature_slug` (`feature_slug`),
  ADD KEY `idx_feature_id` (`feature_id`),
  ADD KEY `idx_is_enabled` (`is_enabled`),
  ADD KEY `idx_image` (`image`);

--
-- Indexes for table `vendor_feature_subcategories`
--
ALTER TABLE `vendor_feature_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_subcategory` (`feature_id`,`subcategory_id`),
  ADD KEY `idx_feature_id` (`feature_id`),
  ADD KEY `idx_is_enabled` (`is_enabled`);

--
-- Indexes for table `vendor_site_settings`
--
ALTER TABLE `vendor_site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_vendor` (`vendor_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47960;

--
-- AUTO_INCREMENT for table `client_settings`
--
ALTER TABLE `client_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_users`
--
ALTER TABLE `client_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_booksets`
--
ALTER TABLE `erp_booksets`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `erp_bookset_packages`
--
ALTER TABLE `erp_bookset_packages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `erp_bookset_package_products`
--
ALTER TABLE `erp_bookset_package_products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `erp_clients`
--
ALTER TABLE `erp_clients`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `erp_client_features`
--
ALTER TABLE `erp_client_features`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=477;

--
-- AUTO_INCREMENT for table `erp_client_feature_subcategories`
--
ALTER TABLE `erp_client_feature_subcategories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `erp_client_settings`
--
ALTER TABLE `erp_client_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `erp_features`
--
ALTER TABLE `erp_features`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `erp_individual_products`
--
ALTER TABLE `erp_individual_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_individual_product_categories`
--
ALTER TABLE `erp_individual_product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_individual_product_category_mapping`
--
ALTER TABLE `erp_individual_product_category_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_individual_product_colors`
--
ALTER TABLE `erp_individual_product_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_individual_product_color_mapping`
--
ALTER TABLE `erp_individual_product_color_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_individual_product_images`
--
ALTER TABLE `erp_individual_product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_individual_product_size_color_prices`
--
ALTER TABLE `erp_individual_product_size_color_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_materials`
--
ALTER TABLE `erp_materials`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `erp_notebooks`
--
ALTER TABLE `erp_notebooks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `erp_notebook_images`
--
ALTER TABLE `erp_notebook_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `erp_notebook_type_mapping`
--
ALTER TABLE `erp_notebook_type_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `erp_orders`
--
ALTER TABLE `erp_orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `erp_order_items`
--
ALTER TABLE `erp_order_items`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `erp_order_status_history`
--
ALTER TABLE `erp_order_status_history`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `erp_product_variation_types`
--
ALTER TABLE `erp_product_variation_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_schools`
--
ALTER TABLE `erp_schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `erp_school_boards`
--
ALTER TABLE `erp_school_boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `erp_school_boards_mapping`
--
ALTER TABLE `erp_school_boards_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `erp_school_branches`
--
ALTER TABLE `erp_school_branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `erp_school_images`
--
ALTER TABLE `erp_school_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `erp_sizes`
--
ALTER TABLE `erp_sizes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT for table `erp_size_charts`
--
ALTER TABLE `erp_size_charts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `erp_stationery`
--
ALTER TABLE `erp_stationery`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `erp_stationery_brands`
--
ALTER TABLE `erp_stationery_brands`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_stationery_brand_mapping`
--
ALTER TABLE `erp_stationery_brand_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_stationery_categories`
--
ALTER TABLE `erp_stationery_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `erp_stationery_colours`
--
ALTER TABLE `erp_stationery_colours`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_stationery_colour_mapping`
--
ALTER TABLE `erp_stationery_colour_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_stationery_images`
--
ALTER TABLE `erp_stationery_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_textbooks`
--
ALTER TABLE `erp_textbooks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `erp_textbook_ages`
--
ALTER TABLE `erp_textbook_ages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_textbook_age_mapping`
--
ALTER TABLE `erp_textbook_age_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_textbook_grades`
--
ALTER TABLE `erp_textbook_grades`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `erp_textbook_grade_mapping`
--
ALTER TABLE `erp_textbook_grade_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `erp_textbook_images`
--
ALTER TABLE `erp_textbook_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `erp_textbook_publishers`
--
ALTER TABLE `erp_textbook_publishers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `erp_textbook_subjects`
--
ALTER TABLE `erp_textbook_subjects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `erp_textbook_subject_mapping`
--
ALTER TABLE `erp_textbook_subject_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `erp_textbook_types`
--
ALTER TABLE `erp_textbook_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `erp_textbook_type_mapping`
--
ALTER TABLE `erp_textbook_type_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `erp_uniforms`
--
ALTER TABLE `erp_uniforms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `erp_uniform_images`
--
ALTER TABLE `erp_uniform_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT for table `erp_uniform_size_prices`
--
ALTER TABLE `erp_uniform_size_prices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `erp_uniform_types`
--
ALTER TABLE `erp_uniform_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `erp_users`
--
ALTER TABLE `erp_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `erp_user_roles`
--
ALTER TABLE `erp_user_roles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `erp_variation_combinations`
--
ALTER TABLE `erp_variation_combinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_variation_combination_prices`
--
ALTER TABLE `erp_variation_combination_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_variation_combination_values`
--
ALTER TABLE `erp_variation_combination_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_variation_types`
--
ALTER TABLE `erp_variation_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_variation_values`
--
ALTER TABLE `erp_variation_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_user_invoice`
--
ALTER TABLE `order_user_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_category`
--
ALTER TABLE `products_category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_warehouse_qty`
--
ALTER TABLE `products_warehouse_qty`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_login`
--
ALTER TABLE `school_login`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1588;

--
-- AUTO_INCREMENT for table `tbl_order_address`
--
ALTER TABLE `tbl_order_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `temp_user`
--
ALTER TABLE `temp_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendor_features`
--
ALTER TABLE `vendor_features`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `vendor_feature_subcategories`
--
ALTER TABLE `vendor_feature_subcategories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_site_settings`
--
ALTER TABLE `vendor_site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `fk_cities_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cities_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_bookset_packages`
--
ALTER TABLE `erp_bookset_packages`
  ADD CONSTRAINT `fk_bookset_packages_board` FOREIGN KEY (`board_id`) REFERENCES `erp_school_boards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bookset_packages_grade` FOREIGN KEY (`grade_id`) REFERENCES `erp_textbook_grades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bookset_packages_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_bookset_package_products`
--
ALTER TABLE `erp_bookset_package_products`
  ADD CONSTRAINT `fk_bookset_package_products_package` FOREIGN KEY (`package_id`) REFERENCES `erp_bookset_packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_client_features`
--
ALTER TABLE `erp_client_features`
  ADD CONSTRAINT `fk_client_features_client` FOREIGN KEY (`client_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_client_features_feature` FOREIGN KEY (`feature_id`) REFERENCES `erp_features` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_client_feature_subcategories`
--
ALTER TABLE `erp_client_feature_subcategories`
  ADD CONSTRAINT `fk_client_feature_subcategories_client` FOREIGN KEY (`client_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_client_feature_subcategories_feature` FOREIGN KEY (`feature_id`) REFERENCES `erp_features` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_client_feature_subcategories_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `erp_features` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_client_settings`
--
ALTER TABLE `erp_client_settings`
  ADD CONSTRAINT `fk_client_settings_client` FOREIGN KEY (`client_id`) REFERENCES `erp_clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_features`
--
ALTER TABLE `erp_features`
  ADD CONSTRAINT `fk_features_parent` FOREIGN KEY (`parent_id`) REFERENCES `erp_features` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_individual_product_categories`
--
ALTER TABLE `erp_individual_product_categories`
  ADD CONSTRAINT `fk_category_parent` FOREIGN KEY (`parent_id`) REFERENCES `erp_individual_product_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_notebooks`
--
ALTER TABLE `erp_notebooks`
  ADD CONSTRAINT `fk_notebooks_brand` FOREIGN KEY (`brand_id`) REFERENCES `erp_textbook_publishers` (`id`);

--
-- Constraints for table `erp_notebook_images`
--
ALTER TABLE `erp_notebook_images`
  ADD CONSTRAINT `fk_notebook_images_notebook` FOREIGN KEY (`notebook_id`) REFERENCES `erp_notebooks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_notebook_type_mapping`
--
ALTER TABLE `erp_notebook_type_mapping`
  ADD CONSTRAINT `fk_notebook_type_mapping_notebook` FOREIGN KEY (`notebook_id`) REFERENCES `erp_notebooks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notebook_type_mapping_type` FOREIGN KEY (`type_id`) REFERENCES `erp_textbook_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_order_items`
--
ALTER TABLE `erp_order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `erp_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_order_status_history`
--
ALTER TABLE `erp_order_status_history`
  ADD CONSTRAINT `fk_order_status_history_order` FOREIGN KEY (`order_id`) REFERENCES `erp_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_schools`
--
ALTER TABLE `erp_schools`
  ADD CONSTRAINT `fk_schools_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `fk_schools_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `fk_schools_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Constraints for table `erp_school_boards_mapping`
--
ALTER TABLE `erp_school_boards_mapping`
  ADD CONSTRAINT `fk_school_boards_mapping_board` FOREIGN KEY (`board_id`) REFERENCES `erp_school_boards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_school_boards_mapping_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_school_branches`
--
ALTER TABLE `erp_school_branches`
  ADD CONSTRAINT `fk_school_branches_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `fk_school_branches_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `fk_school_branches_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_school_branches_state` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`);

--
-- Constraints for table `erp_school_images`
--
ALTER TABLE `erp_school_images`
  ADD CONSTRAINT `fk_school_images_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_sizes`
--
ALTER TABLE `erp_sizes`
  ADD CONSTRAINT `fk_sizes_size_chart` FOREIGN KEY (`size_chart_id`) REFERENCES `erp_size_charts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_stationery`
--
ALTER TABLE `erp_stationery`
  ADD CONSTRAINT `fk_stationery_brand` FOREIGN KEY (`brand_id`) REFERENCES `erp_stationery_brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_stationery_category` FOREIGN KEY (`category_id`) REFERENCES `erp_stationery_categories` (`id`),
  ADD CONSTRAINT `fk_stationery_colour` FOREIGN KEY (`colour_id`) REFERENCES `erp_stationery_colours` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `erp_stationery_brand_mapping`
--
ALTER TABLE `erp_stationery_brand_mapping`
  ADD CONSTRAINT `fk_stationery_brand_mapping_brand` FOREIGN KEY (`brand_id`) REFERENCES `erp_stationery_brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_stationery_brand_mapping_stationery` FOREIGN KEY (`stationery_id`) REFERENCES `erp_stationery` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_stationery_colour_mapping`
--
ALTER TABLE `erp_stationery_colour_mapping`
  ADD CONSTRAINT `fk_stationery_colour_mapping_colour` FOREIGN KEY (`colour_id`) REFERENCES `erp_stationery_colours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_stationery_colour_mapping_stationery` FOREIGN KEY (`stationery_id`) REFERENCES `erp_stationery` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_stationery_images`
--
ALTER TABLE `erp_stationery_images`
  ADD CONSTRAINT `fk_stationery_images_stationery` FOREIGN KEY (`stationery_id`) REFERENCES `erp_stationery` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_textbooks`
--
ALTER TABLE `erp_textbooks`
  ADD CONSTRAINT `fk_textbooks_board` FOREIGN KEY (`board_id`) REFERENCES `erp_school_boards` (`id`),
  ADD CONSTRAINT `fk_textbooks_publisher` FOREIGN KEY (`publisher_id`) REFERENCES `erp_textbook_publishers` (`id`);

--
-- Constraints for table `erp_textbook_age_mapping`
--
ALTER TABLE `erp_textbook_age_mapping`
  ADD CONSTRAINT `fk_textbook_age_mapping_age` FOREIGN KEY (`age_id`) REFERENCES `erp_textbook_ages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_textbook_age_mapping_textbook` FOREIGN KEY (`textbook_id`) REFERENCES `erp_textbooks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_textbook_grade_mapping`
--
ALTER TABLE `erp_textbook_grade_mapping`
  ADD CONSTRAINT `fk_textbook_grade_mapping_grade` FOREIGN KEY (`grade_id`) REFERENCES `erp_textbook_grades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_textbook_grade_mapping_textbook` FOREIGN KEY (`textbook_id`) REFERENCES `erp_textbooks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_textbook_images`
--
ALTER TABLE `erp_textbook_images`
  ADD CONSTRAINT `fk_textbook_images_textbook` FOREIGN KEY (`textbook_id`) REFERENCES `erp_textbooks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_textbook_subject_mapping`
--
ALTER TABLE `erp_textbook_subject_mapping`
  ADD CONSTRAINT `fk_textbook_subject_mapping_subject` FOREIGN KEY (`subject_id`) REFERENCES `erp_textbook_subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_textbook_subject_mapping_textbook` FOREIGN KEY (`textbook_id`) REFERENCES `erp_textbooks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_textbook_type_mapping`
--
ALTER TABLE `erp_textbook_type_mapping`
  ADD CONSTRAINT `fk_textbook_type_mapping_textbook` FOREIGN KEY (`textbook_id`) REFERENCES `erp_textbooks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_textbook_type_mapping_type` FOREIGN KEY (`type_id`) REFERENCES `erp_textbook_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_uniform_images`
--
ALTER TABLE `erp_uniform_images`
  ADD CONSTRAINT `fk_uniform_images_uniform` FOREIGN KEY (`uniform_id`) REFERENCES `erp_uniforms` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
