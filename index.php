<?php
require_once('./helpers.php');
date_default_timezone_set("Europe/Moscow");

$link = mysqli_connect('localhost', 'root', 'root', 'yeticave');
$link->set_charset('utf-8');

$lots = $link->query("SELECT 
        picture,
        lots.name,
        start_price as price,
        picture, amount,
        c.name as category,
        expiry_date
FROM lots
    LEFT JOIN bets ON lots.id = bets.lot
    LEFT JOIN categories c ON lots.category = c.id
WHERE expiry_date > NOW()
ORDER BY lots.id DESC;");
if ($lots) {
    $lots = $lots->fetch_all(MYSQLI_ASSOC);
} else {
    print_r($link->error);
}
$categories = $link->query("SELECT * FROM categories;");
if ($categories) {
    $categories = $categories->fetch_all(MYSQLI_ASSOC);
} else {
    print_r($link->error);
}

$is_auth = rand(0, 1);
$user_name = 'Евгений Артамонов';
$categories = make_safe_data($categories);
$lots = make_safe_data($lots);
$main_data = ['categories' => $categories, 'lots' => $lots];
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
