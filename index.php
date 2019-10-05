<?php
require_once('./init.php');
$lots = $link->query("SELECT
    COALESCE(MAX(bets.amount), lots.start_price) price,
    lots.id,
    lots.name,
    picture,
    description,
    c.name as category,
    expiry_date,
    COUNT(bets.id) as bet_count
FROM lots
     LEFT JOIN bets ON lots.id = bets.lot
     LEFT JOIN categories c ON lots.category = c.id
WHERE expiry_date > NOW()
GROUP BY bets.lot, lots.id
ORDER BY lots.id DESC");
if ($lots) {
    $lots = $lots->fetch_all(MYSQLI_ASSOC);
}

$lots = make_safe_data($lots);
$main_content = include_template('main.php', compact('categories', 'lots'));
$layout_data += [
    'main_content' => $main_content,
    'title' => 'Главная страница',
];
$layout_content = include_template('layout.php', $layout_data);

print($layout_content);
