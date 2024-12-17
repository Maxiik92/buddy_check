CREATE TABLE `user` (  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `first_name` VARCHAR(50) NOT NULL,
  `middle_name` VARCHAR(50),
  `last_name` VARCHAR(50) NOT NULL,
  `password` VARCHAR(150) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT NOW(),
  `updated` DATETIME,
  `deleted` INT(1) NOT NULL DEFAULT 0,
  `last_login` DATETIME,
  `logged` TINYINT(1) DEFAULT 0 NOT NULL,
  PRIMARY KEY (`id`) 
)ENGINE=InnoDB;