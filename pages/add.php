<?php
require_once('../functions/init.php');
if(!$is_auth) {
    header('HTTP/1.0 403 Forbidden');
    $error_title = '403 Доступ запрещен';
    $error_message = 'Добавление лотов запрещено неавторизованным пользователям';
    $error_page = include_template('error.php', compact('navigation', 'error_title', 'error_message'));
    $layout_data += [
        'main_content' => $error_page,
        'title' => $error_title,
    ];
    http_response_code(403);
    print(include_template('layout.php', $layout_data));
    exit();
}
$layout_data += [
    'title' => 'Добавление лота',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lot = $_POST;

    $rules = [
        'name' => function ($key, $lot) {
            return
                validate_filling($key, $lot) ??
                validate_correct_length($lot[$key]);
        },

        'description' => function ($key, $lot) {
            return
                validate_filling($key, $lot) ??
                validate_correct_length($lot[$key]);
        },
        'start_price' => function ($key, $lot) {
            return
                validate_filling($key, $lot) ??
                validate_positive_integer($lot[$key]);
        },
        'expiry_date' => function ($key, $lot) {
            return
                validate_filling($key, $lot) ??
                validate_date($lot[$key]);
        },
        'bet_step' => function ($key, $lot) {
            return
                validate_filling($key, $lot) ??
                validate_positive_integer($lot[$key]);
        },
        'category' => function ($key, $lot) {
            return validate_filling($key, $lot);
        },
        'userfile' => function($key) {
            return check_user_file($key);
        }
    ];

    foreach ($rules as $key => $value) {
            $rule = $rules[$key];
            $errors[$key] = $rule($key, $lot);
    }

    $errors = array_filter($errors);
    if(count($errors)) {
        $add_page = include_template('add-lot.php', compact('navigation', 'categories', 'errors'));
        $layout_data += [
            'main_content' => $add_page,
        ];
        print(include_template('layout.php', $layout_data));
        die();
    }

    $lot['picture'] = store_file($_FILES['userfile']);

    $sql = 'INSERT INTO lots (creation_date, name, description, picture, start_price, expiry_date, bet_step, user_id, category_id)
                VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';

    $stmt = db_get_prepare_stmt($link, $sql, array(
        $lot['name'],
        $lot['description'],
        $lot['picture'],
        $lot['start_price'],
        $lot['expiry_date'],
        $lot['bet_step'],
        $_SESSION['user']['id'],
        $lot['category'],
    ));
    $res = $stmt->execute();
    if ($res) {
        $id = $stmt->insert_id;
        header("Location: lot.php?id=" . $id);
    }
}

$add_page = include_template('add-lot.php', compact('navigation', 'categories', 'errors'));

$layout_data += [
    'main_content' => $add_page,
];

print(include_template('layout.php', $layout_data));
