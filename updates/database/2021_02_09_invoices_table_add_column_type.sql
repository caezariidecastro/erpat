ALTER TABLE `estimates` ADD `type` ENUM('service','product') NOT NULL DEFAULT 'service' AFTER `project_id`;