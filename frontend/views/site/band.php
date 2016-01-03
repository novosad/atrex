<?php
/**
 * view ajax selection
 */

?>

<div class="result-view">
    <?php if ($band != null) {
        echo '<b>Количество найденных товаров: </b>' . count($band) . ' шт.';
        ?>
        <ul class="list-result">
            <?php
            foreach ($band as $vlBand) { ?>
                <li>
                    <a href="article?ware=<?php echo $vlBand->id_product; ?>">
                        <img width="100px" height="50px"
                             src="/img/catalog/<?php echo $vlBand->photo; ?>" alt=""/>
                        <?php echo $vlBand->product_name; ?>
                    </a>
                    (<?php echo number_format($vlBand->price, 0, "", " "); ?> руб.)
                </li>
            <?php }
            ?>
        </ul>
    <?php } else { ?>
        <p><b> По данным критерием нет результата </b></p>
    <?php } ?>
</div>