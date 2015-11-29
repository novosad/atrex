<?php
/**
 * view item
 */

foreach ($article as $capArticle) {
    $caption = $capArticle->product_name;
}

foreach ($section as $vlSection) {
    $dirSection = $vlSection->section_name;
    $urlSection = $vlSection->id_section;
}

foreach ($catalog as $vlCatalog) {
    $dirCatalog = $vlCatalog->catalog_name;
    $urlCatalog = $vlCatalog->id_catalog;
}

?>

    <div class="navigation"><a href="catalog"> Каталог </a> /
        <a href="section?sect=<?= $urlCatalog; ?>"> <?= $dirCatalog ?> </a> /
        <a href="product?item=<?= $urlSection; ?>"> <?= $dirSection; ?> </a> /
        <?= $caption; ?> </div>

<?php
$this->title = $caption;
foreach ($article as $vlArticle) {
    ?>
    <h1> <?php echo $vlArticle->product_name; ?> </h1>
    <div class="article_photo">
        <img src="/img/catalog/<?php echo $vlArticle->photo; ?>.jpg" alt=""/>
    </div>
    <div class="article_desc">
        <?php echo $vlArticle->description; ?>
        <p class="article_price"> <?php echo $vlArticle->price; ?> руб. </p>
    </div>
<?php }