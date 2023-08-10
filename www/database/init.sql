USE `pencil-soft-db`;

CREATE TABLE expense(
    `id` INT NOT NULL AUTO_INCREMENT,
    `date` DATE,
    `sum` INT,
    `comment` VARCHAR(255),
    PRIMARY KEY ( id )
);
