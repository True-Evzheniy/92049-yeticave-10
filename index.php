<?php
require_once('./init.php');
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

$lots = make_safe_data($lots);
$main_content = include_template('main.php', compact('categories', 'lots'));
$layout_data += [
    'main_content' => $main_content,
    'title' => 'Главная страница',
];
$layout_content = include_template('layout.php', $layout_data);

print($layout_content);
