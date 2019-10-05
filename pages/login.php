<?php
require_once('../init.php');
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_form = $_POST;
    $rules = [
        'email' => function ($key, $login_form, $link) {
            return
                validate_filling($key, $login_form) ??
                validate_email($login_form[$key]) ??
                validate_exited_email($login_form[$key], $link);
        },
        'password' => function ($key, &$login_form, $link, $errors) {
            return
                validate_filling($key, $login_form) ??
                process_password($key, $login_form, $link, $errors);
        },
    ];
    foreach ($rules as $key => $value) {
        $rule = $rules[$key];
        $errors[$key] = $rule($key, $login_form, $link, $errors);
    }

    $errors = array_filter($errors);

    if (!count($errors)) {
        $_SESSION['user'] = $login_form['user'];
        header("Location: /index.php");
        exit();
    }
}

$layout_data += [
    'title' => 'Вход',
    'main_content' => include_template('login.php', compact('navigation', 'errors'))
];

if (isset($_SESSION['user'])) {
    header("Location: /index.php");
    exit();
}
print(include_template('layout.php', $layout_data));

function validate_exited_email($email, $link)
{
    if (is_uniq_user_email($email, $link)) {
        return 'Пользователь не существует';
    }
    return null;
}

/**
 * @param string $key
 * @param array $login_form
 * @param mysqli $link
 * @param $errors
 * @return string|null
 */
function process_password($key, &$login_form, $link, $errors)
{
    if ($errors['email'] !== null) return null;
    $safe_email = $link->real_escape_string($login_form['email']);
    $res = $link->query("SELECT * from users WHERE email = '{$safe_email}'");
    $user = $res->fetch_array(MYSQLI_ASSOC);
    if (!password_verify($login_form[$key], $user[$key])) {
        return 'Неверный пароль';
    }
    $login_form['user'] = $user;
    return null;
}
