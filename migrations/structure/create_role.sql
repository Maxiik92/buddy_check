CREATE TABLE `role` (  
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100),
  PRIMARY KEY (`id`) 
)ENGINE=InnoDB;

CREATE TABLE `user_x_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_x_role_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `user_x_role_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `user_x_role_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)
)ENGINE=InnoDB;