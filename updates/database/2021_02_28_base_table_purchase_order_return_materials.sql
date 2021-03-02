CREATE TABLE IF NOT EXISTS `purchase_order_return_materials` (
  `id` bigint(10) NOT NULL,
  `purchase_order_return_id` bigint(10) NOT NULL,
  `purchase_order_material_id` bigint(10) NOT NULL,
  `quantity` double NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;