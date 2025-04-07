-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2024 at 09:03 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rakiro`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `body` varchar(191) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `automails`
--

CREATE TABLE `automails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `from` varchar(191) NOT NULL,
  `cus_category` varchar(191) NOT NULL,
  `days_from_last_invoice` int(11) NOT NULL,
  `schedule_days` int(11) NOT NULL,
  `schedule_time` time NOT NULL,
  `subject` mediumtext DEFAULT NULL,
  `message` mediumtext NOT NULL,
  `attachment` varchar(191) DEFAULT NULL,
  `add_by` int(11) NOT NULL,
  `last_executed_date` date DEFAULT NULL,
  `total_cus` int(11) DEFAULT NULL,
  `job_batch_id` text DEFAULT NULL,
  `total_jobs` int(11) DEFAULT NULL,
  `pending_jobs` int(11) DEFAULT NULL,
  `failed_jobs` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '0="inactive";1="active";2="sent";',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `automails`
--

INSERT INTO `automails` (`id`, `name`, `from`, `cus_category`, `days_from_last_invoice`, `schedule_days`, `schedule_time`, `subject`, `message`, `attachment`, `add_by`, `last_executed_date`, `total_cus`, `job_batch_id`, `total_jobs`, `pending_jobs`, `failed_jobs`, `status`, `created_at`, `updated_at`) VALUES
(1, 'electronic product', 'sales@rakiro.net', '[\"104\",\"106\"]', 8, 10, '17:30:00', 'big days offers', '<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s</p>', NULL, 1, NULL, 0, '0', 0, 0, 0, 1, '2024-06-30 15:32:55', '2024-06-30 15:33:00');

-- --------------------------------------------------------

--
-- Table structure for table `common_setups`
--

CREATE TABLE `common_setups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mailer` varchar(191) NOT NULL,
  `host` varchar(191) NOT NULL,
  `port` int(11) NOT NULL,
  `username` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `encryption` varchar(191) NOT NULL,
  `add_by` int(11) NOT NULL,
  `sending_status` int(11) NOT NULL DEFAULT 1 COMMENT '0="stop";1="start";',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `common_setups`
--

INSERT INTO `common_setups` (`id`, `mailer`, `host`, `port`, `username`, `password`, `encryption`, `add_by`, `sending_status`, `created_at`, `updated_at`) VALUES
(1, 'smtp', 'email-smtp.ap-south-1.amazonaws.com', 465, 'AKIAXWZS4SGQX7VQ72NZ', 'BAvVtQvlpSlc9WGTG6IraD0Vjn9AD4dVV5cdXEB3nzJ6', 'tls', 1, 1, '2024-01-04 06:21:39', '2024-03-18 09:46:57');

-- --------------------------------------------------------

--
-- Table structure for table `email_composes`
--

