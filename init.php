<?php
require_once('./helpers.php');
date_default_timezone_set("Europe/Moscow");

$link = mysqli_connect('localhost', 'root', 'root', 'yeticave');
$link->set_charset('utf-8');

$categories = $link->query("SELECT * FROM categories;");
if ($categories) {
$categories = $categories->fetch_all(MYSQLI_ASSOC);
} else {
print_r($link->error);
}

$is_auth = rand(0, 1);
$user_name = 'Евгений Артамонов';
$categories = make_safe_data($categories);
$navigation = include_template('navigation.php', compact('categories'));
$layout_data = [
'navigation' => $navigation,
'user_name' => $user_name,
'is_auth' => $is_auth,
];