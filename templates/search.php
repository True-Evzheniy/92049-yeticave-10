<main>
    <?=$navigation;?>
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= $search; ?></span>»</h2>
            <ul class="lots__list">
                <?php foreach ($lots as $lot): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= $lot['picture']?>" width="350" height="260" alt="Сноуборд">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= $lot['category']; ?></span>
                            <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= $lot['id']; ?>"><?= $lot['name']; ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount"><?= get_amount_label($lot['bet_count']) ?></span>
                                    <span class="lot__cost"><?= format_price($lot['price'])?></span>
                                </div>
                                <div class="lot__timer timer<?= is_close_to($lot['expiry_date']) ? ' timer--finishing': ''; ?>">
                                    <?= get_timer($lot['expiry_date']); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <?php echo include_template('pagination.php', $pagination);?>
    </div>
</main>
