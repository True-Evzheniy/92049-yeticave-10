<?php
require_once('init.php');
if(!$is_auth) {
    header('HTTP/1.0 403 Forbidden');
    exit();
}
$user_id = $_SESSION['user']['id'];
$bets = get_bets_by_user_id($link, $user_id);

$my_bets_page = include_template('my-bets.php', compact('navigation', 'bets'));
$layout_data += [
    'title' => 'Мои ставки',
    'main_content' => $my_bets_page,
];

$layout_content = include_template('layout.php', $layout_data);
print($layout_content);


function get_bets_by_user_id($link, $id)
{
    $sql = "SELECT 
                b.amount, 
                b.lot,
                b.date,
                l.expiry_date,
                l.name,
                l.picture,
                c.name as category,
                l.winner = b.creator as win,
                DATEDIFF(l.expiry_date, CURDATE()) <= 0 as finished,
                CASE WHEN l.winner IS NOT NULL
                    THEN (SELECT users.contacts FROM users WHERE users.id = l.creator)
                END contacts
            FROM bets b
            INNER JOIN lots l ON b.lot = l.id
            INNER JOIN categories c on l.category = c.id
            WHERE 
                amount IN (SELECT MAX(amount) as amount from bets group by lot) 
                AND b.creator = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$id]);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res) {
        return $res->fetch_all(MYSQLI_ASSOC);
    }
    return null;
}

function get_bet_modifier($win, $finished) {
    if($win) {
        return 'rates__item--win';
    }
    return $finished ? 'rates__item--end' : '';
}

function get_timer_modifier($win, $finished, $finishing) {
    if($win) {
        return 'timer--win';
    }
    if($finished) {
        return 'timer--end';
    }
    return $finishing ? 'timer--finishing' : '';
}

function get_timer_label($win, $finished, $date) {
    if($win) {
        return 'Ставка выиграла';
    }
    if($finished) {
        return 'Торги окончены';
    }
    return get_timer($date);
}
