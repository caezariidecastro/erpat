CREATE TABLE IF NOT EXISTS `purchase_order_budgets` (
  `id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `purchase_id` bigint(10) NOT NULL,
  `account_id` bigint(10) DEFAULT NULL,
  `created_by` int(11) DEFAULT 1,
  `created_on` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;