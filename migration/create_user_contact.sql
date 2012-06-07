CREATE  TABLE IF NOT EXISTS `user_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `contact_title` VARCHAR(255) NOT NULL,
  `contact_body` TEXT NOT NULL,
  `contact_type` INT NOT NULL,
  `contact_content_url` VARCHAR(255),
  `contact_purchase_num` INT,
  `contact_status_id` INT NOT NULL DEFAULT 1,
  `contact_memo` TEXT,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE  TABLE IF NOT EXISTS `contact_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB;

insert into contact_status (name) value ("未処理");
insert into contact_status (name) value ("対応中");
insert into contact_status (name) value ("処理済");
