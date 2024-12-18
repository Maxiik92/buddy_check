CREATE TABLE `menu` (
  `id` INT (11) NOT NULL AUTO_INCREMENT,
  `parent_id` INT (11) DEFAULT NULL,
  `link` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `order` int(10) DEFAULT 100,
  `allowed_group` VARCHAR(100),
  `icon` VARCHAR(255) DEFAULT NULL,
  `active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE = INNODB;