CREATE TABLE IF NOT EXISTS `purchase_order_materials` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `quantity` double NOT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `material_id` bigint(10) NOT NULL,
  `material_inventory_id` bigint(10) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;