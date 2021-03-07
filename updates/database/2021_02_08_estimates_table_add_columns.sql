ALTER TABLE `estimates` ADD `type` ENUM('service','product') NOT NULL DEFAULT 'service' AFTER `project_id`;
ALTER TABLE `estimates` ADD `consumer_id` BIGINT(10) NULL AFTER `client_id`;