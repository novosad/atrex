<?php
/**
 * View catalog
 */

$this->title = 'Каталог';
?>

<h1> Каталог </h1>

<?php
foreach ($catalog as $vlCatalog) {
    ?>
    <div class="catalog-view">
        <a href="section?sect=<?php echo $vlCatalog->id_catalog ?>">
        <img src="/img/icon-catalog/<?php echo $vlCatalog->catalog_photo; ?>"
             width="196px" height="196x" alt="" class="catalog-photo"/> <br/>
      <div class="catalog-name">
              <?php echo $vlCatalog->catalog_name; ?>
          </a>
      </div>
    </div>
    <?php
}