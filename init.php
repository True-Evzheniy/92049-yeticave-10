<?php
require_once('./helpers.php');
session_start();
date_default_timezone_set("Europe/Moscow");

$link = mysqli_connect('localhost', 'root', 'root', 'yeticave');
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

/* set winners */
$sql = 'UPDATE lots INNER JOIN (SELECT bets.lot, bets.creator
                        from bets
                                 INNER JOIN (SELECT MAX(date) AS date, lot FROM bets GROUP BY lot) w
                                            ON w.lot = bets.lot AND w.date = bets.date) u ON u.lot = lots.id
SET lots.winner = u.creator
WHERE DATEDIFF(lots.expiry_date, CURDATE()) <= 0 AND lots.winner IS NULL';
$link->query($sql);
