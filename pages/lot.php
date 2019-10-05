<?php
require_once('../functions/init.php');
$errors = [];
$id = null;
$bets = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$lot = get_lot_by_id($id, $link);
$bets = get_bets_for_lot($id, $link);
$visible_form =
    $is_auth &&
    is_active_lot($lot) &&
    !is_user_creator($lot, $is_auth) &&
    !is_last_betting_user_current($bets);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$visible_form) {
        header('HTTP/1.0 403 Forbidden');
        $error_title = '403 Доступ запрещен';
        $error_message = 'Нет прав на совершение операции';
        $error_page = include_template('error.php', compact('navigation', 'error_title', 'error_message'));
        $layout_data += [
            'main_content' => $error_page,
            'title' => $error_title,
        ];
        http_response_code(403);
        print(include_template('layout.php', $layout_data));
        exit();
    }
    $errors['cost'] =
        validate_filling('cost', $_POST) ??
        validate_currency($_POST['cost']) ??
        validate_min_bet($_POST['cost'], $lot['min_bet']);
    if ($errors['cost'] === null) {
        $sql = "INSERT INTO bets(amount, user_id, lot_id) VALUES (?, ?, ?)";
        $stmt = db_get_prepare_stmt($link, $sql, [get_float_from_currency_string($_POST['cost']), $_SESSION['user']['id'], $id]);
        $stmt->execute();
        $lot = get_lot_by_id($id, $link);
        $bets = get_bets_for_lot($id, $link);
        $visible_form = false;
    };
}

if ($lot) {
    $lot_page = include_template('lot.php', compact('lot', 'errors', 'bets', 'navigation', 'visible_form'));
    $layout_data += [
        'main_content' => $lot_page,
        'title' => $lot['name'],
    ];
} else {
    $error_title = '404 Страница не найдена';
    $error_message = 'Данной страницы не существует на сайте.';
    $error_page = include_template('error.php', compact('navigation', 'error_title', 'error_message'));
    $layout_data += [
        'main_content' => $error_page,
        'title' => 'Страница не найдена',
    ];
    http_response_code(404);
}

$layout_content = include_template('layout.php', $layout_data);

print($layout_content);

/**
 * @param $id
 * @param $link mysqli
 * @return false | array
 */
function get_lot_by_id($id, $link)
{
    $out = false;
    $sql = "SELECT COALESCE(MAX(b.amount), lots.start_price) as current_price,
            (COALESCE(MAX(b.amount), lots.start_price) + bet_step) as min_bet,
               bet_step,
               lots.name,
               start_price,
               expiry_date,
               picture,
               categories.name as category,
               description,
               lots.user_id
        FROM lots
        LEFT JOIN categories ON lots.category_id = categories.id
        LEFT JOIN bets b ON lots.id = b.lot_id
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
    return $out;
}

/**
 * @param $id
 * @param $link
 * @return bool|mixed
 */
function get_bets_for_lot($id, $link)
{
    $sql = "SELECT amount, name, date, user_id FROM bets 
    INNER JOIN users ON users.id = bets.user_id 
    WHERE lot_id = ? ORDER BY date DESC;";
    $stmt = db_get_prepare_stmt($link, $sql, [$id]);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res) {
        return $res->fetch_all(MYSQLI_ASSOC);
    }
    return false;
}

/**
 * @param $value
 * @return string|null
 */
function validate_currency($value)
{
    $cost = get_float_from_currency_string($value);
    return validate_positive_integer($cost);
}

/**
 * @param $value
 * @param $min_bet
 * @return string|null
 */
function validate_min_bet($value, $min_bet)
{
    if (get_float_from_currency_string($value) < $min_bet) {
        return "Минимальная ставка {$min_bet}";
    }
    return null;
}

/**
 * @param $lot
 * @return bool
 */
function is_active_lot($lot)
{
    $finish_date = strtotime($lot['expiry_date']);
    $now = time();
    return $now < $finish_date;
}

/**
 * @param $lot
 * @param $is_auth
 * @return bool
 */
function is_user_creator($lot, $is_auth)
{
    if (isset($_SESSION['user'])) {
        return $is_auth && $lot['user_id'] == $_SESSION['user']['id'];
    }
    return false;
}

/**
 * @param array $bets
 * @return bool
 */
function is_last_betting_user_current($bets)
{
    if (count($bets) && isset($_SESSION['user'])) {
        return $bets[0]['user_id'] == $_SESSION['user']['id'];
    }
    return false;
}
