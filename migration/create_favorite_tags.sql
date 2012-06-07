CREATE  TABLE IF NOT EXISTS `favorite_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `favorite_id` INT NOT NULL,
  `tags` VARCHAR(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB;
