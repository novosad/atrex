<?php

/**
 * result view search
 */

$this->title = 'Результаты поиска';

?>

<h1> Результаты поиска </h1>

Ваши результаты по запросу <b> <?php echo $search_text; ?></b>:

<div class="result-view">
    <?php if ($product != null) { ?>
        <ul class="list-result">
            <?php
            foreach ($product as $keyProduct => $vlProduct) { ?>
                <li>
                    <img width="100px" height="50px"
                         src="/img/catalog/<?php echo $image[$keyProduct]; ?>" alt=""/>
                    <a href="article?ware=<?php echo $keyProduct; ?>">
                        <?php echo $vlProduct; ?>
                    </a>
                </li>
            <?php }
            ?>
        </ul>
    <?php } else { ?>
        <p><i> Поиск результатов не дал </i></p>
        <b> Повторите, пожалуйста, поиск </b>
        <form method="post" class="sisea-search-form" id="formsearch" action="result">
            <span>
                <input type="text" name="search-text" id="search" class="search" value=""/>
            </span>
            <input type="submit" class="button_search" name="search-bt" value=""/>
        </form>
    <?php } ?>
</div>