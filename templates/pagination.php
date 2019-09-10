<?php if ($pages_count > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev" <?=($cur_page == $pages[0]) ? 'style="visibility: hidden"': '';?>>
            <a href="<?=build_pagination_url($path, $cur_page - 1) ?>">Назад</a>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?php if ($page == $cur_page): ?>pagination__item--active<?php endif; ?>">
                <a href="<?=build_pagination_url($path, $page);?>"><?=$page;?></a>
            </li>
        <?php endforeach;?>
        <li class="pagination-item pagination-item-next" <?=($cur_page == end($pages)) ? 'style="visibility: hidden"': '';?>>
            <a href="<?=build_pagination_url($path, $cur_page + 1) ?>">
                Вперед
            </a>
        </li>
    </ul>
<?php endif; ?>
