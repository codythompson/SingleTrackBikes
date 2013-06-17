drop table if exists `single_track`.`footer_links`;

CREATE  TABLE `single_track`.`footer_links` (
  `footer_link_id` INT NOT NULL AUTO_INCREMENT ,
  `link_url` TEXT NOT NULL ,
  `link_text` VARCHAR(255) NOT NULL ,
  `order` INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`footer_link_id`) );
