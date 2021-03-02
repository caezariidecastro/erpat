CREATE TABLE IF NOT EXISTS `purchase_order_materials` (
  `id` bigint(10) NOT NULL,
  `vendor_id` bigint(10) NOT NULL,
  `remarks` text NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` bigint(10) NOT NULL,
  `status` enum('draft','completed','cancelled','partially_budgeted') NOT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `cancelled_by` bigint(10) DEFAULT NULL,
  `last_email_sent_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;