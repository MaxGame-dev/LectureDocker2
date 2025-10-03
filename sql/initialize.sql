CREATE USER IF NOT EXISTS 'data_user'@'localhost' IDENTIFIED BY 'data';
GRANT ALL PRIVILEGES ON * . * TO 'data_user'@'localhost';

CREATE USER IF NOT EXISTS 'data_user'@'%' IDENTIFIED BY 'data';
GRANT ALL PRIVILEGES ON * . * TO 'data_user'@'%';
alter user 'data_user'@'%' identified with mysql_native_password by 'data';

DROP DATABASE IF EXISTS lecture_db;
CREATE DATABASE IF NOT EXISTS lecture_db;

use lecture_db;

DROP TABLE IF EXISTS items;
CREATE TABLE IF NOT EXISTS items (
    item_id INT PRIMARY KEY,
    item_name VARCHAR(255),
    item_image VARCHAR(255)
);

insert into items (item_id, item_name, item_image) values (1, '神レアアイテム', 'f048.png');
insert into items (item_id, item_name, item_image) values (2, '超レアアイテム', 'f042.png');
insert into items (item_id, item_name, item_image) values (3, 'レアアイテム', 'f041.png');
insert into items (item_id, item_name, item_image) values (4, '普通のアイテム', 'f040.png');
insert into items (item_id, item_name, item_image) values (5, 'ガラクタ', 'f039.png');

DROP TABLE IF EXISTS gacha_items;
CREATE TABLE IF NOT EXISTS gacha_items (
    gacha_id INT,
    item_id INT,
    weight INT,
    PRIMARY KEY (gacha_id, item_id)
);

insert into gacha_items (gacha_id, item_id, weight) values (1, 1, 10);
insert into gacha_items (gacha_id, item_id, weight) values (1, 2, 90);
insert into gacha_items (gacha_id, item_id, weight) values (1, 3, 200);
insert into gacha_items (gacha_id, item_id, weight) values (1, 4, 300);
insert into gacha_items (gacha_id, item_id, weight) values (1, 5, 400);

DROP TABLE IF EXISTS gacha_histories;
CREATE TABLE IF NOT EXISTS gacha_histories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    history_id INT,
    gacha_id INT,
    item_id INT,
    INDEX idx_history_id (history_id) 
);
