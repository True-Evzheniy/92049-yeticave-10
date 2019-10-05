<link href="../css/flatpickr.min.css" rel="stylesheet">
<main>
    <?= $navigation; ?>
    <form class="form form--add-lot container <?=count($errors) ? 'form--invalid' : '';?>" action="/pages/add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?=invalid_class('name', $errors);?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота" value="<?= get_post_val('name'); ?>">
                <span class="form__error"><?= $errors['name'] ?? ''?></span>
            </div>
            <div class="form__item <?=invalid_class('category', $errors);?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category">
                    <option value="">Выберите категорию</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id']; ?>" <?=get_post_val('category') == $category['id'] ? 'selected' : '' ?>><?= $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?= $errors['category'] ?? ''?></span>
            </div>
        </div>
        <div class="form__item form__item--wide <?=invalid_class('description', $errors);?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="description" placeholder="Напишите описание лота"><?= get_post_val('description'); ?></textarea>
            <span class="form__error"><?= $errors['description'] ?? ''?></span>
        </div>
        <div class="form__item form__item--file <?=invalid_class('userfile', $errors);?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" value="" name="userfile">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
            <span class="form__error"><?= $errors['userfile'] ?? ''?></span>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?=invalid_class('start_price', $errors);?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="start_price" placeholder="0" value="<?= get_post_val('start_price'); ?>">
                <span class="form__error"><?= $errors['start_price'] ?? ''?></span>
            </div>
            <div class="form__item form__item--small <?=invalid_class('bet_step', $errors);?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="bet_step" placeholder="0" value="<?= get_post_val('bet_step'); ?>">
                <span class="form__error"><?= $errors['bet_step'] ?? ''?></span>
            </div>
            <div class="form__item <?=invalid_class('expiry_date', $errors);?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="expiry_date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= get_post_val('expiry_date'); ?>">
                <span class="form__error"><?= $errors['expiry_date'] ?? ''?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom"><?=count($errors) ? 'Пожалуйста, исправьте ошибки в форме.' : '';?></span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
