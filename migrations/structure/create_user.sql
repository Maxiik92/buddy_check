CREATE TABLE `user` (
  `id` INT (11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `deleted` INT (1) NOT NULL DEFAULT 0,
  `email` VARCHAR(100) NOT NULL,
  `registered` DATETIME NOT NULL DEFAULT NOW(),
  `updated` DATETIME,
  `last_login` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE = INNODB;