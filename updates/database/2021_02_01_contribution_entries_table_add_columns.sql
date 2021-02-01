ALTER TABLE `contribution_entries` ADD `account_id` BIGINT(10) NOT NULL AFTER `id`;
ALTER TABLE `contribution_entries` ADD `expense_id` BIGINT(10) NULL AFTER `account_id`;