<?php
require_once('./init.php');
$layout_data += [
    'title' => 'Добавление лота',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lot = $_POST;

    $required_fields = [
        'name',
        'description',
        'start_price',
        'expiry_date',
        'bet_step',
        'category',
    ];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        };
    }
    $rules = [
        'name' => function ($value) {
            return validate_correct_length($value);
        },
        'description' => function ($value) {
            return validate_correct_length($value);
        },
        'start_price' => function ($value) {
            return validate_positive_integer($value);
        },
        'expiry_date' => function ($value) {
            return validate_date($value);
        },
        'bet_step' => function($value) {
            return validate_positive_integer($value);
        },
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key]) && !isset($errors[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($_POST[$key]);
        }
    }

    check_user_file($errors, $lot);
    $errors = array_filter($errors);
    if(count($errors)) {
        $add_page = include_template('add-lot.php', compact('navigation', 'categories', 'errors'));
        $layout_data += [
            'main_content' => $add_page,
        ];
        print(include_template('layout.php', $layout_data));
        die();
    }

    process_user_file($lot);

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
}

function validate_positive_integer($number)
{
    if (!is_numeric($number) || boolval(fmod($number, 1)) || intval($number) <= 1) {
        return 'Значение должно быть целым и больше нуля';
    }
}

function validate_date($date)
{
    if (!is_date_valid($date)) {
        return 'Укажите дату в формате ГГГГ-ММ-ДД';
    }
    $input = new DateTime($date);
    $tomorrow = new DateTime('tomorrow');
    if($input < $tomorrow) {
        return 'Введите дату не позднее ' . $tomorrow->format('Y-m-d');
    }
}

function check_user_file(&$errors) {
    if(isset($_FILES['userfile']) && file_exists($_FILES['userfile']['tmp_name'])){
        $tmp_name = $_FILES['userfile']['tmp_name'];
        $mime_type = mime_content_type($tmp_name);
        if(!check_mime_type($mime_type)) {
            $errors['userfile'] = 'Загрузите изображение в формате png или jpg';
        }
    } else {
        $errors['userfile'] = 'Загрузите изображение лота';
    }
}

function process_user_file(&$lot) {
    $tmp_name = $_FILES['userfile']['tmp_name'];
    $mime_type = mime_content_type($tmp_name);
    $file_name = uniqid() . get_extension_by_mime($mime_type);
    $path = 'uploads/' . $file_name;
    $lot['picture'] = $path;
    move_uploaded_file($tmp_name, $path);
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

