CREATE TABLE `acl_resource` (
  `id` INT (11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE = INNODB;

CREATE TABLE `acl_action` (
  `id` INT (11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `active` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE = INNODB;

CREATE TABLE `acl_permission` (
  `id` INT (11) NOT NULL AUTO_INCREMENT,
  `role_id` INT(11) NOT NULL,
  `resource_id` INT(11) NOT NULL,
  `action_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `resource_id` (`resource_id`),
  KEY `action_id` (`action_id`),
  CONSTRAINT `acl_permission_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`),
  CONSTRAINT `acl_permission_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `acl_resource` (`id`),
  CONSTRAINT `acl_permission_ibfk_3` FOREIGN KEY (`action_id`) REFERENCES `acl_action` (`id`)
) ENGINE = INNODB;