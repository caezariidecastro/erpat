CREATE TABLE IF NOT EXISTS `asset_categories` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT ,
  `parent_id` bigint(10) NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
