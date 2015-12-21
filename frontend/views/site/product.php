<?php

/**
 * View product
 */

foreach ($titleProduct as $vlTitle) {
    $caption = $vlTitle->section_name;
}

foreach ($catalog as $vlCatalog){
    $directory = $vlCatalog->catalog_name;
    $urlDirect = $vlCatalog->id_catalog;
}

$this->title = 'Товары | ' . $caption;
?>

    <div class="navigation"><a href="catalog"> Каталог </a> /
        <a href="section?sect=<?php echo $urlDirect; ?>"> <?php echo $directory ?> </a> /
        <?php echo $caption; ?> </div>

    <h1> <?php echo $caption; ?> </h1>

<?php
foreach ($product as $vlProduct) {
    ?>
    <div class="catalog-view">
        <img src="/img/catalog/<?php echo $vlProduct->photo; ?>"
             class="catalog-photo" width="196px" height="196px" alt=""/> <br/>

        <div class="catalog-name">
            <a href="article?ware=<?php echo $vlProduct->id_product; ?>">
                <?php echo $vlProduct->product_name; ?>
            </a>
        </div>
    </div>
<?php
}