CREATE  TABLE `single_track`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT ,
  `user_name` VARCHAR(45) NOT NULL ,
  `user_email` VARCHAR(255) NOT NULL ,
  `user_sec_q` TEXT NOT NULL ,
  `user_sec_q_a` TEXT NOT NULL ,
  `user_salt` VARCHAR(16) NOT NULL ,
  `user_hash` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`user_id`) );
