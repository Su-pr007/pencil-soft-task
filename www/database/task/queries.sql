CREATE DATABASE IF NOT EXISTS `pencil-soft-task` CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `pencil-soft-task`;

GRANT ALL PRIVILEGES ON `pencil-soft-task`.* TO 'pencil-soft-user'@'%';

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) DEFAULT NULL,
    `gender` SET('0', '1', '2') NOT NULL COMMENT '0 - не указан, 1 - мужчина, 2 - женщина.', -- Для оптимизации изменил выбранный тип данных
    `birth_date` INT(11) NOT NULL COMMENT 'Дата в unixtime.',
    PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `phone_numbers` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `phone` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Заполнение данными
INSERT INTO `users` (name, gender, birth_date) VALUES (
    'Маша', '2', 1009019569 /* 28.12.2001 -> 21 -> OK */
    ), (
    'Оля', '2', 1189854769 /* 15.09.2007 -> 16 */
    ), (
    'Коля', '1', 906203569 /* 19.09.1998 -> 24 */
    ), (
    'Иван', '1', 1009019569 /* 28.12.2001 -> 21 -> OK */
    ), (
    'Кристина', '2', 1076670769 /* 13.02.2004 -> 20 -> OK */
    );

INSERT INTO `phone_numbers` (user_id, phone) VALUES (
    '1', '8 987 654 32 10'
    ), (
    '1', '8 789 456 32 10'
    ), (
    '2', '8 654 123 78 90'
    ), (
    '3', '8 987 456 21 32'
    ), (
    '4', '8 963 852 14 78'
    ), (
    '5', '8 951 123 45 65'
    );
