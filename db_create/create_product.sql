CREATE  TABLE `single_track`.`product` (
  `product_id` INT NOT NULL AUTO_INCREMENT ,
  `parent_product_id` INT NULL ,
  `product_style_id` INT NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `descr` TEXT NULL ,
  `offsite_url` TEXT NULL ,
  `offsite_url_text` VARCHAR(255) NULL ,
  `image_url` TEXT NULL ,
  `background_image_url` TEXT NULL ,
  PRIMARY KEY (`product_id`) ,
  INDEX `fk_product_style_idx` (`product_style_id` ASC) ,
  CONSTRAINT `fk_product_style`
    FOREIGN KEY (`product_style_id` )
    REFERENCES `single_track`.`product_style` (`product_style_id` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT);
