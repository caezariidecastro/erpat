CREATE TABLE IF NOT EXISTS `purchase_order_returns` (
  `id` bigint(10) NOT NULL,
  `purchase_id` bigint(10) NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;