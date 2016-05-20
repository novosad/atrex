<?php
/**
 * view section
 */

foreach ($titleSection as $vlTitle) {
    $caption = $vlTitle->catalog_name;
}

$this->title = 'Раздел | ' . $caption;

?>

<div class="navigation"> <a href="catalog"> Каталог </a> / <?php echo $caption; ?> </div>

<h1> <?php echo $caption; ?> </h1>

<?php
foreach ($section as $vlSection) {
    ?>
    <div class="catalog-view">
        <a href="product?item=<?php echo $vlSection->id_section; ?>">
        <img src="/img/icon-section/<?php echo $vlSection->section_photo; ?>" class="catalog-photo"
             width="196px" height="196px" alt=""/> <br/>
        <div class="catalog-name">
                <?php echo $vlSection->section_name; ?>
            </a>
        </div>
    </div>
<?php
}