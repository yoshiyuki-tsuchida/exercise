CREATE  TABLE IF NOT EXISTS `content` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NOT NULL ,
  `author` VARCHAR(50) NOT NULL ,
  `price` INT NOT NULL DEFAULT 0 ,
  `category` VARCHAR(45) NULL ,
  `description` TEXT NULL ,
  `image_path` VARCHAR(255) NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;
