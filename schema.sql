CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(128) NOT NULL,
    symbol_code VARCHAR(128) NOT NULL
);

CREATE TABLE lots
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    creation_date  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    name           VARCHAR(128) NOT NULL,
    description    TINYTEXT     NOT NULL,
    picture        VARCHAR(128) NOT NULL,
    start_price    INT          NOT NULL,
    expiry_date    DATE         NOT NULL,
    bet_step       INT          NOT NULL,
    user_id        INT          NOT NULL,
    winner_user_id INT,
    category_id    INT          NOT NULL
);

CREATE TABLE bets
(
    id      INT AUTO_INCREMENT PRIMARY KEY,
    date    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    amount  INT NOT NULL,
    user_id INT NOT NULL,
    lot_id  INT NOT NULL
);

CREATE TABLE users
(
    id                INT AUTO_INCREMENT PRIMARY KEY,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email             VARCHAR(128) UNIQUE NOT NULL,
    name              VARCHAR(64)         NOT NULL,
    password          VARCHAR(64)         NOT NULL,
    contacts          TINYTEXT            NOT NULL
);


CREATE INDEX symbol_code ON categories (symbol_code);

CREATE INDEX category ON lots (category_id);
CREATE INDEX winner ON lots (winner_user_id);
CREATE INDEX creator ON lots (user_id);
CREATE INDEX creation_date ON lots (creation_date);
CREATE INDEX expiry_date ON lots (expiry_date);
CREATE FULLTEXT INDEX search on lots (name, description);

CREATE INDEX creator ON bets (user_id);
CREATE INDEX lot ON bets (lot_id);
CREATE INDEX amount ON bets (amount);


