<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/30/16
 * Time: 5:27 PM
 */
<!--PAGINATION-->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php
            $prev = array_shift($buttons);
            $next = array_pop($buttons);
            if($prev->isActive()):?>
                <li><a href="/books/list/<?=$page-1?>"><span aria-hidden="true">&laquo;</span></a></li>
            <?php else:?>
                <li class="disabled"><span aria-hidden="true">&laquo;</span></li>
            <?php endif;?>
<?php foreach($buttons as $button):?>
    <li <?=$page == $button->getPage() ? 'class="active"' : ''?>><a href="/books/list/<?=$button->getPage()?>"><?=$button->getText()?></a></li>
<?php endforeach; ?>
<?php if($next->isActive()):?>
    <li><a href="/books/list/<?=$page+1?>"><span aria-hidden="true">&raquo;</span></a></li>
<?php else:?>
    <li class="disabled"><span aria-hidden="true">&raquo;</span></li>
<?php endif;?>
</ul>
</nav>
<!--END Pagination-->