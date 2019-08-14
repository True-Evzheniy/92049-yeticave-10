<?php
require_once('./helpers.php');
date_default_timezone_set("Europe/Moscow");

$is_auth = rand(0, 1);
$user_name = 'Евгений Артамонов';
$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$lots = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи	',
        'price' => 10999,
        'picture_url' => 'img/lot-1.jpg',
        'expiry_date' => '2019-08-15',
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'price' => 159999,
        'picture_url' => 'img/lot-2.jpg',
        'expiry_date' => '2019-08-16',
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'price' => 8000,
        'picture_url' => 'img/lot-3.jpg',
        'expiry_date' => '2019-08-17',
    ],
    [
        'name' => 'img/lot-3.jpg',
        'category' => 'Ботинки',
        'price' => 10999,
        'picture_url' => 'img/lot-4.jpg',
        'expiry_date' => '2019-08-18',
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'price' => 7500,
        'picture_url' => 'img/lot-5.jpg',
        'expiry_date' => '2019-08-19',
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'price' => 5400,
        'picture_url' => 'img/lot-6.jpg',
        'expiry_date' => '2019-08-20',
    ],
];

$categories = make_safe_data($categories);
$lots = make_safe_data($lots);
$main_data= ['categories' => $categories, 'lots' => $lots];
$main_content = include_template('main.php', $main_data);
$layout_data = [
    'main_content' => $main_content,
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'title' => 'Главная страница',
];
$layout_content = include_template('layout.php', $layout_data);

print($layout_content);
