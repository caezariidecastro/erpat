ALTER TABLE `vendors` ADD `status` ENUM('active','inactive') NOT NULL DEFAULT 'active' AFTER `country`;