<main>
    <?=$navigation;?>
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= $search; ?></span>»</h2>
            <?php echo include_template('lots.php', ['lots' => $lots]);?>
        </section>
        <?php echo include_template('pagination.php', $pagination);?>
    </div>
</main>
