drop table if exists single_track.announcements;

CREATE  TABLE `single_track`.`announcements` (
  `anouncement_id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(255) NULL ,
  `text` TEXT NOT NULL ,
  `date` DATETIME NOT NULL ,
  PRIMARY KEY (`anouncement_id`) );
