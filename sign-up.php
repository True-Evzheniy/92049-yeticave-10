<?php
require_once('./init.php');
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sign_up = $_POST;

    $rules = [
        'name' => function ($key, $sign_up) {
            return
                validate_filling($key, $sign_up) ??
                validate_correct_length($sign_up[$key], 1, 64);
        },
        'password' => function ($key, $sign_up) {
            return
                validate_filling($key, $sign_up) ??
                validate_correct_length($sign_up[$key], 6);
        },
        'contacts' => function ($key, $sign_up) {
            return
                validate_filling($key, $sign_up) ??
                validate_correct_length($sign_up[$key], 1, 255);
        },
        'email' => function ($key, $sign_up, $link) {
            return
                validate_filling($key, $sign_up) ??
                validate_email($sign_up[$key]) ??
                validate_uniq_email($sign_up[$key], $link);
        },
    ];

    foreach ($rules as $key => $value) {
        $rule = $rules[$key];
        $errors[$key] = $rule($key, $sign_up, $link);
    }
    $errors = array_filter($errors);

    if (!count($errors)) {
        $sql = 'INSERT INTO users (email, name, password, contacts) VALUES (?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [
            $sign_up['email'],
            $sign_up['name'],
            password_hash($sign_up['password'], PASSWORD_DEFAULT),
            $sign_up['contacts']
        ]);
        $stmt->execute();
        header("Location: pages/login.html");
    }
}

$layout_data += [
    'main_content' => include_template('sign-up.php', compact('navigation', 'errors')),
    'title' => 'Регистрация',
];

print(include_template('layout.php', $layout_data));

