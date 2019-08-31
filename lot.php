<?php
require_once('./init.php');

$lot = get_lot($link);
if($lot) {
    $lot_page = include_template('lot.php', compact('lot'));
    $layout_data += [
        'main_content' => $lot_page,
        'title' => $lot['name'],
    ];
} else {
    $error_page = include_template('404.php', compact('navigation'));
    $layout_data += [
        'main_content' => $error_page,
        'title' => 'Cтраница не найдена',
    ];
    http_response_code(404);
}

$layout_content = include_template('layout.php', $layout_data);

print($layout_content);

/**
 * @param $link mysqli
 * @return false | array
 */
function get_lot($link)
{
    $out = false;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT COALESCE(MAX(b.amount), lots.start_price) as current_price,
                (COALESCE(MAX(b.amount), lots.start_price) + bet_step) as min_bet,
                   bet_step,
                   lots.name,
                   start_price,
                   expiry_date,
                   picture,
                   categories.name as category,
                   description
            FROM lots
            LEFT JOIN categories ON lots.category = categories.id
            LEFT JOIN bets b ON lots.id = b.lot
            WHERE lots.id = ?
            GROUP BY lots.id;";

        $link->prepare($sql);
        $stmt = db_get_prepare_stmt($link, $sql, [$id]);
        $stmt->execute();
        $lot = $stmt->get_result();
        if ($lot) {
            $lot = $lot->fetch_all(MYSQLI_ASSOC);
            if (count($lot) === 1) {
                return $lot[0];
            }
        }
    }
    return $out;
}
