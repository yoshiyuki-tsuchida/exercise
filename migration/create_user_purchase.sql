CREATE  TABLE IF NOT EXISTS `user_purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `content_id` INT NOT NULL,
  `purchase_price` INT NOT NULL,
  `purchase_type` INT NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB;
