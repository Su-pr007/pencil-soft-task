CREATE DATABASE IF NOT EXISTS `pencil-soft-db`;

USE `pencil-soft-db`;

CREATE TABLE IF NOT EXISTS expense(
    `id` INT NOT NULL AUTO_INCREMENT,
    `date` DATE,
    `sum` INT UNSIGNED,
    `comment` VARCHAR(255),
    PRIMARY KEY ( id )
);
