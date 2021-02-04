ALTER TABLE `units` ADD `base_unit` BIGINT(10) NULL AFTER `value`;
ALTER TABLE `units` CHANGE `operator` `operator` CHAR(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;