ALTER TABLE `vehicles` ADD `plate_number` VARCHAR(255) NOT NULL AFTER `no_of_wheels`, ADD `max_cargo_weight` VARCHAR(255) NOT NULL AFTER `plate_number`;