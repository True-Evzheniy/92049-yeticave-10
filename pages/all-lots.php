<?php
require_once('../functions/init.php');

$lots = [];
$category = null;
$category_name = null;
$page_items = 9;
$cur_page = $_GET['page'] ?? 1;

if (isset($_GET['category'])) {
    $category = $_GET['category'];
    $category_name = get_category_name($categories, $category);
};

if (!$category_name) {
    $error_title = '404 Страница не найдена';
    $error_message = 'Данной категории не существует.';
    $error_page = include_template('error.php', compact('navigation', 'error_title', 'error_message'));
    $layout_data += [
        'main_content' => $error_page,
        'title' => 'Страница не найдена',
    ];
    http_response_code(404);
    print(include_template('layout.php', $layout_data));
    exit();
}

$sql = '
    SELECT * from lots
        LEFT JOIN categories c ON lots.category_id = c.id
    WHERE c.symbol_code = ?';
$stmt = db_get_prepare_stmt($link, $sql, [$category]);
$stmt->execute();
$res = $stmt->get_result();

if ($res) {
    $items_count = $res->num_rows;
    $offset = ($cur_page - 1) * $page_items;
    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);

    $sql = '
    SELECT 
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
    WHERE c.symbol_code = ?
    GROUP BY bets.lot_id, lots.id
    ORDER BY lots.id DESC
    LIMIT ? OFFSET ?';
    $stmt = db_get_prepare_stmt($link, $sql, [$category, $page_items, $offset]);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res) {
        $lots = $res->fetch_all(MYSQLI_ASSOC);
    }
    $pagination = [
        'cur_page' => $cur_page,
        'pages' => $pages,
        'pages_count' => $pages_count,
        'path' => 'category.php'
    ];
}
$all_lots_page = include_template('all-lots.php', compact('navigation', 'pagination', 'lots', 'category_name'));
$layout_data += [
    'main_content' => $all_lots_page,
    'title' => 'Результаты поиска',
];

print(include_template('layout.php', $layout_data));

function get_category_name($categories, $symbol_code)
{
    $name = null;
    foreach ($categories as $item) {
        if ($item['symbol_code'] === $symbol_code) {
            $name = $item['name'];
        }
    }
    return $name;
}



