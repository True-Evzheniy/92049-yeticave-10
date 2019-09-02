<main>
    <?= $navigation ?>
    <form class="form container<?=count($errors) ? ' form--invalid': '';?>" action="sign-up.php" method="post" autocomplete="off">
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item <?=invalid_class('email', $errors);?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=get_post_val('email');?>">
            <span class="form__error"><?=$errors['email'] ?? '';?></span>
        </div>
        <div class="form__item <?=invalid_class('password', $errors);?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?=get_post_val('password');?>">
            <span class="form__error"><?=$errors['password'] ?? '';?></span>
        </div>
        <div class="form__item <?=invalid_class('name', $errors);?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=get_post_val('name');?>">
            <span class="form__error"><?=$errors['name'] ?? '';?></span>
        </div>
        <div class="form__item <?=invalid_class('contacts', $errors);?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="contacts" placeholder="Напишите как с вами связаться"><?=get_post_val('contacts');?></textarea>
            <span class="form__error"><?=$errors['contacts'] ?? '';?></span>
        </div>
        <span class="form__error<?=count($errors) ? ' form__error--bottom': '';?>">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="/login.php">Уже есть аккаунт</a>
    </form>
</main>
