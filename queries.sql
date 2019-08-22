# Fill categories
INSERT INTO categories
SET name        = 'Доски и лыжи',
    symbol_code ='boards';
INSERT INTO categories
SET name        = 'Крепления',
    symbol_code = 'attachment';
INSERT INTO categories
SET name        = 'Ботинки',
    symbol_code = 'boots';
INSERT INTO categories
SET name        = 'Одежда',
    symbol_code = 'clothing';
INSERT INTO categories
SET name        = 'Инструменты',
    symbol_code = 'tools';
INSERT INTO categories
SET name        = 'Разное',
    symbol_code = 'other';

# Fill a few users
INSERT INTO users
SET email    ='vasyafrommoscow@yandex.ru',
    name     = 'Вася',
    password = 'qwerty',
    avatar   = '/img/vasya.jpg',
    contacts = 'Москва';
INSERT INTO users
SET email    ='petya@mail.ru',
    name     = 'Петя',
    password = 'strong9password',
    avatar   = '/img/petya.jpg',
    contacts = 'Воркута';
INSERT INTO users
SET email    ='maria@gmail.com',
    name     = 'Маша',
    password = 'mybirthday2',
    avatar   = '/img/masha.jpg',
    contacts = 'Новосибирск';

# Fill lots
INSERT INTO lots
SET name            = '2014 Rossignol District Snowboard',
    start_price     = 10999,
    picture         = 'img/lot-1.jpg',
    completion_date = STR_TO_DATE('2019-08-15', '%Y-%m-%d'),
    description     = 'asdfsdf',
    creator         = 1,
    category        = 1,
    bet_step        = 1;
INSERT INTO lots
SET name            = 'DC Ply Mens 2016/2017 Snowboard',
    start_price     = 159999,
    picture         = 'img/lot-2.jpg',
    completion_date = STR_TO_DATE('2019-08-16', '%Y-%m-%d'),
    description     = 'Lorem i t.d.',
    creator         = 2,
    category        = 1,
    bet_step        = 2;
INSERT INTO lots
SET name            = 'Крепления Union Contact Pro 2015 года размер L/XL',
    start_price     = 159999,
    picture         = 'img/lot-3.jpg',
    completion_date = STR_TO_DATE('2019-08-17', '%Y-%m-%d'),
    description     = 'Ipsum and more words',
    creator         = 1,
    category        = 1,
    bet_step        = 2;
INSERT INTO lots
SET name            = 'Ботинки для сноуборда DC Mutiny Charocal',
    start_price     = 159999,
    picture         = 'img/lot-4.jpg',
    completion_date = STR_TO_DATE('2019-08-18', '%Y-%m-%d'),
    description     = 'Ipsum and more words',
    creator         = 2,
    category        = 3,
    bet_step        = 3;
INSERT INTO lots
SET name            = 'Куртка для сноуборда DC Mutiny Charocal',
    start_price     = 7500,
    picture         = 'img/lot-5.jpg',
    completion_date = STR_TO_DATE('2019-08-19', '%Y-%m-%d'),
    description     = 'Ipsum and more words',
    creator         = 2,
    category        = 4,
    bet_step        = 4;
INSERT INTO lots
SET name            = 'Маска Oakley Canopy',
    start_price     = 5400,
    picture         = 'img/lot-6.jpg',
    completion_date = STR_TO_DATE('2019-08-20', '%Y-%m-%d'),
    description     = 'Ipsum and more words',
    creator         = 3,
    category        = 6,
    bet_step        = 3;

# Fill bets
INSERT INTO bets
SET amount  = 5430,
    lot     = 6,
    creator = 1;
INSERT INTO bets
SET amount  = 12000,
    lot     = 1,
    creator = 2;
INSERT INTO bets
SET amount  = 170000,
    lot     = 2,
    creator = 3;
