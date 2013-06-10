CREATE  TABLE `single_track`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT ,
  `user_name` VARCHAR(40) NOT NULL ,
  `user_email` VARCHAR(250) NOT NULL ,
  `user_salt` VARCHAR(32) NOT NULL ,
  `user_hash` VARCHAR(80) NOT NULL ,
  PRIMARY KEY (`user_id`) );
