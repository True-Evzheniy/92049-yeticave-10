<?php
require_once('./init.php');
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

    $sql = 'INSERT INTO lots (creation_date, name, description, picture, start_price, expiry_date, bet_step, creator, category)
                VALUES (NOW(), ?, ?, ?, ?, ?, ?, 1, ?)';

    $stmt = db_get_prepare_stmt($link, $sql, array(
        $lot['name'],
        $lot['description'],
        $lot['picture'],
        $lot['start_price'],
        $lot['expiry_date'],
        $lot['bet_step'],
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

function validate_correct_length($str, $min = 1, $max = 128)
{
    $len = strlen($str);
    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }
    return null;
}

function validate_positive_integer($number)
{
    if (!is_numeric($number) || boolval(fmod($number, 1)) || intval($number) <= 1) {
        return 'Значение должно быть целым и больше нуля';
    }
    return null;
}

function validate_date($date)
{
    if (!is_date_valid($date)) {
        return 'Укажите дату в формате ГГГГ-ММ-ДД';
    }
    try {
        $input = new DateTime($date);
        $tomorrow = new DateTime('tomorrow');
        if($input < $tomorrow) {
            return 'Введите дату не позднее ' . $tomorrow->format('Y-m-d');
        }
    } catch (Exception $error) {
        print_r($error->getMessage());
        die();
    }
    return null;
}

function check_user_file($field) {
    if(isset($_FILES[$field]) && file_exists($_FILES[$field]['tmp_name'])){
        $tmp_name = $_FILES[$field]['tmp_name'];
        $mime_type = mime_content_type($tmp_name);
        if(!check_mime_type($mime_type)) {
            return 'Загрузите изображение в формате png или jpg';
        }
    } else {
        return 'Загрузите изображение лота';
    }
    return null;
}

function store_file($file) {
    $tmp_name = $file['tmp_name'];
    $mime_type = mime_content_type($tmp_name);
    $file_name = uniqid() . get_extension_by_mime($mime_type);
    $path = 'uploads/' . $file_name;
    move_uploaded_file($tmp_name, $path);
    return $path;
}

function get_post_val($name)
{
    return $_POST[$name] ?? '';
}
function get_extension_by_mime($type) {
    $map = [
        'image/png' => '.png',
        'image/jpeg' => '.jpg'
    ];
    return $map[$type];
}

function check_mime_type($mime_type, $allowed_types = ['image/png', 'image/jpeg'])
{
    return in_array($mime_type, $allowed_types);
}

function validate_filling($field, $target)
{
    if (empty($target[$field])) {
        return "Поле обязательно";
    }
    return null;
}

