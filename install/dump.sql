CREATE TABLE `users` (
  `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(320) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `billing` text,
  `api_key` varchar(32) DEFAULT NULL,
  `token_code` varchar(32) DEFAULT NULL,
  `twofa_secret` varchar(16) DEFAULT NULL,
  `anti_phishing_code` varchar(8) DEFAULT NULL,
  `one_time_login_code` varchar(32) DEFAULT NULL,
  `pending_email` varchar(128) DEFAULT NULL,
  `email_activation_code` varchar(32) DEFAULT NULL,
  `lost_password_code` varchar(32) DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `is_newsletter_subscribed` tinyint NOT NULL DEFAULT '0',
  `has_pending_internal_notifications` tinyint NOT NULL DEFAULT '0',
  `plan_id` varchar(16) NOT NULL DEFAULT '',
  `plan_expiration_date` datetime DEFAULT NULL,
  `plan_settings` text,
  `plan_trial_done` tinyint DEFAULT '0',
  `plan_expiry_reminder` tinyint DEFAULT '0',
  `payment_subscription_id` varchar(64) DEFAULT NULL,
  `payment_processor` varchar(16) DEFAULT NULL,
  `payment_total_amount` float DEFAULT NULL,
  `payment_currency` varchar(4) DEFAULT NULL,
  `referral_key` varchar(32) DEFAULT NULL,
  `referred_by` varchar(32) DEFAULT NULL,
  `referred_by_has_converted` tinyint DEFAULT '0',
  `language` varchar(32) DEFAULT 'english',
  `currency` varchar(4) DEFAULT NULL,
  `timezone` varchar(32) DEFAULT 'UTC',
  `preferences` text,
  `extra` text,
  `datetime` datetime DEFAULT NULL,
  `next_cleanup_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(64) DEFAULT NULL,
  `continent_code` varchar(8) DEFAULT NULL,
  `country` varchar(8) DEFAULT NULL,
  `city_name` varchar(32) DEFAULT NULL,
  `device_type` varchar(16) DEFAULT NULL,
  `browser_language` varchar(32) DEFAULT NULL,
  `browser_name` varchar(32) DEFAULT NULL,
  `os_name` varchar(16) DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `total_logins` int DEFAULT '0',
  `user_deletion_reminder` tinyint DEFAULT '0',
  `source` varchar(32) DEFAULT 'direct',
  `audit_audits_current_month` bigint UNSIGNED DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `plan_id` (`plan_id`),
  KEY `api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `users` (`email`, `password`, `name`, `billing`, `api_key`, `token_code`, `type`, `status`, `plan_id`, `plan_expiration_date`, `plan_settings`, `datetime`, `ip`, `last_activity`) VALUES
('admin', '$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy', 'Admin Web', '{\"type\":\"personal\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"AF\",\"phone\":\"\",\"tax_id\":\"\",\"notes\":\"\"}', md5(rand()), md5(rand()), 1, 1, 'custom', '2030-01-01 12:00:00', '{\"websites_limit\":-1,\"audits_per_month_limit\":-1,\"audits_bulk_limit\":-1,\"audits_retention\":1095,\"audits_check_intervals\":[\"21600\",\"43200\",\"86400\",\"259200\",\"432000\",\"864000\",\"1296000\",\"2592000\"],\"domains_limit\":-1,\"teams_limit\":0,\"team_members_limit\":0,\"password_protection_is_enabled\":true,\"api_is_enabled\":true,\"affiliate_commission_percentage\":0,\"no_ads\":true,\"export\": {\"pdf\": true,\"csv\": true,\"json\": true},\"removable_branding_is_enabled\":true,\"active_notification_handlers_per_resource_limit\":-1,\"audits_enabled_tests\":{\"title\":true,\"meta_description\":true,\"h1\":true,\"meta_keywords\":true,\"not_found\":true,\"robots\":true,\"language\":true,\"favicon\":true,\"meta_robots\":true,\"header_robots\":true,\"response_time\":true,\"page_size\":true,\"dom_size\":true,\"meta_viewport\":true,\"meta_charset\":true,\"deprecated_html_tags\":true,\"header_server\":true,\"server_compression\":true,\"doctype\":true,\"social_links\":true,\"words_count\":true,\"words_used\":true,\"emails\":true,\"text_to_html_ratio\":true,\"is_https\":true,\"is_ssl_valid\":true,\"inline_css\":true,\"image_formats\":true,\"image_alt\":true,\"image_lazy_loading\":true,\"other_headings\":true,\"canonical\":true,\"is_seo_friendly_url\":true,\"is_http2\":true,\"unsafe_external_links\":true,\"non_deferred_scripts\":true,\"http_requests\":true,\"internal_links\":true,\"external_links\":true,\"opengraph\":true},\"notification_handlers_email_limit\":-1,\"notification_handlers_webhook_limit\":-1,\"notification_handlers_slack_limit\":-1,\"notification_handlers_discord_limit\":-1,\"notification_handlers_telegram_limit\":-1,\"notification_handlers_microsoft_teams_limit\":-1,\"notification_handlers_twilio_limit\":-1,\"notification_handlers_twilio_call_limit\":-1,\"notification_handlers_whatsapp_limit\":-1,\"notification_handlers_x_limit\":-1}', NOW(), '', NOW());

-- SEPARATOR --

CREATE TABLE `users_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `ip` varchar(64) DEFAULT NULL,
  `device_type` varchar(16) DEFAULT NULL,
  `os_name` varchar(16) DEFAULT NULL,
  `continent_code` varchar(8) DEFAULT NULL,
  `country_code` varchar(8) DEFAULT NULL,
  `city_name` varchar(32) DEFAULT NULL,
  `browser_language` varchar(32) DEFAULT NULL,
  `browser_name` varchar(32) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_logs_user_id` (`user_id`),
  KEY `users_logs_ip_type_datetime_index` (`ip`,`type`,`datetime`),
  CONSTRAINT `users_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `pages_categories` (
  `pages_category_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL DEFAULT '',
  `description` varchar(256) DEFAULT NULL,
  `icon` varchar(32) DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `language` varchar(32) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`pages_category_id`),
  KEY `url` (`url`),
  KEY `pages_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `pages` (
  `page_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `pages_category_id` bigint UNSIGNED DEFAULT NULL,
  `url` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL DEFAULT '',
  `description` varchar(256) DEFAULT NULL,
  `icon` varchar(32) DEFAULT NULL,
  `keywords` varchar(256) DEFAULT NULL,
  `editor` varchar(16) DEFAULT NULL,
  `content` longtext,
  `type` varchar(16) DEFAULT '',
  `position` varchar(16) NOT NULL DEFAULT '',
  `language` varchar(32) DEFAULT NULL,
  `open_in_new_tab` tinyint DEFAULT '1',
  `order` int DEFAULT '0',
  `total_views` bigint UNSIGNED DEFAULT '0',
  `is_published` tinyint DEFAULT '1',
  `datetime` datetime DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`page_id`),
  KEY `pages_pages_category_id_index` (`pages_category_id`),
  KEY `pages_url_index` (`url`),
  KEY `pages_is_published_index` (`is_published`),
  KEY `pages_language_index` (`language`),
  CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`pages_category_id`) REFERENCES `pages_categories` (`pages_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `pages` (`pages_category_id`, `url`, `title`, `description`, `content`, `type`, `position`, `order`, `total_views`, `datetime`, `last_datetime`) VALUES
(NULL, 'https://altumcode.com/', 'Software by AltumCode', '', '', 'external', 'bottom', 1, 0, NOW(), NOW()),
(NULL, 'https://altumco.de/66audit', 'Built with 66audit', '', '', 'external', 'bottom', 0, 0, NOW(), NOW());

-- SEPARATOR --

CREATE TABLE `blog_posts_categories` (
  `blog_posts_category_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL DEFAULT '',
  `description` varchar(256) DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `language` varchar(32) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`blog_posts_category_id`),
  KEY `url` (`url`),
  KEY `blog_posts_categories_url_language_index` (`url`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `blog_posts` (
  `blog_post_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `blog_posts_category_id` bigint UNSIGNED DEFAULT NULL,
  `url` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL DEFAULT '',
  `description` varchar(256) DEFAULT NULL,
  `keywords` varchar(256) DEFAULT NULL,
  `image` varchar(40) DEFAULT NULL,
  `image_description` varchar(256) DEFAULT NULL,
  `editor` varchar(16) DEFAULT NULL,
  `content` longtext,
  `language` varchar(32) DEFAULT NULL,
  `total_views` bigint UNSIGNED DEFAULT '0',
  `is_published` tinyint DEFAULT '1',
  `datetime` datetime DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`blog_post_id`),
  KEY `blog_post_id_index` (`blog_post_id`),
  KEY `blog_post_url_index` (`url`),
  KEY `blog_posts_category_id` (`blog_posts_category_id`),
  KEY `blog_posts_is_published_index` (`is_published`),
  KEY `blog_posts_language_index` (`language`),
  CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`blog_posts_category_id`) REFERENCES `blog_posts_categories` (`blog_posts_category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts` (
  `broadcast_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `subject` varchar(128) DEFAULT NULL,
  `content` text,
  `segment` varchar(64) DEFAULT NULL,
  `settings` text,
  `users_ids` longtext,
  `sent_users_ids` longtext,
  `sent_emails` int UNSIGNED DEFAULT '0',
  `total_emails` int UNSIGNED DEFAULT '0',
  `status` varchar(16) DEFAULT NULL,
  `views` bigint UNSIGNED DEFAULT '0',
  `clicks` bigint UNSIGNED DEFAULT '0',
  `last_sent_email_datetime` datetime DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`broadcast_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `broadcasts_statistics` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `broadcast_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(16) DEFAULT NULL,
  `target` varchar(2048) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `broadcast_id` (`broadcast_id`),
  KEY `broadcasts_statistics_user_id_broadcast_id_type_index` (`broadcast_id`,`user_id`,`type`),
  CONSTRAINT `broadcasts_statistics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `broadcasts_statistics_ibfk_2` FOREIGN KEY (`broadcast_id`) REFERENCES `broadcasts` (`broadcast_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `domains` (
  `domain_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `scheme` varchar(8) NOT NULL DEFAULT '',
  `host` varchar(128) NOT NULL DEFAULT '',
  `custom_index_url` varchar(256) DEFAULT NULL,
  `custom_not_found_url` varchar(256) DEFAULT NULL,
  `is_enabled` tinyint DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`domain_id`),
  KEY `user_id` (`user_id`),
  KEY `host` (`host`),
  CONSTRAINT `domains_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `internal_notifications` (
  `internal_notification_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `for_who` varchar(16) DEFAULT NULL,
  `from_who` varchar(16) DEFAULT NULL,
  `icon` varchar(64) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `url` varchar(512) DEFAULT NULL,
  `is_read` tinyint UNSIGNED DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  `read_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`internal_notification_id`),
  KEY `user_id` (`user_id`),
  KEY `users_notifications_for_who_idx` (`for_who`) USING BTREE,
  CONSTRAINT `internal_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `notification_handlers` (
  `notification_handler_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `settings` text,
  `is_enabled` tinyint NOT NULL DEFAULT '1',
  `last_datetime` datetime DEFAULT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`notification_handler_id`),
  UNIQUE KEY `notification_handler_id` (`notification_handler_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notification_handlers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `plans` (
  `plan_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(256) NOT NULL DEFAULT '',
  `translations` text NOT NULL,
  `prices` text NOT NULL,
  `trial_days` int UNSIGNED NOT NULL DEFAULT '0',
  `settings` longtext NOT NULL,
  `taxes_ids` text,
  `color` varchar(16) DEFAULT NULL,
  `status` tinyint NOT NULL,
  `order` int UNSIGNED DEFAULT '0',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(64) NOT NULL DEFAULT '',
  `value` longtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

SET @cron_key = MD5(RAND());

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES
('main', '{\"title\":\"Your title\",\"default_language\":\"english\",\"default_theme_style\":\"light\",\"default_timezone\":\"UTC\",\"index_url\":\"\",\"terms_and_conditions_url\":\"\",\"privacy_policy_url\":\"\",\"not_found_url\":\"\",\"ai_scraping_is_allowed\":true,\"se_indexing\":true,\"display_index_plans\":true,\"display_index_testimonials\":true,\"display_index_faq\":true,\"display_index_latest_blog_posts\":true,\"default_results_per_page\":100,\"default_order_type\":\"DESC\",\"auto_language_detection_is_enabled\":true,\"blog_is_enabled\":false,\"api_is_enabled\":true,\"theme_style_change_is_enabled\":true,\"logo_light\":\"\",\"logo_dark\":\"\",\"logo_email\":\"\",\"opengraph\":\"\",\"favicon\":\"\",\"openai_api_key\":\"\",\"openai_model\":\"gpt-4o\",\"force_https_is_enabled\":false,\"broadcasts_statistics_is_enabled\":true,\"breadcrumbs_is_enabled\":true,\"display_pagination_when_no_pages\":false,\"chart_cache\":12,\"chart_days\":30}'),
('languages', '{\"english\":{\"status\":\"active\"}}'),
('users', '{\"email_confirmation\":false,\"welcome_email_is_enabled\":false,\"register_is_enabled\":true,\"register_only_social_logins\":false,\"register_social_login_require_password\":false,\"register_display_newsletter_checkbox\":false,\"login_rememberme_checkbox_is_checked\":true,\"login_rememberme_cookie_days\":90,\"auto_delete_unconfirmed_users\":3,\"auto_delete_inactive_users\":30,\"user_deletion_reminder\":0,\"blacklisted_domains\":[],\"blacklisted_countries\":[],\"login_lockout_is_enabled\":true,\"login_lockout_max_retries\":3,\"login_lockout_time\":10,\"lost_password_lockout_is_enabled\":true,\"lost_password_lockout_max_retries\":3,\"lost_password_lockout_time\":10,\"resend_activation_lockout_is_enabled\":true,\"resend_activation_lockout_max_retries\":3,\"resend_activation_lockout_time\":10,\"register_lockout_is_enabled\":true,\"register_lockout_max_registrations\":3,\"register_lockout_time\":10}'),
('ads', '{\"ad_blocker_detector_is_enabled\":true,\"ad_blocker_detector_lock_is_enabled\":false,\"ad_blocker_detector_delay\":5,\"header\":\"\",\"footer\":\"\",\"header_biolink\":\"\",\"footer_biolink\":\"\",\"header_splash\":\"\",\"footer_splash\":\"\"}'),
('captcha', '{\"type\":\"basic\",\"recaptcha_public_key\":\"\",\"recaptcha_private_key\":\"\",\"login_is_enabled\":0,\"register_is_enabled\":0,\"lost_password_is_enabled\":0,\"resend_activation_is_enabled\":0}'),
('cron', concat('{\"key\":\"', @cron_key, '\"}')),
('email_notifications', '{\"emails\":\"\",\"new_user\":false,\"delete_user\":false,\"new_payment\":false,\"new_domain\":false,\"new_affiliate_withdrawal\":false,\"contact\":false}'),
('internal_notifications', '{\"users_is_enabled\":true,\"admins_is_enabled\":true,\"new_user\":true,\"delete_user\":true,\"new_newsletter_subscriber\":true,\"new_payment\":true,\"new_affiliate_withdrawal\":true}'),
('content', '{\"blog_is_enabled\":true,\"blog_share_is_enabled\":true,\"blog_search_widget_is_enabled\":false,\"blog_categories_widget_is_enabled\":true,\"blog_popular_widget_is_enabled\":true,\"blog_views_is_enabled\":true,\"pages_is_enabled\":true,\"pages_share_is_enabled\":true,\"pages_popular_widget_is_enabled\":true,\"pages_views_is_enabled\":true}'),
('sso', '{\"is_enabled\":true,\"display_menu_items\":true,\"websites\":{}}'),
('facebook', '{\"is_enabled\":false,\"app_id\":\"\",\"app_secret\":\"\"}'),
('google', '{\"is_enabled\":false,\"client_id\":\"\",\"client_secret\":\"\"}'),
('twitter', '{\"is_enabled\":false,\"consumer_api_key\":\"\",\"consumer_api_secret\":\"\"}'),
('discord', '{\"is_enabled\":false,\"client_id\":\"\",\"client_secret\":\"\"}'),
('linkedin', '{\"is_enabled\":false,\"client_id\":\"\",\"client_secret\":\"\"}'),
('microsoft', '{\"is_enabled\":false,\"client_id\":\"\",\"client_secret\":\"\"}'),
('plan_custom', '{\"plan_id\":\"custom\",\"name\":\"Custom\",\"description\":\"Contact us for enterprise pricing.\",\"translations\":{\"english\":{\"name\":\"Custom\",\"description\":\"Contact us for enterprise pricing.\",\"price\":\"TBD\"}},\"price\":\"TBD\",\"custom_button_url\":\"mailto:sample@example.com\",\"color\":null,\"status\":2,\"settings\":{\"websites_limit\":-1,\"audits_per_month_limit\":-1,\"audits_bulk_limit\":-1,\"audits_retention\":365,\"audits_check_intervals\":[\"21600\",\"43200\",\"86400\",\"259200\",\"432000\",\"864000\",\"1296000\",\"2592000\"],\"domains_limit\":-1,\"teams_limit\":0,\"team_members_limit\":0,\"password_protection_is_enabled\":true,\"api_is_enabled\":true,\"affiliate_commission_percentage\":0,\"no_ads\":true,\"export\": {\"pdf\": true,\"csv\": true,\"json\": true},\"removable_branding_is_enabled\":true,\"active_notification_handlers_per_resource_limit\":-1,\"audits_enabled_tests\":{\"title\":true,\"meta_description\":true,\"h1\":true,\"meta_keywords\":true,\"not_found\":true,\"robots\":true,\"language\":true,\"favicon\":true,\"meta_robots\":true,\"header_robots\":true,\"response_time\":true,\"page_size\":true,\"dom_size\":true,\"meta_viewport\":true,\"meta_charset\":true,\"deprecated_html_tags\":true,\"header_server\":true,\"server_compression\":true,\"doctype\":true,\"social_links\":true,\"words_count\":true,\"words_used\":true,\"emails\":true,\"text_to_html_ratio\":true,\"is_https\":true,\"is_ssl_valid\":true,\"inline_css\":true,\"image_formats\":true,\"image_alt\":true,\"image_lazy_loading\":true,\"other_headings\":true,\"canonical\":true,\"is_seo_friendly_url\":true,\"is_http2\":true,\"unsafe_external_links\":true,\"non_deferred_scripts\":true,\"http_requests\":true,\"internal_links\":true,\"external_links\":true,\"opengraph\":true},\"notification_handlers_email_limit\":-1,\"notification_handlers_webhook_limit\":-1,\"notification_handlers_slack_limit\":-1,\"notification_handlers_discord_limit\":-1,\"notification_handlers_telegram_limit\":-1,\"notification_handlers_microsoft_teams_limit\":-1,\"notification_handlers_twilio_limit\":-1,\"notification_handlers_twilio_call_limit\":-1,\"notification_handlers_whatsapp_limit\":-1,\"notification_handlers_x_limit\":-1}}'),
('plan_free', '{\"plan_id\":\"free\",\"name\":\"Free\",\"description\":\"No credit card required.\",\"translations\":{\"english\":{\"name\":\"Free\",\"description\":\"No credit card required.\",\"price\":\"$0\"}},\"price\":\"$0\",\"color\":null,\"status\":1,\"settings\":{\"websites_limit\":10,\"audits_per_month_limit\":50,\"audits_bulk_limit\":5,\"audits_retention\":10,\"audits_check_intervals\":[\"86400\",\"259200\",\"432000\",\"864000\",\"1296000\",\"2592000\"],\"domains_limit\":1,\"teams_limit\":0,\"team_members_limit\":0,\"password_protection_is_enabled\":true,\"api_is_enabled\":true,\"affiliate_commission_percentage\":0,\"no_ads\":true,\"export\": {\"pdf\": true,\"csv\": true,\"json\": true},\"removable_branding_is_enabled\":false,\"active_notification_handlers_per_resource_limit\":1,\"audits_enabled_tests\":{\"title\":true,\"meta_description\":true,\"h1\":true,\"meta_keywords\":true,\"not_found\":true,\"robots\":true,\"language\":true,\"favicon\":true,\"meta_robots\":true,\"header_robots\":true,\"response_time\":true,\"page_size\":true,\"dom_size\":true,\"meta_viewport\":true,\"meta_charset\":true,\"deprecated_html_tags\":true,\"header_server\":true,\"server_compression\":true,\"doctype\":true,\"social_links\":true,\"words_count\":true,\"words_used\":true,\"emails\":true,\"text_to_html_ratio\":true,\"is_https\":true,\"is_ssl_valid\":true,\"inline_css\":true,\"image_formats\":true,\"image_alt\":true,\"image_lazy_loading\":true,\"other_headings\":true,\"canonical\":true,\"is_seo_friendly_url\":true,\"is_http2\":true,\"unsafe_external_links\":true,\"non_deferred_scripts\":true,\"http_requests\":true,\"internal_links\":true,\"external_links\":true,\"opengraph\":true},\"notification_handlers_email_limit\":1,\"notification_handlers_webhook_limit\":1,\"notification_handlers_slack_limit\":1,\"notification_handlers_discord_limit\":1,\"notification_handlers_telegram_limit\":1,\"notification_handlers_microsoft_teams_limit\":1,\"notification_handlers_twilio_limit\":1,\"notification_handlers_twilio_call_limit\":1,\"notification_handlers_whatsapp_limit\":1,\"notification_handlers_x_limit\":1}}'),
('plan_guest', '{\"plan_id\":\"guest\",\"name\":\"Guest\",\"description\":\"No sign-up required.\",\"translations\":{\"english\":{\"name\":\"Guest\",\"description\":\"No sign-up required.\",\"price\":\"Free\"}},\"price\":\"Free\",\"color\":null,\"status\":1,\"settings\":{\"websites_limit\":0,\"audits_per_month_limit\":10,\"audits_bulk_limit\":0,\"audits_retention\":3,\"audits_check_intervals\":[],\"domains_limit\":0,\"teams_limit\":0,\"team_members_limit\":0,\"password_protection_is_enabled\":false,\"api_is_enabled\":false,\"affiliate_commission_percentage\":0,\"no_ads\":false,\"export\": {\"pdf\": true,\"csv\": true,\"json\": true},\"removable_branding_is_enabled\":false,\"active_notification_handlers_per_resource_limit\":0,\"audits_enabled_tests\":{\"title\":true,\"meta_description\":true,\"h1\":true,\"meta_keywords\":true,\"not_found\":true,\"robots\":true,\"language\":true,\"favicon\":true,\"meta_robots\":true,\"header_robots\":true,\"response_time\":true,\"page_size\":true,\"dom_size\":true,\"meta_viewport\":true,\"meta_charset\":true,\"deprecated_html_tags\":true,\"header_server\":true,\"server_compression\":true,\"doctype\":true,\"social_links\":true,\"words_count\":true,\"words_used\":true,\"emails\":true,\"text_to_html_ratio\":true,\"is_https\":true,\"is_ssl_valid\":true,\"inline_css\":true,\"image_formats\":true,\"image_alt\":true,\"image_lazy_loading\":true,\"other_headings\":true,\"canonical\":true,\"is_seo_friendly_url\":true,\"is_http2\":true,\"unsafe_external_links\":true,\"non_deferred_scripts\":true,\"http_requests\":true,\"internal_links\":true,\"external_links\":true,\"opengraph\":true},\"notification_handlers_email_limit\":0,\"notification_handlers_webhook_limit\":0,\"notification_handlers_slack_limit\":0,\"notification_handlers_discord_limit\":0,\"notification_handlers_telegram_limit\":0,\"notification_handlers_microsoft_teams_limit\":0,\"notification_handlers_twilio_limit\":0,\"notification_handlers_twilio_call_limit\":0,\"notification_handlers_whatsapp_limit\":0,\"notification_handlers_x_limit\":0}}'),
('payment', '{\"is_enabled\":false,\"type\":\"both\",\"default_payment_frequency\":\"monthly\",\"currencies\":{\"USD\":{\"code\":\"USD\",\"symbol\":\"$\",\"default_payment_processor\":\"offline_payment\"}},\"default_currency\":\"USD\",\"codes_is_enabled\":true,\"taxes_and_billing_is_enabled\":true,\"invoice_is_enabled\":true,\"user_plan_expiry_reminder\":0,\"user_plan_expiry_checker_is_enabled\":0,\"currency_exchange_api_key\":\"\"}'),
('paypal', '{\"is_enabled\":\"0\",\"mode\":\"sandbox\",\"client_id\":\"\",\"secret\":\"\"}'),
('stripe', '{\"is_enabled\":\"0\",\"publishable_key\":\"\",\"secret_key\":\"\",\"webhook_secret\":\"\"}'),
('offline_payment', '{\"is_enabled\":\"0\",\"instructions\":\"Your offline payment instructions go here..\"}'),
('coinbase', '{\"is_enabled\":false,\"api_key\":\"\",\"webhook_secret\":\"\",\"currencies\":[\"USD\"]}'),
('payu', '{\"is_enabled\":false,\"mode\":\"sandbox\",\"merchant_pos_id\":\"\",\"signature_key\":\"\",\"oauth_client_id\":\"\",\"oauth_client_secret\":\"\",\"currencies\":[\"USD\"]}'),
('iyzico', '{\"is_enabled\":false,\"mode\":\"live\",\"api_key\":\"\",\"secret_key\":\"\",\"currencies\":[\"USD\"]}'),
('paystack', '{\"is_enabled\":false,\"public_key\":\"\",\"secret_key\":\"\",\"currencies\":[\"USD\"]}'),
('razorpay', '{\"is_enabled\":false,\"key_id\":\"\",\"key_secret\":\"\",\"webhook_secret\":\"\",\"currencies\":[\"USD\"]}'),
('mollie', '{\"is_enabled\":false,\"api_key\":\"\",\"currencies\":[\"USD\"]}'),
('yookassa', '{\"is_enabled\":false,\"shop_id\":\"\",\"secret_key\":\"\",\"currencies\":[\"USD\"]}'),
('crypto_com', '{\"is_enabled\":false,\"publishable_key\":\"\",\"secret_key\":\"\",\"webhook_secret\":\"\",\"currencies\":[\"USD\"]}'),
('paddle', '{\"is_enabled\":false,\"mode\":\"sandbox\",\"vendor_id\":\"\",\"api_key\":\"\",\"public_key\":\"\",\"currencies\":[\"USD\"]}'),
('mercadopago', '{\"is_enabled\":false,\"access_token\":\"\",\"currencies\":[\"USD\"]}'),
('midtrans', '{\"is_enabled\":false,\"server_key\":\"\",\"mode\":\"sandbox\",\"currencies\":[\"USD\"]}'),
('flutterwave', '{\"is_enabled\":false,\"secret_key\":\"\",\"currencies\":[\"USD\"]}'),
('smtp', '{\"from_name\":\"AltumCode\",\"from\":\"\",\"reply_to_name\":\"\",\"reply_to\":\"\",\"cc\":\"\",\"bcc\":\"\",\"host\":\"\",\"encryption\":\"tls\",\"port\":\"\",\"auth\":0,\"username\":\"\",\"password\":\"\",\"display_socials\":false,\"company_details\":\"\"}'),
('theme', '{\"light_is_enabled\": false, \"dark_is_enabled\": false}'),
('custom', '{\"body_content\":\"\",\"head_js\":\"\",\"head_css\":\"\"}'),
('socials', '{\"threads\":\"\",\"youtube\":\"\",\"facebook\":\"\",\"x\":\"\",\"instagram\":\"\",\"tiktok\":\"\",\"linkedin\":\"\",\"whatsapp\":\"\",\"email\":\"\"}'),
('announcements', '{\"guests_is_enabled\":0,\"guests_id\":\"035cc337f6de075434bc24807b7ad9af\",\"guests_content\":\"\",\"guests_text_color\":\"#000000\",\"guests_background_color\":\"#000000\",\"users_is_enabled\":0,\"users_id\":\"035cc337f6de075434bc24807b7ad9af\",\"users_content\":\"\",\"users_text_color\":\"#000000\",\"users_background_color\":\"#000000\",\"translations\":{\"english\":{\"guests_content\":\"\",\"users_content\":\"\"}}}'),
('business', '{\"invoice_is_enabled\":\"0\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"\",\"email\":\"\",\"phone\":\"\",\"tax_type\":\"\",\"tax_id\":\"\",\"custom_key_one\":\"\",\"custom_value_one\":\"\",\"custom_key_two\":\"\",\"custom_value_two\":\"\"}'),
('webhooks', '{\"user_new\":\"\",\"user_delete\":\"\",\"payment_new\":\"\",\"code_redeemed\":\"\",\"contact\":\"\",\"cron_start\":\"\",\"cron_end\":\"\",\"domain_new\":\"\",\"domain_update\":\"\"}'),
('affiliate', '{\"is_enabled\":\"0\",\"commission_type\":\"forever\",\"minimum_withdrawal_amount\":\"1\",\"commission_percentage\":\"25\",\"withdrawal_notes\":\"\"}'),
('cookie_consent', '{\"is_enabled\":false,\"logging_is_enabled\":false,\"necessary_is_enabled\":true,\"analytics_is_enabled\":true,\"targeting_is_enabled\":true,\"layout\":\"bar\",\"position_y\":\"middle\",\"position_x\":\"center\"}'),
('audits', '{\"user_agent\":\"66audit 1.0.0\",\"request_timeout\":\"3\",\"double_check_is_enabled\":true,\"double_check_wait\":2,\"domains_is_enabled\":1,\"domains_custom_main_ip\":\"\",\"blacklisted_domains\":[],\"available_tests\":{\"title\":true,\"meta_description\":true,\"h1\":true,\"meta_keywords\":true,\"not_found\":true,\"robots\":true,\"language\":true,\"favicon\":true,\"meta_robots\":true,\"header_robots\":true,\"response_time\":true,\"page_size\":true,\"dom_size\":true,\"meta_viewport\":true,\"meta_charset\":true,\"deprecated_html_tags\":true,\"header_server\":true,\"server_compression\":true,\"doctype\":true,\"social_links\":true,\"words_count\":true,\"words_used\":true,\"emails\":true,\"text_to_html_ratio\":true,\"is_https\":true,\"is_ssl_valid\":true,\"inline_css\":true,\"image_formats\":true,\"image_alt\":true,\"image_lazy_loading\":true,\"other_headings\":true,\"canonical\":true,\"is_seo_friendly_url\":true,\"is_http2\":true,\"unsafe_external_links\":true,\"non_deferred_scripts\":true,\"http_requests\":true,\"internal_links\":true,\"external_links\":true,\"opengraph\":true},\"example_url\":\"\"}'),
('tools', '{\"is_enabled\":1,\"access\":\"everyone\",\"available_tools\":{\"dns_lookup\":true,\"ip_lookup\":true,\"ssl_lookup\":true,\"whois_lookup\":true,\"ping\":true,\"meta_tags_checker\":true,\"website_hosting_checker\":true,\"http_headers_lookup\":true,\"http2_checker\":true,\"google_cache_checker\":true,\"url_redirect_checker\":true,\"reverse_ip_lookup\":true,\"brotli_checker\":true},\"extra_content_is_enabled\":true,\"share_is_enabled\":true,\"views_is_enabled\":true,\"submissions_is_enabled\":true,\"last_submissions_is_enabled\":true,\"similar_widget_is_enabled\":true,\"popular_widget_is_enabled\":true}'),
('notification_handlers', '{\"twilio_sid\":\"\",\"twilio_token\":\"\",\"twilio_number\":\"\",\"whatsapp_number_id\":\"\",\"whatsapp_access_token\":\"\",\"email_is_enabled\":true,\"webhook_is_enabled\":true,\"slack_is_enabled\":true,\"discord_is_enabled\":true,\"telegram_is_enabled\":true,\"microsoft_teams_is_enabled\":true,\"twilio_is_enabled\":false,\"twilio_call_is_enabled\":false,\"whatsapp_is_enabled\":false}'),
('license', '{"license":"Extended License","type":"Extended License"}'),
('support', '{"key": "", "expiry_datetime": "2100-01-01 00:00:00"}'),
('product_info', '{\"version\":\"3.0.0\", \"code\":\"300\"}');

-- SEPARATOR --

CREATE TABLE `tools_usage` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tool_id` varchar(64) DEFAULT NULL,
  `total_views` bigint UNSIGNED DEFAULT '0',
  `total_submissions` bigint UNSIGNED DEFAULT '0',
  `data` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tools_usage_tool_id_idx` (`tool_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `websites` (
  `website_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `domain_id` bigint UNSIGNED DEFAULT NULL,
  `scheme` varchar(8) DEFAULT NULL,
  `host` varchar(256) NOT NULL DEFAULT '',
  `settings` text,
  `score` tinyint UNSIGNED DEFAULT '0',
  `notifications` text,
  `total_archived_audits` bigint UNSIGNED DEFAULT '0',
  `total_audits` bigint UNSIGNED DEFAULT '0',
  `total_tests` bigint UNSIGNED DEFAULT '0',
  `total_issues` bigint UNSIGNED DEFAULT '0',
  `passed_tests` bigint UNSIGNED DEFAULT '0',
  `major_issues` bigint UNSIGNED DEFAULT '0',
  `moderate_issues` bigint UNSIGNED DEFAULT '0',
  `minor_issues` bigint UNSIGNED DEFAULT '0',
  `last_audit_datetime` datetime DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`website_id`),
  KEY `user_id` (`user_id`),
  KEY `websites_host_idx` (`host`) USING BTREE,
  KEY `websites_domains_domain_id_fk` (`domain_id`) USING BTREE,
  CONSTRAINT `websites_domains_domain_id_fk` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`domain_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `websites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `audits` (
  `audit_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `website_id` bigint UNSIGNED DEFAULT NULL,
  `domain_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `uploader_id` varchar(32) DEFAULT NULL,
  `host` varchar(256) DEFAULT NULL,
  `url` varchar(2048) DEFAULT NULL,
  `url_hash` varchar(32) DEFAULT NULL,
  `ttfb` float DEFAULT NULL,
  `response_time` float DEFAULT NULL,
  `average_download_speed` float DEFAULT NULL,
  `page_size` float DEFAULT NULL,
  `http_requests` smallint UNSIGNED DEFAULT NULL,
  `is_https` tinyint UNSIGNED DEFAULT NULL,
  `is_ssl_valid` tinyint UNSIGNED DEFAULT '0',
  `http_protocol` varchar(16) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `meta_description` varchar(521) DEFAULT NULL,
  `meta_keywords` varchar(521) DEFAULT NULL,
  `data` longtext,
  `issues` text,
  `settings` text,
  `notifications` text,
  `score` tinyint UNSIGNED DEFAULT '0',
  `total_tests` tinyint UNSIGNED DEFAULT '0',
  `passed_tests` tinyint UNSIGNED DEFAULT '0',
  `total_issues` tinyint UNSIGNED DEFAULT '0',
  `major_issues` tinyint UNSIGNED DEFAULT '0',
  `moderate_issues` tinyint UNSIGNED DEFAULT '0',
  `minor_issues` tinyint UNSIGNED DEFAULT '0',
  `total_refreshes` bigint UNSIGNED DEFAULT '0',
  `refresh_error` varchar(512) DEFAULT NULL,
  `next_refresh_datetime` datetime DEFAULT NULL,
  `last_refresh_datetime` datetime DEFAULT NULL,
  `expiration_datetime` datetime DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`audit_id`),
  KEY `website_id` (`website_id`),
  KEY `domain_id` (`domain_id`),
  KEY `audits_uploader_id_idx` (`uploader_id`) USING BTREE,
  KEY `audits_next_refresh_datetime_idx` (`next_refresh_datetime`) USING BTREE,
  KEY `audits_user_id_url_hash_idx` (`user_id`,`url_hash`) USING BTREE,
  CONSTRAINT `audits_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `audits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `audits_ibfk_3` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`domain_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `archived_audits` (
  `archived_audit_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `audit_id` bigint UNSIGNED NOT NULL,
  `website_id` bigint UNSIGNED DEFAULT NULL,
  `domain_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `uploader_id` varchar(32) DEFAULT NULL,
  `host` varchar(256) DEFAULT NULL,
  `url` varchar(2048) DEFAULT NULL,
  `ttfb` float DEFAULT NULL,
  `response_time` float DEFAULT NULL,
  `average_download_speed` float DEFAULT NULL,
  `page_size` float DEFAULT NULL,
  `http_requests` smallint UNSIGNED DEFAULT NULL,
  `is_https` tinyint UNSIGNED DEFAULT NULL,
  `is_ssl_valid` tinyint UNSIGNED DEFAULT '0',
  `http_protocol` varchar(16) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `meta_description` varchar(521) DEFAULT NULL,
  `meta_keywords` varchar(521) DEFAULT NULL,
  `data` longtext,
  `issues` text,
  `score` tinyint UNSIGNED DEFAULT '0',
  `total_tests` tinyint UNSIGNED DEFAULT '0',
  `passed_tests` tinyint UNSIGNED DEFAULT '0',
  `total_issues` tinyint UNSIGNED DEFAULT '0',
  `major_issues` tinyint UNSIGNED DEFAULT '0',
  `moderate_issues` tinyint UNSIGNED DEFAULT '0',
  `minor_issues` tinyint UNSIGNED DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  `expiration_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`archived_audit_id`),
  KEY `audit_id` (`audit_id`),
  KEY `website_id` (`website_id`),
  KEY `user_id` (`user_id`),
  KEY `domain_id` (`domain_id`),
  CONSTRAINT `archived_audits_ibfk_1` FOREIGN KEY (`audit_id`) REFERENCES `audits` (`audit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `archived_audits_ibfk_2` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `archived_audits_ibfk_3` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `archived_audits_ibfk_4` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `archived_audits_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `archived_audits_ibfk_6` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`domain_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE IF NOT EXISTS `codes` (
  `code_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days` int(10) UNSIGNED DEFAULT NULL,
  `plan_id` int(16) DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `discount` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `redeemed` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `plans_ids` text COLLATE utf8mb4_unicode_ci,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `processor` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan` text COLLATE utf8mb4_unicode_ci,
  `billing` text COLLATE utf8mb4_unicode_ci,
  `business` text COLLATE utf8mb4_unicode_ci,
  `taxes_ids` text COLLATE utf8mb4_unicode_ci,
  `base_amount` float DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `total_amount_default_currency` float DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` float DEFAULT NULL,
  `currency` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE IF NOT EXISTS `redeemed_codes` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE IF NOT EXISTS `taxes` (
  `tax_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `internal_name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `value_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('inclusive','exclusive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_type` enum('personal','business','both') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `countries` text COLLATE utf8mb4_unicode_ci,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `affiliates_commissions` (
  `affiliate_commission_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `referred_user_id` int DEFAULT NULL,
  `payment_id` bigint UNSIGNED DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `is_withdrawn` tinyint(4) UNSIGNED DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`affiliate_commission_id`),
  UNIQUE KEY `affiliate_commission_id` (`affiliate_commission_id`),
  KEY `user_id` (`user_id`),
  KEY `referred_user_id` (`referred_user_id`),
  KEY `payment_id` (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `affiliates_withdrawals` (
  `affiliate_withdrawal_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `note` varchar(1024) DEFAULT NULL,
  `affiliate_commissions_ids` text,
  `is_paid` tinyint(4) UNSIGNED DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`affiliate_withdrawal_id`),
  UNIQUE KEY `affiliate_withdrawal_id` (`affiliate_withdrawal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
