<?php
require_once('./helpers.php');
require_once('./config.php');
require_once('./mail.php');
session_start();
date_default_timezone_set("Europe/Moscow");

$link = mysqli_connect($config['db_host'], $config['db_user'], $config['db_password'], $config['db_name']);
$link->set_charset('utf-8');

$categories = $link->query("SELECT * FROM categories;");
if ($categories) {
    $categories = $categories->fetch_all(MYSQLI_ASSOC);
} else {
    print_r($link->error);
}

$is_auth = !empty($_SESSION);
$user_name = $is_auth ? $_SESSION['user']['name'] : null;

$categories = make_safe_data($categories);
$navigation = include_template('navigation.php', compact('categories'));
$layout_data = [
    'navigation' => $navigation,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
];

$winners = set_winners($link);
send_email_to_winners($winners, $config['domain']);
