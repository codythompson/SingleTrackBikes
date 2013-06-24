drop table if exists single_track.misc_text;

CREATE  TABLE `single_track`.`misc_text` (
  `misc_text_name` VARCHAR(255) NOT NULL ,
  `descr` TEXT NOT NULL ,
  `value` TEXT NOT NULL ,
  PRIMARY KEY (`misc_text_name`) ,
  UNIQUE INDEX `misc_text_name_UNIQUE` (`misc_text_name` ASC) );
