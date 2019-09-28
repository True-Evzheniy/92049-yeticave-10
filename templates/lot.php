<main>
    <?= $navigation; ?>
    <section class="lot-item container">
        <h2><?= $lot['name']; ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= $lot['picture']; ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= $lot['category']; ?></span></p>
                <p class="lot-item__description"><?= $lot['description']; ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer<?= is_close_to($lot['expiry_date']) ? ' timer--finishing': ''; ?>">
                        <?= get_timer($lot['expiry_date']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= $lot['current_price']; ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= $lot['min_bet']; ?></span>
                        </div>
                    </div>
                    <?php if ($visible_form): ?>
                        <form class="lot-item__form <?=count($errors) ? 'lot-item__form--invalid' : '';?>" action="/lot.php?id=<?=$_GET['id'] ?? '';?>" method="post" autocomplete="off">
                            <p class="lot-item__form-item form__item <?=invalid_class('cost', $errors)?>">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="text" name="cost" placeholder="<?= $lot['min_bet']?>">
                                <span class="form__error"><?=$errors['cost'] ?? '';?></span>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="history">
                    <h3>История ставок (<span><?=count($bets);?></span>)</h3>
                    <table class="history__list">
                        <?php foreach ($bets as $bet):?>
                            <tr class="history__item">
                                <td class="history__name"><?= $bet['name']?></td>
                                <td class="history__price"><?= format_price($bet['amount'])?></td>
                                <td class="history__time"><?=get_human_readable_date($bet['date'])?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
