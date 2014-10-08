<?php
include '../common/connect.php';

$sql = "CREATE TABLE IF NOT EXISTS `news` (
            `id` INT(11) NOT NULL,
              `owner_id` INT(11) NULL DEFAULT NULL,
              `from_id` INT(11) NULL DEFAULT NULL,
              `signer_id` INT(11) NULL DEFAULT NULL,
              `date` INT(11) NULL DEFAULT NULL,
              `text` TEXT NULL DEFAULT NULL,
              PRIMARY KEY (`id`))
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci;

            CREATE TABLE IF NOT EXISTS `photo_news` (
            `id` INT(11) NOT NULL,
              `path` TEXT NULL DEFAULT NULL,
              `news_id` INT(11) NOT NULL,
              `text` TEXT NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              INDEX `fk_photo_news_news1_idx` (`news_id` ASC),
              CONSTRAINT `fk_photo_news_news1`
                FOREIGN KEY (`news_id`)
                REFERENCES `sportclass`.`news` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8
            COLLATE = utf8_general_ci;";

mysql_query($sql);