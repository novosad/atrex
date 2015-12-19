<?php

/**
 * result view search
 */

$this->title = 'Результаты поиска';

?>

<h1> Результаты поиска </h1>

Ваши результаты по запросу <b> <?php echo $search_text; ?></b>:

<div class="result-view">
    <?php if ($product != null){ ?>
    <ul class="list-result">
        <?php
        foreach ($product as $keyProduct => $vlProduct) { ?>
            <li>
                <img width="100px" height="50px"
                     src="/img/catalog/<?php echo $image[$keyProduct]; ?>.jpg" alt=""/>
                <a href="article?ware=<?php echo $keyProduct; ?>">
                    <?php echo $vlProduct; ?>
                </a>
            </li>
        <?php }
        ?>
    </ul>
    <?php } else{
        echo "<p><i> Поиск результатов не дал </i></p>";
    } ?>
</div>