CREATE TABLE `email_composes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from` varchar(191) NOT NULL,
  `to` varchar(191) NOT NULL,
  `to_group_name` text DEFAULT NULL,
  `cc` mediumtext DEFAULT NULL,
  `subject` mediumtext DEFAULT NULL,
  `message` mediumtext NOT NULL,
  `attachment` mediumtext DEFAULT NULL,
  `schedule_time` datetime NOT NULL,
  `add_by` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0="in schedule";1="sending";2="sent";3="stop";',
  `sending_status` int(11) NOT NULL DEFAULT 1 COMMENT '0="stop";1="start";',
  `total_cus` int(11) DEFAULT NULL,
  `job_batch_id` text DEFAULT NULL,
  `total_jobs` int(10) DEFAULT NULL,
  `pending_jobs` int(10) DEFAULT NULL,
  `failed_jobs` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_lists`
--

CREATE TABLE `group_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_name` varchar(191) NOT NULL,
  `customer_id` mediumtext NOT NULL,
  `add_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_lists`
--

INSERT INTO `group_lists` (`id`, `group_name`, `customer_id`, `add_by`, `created_at`, `updated_at`) VALUES
(1, 'demo', '[\"C000002\",\"C000001\",\"C000004\",\"C000005\",\"C000006\",\"C000007\",\"C000008\",\"C000009\",\"C000011\",\"C000013\",\"C000014\",\"C000015\",\"C000017\",\"C000019\",\"C000020\",\"C000024\",\"C000025\",\"C000026\",\"C000027\",\"C000028\",\"C000030\",\"C000031\",\"C000036\",\"C000037\",\"C000038\",\"C000039\",\"C000040\",\"C000043\",\"C000044\",\"C000045\",\"C000046\",\"C000048\",\"C000050\",\"C000051\",\"C000053\",\"C000054\",\"C000056\",\"C000057\",\"C000058\",\"C000059\",\"C000061\",\"C000062\",\"C000063\",\"C000064\",\"C000065\",\"C000067\",\"C000068\",\"C000069\",\"C000070\",\"C000071\",\"C000072\",\"C000073\",\"C000076\",\"C000077\",\"C000078\",\"C000080\",\"C000081\",\"C000083\",\"C000084\",\"C000085\",\"C000086\",\"C000087\",\"C000088\",\"C000090\",\"C000092\",\"C000093\",\"C000094\",\"C000095\",\"C000096\",\"C000097\",\"C000098\",\"C000099\",\"C000100\",\"C000101\",\"C000102\",\"C000103\",\"C000105\",\"C000106\",\"C000107\",\"C000108\",\"C000109\",\"C000111\",\"C000112\",\"C000113\",\"C000116\",\"C000118\",\"C000120\",\"C000121\",\"C000122\",\"C000123\",\"C000125\",\"C000126\",\"C000127\",\"C000128\",\"C000129\",\"C000130\",\"C000131\",\"C000133\",\"C000134\",\"C000136\",\"C000137\",\"C000138\",\"C000139\",\"C000141\",\"C000143\",\"C000144\",\"C000145\",\"C000147\",\"C000148\",\"C000149\",\"C000150\",\"C000151\",\"C000152\",\"C000154\",\"C000156\",\"C000157\",\"C000158\",\"C000159\",\"C000160\",\"C000161\",\"C000162\",\"C000163\",\"C000164\",\"C000165\",\"C000166\",\"C000167\",\"C000168\",\"C000170\",\"C000171\",\"C000172\",\"C000173\",\"C000176\",\"C000177\",\"C000178\",\"C000179\",\"C000180\",\"C000181\",\"C000185\",\"C000186\",\"C000188\",\"C000191\",\"C000192\",\"C000193\",\"C000198\",\"C000200\",\"C000201\",\"C000202\",\"C000203\",\"C000204\",\"C000205\",\"C000206\",\"C000208\",\"C000211\",\"C000213\",\"C000214\",\"C000215\",\"C000218\",\"C000219\",\"C000221\",\"C000222\",\"C000223\",\"C000224\",\"C000225\",\"C000236\",\"C000294\",\"C000331\",\"C000358\",\"C000372\",\"C000398\",\"C000400\",\"C000402\",\"C000411\",\"C000423\",\"C000435\",\"C000439\",\"C000440\",\"C000452\",\"C000453\",\"C000464\",\"C000494\",\"C000498\",\"C000503\",\"C000506\",\"C000543\",\"C000573\",\"C000597\",\"C000617\",\"C000625\",\"C000682\",\"C000776\",\"C000791\",\"C000799\",\"C000826\",\"C000835\",\"C000841\",\"C000938\",\"C000948\",\"C000978\",\"C000980\",\"C000988\",\"C000991\",\"C001014\",\"C001030\",\"C001052\",\"C001083\",\"C001100\",\"C001106\",\"C001117\",\"C001134\",\"C001353\",\"C001365\",\"C001369\",\"C001373\",\"C001391\",\"C001420\",\"C001424\",\"C001432\",\"C001441\",\"C001455\",\"C001462\",\"C001463\",\"C001496\",\"C001537\",\"C001543\",\"C001572\",\"C001601\",\"C001630\",\"C001646\",\"C001793\",\"C001953\",\"C002126\",\"C002139\",\"C002342\",\"C002457\",\"C002590\",\"C002595\",\"C002609\",\"C002643\",\"C002657\",\"C002665\",\"C002687\",\"C002694\",\"C002778\",\"C002851\",\"C002925\",\"C002960\",\"C003126\",\"C003213\",\"C003311\",\"C003346\",\"C003349\",\"C003427\",\"C003550\",\"C003736\",\"C003836\",\"C003875\",\"C003901\",\"C003931\",\"C003941\",\"C004075\",\"C004666\",\"C004795\",\"C005056\"]', 1, '2024-06-30 14:45:48', '2024-06-30 14:45:48');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(13, '2023_01_27_133021_create_settings_table', 1),
(38, '2014_10_12_000000_create_users_table', 3),
(39, '2014_10_12_100000_create_password_resets_table', 3),
(40, '2016_06_01_000001_create_oauth_auth_codes_table', 3),
(41, '2016_06_01_000002_create_oauth_access_tokens_table', 3),
(42, '2016_06_01_000003_create_oauth_refresh_tokens_table', 3),
(44, '2016_06_01_000005_create_oauth_personal_access_clients_table', 3),
(45, '2019_08_19_000000_create_failed_jobs_table', 3),
(46, '2019_12_14_000001_create_personal_access_tokens_table', 3),
(49, '2023_09_30_113859_create_articles_table', 3),
(51, '2023_11_20_100519_create_group_lists_table', 3),
(53, '2016_06_01_000004_create_oauth_clients_table', 4),
(54, '2023_12_09_130032_create_email_composes_table', 4),
(55, '2023_12_18_112759_create_smtp_setups_table', 5),
(56, '2023_12_21_110825_create_from_emails_table', 6),
(57, '2024_01_04_105449_create_common_setups_table', 7),
(58, '2024_01_05_180121_create_automails_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(191) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) NOT NULL,
  `option_value` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `option_name`, `option_value`, `created_at`, `updated_at`) VALUES
(1, 'rules', '<p><strong>Privacy Policy of&nbsp;Rummy Agent</strong></p>\r\n\r\n<p>At Rummy Agent, we collect and manage user data according to the following Privacy Policy.</p>', NULL, '2023-10-10 08:39:11'),
(2, 'app_version', '3.0.4', NULL, '2023-10-10 08:38:26');

-- --------------------------------------------------------

--
-- Table structure for table `smtp_setups`
--

CREATE TABLE `smtp_setups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `from_address` text NOT NULL,
  `add_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `smtp_setups`
--

INSERT INTO `smtp_setups` (`id`, `name`, `from_address`, `add_by`, `created_at`, `updated_at`) VALUES
(8, 'RakiroBiotech', 'sales@rakiro.net', 1, '2023-12-26 09:52:11', '2024-03-18 09:45:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fname` varchar(191) DEFAULT NULL,
  `lname` varchar(191) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `mob_prefix` varchar(191) DEFAULT NULL COMMENT 'country code',
  `country_name` varchar(191) DEFAULT NULL COMMENT 'country name',
  `mobile` bigint(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` int(11) DEFAULT NULL COMMENT '1=admin;2=subadmin;',
  `password` varchar(191) NOT NULL,
  `photo` varchar(191) DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `refcode` varchar(191) DEFAULT NULL,
  `refby` varchar(191) DEFAULT NULL,
  `mob_otp` int(11) DEFAULT NULL,
  `email_otp` int(11) DEFAULT NULL,
  `account_otp_verified` int(11) NOT NULL DEFAULT 0,
  `reset_otp` int(11) DEFAULT NULL,
  `reset_token` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(191) DEFAULT NULL,
  `alt_no` varchar(191) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `last_login` varchar(150) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `name`, `email`, `mob_prefix`, `country_name`, `mobile`, `email_verified_at`, `role`, `password`, `photo`, `avatar`, `refcode`, `refby`, `mob_otp`, `email_otp`, `account_otp_verified`, `reset_otp`, `reset_token`, `address`, `dob`, `gender`, `alt_no`, `status`, `last_login`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'M9tXuAy1Np', 'LsGtlxnANX', 'Rakiro Mail', 'admin@gmail.com', NULL, NULL, 8817408300, NULL, 1, '$2y$10$Bj3P99iqPw2j5/jUtuLncuPcG.v449zqdxgft3471Mwdl5d.E7hSW', 'gallery/05-01-2024-11-19-531704433793.jpg', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'mp nagar, madhya pradesh', NULL, NULL, NULL, 1, '2024-06-30 7:43 PM', NULL, NULL, '2024-06-30 14:13:33'),
(7, NULL, NULL, 'john', 'john@gmail.com', NULL, NULL, 1478569320, NULL, 2, '$2y$10$xzJullcIbBLNNzSuQP10UO46576IUvsqg5ZtihOyOOUTfXcoT.QQS', 'gallery/18-12-2023-11-02-451702877565.jpg', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'rftgyhujik', NULL, NULL, NULL, 1, NULL, NULL, '2023-12-18 05:32:45', '2024-02-26 09:55:07'),
(8, NULL, NULL, 'deo', 'deo@gmail.com', NULL, NULL, 1478523690, NULL, 2, '$2y$10$SFBp5Pd7z2TQlHrAO609d.OxQLd1oW.XxqRyXMl1Xf.W4uM5IZG7a', 'gallery/26-02-2024-15-42-411708942360.png', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'mp nagar', NULL, NULL, NULL, 1, NULL, NULL, '2024-02-26 09:59:39', '2024-02-26 10:22:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `automails`
--
ALTER TABLE `automails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `common_setups`
--
ALTER TABLE `common_setups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_composes`
--
ALTER TABLE `email_composes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `group_lists`
--
ALTER TABLE `group_lists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_lists_group_name_unique` (`group_name`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smtp_setups`
--
ALTER TABLE `smtp_setups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `automails`
--
ALTER TABLE `automails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `common_setups`
--
ALTER TABLE `common_setups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `email_composes`
--
ALTER TABLE `email_composes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `group_lists`
--
ALTER TABLE `group_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `smtp_setups`
--
ALTER TABLE `smtp_setups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
