CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories
(
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        CHAR(128) NOT NULL,
  symbol_code CHAR(128) NOT NULL
);

CREATE TABLE lots
(
  id              INT AUTO_INCREMENT PRIMARY KEY,
  creation_date   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name            CHAR(128) NOT NULL,
  description     TINYTEXT  NOT NULL,
  picture         CHAR(128) NOT NULL,
  start_price     INT       NOT NULL,
  completion_date DATE      NOT NULL,
  bet_step        INT       NOT NULL,
  creator         INT       NOT NULL,
  winner          INT,
  category        INT       NOT NULL
);

CREATE TABLE bets
(
  id      INT AUTO_INCREMENT PRIMARY KEY,
  date    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  amount  INT NOT NULL,
  creator INT NOT NULL,
  lot     INT NOT NULL
);

CREATE TABLE users
(
  id                INT AUTO_INCREMENT PRIMARY KEY,
  registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email             CHAR(128) UNIQUE NOT NULL,
  name              CHAR(64)         NOT NULL,
  password          CHAR(64)         NOT NULL,
  avatar            CHAR(128)        NOT NULL,
  contacts          TINYTEXT         NOT NULL
);


CREATE INDEX symbol_code ON categories(symbol_code);

CREATE INDEX category ON lots(category);
CREATE INDEX winner ON lots(winner);
CREATE INDEX creator ON lots(creator);

CREATE INDEX creator ON bets(creator);
CREATE INDEX lot ON bets(lot);


