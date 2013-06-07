CREATE  TABLE `single_track`.`product` (
  `product_id` INT NOT NULL AUTO_INCREMENT ,
  `product_type_id` INT NOT NULL ,
  `product_parent_id` INT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `offsite_url` TEXT NULL ,
  `short_descr` VARCHAR(255) NULL ,
  `long_descr` TEXT NULL ,
  `logo_url` TEXT NULL ,
  `large_logo_url` TEXT NULL ,
  PRIMARY KEY (`product_id`) ,
  INDEX `fk_product_type_idx` (`product_type_id` ASC) ,
  CONSTRAINT `fk_product_type`
    FOREIGN KEY (`product_type_id` )
    REFERENCES `single_track`.`product_type` (`product_type_id` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT );
