ALTER TABLE `single_track`.`nav_links` CHANGE COLUMN `link_hover_text` `link_hover_text` VARCHAR(255) NULL  , ADD COLUMN `order` INT NOT NULL DEFAULT 0  AFTER `editable` ;
