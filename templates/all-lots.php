<main>
    <?= $navigation; ?>
    <div class="container">
        <section class="lots">
            <?php if(count($lots)): ?>
                <h2>Все лоты в категории <span>«<?= $category_name ?>»</span></h2>
            <?php else: ?>
            <h2>Лотов в категории <span>«<?= $category_name ?>»</span> нет</h2>
                <?php endif; ?>
            <?php echo include_template('lots.php', ['lots' => $lots]);?>
        </section>
        <?php echo include_template('pagination.php', $pagination);?>
    </div>
</main>
