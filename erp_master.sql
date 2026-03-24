-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 10, 2026 at 04:46 PM
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
-- Database: `erp_master`
--

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
-- Table structure for table `chatbot_messages`
--

CREATE TABLE `chatbot_messages` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sender` enum('bot','user') NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country`, `code`, `name`, `symbol`, `currency_format`, `symbol_direction`, `space_money_symbol`, `exchange_rate`, `status`) VALUES
(1, 'India', 'INR', 'India Rupee', '₹', 'us', 'left', 0, 1.0000, 1),
(2, 'New Zealand', 'NZD', 'New Zealand Dollar', '$', 'us', 'left', 0, 0.0200, 1),
(3, 'Australia', 'AUD', 'Australia Dollar', '$', 'us', 'left', 0, 0.0180, 1),
(4, 'USA', 'USD', 'United States Dollar', '$', 'us', 'left', 0, 0.0120, 1),
(5, 'UAE ', 'AED', 'UAE Dirham', 'AED', 'us', 'left', 0, 0.0450, 1),
(6, 'UK', 'GBP', 'United Kingdom Pound', '£', 'us', 'left', 0, 0.0096, 1);

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
(3, 3, 20, 10, 1, NULL, NULL, 1, 0, 2, 'active', '2025-12-31 12:27:53', '2025-12-31 12:36:50'),
(4, 3, 19, 10, 3, NULL, NULL, 1, 0, 1, 'active', '2025-12-31 12:40:05', '2025-12-31 14:24:37'),
(5, 3, 19, 10, 1, NULL, NULL, 0, 1, 1, 'active', '2025-12-31 12:54:40', '2025-12-31 12:54:40');

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
(2, 3, NULL, 20, 7, 2, 1, NULL, 'tststst', 0.00, 0.00, 0.00, NULL, 50.00, 'mandatory', 'testtt', 0, 0, 0, 'active', 0, '2025-12-30 14:43:47', '2025-12-30 14:43:47'),
(5, 3, 3, 20, 10, 1, NULL, NULL, 'testt', 0.00, 0.00, 0.00, NULL, 1500.00, 'mandatory+optional', NULL, 0, 0, 1, 'active', 1, '2025-12-31 12:36:50', '2025-12-31 12:36:50'),
(6, 3, 3, 20, 10, 1, NULL, NULL, 'notebookssss', 0.00, 0.00, 0.00, NULL, 150.00, 'mandatory', NULL, 1, 0, 0, 'active', 1, '2025-12-31 12:36:50', '2025-12-31 12:36:50'),
(8, 3, 5, 19, 10, 1, NULL, NULL, 'testt', 100.00, 50.00, 18.00, '1121212', 100.00, 'mandatory+optional', NULL, 0, 0, 1, 'active', 0, '2025-12-31 12:54:40', '2025-12-31 12:54:40'),
(11, 3, 4, 19, 10, 3, NULL, 'notebook', 'live books', 0.00, 0.00, 0.00, NULL, 100.00, 'mandatory', NULL, 1, 0, 0, 'active', 1, '2025-12-31 14:24:37', '2025-12-31 14:24:37'),
(12, 3, NULL, 16, 6, 1, NULL, 'textbook', 'Grade 1 Complete Bookset Package', 2500.00, 2200.00, 12.00, '49011000', 2000.00, 'mandatory', 'Complete package for Grade 1 students', 5, 0, 0, 'active', 1, '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(13, 3, NULL, 17, 7, 2, NULL, 'notebook', 'Grade 2 Notebook Package', 800.00, 700.00, 12.00, '48201000', 1500.00, 'mandatory', 'Complete notebook package for Grade 2', 3, 0, 0, 'active', 1, '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(14, 3, NULL, 18, 6, 3, NULL, 'stationery', 'Grade 3 Stationery Kit', 1200.00, 1000.00, 18.00, '96081000', 800.00, 'mandatory+optional', 'Complete stationery kit for Grade 3', 0, 0, 1, 'active', 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(15, 3, NULL, 19, 10, 1, NULL, 'textbook', 'Grade 1 Premium Package', 3500.00, 3000.00, 12.00, '49011000', 2500.00, 'mandatory+optional', 'Premium package with textbooks and notebooks', 0, 0, 1, 'active', 1, '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(16, 3, NULL, 20, 7, 2, NULL, 'notebook', 'Grade 2 Complete Study Package', 1500.00, 1300.00, 12.00, '48201000', 1800.00, 'mandatory', 'Complete study package for Grade 2', 4, 0, 0, 'active', 1, '2025-12-31 19:20:56', '2025-12-31 19:20:56');

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
(5, 5, 'textbook', 1, 'testss', 1, 0.00, 'mandatory+optional', 0.00, NULL, 'active', '2025-12-31 12:36:50', '2025-12-31 12:36:50'),
(6, 6, 'notebook', 1, 'testt notebook ', 1, 300.00, 'mandatory', 0.00, NULL, 'active', '2025-12-31 12:36:50', '2025-12-31 12:36:50'),
(10, 11, 'notebook', 1, 'testt notebook ', 1, 1500.00, 'mandatory', 0.00, NULL, 'active', '2025-12-31 14:24:37', '2025-12-31 14:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `erp_clients`
--

CREATE TABLE `erp_clients` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Client/Vendor name',
  `address` text DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `pan` varchar(20) DEFAULT NULL,
  `gstin` varchar(20) DEFAULT NULL,
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
  `shipping_providers` varchar(255) DEFAULT NULL,
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

INSERT INTO `erp_clients` (`id`, `name`, `address`, `pincode`, `pan`, `gstin`, `domain`, `username`, `password`, `database_name`, `db_username`, `status`, `sidebar_color`, `logo`, `favicon`, `site_title`, `meta_description`, `meta_keywords`, `payment_gateway`, `shipping_providers`, `razorpay_key_id`, `razorpay_key_secret`, `ccavenue_merchant_id`, `ccavenue_access_code`, `ccavenue_working_key`, `zepto_mail_api_key`, `zepto_mail_from_email`, `zepto_mail_from_name`, `firebase_api_key`, `firebase_auth_domain`, `firebase_project_id`, `firebase_storage_bucket`, `firebase_messaging_sender_id`, `firebase_app_id`, `created_at`, `updated_at`) VALUES
(38, 'Varitty', NULL, NULL, NULL, NULL, 'varitty.in', 'varitty', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'erp_client_varitty', 'vd_admin', 'active', '#33284D', 'uploads/vendors_logos/logos/vendor_38_1770014399.png', 'uploads/vendors_logos/favicons/favicon_38_1770016941.png', 'Varitty | Explore a Wide Range of Products & Choices', 'Discover Varitty – your destination for a wide variety of quality products. Browse multiple options, great deals, and find exactly what you’re looking for.', 'varitty, variety products, wide range of products, multiple options, product variety, best variety store', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-14 13:24:51', '2026-02-02 13:48:29'),
(41, 'Shivam Books & Stationery Shop', NULL, NULL, NULL, NULL, 'shivambook.com', 'shivambook', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'erp_client_shivambookscom', 'vd_shivambooks', 'active', '#393185', 'uploads/vendors_logos/logos/vendor_41_1772780795.png', 'uploads/vendors_logos/favicons/favicon_41_1772779806.png', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-30 10:57:01', '2026-03-06 07:06:35'),
(43, 'kirtibook', NULL, NULL, NULL, NULL, 'kirtibook.in', 'kirtibook', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'erp_client_kirtibookin', 'vd_kirtibook', 'active', '#25404C', 'uploads/vendors_logos/logos/vendor_43_1772780911.png', 'uploads/vendors_logos/favicons/favicon_43_1772781028.png', NULL, NULL, NULL, '', 'bigship,velocity', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-25 06:16:11', '2026-03-06 07:10:28');

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

--
-- Dumping data for table `erp_client_features`
--

INSERT INTO `erp_client_features` (`id`, `client_id`, `feature_id`, `is_enabled`, `created_at`, `updated_at`) VALUES
(13, 3, 1, 1, '2025-12-20 10:10:45', '2025-12-31 12:55:40'),
(15, 3, 3, 1, '2025-12-20 10:10:45', '2025-12-31 12:55:40'),
(16, 3, 4, 1, '2025-12-20 10:10:45', '2025-12-31 12:55:40'),
(17, 3, 5, 0, '2025-12-20 10:10:45', '2025-12-31 12:55:40'),
(18, 3, 6, 0, '2025-12-20 10:10:45', '2025-12-31 12:55:40'),
(306, 3, 11, 1, '2025-12-31 12:55:40', '2025-12-31 12:55:40'),
(307, 3, 12, 1, '2025-12-31 12:55:40', '2025-12-31 12:55:40'),
(315, 9, 1, 1, '2026-01-01 10:32:48', '2026-01-07 06:21:26'),
(316, 9, 3, 1, '2026-01-01 10:32:48', '2026-01-07 06:21:26'),
(317, 9, 4, 0, '2026-01-01 10:32:48', '2026-01-07 06:21:26'),
(318, 9, 5, 0, '2026-01-01 10:32:48', '2026-01-07 06:21:26'),
(319, 9, 6, 0, '2026-01-01 10:32:48', '2026-01-07 06:21:26'),
(320, 9, 11, 1, '2026-01-01 10:32:48', '2026-01-07 06:21:26'),
(321, 9, 12, 1, '2026-01-01 10:32:48', '2026-01-07 06:21:27'),
(357, 10, 1, 1, '2026-01-01 13:10:36', '2026-01-07 06:16:00'),
(358, 10, 3, 1, '2026-01-01 13:10:36', '2026-01-07 06:16:00'),
(359, 10, 4, 1, '2026-01-01 13:10:36', '2026-01-07 06:16:00'),
(360, 10, 5, 0, '2026-01-01 13:10:36', '2026-01-07 06:16:00'),
(361, 10, 6, 0, '2026-01-01 13:10:36', '2026-01-07 06:16:00'),
(362, 10, 11, 1, '2026-01-01 13:10:36', '2026-01-07 06:16:00'),
(363, 10, 12, 1, '2026-01-01 13:10:36', '2026-01-07 06:16:00'),
(392, 14, 1, 1, '2026-01-01 13:52:17', '2026-01-01 14:00:16'),
(393, 14, 3, 1, '2026-01-01 13:52:17', '2026-01-01 14:00:16'),
(394, 14, 4, 1, '2026-01-01 13:52:17', '2026-01-01 14:00:16'),
(395, 14, 5, 0, '2026-01-01 13:52:17', '2026-01-01 14:00:16'),
(396, 14, 6, 0, '2026-01-01 13:52:17', '2026-01-01 14:00:16'),
(397, 14, 11, 1, '2026-01-01 13:52:17', '2026-01-01 14:00:16'),
(398, 14, 12, 1, '2026-01-01 13:52:17', '2026-01-01 14:00:16'),
(455, 31, 1, 0, '2026-01-12 07:15:20', '2026-01-12 10:13:39'),
(456, 31, 3, 1, '2026-01-12 07:15:20', '2026-01-12 10:13:39'),
(457, 31, 4, 1, '2026-01-12 07:15:20', '2026-01-12 10:13:39'),
(458, 31, 5, 0, '2026-01-12 07:15:20', '2026-01-12 10:13:39'),
(459, 31, 6, 0, '2026-01-12 07:15:20', '2026-01-12 10:13:39'),
(460, 31, 11, 0, '2026-01-12 07:15:20', '2026-01-12 10:13:39'),
(461, 31, 12, 0, '2026-01-12 07:15:20', '2026-01-12 10:13:39'),
(476, 31, 13, 1, '2026-01-12 10:13:39', '2026-01-12 10:13:39'),
(477, 38, 1, 0, '2026-01-14 13:27:57', '2026-02-02 13:48:29'),
(478, 38, 3, 1, '2026-01-14 13:27:57', '2026-02-02 13:48:29'),
(479, 38, 4, 0, '2026-01-14 13:27:57', '2026-02-02 13:48:29'),
(480, 38, 5, 0, '2026-01-14 13:27:57', '2026-02-02 13:48:29'),
(481, 38, 6, 0, '2026-01-14 13:27:57', '2026-02-02 13:48:29'),
(482, 38, 11, 0, '2026-01-14 13:27:57', '2026-02-02 13:48:29'),
(483, 38, 12, 0, '2026-01-14 13:27:57', '2026-02-02 13:48:29'),
(484, 38, 13, 0, '2026-01-14 13:27:57', '2026-02-02 13:48:29'),
(669, 41, 1, 1, '2026-01-30 10:58:43', '2026-03-06 07:06:35'),
(670, 41, 3, 0, '2026-01-30 10:58:43', '2026-03-06 07:06:35'),
(671, 41, 4, 0, '2026-01-30 10:58:43', '2026-03-06 07:06:35'),
(672, 41, 5, 0, '2026-01-30 10:58:43', '2026-03-06 07:06:35'),
(673, 41, 6, 0, '2026-01-30 10:58:43', '2026-03-06 07:06:35'),
(674, 41, 11, 1, '2026-01-30 10:58:43', '2026-03-06 07:06:35'),
(675, 41, 12, 1, '2026-01-30 10:58:43', '2026-03-06 07:06:35'),
(676, 41, 13, 0, '2026-01-30 10:58:43', '2026-03-06 07:06:35'),
(1013, 43, 1, 1, '2026-02-25 06:28:44', '2026-03-06 07:10:28'),
(1014, 43, 3, 0, '2026-02-25 06:28:44', '2026-03-06 07:10:28'),
(1015, 43, 4, 0, '2026-02-25 06:28:44', '2026-03-06 07:10:28'),
(1016, 43, 5, 0, '2026-02-25 06:28:44', '2026-03-06 07:10:28'),
(1017, 43, 6, 0, '2026-02-25 06:28:44', '2026-03-06 07:10:28'),
(1018, 43, 11, 1, '2026-02-25 06:28:44', '2026-03-06 07:10:28'),
(1019, 43, 12, 1, '2026-02-25 06:28:44', '2026-03-06 07:10:28'),
(1020, 43, 13, 0, '2026-02-25 06:28:44', '2026-03-06 07:10:28');

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

--
-- Dumping data for table `erp_client_settings`
--

INSERT INTO `erp_client_settings` (`id`, `client_id`, `logo`, `primary_color`, `secondary_color`, `theme`, `sms_provider`, `sms_credentials`, `email_smtp_config`, `whatsapp_config`, `firebase_config`, `created_at`, `updated_at`) VALUES
(3, 3, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2025-12-20 06:40:50', '2025-12-20 06:40:50'),
(9, 9, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2026-01-01 10:23:56', '2026-01-01 10:23:56'),
(10, 10, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2026-01-01 13:06:00', '2026-01-01 13:06:00'),
(14, 14, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2026-01-01 13:41:58', '2026-01-01 13:41:58'),
(16, 16, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2026-01-07 12:54:37', '2026-01-07 12:54:37'),
(31, 31, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2026-01-09 08:52:18', '2026-01-09 08:52:18'),
(33, 33, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2026-01-14 10:04:12', '2026-01-14 10:04:12'),
(38, 38, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2026-01-14 13:24:51', '2026-01-14 13:24:51'),
(41, 41, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2026-01-30 10:57:02', '2026-01-30 10:57:02'),
(42, 42, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2026-02-23 04:39:41', '2026-02-23 04:39:41'),
(43, 43, NULL, '#007bff', '#6c757d', 'default', NULL, NULL, NULL, NULL, NULL, '2026-02-25 06:16:11', '2026-02-25 06:16:11');

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

--
-- Dumping data for table `erp_features`
--

INSERT INTO `erp_features` (`id`, `parent_id`, `name`, `slug`, `description`, `is_school`, `is_active`, `has_variations`, `has_size`, `has_colour`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Bookset', 'bookset', 'Books module for managing individual books', 0, 1, 0, 0, 0, '2025-12-19 09:34:12', '2025-12-29 13:41:49'),
(3, NULL, 'Uniforms', 'uniforms', 'Uniforms module for managing uniform products', 0, 1, 0, 0, 0, '2025-12-19 09:34:12', '2025-12-19 09:34:12'),
(4, NULL, 'Stationery', 'stationery', 'Stationery module for managing stationery items', 0, 1, 0, 0, 0, '2025-12-19 09:34:12', '2025-12-19 09:34:12'),
(5, NULL, 'Bags', 'bags', 'Bags module for managing bag products', 0, 1, 0, 0, 0, '2025-12-19 09:34:12', '2025-12-19 09:34:12'),
(6, NULL, 'Sports', 'sports', 'Sports items module', 0, 1, 0, 0, 0, '2025-12-19 09:34:12', '2025-12-19 09:34:12'),
(7, 3, 'pants', 'pants', 'Uniforms', 0, 1, 0, 0, 0, '2025-12-22 07:20:28', '2025-12-22 07:20:28'),
(11, NULL, 'Textbook', 'textbook', '', 0, 1, 0, 0, 0, '2025-12-31 12:55:21', '2026-02-02 10:35:34'),
(12, NULL, 'Notebooks', 'notebooks', '', 0, 1, 0, 0, 0, '2025-12-31 12:55:32', '2026-02-02 10:35:26'),
(13, NULL, 'Individual products', 'individual-products', '', 0, 1, 1, 1, 1, '2026-01-12 10:13:29', '2026-02-02 10:35:46');

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

--
-- Triggers `erp_individual_product_images`
--
DELIMITER $$
CREATE TRIGGER `after_individual_product_image_insert` AFTER INSERT ON `erp_individual_product_images` FOR EACH ROW BEGIN
    DECLARE product_id_val INT;
    DECLARE vendor_id_val INT;
    
    SELECT p.id, p.vendor_id INTO product_id_val, vendor_id_val 
    FROM erp_products p
    WHERE p.legacy_table = 'erp_individual_products' 
    AND p.legacy_id = NEW.product_id 
    LIMIT 1;
    
    IF product_id_val IS NOT NULL AND vendor_id_val IS NOT NULL THEN
        INSERT INTO erp_product_images 
        (product_id, image_path, image_order, is_main, legacy_table, legacy_id, vendor_id)
        VALUES 
        (product_id_val, NEW.image_path, NEW.image_order, NEW.is_main, 'erp_individual_product_images', NEW.id, vendor_id_val);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_individual_product_image_update` AFTER UPDATE ON `erp_individual_product_images` FOR EACH ROW BEGIN
    UPDATE erp_product_images 
    SET image_order = NEW.image_order,
        is_main = NEW.is_main,
        image_path = NEW.image_path
    WHERE legacy_table = 'erp_individual_product_images' 
    AND legacy_id = NEW.id;
END
$$
DELIMITER ;

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
-- Table structure for table `erp_master_courier`
--

CREATE TABLE `erp_master_courier` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `courier_name` varchar(150) NOT NULL,
  `tracking_link` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1=active,0=inactive',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
(1, 'Cotton', '', 'active', '2025-12-23 13:31:55', '2025-12-23 13:31:55'),
(2, 'Elastic', '', 'active', '2025-12-23 13:46:20', '2025-12-23 13:46:20');

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
(1, 3, 2, 'testt notebook ', '74123', 'A4', 'center_binding', 100, 1, 7, '<p>test </p>\r\n', '<p>testt</p>\r\n', 22.00, 3.00, 3.00, 3.00, 5.00, '7412311', '74123', '74123', 7999.00, 4999.00, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'active', 0, 1, '2025-12-23 11:17:20', '2025-12-29 14:18:54'),
(2, 9, 3, 'Drawing Notebook Big', 'A4002', 'King Size', 'center_binding', 40, 1, 7, '<p>Drawing Notebook Big</p>\r\n', '<p>Drawing Notebook Big</p>\r\n', 1.00, 1.00, 1.00, 1.00, 18.00, '74123', 'A002', 'A002', 450.00, 4500.00, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'active', 1, 1, '2026-01-01 11:39:39', '2026-01-01 11:39:39');

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
(11, 1, 'vendors/3/notebooks/images/notebook_1_1766497816_0.jpg', 0, 0, '2025-12-23 14:50:16'),
(12, 1, 'vendors/3/notebooks/images/notebook_1_1766497816_1.jpg', 1, 1, '2025-12-23 14:50:16'),
(14, 2, 'vendors/9/notebooks/images/notebook_2_1767263979_0.jpg', 0, 0, '2026-01-01 11:39:39'),
(15, 2, 'vendors/9/notebooks/images/notebook_2_1767263979_1.jpg', 1, 0, '2026-01-01 11:39:39'),
(16, 2, 'vendors/9/notebooks/images/notebook_2_1767263979_2.jpg', 2, 0, '2026-01-01 11:39:39'),
(17, 2, 'vendors/9/notebooks/images/notebook_2_1767263979_3.jpg', 3, 0, '2026-01-01 11:39:39');

--
-- Triggers `erp_notebook_images`
--
DELIMITER $$
CREATE TRIGGER `after_notebook_image_insert` AFTER INSERT ON `erp_notebook_images` FOR EACH ROW BEGIN
    DECLARE product_id_val INT;
    DECLARE vendor_id_val INT;
    
    SELECT p.id, p.vendor_id INTO product_id_val, vendor_id_val 
    FROM erp_products p
    WHERE p.legacy_table = 'erp_notebooks' 
    AND p.legacy_id = NEW.notebook_id 
    LIMIT 1;
    
    IF product_id_val IS NOT NULL AND vendor_id_val IS NOT NULL THEN
        INSERT INTO erp_product_images 
        (product_id, image_path, image_order, is_main, legacy_table, legacy_id, vendor_id)
        VALUES 
        (product_id_val, NEW.image_path, NEW.image_order, NEW.is_main, 'erp_notebook_images', NEW.id, vendor_id_val);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_notebook_image_update` AFTER UPDATE ON `erp_notebook_images` FOR EACH ROW BEGIN
    UPDATE erp_product_images 
    SET image_order = NEW.image_order,
        is_main = NEW.is_main,
        image_path = NEW.image_path
    WHERE legacy_table = 'erp_notebook_images' 
    AND legacy_id = NEW.id;
END
$$
DELIMITER ;

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
(16, 1, 1, '2025-12-29 18:48:54'),
(17, 2, 6, '2026-01-01 16:09:39');

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

--
-- Dumping data for table `erp_orders`
--

INSERT INTO `erp_orders` (`id`, `vendor_id`, `school_id`, `customer_name`, `customer_email`, `customer_address`, `order_number`, `order_date`, `delivery_date`, `payment_status`, `order_status`, `payment_method`, `payment_date`, `subtotal`, `tax_amount`, `discount_amount`, `total_amount`, `delivery_address`, `delivery_city`, `delivery_state`, `delivery_pincode`, `delivery_phone`, `notes`, `cancelled_at`, `cancellation_reason`, `delivered_at`, `created_at`, `updated_at`) VALUES
(1, 3, 16, NULL, NULL, NULL, 'ORD-2025-001', '2025-12-15 10:30:00', '2025-12-20 14:00:00', 'success', 'delivered', 'Online Payment', '2025-12-15 10:35:00', 2500.00, 300.00, 200.00, 2600.00, '123 Education Street, Andheri West', 'Mumbai', 'Maharashtra', '400053', '9876543210', 'Please deliver during school hours', NULL, NULL, '2025-12-20 14:00:00', '2025-12-15 10:30:00', '2025-12-20 14:00:00'),
(2, 3, 17, NULL, NULL, NULL, 'ORD-2025-002', '2025-12-28 14:20:00', '2026-01-05 16:00:00', 'pending', 'processing', NULL, NULL, 1800.00, 216.00, 100.00, 1916.00, '456 School Road, Bandra West', 'Mumbai', 'Maharashtra', '400050', '9876543211', 'Urgent delivery required', NULL, NULL, NULL, '2025-12-28 14:20:00', '2025-12-28 14:20:00'),
(3, 3, 18, NULL, NULL, NULL, 'ORD-2025-003', '2025-12-25 11:15:00', NULL, 'failed', 'cancelled', 'Card Payment', NULL, 3200.00, 384.00, 200.00, 3384.00, '789 Knowledge Park, Powai', 'Mumbai', 'Maharashtra', '400076', '9876543212', 'Payment gateway error', '2025-12-25 15:30:00', 'Payment failed due to insufficient funds', NULL, '2025-12-25 11:15:00', '2025-12-25 15:30:00'),
(4, 3, 19, NULL, NULL, NULL, 'ORD-2025-004', '2025-12-30 09:45:00', '2026-01-10 12:00:00', 'success', 'processing', 'Cash on Delivery', '2025-12-30 09:45:00', 4500.00, 540.00, 300.00, 4740.00, '321 Learning Avenue, Vile Parle', 'Mumbai', 'Maharashtra', '400056', '9876543213', 'Bulk order for school', NULL, NULL, NULL, '2025-12-30 09:45:00', '2025-12-30 09:45:00'),
(5, 3, 20, NULL, NULL, NULL, 'ORD-2025-005', '2025-12-31 16:00:00', '2026-01-15 10:00:00', 'pending', 'pending', NULL, NULL, 2200.00, 264.00, 150.00, 2314.00, '654 Education Lane, Goregaon', 'Mumbai', 'Maharashtra', '400063', '9876543214', 'New order - awaiting confirmation', NULL, NULL, NULL, '2025-12-31 16:00:00', '2025-12-31 16:00:00');

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

--
-- Dumping data for table `erp_order_items`
--

INSERT INTO `erp_order_items` (`id`, `order_id`, `product_type`, `product_id`, `bookset_id`, `package_id`, `product_name`, `display_name`, `sku`, `quantity`, `unit_price`, `discounted_price`, `tax_percentage`, `tax_amount`, `discount_amount`, `subtotal`, `total`, `weight`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'package', NULL, NULL, 12, 'Grade 1 Complete Bookset Package', 'Grade 1 Complete Bookset', 'PKG-001', 1, 2500.00, 2200.00, 12.00, 264.00, 300.00, 2200.00, 2464.00, 2000.00, NULL, '2025-12-15 10:30:00', '2025-12-15 10:30:00'),
(2, 1, 'stationery', 1, NULL, NULL, 'Premium Ballpoint Pen Set - Blue', 'Pen Set Blue', 'STN-PEN-001', 2, 200.00, 200.00, 18.00, 72.00, 0.00, 400.00, 472.00, 100.00, NULL, '2025-12-15 10:30:00', '2025-12-15 10:30:00'),
(3, 2, '', 2, NULL, NULL, 'Boys School Shirt - Navy Blue', 'School Shirt Navy', 'UNF-SHIRT-001', 5, 599.00, 550.00, 5.00, 137.50, 245.00, 2750.00, 2887.50, 1000.00, 'Size: Medium', '2025-12-28 14:20:00', '2025-12-28 14:20:00'),
(4, 2, 'stationery', 2, NULL, NULL, 'A4 Size Notebook - 200 Pages', 'A4 Notebook', 'STN-NB-001', 10, 120.00, 110.00, 12.00, 132.00, 100.00, 1100.00, 1232.00, 3000.00, NULL, '2025-12-28 14:20:00', '2025-12-28 14:20:00'),
(5, 3, 'package', NULL, NULL, 13, 'Grade 2 Notebook Package', 'Grade 2 Notebooks', 'PKG-002', 2, 800.00, 700.00, 12.00, 168.00, 200.00, 1400.00, 1568.00, 3000.00, NULL, '2025-12-25 11:15:00', '2025-12-25 11:15:00'),
(6, 3, '', 3, NULL, NULL, 'Girls School Skirt - White', 'School Skirt White', 'UNF-SKIRT-001', 8, 699.00, 650.00, 5.00, 260.00, 392.00, 5200.00, 5460.00, 2000.00, 'Size: Small', '2025-12-25 11:15:00', '2025-12-25 11:15:00'),
(7, 4, 'package', NULL, NULL, 15, 'Grade 1 Premium Package', 'Premium Package Grade 1', 'PKG-003', 1, 3500.00, 3000.00, 12.00, 360.00, 500.00, 3000.00, 3360.00, 2500.00, NULL, '2025-12-30 09:45:00', '2025-12-30 09:45:00'),
(8, 4, 'stationery', 3, NULL, NULL, 'Geometry Box Set - Complete', 'Geometry Box', 'STN-GB-001', 5, 280.00, 250.00, 18.00, 225.00, 150.00, 1250.00, 1475.00, 750.00, NULL, '2025-12-30 09:45:00', '2025-12-30 09:45:00'),
(9, 4, '', 4, NULL, NULL, 'School Trousers - Grey', 'School Trousers', 'UNF-TROUSERS-001', 3, 799.00, 750.00, 5.00, 112.50, 147.00, 2250.00, 2362.50, 900.00, 'Size: Large', '2025-12-30 09:45:00', '2025-12-30 09:45:00'),
(10, 5, 'package', NULL, NULL, 16, 'Grade 2 Complete Study Package', 'Study Package Grade 2', 'PKG-004', 1, 1500.00, 1300.00, 12.00, 156.00, 200.00, 1300.00, 1456.00, 1800.00, NULL, '2025-12-31 16:00:00', '2025-12-31 16:00:00'),
(11, 5, 'stationery', 4, NULL, NULL, 'HB Pencil Set - Pack of 12', 'HB Pencils 12', 'STN-PCL-001', 8, 95.00, 90.00, 12.00, 86.40, 40.00, 720.00, 806.40, 800.00, NULL, '2025-12-31 16:00:00', '2025-12-31 16:00:00'),
(12, 5, '', 5, NULL, NULL, 'School Tie - Navy Blue', 'School Tie', 'UNF-TIE-001', 2, 299.00, 280.00, 5.00, 28.00, 38.00, 560.00, 588.00, 100.00, NULL, '2025-12-31 16:00:00', '2025-12-31 16:00:00');

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

--
-- Dumping data for table `erp_order_status_history`
--

INSERT INTO `erp_order_status_history` (`id`, `order_id`, `status_type`, `old_status`, `new_status`, `changed_by`, `notes`, `created_at`) VALUES
(1, 1, 'payment_status', NULL, 'pending', 3, 'Order created', '2025-12-15 10:30:00'),
(2, 1, 'payment_status', 'pending', 'success', 3, 'Payment received via online payment', '2025-12-15 10:35:00'),
(3, 1, 'order_status', 'pending', 'processing', 3, 'Order confirmed and processing started', '2025-12-15 11:00:00'),
(4, 1, 'order_status', 'processing', 'delivered', 3, 'Order delivered successfully', '2025-12-20 14:00:00'),
(5, 2, 'order_status', 'pending', 'processing', 3, 'Order confirmed, awaiting payment', '2025-12-28 14:25:00'),
(6, 3, 'payment_status', NULL, 'pending', 3, 'Order created', '2025-12-25 11:15:00'),
(7, 3, 'payment_status', 'pending', 'failed', 3, 'Payment failed - insufficient funds', '2025-12-25 12:00:00'),
(8, 3, 'order_status', 'pending', 'cancelled', 3, 'Order cancelled due to payment failure', '2025-12-25 15:30:00'),
(9, 4, 'payment_status', NULL, 'success', 3, 'Payment received via cash on delivery', '2025-12-30 09:45:00'),
(10, 4, 'order_status', 'pending', 'processing', 3, 'Order confirmed and processing', '2025-12-30 10:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `erp_products`
--

CREATE TABLE `erp_products` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `type` enum('textbook','notebook','stationery','uniform','individual','other') NOT NULL DEFAULT 'other',
  `slug` varchar(500) DEFAULT NULL,
  `product_name` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `brand_id` int(11) DEFAULT NULL,
  `board_id` varchar(200) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `discount` int(2) NOT NULL DEFAULT 0,
  `discount_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `selling_price` double(16,2) DEFAULT 0.00,
  `product_mrp` double(16,2) DEFAULT 0.00,
  `gst` double(16,2) DEFAULT NULL,
  `isbn` varchar(100) DEFAULT NULL,
  `hsn` int(11) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `length` varchar(250) DEFAULT NULL,
  `width` varchar(250) DEFAULT NULL,
  `height` varchar(250) DEFAULT NULL,
  `weight` varchar(250) DEFAULT NULL,
  `meta_title` varchar(250) DEFAULT NULL,
  `meta_keyword` varchar(500) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `product_origin` int(11) DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `size_chart_id` int(11) DEFAULT NULL,
  `no_of_pages` int(11) DEFAULT NULL,
  `binding_type` varchar(100) DEFAULT NULL,
  `material_id` int(11) DEFAULT NULL,
  `min_quantity` int(11) NOT NULL DEFAULT 0,
  `is_set` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Set Product',
  `is_individual` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Is Individual Product',
  `legacy_table` varchar(50) DEFAULT NULL,
  `legacy_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `product_code` varchar(100) DEFAULT NULL COMMENT 'Product Code (For control of school set)',
  `pointers` text DEFAULT NULL COMMENT 'Pointers / Highlights (CKEditor)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `erp_product_images`
--

CREATE TABLE `erp_product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL COMMENT 'Reference to erp_products.id',
  `image_path` varchar(500) NOT NULL,
  `image_order` int(11) NOT NULL DEFAULT 0,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `legacy_table` varchar(50) DEFAULT NULL COMMENT 'Original source table (erp_textbook_images, erp_notebook_images, etc)',
  `legacy_id` int(11) DEFAULT NULL COMMENT 'Original ID from source table',
  `vendor_id` int(11) NOT NULL COMMENT 'For filtering by vendor',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Unified product images reference table';

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
  `is_payment_required` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Payment Required Status (1=Required, 0=Not Required)',
  `deliver_at_school` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Deliver at School (1=Yes/Address Required, 0=No/Address Not Required)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Schools managed by vendors';

--
-- Dumping data for table `erp_schools`
--

INSERT INTO `erp_schools` (`id`, `vendor_id`, `school_name`, `slug`, `school_board`, `total_strength`, `school_description`, `affiliation_no`, `address`, `country_id`, `state_id`, `city_id`, `pincode`, `admin_name`, `admin_phone`, `admin_email`, `admin_password`, `status`, `is_branch`, `parent_school_id`, `is_block_payment`, `is_national_block`, `is_payment_required`, `deliver_at_school`, `created_at`, `updated_at`) VALUES
(16, 3, 'Delhi Public Schools', '', '10,7,6', 2500, 'A premier educational institution offering quality education with modern facilities and experienced faculty.', 'CBSE/123456/2020', '123 Education Street, Andheri West', 101, 1568, 17423, '400053', 'Rajesh Kumar', '9876543210', 'admin@dpsmumbai.edu.in', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, 1, 1, '2025-12-20 14:26:52', '2025-12-20 17:04:46'),
(17, 3, 'St. Mary\'s Convent School', '', '7,6', 1800, 'A well-established convent school providing holistic education with emphasis on moral values and academic excellence.', 'ICSE/789012/2018', '456 School Road, Bandra West', 101, 1568, 17423, '400050', 'Sister Mary Joseph', '9876543211', 'admin@stmarysmumbai.edu.in', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, 1, 1, '2025-12-20 14:26:52', '2025-12-20 15:10:00'),
(18, 3, 'Bangalore International School', '', 'CBSE', 3200, 'An international standard school with state-of-the-art infrastructure and global curriculum.', 'CBSE/345678/2019', '789 Knowledge Park, Powai', 101, 1568, 17423, '400076', 'Dr. Priya Sharma', '9876543212', 'admin@bismumbai.edu.in', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, 1, 1, '2025-12-20 14:26:52', '2025-12-20 14:26:52'),
(19, 3, 'Chennai Public School', '', 'State Board', 2200, 'A leading educational institution in Mumbai offering quality education with focus on holistic development and modern learning.', 'MH/901234/2021', '321 Learning Avenue, Vile Parle', 101, 1568, 17423, '400056', 'Ramesh Iyer', '9876543213', 'admin@cpsmumbai.edu.in', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, 1, 1, '2025-12-20 14:26:52', '2025-12-20 14:26:52'),
(20, 3, 'Ahmedabad High School', '', 'CBSE', 2800, 'A progressive school committed to nurturing young minds with innovative teaching methods and comprehensive development programs.', 'CBSE/567890/2022', '654 Education Lane, Goregaon', 101, 1568, 17423, '400063', 'Meera Patel', '9876543214', 'admin@ahmedabadhighmumbai.edu.in', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'active', 0, NULL, 0, 0, 1, 1, '2025-12-20 14:26:52', '2025-12-20 14:26:52');

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
(6, 3, 'ICSE', '', 'active', '2025-12-20 13:08:16', '2025-12-20 13:08:16'),
(7, 3, 'IB', '', 'active', '2025-12-20 13:08:24', '2025-12-20 13:08:24'),
(8, 3, 'IGCSE', '', 'active', '2025-12-20 13:18:08', '2025-12-20 13:18:08'),
(9, 3, 'STATE BOARD', '', 'active', '2025-12-20 13:20:19', '2025-12-20 13:20:19'),
(10, 3, 'CBSE', '', 'active', '2025-12-20 13:20:45', '2025-12-20 13:20:45'),
(11, 3, 'PRE SCHOOL', '', 'active', '2025-12-20 13:21:00', '2025-12-20 13:21:00');

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
(6, 18, 10, '2025-12-20 14:26:52'),
(9, 19, 10, '2025-12-20 14:26:52'),
(16, 17, 7, '2025-12-20 15:10:00'),
(17, 17, 6, '2025-12-20 15:10:00'),
(54, 16, 10, '2025-12-20 17:04:46'),
(55, 16, 7, '2025-12-20 17:04:46'),
(56, 16, 6, '2025-12-20 17:04:46');

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
  `is_payment_required` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Payment Required Status (1=Required, 0=Not Required)',
  `deliver_at_school` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Deliver at School (1=Yes/Address Required, 0=No/Address Not Required)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='School branches managed by vendors';

--
-- Dumping data for table `erp_school_branches`
--

INSERT INTO `erp_school_branches` (`id`, `school_id`, `vendor_id`, `branch_name`, `slug`, `address`, `country_id`, `state_id`, `city_id`, `pincode`, `status`, `is_payment_required`, `deliver_at_school`, `created_at`, `updated_at`) VALUES
(1, 16, 3, 'Gujarat Branch', NULL, '123 Education Street, Andheri West', 101, 1558, 15508, '400075', 'active', 1, 1, '2026-01-01 13:52:57', '2026-01-01 14:10:02');

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

-- --------------------------------------------------------

--
-- Table structure for table `erp_shipping_providers`
--

CREATE TABLE `erp_shipping_providers` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `provider` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `company_id` varchar(255) DEFAULT NULL COMMENT 'bigship_access_key velocity_accno',
  `channel_id` varchar(100) DEFAULT NULL COMMENT 'velocity_secret_code',
  `pickup_city` varchar(100) DEFAULT NULL,
  `pickup_state` varchar(100) DEFAULT NULL,
  `pickup_address` text DEFAULT NULL,
  `pickup_landmark` varchar(500) DEFAULT NULL,
  `pickup_pincode` varchar(10) DEFAULT NULL,
  `pickup_phoneno` varchar(20) DEFAULT NULL,
  `pickup_alt_phoneno` varchar(20) DEFAULT NULL,
  `pickup_name` varchar(150) DEFAULT NULL,
  `pickup_emailid` varchar(150) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_shipping_providers`
--

INSERT INTO `erp_shipping_providers` (`id`, `client_id`, `provider`, `name`, `email`, `password`, `company_id`, `channel_id`, `pickup_city`, `pickup_state`, `pickup_address`, `pickup_landmark`, `pickup_pincode`, `pickup_phoneno`, `pickup_alt_phoneno`, `pickup_name`, `pickup_emailid`, `token`, `token_expiry`, `status`, `created_at`, `last_updated`) VALUES
(1, 43, 'velocity', 'Kirti Book Agency', 'kirtistore', 'kirtistore@123', '30100377', 'Kirti2025111395', 'Pune', 'Maharashtra', 'waydande farmhouse, Maling Rd, near Tanvi Residency, Sarkar Wada, Ward No. 8, Aundh Gaon, Aundh, Pune, Maharashtra', ' near Shankar Mandir', '411067', '9881190907', '9881190907', 'Sandeep', 'info@webwork.co.in', NULL, NULL, 1, '2026-02-25 18:19:49', '2026-03-06 15:10:28'),
(2, 43, 'bigship', 'API USER', 'kirtibookagency@gmail.com', 'Kirtibooks@9090', '49ba505b05fff76a76cf6430e85de1e94102423f283c37d35751aed196fbbe43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJuYW1laWQiOiI3NDQzNzk0IiwidW5pcXVlX25hbWUiOiJUcnVlIiwidG9rZW5faWQiOiJmNGZhZDAzNTU3YzFmNDIwMWI3ZGFhZTBhZTE2YTFjZDY4ZTY0MWYwYTViZTMxMTEwOGQ0NzY0YzRmMzZkNzY0IiwidXNlcl9uYW1lIjoiU2FuZGVlcCBSYXRob2QiLCJjb21wYW55X25hbWUiOiJLSVJUSSBCT09LIEFHRU5DWSIsImNvbXBhbnlfZW1haWxfaWQiOiJraXJ0aWJvb2thZ2VuY3lAZ21haWwuY29tIiwibmJmIjoxNzcyNzA1ODg5LCJleHAiOjE3NzI3Njg4ODksImlhdCI6MTc3MjcwNTg4OX0.Ast1jARlcc7CEYLBkBGy6w_TaYY1uqbR2F6FotGW-E8eBhS464rjlhPUSmxL-EgGJ6n2kIr4GxFaZBP83WRXIg', '2026-03-05 23:48:09', 1, '2026-03-05 15:48:09', '2026-03-06 15:10:28'),
(3, 41, 'velocity', 'Kirti Book Agency', 'kirtistore', 'kirtistore@123', '30100377', 'Kirti2025111395', 'Mumbai', 'Maharashtra', 'Hotel Sonam In,First Floor 101,Andheri-West', 'Near Subway', '400058', '7878787878', '9777777777', 'Test', 'test@gmail.com', NULL, NULL, 0, '2026-02-25 19:41:57', '2026-03-06 15:06:35'),
(4, 41, 'bigship', 'API USER', 'kirtibookagency@gmail.com', 'Kirtibooks@9090', '49ba505b05fff76a76cf6430e85de1e94102423f283c37d35751aed196fbbe43', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJuYW1laWQiOiI3NDQzNzk0IiwidW5pcXVlX25hbWUiOiJUcnVlIiwidG9rZW5faWQiOiJmNGZhZDAzNTU3YzFmNDIwMWI3ZGFhZTBhZTE2YTFjZDY4ZTY0MWYwYTViZTMxMTEwOGQ0NzY0YzRmMzZkNzY0IiwidXNlcl9uYW1lIjoiU2FuZGVlcCBSYXRob2QiLCJjb21wYW55X25hbWUiOiJLSVJUSSBCT09LIEFHRU5DWSIsImNvbXBhbnlfZW1haWxfaWQiOiJraXJ0aWJvb2thZ2VuY3lAZ21haWwuY29tIiwibmJmIjoxNzcyNzA1NDUxLCJleHAiOjE3NzI3Njg0NTEsImlhdCI6MTc3MjcwNTQ1MX0.Lzgzc4__3UrMooToxKZ5hEDIDSGsXck-Xn6upqxa8eb_qFdnN7gCN6WT_VAykY00MliMs0wj2l-xqVoO9omemA', '2026-03-05 23:40:51', 0, '2026-03-05 15:40:51', '2026-03-06 15:06:35');

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

--
-- Dumping data for table `erp_stationery`
--

INSERT INTO `erp_stationery` (`id`, `vendor_id`, `category_id`, `brand_id`, `colour_id`, `product_name`, `isbn`, `sku`, `product_code`, `min_quantity`, `days_to_exchange`, `pointers`, `product_description`, `packaging_length`, `packaging_width`, `packaging_height`, `packaging_weight`, `gst_percentage`, `gst_type`, `hsn`, `mrp`, `selling_price`, `meta_title`, `meta_keywords`, `meta_description`, `status`, `is_individual`, `is_set`, `created_at`, `updated_at`) VALUES
(1, 3, 1, NULL, NULL, 'Premium Ballpoint Pen Set - Blue', 'PEN001', 'STN-PEN-001', 'PEN-SET-BLUE', 1, 7, '<p>Premium quality ballpoint pens</p>', '<p>Set of 10 premium blue ballpoint pens with smooth writing experience. Perfect for students and professionals.</p>', 15.00, 2.00, 2.00, 50.00, 18.00, 'cgst_sgst', '96081000', 250.00, 200.00, 'Premium Ballpoint Pen Set', 'pen, ballpoint, stationery', 'Premium ballpoint pen set for students', 'active', 1, 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(2, 3, 4, NULL, NULL, 'A4 Size Notebook - 200 Pages', 'NB001', 'STN-NB-001', 'NB-A4-200', 1, 7, '<p>High quality ruled notebook</p>', '<p>Premium A4 size notebook with 200 ruled pages. Perfect binding with durable cover.</p>', 30.00, 21.00, 2.50, 300.00, 12.00, 'cgst_sgst', '48201000', 150.00, 120.00, 'A4 Notebook 200 Pages', 'notebook, ruled, A4', 'Premium A4 notebook with 200 pages', 'active', 1, 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(3, 3, 2, NULL, NULL, 'Geometry Box Set - Complete', 'GB001', 'STN-GB-001', 'GB-COMPLETE', 1, 7, '<p>Complete geometry box with all instruments</p>', '<p>Complete geometry box containing compass, protractor, ruler, divider, and eraser. Made with high quality materials.</p>', 20.00, 10.00, 3.00, 150.00, 18.00, 'cgst_sgst', '90172000', 350.00, 280.00, 'Geometry Box Complete Set', 'geometry box, compass, protractor', 'Complete geometry box set for students', 'active', 1, 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(4, 3, 1, NULL, NULL, 'HB Pencil Set - Pack of 12', 'PCL001', 'STN-PCL-001', 'PCL-HB-12', 1, 7, '<p>Premium HB pencils</p>', '<p>Set of 12 premium HB pencils with eraser tips. Perfect for writing and drawing.</p>', 18.00, 2.00, 2.00, 100.00, 12.00, 'cgst_sgst', '96091000', 120.00, 95.00, 'HB Pencil Set 12 Pack', 'pencil, HB, stationery', 'Premium HB pencil set of 12', 'active', 1, 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(5, 3, 3, NULL, NULL, 'Eraser Set - Pack of 10', 'ERS001', 'STN-ERS-001', 'ERS-SET-10', 1, 7, '<p>High quality erasers</p>', '<p>Set of 10 premium quality erasers. Non-dust and smudge-free.</p>', 12.00, 5.00, 2.00, 50.00, 18.00, 'cgst_sgst', '40169200', 80.00, 65.00, 'Eraser Set 10 Pack', 'eraser, stationery', 'Premium eraser set of 10', 'active', 1, 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56');

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

--
-- Dumping data for table `erp_stationery_categories`
--

INSERT INTO `erp_stationery_categories` (`id`, `vendor_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'Writing Instruments', 'Pens, pencils, and writing tools', 'active', '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(2, 3, 'Geometry Tools', 'Compass, protractor, and measuring tools', 'active', '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(3, 3, 'Erasers & Correction', 'Erasers and correction supplies', 'active', '2025-12-31 19:20:56', '2025-12-31 19:20:56'),
(4, 3, 'Notebooks', 'Notebooks and writing pads', 'active', '2025-12-31 19:20:56', '2025-12-31 19:20:56');

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

--
-- Triggers `erp_stationery_images`
--
DELIMITER $$
CREATE TRIGGER `after_stationery_image_insert` AFTER INSERT ON `erp_stationery_images` FOR EACH ROW BEGIN
    DECLARE product_id_val INT;
    DECLARE vendor_id_val INT;
    
    SELECT p.id, p.vendor_id INTO product_id_val, vendor_id_val 
    FROM erp_products p
    WHERE p.legacy_table = 'erp_stationery' 
    AND p.legacy_id = NEW.stationery_id 
    LIMIT 1;
    
    IF product_id_val IS NOT NULL AND vendor_id_val IS NOT NULL THEN
        INSERT INTO erp_product_images 
        (product_id, image_path, image_order, is_main, legacy_table, legacy_id, vendor_id)
        VALUES 
        (product_id_val, NEW.image_path, NEW.image_order, NEW.is_main, 'erp_stationery_images', NEW.id, vendor_id_val);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_stationery_image_update` AFTER UPDATE ON `erp_stationery_images` FOR EACH ROW BEGIN
    UPDATE erp_product_images 
    SET image_order = NEW.image_order,
        is_main = NEW.is_main,
        image_path = NEW.image_path
    WHERE legacy_table = 'erp_stationery_images' 
    AND legacy_id = NEW.id;
END
$$
DELIMITER ;

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
(1, 3, 2, 7, 'grade', 'testss', '74123', 1, 7, '<p>test</p>\r\n', '<p>test</p>\r\n', 11.00, 1.00, 1.00, 1.00, 5.00, NULL, '74123', '74123', '74123', 799.00, 199.00, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'active', 0, 1, '2025-12-23 08:17:07', '2025-12-29 14:19:05');

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
(1, 3, '1', NULL, 'active', '2025-12-23 08:05:39', '2025-12-23 08:05:39'),
(2, 3, '2', NULL, 'active', '2025-12-23 08:05:43', '2025-12-23 08:05:43'),
(3, 3, '3', NULL, 'active', '2025-12-23 08:05:47', '2025-12-23 08:05:47');

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
(7, 1, 2, '2025-12-29 14:19:05');

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
(1, 1, 'uploads/textbooks/3/textbook_1_1766475737_0.jpg', 0, 0, '2025-12-23 08:42:17');

--
-- Triggers `erp_textbook_images`
--
DELIMITER $$
CREATE TRIGGER `after_textbook_image_insert` AFTER INSERT ON `erp_textbook_images` FOR EACH ROW BEGIN
    DECLARE product_id_val INT;
    DECLARE vendor_id_val INT;
    
    -- Get the product_id from erp_products for this textbook
    SELECT p.id, p.vendor_id INTO product_id_val, vendor_id_val 
    FROM erp_products p
    WHERE p.legacy_table = 'erp_textbooks' 
    AND p.legacy_id = NEW.textbook_id 
    LIMIT 1;
    
    IF product_id_val IS NOT NULL AND vendor_id_val IS NOT NULL THEN
        INSERT INTO erp_product_images 
        (product_id, image_path, image_order, is_main, legacy_table, legacy_id, vendor_id)
        VALUES 
        (product_id_val, NEW.image_path, NEW.image_order, NEW.is_main, 'erp_textbook_images', NEW.id, vendor_id_val);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_textbook_image_update` AFTER UPDATE ON `erp_textbook_images` FOR EACH ROW BEGIN
    UPDATE erp_product_images 
    SET image_order = NEW.image_order,
        is_main = NEW.is_main,
        image_path = NEW.image_path
    WHERE legacy_table = 'erp_textbook_images' 
    AND legacy_id = NEW.id;
END
$$
DELIMITER ;

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
(1, 3, 'Arsh Books', NULL, 'active', '2025-12-23 08:03:47', '2025-12-23 08:03:47'),
(2, 3, 'Aagam Books ', NULL, 'active', '2025-12-23 08:04:03', '2025-12-23 08:04:03'),
(3, 9, 'Abhinav', NULL, 'active', '2026-01-01 11:35:43', '2026-01-01 11:35:43');

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
(1, 3, 'Accountancy', NULL, 'active', '2025-12-23 08:06:23', '2025-12-23 08:06:23'),
(2, 3, 'Activity Book', NULL, 'active', '2025-12-23 08:06:44', '2025-12-23 08:06:44');

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
(7, 1, 1, '2025-12-29 14:19:05');

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
(1, 3, 'Textbook', NULL, 'active', '2025-12-23 08:02:54', '2025-12-23 08:02:54'),
(2, 3, 'Notebooks', NULL, 'active', '2025-12-23 08:03:00', '2025-12-23 08:03:00'),
(3, 3, 'testtt', NULL, 'active', '2025-12-29 13:53:35', '2025-12-29 13:53:35'),
(4, 3, 'testtssss', NULL, 'active', '2025-12-31 13:56:00', '2025-12-31 13:56:00'),
(5, 9, '2 LInes Notebooks ', NULL, 'active', '2026-01-01 11:34:28', '2026-01-01 11:34:28'),
(6, 9, 'Drawing book', NULL, 'active', '2026-01-01 11:36:29', '2026-01-01 11:36:29');

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
(7, 1, 1, '2025-12-29 14:19:05');

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
(1, 3, 1, 16, NULL, 6, 'male', 'Black', 'Testtt', NULL, '74123', 1, 7, 2, 'India', '<p>testt</p>', '<p>testtt</p>\r\n', '<p>testtt</p>\r\n', '<p>testtt</p>\r\n', NULL, 2, NULL, 111.00, 11.00, 22.00, 222.00, NULL, 5.00, NULL, '74123', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'active', 0, 0, '2025-12-23 13:48:11', '2025-12-23 13:48:11', NULL, NULL),
(2, 3, 1, 17, NULL, 7, 'male', 'Navy Blue', 'Boys School Shirt - Navy Blue', NULL, 'UNF-SHIRT-001', 1, 7, 2, 'India', '<p>Premium quality school shirt for boys in navy blue color</p>', '<p>Manufactured by ABC Textiles</p>', '<p>Packed by XYZ Garments</p>', '<p>For school students</p>', 599.00, 2, NULL, 30.00, 25.00, 5.00, 200.00, NULL, 5.00, NULL, '61091000', 'Boys School Shirt Navy Blue', 'uniform, shirt, school', 'Premium boys school shirt', 'active', 1, 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56', NULL, NULL),
(3, 3, 2, 18, NULL, 6, 'female', 'Grey', 'Girls School Pants - Grey', NULL, 'UNF-PANTS-001', 1, 7, 2, 'India', '<p>Premium quality school pants for girls in grey color</p>', '<p>Manufactured by ABC Textiles</p>', '<p>Packed by XYZ Garments</p>', '<p>For school students</p>', 699.00, 2, NULL, 35.00, 30.00, 3.00, 250.00, NULL, 5.00, NULL, '62034200', 'Girls School Pants Grey', 'uniform, pants, school', 'Premium girls school pants', 'active', 1, 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56', NULL, NULL),
(4, 3, 2, 19, NULL, 10, 'unisex', 'Grey', 'School Trousers - Grey', NULL, 'UNF-TROUSERS-001', 1, 7, 2, 'India', '<p>Premium quality school trousers in grey color</p>', '<p>Manufactured by ABC Textiles</p>', '<p>Packed by XYZ Garments</p>', '<p>For school students</p>', 799.00, 2, NULL, 40.00, 35.00, 5.00, 300.00, NULL, 5.00, NULL, '62034200', 'School Trousers Grey', 'uniform, trousers, school', 'Premium school trousers', 'active', 1, 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56', NULL, NULL),
(5, 3, 3, 20, NULL, 7, 'unisex', 'Navy Blue', 'School Tie - Navy Blue', NULL, 'UNF-TIE-001', 1, 7, 2, 'India', '<p>Premium quality school tie in navy blue</p>', '<p>Manufactured by ABC Textiles</p>', '<p>Packed by XYZ Garments</p>', '<p>For school students</p>', 299.00, 2, NULL, 25.00, 5.00, 2.00, 50.00, NULL, 5.00, NULL, '62114300', 'School Tie Navy Blue', 'uniform, tie, school', 'Premium school tie', 'active', 1, 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56', NULL, NULL),
(6, 3, 1, 16, NULL, 6, 'female', 'White', 'Girls School Shirt - White', NULL, 'UNF-SHIRT-002', 1, 7, 2, 'India', '<p>Premium quality school shirt for girls in white color</p>', '<p>Manufactured by ABC Textiles</p>', '<p>Packed by XYZ Garments</p>', '<p>For school students</p>', 599.00, 2, NULL, 30.00, 25.00, 5.00, 200.00, NULL, 5.00, NULL, '61091000', 'Girls School Shirt White', 'uniform, shirt, school', 'Premium girls school shirt', 'active', 1, 0, '2025-12-31 19:20:56', '2025-12-31 19:20:56', NULL, NULL);

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
(1, 1, 'uploads/uniforms/3/uniform_1_1766477891_0.jpg', 0, 0, '2025-12-23 13:48:11'),
(2, 1, 'uploads/uniforms/3/uniform_1_1766477891_1.jpg', 1, 0, '2025-12-23 13:48:12'),
(3, 1, 'uploads/uniforms/3/uniform_1_1766477892_2.jpg', 2, 0, '2025-12-23 13:48:12');

--
-- Triggers `erp_uniform_images`
--
DELIMITER $$
CREATE TRIGGER `after_uniform_image_insert` AFTER INSERT ON `erp_uniform_images` FOR EACH ROW BEGIN
    DECLARE product_id_val INT;
    DECLARE vendor_id_val INT;
    
    SELECT p.id, p.vendor_id INTO product_id_val, vendor_id_val 
    FROM erp_products p
    WHERE p.legacy_table = 'erp_uniforms' 
    AND p.legacy_id = NEW.uniform_id 
    LIMIT 1;
    
    IF product_id_val IS NOT NULL AND vendor_id_val IS NOT NULL THEN
        INSERT INTO erp_product_images 
        (product_id, image_path, image_order, is_main, legacy_table, legacy_id, vendor_id)
        VALUES 
        (product_id_val, NEW.image_path, NEW.image_order, NEW.is_main, 'erp_uniform_images', NEW.id, vendor_id_val);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_uniform_image_update` AFTER UPDATE ON `erp_uniform_images` FOR EACH ROW BEGIN
    UPDATE erp_product_images 
    SET image_order = NEW.image_order,
        is_main = NEW.is_main,
        image_path = NEW.image_path
    WHERE legacy_table = 'erp_uniform_images' 
    AND legacy_id = NEW.id;
END
$$
DELIMITER ;

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

--
-- Dumping data for table `erp_uniform_types`
--

INSERT INTO `erp_uniform_types` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'shirt ', '', 'active', '2025-12-23 13:19:45', '2025-12-23 13:19:45'),
(2, 'Pants ', '', 'active', '2025-12-23 13:37:23', '2025-12-23 13:37:23'),
(3, 'Tie', '', 'active', '2025-12-23 13:45:28', '2025-12-23 13:45:28'),
(4, 'Socks', '', 'active', '2025-12-23 13:45:41', '2025-12-23 13:45:41'),
(5, 'badge', 'School Badge', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(6, 'bag', 'School Bag', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(7, 'belt', 'School Belt', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(8, 'blazer', 'School Blazer', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(9, 'cap', 'School Cap', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(10, 'frock', 'School Frock', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(11, 'full pant', 'Full Pant', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(12, 'half pant', 'Half Pant', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(13, 'hoodie', 'Hoodie', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(14, 'jacket', 'Jacket', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(15, 'leggings', 'Leggings', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(16, 'mask', 'Face Mask', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(17, 'pina frock', 'Pina Frock', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(18, 'shirt', 'Shirt', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(19, 'shorts', 'Shorts', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(20, 'skirt', 'Skirt', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(21, 'socks', 'Socks', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(22, 'sweater', 'Sweater', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(23, 'tshirt', 'T-Shirt', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(24, 'tie', 'Tie', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(25, 'trackpants', 'Track Pants', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(26, 'trousers', 'Trousers', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27'),
(27, 'white shirts', 'White Shirts', 'active', '2026-01-07 14:10:27', '2026-01-07 14:10:27');

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
(3, 'admin', 'admin@erp.local', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 1, '2026-03-10 11:14:19', '2025-12-19 10:00:26', '2026-03-10 08:44:19'),
(19, 'varitty', 'varitty@varitty.in.local', '7c4a8d09ca3762af61e59520943dc26494f8941b', 4, 1, '2026-03-06 09:49:21', '2026-01-14 13:46:59', '2026-03-06 07:19:21');

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
-- Table structure for table `otp_requests`
--

CREATE TABLE `otp_requests` (
  `id` int(11) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `used_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `site_logo`, `primary_color`, `secondary_color`, `footer_text`, `show_search`, `show_cart`, `show_wishlist`, `enable_checkout`, `homepage_title`, `homepage_description`, `contact_email`, `contact_phone`, `contact_address`, `social_facebook`, `social_twitter`, `social_instagram`, `meta_keywords`, `meta_description`, `created_at`, `updated_at`) VALUES
(1, 'Book Store', NULL, '#007bff', '#6c757d', '© 2024 All rights reserved.', 1, 1, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-02 07:30:09', '2026-01-02 07:30:09');

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
-- Table structure for table `tbl_order_bookset_products`
--

CREATE TABLE `tbl_order_bookset_products` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL COMMENT 'Order ID from tbl_order_details',
  `order_item_id` int(11) NOT NULL COMMENT 'Order item ID from tbl_order_items',
  `package_id` int(11) NOT NULL COMMENT 'Package ID from erp_bookset_packages',
  `package_name` varchar(255) NOT NULL COMMENT 'Package name at time of order',
  `package_price` decimal(10,2) DEFAULT 0.00 COMMENT 'Package price at time of order',
  `product_type` varchar(50) NOT NULL COMMENT 'Type: textbook, notebook, stationery',
  `product_id` int(11) NOT NULL COMMENT 'Product ID (textbook_id, notebook_id, or stationery_id)',
  `product_name` varchar(500) NOT NULL COMMENT 'Product name at time of order',
  `product_sku` varchar(255) DEFAULT NULL COMMENT 'Product SKU',
  `product_isbn` varchar(255) DEFAULT NULL COMMENT 'Product ISBN',
  `quantity` int(11) DEFAULT 1 COMMENT 'Quantity of this product',
  `unit_price` decimal(10,2) DEFAULT 0.00 COMMENT 'Unit price at time of order',
  `total_price` decimal(10,2) DEFAULT 0.00 COMMENT 'Total price (unit_price * quantity)',
  `weight` decimal(10,2) DEFAULT 0.00 COMMENT 'Product weight',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Individual products from bookset packages in orders';

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
  `payment_status` varchar(155) DEFAULT NULL,
  `payment_method` varchar(155) DEFAULT 'cod',
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
  `type_order` varchar(50) DEFAULT NULL COMMENT 'Type of order: individual, bookset, uniform',
  `total_weight_gm` decimal(12,2) DEFAULT NULL COMMENT 'Total order weight in grams',
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
  `shipping_label_generated` tinyint(1) DEFAULT 0,
  `shipping_label_generated_at` datetime DEFAULT NULL,
  `barcode_path` varchar(500) DEFAULT NULL COMMENT 'Path to barcode/QR code image',
  `track_url` text DEFAULT NULL,
  `track_date` datetime DEFAULT NULL,
  `awb_no` varchar(30) DEFAULT NULL,
  `courier` enum('shiprocket','manual','3rd_party','') DEFAULT NULL,
  `third_party_provider` varchar(50) DEFAULT NULL COMMENT 'shiprocket, bigship - when courier=3rd_party',
  `pkg_length_cm` decimal(10,2) DEFAULT NULL COMMENT 'Package length in cm',
  `pkg_breadth_cm` decimal(10,2) DEFAULT NULL COMMENT 'Package breadth in cm',
  `pkg_height_cm` decimal(10,2) DEFAULT NULL COMMENT 'Package height in cm',
  `pkg_weight_kg` decimal(10,2) DEFAULT NULL COMMENT 'Package weight in kg',
  `erp_courier_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK to erp_master_courier when courier=manual',
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
  `is_cancel_invoice` tinyint(1) NOT NULL DEFAULT 0,
  `is_payment_required` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = payment required, 0 = no payment required',
  `is_deliver_at_school` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=deliver at school (student info), 0=deliver at address',
  `ready_to_ship` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Order is ready to ship, 0 = Not ready',
  `ready_to_ship_time` datetime DEFAULT NULL COMMENT 'Timestamp when order was marked ready to ship'
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
  `order_type` varchar(50) DEFAULT 'individual' COMMENT 'Type of order: individual, bookset',
  `package_id` varchar(255) DEFAULT NULL COMMENT 'Comma-separated package IDs for bookset',
  `f_name` varchar(255) DEFAULT NULL COMMENT 'First name for bookset personalization',
  `m_name` varchar(255) DEFAULT NULL COMMENT 'Middle name for bookset personalization',
  `s_name` varchar(255) DEFAULT NULL COMMENT 'Last name for bookset personalization',
  `dob` date DEFAULT NULL COMMENT 'Date of birth for bookset personalization',
  `school_id` int(11) DEFAULT NULL COMMENT 'School ID for bookset',
  `branch_id` int(11) DEFAULT NULL COMMENT 'Branch ID for uniform (erp_school_branches)',
  `board_id` int(11) DEFAULT NULL COMMENT 'Board ID for bookset',
  `grade_id` int(11) DEFAULT NULL COMMENT 'Grade ID for bookset',
  `grade` varchar(50) DEFAULT NULL COMMENT 'Grade/Class for student (when deliver_at_school=1)',
  `roll_number` varchar(50) DEFAULT NULL COMMENT 'Roll number for student (when deliver_at_school=1)',
  `remarks` varchar(250) DEFAULT NULL COMMENT 'Optional remarks for order item (250 chars max)',
  `bookset_packages_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON data for bookset packages',
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
-- Table structure for table `tbl_order_third_party_shipping`
--

CREATE TABLE `tbl_order_third_party_shipping` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL COMMENT 'FK to tbl_order_details.id',
  `order_unique_id` varchar(50) NOT NULL COMMENT 'Order unique ID',
  `order_number` varchar(50) DEFAULT NULL COMMENT 'Invoice/order number',
  `delivery_address_full` text DEFAULT NULL COMMENT 'Full delivery address of the order',
  `pickup_address_full` text DEFAULT NULL COMMENT 'Pickup address (from erp_clients)',
  `pickup_state` varchar(100) DEFAULT NULL,
  `pickup_country` varchar(100) DEFAULT NULL,
  `length_cm` decimal(10,2) DEFAULT NULL COMMENT 'Package length in cm',
  `breadth_cm` decimal(10,2) DEFAULT NULL COMMENT 'Package breadth in cm',
  `height_cm` decimal(10,2) DEFAULT NULL COMMENT 'Package height in cm',
  `weight_kg` decimal(10,2) DEFAULT NULL COMMENT 'Package weight in kg',
  `third_party_provider` varchar(50) NOT NULL COMMENT 'shiprocket, bigship',
  `pickup_provider` varchar(100) DEFAULT NULL COMMENT 'Mini shipping company for pickup (e.g. DTDC, Bluedart within Shiprocket)',
  `awb_no` varchar(100) DEFAULT NULL,
  `track_url` varchar(500) DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `from_time` time DEFAULT NULL,
  `to_time` time DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `system_order_id` varchar(100) DEFAULT NULL,
  `provider_response` longtext DEFAULT NULL,
  `provider_request` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`provider_request`)),
  `booking_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='3rd party shipping details (Shiprocket, Big Ship)';

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
-- Table structure for table `tbl_user_concerns`
--

CREATE TABLE `tbl_user_concerns` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `concern_type` enum('order_status','delivery','product','payment','other') DEFAULT 'other',
  `message` text NOT NULL,
  `contact_preference` enum('phone','email','whatsapp') DEFAULT 'phone',
  `status` enum('pending','in_progress','resolved') DEFAULT 'pending',
  `admin_response` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `velocity_pincode`
--

CREATE TABLE `velocity_pincode` (
  `id` int(11) NOT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `area` varchar(60) DEFAULT NULL,
  `state` varchar(60) DEFAULT NULL,
  `parcel` varchar(10) DEFAULT NULL,
  `ecommerce` varchar(10) DEFAULT NULL,
  `cod` varchar(10) DEFAULT NULL,
  `pickup` varchar(10) DEFAULT NULL,
  `oda` varchar(10) DEFAULT NULL,
  `air_ndd` varchar(30) DEFAULT NULL,
  `surface_ndd` varchar(30) DEFAULT NULL,
  `tat_hub` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `velocity_pincode`
--

INSERT INTO `velocity_pincode` (`id`, `pincode`, `area`, `state`, `parcel`, `ecommerce`, `cod`, `pickup`, `oda`, `air_ndd`, `surface_ndd`, `tat_hub`) VALUES
(2, '110001', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3, '110002', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(4, '110003', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(5, '110004', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(6, '110005', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(7, '110006', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(8, '110007', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(9, '110008', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(10, '110009', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(11, '110010', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(12, '110011', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(13, '110012', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(14, '110013', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(15, '110014', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(16, '110015', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(17, '110016', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(18, '110017', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(19, '110018', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(20, '110019', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(21, '110020', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(22, '110021', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(23, '110022', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(24, '110023', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(25, '110024', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(26, '110025', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(27, '110026', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(28, '110027', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(29, '110028', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(30, '110029', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(31, '110030', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(32, '110031', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(33, '110032', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(34, '110033', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(35, '110034', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(36, '110035', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(37, '110036', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(38, '110037', 'Mahipalpur', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(39, '110038', 'Mahipalpur', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(40, '110039', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(41, '110040', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(42, '110041', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(43, '110042', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(44, '110043', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(45, '110044', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(46, '110045', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(47, '110046', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(48, '110047', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(49, '110048', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(50, '110049', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(51, '110050', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(52, '110051', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(53, '110052', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(54, '110053', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(55, '110054', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(56, '110055', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(57, '110056', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(58, '110057', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(59, '110058', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(60, '110059', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(61, '110060', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(62, '110061', 'Mahipalpur', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(63, '110062', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(64, '110063', 'Karampura', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(65, '110064', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(66, '110065', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(67, '110066', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(68, '110067', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(69, '110068', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(70, '110069', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(71, '110070', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(72, '110071', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(73, '110072', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(74, '110073', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(75, '110074', 'South Extension', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(76, '110075', 'Mahipalpur', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(77, '110076', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(78, '110077', 'Mahipalpur', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(79, '110078', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(80, '110079', 'Mahipalpur', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(81, '110080', 'Okhla', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(82, '110081', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(83, '110082', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(84, '110083', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(85, '110084', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(86, '110085', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(87, '110086', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(88, '110087', 'Uttam Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(89, '110088', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(90, '110089', 'Rohini', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(91, '110090', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(92, '110091', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(93, '110092', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(94, '110093', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(95, '110094', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(96, '110095', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(97, '110096', 'Gandhi Nagar', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(98, '110097', 'Mahipalpur', 'Delhi', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(99, '121001', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(100, '121002', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(101, '121003', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(102, '121004', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(103, '121005', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(104, '121006', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(105, '121007', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(106, '121008', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(107, '121009', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(108, '121010', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(109, '121011', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(110, '121012', 'Faridabad', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(111, '122001', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(112, '122002', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(113, '122003', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(114, '122004', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(115, '122005', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(116, '122006', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(117, '122007', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(118, '122008', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(119, '122009', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(120, '122010', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(121, '122011', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(122, '122012', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(123, '122013', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(124, '122014', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(125, '122015', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(126, '122016', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(127, '122017', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(128, '122018', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(129, '122022', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(130, '122050', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(131, '122051', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(132, '122101', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(133, '122102', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(134, '122505', 'Gurgaon', 'Haryana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(135, '201001', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(136, '201002', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(137, '201003', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(138, '201004', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(139, '201005', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(140, '201006', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(141, '201007', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(142, '201009', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(143, '201010', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(144, '201011', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(145, '201012', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(146, '201013', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(147, '201014', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(148, '201016', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(149, '201017', 'Ghaziabad', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(150, '201301', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(151, '201303', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(152, '201304', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(153, '201305', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(154, '201306', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(155, '201307', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(156, '201308', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(157, '201309', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(158, '201310', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(159, '201316', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(160, '201318', 'NOIDA', 'Uttar Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(161, '360001', 'Rajkot', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(162, '360002', 'Rajkot', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(163, '360003', 'Rajkot', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(164, '360004', 'Rajkot', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(165, '360005', 'Rajkot', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(166, '360006', 'Rajkot', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(167, '360007', 'Rajkot', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(168, '360008', 'RAJKOT', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(169, '360009', 'RAJKOT', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(170, '360311', 'Gondal', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(171, '361001', 'Jamnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(172, '361002', 'Jamnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(173, '361003', 'Jamnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(174, '361004', 'Jamnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(175, '361005', 'Jamnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(176, '361006', 'Jamnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(177, '361007', 'Jamnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(178, '361008', 'Jamnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(179, '363641', 'Morbi', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(180, '363642', 'Morbi', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(181, '364001', 'Bhavnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(182, '364002', 'Bhavnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(183, '364003', 'Bhavnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(184, '364004', 'Bhavnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(185, '364005', 'Bhavnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(186, '364006', 'Bhavnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(187, '370201', 'Gandhidham', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(188, '370205', 'Gandhidham', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(189, '380001', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(190, '380002', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(191, '380003', 'AHMEDABAD', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(192, '380004', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(193, '380005', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(194, '380006', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(195, '380007', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(196, '380008', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(197, '380009', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(198, '380010', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(199, '380011', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(200, '380012', 'ASHARWA', 'GUJARAT', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(201, '380013', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(202, '380014', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(203, '380015', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(204, '380016', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(205, '380017', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(206, '380018', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(207, '380019', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(208, '380020', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(209, '380021', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(210, '380022', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(211, '380023', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(212, '380024', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(213, '380025', 'AHMEDABAD', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(214, '380026', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(215, '380027', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(216, '380028', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(217, '380049', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(218, '380050', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(219, '380051', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(220, '380052', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(221, '380053', 'AHMEDABAD', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(222, '380054', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(223, '380056', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(224, '380058', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(225, '380059', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(226, '380060', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(227, '380061', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(228, '380063', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(229, '382001', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(230, '382002', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(231, '382003', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(232, '382004', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(233, '382005', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(234, '382006', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(235, '382007', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(236, '382008', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(237, '382009', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(238, '382010', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(239, '382011', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(240, '382012', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(241, '382013', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(242, '382014', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(243, '382015', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(244, '382016', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(245, '382017', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(246, '382018', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(247, '382019', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(248, '382020', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(249, '382021', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(250, '382022', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(251, '382023', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(252, '382024', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(253, '382025', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(254, '382026', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(255, '382027', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(256, '382028', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(257, '382029', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(258, '382030', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(259, '382041', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(260, '382325', 'NARODA', 'GUJARAT', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(261, '382330', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(262, '382340', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(263, '382345', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(264, '382346', 'NARODA', 'GUJARAT', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(265, '382350', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(266, '382352', 'KHODIYAR NAGAR', 'GUJARAT', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(267, '382405', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(268, '382410', 'ODHAV', 'GUJARAT', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(269, '382415', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(270, '382418', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(271, '382421', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(272, '382424', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(273, '382430', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(274, '382440', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(275, '382442', 'AHMEDABAD', 'GUJARAT', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(276, '382443', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(277, '382445', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(278, '382449', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(279, '382470', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(280, '382475', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(281, '382480', 'Ahmedabad', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(282, '382481', 'Gandhinagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(283, '383001', 'Himatnagar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(284, '384001', 'Mahesana', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(285, '384002', 'Mahesana', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(286, '384003', 'Mahesana', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(287, '385001', 'Palanpur', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(288, '390001', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(289, '390002', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(290, '390003', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(291, '390004', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(292, '390005', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(293, '390006', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(294, '390007', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(295, '390008', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(296, '390009', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(297, '390010', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(298, '390011', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(299, '390012', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(300, '390013', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(301, '390014', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(302, '390015', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(303, '390016', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(304, '390017', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(305, '390018', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(306, '390019', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(307, '390020', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(308, '390021', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(309, '390022', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(310, '390023', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(311, '390024', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(312, '390025', 'Vadodara', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(313, '392001', 'Bahruch', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(314, '392002', 'BHARUCH', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(315, '392011', 'Bharuch', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(316, '392012', 'BHARUCH', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(317, '392015', 'Bharuch', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(318, '393001', 'Ankleshwar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(319, '393002', 'Ankleshwar', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(320, '394101', 'Chorasi', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(321, '394105', 'Chorasi', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(322, '394107', 'Chorasi', 'GUJARAT', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(323, '394221', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(324, '395001', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(325, '395002', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(326, '395003', 'SURAT', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(327, '395004', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(328, '395005', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(329, '395006', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(330, '395007', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(331, '395008', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(332, '395009', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(333, '395010', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(334, '395011', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(335, '395012', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(336, '395013', 'Choryasi', 'GUJARAT', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(337, '395017', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(338, '395023', 'Surat', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(339, '396191', 'Pardi', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(340, '396195', 'Pardi', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(341, '396421', 'Navsari', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(342, '396445', 'Navsari', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(343, '396450', 'Navsari', 'Gujarat', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(344, '396463', 'Jalalpore', 'GUJARAT', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(345, '400001', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(346, '400002', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(347, '400003', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(348, '400004', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(349, '400005', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(350, '400006', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(351, '400007', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(352, '400008', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(353, '400009', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(354, '400010', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(355, '400011', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(356, '400012', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(357, '400013', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(358, '400014', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(359, '400015', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(360, '400016', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(361, '400017', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(362, '400018', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(363, '400019', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(364, '400020', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(365, '400021', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(366, '400022', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(367, '400023', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(368, '400024', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(369, '400025', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(370, '400026', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(371, '400027', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(372, '400028', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(373, '400029', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(374, '400030', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(375, '400031', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(376, '400032', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(377, '400033', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(378, '400034', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(379, '400035', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(380, '400036', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(381, '400037', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(382, '400038', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(383, '400039', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(384, '400041', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(385, '400042', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(386, '400043', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(387, '400049', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(388, '400050', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(389, '400051', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(390, '400052', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(391, '400053', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(392, '400054', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(393, '400055', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(394, '400056', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(395, '400057', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(396, '400058', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(397, '400059', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(398, '400060', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(399, '400061', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(400, '400062', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(401, '400063', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(402, '400064', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(403, '400065', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(404, '400066', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(405, '400067', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(406, '400068', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(407, '400069', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(408, '400070', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(409, '400071', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(410, '400072', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(411, '400074', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(412, '400075', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(413, '400076', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(414, '400077', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(415, '400078', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(416, '400079', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(417, '400080', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(418, '400081', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(419, '400082', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(420, '400083', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(421, '400084', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(422, '400085', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(423, '400086', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(424, '400087', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(425, '400088', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(426, '400089', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(427, '400090', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(428, '400091', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(429, '400092', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(430, '400093', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(431, '400094', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(432, '400095', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(433, '400096', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(434, '400097', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(435, '400098', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(436, '400099', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(437, '400101', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(438, '400102', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(439, '400103', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(440, '400104', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(441, '400105', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(442, '400601', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(443, '400602', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(444, '400603', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(445, '400604', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(446, '400605', 'Mumbai', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(447, '400606', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(448, '400607', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(449, '400608', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(450, '400609', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(451, '400610', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(452, '400611', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(453, '400612', 'THANE', 'MAHARASHTRA', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '2'),
(454, '400613', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(455, '400614', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(456, '400615', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(457, '400701', 'Navi Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(458, '400703', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(459, '400705', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(460, '400706', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(461, '400708', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(462, '400709', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(463, '400710', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(464, '401101', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(465, '401102', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '2'),
(466, '401103', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '2'),
(467, '401104', 'Mumbai', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(468, '401105', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(469, '401106', 'Mumbai', 'MAHARASHTRA', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '2'),
(470, '401107', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(471, '401201', 'Mumbai', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(472, '401202', 'Mumbai', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(473, '401203', 'Mumbai', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(474, '401204', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '2'),
(475, '401205', 'Mumbai', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(476, '401206', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(477, '401207', 'Mumbai', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(478, '401208', 'MUMBAI', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(479, '401209', 'Mumbai', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(480, '401210', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(481, '401301', 'VASAI VIRAR', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(482, '401302', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(483, '401303', 'Mumbai', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(484, '401304', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(485, '401305', 'VASAI VIRAR', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(486, '401401', 'Mumbai', 'MAHARASHTRA', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(487, '401403', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(488, '401404', 'Thane', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', 'N', '3'),
(489, '401405', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', 'N', '3'),
(490, '401501', 'Thane', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', 'N', '3'),
(491, '401503', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', 'N', '3'),
(492, '401504', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(493, '401505', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(494, '401506', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', 'N', '3'),
(495, '401601', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(496, '401603', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(497, '401604', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(498, '401605', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(499, '401606', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(500, '401607', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(501, '401608', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(502, '401609', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(503, '401610', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(504, '401701', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(505, '401702', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(506, '401703', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', 'N', '3'),
(507, '410208', 'NAVI MUMBAI', 'MAHARASHTRA', 'Y', 'N', 'N', 'N', 'N', '', 'N', '3'),
(508, '410209', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(509, '410210', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(510, '410218', 'Mumbai', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(511, '410402', 'Lonavala', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '3'),
(512, '410403', 'Lonavala', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '3'),
(513, '410405', 'Lonavala', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', '3'),
(514, '410501', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(515, '410502', 'Junnar', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', '3'),
(516, '410503', 'Ambegaon', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', '3'),
(517, '410504', 'Ambegaon', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', '3'),
(518, '410505', 'Rajgurunagar', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '3'),
(519, '410506', 'Pune', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '2'),
(520, '410507', 'Pune', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '2'),
(521, '410515', 'Ambegaon', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', '3'),
(522, '411001', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(523, '411002', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(524, '411003', 'Wakdewadi', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(525, '411004', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(526, '411005', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(527, '411006', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(528, '411007', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(529, '411008', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(530, '411009', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(531, '411010', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(532, '411011', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(533, '411012', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(534, '411013', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(535, '411014', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(536, '411015', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(537, '411016', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(538, '411017', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(539, '411018', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(540, '411019', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(541, '411020', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(542, '411021', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(543, '411022', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(544, '411023', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(545, '411024', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(546, '411025', 'Pune', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '2'),
(547, '411026', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(548, '411027', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(549, '411028', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(550, '411029', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(551, '411030', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(552, '411031', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(553, '411032', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(554, '411033', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(555, '411034', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(556, '411035', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(557, '411036', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(558, '411037', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(559, '411038', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(560, '411039', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(561, '411040', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(562, '411041', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(563, '411042', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(564, '411043', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(565, '411044', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(566, '411045', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(567, '411046', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(568, '411047', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(569, '411048', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(570, '411049', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(571, '411050', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(572, '411051', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(573, '411052', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(574, '411053', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(575, '411057', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(576, '411058', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(577, '411060', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(578, '411061', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(579, '411062', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(580, '411067', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(581, '411068', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(582, '412101', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(583, '412104', 'Saswad', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', '2'),
(584, '412105', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(585, '412108', 'Pirangut', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '3'),
(586, '412109', 'Pune', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '2'),
(587, '412110', 'Pune', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '2'),
(588, '412114', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(589, '412115', 'Pune', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '3'),
(590, '412201', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(591, '412202', 'UrliKanchan', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(592, '412203', 'Daund', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', '3'),
(593, '412207', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(594, '412208', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(595, '412209', 'Shirur', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '3'),
(596, '412210', 'Shirur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '3'),
(597, '412213', 'Khandala', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '3'),
(598, '412214', 'UrliKanchan', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(599, '412216', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(600, '412219', 'Daund', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', '3'),
(601, '412220', 'Shirur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '3'),
(602, '412301', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(603, '412303', 'Saswad', 'Maharashtra', 'Y', 'N', 'N', 'N', 'N', '', '', '3'),
(604, '412307', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(605, '412308', 'Pune', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(606, '412411', 'Alephata', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', '3'),
(607, '413101', 'Akluj', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '3'),
(608, '413102', 'Daund', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '2'),
(609, '413106', 'Indapur', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', '3'),
(610, '413133', 'Daund', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '2'),
(611, '413201', 'Jamkhed', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(612, '413202', 'Karmala', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(613, '413204', 'Jamkhed', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(614, '413205', 'Jamkhed', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(615, '413206', 'Karmala', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(616, '413510', 'Latur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(617, '413511', 'Latur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(618, '413512', 'Latur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(619, '413513', 'Ahmadpur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(620, '413515', 'Ahmadpur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(621, '413517', 'Udgir', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(622, '413519', 'Udgir', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(623, '413520', 'Latur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(624, '413521', 'Nilanga', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(625, '413522', 'Nilanga', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(626, '413524', 'Nilanga', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(627, '413531', 'Latur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(628, '413532', 'Mukhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(629, '413544', 'Latur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(630, '413701', 'Shrigonda', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(631, '413702', 'Parner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '');
INSERT INTO `velocity_pincode` (`id`, `pincode`, `area`, `state`, `parcel`, `ecommerce`, `cod`, `pickup`, `oda`, `air_ndd`, `surface_ndd`, `tat_hub`) VALUES
(632, '413704', 'Rahuri', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(633, '413705', 'Rahuri', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(634, '413706', 'Rahuri', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(635, '413707', 'Shrirampur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(636, '413709', 'Shrirampur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(637, '413710', 'Loni', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(638, '413711', 'Loni', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(639, '413712', 'Loni', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(640, '413713', 'Loni', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(641, '413714', 'Sangamner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(642, '413715', 'Shrirampur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(643, '413716', 'Rahuri', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(644, '413717', 'Shrirampur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(645, '413718', 'Shrirampur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(646, '413719', 'Shirdi', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(647, '413720', 'Shrirampur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(648, '413721', 'Shrirampur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(649, '413722', 'Rahuri', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(650, '413723', 'Shrirampur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(651, '413725', 'Shrirampur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(652, '413726', 'Shrigonda', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(653, '413728', 'Shrigonda', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(654, '413736', 'Loni', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(655, '413737', 'Loni', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(656, '413738', 'Sangamner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(657, '413739', 'Shrirampur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(658, '413801', 'Daund', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '3'),
(659, '413802', 'Daund', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '3'),
(660, '414001', 'Ahmednagar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(661, '414002', 'Ahmednagar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(662, '414003', 'Ahmednagar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(663, '414005', 'Ahmednagar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(664, '414006', 'Ahmednagar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(665, '414011', 'Shrigonda', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(666, '414102', 'Pathardi', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(667, '414103', 'Ahmednagar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(668, '414104', 'Junnar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(669, '414105', 'Newasa', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(670, '414106', 'Pathardi', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(671, '414107', 'Shrigonda', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(672, '414110', 'Ahmednagar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(673, '414111', 'Ahmednagar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(674, '414113', 'Pathardi', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(675, '414201', 'Ahmednagar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(676, '414202', 'Ashti', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(677, '414203', 'Ashti', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(678, '414204', 'Jamkhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(679, '414205', 'BEDF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(680, '414208', 'Ashti', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(681, '414301', 'Parner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(682, '414302', 'Parner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(683, '414305', 'Alephata', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(684, '414306', 'Shirur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(685, '414401', 'Karjat-MH', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(686, '414402', 'Karjat-MH', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(687, '414403', 'Karjat-MH', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(688, '414501', 'Pathardi', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(689, '414502', 'Shevgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(690, '414503', 'Shevgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(691, '414505', 'Pathardi', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(692, '414601', 'Ahmednagar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(693, '414602', 'Newasa', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(694, '414603', 'Newasa', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(695, '414604', 'Newasa', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(696, '414605', 'Newasa', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(697, '414606', 'Newasa', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(698, '414607', 'Newasa', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(699, '414609', 'Newasa', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(700, '414701', 'Daund', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(701, '414702', 'Daund', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(702, '414703', 'Daund', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(703, '414709', 'Daund', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(704, '415001', 'Satara', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(705, '415002', 'Satara', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(706, '415003', 'Satara', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(707, '415004', 'Satara', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(708, '415005', 'Satara', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', 'N', 'N', '2'),
(709, '415011', 'Satara', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '2'),
(710, '415012', 'Satara', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '2'),
(711, '415015', 'Satara', 'Maharashtra', 'Y', 'Y', 'N', 'Y', 'Y', 'N', 'N', '2'),
(712, '415019', 'Satara', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '2'),
(713, '415020', 'Satara', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '2'),
(714, '415022', 'Satara', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '2'),
(715, '415513', 'Satara', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(716, '415518', 'Satara', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '2'),
(717, '416001', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(718, '416002', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(719, '416003', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(720, '416004', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(721, '416005', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(722, '416006', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(723, '416007', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(724, '416008', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(725, '416009', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(726, '416010', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(727, '416011', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(728, '416012', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(729, '416112', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'N', '1'),
(730, '416115', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N', '1'),
(731, '416116', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N', '1'),
(732, '416119', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(733, '416122', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(734, '416201', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'N', '1'),
(735, '416202', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'N', '1'),
(736, '416203', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'N', '1'),
(737, '416204', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'N', '1'),
(738, '416232', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '1'),
(739, '416234', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(740, '416235', 'Kolhapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '1'),
(741, '416304', 'Sangli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '1'),
(742, '416306', 'Sangli', 'Maharashtra', 'Y', 'Y', 'y', 'N', 'Y', 'N', 'N', '2'),
(743, '416401', 'Sangli', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'N', '2'),
(744, '416406', 'Sangli', 'Maharashtra', 'Y', 'Y', 'y', 'N', 'N', 'N', 'N', '1'),
(745, '416407', 'Sangli', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'N', '2'),
(746, '416409', 'Sangli', 'Maharashtra', 'Y', 'Y', 'y', 'N', 'Y', 'N', 'N', '1'),
(747, '416410', 'Sangli', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N', '1'),
(748, '416414', 'Sangli', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'N', '1'),
(749, '416415', 'Sangli', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(750, '416416', 'Sangli', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(751, '416420', 'Sangli', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'N', '2'),
(752, '416436', 'Sangli', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N', '1'),
(753, '416437', 'Sangli', 'Maharashtra', 'Y', 'Y', 'Y', 'N', 'Y', 'N', 'N', '1'),
(754, '421001', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(755, '421002', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(756, '421003', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(757, '421004', 'MUMBAI', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(758, '421005', 'THANE', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(759, '421101', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'Y', 'N', '', 'N', '1'),
(760, '421201', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(761, '421202', 'THANE', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(762, '421203', 'THANE', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(763, '421204', 'MUMBAI', 'MAHARASHTRA', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(764, '421301', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(765, '421302', 'Thane', 'Maharashtra', 'Y', 'N', 'N', 'Y', 'N', '', 'N', '3'),
(766, '421303', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'Y', 'Y', '', 'N', '3'),
(767, '421304', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'Y', 'N', '', 'N', '3'),
(768, '421305', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'Y', 'N', '', 'N', '3'),
(769, '421306', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(770, '421308', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'Y', 'Y', '', 'N', '3'),
(771, '421312', 'Mumbai Remote Outskirt', 'Maharashtra', 'Y', 'N', 'N', 'Y', 'Y', '', 'N', '3'),
(772, '421501', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(773, '421502', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(774, '421504', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(775, '421505', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(776, '421506', 'Thane', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(777, '422001', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(778, '422002', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(779, '422003', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(780, '422004', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(781, '422005', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(782, '422006', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(783, '422007', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(784, '422008', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(785, '422009', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(786, '422010', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(787, '422011', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(788, '422012', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(789, '422013', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(790, '422101', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(791, '422102', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(792, '422103', 'Sinnar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(793, '422104', 'Sinnar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(794, '422105', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(795, '422112', 'Sinnar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(796, '422113', 'Sinnar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(797, '422201', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(798, '422202', 'Dindori-MH', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(799, '422203', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(800, '422204', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(801, '422205', 'Dindori-MH', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(802, '422206', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(803, '422207', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(804, '422208', 'NSK', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(805, '422209', 'Dindori-MH', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(806, '422210', 'Sinnar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(807, '422212', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(808, '422213', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(809, '422214', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(810, '422215', 'Dindori-MH', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(811, '422221', 'Dindori-MH', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(812, '422222', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(813, '422301', 'Dindori-MH', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(814, '422302', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(815, '422303', 'Lasalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(816, '422304', 'Lasalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(817, '422305', 'Lasalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(818, '422306', 'Lasalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(819, '422308', 'Lasalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(820, '422401', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(821, '422402', 'Igatpuri', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(822, '422403', 'Igatpuri', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(823, '422501', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(824, '422502', 'Nashik', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(825, '422601', 'Akole', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(826, '422603', 'Sangamner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(827, '422604', 'Akole', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(828, '422605', 'Sangamner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(829, '422606', 'Sinnar', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(830, '422608', 'Sangamner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(831, '422611', 'Sangamner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(832, '422620', 'XHD', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(833, '423102', 'Satana', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(834, '423104', 'Manmad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(835, '423105', 'Malegaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(836, '423106', 'Manmad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(837, '423107', 'Shirdi', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(838, '423108', 'Malegaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(839, '423109', 'Shirdi', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(840, '423110', 'Chandwad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(841, '423117', 'Chandwad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(842, '423201', 'Malegaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(843, '423202', 'Malegaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(844, '423203', 'Malegaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(845, '423204', 'Satana', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(846, '423205', 'Malegaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(847, '423206', 'Malegaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(848, '423208', 'Malegaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(849, '423212', 'Malegaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(850, '423213', 'Satana', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(851, '423301', 'Satana', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(852, '423302', 'Satana', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(853, '423303', 'Satana', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(854, '423401', 'Yeola', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(855, '423402', 'Yeola', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(856, '423403', 'Yeola', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(857, '423501', 'Satana', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(858, '423502', 'Dindori-MH', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(859, '423601', 'Kopargaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(860, '423602', 'Kopargaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(861, '423603', 'Kopargaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(862, '423701', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(863, '423702', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(864, '423703', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(865, '424001', 'Dhule', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(866, '424002', 'Dhule', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(867, '424004', 'Dhule', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(868, '424005', 'Dhule', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(869, '424006', 'Dhule', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '1'),
(870, '424101', 'Chalisgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(871, '424102', 'Pachora', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(872, '424103', 'Pachora', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(873, '424104', 'Pachora', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(874, '424105', 'Pachora', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(875, '424106', 'Chalisgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(876, '424109', 'Manmad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(877, '424119', 'Chalisgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(878, '424201', 'Pachora', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(879, '424204', 'Soegaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(880, '424205', 'Jamner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(881, '424206', 'Jamner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(882, '424208', 'Jamner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(883, '424301', 'Dhule', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(884, '424303', 'Dhule', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(885, '424304', 'Sakri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(886, '424306', 'Sakri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(887, '424309', 'Shindkheda', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(888, '424310', 'Sakri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(889, '424311', 'Dhule', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(890, '425001', 'Jalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(891, '425002', 'Jalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(892, '425003', 'Jalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '1'),
(893, '425101', 'Jalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(894, '425103', 'Jalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(895, '425105', 'JANF', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(896, '425107', 'Chopda', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(897, '425109', 'JANF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(898, '425110', 'JANF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(899, '425111', 'Amalner', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(900, '425115', 'Pachora', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(901, '425116', 'Jalgaon', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(902, '425201', 'Bhusawal', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(903, '425203', 'Bhusawal', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(904, '425301', 'Bhusawal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(905, '425302', 'JANF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(906, '425303', 'Chopda', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(907, '425305', 'Bhusawal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(908, '425306', 'Raver', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(909, '425307', 'Bhusawal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(910, '425308', 'Bhusawal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(911, '425309', 'Bhusawal', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(912, '425310', 'Malkapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(913, '425311', 'Malkapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(914, '425327', 'JANF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(915, '425401', 'Amalner', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(916, '425402', 'Amalner', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(917, '425403', 'Shindkheda', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(918, '425405', 'Shirpur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(919, '425406', 'Shindkheda', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(920, '425408', 'Shindkheda', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(921, '425409', 'Shahada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(922, '425411', 'Nandurbar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(923, '425412', 'Nandurbar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(924, '425413', 'Nandurbar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(925, '425415', 'Akkalkuva', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(926, '425416', 'Nandurbar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(927, '425417', 'Navapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(928, '425418', 'Navapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(929, '425419', 'Akkalkuva', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(930, '425420', 'Amalner', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(931, '425422', 'Shahada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(932, '425423', 'Shahada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(933, '425426', 'Navapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(934, '425428', 'Shirpur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(935, '425432', 'DHUF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(936, '425501', 'Raver', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(937, '425502', 'Raver', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(938, '425503', 'Bhusawal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(939, '425504', 'Raver', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(940, '425505', 'Raver', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(941, '425507', 'Raver', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(942, '425508', 'Raver', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(943, '425524', 'Bhusawal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', 'N', 'N', '2'),
(944, '431001', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(945, '431002', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(946, '431003', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(947, '431004', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(948, '431005', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(949, '431006', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(950, '431007', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(951, '431008', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(952, '431009', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(953, '431010', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(954, '431011', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(955, '431016', 'AURANGABAD', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(956, '431101', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(957, '431102', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(958, '431103', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(959, '431104', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(960, '431105', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(961, '431106', 'AURANGABAD', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(962, '431107', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(963, '431109', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(964, '431111', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(965, '431112', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(966, '431113', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(967, '431114', 'Sillod', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(968, '431115', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(969, '431116', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(970, '431117', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(971, '431118', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(972, '431120', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(973, '431121', 'Paithan', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(974, '431122', 'Beed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '1'),
(975, '431123', 'Kaij', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(976, '431124', 'Ambajogai', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(977, '431125', 'Beed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(978, '431127', 'Georai', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(979, '431128', 'BEDF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(980, '431129', 'Manjlegaon', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(981, '431130', 'Georai', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(982, '431131', 'Manjlegaon', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '2'),
(983, '431132', 'Bokardhan', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(984, '431133', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(985, '431134', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(986, '431135', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(987, '431136', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(988, '431137', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(989, '431143', 'Georai', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(990, '431147', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(991, '431148', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(992, '431150', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(993, '431151', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(994, '431154', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '1'),
(995, '431201', 'AURANGABAD', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(996, '431202', 'Jalna', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '1'),
(997, '431203', 'Jalna', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '1'),
(998, '431204', 'Jalna', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(999, '431205', 'Georai', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1000, '431206', 'Jafrabad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1001, '431207', 'Partur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1002, '431208', 'Jafrabad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1003, '431209', 'Partur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '2'),
(1004, '431210', 'Aurangabad', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1005, '431211', 'AURANGABAD', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1006, '431212', 'Georai', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1007, '431213', 'Jalna', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '2'),
(1008, '431214', 'AURANGABAD', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1009, '431215', 'Jalna', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1010, '431401', 'Parbhani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '1'),
(1011, '431402', 'Parbhani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '1'),
(1012, '431501', 'Partur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1013, '431502', 'AURANGABAD', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1014, '431503', 'Selu', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1015, '431504', 'Mantha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1016, '431505', 'Selu', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '2'),
(1017, '431506', 'Selu', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1018, '431507', 'Manjlegaon', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1019, '431508', 'Mantha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1020, '431509', 'Mantha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1021, '431510', 'Mantha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1022, '431511', 'Vasmat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1023, '431512', 'Vasmat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1024, '431513', 'Hingoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1025, '431514', 'Gangakher', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1026, '431515', 'Parli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1027, '431516', 'Parli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1028, '431517', 'Ambajogai', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1029, '431518', 'Kaij', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1030, '431521', 'Parbhani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1031, '431537', 'Parbhani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1032, '431540', 'AURANGABAD', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1033, '431542', 'PBNF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1034, '431601', 'Nanded', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '1'),
(1035, '431602', 'Nanded', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '1'),
(1036, '431603', 'Nanded', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '1'),
(1037, '431604', 'Nanded', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '1'),
(1038, '431605', 'Nanded', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '1'),
(1039, '431606', 'Nanded', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '2'),
(1040, '431646', 'Mukhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1041, '431701', 'Waranga Phata', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1042, '431702', 'Hingoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1043, '431703', 'Hingoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1044, '431704', 'Nanded', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '2'),
(1045, '431705', 'Hingoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1046, '431707', 'Loha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1047, '431708', 'Loha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1048, '431709', 'Mukhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1049, '431710', 'Dharmabad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1050, '431711', 'Dharmabad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1051, '431712', 'Umarkhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1052, '431713', 'Bhokar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1053, '431714', 'Loha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1054, '431715', 'Mukhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1055, '431716', 'Mukhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1056, '431717', 'Degloor', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1057, '431718', 'Degloor', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1058, '431719', 'Udgir', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1059, '431720', 'Gangakher', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1060, '431721', 'Mahagaon', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1061, '431722', 'Mukhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1062, '431723', 'Degloor', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1063, '431731', 'AURANGABAD', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1064, '431736', 'Mukhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1065, '431742', 'Mukhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1066, '431743', 'Umarkhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1067, '431745', 'Nanded', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1068, '431746', 'Mukhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1069, '431750', 'Vasmat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1070, '431801', 'Bhokar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1071, '431802', 'Bhokar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1072, '431803', 'Bhokar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1073, '431804', 'Kinwat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1074, '431805', 'Kinwat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1075, '431806', 'Bhokar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1076, '431807', 'Bhokar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1077, '431808', 'Dharmabad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1078, '431809', 'Dharmabad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1079, '431810', 'Kinwat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1080, '431811', 'Kinwat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '2'),
(1081, '431812', 'Kinwat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', '2'),
(1082, '440001', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1083, '440002', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1084, '440003', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1085, '440004', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1086, '440005', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1087, '440006', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1088, '440007', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1089, '440008', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1090, '440009', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1091, '440010', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1092, '440011', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1093, '440012', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1094, '440013', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1095, '440014', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1096, '440015', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1097, '440016', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1098, '440017', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1099, '440018', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1100, '440019', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1101, '440020', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1102, '440021', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1103, '440022', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1104, '440023', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1105, '440024', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1106, '440025', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1107, '440026', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1108, '440027', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1109, '440028', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1110, '440029', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1111, '440030', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1112, '440032', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1113, '440033', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1114, '440034', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1115, '440035', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1116, '440036', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1117, '440037', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1118, '441001', 'NGP', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1119, '441002', 'NGP', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1120, '441101', 'Savner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1121, '441102', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1122, '441103', 'Katol', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1123, '441104', 'Ramtek', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1124, '441105', 'Savner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1125, '441106', 'Ramtek', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1126, '441107', 'Savner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1127, '441108', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1128, '441109', 'Nagpur', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', ''),
(1129, '441110', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1130, '441111', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1131, '441112', 'Savner', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1132, '441113', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1133, '441122', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1134, '441123', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1135, '441201', 'Pauni', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1136, '441202', 'Umred', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1137, '441203', 'Umred', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1138, '441204', 'Umred', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1139, '441205', 'Brahmapuri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1140, '441206', 'Brahmapuri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1141, '441207', 'Brahmapuri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1142, '441208', 'Brahmapuri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1143, '441209', 'Kurkheda', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1144, '441210', 'Umred', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1145, '441212', 'Mul', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1146, '441214', 'Umred', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1147, '441215', 'Saoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1148, '441217', 'Kurkheda', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1149, '441221', 'Sindewahi', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1150, '441222', 'Sindewahi', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1151, '441223', 'Sindewahi', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1152, '441224', 'Mul', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1153, '441225', 'Saoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1154, '441226', 'Saoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1155, '441227', 'NGP', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', ''),
(1156, '441228', 'Saoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1157, '441301', 'Katol', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1158, '441302', 'Katol', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1159, '441303', 'Katol', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1160, '441304', 'Katol', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1161, '441305', 'Katol', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1162, '441306', 'Katol', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1163, '441401', 'Ramtek', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1164, '441404', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1165, '441406', 'Ramtek', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1166, '441501', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'N', '', '', ''),
(1167, '441502', 'Nagpur', 'Maharashtra', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', ''),
(1168, '441601', 'Gondia', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1169, '441614', 'Gondia', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1170, '441701', 'Brahmapuri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1171, '441702', 'Sadak Arjuni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1172, '441801', 'Gondia', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1173, '441802', 'Sakoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1174, '441803', 'Brahmapuri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1175, '441804', 'Sakoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1176, '441805', 'Sakoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1177, '441806', 'Sadak Arjuni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1178, '441807', 'Sadak Arjuni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1179, '441808', 'Sakoli', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', ''),
(1180, '441809', 'Sakoli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1181, '441811', 'Sakoli', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', ''),
(1182, '441901', 'Sadak Arjuni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1183, '441902', 'Gondia', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1184, '441903', 'Pauni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1185, '441904', 'Bhandara', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1186, '441905', 'Bhandara', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1187, '441906', 'Bhandara', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1188, '441907', 'Tumsar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1189, '441908', 'Pauni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1190, '441909', 'Tumsar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1191, '441910', 'Pauni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1192, '441911', 'Gondia', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1193, '441912', 'Tumsar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1194, '441913', 'Tumsar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1195, '441914', 'Ramtek', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1196, '441915', 'Tumsar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1197, '441916', 'GONF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1198, '441917', 'NGP', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', ''),
(1199, '441918', 'NGP', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', ''),
(1200, '441919', 'Gondia', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', ''),
(1201, '441920', 'Umred', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', ''),
(1202, '441923', 'NGP', 'Maharashtra', 'Y', 'N', 'N', 'N', 'Y', '', '', ''),
(1203, '441924', 'Bhandara', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1204, '442001', 'Wardha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1205, '442101', 'Pulgaon', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1206, '442102', 'Wardha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1207, '442104', 'Wardha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1208, '442105', 'Wardha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1209, '442201', 'Talegaon Sp', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1210, '442202', 'Talegaon Sp', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1211, '442203', 'Talegaon Sp', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1212, '442301', 'Hinganghat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1213, '442302', 'Pulgaon', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1214, '442303', 'Pulgaon', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1215, '442304', 'Hinganghat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1216, '442305', 'Hinganghat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1217, '442306', 'Pulgaon', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1218, '442307', 'Hinganghat', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1219, '442401', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1220, '442402', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1221, '442403', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1222, '442404', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1223, '442406', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1224, '442501', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1225, '442502', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1226, '442503', 'Wani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1227, '442504', 'Sironcha', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1228, '442505', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1229, '442507', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1230, '442603', 'Chamorshi', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1231, '442604', 'Chamorshi', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1232, '442605', 'Gadchiroli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1233, '442606', 'GDCF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1234, '442701', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1235, '442702', 'Gadchandur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1236, '442703', 'Aheri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1237, '442704', 'Aheri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1238, '442705', 'Aheri', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1239, '442707', 'GDCF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1240, '442901', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1241, '442902', 'Bhadravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1242, '442903', 'Chimur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1243, '442904', 'Chimur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1244, '442905', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1245, '442906', 'CHRF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1246, '442907', 'Wani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1247, '442908', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1248, '442914', 'Wani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1249, '442916', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1250, '442917', 'Chandrapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1251, '442918', 'Gadchandur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1252, '443001', 'Buldhana', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1253, '443101', 'Malkapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1254, '443102', 'Malkapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', '');
INSERT INTO `velocity_pincode` (`id`, `pincode`, `area`, `state`, `parcel`, `ecommerce`, `cod`, `pickup`, `oda`, `air_ndd`, `surface_ndd`, `tat_hub`) VALUES
(1255, '443103', 'Malkapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1256, '443104', 'Jamner', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1257, '443105', 'Buldhana', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1258, '443106', 'Buldhana', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1259, '443201', 'Chikhli', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1260, '443202', 'Dusrabid', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1261, '443203', 'Jalna', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1262, '443204', 'Jalna', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1263, '443206', 'Jalna', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1264, '443209', 'Dusrabid', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1265, '443301', 'Lonar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1266, '443302', 'Lonar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1267, '443303', 'Lonar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1268, '443304', 'Lonar', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1269, '443305', 'BLDF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1270, '443308', 'Dusrabid', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1271, '443401', 'Nandura', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1272, '443402', 'Jalgaon Jamod', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1273, '443403', 'Nandura', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1274, '443404', 'Nandura', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1275, '443405', 'Nandura', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1276, '444001', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1277, '444002', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1278, '444003', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1279, '444004', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1280, '444005', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1281, '444006', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1282, '444101', 'Akot', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1283, '444102', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1284, '444103', 'Akot', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1285, '444104', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1286, '444105', 'Karanja', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1287, '444106', 'Murtizapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1288, '444107', 'Murtizapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1289, '444108', 'Akot', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1290, '444109', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1291, '444110', 'Murtizapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1292, '444404', 'Digras', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1293, '444405', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1294, '444407', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1295, '444409', 'AMVF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1296, '444501', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1297, '444502', 'Akola', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1298, '444503', 'Washim', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1299, '444504', 'Washim', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1300, '444505', 'WASF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1301, '444506', 'WASF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1302, '444507', 'WASF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1303, '444510', 'AMVF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1304, '444511', 'AKOF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1305, '444512', 'Washim', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1306, '444601', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1307, '444602', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1308, '444603', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1309, '444604', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1310, '444605', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1311, '444606', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1312, '444607', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1313, '444701', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1314, '444702', 'Dharni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1315, '444704', 'Morshi', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1316, '444705', 'Daryapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1317, '444706', 'Daryapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1318, '444707', 'Morshi', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1319, '444708', 'Yavatmal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1320, '444709', 'Dhamangaon', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1321, '444710', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1322, '444711', 'Dhamangaon', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1323, '444717', 'PRWF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1324, '444720', 'Paratwada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1325, '444723', 'Paratwada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1326, '444801', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1327, '444802', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1328, '444803', 'Daryapur', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1329, '444804', 'Paratwada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1330, '444805', 'Paratwada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1331, '444806', 'Paratwada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1332, '444807', 'Paratwada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1333, '444808', 'Paratwada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1334, '444809', 'Paratwada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1335, '444810', 'Paratwada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1336, '444813', 'AMVF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1337, '444901', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1338, '444902', 'Morshi', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1339, '444903', 'Morshi', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1340, '444904', 'Amravati', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1341, '444905', 'Morshi', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1342, '444906', 'Warud', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1343, '444907', 'Warud', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1344, '444908', 'Warud', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1345, '444913', 'Warud', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1346, '444921', 'AMVF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1347, '445001', 'Yavatmal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1348, '445002', 'Yavatmal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1349, '445101', 'Yavatmal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1350, '445102', 'Yavatmal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1351, '445103', 'Arni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1352, '445105', 'Arni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1353, '445106', 'Arni', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1354, '445109', 'Ghatanji', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1355, '445110', 'Digras', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1356, '445201', 'Yavatmal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1357, '445202', 'Digras', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1358, '445203', 'Digras', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1359, '445204', 'Pusad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1360, '445206', 'Umarkhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1361, '445207', 'Umarkhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1362, '445208', 'YALF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1363, '445211', 'Umarkhed', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1364, '445215', 'Pusad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1365, '445216', 'Pusad', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1366, '445230', 'YALF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'N', '', '', ''),
(1367, '445301', 'Ghatanji', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1368, '445302', 'Pandharkawada', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1369, '445303', 'Wani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1370, '445304', 'Wani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1371, '445305', 'Wani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1372, '445306', 'Ghatanji', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1373, '445307', 'Wani', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1374, '445308', 'YALF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1375, '445401', 'Yavatmal', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1376, '445402', 'YALF', 'Maharashtra', 'Y', 'Y', 'N', 'N', 'Y', '', '', ''),
(1377, '500001', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1378, '500002', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1379, '500003', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1380, '500004', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1381, '500005', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1382, '500006', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1383, '500007', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1384, '500008', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1385, '500009', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1386, '500010', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1387, '500011', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1388, '500012', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1389, '500013', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1390, '500014', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1391, '500015', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1392, '500016', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1393, '500017', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1394, '500018', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1395, '500019', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1396, '500020', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1397, '500021', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1398, '500022', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1399, '500023', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1400, '500024', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1401, '500025', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1402, '500026', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1403, '500027', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1404, '500028', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1405, '500029', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1406, '500030', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1407, '500031', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1408, '500032', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1409, '500033', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1410, '500034', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1411, '500035', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1412, '500036', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1413, '500037', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1414, '500038', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1415, '500039', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1416, '500040', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1417, '500041', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1418, '500042', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1419, '500043', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1420, '500044', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1421, '500045', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1422, '500046', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1423, '500047', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1424, '500048', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1425, '500049', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1426, '500050', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1427, '500051', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1428, '500053', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1429, '500054', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1430, '500055', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1431, '500056', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1432, '500057', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1433, '500058', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1434, '500059', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1435, '500060', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1436, '500061', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1437, '500062', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1438, '500063', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1439, '500064', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1440, '500065', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1441, '500066', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1442, '500067', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1443, '500068', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1444, '500069', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1445, '500070', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1446, '500071', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1447, '500072', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1448, '500073', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1449, '500074', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1450, '500075', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1451, '500076', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1452, '500077', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1453, '500078', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1454, '500079', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1455, '500080', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1456, '500081', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1457, '500082', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1458, '500083', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1459, '500084', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1460, '500085', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1461, '500086', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1462, '500087', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1463, '500089', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1464, '500090', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1465, '500091', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1466, '500092', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1467, '500093', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1468, '500094', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1469, '500095', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1470, '500096', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1471, '500097', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1472, '500098', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1473, '500100', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1474, '500101', 'HYDERABAD', 'TELANGANA', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '1'),
(1475, '506001', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1476, '506002', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1477, '506003', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1478, '506004', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1479, '506005', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1480, '506006', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1481, '506007', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1482, '506008', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1483, '506009', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1484, '506010', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1485, '506011', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1486, '506012', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1487, '506013', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1488, '506014', 'Warangal', 'Telangana', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1489, '515001', 'Anantapur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1490, '515002', 'Anantapur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1491, '515003', 'Anantapur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1492, '515004', 'Anantapur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1493, '515005', 'Anantapur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1494, '516001', 'Cuddapah', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1495, '516002', 'Cuddapah', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1496, '516003', 'Cuddapah', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1497, '516004', 'Cuddapah', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1498, '517001', 'Chittoor', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1499, '517002', 'Chittoor', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1500, '517004', 'Chittoor', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1501, '517501', 'Tirupati', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1502, '517502', 'Tirupati', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1503, '517503', 'Tirupati', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1504, '517505', 'Tirupati', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1505, '517506', 'Tirupati', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1506, '517507', 'Tirupati', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1507, '517520', 'Tirupati', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1508, '517526', 'Tirupati', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1509, '518001', 'Kurnool', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1510, '518002', 'Kurnool', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1511, '518003', 'Kurnool', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1512, '518004', 'Kurnool', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1513, '518005', 'Kurnool', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1514, '518007', 'Kurnool', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1515, '520001', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1516, '520002', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1517, '520003', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1518, '520004', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1519, '520007', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1520, '520008', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1521, '520009', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1522, '520010', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1523, '520011', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1524, '520012', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1525, '520013', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1526, '520015', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1527, '522001', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1528, '522002', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1529, '522003', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1530, '522004', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1531, '522005', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1532, '522006', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1533, '522007', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1534, '522009', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1535, '522016', 'Tadikonda', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1536, '522019', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1537, '522020', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1538, '522022', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1539, '522034', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1540, '522501', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1541, '522502', 'Vijayawada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1542, '522503', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1543, '522508', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1544, '522509', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1545, '522510', 'Guntur', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1546, '523001', 'Ongole', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1547, '523002', 'Ongole', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1548, '524001', 'Nellore', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1549, '524002', 'Nellore', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1550, '524003', 'Nellore', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1551, '524004', 'Nellore', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1552, '524005', 'Nellore', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1553, '530001', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1554, '530002', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1555, '530003', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1556, '530004', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1557, '530005', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1558, '530007', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1559, '530008', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1560, '530009', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1561, '530011', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1562, '530012', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1563, '530013', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1564, '530014', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1565, '530016', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1566, '530017', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1567, '530018', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1568, '530020', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1569, '530022', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1570, '530024', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1571, '530026', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1572, '530027', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1573, '530028', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1574, '530029', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1575, '530032', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1576, '530035', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1577, '530040', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1578, '530041', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1579, '530043', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1580, '530044', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1581, '530045', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1582, '530046', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1583, '530047', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1584, '530048', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1585, '530049', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1586, '530051', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1587, '530052', 'Bheemunipatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1588, '530053', 'Visakhapatnam', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1589, '533001', 'Kakinada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1590, '533002', 'Kakinada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1591, '533003', 'Kakinada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1592, '533004', 'Kakinada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1593, '533005', 'Kakinada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1594, '533006', 'Kakinada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1595, '533016', 'Kakinada', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1596, '533101', 'Rajahmundry', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1597, '533102', 'Rajahmundry', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1598, '533103', 'Rajahmundry', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1599, '533104', 'Rajahmundry', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1600, '533105', 'Rajahmundry', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1601, '533106', 'Rajahmundry', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1602, '533107', 'Rajahmundry', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1603, '533125', 'Rajahmundry', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1604, '533126', 'Rajahmundry', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1605, '534001', 'Eluru', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1606, '534002', 'Eluru', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1607, '534003', 'Eluru', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1608, '534004', 'Eluru', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1609, '534006', 'Eluru', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1610, '534007', 'Eluru', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1611, '534195', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1612, '534197', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1613, '534198', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1614, '534199', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1615, '534201', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1616, '534202', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1617, '534203', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1618, '534204', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(1619, '534206', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(1620, '534216', 'Tanuku', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1621, '534218', 'Tanuku', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1622, '534230', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1623, '534235', 'Kaikaluru', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(1624, '534237', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1625, '534238', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1626, '534239', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1627, '534244', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1628, '534245', 'Palakollu', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1629, '534247', 'Bhimavaram', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1630, '534250', 'Palakollu', 'Andhra Pradesh', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', '2'),
(1631, '670001', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1632, '670002', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1633, '670003', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1634, '670004', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1635, '670005', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1636, '670006', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1637, '670007', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1638, '670008', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1639, '670009', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1640, '670010', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1641, '670011', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1642, '670012', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1643, '670013', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1644, '670014', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1645, '670015', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1646, '670016', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1647, '670017', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1648, '670018', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1649, '670101', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1650, '670102', 'Kannur', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '2'),
(1651, '670103', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1652, '670104', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1653, '670105', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1654, '670106', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1655, '670107', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1656, '670141', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1657, '670142', 'KANNUR', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(1658, '670143', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1659, '670301', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1660, '670302', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1661, '670303', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1662, '670304', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1663, '670305', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1664, '670306', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1665, '670307', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1666, '670308', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1667, '670309', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1668, '670310', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1669, '670325', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1670, '670327', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1671, '670331', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1672, '670334', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1673, '670353', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1674, '670358', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1675, '670501', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1676, '670502', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1677, '670503', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1678, '670504', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1679, '670511', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '3'),
(1680, '670521', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1681, '670561', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1682, '670562', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1683, '670563', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1684, '670565', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1685, '670567', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1686, '670571', 'KANNUR', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1687, '670581', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1688, '670582', 'KANNUR', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1689, '670591', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1690, '670592', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1691, '670593', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1692, '670594', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1693, '670595', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1694, '670597', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1695, '670601', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1696, '670602', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1697, '670604', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1698, '670611', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1699, '670612', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1700, '670613', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1701, '670621', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1702, '670622', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1703, '670631', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1704, '670632', 'KANNUR', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1705, '670633', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1706, '670641', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1707, '670642', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1708, '670643', 'Kannur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1709, '670645', 'Wayanad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '3'),
(1710, '670646', 'WAYANAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '3'),
(1711, '670649', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1712, '670650', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1713, '670651', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1714, '670661', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1715, '670662', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1716, '670663', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1717, '670671', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1718, '670672', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1719, '670673', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1720, '670674', 'KANNUR', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1721, '670675', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1722, '670676', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1723, '670691', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1724, '670692', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1725, '670693', 'KANNUR', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1726, '670694', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1727, '670701', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1728, '670702', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1729, '670703', 'KANNUR', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1730, '670704', 'KANNUR', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1731, '670705', 'KANNUR', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1732, '670706', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1733, '670731', 'KANNUR', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1734, '670741', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1735, '671121', 'Kasaragod', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '3'),
(1736, '671122', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1737, '671123', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1738, '671124', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1739, '671310', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1740, '671311', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1741, '671312', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1742, '671313', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1743, '671314', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1744, '671315', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1745, '671316', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1746, '671317', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1747, '671318', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(1748, '671319', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1749, '671321', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1750, '671322', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1751, '671323', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1752, '671324', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1753, '671326', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1754, '671348', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1755, '671351', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1756, '671531', 'Kasaragod', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '3'),
(1757, '671532', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1758, '671533', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1759, '671541', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1760, '671542', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1761, '671543', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1762, '671551', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1763, '671552', 'Kasaragod', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1764, '673001', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1765, '673002', 'KOZHIKODE', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1766, '673003', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1767, '673004', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1768, '673005', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1769, '673006', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1770, '673007', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1771, '673008', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1772, '673009', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1773, '673010', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1774, '673011', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1775, '673012', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1776, '673013', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1777, '673014', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1778, '673015', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1779, '673016', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1780, '673017', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1781, '673018', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1782, '673019', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1783, '673020', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1784, '673021', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1785, '673024', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1786, '673025', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1787, '673026', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1788, '673027', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1789, '673028', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1790, '673029', 'KOZHIKODE', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1791, '673031', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1792, '673032', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1793, '673037', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1794, '673101', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1795, '673102', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1796, '673103', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1797, '673104', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1798, '673105', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1799, '673106', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1800, '673121', 'WAYANAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '3'),
(1801, '673122', 'Kozhikode', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1802, '673123', 'Kozhikode', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1803, '673301', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1804, '673302', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1805, '673303', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1806, '673304', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1807, '673305', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1808, '673306', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1809, '673307', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1810, '673308', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1811, '673309', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1812, '673310', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1813, '673311', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1814, '673312', 'KANNUR', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1815, '673313', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1816, '673314', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1817, '673315', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1818, '673316', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1819, '673317', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1820, '673323', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1821, '673328', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1822, '673501', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1823, '673502', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1824, '673503', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1825, '673504', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1826, '673505', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1827, '673506', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1828, '673507', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1829, '673508', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1830, '673509', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1831, '673510', 'Thamarassery', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(1832, '673511', 'Kuttiady', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(1833, '673513', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1834, '673516', 'Kuttiady', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(1835, '673517', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1836, '673521', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1837, '673522', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1838, '673523', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1839, '673524', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1840, '673525', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1841, '673526', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1842, '673527', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1843, '673528', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1844, '673529', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1845, '673531', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1846, '673541', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1847, '673542', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1848, '673570', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1849, '673571', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1850, '673572', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1851, '673573', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1852, '673574', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1853, '673575', 'Kozhikode', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1854, '673576', 'Kozhikode', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1855, '673577', 'Kozhikode', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1856, '673579', 'Wayanad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '3'),
(1857, '673580', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1858, '673581', 'Wayanad', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(1859, '673582', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1860, '673585', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1861, '673586', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1862, '673591', 'Wayanad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '3'),
(1863, '673592', 'Wayanad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '3'),
(1864, '673593', 'Kozhikode', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1865, '673595', 'Wayanad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1866, '673596', 'Kozhikode', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1867, '673601', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1868, '673602', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1869, '673603', 'Kozhikode', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1870, '673604', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1871, '673611', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1872, '673612', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1873, '673613', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1874, '673614', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1875, '673615', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1876, '673616', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1877, '673620', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1878, '673631', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1');
INSERT INTO `velocity_pincode` (`id`, `pincode`, `area`, `state`, `parcel`, `ecommerce`, `cod`, `pickup`, `oda`, `air_ndd`, `surface_ndd`, `tat_hub`) VALUES
(1879, '673632', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1880, '673633', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1881, '673634', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1882, '673635', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1883, '673636', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1884, '673637', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1885, '673638', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1886, '673639', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1887, '673640', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1888, '673641', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1889, '673642', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1890, '673645', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1891, '673647', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1892, '673655', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1893, '673661', 'Kozhikode', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1894, '676101', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1895, '676102', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1896, '676103', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1897, '676104', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1898, '676105', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1899, '676106', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1900, '676107', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1901, '676108', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1902, '676109', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1903, '676121', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1904, '676122', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1905, '676123', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1906, '676301', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1907, '676302', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1908, '676303', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1909, '676304', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1910, '676305', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1911, '676306', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1912, '676307', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1913, '676309', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1914, '676311', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1915, '676312', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1916, '676317', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1917, '676319', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1918, '676320', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1919, '676501', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1920, '676502', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1921, '676503', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1922, '676504', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1923, '676505', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1924, '676506', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1925, '676507', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1926, '676508', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1927, '676509', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1928, '676510', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1929, '676517', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1930, '676519', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1931, '676521', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1932, '676522', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1933, '676523', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1934, '676525', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1935, '676528', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1936, '676541', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1937, '676542', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1938, '676551', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1939, '676552', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1940, '676553', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1941, '676557', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1942, '676561', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1943, '676562', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1944, '678001', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1945, '678002', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1946, '678003', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1947, '678004', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1948, '678005', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1949, '678006', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1950, '678007', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1951, '678008', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1952, '678009', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1953, '678010', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1954, '678011', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1955, '678012', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1956, '678013', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(1957, '678014', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(1958, '678101', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1959, '678102', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1960, '678103', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1961, '678104', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1962, '678501', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1963, '678502', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1964, '678503', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1965, '678504', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1966, '678505', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1967, '678506', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1968, '678507', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1969, '678508', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1970, '678510', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1971, '678512', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1972, '678531', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1973, '678532', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1974, '678533', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1975, '678534', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1976, '678541', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1977, '678542', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1978, '678543', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1979, '678544', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1980, '678545', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1981, '678546', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1982, '678551', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1983, '678552', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1984, '678553', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1985, '678554', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1986, '678555', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1987, '678556', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1988, '678557', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1989, '678571', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1990, '678572', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1991, '678573', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1992, '678574', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1993, '678581', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(1994, '678582', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1995, '678583', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1996, '678591', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1997, '678592', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(1998, '678593', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(1999, '678594', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2000, '678595', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2001, '678596', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2002, '678597', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2003, '678598', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2004, '678601', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2005, '678611', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2006, '678612', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2007, '678613', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2008, '678621', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2009, '678622', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2010, '678623', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2011, '678624', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2012, '678631', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2013, '678632', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2014, '678633', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2015, '678641', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2016, '678642', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2017, '678651', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2018, '678661', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2019, '678671', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2020, '678681', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2021, '678682', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2022, '678683', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2023, '678684', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2024, '678685', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2025, '678686', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2026, '678687', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2027, '678688', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2028, '678701', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2029, '678702', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2030, '678703', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2031, '678704', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2032, '678705', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2033, '678706', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2034, '678721', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2035, '678722', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2036, '678731', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2037, '678732', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2038, '678762', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2039, '679101', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2040, '679102', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2041, '679103', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2042, '679104', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2043, '679105', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2044, '679106', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2045, '679121', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2046, '679122', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2047, '679123', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2048, '679301', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2049, '679302', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2050, '679303', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2051, '679304', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2052, '679305', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2053, '679306', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2054, '679307', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2055, '679308', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2056, '679309', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2057, '679313', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2058, '679321', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2059, '679322', 'Malappuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2060, '679323', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2061, '679324', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2062, '679325', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2063, '679326', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2064, '679327', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2065, '679328', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2066, '679329', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2067, '679330', 'MALAPPURAM', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2068, '679331', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2069, '679332', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2070, '679333', 'MALAPPURAM', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2071, '679334', 'MALAPPURAM', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2072, '679335', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2073, '679336', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2074, '679337', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2075, '679338', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2076, '679339', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2077, '679340', 'MALAPPURAM', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2078, '679341', 'MALAPPURAM', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2079, '679357', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2080, '679501', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2081, '679502', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2082, '679503', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2083, '679504', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2084, '679505', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2085, '679506', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2086, '679511', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2087, '679512', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2088, '679513', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2089, '679514', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2090, '679515', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2091, '679516', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2092, '679521', 'PALAKKAD', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2093, '679522', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2094, '679523', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2095, '679528', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2096, '679531', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2097, '679532', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2098, '679533', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2099, '679534', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2100, '679535', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2101, '679536', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2102, '679551', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2103, '679552', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2104, '679553', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2105, '679554', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2106, '679561', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2107, '679562', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2108, '679563', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2109, '679564', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2110, '679571', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2111, '679572', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2112, '679573', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2113, '679574', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2114, '679575', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2115, '679576', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2116, '679577', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2117, '679578', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2118, '679579', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2119, '679580', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2120, '679581', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2121, '679582', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2122, '679583', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2123, '679584', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2124, '679585', 'Palakkad', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2125, '679586', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2126, '679587', 'Palakkad', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2127, '679591', 'MALAPPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2128, '680001', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2129, '680002', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2130, '680003', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2131, '680004', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2132, '680005', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2133, '680006', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2134, '680007', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2135, '680008', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2136, '680009', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2137, '680010', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2138, '680011', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2139, '680012', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2140, '680013', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2141, '680014', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2142, '680018', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2143, '680020', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2144, '680021', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2145, '680022', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2146, '680026', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2147, '680027', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2148, '680028', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2149, '680101', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2150, '680102', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2151, '680103', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2152, '680104', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2153, '680121', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2154, '680122', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2155, '680123', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2156, '680125', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2157, '680301', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2158, '680302', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2159, '680303', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2160, '680304', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2161, '680305', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2162, '680306', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2163, '680307', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2164, '680308', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2165, '680309', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2166, '680310', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2167, '680311', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2168, '680312', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2169, '680317', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2170, '680501', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2171, '680502', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2172, '680503', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2173, '680504', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2174, '680505', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2175, '680506', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2176, '680507', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2177, '680508', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2178, '680509', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2179, '680510', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2180, '680511', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2181, '680512', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2182, '680513', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2183, '680514', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2184, '680515', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2185, '680516', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2186, '680517', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2187, '680518', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2188, '680519', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2189, '680520', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2190, '680521', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2191, '680522', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2192, '680523', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2193, '680524', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2194, '680541', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2195, '680542', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2196, '680543', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2197, '680544', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2198, '680545', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2199, '680546', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2200, '680551', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2201, '680552', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2202, '680553', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2203, '680555', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2204, '680561', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2205, '680562', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2206, '680563', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2207, '680564', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2208, '680565', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2209, '680566', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2210, '680567', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2211, '680568', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2212, '680569', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2213, '680570', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2214, '680571', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2215, '680581', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2216, '680582', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2217, '680583', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2218, '680584', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2219, '680585', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2220, '680586', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2221, '680587', 'Thrissur', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2222, '680588', 'Thrissur', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2223, '680589', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2224, '680590', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2225, '680591', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2226, '680594', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2227, '680596', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2228, '680601', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2229, '680602', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2230, '680604', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2231, '680611', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2232, '680612', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2233, '680613', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2234, '680614', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2235, '680615', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2236, '680616', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2237, '680617', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2238, '680618', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2239, '680619', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2240, '680620', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2241, '680623', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2242, '680631', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2243, '680641', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2244, '680642', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2245, '680651', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2246, '680652', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2247, '680653', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2248, '680654', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2249, '680655', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2250, '680656', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2251, '680661', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2252, '680662', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2253, '680663', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2254, '680664', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2255, '680665', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2256, '680666', 'Thrissur', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2257, '680667', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2258, '680668', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2259, '680669', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2260, '680670', 'Thrissur', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2261, '680671', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2262, '680681', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2263, '680682', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2264, '680683', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2265, '680684', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2266, '680685', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2267, '680686', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2268, '680687', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2269, '680688', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2270, '680689', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2271, '680691', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2272, '680697', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2273, '680699', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2274, '680701', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2275, '680702', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2276, '680703', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2277, '680711', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2278, '680712', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2279, '680721', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2280, '680722', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2281, '680724', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2282, '680731', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2283, '680732', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2284, '680733', 'Thrissur', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2285, '680734', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2286, '680741', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2287, '680751', 'Thrissur', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2288, '682001', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2289, '682002', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2290, '682003', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2291, '682004', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2292, '682005', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2293, '682006', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2294, '682007', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2295, '682008', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2296, '682009', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2297, '682010', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2298, '682011', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2299, '682012', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2300, '682013', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2301, '682014', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2302, '682015', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2303, '682016', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2304, '682017', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2305, '682018', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2306, '682019', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2307, '682020', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2308, '682021', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2309, '682022', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2310, '682023', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2311, '682024', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2312, '682025', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2313, '682026', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2314, '682027', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2315, '682028', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2316, '682029', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2317, '682030', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2318, '682031', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2319, '682032', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2320, '682033', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2321, '682034', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2322, '682035', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2323, '682036', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2324, '682037', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2325, '682038', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2326, '682039', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2327, '682040', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2328, '682041', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2329, '682042', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2330, '682301', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2331, '682302', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2332, '682303', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2333, '682304', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2334, '682305', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2335, '682306', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2336, '682307', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2337, '682308', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2338, '682309', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2339, '682310', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2340, '682311', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2341, '682312', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2342, '682313', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2343, '682314', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2344, '682315', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2345, '682316', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2346, '682317', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2347, '682501', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2348, '682502', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2349, '682503', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2350, '682504', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2351, '682505', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2352, '682506', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2353, '682507', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2354, '682508', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2355, '682509', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2356, '682511', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2357, '682552', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2358, '683101', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2359, '683102', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2360, '683103', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2361, '683104', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2362, '683105', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2363, '683106', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2364, '683108', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2365, '683109', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2366, '683110', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2367, '683111', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2368, '683112', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2369, '683501', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2370, '683502', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2371, '683503', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2372, '683506', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2373, '683511', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2374, '683512', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2375, '683513', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2376, '683514', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2377, '683515', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2378, '683516', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2379, '683517', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2380, '683518', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2381, '683519', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '1'),
(2382, '683520', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '1'),
(2383, '683521', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2384, '683522', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2385, '683541', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2386, '683542', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2387, '683543', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2388, '683544', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2389, '683545', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2390, '683546', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2391, '683547', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2392, '683548', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2393, '683549', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2394, '683550', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2395, '683556', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2396, '683561', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2397, '683562', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2398, '683563', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2399, '683564', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2400, '683565', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2401, '683571', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '1'),
(2402, '683572', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2403, '683573', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2404, '683574', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2405, '683575', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2406, '683576', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2407, '683577', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2408, '683578', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2409, '683579', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2410, '683580', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2411, '683581', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2412, '683585', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2413, '683587', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2414, '683589', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2415, '683594', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2416, '685501', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2417, '685503', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2418, '685505', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2419, '685507', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2420, '685508', 'Idukki', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '3'),
(2421, '685509', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2422, '685511', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2423, '685512', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2424, '685514', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2425, '685515', 'IDUKKI', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2426, '685531', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2427, '685532', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2428, '685533', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2429, '685535', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2430, '685551', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2431, '685552', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2432, '685553', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2433, '685554', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2434, '685561', 'Idukki', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '3'),
(2435, '685562', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2436, '685563', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2437, '685565', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2438, '685566', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2439, '685571', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2440, '685581', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2441, '685582', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2442, '685583', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2443, '685584', 'Idukki', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2444, '685585', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2445, '685586', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2446, '685587', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2447, '685588', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2448, '685589', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2449, '685590', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2450, '685591', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2451, '685595', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2452, '685601', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2453, '685602', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2454, '685603', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2455, '685604', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2456, '685605', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2457, '685606', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2458, '685607', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2459, '685608', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2460, '685609', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2461, '685612', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2462, '685613', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2463, '685614', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2464, '685615', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2465, '685616', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2466, '685618', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2467, '685619', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2468, '685620', 'Idukki', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2469, '686001', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2470, '686002', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2471, '686003', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2472, '686004', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2473, '686005', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2474, '686006', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2475, '686007', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2476, '686008', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2477, '686009', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2478, '686010', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2479, '686011', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2480, '686012', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2481, '686013', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2482, '686014', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2483, '686015', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2484, '686016', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2485, '686017', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2486, '686018', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2487, '686019', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2488, '686020', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2489, '686021', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2490, '686022', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2491, '686031', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2492, '686038', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2493, '686039', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2494, '686041', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2495, '686101', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2496, '686102', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '1'),
(2497, '686103', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2498, '686104', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2499, '686105', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2500, '686106', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2501, '686121', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2502, '686122', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2503, '686123', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2504, '686141', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2505, '686143', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2506, '686144', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2507, '686146', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2508, '686501', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2509, '686502', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2510, '686503', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2511, '686504', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2512, '686505', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2513, '686506', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2514, '686507', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2515, '686508', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2516, '686509', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2517, '686510', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2518, '686511', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2519, '686512', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2520, '686513', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2521, '686514', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2522, '686515', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2523, '686516', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2524, '686517', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2525, '686518', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3');
INSERT INTO `velocity_pincode` (`id`, `pincode`, `area`, `state`, `parcel`, `ecommerce`, `cod`, `pickup`, `oda`, `air_ndd`, `surface_ndd`, `tat_hub`) VALUES
(2526, '686519', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2527, '686520', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2528, '686521', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2529, '686522', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2530, '686531', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2531, '686532', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '1'),
(2532, '686533', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2533, '686534', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2534, '686535', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2535, '686536', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2536, '686537', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2537, '686538', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2538, '686539', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2539, '686540', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2540, '686541', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2541, '686542', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2542, '686543', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2543, '686544', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2544, '686545', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2545, '686546', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2546, '686547', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2547, '686548', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2548, '686555', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2549, '686560', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2550, '686561', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2551, '686562', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2552, '686563', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2553, '686564', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2554, '686571', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2555, '686572', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2556, '686573', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2557, '686574', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2558, '686575', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2559, '686576', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2560, '686577', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2561, '686578', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2562, '686579', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2563, '686580', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2564, '686581', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2565, '686582', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2566, '686583', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2567, '686584', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2568, '686585', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2569, '686586', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2570, '686587', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2571, '686601', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2572, '686602', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2573, '686603', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2574, '686604', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2575, '686605', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2576, '686606', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2577, '686607', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2578, '686608', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2579, '686609', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2580, '686610', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2581, '686611', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2582, '686612', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2583, '686613', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2584, '686616', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2585, '686630', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2586, '686631', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2587, '686632', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2588, '686633', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2589, '686634', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2590, '686635', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2591, '686636', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2592, '686637', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2593, '686651', 'Kottayam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2594, '686652', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2595, '686653', 'Kottayam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2596, '686661', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2597, '686662', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2598, '686663', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2599, '686664', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2600, '686665', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2601, '686666', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2602, '686667', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2603, '686668', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2604, '686669', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2605, '686670', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2606, '686671', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2607, '686672', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2608, '686673', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2609, '686681', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2610, '686691', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2611, '686692', 'Ernakulam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2612, '686693', 'Ernakulam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2613, '688001', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2614, '688002', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2615, '688003', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2616, '688004', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2617, '688005', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2618, '688006', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2619, '688007', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2620, '688008', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2621, '688009', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2622, '688011', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2623, '688012', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2624, '688013', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2625, '688014', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2626, '688501', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '1'),
(2627, '688502', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2628, '688503', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '1'),
(2629, '688504', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2630, '688505', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '1'),
(2631, '688506', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2632, '688521', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2633, '688522', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2634, '688523', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2635, '688524', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2636, '688525', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2637, '688526', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2638, '688527', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2639, '688528', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2640, '688529', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2641, '688530', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2642, '688531', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2643, '688532', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2644, '688533', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2645, '688534', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2646, '688535', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2647, '688536', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2648, '688537', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2649, '688538', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2650, '688539', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2651, '688540', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2652, '688541', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2653, '688547', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2654, '688549', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2655, '688555', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2656, '688561', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2657, '688562', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '1'),
(2658, '688570', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2659, '688582', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2660, '689101', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2661, '689102', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2662, '689103', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2663, '689104', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2664, '689105', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2665, '689106', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2666, '689107', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2667, '689108', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2668, '689109', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2669, '689110', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2670, '689111', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2671, '689112', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2672, '689113', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2673, '689115', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2674, '689121', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2675, '689122', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2676, '689123', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2677, '689124', 'Pathanamthitta', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2678, '689126', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2679, '689501', 'Pathanamthitta', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2680, '689502', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2681, '689503', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2682, '689504', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2683, '689505', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2684, '689506', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2685, '689507', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2686, '689508', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2687, '689509', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2688, '689510', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2689, '689511', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2690, '689512', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2691, '689513', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2692, '689514', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2693, '689520', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2694, '689521', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2695, '689531', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2696, '689532', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2697, '689533', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2698, '689541', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2699, '689542', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2700, '689543', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2701, '689544', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2702, '689545', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2703, '689546', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2704, '689547', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2705, '689548', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2706, '689549', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2707, '689550', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2708, '689551', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2709, '689571', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2710, '689572', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2711, '689573', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2712, '689574', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2713, '689581', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2714, '689582', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2715, '689583', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2716, '689584', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2717, '689585', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2718, '689586', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2719, '689587', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2720, '689588', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2721, '689589', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2722, '689590', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2723, '689591', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2724, '689592', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2725, '689594', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2726, '689595', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '1'),
(2727, '689597', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2728, '689602', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2729, '689611', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2730, '689612', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2731, '689613', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2732, '689614', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2733, '689615', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2734, '689621', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2735, '689622', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2736, '689623', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2737, '689624', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2738, '689625', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2739, '689626', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2740, '689627', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2741, '689641', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2742, '689642', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2743, '689643', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2744, '689644', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2745, '689645', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2746, '689646', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2747, '689647', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2748, '689648', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2749, '689649', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2750, '689650', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2751, '689652', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2752, '689653', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2753, '689654', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2754, '689656', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2755, '689661', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2756, '689662', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2757, '689663', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2758, '689664', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2759, '689666', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2760, '689667', 'Pathanamthitta', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2761, '689668', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2762, '689671', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2763, '689672', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2764, '689673', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2765, '689674', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2766, '689675', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2767, '689676', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2768, '689677', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2769, '689678', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2770, '689691', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2771, '689692', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2772, '689693', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2773, '689694', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2774, '689695', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2775, '689696', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2776, '689698', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2777, '689699', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2778, '689711', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2779, '690101', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2780, '690102', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2781, '690103', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2782, '690104', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2783, '690105', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2784, '690106', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2785, '690107', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2786, '690108', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2787, '690110', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2788, '690501', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2789, '690502', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2790, '690503', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2791, '690504', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2792, '690505', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2793, '690506', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2794, '690507', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2795, '690508', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2796, '690509', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2797, '690510', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2798, '690511', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2799, '690512', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2800, '690513', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2801, '690514', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2802, '690515', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2803, '690516', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2804, '690517', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2805, '690518', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2806, '690519', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2807, '690520', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2808, '690521', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2809, '690522', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2810, '690523', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2811, '690524', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2812, '690525', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2813, '690526', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2814, '690527', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2815, '690528', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2816, '690529', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2817, '690530', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2818, '690531', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2819, '690532', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2820, '690533', 'Alappuzha', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2821, '690534', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2822, '690535', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2823, '690536', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2824, '690537', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2825, '690538', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2826, '690539', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2827, '690540', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2828, '690542', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2829, '690544', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2830, '690546', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2831, '690547', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2832, '690548', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2833, '690558', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2834, '690559', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2835, '690561', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2836, '690571', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2837, '690572', 'Alappuzha', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2838, '690573', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2839, '690574', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2840, '691001', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2841, '691002', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2842, '691003', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2843, '691004', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2844, '691005', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2845, '691006', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2846, '691007', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2847, '691008', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2848, '691009', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2849, '691010', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2850, '691011', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2851, '691012', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2852, '691013', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2853, '691014', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2854, '691015', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2855, '691016', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2856, '691019', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2857, '691020', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2858, '691021', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2859, '691301', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2860, '691302', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '1'),
(2861, '691303', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', 'Y', '1'),
(2862, '691304', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2863, '691305', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2864, '691306', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2865, '691307', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2866, '691308', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2867, '691309', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2868, '691310', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2869, '691311', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2870, '691312', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2871, '691319', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2872, '691322', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2873, '691331', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2874, '691332', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2875, '691333', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2876, '691334', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2877, '691471', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', '', '3'),
(2878, '691500', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2879, '691501', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2880, '691502', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2881, '691503', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2882, '691504', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2883, '691505', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', 'Y', '1'),
(2884, '691506', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2885, '691507', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2886, '691508', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2887, '691509', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2888, '691510', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2889, '691511', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2890, '691512', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', 'Y', '1'),
(2891, '691515', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2892, '691516', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2893, '691520', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '2'),
(2894, '691521', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2895, '691522', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2896, '691523', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2897, '691524', 'Pathanamthitta', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2898, '691525', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2899, '691526', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2900, '691530', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2901, '691531', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2902, '691532', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2903, '691533', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2904, '691534', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2905, '691535', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2906, '691536', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2907, '691537', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2908, '691538', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2909, '691540', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2910, '691541', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2911, '691543', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2912, '691551', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2913, '691552', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2914, '691553', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2915, '691554', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2916, '691555', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2917, '691556', 'Pathanamthitta', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2918, '691557', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2919, '691559', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', '', '3'),
(2920, '691560', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2921, '691566', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2922, '691571', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2923, '691572', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2924, '691573', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2925, '691574', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2926, '691576', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2927, '691577', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2928, '691578', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2929, '691579', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2930, '691580', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2931, '691581', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2932, '691582', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2933, '691583', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'Y', '', 'Y', '1'),
(2934, '691584', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2935, '691585', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2936, '691589', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2937, '691590', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2938, '691601', 'Kollam', 'Kerala', 'Y', 'N', 'N', 'Y', 'N', '', 'Y', '1'),
(2939, '691602', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2940, '695001', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2941, '695002', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2942, '695003', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2943, '695004', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2944, '695005', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2945, '695006', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2946, '695007', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2947, '695008', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2948, '695009', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2949, '695010', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2950, '695011', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2951, '695012', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2952, '695013', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2953, '695014', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2954, '695015', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2955, '695016', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2956, '695017', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2957, '695018', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2958, '695019', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2959, '695020', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2960, '695021', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2961, '695022', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2962, '695023', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2963, '695024', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2964, '695025', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2965, '695026', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2966, '695027', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2967, '695028', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2968, '695029', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2969, '695030', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2970, '695031', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2971, '695032', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2972, '695033', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2973, '695034', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2974, '695035', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2975, '695036', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2976, '695038', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2977, '695039', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2978, '695040', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2979, '695042', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2980, '695043', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2981, '695099', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2982, '695101', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2983, '695102', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2984, '695103', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2985, '695104', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2986, '695121', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(2987, '695122', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2988, '695123', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2989, '695124', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(2990, '695125', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2991, '695126', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(2992, '695132', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2993, '695133', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2994, '695134', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(2995, '695141', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(2996, '695142', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2997, '695143', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2998, '695144', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(2999, '695145', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(3000, '695146', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3001, '695301', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3002, '695302', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3003, '695303', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3004, '695304', 'THIRUVANANTHAPURAM', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3005, '695305', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3006, '695306', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3007, '695307', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3008, '695308', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3009, '695309', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3010, '695310', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3011, '695311', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3012, '695312', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3013, '695313', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(3014, '695315', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3015, '695316', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3016, '695317', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3017, '695318', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3018, '695501', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3019, '695502', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3020, '695503', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(3021, '695504', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', '', '2'),
(3022, '695505', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(3023, '695506', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(3024, '695507', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3025, '695508', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(3026, '695512', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3027, '695513', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', '', 'Y', '1'),
(3028, '695521', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3029, '695522', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3030, '695523', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3031, '695524', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3032, '695525', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3033, '695526', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3034, '695527', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3035, '695528', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3036, '695541', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3037, '695542', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(3038, '695543', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3039, '695547', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3040, '695551', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3041, '695561', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3042, '695562', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(3043, '695563', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3044, '695564', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3045, '695568', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3046, '695571', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3047, '695572', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3048, '695573', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3049, '695574', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(3050, '695575', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3051, '695581', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3052, '695582', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3053, '695583', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3054, '695584', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3055, '695585', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3056, '695586', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3057, '695587', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3058, '695588', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3059, '695589', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3060, '695601', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3061, '695602', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3062, '695603', 'Kollam', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3063, '695604', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3064, '695605', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3065, '695606', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3066, '695607', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3067, '695608', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3068, '695609', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3069, '695610', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(3070, '695611', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3071, '695612', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', '', '2'),
(3072, '695614', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3073, '695615', 'Thiruvananthapuram', 'Kerala', 'Y', 'Y', 'Y', 'Y', 'Y', '', 'Y', '1'),
(3074, '700001', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3075, '700002', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3076, '700003', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3077, '700004', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3078, '700005', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3079, '700006', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3080, '700007', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3081, '700008', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3082, '700009', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3083, '700010', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3084, '700011', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3085, '700012', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3086, '700013', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3087, '700014', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3088, '700015', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3089, '700016', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3090, '700017', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3091, '700018', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3092, '700019', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3093, '700020', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3094, '700021', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3095, '700022', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3096, '700023', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3097, '700024', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3098, '700025', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3099, '700026', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3100, '700027', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3101, '700028', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3102, '700029', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3103, '700030', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3104, '700031', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3105, '700032', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3106, '700033', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3107, '700034', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3108, '700035', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3109, '700036', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3110, '700037', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3111, '700038', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3112, '700039', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3113, '700040', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3114, '700041', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3115, '700042', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3116, '700043', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3117, '700044', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3118, '700045', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3119, '700046', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3120, '700047', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3121, '700048', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3122, '700049', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3123, '700050', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3124, '700051', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3125, '700052', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3126, '700053', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3127, '700054', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3128, '700055', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3129, '700056', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3130, '700057', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3131, '700058', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3132, '700059', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3133, '700060', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3134, '700061', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3135, '700062', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3136, '700063', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3137, '700064', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3138, '700065', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3139, '700066', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3140, '700067', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3141, '700068', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3142, '700069', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3143, '700070', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3144, '700071', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3145, '700072', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3146, '700073', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1');
INSERT INTO `velocity_pincode` (`id`, `pincode`, `area`, `state`, `parcel`, `ecommerce`, `cod`, `pickup`, `oda`, `air_ndd`, `surface_ndd`, `tat_hub`) VALUES
(3147, '700074', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3148, '700075', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3149, '700076', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3150, '700077', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3151, '700078', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3152, '700079', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3153, '700080', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3154, '700081', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3155, '700082', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3156, '700083', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3157, '700084', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3158, '700085', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3159, '700086', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3160, '700087', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3161, '700088', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3162, '700089', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3163, '700090', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3164, '700091', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3165, '700092', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3166, '700093', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3167, '700094', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3168, '700095', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3169, '700096', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3170, '700097', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3171, '700098', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3172, '700099', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3173, '700100', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3174, '700101', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3175, '700102', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3176, '700103', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3177, '700104', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3178, '700105', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3179, '700106', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3180, '700107', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3181, '700108', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3182, '700109', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3183, '700110', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3184, '700111', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3185, '700112', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3186, '700113', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3187, '700114', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3188, '700115', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3189, '700116', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3190, '700117', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3191, '700118', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3192, '700119', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3193, '700120', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3194, '700121', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3195, '700122', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3196, '700123', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3197, '700124', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3198, '700125', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3199, '700126', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3200, '700127', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3201, '700128', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3202, '700129', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3203, '700130', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3204, '700131', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3205, '700132', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3206, '700133', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3207, '700134', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'Y', '1'),
(3208, '700135', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'Y', '1'),
(3209, '700136', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'Y', '1'),
(3210, '700137', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3211, '700138', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3212, '700139', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3213, '700140', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3214, '700141', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3215, '700142', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3216, '700143', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3217, '700144', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3218, '700145', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3219, '700146', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3220, '700147', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3221, '700148', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3222, '700149', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3223, '700150', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3224, '700151', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3225, '700152', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3226, '700153', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3227, '700154', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3228, '700155', 'Amdanga', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3229, '700156', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'Y', '1'),
(3230, '700157', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'Y', '1'),
(3231, '700158', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'Y', '1'),
(3232, '700159', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'Y', '1'),
(3233, '700160', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'Y', '1'),
(3234, '700161', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'Y', '1'),
(3235, '711101', 'Domjur', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3236, '711102', 'Bally Jagachha', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3237, '711103', 'Sankrail', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3238, '711104', 'Domjur', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3239, '711105', 'Domjur', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3240, '711106', 'Bally Jagachha', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3241, '711107', 'Uluberia - I', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3242, '711108', 'Uluberia - I', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3243, '711109', 'Bally Jagachha', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3244, '711110', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3245, '711111', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3246, '711112', 'Bally Jagachha', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3247, '711113', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3248, '711114', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3249, '711115', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3250, '711201', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3251, '711202', 'BELUR BAZAR', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3252, '711203', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3253, '711204', 'LILUAH', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3254, '711205', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3255, '711227', 'ANANDANAGAR', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3256, '711302', 'Howrah', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '1'),
(3257, '711403', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3258, '711405', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3259, '711409', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3260, '711411', 'Howrah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3261, '712101', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3262, '712102', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3263, '712103', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3264, '712104', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3265, '712105', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3266, '712121', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3267, '712124', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3268, '712125', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3269, '712136', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3270, '712137', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3271, '712138', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3272, '712139', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3273, '712201', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3274, '712202', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3275, '712203', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3276, '712204', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3277, '712221', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3278, '712222', 'Singur', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3279, '712223', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3280, '712232', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3281, '712233', 'HINDMOTOR', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3282, '712235', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3283, '712245', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3284, '712246', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3285, '712248', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3286, '712249', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3287, '712250', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3288, '712258', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3289, '712306', 'Serampur Uttarpara', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3290, '712310', 'DANKUNI', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3291, '712311', 'DANKUNI', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3292, '712502', '?Chinsurah', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3293, '734001', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3294, '734002', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3295, '734003', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3296, '734004', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3297, '734005', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3298, '734006', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3299, '734007', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3300, '734401', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3301, '734403', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3302, '734404', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3303, '734405', 'SILIGURI-', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3304, '741201', 'Ranaghat - I', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3305, '741223', 'Kalyani', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3306, '741232', 'Ranaghat - I', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3307, '741255', 'Ranaghat - I', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3308, '741402', 'Ranaghat - I', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3309, '741404', 'Santipur', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3310, '741501', 'Ranaghat - II', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3311, '743122', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3312, '743125', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3313, '743126', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3314, '743127', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3315, '743128', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3316, '743133', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3317, '743134', 'Kalyani', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3318, '743135', 'Naihati', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3319, '743144', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3320, '743145', 'Kalyani', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3321, '743165', 'Naihati', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3322, '743166', 'Naihati', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3323, '743193', 'Kalyani', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3324, '743221', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3325, '743222', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '1'),
(3326, '743232', 'Helencha', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3327, '743234', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '1'),
(3328, '743235', 'Bongaon', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '1'),
(3329, '743245', 'Bongaon', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '1'),
(3330, '743247', 'Swarupnagar', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3331, '743248', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3332, '743251', 'Helencha', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3333, '743262', 'Bongaon', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3334, '743263', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '1'),
(3335, '743268', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '1'),
(3336, '743270', 'Helencha', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3337, '743271', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '1'),
(3338, '743273', 'Swarupnagar', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3339, '743286', 'Swarupnagar', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3340, '743287', 'Joypul', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3341, '743289', 'Joypul', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3342, '743290', 'Kalyani', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3343, '743293', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3344, '743294', 'Kolkata', 'West Bengal', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3345, '743297', 'Helencha', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3346, '743401', 'Basirhat', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3347, '743405', 'Bongaon', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3348, '743411', 'Basirhat', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3349, '743412', 'Basirhat', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3350, '743423', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '1'),
(3351, '743424', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3352, '743425', 'Taki', 'West Bengal', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '1'),
(3353, '743426', 'Taki', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3354, '743427', 'Basirhat', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3355, '743428', 'Basirhat', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3356, '743437', 'Kolkata', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3357, '743438', 'Swarupnagar', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3358, '743442', 'Taki', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3359, '743456', 'Taki', 'West Bengal', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', '1'),
(3360, '751001', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3361, '751002', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3362, '751003', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3363, '751004', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3364, '751005', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3365, '751006', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3366, '751007', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3367, '751008', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3368, '751009', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3369, '751010', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3370, '751011', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3371, '751012', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3372, '751013', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3373, '751014', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3374, '751015', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3375, '751016', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3376, '751017', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3377, '751018', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3378, '751019', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3379, '751020', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3380, '751021', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3381, '751022', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3382, '751023', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3383, '751024', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3384, '751025', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3385, '751026', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3386, '751027', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3387, '751028', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3388, '751029', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', '1'),
(3389, '751030', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3390, '751031', 'Bhubaneshwar', 'Odisha', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3391, '781001', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3392, '781003', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3393, '781004', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3394, '781005', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3395, '781006', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3396, '781007', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3397, '781008', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3398, '781009', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3399, '781010', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', '1'),
(3400, '781011', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3401, '781012', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3402, '781013', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3403, '781014', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3404, '781015', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3405, '781016', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3406, '781017', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3407, '781018', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3408, '781019', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3409, '781020', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3410, '781021', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3411, '781022', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3412, '781024', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3413, '781025', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3414, '781026', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3415, '781027', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3416, '781028', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3417, '781029', 'Guwahati', 'Assam', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2'),
(3418, '781032', 'Guwahati', 'Assam', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '2'),
(3419, '781034', 'Guwahati', 'Assam', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', '2'),
(3420, '800001', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3421, '800003', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3422, '800004', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3423, '800005', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3424, '800006', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3425, '800007', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3426, '800008', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3427, '800009', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3428, '800010', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3429, '800011', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3430, '800012', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3431, '800013', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3432, '800014', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3433, '800015', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3434, '800016', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3435, '800017', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3436, '800018', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3437, '800019', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3438, '800020', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3439, '800021', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3440, '800022', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3441, '800023', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3442, '800024', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3443, '800025', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3444, '800026', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3445, '800027', 'Patna', 'Bihar', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3446, '834001', 'Ranchi', 'Jharkhand', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3447, '834002', 'Ranchi', 'Jharkhand', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3448, '834003', 'Ranchi', 'Jharkhand', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3449, '834004', 'Ranchi', 'Jharkhand', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3450, '834005', 'Ranchi', 'Jharkhand', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3451, '834006', 'Ranchi', 'Jharkhand', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3452, '834007', 'Ranchi', 'Jharkhand', 'Y', 'Y', 'Y', 'Y', 'N', 'Y', 'Y', '1'),
(3453, '834008', 'Ranchi', 'Jharkhand', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3454, '834009', 'Ranchi', 'Jharkhand', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'Y', '1'),
(3455, '560001', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3456, '560002', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3457, '560003', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3458, '560004', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3459, '560005', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3460, '560007', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3461, '560008', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3462, '560010', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3463, '560011', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3464, '560012', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3465, '560013', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3466, '560015', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3467, '560016', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3468, '560017', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3469, '560018', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3470, '560019', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3471, '560020', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3472, '560021', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3473, '560022', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3474, '560023', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3475, '560024', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3476, '560025', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3477, '560026', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3478, '560027', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3479, '560028', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3480, '560029', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3481, '560032', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3482, '560033', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3483, '560034', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3484, '560035', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3485, '560036', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3486, '560037', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3487, '560038', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3488, '560039', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3489, '560040', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3490, '560042', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3491, '560043', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3492, '560045', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3493, '560046', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3494, '560047', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3495, '560048', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3496, '560049', 'Hoskote', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3497, '560050', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3498, '560051', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3499, '560052', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3500, '560053', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3501, '560054', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3502, '560055', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3503, '560056', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3504, '560057', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3505, '560058', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3506, '560059', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3507, '560060', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3508, '560061', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3509, '560062', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3510, '560064', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3511, '560065', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3512, '560066', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3513, '560067', 'Hoskote', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3514, '560068', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3515, '560069', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3516, '560070', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3517, '560071', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3518, '560072', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3519, '560073', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3520, '560075', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3521, '560076', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3522, '560077', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3523, '560078', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3524, '560079', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3525, '560080', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3526, '560084', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3527, '560085', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3528, '560086', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3529, '560087', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3530, '560090', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3531, '560091', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3532, '560092', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3533, '560093', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3534, '560094', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3535, '560095', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3536, '560096', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3537, '560097', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3538, '560098', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3539, '560099', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3540, '560100', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3541, '560102', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3542, '560103', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3543, '560104', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3544, '560105', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3545, '560108', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3546, '560109', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3547, '560110', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', ''),
(3548, '560111', 'Bangalore', 'Karnataka', 'Y', 'N', 'N', 'N', 'N', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_shipping_label`
--

CREATE TABLE `vendor_shipping_label` (
  `id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL COMMENT 'Vendor ID',
  `process_slot` varchar(150) DEFAULT NULL COMMENT 'Process slot number',
  `slot_no` varchar(150) NOT NULL COMMENT 'Shipping slot number (order_unique_id)',
  `ctype` enum('direct','bigship','') DEFAULT 'direct' COMMENT 'Courier type',
  `barcode_url` varchar(500) DEFAULT NULL COMMENT 'Relative path to barcode image',
  `barcode_awb_url` varchar(500) DEFAULT NULL COMMENT 'Relative path to AWB barcode image',
  `label_url` varchar(500) DEFAULT NULL COMMENT 'Relative path to shipping label PDF',
  `awb_number` varchar(100) DEFAULT NULL COMMENT 'AWB tracking number',
  `shipment_provider` varchar(100) DEFAULT NULL COMMENT 'Shipment provider name',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='Vendor shipping labels and barcodes';

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

--
-- Dumping data for table `vendor_site_settings`
--

INSERT INTO `vendor_site_settings` (`id`, `vendor_id`, `site_title`, `site_description`, `logo_path`, `favicon_path`, `primary_color`, `secondary_color`, `accent_color`, `header_bg_color`, `footer_bg_color`, `text_primary_color`, `text_secondary_color`, `link_color`, `link_hover_color`, `button_primary_bg`, `button_primary_text`, `button_secondary_bg`, `button_secondary_text`, `modal_bg_gradient_start`, `modal_bg_gradient_end`, `modal_button_bg`, `modal_button_text`, `since_text`, `custom_css`, `meta_title`, `meta_keywords`, `meta_description`, `is_active`, `created_at`, `updated_at`, `banner_image`) VALUES
(1, 31, 'My Online Store', 'Welcome to our online store', 'uploads/vendors_logos/logos/vendor_31_1768308033.png', NULL, '#116B31', '#ffffff', '#28a745', '#ffffff', '#f8f9fa', '#333333', '#666666', '#116B31', '#0d5a26', '#116B31', '#ffffff', '#6c757d', '#ffffff', '#116B31', '#28a745', '#ffffff', '#116B31', NULL, NULL, '', '', '', 1, '2026-01-13 12:24:57', '2026-01-13 12:40:33', NULL),
(2, 38, 'My Online Stores', 'Welcome to our online store', NULL, NULL, '#116B31', '#ffffff', '#28a745', '#ffffff', '#f8f9fa', '#333333', '#666666', '#116B31', '#0d5a26', '#116B31', '#ffffff', '#6c757d', '#ffffff', '#116B31', '#28a745', '#ffffff', '#116B31', NULL, NULL, '', '', '', 1, '2026-01-15 14:00:19', '2026-01-15 14:26:15', NULL);

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
-- Indexes for table `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_at` (`created_at`);

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
-- Indexes for table `erp_master_courier`
--
ALTER TABLE `erp_master_courier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_status` (`status`);

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
-- Indexes for table `erp_products`
--
ALTER TABLE `erp_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_type_status` (`vendor_id`,`type`,`status`),
  ADD KEY `slug_idx` (`slug`),
  ADD KEY `sku_idx` (`sku`),
  ADD KEY `isbn_idx` (`isbn`),
  ADD KEY `legacy_idx` (`legacy_table`,`legacy_id`),
  ADD KEY `idx_is_set` (`is_set`),
  ADD KEY `idx_is_individual` (`is_individual`);

--
-- Indexes for table `erp_product_images`
--
ALTER TABLE `erp_product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `legacy_reference` (`legacy_table`,`legacy_id`),
  ADD KEY `is_main` (`is_main`),
  ADD KEY `image_order_index` (`image_order`),
  ADD KEY `created_at_index` (`created_at`);

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
-- Indexes for table `erp_shipping_providers`
--
ALTER TABLE `erp_shipping_providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_provider` (`client_id`,`provider`);

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
-- Indexes for table `otp_requests`
--
ALTER TABLE `otp_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mobile_created` (`mobile_number`,`created_at`),
  ADD KEY `idx_ip_created` (`ip_address`,`created_at`);

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
-- Indexes for table `tbl_order_bookset_products`
--
ALTER TABLE `tbl_order_bookset_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_order_item_id` (`order_item_id`),
  ADD KEY `idx_package_id` (`package_id`),
  ADD KEY `idx_product_type_id` (`product_type`,`product_id`);

--
-- Indexes for table `tbl_order_details`
--
ALTER TABLE `tbl_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ready_to_ship` (`ready_to_ship`);

--
-- Indexes for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`inv_type`,`order_id`,`user_id`,`category_id`,`product_id`),
  ADD KEY `inv_type` (`inv_type`,`order_id`,`user_id`,`category_id`,`product_id`),
  ADD KEY `idx_order_type` (`order_type`),
  ADD KEY `idx_school_id` (`school_id`),
  ADD KEY `order_type_idx` (`order_type`),
  ADD KEY `school_id_idx` (`school_id`),
  ADD KEY `idx_board_id` (`board_id`),
  ADD KEY `idx_grade_id` (`grade_id`),
  ADD KEY `idx_branch_id` (`branch_id`);

--
-- Indexes for table `tbl_order_status`
--
ALTER TABLE `tbl_order_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_third_party_shipping`
--
ALTER TABLE `tbl_order_third_party_shipping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_order_unique_id` (`order_unique_id`),
  ADD KEY `idx_third_party_provider` (`third_party_provider`);

--
-- Indexes for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`order_id`,`payment_id`,`razorpay_order_id`,`date`,`status`);

--
-- Indexes for table `tbl_user_concerns`
--
ALTER TABLE `tbl_user_concerns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `status` (`status`);

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
-- Indexes for table `velocity_pincode`
--
ALTER TABLE `velocity_pincode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_shipping_label`
--
ALTER TABLE `vendor_shipping_label`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_slot_no` (`slot_no`),
  ADD KEY `idx_vendor_id` (`vendor_id`),
  ADD KEY `idx_process_slot` (`process_slot`);

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
-- AUTO_INCREMENT for table `chatbot_messages`
--
ALTER TABLE `chatbot_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `erp_client_features`
--
ALTER TABLE `erp_client_features`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1405;

--
-- AUTO_INCREMENT for table `erp_client_feature_subcategories`
--
ALTER TABLE `erp_client_feature_subcategories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `erp_client_settings`
--
ALTER TABLE `erp_client_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
-- AUTO_INCREMENT for table `erp_master_courier`
--
ALTER TABLE `erp_master_courier`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_materials`
--
ALTER TABLE `erp_materials`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT for table `erp_products`
--
ALTER TABLE `erp_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_product_images`
--
ALTER TABLE `erp_product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `erp_school_branches`
--
ALTER TABLE `erp_school_branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `erp_school_images`
--
ALTER TABLE `erp_school_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `erp_shipping_providers`
--
ALTER TABLE `erp_shipping_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_requests`
--
ALTER TABLE `otp_requests`
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
-- AUTO_INCREMENT for table `tbl_order_bookset_products`
--
ALTER TABLE `tbl_order_bookset_products`
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
-- AUTO_INCREMENT for table `tbl_order_status`
--
ALTER TABLE `tbl_order_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_order_third_party_shipping`
--
ALTER TABLE `tbl_order_third_party_shipping`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_transaction`
--
ALTER TABLE `tbl_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user_concerns`
--
ALTER TABLE `tbl_user_concerns`
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
-- AUTO_INCREMENT for table `velocity_pincode`
--
ALTER TABLE `velocity_pincode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3549;

--
-- AUTO_INCREMENT for table `vendor_shipping_label`
--
ALTER TABLE `vendor_shipping_label`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `erp_bookset_package_products`
--
ALTER TABLE `erp_bookset_package_products`
  ADD CONSTRAINT `fk_bookset_package_products_package` FOREIGN KEY (`package_id`) REFERENCES `erp_bookset_packages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
