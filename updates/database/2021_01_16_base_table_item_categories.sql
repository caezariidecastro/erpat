CREATE TABLE IF NOT EXISTS `item_categories` (
`id` bigint(10) NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`description` text NOT NULL,
`created_on` datetime NOT NULL,
`created_by` bigint(10) NOT NULL,
`deleted` tinyint(4) NOT NULL DEFAULT 0,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;