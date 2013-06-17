drop table if exists `single_track`.`nav_links`;

CREATE  TABLE `single_track`.`nav_links` (
  `nav_link_id` INT NOT NULL AUTO_INCREMENT ,
  `parent_nav_link_id` INT NULL ,
  `link_url` TEXT NOT NULL ,
  `link_text` VARCHAR(255) NOT NULL ,
  `link_hover_text` VARCHAR(255) NULL ,
  `editable` BIT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`nav_link_id`) );
