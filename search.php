<?php
require_once('init.php');
$search = null;
$lots = [];
if(isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$sql = "SELECT
    COALESCE(MAX(bets.amount), lots.start_price) price,
    lots.id,
    lots.name,
    picture,
    description,
    c.name as category,
    expiry_date,
    COUNT(bets.id) as bet_count,
    MATCH(lots.name, lots.description) AGAINST(?) as score
FROM lots
     LEFT JOIN bets ON lots.id = bets.lot
     LEFT JOIN categories c ON lots.category = c.id
WHERE MATCH(lots.name, lots.description) AGAINST(?)
GROUP BY bets.lot, lots.id
ORDER BY score DESC";
$stmt = db_get_prepare_stmt($link, $sql, [$search, $search]);
$stmt->execute();
$res = $stmt->get_result();
if ($res) {
    $lots = $res->fetch_all(MYSQLI_ASSOC);
}


$search_page = include_template('search.php', compact('navigation', 'search', 'lots'));
$layout_data += [
    'main_content' => $search_page,
    'title' => 'Результаты поиска',
    'search' => $search,
];

print(include_template('layout.php', $layout_data));
