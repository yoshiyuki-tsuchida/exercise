CREATE  TABLE IF NOT EXISTS `user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  `password` VARCHAR(255) NOT NULL ,
  `salt` VARCHAR(255) NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  `birthday` DATE NOT NULL ,
  `privilege` INT NOT NULL DEFAULT 0, -- 0:一般ユーザー　1以降がadmin 
  `created_at` DATETIME NOT NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;
