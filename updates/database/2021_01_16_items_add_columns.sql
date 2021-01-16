ALTER TABLE `items` ADD `created_on` DATETIME NOT NULL AFTER `active`, ADD `created_by` BIGINT(10) NOT NULL AFTER `created_on`;
ALTER TABLE `items` ADD `category` BIGINT(10) NOT NULL AFTER `active`;