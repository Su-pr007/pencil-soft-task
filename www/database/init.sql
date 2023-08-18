SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

CREATE DATABASE IF NOT EXISTS `pencil-soft-db` CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `pencil-soft-db`;

CREATE TABLE IF NOT EXISTS `expense` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `date` DATE,
    `sum` INT UNSIGNED,
    `comment` VARCHAR(255),
    PRIMARY KEY ( id )
);
