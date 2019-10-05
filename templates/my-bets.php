<main>
    <?= $navigation; ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($bets as $bet): ?>
                <tr class="rates__item <?= get_bet_modifier($bet['win'], $bet['finished'])?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?=$bet['picture'];?>" width="54" height="40" alt="<?= $bet['name']?>">
                        </div>
                        <div>
                            <h3 class="rates__title"><a href="/pages/lot.php?id=<?= $bet['lot']?>"><?= $bet['name']?></a></h3>
                            <?php if ($bet['contacts']): ?>
                                <p><?= $bet['contacts']; ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="rates__category">
                        <?= $bet['category']?>
                    </td>
                    <td class="rates__timer">
                        <div class="timer <?= get_timer_modifier($bet['win'], $bet['finished'], is_close_to($bet['expiry_date'])); ?>">
                            <?= get_timer_label($bet['win'], $bet['finished'], $bet['expiry_date']); ?>
                        </div>
                    </td>
                    <td class="rates__price">
                        <?= format_price($bet['amount']); ?>
                    </td>
                    <td class="rates__time">
                        <?= get_human_readable_date($bet['date']);?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>
