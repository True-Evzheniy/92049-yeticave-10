<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Форматирует цену, разделяя тысячи, округляя до целых и добавдляя символ рубля
 * @param float|integer $price
 * @return string
 */
function format_price($price) {
    $price = ceil($price);
    $price = number_format($price, 0, '.', ' ');
    $price .= ' ₽';

    return $price;
}

/**
 * Рекурсивно преобразует специальные символы в HTML-сущности
 * @param array $array
 * @return array
 */
function make_safe_data(array $array) {
    return array_map( function ($item) {
        if(is_array($item)){
            return make_safe_data($item);
        }
        if(is_string($item)) {
            return htmlspecialchars($item);
        }
        return $item;
    }, $array);
}

/**
 * Количество часов и минут до даты
 * @param $date_str
 * @return array
 */
function get_time_until($date_str) {
    $finish_date = strtotime($date_str);
    $now = time();
    $diff = $finish_date - $now;
    if ($diff <= 0) {
        return [0, 0];
    }

    $hours = ceil($diff / 3600);
    $minutes = ceil(($diff % 3600) / 60);

    return [$hours, $minutes];
}


/**
 * Провека на близость даты
 * @param $date_str
 * @return bool
 */
function is_close_to($date_str) {
    [$hours] = get_time_until($date_str);

    return !boolval($hours);
}

/**
 * Возвращает формат таймера
 * @param array $time
 * @return string
 */
function get_timer($date_str) {
    [$hours, $minutes] = get_time_until($date_str);
    $padded_hours = sprintf("%02d", $hours);
    $padded_minutes = sprintf("%02d", $minutes);

    return "{$padded_hours}:{$padded_minutes}";
}

/**
 * @param string $str
 * @param int $min
 * @param int $max
 * @return string|null
 */
function validate_correct_length($str, $min = 1, $max = 128)
{
    $len = strlen($str);
    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }
    return null;
}

/**
 * @param string $number
 * @return string|null
 */
function validate_positive_integer($number)
{
    if (!is_numeric($number) || boolval(fmod($number, 1)) || intval($number) <= 1) {
        return 'Значение должно быть целым и больше нуля';
    }
    return null;
}

/**
 * @param string $date
 * @return string|null
 */
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

/**
 * @param string $field
 * @return string|null
 */
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

/**
 * @param array $file
 * @return string
 */
function store_file($file) {
    $tmp_name = $file['tmp_name'];
    $mime_type = mime_content_type($tmp_name);
    $file_name = uniqid() . get_extension_by_mime($mime_type);
    $path = 'uploads/' . $file_name;
    move_uploaded_file($tmp_name, $path);
    return $path;
}

/**
 * @param string $name
 * @return mixed|string
 */
function get_post_val($name)
{
    return $_POST[$name] ?? '';
}


/**
 * @param string $type
 * @return string
 */
function get_extension_by_mime($type) {
    $map = [
        'image/png' => '.png',
        'image/jpeg' => '.jpg'
    ];
    return $map[$type] ?? '';
}

/**
 * @param string $mime_type
 * @param array $allowed_types
 * @return bool
 */
function check_mime_type($mime_type, $allowed_types = ['image/png', 'image/jpeg'])
{
    return in_array($mime_type, $allowed_types);
}

/**
 * @param $field string
 * @param $target array
 * @return string|null
 */
function validate_filling($field, $target)
{
    if (empty($target[$field])) {
        return "Поле обязательно";
    }
    return null;
}

/**
 * @param $name string
 * @return string|null
 */
function validate_email($name)
{
    if (!filter_var($name, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    }
    return null;
}

/**
 * @param $email string
 * @param $link mysqli
 * @return bool
 */
function is_uniq_user_email($email, $link) {
    $safe_email = $link->real_escape_string($email);
    $res = $link->query("SELECT id FROM users WHERE email = '{$safe_email}'");
    return $res->num_rows === 0;
}

/**
 * @param $email
 * @param $link mysqli
 * @return string|null
 */
function validate_uniq_email($email, $link) {
    if(!is_uniq_user_email($email, $link)) {
        return 'Пользователь с этим email уже зарегистрирован';
    }
    return null;
}

/**
 * @param string $name
 * @param array $errors
 * @param string $class
 * @return string
 */
function invalid_class($name, $errors, $class='form__item--invalid') {
    return (isset($errors[$name])) ? $class : '';
}

/**
 * @param string $value
 * @return float
 */
function get_float_from_currency_string($value) {
    return floatval(preg_replace('/[^\d.]+/', '', $value));
}

