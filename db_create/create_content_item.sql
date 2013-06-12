CREATE  TABLE `single_track`.`content_item` (
  `content_item_id` INT NOT NULL AUTO_INCREMENT ,
  `content_item_location_id` INT NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NOT NULL ,
  `bg_image_url` TEXT NOT NULL ,
  `bg_image_alt` VARCHAR(255) NOT NULL ,
  `order_num` DOUBLE NOT NULL DEFAULT 0,
  PRIMARY KEY (`content_item_id`) ,
  INDEX `fk_content_item_location_idx` (`content_item_location_id` ASC) ,
  CONSTRAINT `fk_content_item_location`
    FOREIGN KEY (`content_item_location_id` )
    REFERENCES `single_track`.`content_item_location` (`content_item_location_id` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT);
