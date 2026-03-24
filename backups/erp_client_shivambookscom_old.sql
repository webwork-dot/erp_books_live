-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 03, 2026 at 07:14 PM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `erp_client_shivambookscom`
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
  `location` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `lattitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT 101 COMMENT '101 = India',
  `state_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Cities';

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

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('rh1j8ed47ufavc0psb7a1idkjiu4r7l0', '172.70.218.174', 1770117246, ''),
('npqrk3aem81h3ncbal2p2j396f1vmft8', '162.158.174.155', 1770036298, ''),
('p3r6ns7sj5vgnlkikt6ck38r2t30799e', '162.158.216.200', 1770036312, ''),
('6efrgailmenue6i2hcgeh34f23k8a637', '172.64.192.102', 1770036307, ''),
('7bknoe5k93d89bidqfpkossgn0mcnbhr', '162.158.41.81', 1770036340, ''),
('forfm3iic89dlcebn2ckedbmv1cpd7t1', '108.162.245.75', 1770036340, ''),
('pt71tack5js50bmbedpaffvpsokot3jv', '172.68.70.240', 1770036341, ''),
('mh3m6ar9gtsrhpqiu9i0ngl9fm1idg7g', '108.162.237.65', 1770036341, ''),
('ivde3hppiepqv476kboc8qr1ljfgn7v6', '172.68.26.156', 1770036927, ''),
('rhfa84kpjq5u631q68563bencg180m66', '172.70.208.154', 1770037795, ''),
('kadcs8gkth9ppiu5nag7b1pdt2se2tdg', '172.69.151.100', 1770038382, ''),
('0ogau9h65301kranummheas1vlvedegu', '172.68.195.176', 1770038383, ''),
('om1lpgdodtnhfg4181afcu0m00amtu46', '172.68.195.176', 1770038383, ''),
('svpic8ps294rf9k8t7ujjf8ae5sg68bd', '172.68.195.176', 1770038384, ''),
('sgl6h4frttl0160srqa7fganmbulkptj', '162.158.235.78', 1770038419, ''),
('skvi80785frjlvpbfd70560qqonl5h3f', '162.158.235.193', 1770038419, ''),
('pop7hfl0ijscmpdhr0e5fuku78f81g64', '162.158.235.78', 1770038420, ''),
('iis80imlg2a86juf95p9j7ags3ovb8pi', '162.158.235.27', 1770038421, ''),
('elo6qmjhlqmks0jc4jledgjn1gp1npjb', '162.158.227.128', 1770038421, ''),
('pp3ah8qhvc6epo6507io9g8chiotemva', '172.69.179.206', 1770038532, ''),
('p6p4ahvmi3h61gr2b1vm3jos9hfv33k6', '172.69.179.206', 1770038532, ''),
('3kvji0a4f2uc7sbq6jq71pnv6tvu2qig', '172.69.179.206', 1770038535, ''),
('pqpc99oojmmmprtgcl1o4m70n8rav5bb', '172.69.179.206', 1770038535, ''),
('pd2rnnujl9hu9pn2h26gh3e9n7ijus8v', '172.69.178.60', 1770038535, ''),
('s9jb37haon48f2bger79tktn39d9b0ng', '172.69.178.165', 1770038545, ''),
('cetjjjkj6fnlinteq4a9p6m33fjrl6o2', '172.69.178.249', 1770038545, ''),
('oep1tct1t73ob8dcqvag5rm2jjork4ro', '172.69.165.43', 1770038577, ''),
('a6ckd897rd1bnbrcbpm1dgdfvklceuv1', '172.69.165.4', 1770038578, ''),
('4ub68f16nrihdlq08etmmbc81fflktqb', '172.69.165.4', 1770038578, ''),
('nbkufak0mk1gbssh8vgnqrg84rirfnkh', '172.69.165.4', 1770038578, ''),
('reg4jjlgubdvdcrsm7leo4q3gnqfk7ip', '162.158.235.77', 1770039940, ''),
('8s7t52qucv7u42cokf8vl10t23qnc8ff', '162.158.235.77', 1770039941, ''),
('6iij7fob9nl2io78br4oef777tct94l7', '162.158.227.131', 1770101936, ''),
('c973rolk5ont98g1q4te2i99asudhru7', '172.69.179.205', 1770041038, ''),
('99looevcuaaa7t2lc0e3iccgdvj8pl4o', '172.69.179.205', 1770041039, ''),
('c814hbv5a204qeo822siu8g5o6dea998', '172.69.178.60', 1770041039, ''),
('pm50ppberobtqiuiq8ucu3dp258um8hp', '172.69.178.248', 1770041040, ''),
('2dqcsph63eabq4rp61bg4rlbrgfglmd3', '172.69.178.165', 1770041040, ''),
('ua42v881fpoekeempt6150u5tsu1q2s9', '172.69.179.205', 1770041133, ''),
('ti9hvq2n749k2rdoiljjr9fjolqais11', '172.69.179.205', 1770041134, ''),
('ogq1tneajfkpvuu6r1gjqb7157pqv3e5', '172.71.198.64', 1770041203, ''),
('sl2mi2d9php11cm349kdks6ehejvt5ie', '172.71.198.64', 1770041203, ''),
('2q5frpavmorb4ok8m7ne702pa5ki29g0', '172.71.198.64', 1770041216, ''),
('jhtiisp06u584ib0k2pnrhof53huh24o', '172.71.198.64', 1770041217, ''),
('3f0csrs465aggb2ug19qqt7o30rc39on', '172.71.198.163', 1770041217, ''),
('64q0mli3cg189q0jvitagfnlu3l6v82u', '172.71.198.195', 1770041219, ''),
('402s335u0o8kpn9r0u7n456av6kbime6', '172.71.198.180', 1770041219, ''),
('pqc9dk9j3hmuqvdkqs9toqg2rpe9rvf1', '162.159.98.33', 1770041648, ''),
('3stbsdnm80d0bkoh8ncdfo4q5i2i084m', '162.159.98.191', 1770041649, ''),
('ag5og32r1mo1s286v1odpe19pb8gae3k', '162.159.98.191', 1770041649, ''),
('ff54ue3id5d9kp57e7nqh6vi83douldm', '162.159.98.191', 1770041649, ''),
('f0bn9udthbrf8ejmbdti1r17u028ill8', '172.69.166.82', 1770041900, ''),
('se9fpt31h6dskv1gefpk9l04i6n04efd', '172.70.143.203', 1770041902, ''),
('v3bltradkjljtf5ge5dot72h4updusr3', '172.70.143.203', 1770041902, ''),
('jq8srv038olspbd4p2ri6cqll9b3tqdb', '172.70.143.203', 1770041903, ''),
('r9q1cok6on6qua4f0q4n88ni1217puc5', '162.158.227.131', 1770042250, ''),
('eueqe2j65gknt2tuoj4gii8m3in6phdk', '172.71.203.24', 1770042983, ''),
('m05clcr7tee4lhpv5n94ki65vdjnsn75', '104.23.190.69', 1770042992, ''),
('r6dic95ja2nlvsia9llihak5guvrv82g', '172.71.198.73', 1770043352, ''),
('9og7ajf1obhhjantpt31a7m6sc6rtv5l', '162.159.98.33', 1770043854, ''),
('mqrt2a8pn9hn6n1uqbb8qk43tis84h0g', '162.159.98.33', 1770043854, ''),
('md35cbchk7gm9oghc3moj86uqro3do0e', '162.159.98.33', 1770043854, ''),
('pmtn1u2v6s2t7rf9pblpu53ht34khnm4', '162.159.98.33', 1770043854, ''),
('b1o0gea5a4i8t7001s3qamslv85fei2v', '172.70.208.155', 1770043957, ''),
('29rqqgen216jp42f0jqp5ui3urctkosm', '172.70.208.90', 1770043958, ''),
('lnuqc4hlucectsaqk5qcjdr866ma9qc1', '172.70.208.90', 1770043958, ''),
('etauvhfohroubsdn703m82qfbbqj2mib', '172.70.208.90', 1770043958, ''),
('2s8a3nd145t8p2uoafgcomlepvfoe4f2', '162.159.98.32', 1770044157, ''),
('j7gf4p8v1vn0jj8cp0orp2934p7c6d1o', '162.159.98.32', 1770044157, ''),
('nnnmist33blhj196om74d8qt7gkh4v9s', '162.159.98.32', 1770044157, ''),
('707cmvs5u9mqsnijcf4lgjbc217jkqpb', '162.159.98.32', 1770044157, ''),
('n01ud44n0fn44r959d7gocdipq15q7iv', '141.101.97.67', 1770044243, ''),
('egi8bdobr151u73ssu226dauvs2591vg', '162.158.108.67', 1770044720, ''),
('jeeeahad0dd11cplja8f85dmlilj66m2', '162.158.108.67', 1770044720, ''),
('4a33htjoucs0jleeegd891t50ejuo9v1', '162.158.108.67', 1770044720, ''),
('ar0aitrh924ks81fn8k33o1lqrnad5u1', '162.158.108.67', 1770044720, ''),
('vff380gontpv5trfmi1ei0f67hhs5nhp', '172.68.211.32', 1770045210, ''),
('ikos5rh1vhj1qnue01ueoeh1b1o9vfsv', '172.68.211.32', 1770045210, ''),
('7pa9g6keatdaq3d9sl9ch1ep9fi4od1v', '172.68.211.32', 1770045210, ''),
('ej21u6i2agkf8m9goi6ar9r5rp9q8d20', '172.68.211.32', 1770045210, ''),
('1g5go0kebdlhs7kcjgcdh08d9sum7hjt', '172.69.7.13', 1770046993, ''),
('oi194ts4beevr5b99tv53huf5krnqf9s', '172.71.81.104', 1770047395, ''),
('6k0jtj8g59mombqlgra8ivt7nokrl4si', '172.68.132.168', 1770047643, ''),
('d7csa35ik3425ttdgoqqnrkteep9omqc', '172.71.210.48', 1770047810, ''),
('ta43a6vrfvgotct9kh89vffo4aotu6b1', '172.71.210.48', 1770047810, ''),
('u8v1rte0efe4g79fvv3qmpq1dpaspoe0', '172.71.210.48', 1770047811, ''),
('boas3ffa0isp7kt6ebdflg8giu01l6jl', '172.71.210.48', 1770047811, ''),
('61lhc7kan5i0vja4if58rn4fl9mafuv2', '172.69.165.42', 1770050552, ''),
('e3modejit2f7ccg88s9qpqrac74jv6ul', '172.69.165.42', 1770050552, ''),
('qse28ugap8m6072pjccpe9v6081n62iu', '172.69.165.42', 1770050552, ''),
('ubjesialuaop90mjtehf0q3kn9ss02p0', '172.69.165.42', 1770050552, ''),
('siqoqmfsimvgirjbs2sf0lue1i9c2n7u', '141.101.97.66', 1770050578, ''),
('753h9tu8jm9iebft952tf6ua021pkuaf', '141.101.97.66', 1770050578, ''),
('el3t7lt8tlkvgmd2gu9ehvjd1nd1ob3m', '141.101.97.66', 1770050578, ''),
('uuc9bmmcr00chd1goii01f3kji0dv471', '141.101.97.66', 1770050579, ''),
('2ujqj71omghhgrpd37hqna2289ncqb0m', '172.68.211.32', 1770052145, ''),
('nkl7tgi4kmk6t1uuhlvho8kdmfm1ncj6', '141.101.84.207', 1770052994, ''),
('95m6m2dqaobretiq1mss149o6i7scvdd', '162.158.178.68', 1770053322, ''),
('gvsemdarj1pr004ekcgo3ou3gdjgft4s', '162.158.178.232', 1770053322, ''),
('1ksueguqar3oi80h7q3ltd6rkb47070d', '162.158.178.232', 1770053322, ''),
('pappaiqdpjmgm711p6fg7o3ll3fiq4cu', '162.158.178.232', 1770053323, ''),
('k9r6h9aqm4mq0l6be32h5tsetrcf5klc', '172.71.158.100', 1770053599, ''),
('77rd0hkn4m6p6v0jnfgoufhdsprgupma', '172.68.225.231', 1770054102, ''),
('sh49u6g43gr0ael95o1frvaqerrna3mu', '104.22.18.31', 1770054760, ''),
('g8vmb962dn5kfqfehv8sc759u86ulq9o', '172.64.217.202', 1770054847, ''),
('p2q8ctj3mcd6e7pbn5bqr1rvfpqhdb51', '172.64.217.202', 1770054848, ''),
('70dfflns6lvfac5qdiro2tsif9j1e73n', '172.64.217.202', 1770054848, ''),
('e5dh8qjbvphig1ng59qot4jh3qlda9rh', '172.71.154.27', 1770055393, ''),
('q1gc4bt0ms2bq3iamiv5caqhhi2b6lbc', '104.22.10.69', 1770055944, ''),
('82gai58d2d891ia6tv86b438b0fgqduk', '172.69.165.42', 1770056355, ''),
('0vh2trjs789cfr8r45gqcfbutgh3qpii', '172.69.165.42', 1770056355, ''),
('0arseq2dvn1gp24d50u0sk1erje9jdfv', '172.69.165.42', 1770056355, ''),
('74uupqbg7itl85j5uvbf99t5l2aea0rj', '172.69.165.42', 1770056355, ''),
('2uceqhiml75j1r4b0q51km8blv75iuuv', '104.22.18.31', 1770056439, ''),
('86usb1pa41ghmiuklnfec492c8cbdk5n', '172.70.126.17', 1770056890, ''),
('tu3r3tj992ctorjhltbrv9kdiv5ocfr2', '172.64.217.202', 1770056904, ''),
('bpl3eo0ipg16nemn8lsus1pqvv1mnuun', '172.64.217.100', 1770056905, ''),
('lj0pfsbgfbm837am671f3mrgk2dnbvqe', '172.64.217.100', 1770056905, ''),
('8gedorrmesfer1tjbmknnd68ls4fs3g3', '172.64.217.100', 1770056906, ''),
('i0hi53sq6ka7pc13dhohajbngvdrdjne', '172.69.134.82', 1770057126, ''),
('qki9omn8damr71u80gi4b9q55jgsrkkg', '172.69.195.131', 1770057614, ''),
('do1ut192eolbqgqnlj4snfm6jfuc87bk', '172.69.194.129', 1770057615, ''),
('rqq7rs1vabtp9nuseh5rnstulkbomoog', '172.69.194.129', 1770057615, ''),
('c94tu1uao3lnbs9gbtl7h6c841khq253', '172.69.194.129', 1770057615, ''),
('rditfej84fjqg81srcqt3p4sl3jkdcfr', '104.22.20.154', 1770057774, ''),
('jb09bsphpu73kp2rrl3mne48a46g54cu', '104.23.187.144', 1770057884, ''),
('1vf1lqvvn4h7t8nsn732pa7r600kqfno', '172.68.210.170', 1770058139, ''),
('se9btlobcts518b72ibua0stda6c43po', '172.68.210.40', 1770058141, ''),
('2rubekpj253f2mimlnnv49lsc73353ao', '172.68.210.40', 1770058142, ''),
('0jdltvlanpfducatn59ssubn3ivqal3p', '172.68.210.170', 1770058142, ''),
('6opsqteb9hkjg36qj6n5sbm1ivflta0d', '172.68.210.170', 1770058143, ''),
('m544do21rg0r8ebs254nc5uu3nj9a6hv', '172.68.210.170', 1770058144, ''),
('gsj6fbv4vjv00ahr4fk1i5buirs0k1vo', '172.68.210.170', 1770058144, ''),
('lpr3fq4v6k1i225o7432jjo5pc6k0e7u', '172.68.210.170', 1770058145, ''),
('khtnfm3a9f7r7mhh24osa0j4gj9lakra', '172.68.210.170', 1770058145, ''),
('tagth7kor4n7jf9s3m9ujf88s3u2f4mp', '172.68.210.170', 1770058146, ''),
('m2a4k8gamif80c6tmdjdluoitnbgklkt', '172.68.210.170', 1770058146, ''),
('c7gt48i0t34clfqdoh09srogm0un6dr1', '172.68.210.170', 1770058147, ''),
('6tuvo55u1uafajkuel4fh0j6tcq370ts', '172.68.210.170', 1770058147, ''),
('jtdofr0ps08n0bu81psge8fsnqqohjb7', '172.68.210.170', 1770058148, ''),
('ugjn75d0fril9tonl96jmqh2gofvru8b', '172.68.210.170', 1770058148, ''),
('h6qi9vf78u965eiu4tihe0acon50tdm5', '172.68.210.170', 1770058149, ''),
('e6ede8hkohduocrd01p90cvc72vpe44j', '172.68.210.170', 1770058149, ''),
('stt44dm3rd999e41fi4b8mk6d438e96t', '172.68.210.170', 1770058150, ''),
('u81ab558buqbgacijccdbmgh4ln4n6f0', '172.68.210.170', 1770058150, ''),
('dn33d8tkuto2jg6i12eefk867maa0kh6', '172.68.210.170', 1770058151, ''),
('n04b8rjve1l5fqt2j27ck994qjik2q65', '172.68.210.170', 1770058151, ''),
('onftbcqk1cubjk6dr70gjdoi28b6rtft', '172.68.210.170', 1770058152, ''),
('qmiejqb2i3gg2nbla9qcnvng99hv1obk', '172.68.210.170', 1770058152, ''),
('43t8j1ukhf58v23qkcef6djfi8v1shm4', '172.68.210.170', 1770058153, ''),
('ir5s94q63n0jpf4gofoam86adqvraq6r', '172.68.210.170', 1770058153, ''),
('3vpre0c99s2bfrpcvva3cnpdegbq90sk', '172.68.210.170', 1770058154, ''),
('jln8h7eau75b9hokt2lfjbb4paie3ntc', '172.68.210.170', 1770058155, ''),
('2fu16fhb57em7hfjsgmm39gbipu514n5', '172.68.210.170', 1770058155, ''),
('modtjph8jbpjahqb2tlnlkjkb94v34da', '172.68.210.170', 1770058156, ''),
('6mfok7shgu0qn6oqicb6lb4cvag44p7u', '172.68.210.170', 1770058156, ''),
('9pnskqn5mj3e7hnv9c22ptsbv74kmne6', '172.68.210.170', 1770058157, ''),
('9p1upaddfl7p4rmjiln66aocj4o5538u', '172.68.210.170', 1770058157, ''),
('2nje0vm21pvaac7l3t8935jt3op2n9jg', '172.68.210.170', 1770058158, ''),
('2jjqmjsqnf7b2pt837tqqqu5ljlatfs4', '172.68.210.170', 1770058158, ''),
('25punu8fqrst8f16vhr7ccbpt36i19vq', '172.68.210.170', 1770058159, ''),
('rh6ct1etrgpgq6e1ctckgp356lkds6ag', '172.68.210.170', 1770058159, ''),
('42dam7a7tvd1q305r9jukm9g6oganvgs', '172.68.210.170', 1770058160, ''),
('h4jf5f8mf2dgs3d990n389hc120l7ng9', '172.68.210.170', 1770058160, ''),
('16o6prs48aehh664js87ib0t9s9c48bl', '172.68.210.170', 1770058161, ''),
('k8t9eegg2a9iq461u7a0ul3jnsd182ks', '172.68.210.170', 1770058161, ''),
('iqdmp8kgbgec74r9iefn095jeil36u5s', '172.68.210.170', 1770058162, ''),
('48tjamdmdo18stchaujm50is8dkbqqju', '172.68.210.170', 1770058162, ''),
('j3vgpjfo954666be9ur6klt0ddd52shr', '172.68.210.170', 1770058163, ''),
('s85crq8vrqqu1htkhv8kud5k7q7rh66s', '172.68.210.170', 1770058163, ''),
('roe07rbpkca755sp236dc8c61q1d57v6', '172.68.210.170', 1770058164, ''),
('vids180hisslqdk9b7r5pa3lvcklcdj7', '172.68.210.170', 1770058164, ''),
('ccopbitrulr1oe6b98qnus60lf9gtgfe', '172.68.210.170', 1770058165, ''),
('15km2csamhanpkhr2vf6j4hbav3h5mlc', '172.68.210.170', 1770058165, ''),
('e9e2o155h7gl4u0ro8k89kldd7di36fn', '172.68.210.170', 1770058166, ''),
('p2mtciu4k6ds7s7p1re2595dij0bggp0', '172.68.210.170', 1770058166, ''),
('fqds5frq97o66uqffv1a0o3rc452mltr', '172.68.210.170', 1770058167, ''),
('nsus2gplmiil5fd15cm5qpt2uiv1q0n1', '172.68.210.170', 1770058167, ''),
('s3mpv79ol9f0d6hu46smpt0o80sa2vsi', '172.68.210.170', 1770058167, ''),
('m07g6c5bqp46gstk8g5lqtcmkcpr3443', '172.68.210.170', 1770058168, ''),
('dopaipd5hve1fg1t36730s9aug8h1rqo', '172.68.210.170', 1770058168, ''),
('e7a812nogjumh8a3eo066tmokdnhda5g', '172.68.210.170', 1770058169, ''),
('3ftdje5cr6ksdh1kpn8akj3pe6d2v7dj', '172.68.210.170', 1770058169, ''),
('ae93d75rs6p025oigpqm8u2gmnvso0aa', '172.68.210.170', 1770058170, ''),
('6h72spahdq8ogn1fi1iause9gd26k0m7', '172.68.210.170', 1770058170, ''),
('srhluo13glipfv5ggklsrjkk24a72n5n', '172.68.210.170', 1770058171, ''),
('2ktvh4144jghe8gc9svfd0h9hfcsrotd', '172.68.210.170', 1770058171, ''),
('cps23n6ld7rfv41tq56t311uc4oa9ajv', '172.68.210.170', 1770058172, ''),
('ahe2u3tj5l6jdupg5pgft4nbh24947f3', '172.68.210.170', 1770058172, ''),
('3qqcqo73p14tln7at5mon6uda2qkar9t', '172.68.210.170', 1770058173, ''),
('aqkdngrom4n0cr20tqsrped5fu7fa9lv', '172.68.210.170', 1770058173, ''),
('7q1f152lka8d485tkvi5um0nm4frh7f5', '172.68.210.170', 1770058174, ''),
('kq3b9312mscn3fgft226jejtjj2viesm', '172.68.210.170', 1770058174, ''),
('vef0phs091cb83fgh0irlf9psmpof2vf', '172.68.210.170', 1770058175, ''),
('kmjsv611rfekm60b691cs6u2rkrl1ft0', '172.68.210.170', 1770058175, ''),
('5uphsbrd5h8r6nbfm6tj6vag1unmeit8', '172.68.210.170', 1770058176, ''),
('l0p8casua2s2dpfqvpf3jtap4g9rbrij', '172.68.210.170', 1770058176, ''),
('ts05lmdsfdd2sh9ptambecbg2m729lrl', '172.68.210.170', 1770058177, ''),
('3cbja3daeq3hja0up6mtfjk720b18iqv', '172.68.210.170', 1770058177, ''),
('02ec46mlstr6fgd4ep00dd5bfaj786qu', '172.68.210.170', 1770058178, ''),
('vefiqo4k1q95gsm1haq02t89tv2sbc28', '172.68.210.170', 1770058178, ''),
('ngqqvn9pb81vioul2tho32bupurf4omb', '172.68.210.170', 1770058179, ''),
('8hjqu3qkqhatrbo58c1189lt7fd11eqa', '172.68.210.170', 1770058179, ''),
('373hdan1cjre2fr3vf8uqll7ehkeqh1r', '172.68.210.170', 1770058180, ''),
('2td2j3d176qh5h9rcvqio4ctcojm5jup', '172.68.210.170', 1770058180, ''),
('qsq934l9chsr9tdk4d4eseetl9p3pelt', '172.68.210.170', 1770058181, ''),
('9jkd8qour7p3nvjkkeg7pglqn8jq0j3a', '172.68.210.170', 1770058181, ''),
('97imvvp33q16k0i3apqhn7a6kv7qku5i', '172.68.210.170', 1770058182, ''),
('5kfipva6q8ujcrddjm7mq5582jdke109', '172.68.210.170', 1770058182, ''),
('m04b8plj70487ahtm08t6touhjfta6ke', '172.68.210.170', 1770058183, ''),
('755k0gldoa5jk0seo173nheravv278g8', '172.68.210.170', 1770058183, ''),
('lpg6vpecig8afjee5g304pnk64dum94t', '172.68.210.170', 1770058184, ''),
('ooju7bhluab2l7oeskhl79gmhg5tpnic', '172.68.210.170', 1770058184, ''),
('deole19bd4tblg0ctk15mvrueahoqtdk', '172.68.210.170', 1770058185, ''),
('qs33vjgonunr8vtrdkk4c6ucvo02iqvp', '172.68.210.170', 1770058185, ''),
('1k9cqa46rvjutvjkqr5aufo8dd0u0tld', '104.22.14.68', 1770058235, ''),
('n69jtsjjcvlc2tjievs2oq5ofka1b6ga', '172.69.134.82', 1770058362, ''),
('v8jmj09tic8c1h3qfslbfmqpvnf0husi', '104.22.18.31', 1770058367, ''),
('eocak8ut8q7smpc7pk1dsftgqdedsvn5', '104.22.20.155', 1770058990, ''),
('0drksi1m4to4n3t7j2vjmaknfe8npcus', '104.22.18.32', 1770058994, ''),
('cgnfqcd1nf44ml08spae2oj5dgcoeptd', '172.71.158.100', 1770059514, ''),
('in1961kgrj83ch9oupsacs916j52b3as', '162.158.41.6', 1770059762, ''),
('hi3rbvleh20d48c9gnvsgl9h0613r5rf', '162.158.41.82', 1770059763, ''),
('ba5s92lq63o4rvcgqilb7h2u25mrko23', '162.158.41.82', 1770059763, ''),
('tmff19he68kpcbbjlq3kv97jpjpo5en2', '162.159.120.171', 1770059801, ''),
('6iavie751kh9j9pb6bin0o3ub16ko2tu', '172.71.111.39', 1770060197, ''),
('cotpi3vlumsoren9ad8g66dtg0nqn65t', '172.68.225.230', 1770060744, ''),
('1jqk2d094vh95e2set36d57ntneg77af', '172.71.238.39', 1770061316, ''),
('p86pki9hak1jr0m5bqkhokdafp2vjvcm', '172.68.245.116', 1770061785, ''),
('6g40rl05oun75hhreld0ou1i80pv9tm7', '172.69.39.154', 1770061982, ''),
('50i3jv6sadqjgm5rg3bbccu5i3b37ovj', '104.23.211.68', 1770062223, ''),
('75r5lobipq4p2u4mfi6mdjvnt6nnm1o5', '104.23.211.103', 1770062225, ''),
('lvbodh8lfk3j5f39khpapjko63mp5ie1', '104.23.211.103', 1770062225, ''),
('6n40uvjh83bgqeqvl660q6eurpqsal2b', '104.23.211.103', 1770062225, ''),
('4qvlhms29mbv4dbenect19s3380ccj4s', '172.68.225.231', 1770062524, ''),
('a1rcsmc4pprdobnfvds9363kvgqcg5el', '172.68.225.231', 1770062525, ''),
('rmnol65c1vs7elik0u7j897gck5bsq3v', '172.68.225.231', 1770062525, ''),
('908o2dosjto9ujjcj2p91eqs4000rumf', '172.68.225.231', 1770062525, ''),
('96609ine0e9evsk9lbeclqpgghb3ia95', '162.158.193.148', 1770062546, ''),
('9h0arpqs2m1p3r010dma4oodefqk48fn', '162.158.193.148', 1770062546, ''),
('pvrhirobc6ktsrv7vrf1a9du2i4uond2', '162.158.193.148', 1770062546, ''),
('or3lqbrclojb3dh700r8nd9h6141pqg9', '162.158.193.148', 1770062546, ''),
('avo9il2kdo5a82pvuujsmicv24t2rhpa', '172.71.254.49', 1770062733, ''),
('i4erh5cmak4nms5jqr6dh58d2tg5nirk', '172.71.190.242', 1770065500, ''),
('2s9pahqq0p1mqdpg3ho6jlhm5kapkik0', '108.162.216.252', 1770065504, ''),
('afne8dko8iu3ktg4grmn43t4210mhr0q', '162.159.98.33', 1770066926, ''),
('0l6ncpmt3una13u85kdkduv2jpt4ea0u', '162.159.98.33', 1770066926, ''),
('764dn08a5i7etdtoa7o8722ai7v1a5at', '162.159.98.33', 1770066927, ''),
('kgekr58jssuntpknn1o0ptv0go720sm9', '162.159.98.33', 1770066927, ''),
('m73l9mohritfss6gnsmch80e25lnh8md', '172.70.126.16', 1770067692, ''),
('hmk3ku6i9sdsu4q92s43m3gjanf73u56', '172.71.144.27', 1770068213, ''),
('cnjr55fmvdqd1t1pphj03fev5q77klne', '172.71.144.27', 1770068213, ''),
('8maoil4p5qjg203e6na2gkleier0r9ap', '172.71.144.27', 1770068214, ''),
('jtnuju33rjdtj1ktcmgja9c6r81cusrk', '172.71.144.27', 1770068214, ''),
('emsq697c5146kjl63hufftp55s4o7c6u', '104.23.225.96', 1770069036, ''),
('lduilbd9eucm3eqpo8s1h4a2bi62qkd8', '104.23.225.34', 1770069037, ''),
('qs4d668go86beq5u0vof4u28rkhngv4g', '104.23.225.34', 1770069038, ''),
('sdah96al878rqf9pqv2lu0jt2v4597dd', '104.23.225.34', 1770069038, ''),
('a5u907u2kkrqtnelv16d5dofflidk67m', '104.23.254.12', 1770069385, ''),
('s3vt1jcipplbtj84q5oh0bmj3l4s3k01', '172.69.11.218', 1770070493, ''),
('r4ltu102hm977m9hikgrdkl8ujha21i5', '104.23.166.10', 1770070923, ''),
('4htu4bp8cosvs75ust65l2a34ap0l3fl', '172.69.169.238', 1770071476, ''),
('4l2c4rfu40jd4ss4ljs62c9gnonopf0a', '172.70.206.81', 1770073655, ''),
('koqvkgu62spcgch5asi7kpgia75gec6t', '172.70.206.81', 1770073656, ''),
('26am84pgcom97bd82aqqb3fnrh18pstc', '172.70.206.81', 1770073656, ''),
('hno7ikcaq01gkda9j7c9mui36in9fpea', '172.70.206.81', 1770073657, ''),
('o1qi69hdhs6eu0q1hnci3af8hjqv3na9', '172.70.208.155', 1770073749, ''),
('r5bggh8mucl8fjovlinebvn266ikvg2t', '172.71.103.21', 1770074748, ''),
('l1eut8mf0ju39be02pf149qtmdb9ip70', '172.64.215.33', 1770075277, ''),
('hvpusdnn72g90lk6urrojgaiditgt1dm', '172.68.3.69', 1770075903, ''),
('dsb2di0eef8up1iuoheq4vkbt967u151', '172.68.3.69', 1770075903, ''),
('osnj1vu7ctf02tc7oucajjusirpd528d', '172.68.3.69', 1770075904, ''),
('jqviphj8gv1h9bgeqtqc64a40b0l35s9', '172.68.3.69', 1770075904, ''),
('korpaftiov2toe13imhrdfo7ple4kdos', '162.158.94.153', 1770077064, ''),
('g6lojlcbub4k8uco4m5p0p3f0okrmk6s', '172.71.214.231', 1770078433, ''),
('cpd56g4f3js4dfqjcub4titqq2ea35ev', '172.71.214.231', 1770078433, ''),
('d5l5mlnn9pgtikdi5qvhj32m3sjc33ps', '172.71.214.231', 1770078434, ''),
('4p7j0fqq7oln4fliu3ua5m8tl9kg0anc', '172.71.214.231', 1770078434, ''),
('aim145aqni5i8fpo1gnka85q97f6i9qi', '104.22.20.154', 1770078618, ''),
('7brp89g28a4hqadpsgs60gc2lec3nrt4', '172.68.22.34', 1770078693, ''),
('i9junei43eoij89kfjus89i3ivfv09em', '172.68.23.25', 1770078695, ''),
('acvnl6qdd1ijv1dvn2d2smdfrgh5o0oq', '104.22.18.32', 1770079080, ''),
('rujjcmk81r39l608nifvlodq71f09jlj', '104.22.20.155', 1770079659, ''),
('7rkehkc30gpira0lbohf3l1j7oe880p0', '104.22.18.31', 1770079663, ''),
('10dqgcj95bbnm0t93peil6s3i3dku2rf', '172.70.39.90', 1770080291, ''),
('6b1senumauc6r278arrt71is9l4cp91b', '162.158.88.80', 1770080660, ''),
('mdrjmvitc6fim087av583dl0snfl0g2c', '162.158.88.81', 1770081026, ''),
('6d69ihb0l7rk0eol8j5tcgm7qo8qd2k7', '172.71.158.100', 1770081444, ''),
('dcljlt9aof9789pu1c6ke918436v7ed6', '172.71.214.230', 1770081794, ''),
('7g161ljg36tc3j388r33bsjomtad6064', '172.71.215.107', 1770081795, ''),
('saqk865gltur1605ol64bqk8kmkflhq8', '172.71.215.107', 1770081795, ''),
('cholmo84jbd1rg3gpj42c6dqca9ojpq6', '172.71.215.107', 1770081795, ''),
('4an1buv4d9ogegltelspu4liufqofscp', '104.22.18.32', 1770082054, ''),
('uklsnir9ob05cmdcflm883viu1a0iufg', '162.158.91.151', 1770082094, ''),
('oan3clcuvv2gle8ucc21f72keeih4fis', '162.158.90.82', 1770082096, ''),
('qodjaie82sa4fv1o1q1icci8a52v3djp', '162.158.90.82', 1770082096, ''),
('ecdktm1bld3qjuoh8rhrhkfqdsgril0e', '162.158.90.82', 1770082097, ''),
('skfccadrvakr25e9m1tmrgo4t546einn', '162.159.108.154', 1770082639, ''),
('j51o7rrotql3efen45vm9c8hvigh7vpg', '172.69.151.100', 1770083115, ''),
('apn1f6u3vmmq0i20opuq0l2kd1s41dkl', '172.68.195.177', 1770083115, ''),
('apghg3bj359cllq0qs1uouf8qjh5bmck', '172.68.195.177', 1770083116, ''),
('cu7psq9iet6ftqchh5t2qph769d9cscg', '172.68.195.177', 1770083116, ''),
('qgimhln8t01uir7vj20sbhelggq2nt7v', '104.22.18.32', 1770083202, ''),
('61obfrgt7h0csklot7nch26bja8erhjt', '172.71.210.48', 1770083620, ''),
('jc313e6gegvbsjaaqvt784rivnkrje29', '172.71.210.48', 1770083620, ''),
('c1rsvdc6o6ugctaju0nnu2h6uj2bvb2i', '172.71.210.48', 1770083620, ''),
('3ma2omd0rcttjrekecp6fr58fu2s0ega', '172.71.210.48', 1770083620, ''),
('5hj4dpn6c0eav1qtmai8oh1fbjcovcm0', '104.23.213.97', 1770083902, ''),
('4h8vqdcalbi8kfutnocrgurdalia8bp1', '104.22.18.31', 1770084504, ''),
('97nd5cicinq9dv50ia1ait4f1d2umuif', '172.70.126.16', 1770084783, ''),
('u58hgfln1i02qbn2jglr2ckavp6ej5a9', '162.159.108.155', 1770085111, ''),
('q84qb6n47tu92o2tircutlmf6fiqldq5', '172.68.10.67', 1770085343, ''),
('akadse187sqj763jj460sck94of26f6o', '172.71.184.68', 1770085346, ''),
('jaid9qjrnno6hv0lop9ci38ti4fjdkvp', '172.69.134.82', 1770085510, ''),
('p6cop461c8segtdufqmfsa9cj89o8nds', '162.158.170.141', 1770086245, ''),
('a00q3q9p846hlji55sqctrdeo4ckfqjh', '104.23.170.106', 1770086412, ''),
('g6ds2ihcet8frlhhc9n16e2f8svbuvg2', '104.23.170.106', 1770086412, ''),
('eckjcpa94hl8qa64evf5rl4pakv5abpv', '104.23.170.106', 1770086412, ''),
('0i7erprcpqpkbtdo7c5bmvv4i55mb4cu', '104.23.170.106', 1770086413, ''),
('8dk503cb1hrp003fvu2gnnu9ngps46m0', '172.68.225.230', 1770086434, ''),
('ibdee70cqm4ijdjq9p3fpa7k2sa7asdv', '162.159.98.33', 1770086434, ''),
('h4ggh56452dumtbfhba3q9qfh5nsruq2', '172.68.225.238', 1770086435, ''),
('564m2ufn7celhfn8r995akae5ctjdrju', '162.159.98.191', 1770086435, ''),
('ot9oe2tnpelsnjh5r7jrg1l9i932m10d', '162.159.98.191', 1770086435, ''),
('8ceih0m2ej0muh5umbalcfb7ifanc4fl', '172.68.225.238', 1770086435, ''),
('dbsv51l5tfbqrfk0d6fogp936h97sc9s', '162.159.98.191', 1770086435, ''),
('82nf43cn03ur4bbgodc6apfp3dntfb85', '172.68.225.238', 1770086435, ''),
('r6bsd6u622aetltum7b7r0fkjq3ubodm', '162.158.94.152', 1770086604, ''),
('02vdc56trk49takd71qdj0g23jdkt8m4', '172.71.158.101', 1770086889, ''),
('f9gdmbmqcka9ceddfapcm6rknruen6dq', '172.69.22.8', 1770087465, ''),
('t669h1pdao4fme16h8p9o2l7miv96e22', '172.71.210.49', 1770087995, ''),
('j2m92hr6cffcedfc9chjv9g7hnkjalor', '172.68.211.33', 1770087997, ''),
('gnoj230bmdl8k4nudr96jfc3q2lg3t9q', '108.162.216.253', 1770088172, ''),
('qjtf4ptqnvm77ip2eon0rqk84ma7vaqe', '108.162.216.253', 1770088173, ''),
('dd4vvevn5jai7ljp0hau0cf0rcogd65q', '108.162.216.253', 1770088173, ''),
('4e57536nhbk414grqv9c6i6ig6h1mtcj', '108.162.216.253', 1770088174, ''),
('k018ib6mcu1mbe1h6bsqddbe7k0p0ogg', '172.68.22.15', 1770088403, ''),
('qpk30haof7lgsoln45drc51enrrurg7l', '172.71.151.208', 1770088928, ''),
('8nossvukv293gkguum0ql29at9upb036', '172.70.92.181', 1770090007, ''),
('pt03adqono98649e28ko2j8tq37v51sg', '172.70.92.181', 1770090007, ''),
('vujcbqobms0usot5suj2j8d45l2b9dnc', '172.70.92.181', 1770090007, ''),
('pmq1nkl7pd1cl96dsul2jr48enadl1id', '172.70.92.181', 1770090007, ''),
('ka2boe0shjr8donjldm50c6b415dk3av', '172.68.225.230', 1770091043, ''),
('2j7e1q9r9m8b62l8k9rm5vro0k4knq6u', '172.68.225.230', 1770091048, ''),
('m6i6tb239240sno6kfk9l457dp5omrqr', '172.68.225.230', 1770091052, ''),
('jqp9d61ettkmvv5arlshqdrfmkvqovdm', '172.68.225.230', 1770091055, ''),
('qp3ej5eqgoo1tu4us2sl7ch5an78osl0', '172.71.103.21', 1770091114, ''),
('giohnqnm8vfcd4m1q9mjr5qqioprv52s', '172.69.178.202', 1770091438, ''),
('sqqglr73llvai868mif2mcs0j87vd1d1', '162.158.107.10', 1770092690, ''),
('808t7o5jmg4c44rfrgiaq2512tod7qt4', '172.71.210.49', 1770093420, ''),
('s0t4ovfvc1n23qir8o6dresi4kgjuhj7', '172.71.210.49', 1770093420, ''),
('msor12nm8tapj35237v568818vib2jlj', '172.71.210.49', 1770093420, ''),
('nrj8kgb2qaps1rubnme5t38nlah6stln', '172.71.210.49', 1770093420, ''),
('422s6h6lrkjr101os08usj4tuap7io6k', '172.71.182.216', 1770094561, ''),
('8pd7gr70ug3lgk248tf2p7ocvhdj60l2', '172.71.182.216', 1770094562, ''),
('0avjanitf7ongu29k2ho544m09esdpcl', '172.71.182.216', 1770094563, ''),
('r85qmb8b1q79ued75seo2dpn9244mfb3', '172.71.182.216', 1770094564, ''),
('fi8catf9vt4647o6e4h5gmbr87lh7448', '172.69.165.43', 1770094976, ''),
('jm72jtk2c8r7db1sc315lqkk6ohtlq4k', '172.69.166.83', 1770096222, ''),
('6jh8hf7ft51s5rmu8f962u1rdl1pf8uo', '172.69.166.83', 1770096222, ''),
('tu9pd2qu94g3bp94k8nfqsphpbfecf8j', '172.69.166.83', 1770096223, ''),
('s52dhs6ef7o9a8mldf3rqp3trbgsa6oe', '172.69.166.83', 1770096223, ''),
('52upbaddqvqstejmuvlp9a31ic7f1lqe', '172.70.206.81', 1770097153, ''),
('lh9r8vp3gjvl4p7qdrr4v7fn839rn166', '172.70.206.81', 1770097154, ''),
('h4q94dbt5ifhpp0tvpu1g2bmq1e99a4t', '172.70.206.81', 1770097154, ''),
('hgksqkpbeav2ms4kee23a8dodt0q10ud', '172.70.206.81', 1770097154, ''),
('3d49eoek8j6hgqk4b9ligm7c171okl35', '162.159.98.33', 1770097711, ''),
('r552o6n1ghjs6n8437r1ql8nukh8rehs', '162.159.98.33', 1770097711, ''),
('59ne504mverb14a45ni1njhgv5ehvcrg', '162.159.98.33', 1770097712, ''),
('u8901kjd47skce0f53b2uvvtedlrqoan', '162.159.98.33', 1770097712, ''),
('460n038jukmg47r0gl6h82cb8ml3dhd7', '172.71.127.87', 1770100013, ''),
('3dfqc06k7dv978d4n3iqlqf6pj79ehh3', '162.159.98.32', 1770100125, ''),
('s4bunik6cjr10vue6iar3di7in8notqm', '162.159.98.32', 1770100126, ''),
('g4ihr4h3i6du2fhumhnkjvq0a6ivgu4m', '162.159.98.32', 1770100126, ''),
('slgbear1kn316vmqvnd6grifvb98el5g', '162.159.98.32', 1770100126, ''),
('nstr76e1cidljvi2ipep2ifeie6rq91e', '172.71.158.101', 1770101399, ''),
('j78qp0fkqfo2dr7l5an9qei0ngqjb367', '104.22.18.31', 1770101400, ''),
('nh2t5i4o0vf4h86nqvfleck2q9q4gd9d', '104.22.18.32', 1770101586, ''),
('3lchtlaa5utgi5qnolf0t2vfb43crevr', '172.69.166.83', 1770102657, ''),
('sq0r5jknhmfobqm85hb2kuc3rv0tgori', '104.23.225.96', 1770103678, ''),
('54qj46do0nng3c6do1kg250uk2ll6mv9', '141.101.97.67', 1770103678, ''),
('c22tm2670lanjme0ko6evh65g21vi4r1', '104.23.225.96', 1770103679, ''),
('9gk22msd0d5kmifsr9m4623hm8aftfin', '141.101.69.31', 1770103680, ''),
('0hr292ikbat6deegvbeo62cn5ue7sdav', '104.23.229.102', 1770103681, ''),
('frvgjk8mvq5bd5l653cp24d6ck8jh4g6', '172.71.118.199', 1770103681, ''),
('qtfgjcl56dhddhi1pagkak3g4cskh47v', '104.23.225.96', 1770103682, ''),
('ku4fid1prpqhsrsj9tut6ccjicbcfk3c', '172.71.122.200', 1770103683, ''),
('cev7gigq2hfl8p8oaa89q9mp24vpok79', '172.71.130.168', 1770103684, ''),
('ka0t0d79525lqon6vqj1nj09bp1qbi1s', '104.23.225.96', 1770103685, ''),
('1vddta5nkmhetd4pu10ne371tg9rdbcs', '172.71.127.87', 1770103686, ''),
('ecq5gq4ndb2tujh7tqv0hj32ijuqm7km', '172.68.151.144', 1770103687, ''),
('vppgod2tu92ctmul3ulm2jfde4rddd87', '172.71.130.168', 1770103687, ''),
('umlt71ghj4spqurebaqft6sh6q1f19bu', '172.68.151.144', 1770103690, ''),
('oimovk8fhkel9if652f5e96aqs2atn6p', '172.71.232.143', 1770103691, ''),
('8v3er6oaijrd6v0gu066uciulvr9uu7r', '172.68.151.144', 1770103692, ''),
('kpbbqs3qmv8ahn6t1bid22vgidhpsigp', '104.23.229.102', 1770103693, ''),
('pnuuv8hg3v3ka9gus8jqgefls5613q02', '172.71.127.87', 1770103693, ''),
('ik870u6iuidhnr3gsgq8o9p3b7f3nc9a', '172.68.151.144', 1770103694, ''),
('btsflqd7dsmjfc1julco15hq05ue9fp4', '172.71.135.82', 1770103695, ''),
('9krq5ibgq1nr9dd5mfn6ceb2elp2t7be', '172.71.232.143', 1770103695, ''),
('21lrrfpia3m77irv559fqjoq20serlem', '172.71.135.82', 1770103696, ''),
('uiskleolc6l18uni25r1spqqseg6sl5n', '172.71.232.143', 1770103697, ''),
('bglpekt1o118dkmissad3bq4rktqqbmc', '172.71.135.82', 1770103698, ''),
('9n4tve0pj0vs0ei1rv7ga2umh4hbtt0m', '141.101.97.67', 1770103698, ''),
('s15fn79hon9jn378mjq5j3eka6o4tl7f', '104.23.225.96', 1770103699, ''),
('3d6sog7d3f7jfvu2tfd3i4t33916df6u', '104.23.229.102', 1770103699, ''),
('sse4qqhq5v33vcqvq8nvmjmeer44l74n', '104.23.229.102', 1770103700, ''),
('q0k7igcmi7gkkff8paf9lq79eap1ja73', '172.71.232.143', 1770103700, ''),
('1km4au229lpqaeqa7i8qbvtall4j27uu', '172.71.118.199', 1770103701, ''),
('5uq23vmf4d4m97blonadbh8v8oppcrpc', '172.71.130.168', 1770103701, ''),
('eierpnn987o22kiaqv7qoagse7ho7kii', '104.23.225.96', 1770103702, ''),
('ophthj3vmhcn259hv0f1gmut2jl74u3i', '141.101.69.31', 1770103703, ''),
('nmlnobqfjalagkprg6183tdh3ot0u0em', '172.71.135.82', 1770103703, ''),
('q928ktd002qv3sr26f3nkck3snqak1fm', '141.101.69.31', 1770103704, ''),
('viva8ogshomn5q5r8vaqepdvgglqk7sa', '172.71.122.200', 1770103704, ''),
('844qq77eeojs96rlj368lejqiosn8536', '141.101.69.31', 1770103705, ''),
('93vi1kvatgs8rahoga25m8g01is0m7iv', '104.23.225.96', 1770103706, ''),
('0is5enmvrv4lgg0v1r3hg0t52htp9ppp', '172.71.118.199', 1770103707, ''),
('84oihbe926cvgvdues0j1en5k7fq8vs1', '172.71.118.199', 1770103707, ''),
('ltcgm2lr4p4lor7cord0t8092tkmht0h', '172.71.130.168', 1770103708, ''),
('bjnutbi8497fmtr1j9senja8kg7ctd0l', '172.71.127.87', 1770103708, ''),
('4tnqd96av707snv7b06v8r1k2ud6irbf', '141.101.97.67', 1770103709, ''),
('eaipq0cld27afb3jmcun54r0a2cs10ka', '172.71.127.87', 1770103710, ''),
('8pu0k7hqvuldus0531jtlf8r4r4q2t9d', '104.23.225.127', 1770103711, ''),
('ft2b4dcvl9kih4pdcom058knmsg96t8p', '172.71.232.143', 1770103712, ''),
('giv1447385cbadj3ko3118npsameu8a8', '141.101.69.31', 1770103712, ''),
('4pq3sulq3jc67dnf3v9meiv69k48g55v', '141.101.69.31', 1770103713, ''),
('doc1ro0crk9kf3mskitt22tko8kc8ecg', '172.68.151.144', 1770103713, ''),
('rldug6rv1g83lrsic5i0niiglcf8mqef', '172.68.151.144', 1770103714, ''),
('1v7eq5ldp1jmu1uehpgs2flolisg28cs', '104.23.225.96', 1770103714, ''),
('orepg8373hjm61vjai62kfo7o1jhl5hu', '141.101.69.31', 1770103715, ''),
('n0aqkihja850oagtaap6fib5an8pki98', '104.23.229.102', 1770103715, ''),
('0l12et58s1jgh0b7dhhpg2vn8gqd4fgj', '172.71.118.199', 1770103716, ''),
('upcat1jcl428krkoaqp4hgi9fikiei5m', '172.71.130.168', 1770103716, ''),
('9mvv9a2j1sk7qiq063f2n0qdbudki7f8', '172.71.232.143', 1770103717, ''),
('fii0t6o0s2vph1ngeuu66gnrbpjq1hp9', '141.101.69.31', 1770103717, ''),
('l0gsv3ldacnvnh0es45h8lpveqjtrkh9', '172.71.130.168', 1770103718, ''),
('mql48uasr571mu7nf71vh7un5i7c4ih8', '172.71.232.143', 1770103718, ''),
('0f54qj0tkpmmgajnva5c3dhkqn1udf5k', '172.71.118.199', 1770103719, ''),
('po0ftsvj5p0vur9i34kralpll2j1uqgr', '141.101.97.67', 1770103719, ''),
('v58jibfqffvai9km3el6mn5t32mbmft7', '172.71.122.200', 1770103720, ''),
('ghdrtod2ipbmb45afp8pum04ja0nrm7a', '172.71.127.87', 1770103720, ''),
('tejepop7j4abhcilh7s539638n7g0hlu', '104.23.225.96', 1770103721, ''),
('un2f494vaapv6fk1ug67k6l5lnbv188r', '172.71.232.143', 1770103721, ''),
('m6jsidl7ocsfg7p7oqbjd72seok3kh18', '172.71.127.87', 1770103722, ''),
('vnmek2kiac4fi6addb483mhs6cmk2b0t', '104.23.229.102', 1770103722, ''),
('0lkugh4t16p4plnusmjmtf5hsjlh2sd2', '172.71.122.200', 1770103723, ''),
('0l0kf6825vpgf6relffupl2noifqoco3', '172.71.118.199', 1770103723, ''),
('e7c38km69kbjtg7o5bahddba9bvj5bt1', '172.71.118.199', 1770103724, ''),
('hgfed4vn7inbtbukggv787co2ttre5ep', '172.71.232.143', 1770103724, ''),
('l2t2g1u4a6d8e9f5ff38n3rp9nc63i5r', '141.101.97.67', 1770103725, ''),
('03m518415j6dqevnsqtmma9g7p3kv89h', '172.71.118.199', 1770103726, ''),
('kb3bgm7u7lpbsid68i9t7lbc945slo94', '172.71.135.82', 1770103727, ''),
('01bl1bts9t6eo0s45ua6t0o4jtq8vmjq', '104.23.229.102', 1770103727, ''),
('ktu9pvsddk6kajeq0v2qc9sei9tha19o', '141.101.69.31', 1770103728, ''),
('4o9s07oo1p4609h663i0if3aju2vt0i9', '172.71.135.82', 1770103728, ''),
('rd9o606halbdfcpne6c0qgtr5sskhqo7', '104.23.229.102', 1770103729, ''),
('v6r7s1fbc5r6fcvh64m3vdo4flk4nl85', '172.71.130.168', 1770103729, ''),
('rc4894b4hd4ba1ddbakme0pb7e1btkv6', '104.23.225.96', 1770103730, ''),
('vnj05qteppp0t9ksh6i4bg3io601hm25', '172.68.151.144', 1770103730, ''),
('ocqun8u2ua5v8pka4k8iiimrms9u6e3r', '172.71.122.200', 1770103731, ''),
('ik66mfbn1vg2qmkif5pcrn0nls22cc6l', '104.23.225.96', 1770103731, ''),
('nbn2urlbs5p76tjr54iig6ccpv1firbd', '104.23.225.96', 1770103732, ''),
('ct3nq7v2c152uqtnp46d2tfcr0nevqgq', '172.71.118.199', 1770103732, ''),
('tbk8b8gkht2aka964dflul7nq750scko', '172.71.118.199', 1770103733, ''),
('814h8atn47kap0vnqj3avrm8ve6in9p5', '141.101.97.67', 1770103733, ''),
('ba4kaavrhqf3jf8lnlkep1b2525f4ruf', '172.68.151.144', 1770103734, ''),
('3da9kv7r2kp4aeq3vkk48odfdl8dctlh', '172.71.232.143', 1770103734, ''),
('kvij7ec5bhijdbjapd75fve8hnj55gdu', '141.101.69.31', 1770103735, ''),
('07hqg72vm9pi5uge7soviu69acot8cd6', '172.71.134.65', 1770103736, ''),
('n1lciicej73s303of78c4baro4p6frup', '104.23.225.91', 1770103737, ''),
('n53jbafolu2l31ajukkgpvhoh65nsf6g', '172.68.151.144', 1770103738, ''),
('ejnj7lph6a5t9g1tfb0drpgfjvb4t90f', '104.23.225.96', 1770103738, ''),
('gdgeeuc8jaskpddl22vllhpd8tvb6jsf', '172.71.232.143', 1770103739, ''),
('hud020cao62d8b28voj83e15j31hjf8g', '104.23.229.102', 1770103739, ''),
('8ibrkogss83qs30nuuikopubll5s8a7r', '104.23.229.102', 1770103740, ''),
('4do3s55d26tne1pllq1o7cmtofur3dbq', '172.68.151.144', 1770103740, ''),
('hkqmfpegl401le7ca50cll6la6q7v8u1', '104.23.229.102', 1770103741, ''),
('cft8m8rqu7d339cdg1bb72oqh081jsp1', '141.101.69.31', 1770103741, ''),
('j05emc60v1ekobet1cavcmo1beb9irrt', '104.23.229.102', 1770103742, ''),
('d0pjj2mlh3mdpprq42si56r9a2nh4jdp', '104.23.229.102', 1770103742, ''),
('o716uetcn2tvonp0ivr0dr72gbus6c9e', '141.101.97.67', 1770103743, ''),
('kgug0hcf6u3na6ljqp7ad9oojr6vc61n', '172.68.151.144', 1770103743, ''),
('8sn7urq2jo2166l55fovmv0o76icq2cs', '104.23.229.102', 1770103744, ''),
('4sjlcifarnei445apcks5msuci2hbhgv', '172.71.118.199', 1770103744, ''),
('p3vqeo399i4g53gi4lsuo2s8nb8mragd', '172.71.232.143', 1770103745, ''),
('rifuaahoo3c1amgkp8r291pn3lpg6p6h', '172.68.151.144', 1770103746, ''),
('sedjaobmtgs4ohejbke3q2rg7ln6524i', '104.23.225.96', 1770103746, ''),
('0s2af338sgnelksgk23vb2r0puhfmorv', '172.71.130.168', 1770103747, ''),
('1v89mb72g366ubbvo986hu5l72nuf0oj', '172.68.151.144', 1770103747, ''),
('vsut9kr34i7are1n44kem9d9v6nv2mdh', '172.71.122.200', 1770103748, ''),
('611s8ta52f3j02n74sjsdhi45tsbc68t', '141.101.69.31', 1770103748, ''),
('mppakd7v2crb7tl33ks5kr3ja8hhiggt', '172.71.130.168', 1770103749, ''),
('t88i9je08vgjdv5s3koiu11nssfnbp1d', '141.101.97.67', 1770103750, ''),
('amou3cma721tmuuj8v9n1bs5qgd6gkdj', '172.71.122.200', 1770103750, ''),
('6q9bm1d8ng6eq3apfvrkddc2u4mkta6c', '104.23.229.102', 1770103751, ''),
('r1ivvaltldb89a4pkid4u6j1k01l53kp', '104.23.225.96', 1770103751, ''),
('7e11hk4uf0e3o9picvufvvbgk96r12av', '172.71.232.143', 1770103752, ''),
('1qiiqm86u4hv0hajtcol27std8nfiahi', '104.23.229.102', 1770103752, ''),
('88liovh00j93b8bgmgdu9gk6qjksgdnc', '172.71.122.200', 1770103753, ''),
('28n387ipvqraqr07cfdm6um6ck4tu5vf', '141.101.97.67', 1770103753, ''),
('ia36qt0i46nknpkrgd3bej5ngtho4ni0', '172.71.127.87', 1770103754, ''),
('uqdvv17nad5n8gudaenp3ckeale69qk1', '172.71.135.82', 1770103754, ''),
('thfvrbingsppbo0g2b8qp1r82kvv3m99', '172.71.232.143', 1770103755, ''),
('11t0pamrs0o9lue4343s5mif42odo66c', '172.71.130.168', 1770103755, ''),
('7621kb2oiena9c62n1eir3r7rmhcvs1d', '141.101.97.67', 1770103756, ''),
('m0it46u7jc1hfuulgg9ms46s7qat2fdt', '141.101.69.31', 1770103756, ''),
('c75meaoe7gp7qt9u32a1f2akcrc53s1j', '172.71.232.143', 1770103757, ''),
('msr8p6teuglvb3dgdivhjs8mcm61fkp5', '172.71.232.143', 1770103757, ''),
('l7chtgboa781iqctgkrsumr5gvrqu26a', '172.68.151.144', 1770103758, ''),
('j0nuld04e5aq0gltos1l0bhujld5lj8c', '172.68.151.144', 1770103759, ''),
('4tus76op2fo6ggi4ibebcrs5c7uq3svm', '104.23.225.96', 1770103760, ''),
('ab9tlrtqrre4lhfvhumfq5rr71k6jaf1', '172.71.130.168', 1770103760, ''),
('kv8gdc5d7vcgqvhncoubsc2o3e4n7ok5', '172.68.151.144', 1770103761, ''),
('a46q4hnvsme3h3e9d7dpe0vdeeap9qcd', '104.23.229.102', 1770103761, ''),
('27l967phtu0bkra6ov9nrd4gviphjjdi', '172.68.151.144', 1770103762, ''),
('1er8378se7qiru9qcjf6ne33ph1rb5m8', '172.71.127.87', 1770103762, ''),
('p1jg4fc56s39k4qi706eietdnmlkb8j2', '141.101.97.67', 1770103763, ''),
('vrp6pfr0eq9e8n0iegvrtmon8he3k29c', '141.101.69.31', 1770103763, ''),
('t8olj6mskd5kmula63vc3fjjp63fgkt3', '141.101.97.67', 1770103764, ''),
('jjm518tofcl5e4bhtj6debv0s8j40bqp', '172.71.118.199', 1770103764, ''),
('r7qdlgbtc6gka0fuv6d8mkilbqi1tpqf', '172.71.122.200', 1770103765, ''),
('5l9tbobja0csau33hjrmu2okgiuj2a5d', '172.71.127.87', 1770103765, ''),
('78khropakh0vsreeooe86c5odol9ms87', '172.71.122.200', 1770103766, ''),
('jq3b1d1a4h5i4lfd6jcdtrjnrhm49l8a', '172.71.118.199', 1770103767, ''),
('41nha3jjrvmqhsc4ehb3tut6o0qrjaaf', '172.71.232.143', 1770103768, ''),
('j3kqa95dmhsm26vvl3dot9fn50rvsko3', '141.101.69.31', 1770103768, ''),
('efg3mrfegtpcrs2jc629v30cfi5bquc9', '172.71.135.82', 1770103769, ''),
('2bmjngbpcf2divq2poek4ap1opucf6gm', '172.71.118.199', 1770103769, ''),
('jev907ka20gv95p116bslb4ptntn0bco', '172.68.151.144', 1770103770, ''),
('qhchcvlhv6aqnskumpkp40m8kb3h6ml5', '141.101.97.67', 1770103770, ''),
('kdkmc89i1t8bn6hcqcr9vjpqceuos4cf', '141.101.69.31', 1770103771, ''),
('tbmfg1lfd8sh525n9vmqqu5qb9vu1noq', '141.101.69.31', 1770103771, ''),
('hg3ftnbtju3ueek4fq9fv88m921gh6s4', '172.71.130.168', 1770103772, ''),
('95mv876e95rdpnvv85027j2hm80bqgpf', '172.71.232.143', 1770103772, ''),
('n0qhivts742pk75de1hu2d6kl01kspqa', '172.71.127.87', 1770103773, ''),
('ctb1vidfa9v7cp29p0iuni7bf21cia5m', '104.23.225.96', 1770103773, ''),
('0e38iu0t242spmf1c6n11sjnqpki75om', '172.68.151.144', 1770103774, ''),
('ngnp00g75gtsd5fate37irjte8pvb578', '104.23.229.102', 1770103774, ''),
('a93itnp5ku2mrbitkqbons13flb9m6ti', '104.23.225.96', 1770103775, ''),
('obuu1ih2gnvmces71l0tlnq38n2tdeev', '172.71.122.200', 1770103775, ''),
('5ffs7u43rsk73sqtjhaf050l2co5f28f', '104.23.225.96', 1770103776, ''),
('50drknnbpisroehs1gncv2vnbu5lrg84', '104.23.225.96', 1770103776, ''),
('f3r4qjdtp4rl5vnngd4q6r6lkpgsupib', '172.71.127.87', 1770103777, ''),
('qtipaqgf3vfcssdq6a0bkf6d9ctups9i', '104.23.229.102', 1770103777, ''),
('nthmnggqdhb3vham4tor242m1rq1nkkn', '172.71.127.87', 1770103778, ''),
('vp0900cmm1mpeq2vqujdcp8iu1bn6lbu', '172.71.122.200', 1770103778, ''),
('a0l5jhk01s3tce4g5ne6hg3m19cefh0r', '172.71.135.82', 1770103779, ''),
('p50tcpkcfcfa9hhd3og1p6pit16fiek7', '172.71.232.143', 1770103779, ''),
('ole9k2i8ekup10vsjmq5lv5n0mj1go77', '172.71.127.87', 1770103780, ''),
('lhfd1nqkstv83sruv09mnj535pdmd43j', '172.68.151.144', 1770103780, ''),
('e32fv9g375rmhbbbu2me9d3ddnphmo52', '104.23.229.102', 1770103781, ''),
('8ofdgpbt54g45thlql392032ph9081gd', '172.71.130.168', 1770103781, ''),
('5ov0t4uu367dkie6fqiu0tvue708l62t', '172.71.232.143', 1770103782, ''),
('1gt4eaoga7jiq17u1ehc75guino9nb6o', '104.23.225.96', 1770103784, ''),
('u81v2i59u4uearfupgireo62jbeqo0e2', '141.101.97.67', 1770103784, ''),
('strp5k8biturl1jo6n3aqe3glqtrm6gr', '172.71.232.143', 1770103785, ''),
('tsat8k09ii97mk4vjdkjjkb74j2mttpd', '172.68.151.144', 1770103785, ''),
('drf6m68osahv8d6uugb9g5tpfcnb7j5t', '104.23.225.96', 1770103786, ''),
('j563k8lvree6se6emgjrv4rh3sa5edjg', '141.101.97.67', 1770103786, ''),
('rai2sthuv109levfn26bucmstqk8s7rn', '141.101.97.67', 1770103787, ''),
('p4acjnls88kadrviu2d61c7q988vtdnp', '172.71.122.200', 1770103787, ''),
('f5doc0bfejp4l3g9t92kf7ell5vjgcut', '172.71.135.82', 1770103788, ''),
('fp9t41ink094g9d2naq4j4ueleumdm0k', '172.71.135.82', 1770103788, ''),
('bpt0ff46c5neec5i15piutgqjq34co1f', '172.71.118.199', 1770103789, ''),
('l34jso60u901fgd1e4ke7om0iub3hd8p', '172.71.118.199', 1770103789, ''),
('tve4b99sh3hguh91p5e9lhofk29juikk', '104.23.229.102', 1770103789, ''),
('0tl3ohh417o6hov3kd24a5qftj5tnqis', '172.71.232.143', 1770103790, ''),
('vd59vtckgrig3q22ceh8bobipmhhpm6j', '104.23.229.102', 1770103790, ''),
('edje6drr4b0ncvr51lnnt07mou2ttu2d', '172.71.130.168', 1770103791, ''),
('8qo0t3kiiuhknvmodao0rsjfs2hfsc90', '141.101.69.31', 1770103791, ''),
('fp2407bgitmqjcd24l0k38r77ug7532c', '172.71.122.200', 1770103792, ''),
('80bokogjkvpvneuvb4dr6m86famtt764', '172.71.122.200', 1770103792, ''),
('iiqi3apfh9pq9qni3miu0gn29hui814v', '172.71.122.200', 1770103793, ''),
('evh7fk6a34afr2e8gs2rf3s2nijffha8', '141.101.97.67', 1770103793, ''),
('eooquo52cnojqbk88hc1rnqrmcdf0jvm', '141.101.97.67', 1770103794, ''),
('2qurpo9454uf4jgf67ja86r4m3831f2q', '172.71.127.87', 1770103794, ''),
('1k9f1dvbc5ggq142f9o1dho9ibs3shjf', '104.23.225.96', 1770103795, ''),
('eumuq7ninlojiot057slav04s66r8n41', '172.71.232.143', 1770103795, ''),
('dldfu5rquc5ml996oedv1e2dl3rph3co', '172.71.127.87', 1770103796, ''),
('v509b8299agrbdcld4nq1b6h3j64osu0', '172.68.151.144', 1770103796, ''),
('nh97i6efo2u86efgia7v1hbeisl8vedf', '141.101.97.67', 1770103797, ''),
('japui404nnlcq3s32qe7ao5q59hf6gse', '172.71.135.82', 1770103797, ''),
('90g66hm92r82b6ivlorg9nlv94m859u4', '172.68.151.144', 1770103798, ''),
('0ev0cccp9ka5m9ekqgos9j6jfb35bcg9', '172.71.127.87', 1770103798, ''),
('82s9oaa8mqp097f7h1hdv093qcdla4a2', '172.71.130.168', 1770103799, ''),
('0cd8oqprvi79k5tf48kjrc5bc6qkeq4t', '172.71.118.199', 1770103799, ''),
('f2gd7ck1sik897lng97oc9f5n2oq0cu7', '172.71.232.143', 1770103800, ''),
('da2f7gilb9dg6n5mjajuvquom3t2ile2', '141.101.69.31', 1770103800, ''),
('c0k0ou2mmj1g7k9nm6nts7fiuqmq2ue9', '172.71.118.199', 1770103802, ''),
('kt9t2oi6vu87oqtiirb1r5dih2t467l4', '172.71.135.82', 1770103803, ''),
('gqft98is85co58cjt0em8k31a7htkl0b', '172.68.151.144', 1770103805, ''),
('kttohh0e83npon3dgtpk0b45gc8q1fpq', '172.71.232.143', 1770103806, ''),
('a2qp9vvr2v9mvgnlqvprndgir0gmbv5l', '172.71.127.87', 1770103807, ''),
('tim47kkstdbt7r9e8hc7tsfn30p6jfll', '172.71.127.87', 1770103808, ''),
('dfhct9sikkk6j1p9i2po1mi5ler183mo', '104.23.170.107', 1770103814, ''),
('b8fbdo0a15q1ssjpjcptlal3eos02vp5', '104.23.170.66', 1770103816, ''),
('olphnjr7j06264ml38qeg5ur572nf15k', '104.23.170.66', 1770103817, ''),
('trc0qfcolbfmjfhegkma3moih73qp37o', '104.23.170.66', 1770103817, ''),
('kuepdhr6odro431hkcligg17lu9iap3o', '104.22.18.32', 1770104187, ''),
('b98hljiu8rnm3j1g5kid66bjlivmar4v', '172.71.210.49', 1770104707, ''),
('j668un8ftj9hv8lhdeljtl42k62d40c3', '172.71.210.49', 1770104708, ''),
('e5mm7mi71kpp6tqaf20111i92trggijv', '162.158.114.169', 1770104708, ''),
('ubevp5cta6hbcg0226tlblumjksia62p', '162.158.114.169', 1770104708, ''),
('jnsa40o1qu50eu7fmcivjogj0s58m2b0', '172.68.225.231', 1770104749, ''),
('p7fn21akmdfb0uhaemr7o25q3873bqp0', '172.68.225.231', 1770104749, ''),
('80bk85lbvkf9cpk18hfmr7faegbre1s7', '172.68.225.238', 1770104750, ''),
('nvf0cvm21pco4orvajj8dpg5bq5b8p8l', '172.68.225.238', 1770104750, ''),
('5vaojkepea1f0ogaerlnehagbllef4aa', '104.22.18.31', 1770104793, ''),
('dacvdpdvfl03ecbant9jhvbl0np4m34e', '172.69.39.155', 1770105339, ''),
('n1nmjos9c37qohht8ljg7a2eg7fq18d2', '162.158.94.152', 1770105891, ''),
('frb0jej8edpurftluoi8ret7rqoolrro', '162.158.94.152', 1770105891, ''),
('d4r0fh4i123ppopin06me87f4glh3lph', '162.158.94.152', 1770105892, ''),
('qc4udmjbuqm4gij2qch379jbpcekpqe0', '162.158.94.152', 1770105892, ''),
('hio0769280ilo60tai57vno1vg0n4h5g', '172.69.39.155', 1770105989, ''),
('fc5anmrmjq7v86q13f55u219rk6jitot', '162.159.98.33', 1770107645, ''),
('8rtl1ro8bttd4dc5giv8t2p8vqvno2h8', '162.159.98.33', 1770107645, ''),
('g823d42nefchkuc62f83o189sr7l370i', '162.159.98.33', 1770107645, ''),
('s0a72c8h8qc0g02oqvnh91v7emp6tepj', '162.159.98.33', 1770107646, ''),
('ed4k4vem9a2jsspa0ancuaft607disba', '104.22.18.32', 1770107770, ''),
('grl3il9vr8lll6sadspgpk3tshuvrc2l', '104.22.18.32', 1770107771, ''),
('k446bgec39q4ks4shqlpa33jb6dv3g6f', '172.71.210.49', 1770108183, ''),
('l8ncvt9kq4gecktjgfcdvrgchcmfiv8q', '162.158.114.169', 1770108184, ''),
('snju0v5ndcasuhlsrncq2hua7irpvoh6', '162.158.114.169', 1770108184, ''),
('jmcq2mi9vpdvaipfeo9ekh83bno485ja', '162.158.114.169', 1770108184, ''),
('i537vvtrkq9jpml76s9mi92t4qa2gh0h', '172.71.158.100', 1770108397, ''),
('veu8g8jpra7u0qhmfo2aj0algeshfkfg', '141.101.84.207', 1770108933, ''),
('925o3igkv0cnqjhbhcj658re0dpv366s', '162.159.110.81', 1770109582, ''),
('a0ciq9488098vi2v105a1c9h841dr0j5', '172.71.214.231', 1770109990, ''),
('1kcgbqtri3khtssteet42vfa09hga8gt', '172.71.214.231', 1770109990, ''),
('0cqis0anq02rq9k9pmjsnscvha04e4l7', '172.71.214.231', 1770109990, ''),
('43h8qrdren7uvs7a4o25q4hgjl0rq9o5', '172.71.214.231', 1770109990, ''),
('c363gmjpajmo3cmko646sab1mi23kd2o', '172.71.214.230', 1770110801, ''),
('t9nj02jn8tvmp8na2ouggra1m97d240m', '172.71.214.230', 1770110801, ''),
('35h1m91a7sukge4tef6e91q68441ippf', '172.71.214.230', 1770110802, ''),
('g6p0ladjet19vti6vr835srvabqt1ia0', '172.71.214.230', 1770110802, ''),
('d4gtgpnnvglbn31fashscc5ufeqrhb82', '172.71.158.100', 1770110806, ''),
('lrsnlqf2nlo398724ccpv5kjhdn7codh', '172.70.248.123', 1770111539, ''),
('8g0ims80oma3od4tfcm3dp35j9pv9fi3', '172.70.248.123', 1770111540, ''),
('vdq7up7tenhj1201n7jd3piv9j5tfj61', '172.70.248.123', 1770111540, ''),
('fhm5dnborf2u2gdi8cmmnnmk5gsgi3j8', '172.70.248.123', 1770111540, ''),
('p7jc4ekgdmail2nbp8nuh3ae1i4cg0gu', '104.23.211.69', 1770112004, ''),
('lqgkghqhjo9jj5oqofa3o4kj86ogqq8s', '162.158.88.81', 1770112554, ''),
('fhb7jqbq4e6l22tg3i73cp6qsp9k6nm0', '162.158.88.59', 1770112554, ''),
('jaea87fk1fpj5v0s2vi1lfj4t63533mv', '162.158.88.59', 1770112554, ''),
('d989ptmvhecobnbii9c440pmab8lrdte', '162.158.88.59', 1770112555, ''),
('pr5i0mogj9jv3ti0tag19inbjgaanca1', '104.22.18.31', 1770112615, ''),
('ei9dckiitb46q627oq1b7ft3jmshdjhf', '162.158.118.152', 1770112744, ''),
('qb9s0pi3860vi2i4lin8ak6sek3d80nq', '162.158.118.152', 1770112785, ''),
('hj1t1v467pb46ict7slkqlqgcc7fdg7b', '172.71.214.231', 1770112846, ''),
('k8ulhtiusk6n8a9lk7htocjr002uvuhp', '172.71.214.231', 1770112847, ''),
('rm2ifoplraig31skckgbsf6voqk4dheu', '172.71.214.231', 1770112847, ''),
('3uoviamkfvbpra03s1qjvsbsd48o4h25', '172.71.214.231', 1770112847, ''),
('4ugu0rdlahnjlr6tsmbdd5ctjtc351uc', '172.68.211.33', 1770113105, ''),
('rpna7ubllq3b0hi8j7ufgtfmf5qjofk1', '172.68.211.214', 1770113106, ''),
('qj549440pe6b6ub4it7jprdfvobaljk5', '172.68.211.214', 1770113106, ''),
('in8q5f2m1md6vmv3jubkr8c2rij2n0ao', '172.68.211.214', 1770113106, ''),
('bdb1qvhu4ppse18teh9bne5k18qlo5ci', '172.69.86.48', 1770113903, ''),
('soq5qeaqqvdp4b56ud9sc29g6cd7hnhn', '172.71.194.142', 1770114180, ''),
('6hcb9josfvaans6h1s59nkq1pku5qsfm', '162.158.178.69', 1770114398, ''),
('o0ntok36rtu63thqmrvlmcdlo15pgebh', '162.158.178.69', 1770114398, ''),
('ae0e8vr7suj4r203gcc9j5h4ueh20njr', '162.158.178.69', 1770114398, ''),
('5224moipbkugu9kqclj7glvc4o6i9keh', '162.158.178.69', 1770114398, ''),
('pttbo1s8560tub0off6ahb0m2j745u3u', '172.68.225.231', 1770114421, ''),
('f6f0fin7ndcofp8o0j83pl6olaa4q0ld', '172.68.225.239', 1770114422, ''),
('ebck8q33a9j07d417d1t89o1cjcivk8m', '172.68.225.239', 1770114422, ''),
('13ibg5ac0b9d2p79v5fa01pb5hh2t24o', '172.68.225.239', 1770114423, ''),
('g0mgp6sa0gb1mp5r0279kvum94d78uc9', '162.159.98.32', 1770115281, ''),
('kgfu79i40i636qq8043e9s4krbvvl6ce', '162.159.98.32', 1770115282, ''),
('84ovmvj2uggm8pa5bmqfoufjc5a2rcq4', '162.159.98.32', 1770115282, ''),
('s07of4bubihvpea543lu0df65252g88c', '162.159.98.32', 1770115282, ''),
('p7qnh41cmhocdc8uqv97ndn15jlm6rhd', '172.69.176.67', 1770116496, ''),
('lmprn4jh8ogjth99b2i7am0rvnofl4dg', '172.69.176.96', 1770116496, ''),
('8sg7lsp0kves3g2k53sie38kb9do83pm', '172.69.176.96', 1770116496, ''),
('mjbijhho0n2d955vhsulp1ieqofe0v5b', '172.69.176.96', 1770116496, ''),
('nhnec0chmr9tfkrl8er7makl8agam00f', '172.71.223.79', 1770116899, ''),
('j07h1sugu5hcenkmvihcu3hf9h2r0gpu', '172.68.225.230', 1770117070, ''),
('laj5q82pnqfrjbcrr67dkal4gmrm30fe', '172.68.225.230', 1770117070, ''),
('vh5efboprg0tlilm6ntc3dm0tpk3npoc', '172.68.225.230', 1770117071, ''),
('s60e2mjd4bjnnb6q9qbbue3kf84a7rue', '172.68.225.230', 1770117071, ''),
('ktk2f69crchaklf84kmuvboua50elo2f', '172.71.198.64', 1770117129, ''),
('pgf3jaovfcn3ktut7unnefek20f2pm3e', '172.71.198.163', 1770117130, ''),
('h8vrqc8ouibjukktu7n0k0th5q30vrnn', '172.71.198.194', 1770117131, ''),
('es7pdr32g8a7aee8g0cu48pfdcqvflv9', '172.71.198.181', 1770117131, ''),
('hjdhrfvkjmqcpojhthmqqec1re82g1n3', '162.158.178.69', 1770117134, ''),
('2ei4kfobuu04rbpha4jqjvaulrgi60rs', '162.158.178.69', 1770117134, ''),
('04q64q1l6q7t8nn132u30bertnsdlvne', '162.158.178.69', 1770117135, ''),
('6ab5dv2au2mmqv0jsh5lte87mlvle800', '162.158.178.69', 1770117135, ''),
('a77n4747qro9nclrb6e62cbkb3vfk5j1', '172.71.198.64', 1770117137, ''),
('83t1gmr3t46o5gl80373h6p2cifhvidv', '172.71.198.194', 1770117137, ''),
('g725584j3t6e2smucdich5aqbkd8s89p', '172.71.198.163', 1770117137, ''),
('hdj7tna9dbhlommkf3dlpv195m6vi18u', '172.71.198.181', 1770117137, ''),
('iao1p7451fnql7gnfk3iirj88fj8fg75', '172.71.198.64', 1770117140, ''),
('pu4b6f16k1831vgmk5ou2d23pobolkf4', '172.71.198.163', 1770117140, ''),
('5u4k86bp2hp4lr16bgn3hgennoscvige', '172.71.198.64', 1770117143, ''),
('3gibmtq78eq0d7vukbkks29eh09dinid', '172.71.198.181', 1770117143, ''),
('u4ueosg7vou5crbe487dtanklk995qi8', '172.71.198.194', 1770117143, ''),
('556n0lq5dtmqd574eohofguauqfmcb02', '172.71.198.163', 1770117143, ''),
('9u9mkjo50im6b3jh1m4pnbep0vn5i2vh', '172.71.198.64', 1770117145, ''),
('00amfvtmg8gd1or37q5vsg9vlf8rf36n', '172.69.94.183', 1770117178, '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Countries';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
  `slug` varchar(255) DEFAULT NULL,
  `has_products` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Bookset with products, 0 = Bookset without products',
  `mandatory_packages` int(11) NOT NULL DEFAULT 0 COMMENT 'Number of mandatory packages required',
  `total_packages` int(11) NOT NULL DEFAULT 0 COMMENT 'Total number of packages in this bookset',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Booksets - Groups of Packages';

--
-- Dumping data for table `erp_booksets`
--

INSERT INTO `erp_booksets` (`id`, `vendor_id`, `school_id`, `board_id`, `grade_id`, `bookset_name`, `slug`, `has_products`, `mandatory_packages`, `total_packages`, `status`, `created_at`, `updated_at`) VALUES
(7, 41, 26, 14, 5, NULL, 'city-international-school-aundh-cbse-2', 1, 0, 2, 'active', '2026-02-03 16:24:25', '2026-02-03 18:30:53'),
(8, 41, 26, 14, 4, NULL, 'city-international-school-aundh-cbse-1', 1, 0, 1, 'active', '2026-02-03 18:29:35', '2026-02-03 18:29:35');

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

--
-- Dumping data for table `erp_bookset_packages`
--

INSERT INTO `erp_bookset_packages` (`id`, `vendor_id`, `bookset_id`, `school_id`, `board_id`, `grade_id`, `category_id`, `category`, `package_name`, `package_price`, `package_offer_price`, `gst`, `hsn`, `package_weight`, `is_it`, `note`, `mandatory_count`, `optional_count`, `mandatory_optional_count`, `status`, `with_product`, `created_at`, `updated_at`) VALUES
(24, 41, 8, 26, 14, 4, NULL, 'notebook', 'Maths ', 0.00, 0.00, 0.00, NULL, 15.00, 'mandatory', NULL, 1, 0, 0, 'active', 1, '2026-02-03 18:29:35', '2026-02-03 18:29:35'),
(25, 41, 7, 26, 14, 5, NULL, 'notebook', 'Hindi set ', 0.00, 0.00, 0.00, NULL, 1.00, 'mandatory', NULL, 1, 0, 0, 'active', 1, '2026-02-03 18:30:53', '2026-02-03 18:30:53'),
(26, 41, 7, 26, 14, 5, NULL, 'textbook', 'English ', 0.00, 0.00, 0.00, NULL, 150.00, 'mandatory+optional', NULL, 0, 0, 1, 'active', 1, '2026-02-03 18:30:53', '2026-02-03 18:30:53');

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

--
-- Dumping data for table `erp_bookset_package_products`
--

INSERT INTO `erp_bookset_package_products` (`id`, `package_id`, `product_type`, `product_id`, `display_name`, `quantity`, `discounted_mrp`, `is_it`, `weight`, `note`, `status`, `created_at`, `updated_at`) VALUES
(18, 24, 'notebook', 3, 'PP1 Book', 1, 450.00, 'mandatory', 0.00, NULL, 'active', '2026-02-03 18:29:35', '2026-02-03 18:29:35'),
(19, 25, 'notebook', 3, 'PP1 Book', 1, 450.00, 'mandatory', 0.00, NULL, 'active', '2026-02-03 18:30:53', '2026-02-03 18:30:53'),
(20, 26, 'textbook', 2, 'Baby Frocks', 1, 999.00, 'mandatory', 0.00, NULL, 'active', '2026-02-03 18:30:53', '2026-02-03 18:30:53');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Client/Vendor information';

--
-- Dumping data for table `erp_clients`
--

INSERT INTO `erp_clients` (`id`, `name`, `domain`, `username`, `password`, `database_name`, `db_username`, `status`, `sidebar_color`, `logo`, `favicon`, `site_title`, `meta_description`, `meta_keywords`, `payment_gateway`, `razorpay_key_id`, `razorpay_key_secret`, `ccavenue_merchant_id`, `ccavenue_access_code`, `ccavenue_working_key`, `zepto_mail_api_key`, `zepto_mail_from_email`, `zepto_mail_from_name`, `firebase_api_key`, `firebase_auth_domain`, `firebase_project_id`, `firebase_storage_bucket`, `firebase_messaging_sender_id`, `firebase_app_id`, `created_at`, `updated_at`) VALUES
(41, '0', 'shivambook.com', 'shivambook', NULL, '', NULL, 'active', '#E31E24', 'uploads/vendors_logos/logos/vendor_41_1769856440.png', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-30 10:57:10', '2026-02-01 17:27:43');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Feature assignments to clients';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Sub-category assignments to clients/vendors';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Client branding and configuration';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Available features';

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

--
-- Dumping data for table `erp_notebooks`
--

INSERT INTO `erp_notebooks` (`id`, `vendor_id`, `brand_id`, `product_name`, `isbn`, `size`, `binding_type`, `no_of_pages`, `min_quantity`, `days_to_exchange`, `pointers`, `product_description`, `packaging_length`, `packaging_width`, `packaging_height`, `packaging_weight`, `gst_percentage`, `hsn`, `product_code`, `sku`, `mrp`, `selling_price`, `meta_title`, `meta_keywords`, `meta_description`, `status`, `is_individual`, `is_set`, `created_at`, `updated_at`) VALUES
(3, 41, 4, 'PP1 Book', NULL, NULL, NULL, NULL, 1, NULL, NULL, '<p>4</p>\r\n', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, 200.00, 150.00, NULL, NULL, NULL, 'active', 0, 1, '2026-01-30 19:14:07', '2026-01-31 19:04:24');

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

--
-- Dumping data for table `erp_notebook_images`
--

INSERT INTO `erp_notebook_images` (`id`, `notebook_id`, `image_path`, `image_order`, `is_main`, `created_at`) VALUES
(18, 3, 'uploads/notebooks/images/2026_01_31/notebook_3_697dc7c6cdc2b_0.png', 0, 0, '2026-01-31 17:13:42'),
(19, 3, 'uploads/notebooks/images/2026_01_31/notebook_3_697de1a64b326_0.png', 1, 1, '2026-01-31 19:04:06'),
(20, 3, 'uploads/notebooks/images/2026_01_31/notebook_3_697de1b80f8cf_0.jpg', 2, 0, '2026-01-31 19:04:24');

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

--
-- Dumping data for table `erp_notebook_type_mapping`
--

INSERT INTO `erp_notebook_type_mapping` (`id`, `notebook_id`, `type_id`, `created_at`) VALUES
(29, 3, 7, '2026-01-31 16:34:24');

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
(23, 41, 'GUNDECHA EDUCATION ACADEMY  KANDIVALI', '', '', NULL, NULL, NULL, 'Thakur Village Road, Valley of Flowers, near Evershine Dream Park, Thakur Village, Kandivali East, Mumbai, Maharashtra 400101', 101, 1568, 17423, '', '', '', '', '', 'active', 0, NULL, 0, 0, '2021-04-24 16:44:33', '2026-02-03 13:33:49'),
(24, 41, 'GUNDECHA EDUCATION ACADEMY OSHIWARA', '', '', NULL, NULL, NULL, 'Off Link Road Next to Mega Mall, Oshiwara, Mhada Colony, Andheri West, Mumbai, Maharashtra 400053', 101, 1568, 17423, '', '', '', '', '', 'active', 0, NULL, 0, 0, '2021-04-24 16:45:06', '2026-02-03 13:37:04'),
(25, 41, 'MKVV International Vidyalaya', '', '', NULL, NULL, NULL, 'Sukarwadi, Borivali East, Mumbai, Maharashtra 400066', 101, 1568, 17423, '', '', '', '', '', 'active', 0, NULL, 0, 0, '2021-02-13 17:39:06', '2026-02-03 13:37:04'),
(26, 41, 'City International School, Aundh', '', '', NULL, NULL, NULL, '2/1, Vidyapeeth Rd, Near Bremen Chowk, Phase 1, Siddarth Nagar Society, Aundh, Pune, Maharashtra 411067', 101, 1568, 17479, '', '', '', '', '', 'active', 0, NULL, 0, 0, '2021-03-30 17:12:52', '2026-02-03 14:33:51'),
(27, 41, 'City International School, Wanowrie', '', '', NULL, NULL, NULL, 'Fatima Nagar, Opp. Mahatma Phule Sanskrutik Bhawan, Wanowrie, Pune, Maharashtra 411040', 101, 1568, 17479, '', '', '', '', '', 'active', 0, NULL, 0, 0, '2021-03-30 17:12:58', '2026-02-03 13:37:04'),
(28, 41, 'City International School, Satara Road', '', '', NULL, NULL, NULL, 'Maharshi Nagar, Pune - Satara Rd, behind Dena Bank, Pune, Maharashtra 411037', 101, 1568, 17479, '', '', '', '', '', 'active', 0, NULL, 0, 0, '2021-03-30 17:13:04', '2026-02-03 13:37:04'),
(29, 41, 'Tree House High School, Kalyan', '', '', NULL, NULL, NULL, 'Godrej Hill, Khadakpada, Kalyan West, Kalyan, Maharashtra 421301', 101, 1568, 17334, '', '', '', '', '', 'active', 0, NULL, 0, 0, '2021-04-06 20:23:27', '2026-02-03 13:37:04'),
(30, 41, 'City International School, Mumbai', '', '', NULL, NULL, NULL, 'New Link Rd, Oshiwara, Andheri West, Mumbai, Maharashtra 400102', 101, 1568, 17423, '', '', '', '', '', 'active', 0, NULL, 0, 0, '2021-04-21 12:56:55', '2026-02-03 13:37:04'),
(31, 41, 'GUNDECHA EDUCATION ACADEMY IGCSE, KANDIVALI', '', '', NULL, NULL, NULL, 'Thakur Village Road, Valley of Flowers, near Evershine Dream Park, Thakur Village, Kandivali East, Mumbai, Maharashtra 400101', 101, 1568, 17423, '', '', '', '', '', 'active', 0, NULL, 0, 0, '2021-04-26 17:38:41', '2026-02-03 13:37:04'),
(32, 41, 'Sharada Gyan Peeth International School, Malad East', '', '', NULL, NULL, NULL, 'Datta Mandir Road, Near Military Camp, Next To Central Ordance Depot, Malad East, Mumbai – 400097', 101, 1568, 17423, '', '', '', '', '', 'active', 0, NULL, 0, 0, '2021-04-26 20:24:40', '2026-02-03 13:37:04');

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
(13, 41, 'CBSC', '', 'active', '2026-01-30 16:33:03', '2026-01-30 16:33:03'),
(14, 41, 'CBSE', NULL, 'active', '2026-02-02 19:28:09', '2026-02-03 13:37:52'),
(15, 41, 'ICSE', NULL, 'active', '2026-02-02 19:28:09', '2026-02-03 13:37:52'),
(16, 41, 'IGCSE', NULL, 'active', '2026-02-02 19:28:10', '2026-02-03 13:37:52');

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
(66, 23, 15, '2026-02-02 19:28:10'),
(67, 24, 15, '2026-02-02 19:28:10'),
(68, 25, 14, '2026-02-02 19:28:10'),
(69, 26, 14, '2026-02-02 19:28:10'),
(70, 27, 14, '2026-02-02 19:28:10'),
(71, 28, 14, '2026-02-02 19:28:10'),
(72, 29, 15, '2026-02-02 19:28:10'),
(73, 30, 15, '2026-02-02 19:28:10'),
(74, 31, 16, '2026-02-02 19:28:10'),
(75, 32, 15, '2026-02-02 19:28:10');

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
(2, 23, 'uploads/school/school_6023d8d23fe089-05048767-80969153.jpg', NULL, 0, 1, '2026-02-02 19:28:10'),
(3, 24, 'uploads/school/school_6023da54f17626-19291541-45700987.jpg', NULL, 0, 1, '2026-02-02 19:28:10'),
(4, 25, 'uploads/school/school_6023df65e94622-53498215-27351829.jpg', NULL, 0, 1, '2026-02-02 19:28:10'),
(5, 26, 'uploads/school/school_60630d858cc508-57900429-90051227.jpg', NULL, 0, 1, '2026-02-02 19:28:10'),
(6, 27, 'uploads/school/school_60630d53ba1415-57963960-60989582.jpg', NULL, 0, 1, '2026-02-02 19:28:10'),
(7, 28, 'uploads/school/school_60630d3e32fd84-62546149-75005409.jpg', NULL, 0, 1, '2026-02-02 19:28:10'),
(8, 29, 'uploads/school/school_606c585c079495-48913538-24989500.jpg', NULL, 0, 1, '2026-02-02 19:28:10'),
(9, 30, 'uploads/school/school_607828c0920405-13414185-47023844.jpg', NULL, 0, 1, '2026-02-02 19:28:10'),
(10, 31, 'uploads/school/school_608663c6b34b37-04422462-74905474.jpg', NULL, 0, 1, '2026-02-02 19:28:10'),
(11, 32, 'uploads/school/school_6086b7622baf98-69393241-10853376.jpg', NULL, 0, 1, '2026-02-02 19:28:10');

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

--
-- Dumping data for table `erp_textbooks`
--

INSERT INTO `erp_textbooks` (`id`, `vendor_id`, `publisher_id`, `board_id`, `grade_age_type`, `product_name`, `isbn`, `min_quantity`, `days_to_exchange`, `pointers`, `product_description`, `packaging_length`, `packaging_width`, `packaging_height`, `packaging_weight`, `gst_percentage`, `gst_type`, `hsn`, `product_code`, `sku`, `mrp`, `selling_price`, `meta_title`, `meta_keywords`, `meta_description`, `status`, `is_individual`, `is_set`, `created_at`, `updated_at`) VALUES
(2, 41, 4, 13, 'grade', 'Baby Frocks', '74123', 1, NULL, NULL, '<p>testtt</p>\r\n', NULL, NULL, NULL, NULL, 0.00, NULL, NULL, NULL, NULL, 79999.00, 799.00, NULL, NULL, NULL, 'active', 0, 1, '2026-01-31 17:35:06', '2026-01-31 18:31:19');

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

--
-- Dumping data for table `erp_textbook_grades`
--

INSERT INTO `erp_textbook_grades` (`id`, `vendor_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(4, 41, '1', NULL, 'active', '2026-01-30 20:40:06', '2026-01-30 20:40:06'),
(5, 41, '2', NULL, 'active', '2026-01-30 20:40:10', '2026-01-30 20:40:10');

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

--
-- Dumping data for table `erp_textbook_grade_mapping`
--

INSERT INTO `erp_textbook_grade_mapping` (`id`, `textbook_id`, `grade_id`, `created_at`) VALUES
(24, 2, 4, '2026-01-31 18:31:19'),
(25, 2, 5, '2026-01-31 18:31:19');

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

--
-- Dumping data for table `erp_textbook_images`
--

INSERT INTO `erp_textbook_images` (`id`, `textbook_id`, `image_path`, `image_order`, `is_main`, `created_at`) VALUES
(2, 2, 'uploads/textbooks/images/2026_01_31/textbook_2_697dcf9fbab41_0.png', 0, 0, '2026-01-31 17:47:11'),
(3, 2, 'uploads/textbooks/images/2026_01_31/textbook_2_697dcf9fbc07c_1.png', 1, 0, '2026-01-31 17:47:11'),
(4, 2, 'uploads/textbooks/images/2026_01_31/textbook_2_697dd9efc5218_0.jpg', 2, 0, '2026-01-31 18:31:11');

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

--
-- Dumping data for table `erp_textbook_publishers`
--

INSERT INTO `erp_textbook_publishers` (`id`, `vendor_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(4, 41, 'Navneet', NULL, 'active', '2026-01-30 19:13:01', '2026-01-30 19:13:01');

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

--
-- Dumping data for table `erp_textbook_subjects`
--

INSERT INTO `erp_textbook_subjects` (`id`, `vendor_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(3, 41, 'maths', NULL, 'active', '2026-01-31 17:34:38', '2026-01-31 17:34:38');

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

--
-- Dumping data for table `erp_textbook_subject_mapping`
--

INSERT INTO `erp_textbook_subject_mapping` (`id`, `textbook_id`, `subject_id`, `created_at`) VALUES
(16, 2, 3, '2026-01-31 18:31:19');

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

--
-- Dumping data for table `erp_textbook_types`
--

INSERT INTO `erp_textbook_types` (`id`, `vendor_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(7, 41, 'Science', NULL, 'active', '2026-01-30 19:12:53', '2026-01-30 19:12:53'),
(8, 41, 'maths', NULL, 'active', '2026-01-31 17:28:43', '2026-01-31 17:28:43');

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

--
-- Dumping data for table `erp_textbook_type_mapping`
--

INSERT INTO `erp_textbook_type_mapping` (`id`, `textbook_id`, `type_id`, `created_at`) VALUES
(16, 2, 7, '2026-01-31 18:31:19');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Super admin users';

--
-- Dumping data for table `erp_users`
--

INSERT INTO `erp_users` (`id`, `username`, `email`, `password`, `role_id`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(20, 'shivambooks', 'shivambooks@shivambooks.com.local', '7c4a8d09ca3762af61e59520943dc26494f8941b', 4, 1, NULL, '2026-01-30 13:27:10', '2026-01-30 13:27:10');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Super admin role definitions';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_stock` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_category`
--

CREATE TABLE `products_category` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `variation_id` int(11) DEFAULT 0,
  `product_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `thumb` varchar(200) DEFAULT NULL,
  `sort` int(11) DEFAULT 0,
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` mediumint(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `country_id` int(11) NOT NULL DEFAULT 101 COMMENT '101 = India'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='States';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `vendor_features`
--

INSERT INTO `vendor_features` (`id`, `feature_id`, `feature_slug`, `feature_name`, `image`, `is_enabled`, `has_variations`, `has_size`, `has_colour`, `synced_at`, `updated_at`) VALUES
(1, 1, 'bookset', 'Bookset', NULL, 1, 0, 0, 0, '2026-02-01 14:57:43', '2026-02-01 14:57:43'),
(2, 3, 'uniforms', 'Uniforms', NULL, 0, 0, 0, 0, '2026-02-01 14:57:43', '2026-02-01 14:57:43'),
(3, 4, 'stationery', 'Stationery', NULL, 0, 0, 0, 0, '2026-02-01 14:57:43', '2026-02-01 14:57:43'),
(4, 5, 'bags', 'Bags', NULL, 0, 0, 0, 0, '2026-02-01 14:57:43', '2026-02-01 14:57:43'),
(5, 6, 'sports', 'Sports', NULL, 0, 0, 0, 0, '2026-02-01 14:57:43', '2026-02-01 14:57:43'),
(6, 11, 'textbook', 'textbook', NULL, 1, 0, 0, 0, '2026-02-01 14:57:43', '2026-02-01 14:57:43'),
(7, 12, 'notebooks', 'notebooks', NULL, 1, 0, 0, 0, '2026-02-01 14:57:43', '2026-02-01 14:57:43'),
(8, 13, 'individual-products', 'individual products', NULL, 0, 1, 1, 1, '2026-02-01 14:57:43', '2026-02-01 14:57:43');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `erp_bookset_packages`
--
ALTER TABLE `erp_bookset_packages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `erp_bookset_package_products`
--
ALTER TABLE `erp_bookset_package_products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `erp_clients`
--
ALTER TABLE `erp_clients`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `erp_client_features`
--
ALTER TABLE `erp_client_features`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=701;

--
-- AUTO_INCREMENT for table `erp_client_feature_subcategories`
--
ALTER TABLE `erp_client_feature_subcategories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `erp_client_settings`
--
ALTER TABLE `erp_client_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `erp_notebooks`
--
ALTER TABLE `erp_notebooks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `erp_notebook_images`
--
ALTER TABLE `erp_notebook_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `erp_notebook_type_mapping`
--
ALTER TABLE `erp_notebook_type_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `erp_school_boards`
--
ALTER TABLE `erp_school_boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `erp_school_boards_mapping`
--
ALTER TABLE `erp_school_boards_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `erp_school_branches`
--
ALTER TABLE `erp_school_branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `erp_school_images`
--
ALTER TABLE `erp_school_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `erp_sizes`
--
ALTER TABLE `erp_sizes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `erp_size_charts`
--
ALTER TABLE `erp_size_charts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `erp_textbook_grade_mapping`
--
ALTER TABLE `erp_textbook_grade_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `erp_textbook_images`
--
ALTER TABLE `erp_textbook_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `erp_textbook_publishers`
--
ALTER TABLE `erp_textbook_publishers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `erp_textbook_subjects`
--
ALTER TABLE `erp_textbook_subjects`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `erp_textbook_subject_mapping`
--
ALTER TABLE `erp_textbook_subject_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `erp_textbook_types`
--
ALTER TABLE `erp_textbook_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `erp_textbook_type_mapping`
--
ALTER TABLE `erp_textbook_type_mapping`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `erp_uniforms`
--
ALTER TABLE `erp_uniforms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `erp_uniform_images`
--
ALTER TABLE `erp_uniform_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `erp_uniform_size_prices`
--
ALTER TABLE `erp_uniform_size_prices`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `erp_uniform_types`
--
ALTER TABLE `erp_uniform_types`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `erp_users`
--
ALTER TABLE `erp_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_user`
--
ALTER TABLE `temp_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_features`
--
ALTER TABLE `vendor_features`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT for table `vendor_feature_subcategories`
--
ALTER TABLE `vendor_feature_subcategories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_site_settings`
--
ALTER TABLE `vendor_site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Constraints for table `erp_uniforms`
--
ALTER TABLE `erp_uniforms`
  ADD CONSTRAINT `fk_uniforms_board` FOREIGN KEY (`board_id`) REFERENCES `erp_school_boards` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_uniforms_branch` FOREIGN KEY (`branch_id`) REFERENCES `erp_school_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_uniforms_material` FOREIGN KEY (`material_id`) REFERENCES `erp_materials` (`id`),
  ADD CONSTRAINT `fk_uniforms_school` FOREIGN KEY (`school_id`) REFERENCES `erp_schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_uniforms_type` FOREIGN KEY (`uniform_type_id`) REFERENCES `erp_uniform_types` (`id`);

--
-- Constraints for table `erp_uniform_images`
--
ALTER TABLE `erp_uniform_images`
  ADD CONSTRAINT `fk_uniform_images_uniform` FOREIGN KEY (`uniform_id`) REFERENCES `erp_uniforms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_uniform_size_prices`
--
ALTER TABLE `erp_uniform_size_prices`
  ADD CONSTRAINT `fk_uniform_size_prices_size` FOREIGN KEY (`size_id`) REFERENCES `erp_sizes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_uniform_size_prices_uniform` FOREIGN KEY (`uniform_id`) REFERENCES `erp_uniforms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `erp_users`
--
ALTER TABLE `erp_users`
  ADD CONSTRAINT `fk_erp_users_role` FOREIGN KEY (`role_id`) REFERENCES `erp_user_roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `fk_orders_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `fk_order_products_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_products_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products_category`
--
ALTER TABLE `products_category`
  ADD CONSTRAINT `fk_products_category_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_products_category_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products_warehouse_qty`
--
ALTER TABLE `products_warehouse_qty`
  ADD CONSTRAINT `fk_products_warehouse_qty_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_products_warehouse_qty_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `school_login`
--
ALTER TABLE `school_login`
  ADD CONSTRAINT `fk_school_login_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `states`
--
ALTER TABLE `states`
  ADD CONSTRAINT `fk_states_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
