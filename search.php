<?php
require_once('init.php');
$search = null;
$lots = [];
$page_items = 9;
$cur_page = $_GET['page'] ?? 1;
if (isset($_GET['search'])) {
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
    COUNT(bets.id) as bet_count
FROM lots
     LEFT JOIN bets ON lots.id = bets.lot_id
     LEFT JOIN categories c ON lots.category_id = c.id
WHERE MATCH(lots.name, lots.description) AGAINST(?)
GROUP BY bets.lot_id, lots.id
ORDER BY lots.id DESC";
$stmt = db_get_prepare_stmt($link, $sql, [$search]);
$stmt->execute();
$res = $stmt->get_result();
if ($res) {
    $items_count = $res->num_rows;
    $offset = ($cur_page - 1) * $page_items;
    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);
    $sql = "SELECT
    COALESCE(MAX(bets.amount), lots.start_price) price,
    lots.id,
    lots.name,
    picture,
    description,
    c.name as category,
    expiry_date,
    COUNT(bets.id) as bet_count
FROM lots
     LEFT JOIN bets ON lots.id = bets.lot_id
     LEFT JOIN categories c ON lots.category_id = c.id
WHERE MATCH(lots.name, lots.description) AGAINST(?)
GROUP BY bets.lot_id, lots.id
ORDER BY lots.id DESC
LIMIT ? OFFSET ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$search, $page_items, $offset]);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res) {
        $lots = $res->fetch_all(MYSQLI_ASSOC);
    }

    $pagination = [
        'cur_page' => $cur_page,
        'pages' => $pages,
        'pages_count' => $pages_count,
        'path' => 'search.php'
    ];
}


$search_page = include_template('search.php', compact('navigation', 'search', 'lots', 'pagination'));
$layout_data += [
    'main_content' => $search_page,
    'title' => 'Результаты поиска',
    'search' => $search,
];

print(include_template('layout.php', $layout_data));
