<?php
/**
 * view random product
 */

foreach ($product as $vlProduct){
    $id_product[] = $vlProduct->id_product;
    $name_product[] = $vlProduct->product_name;
    $photo_product[] = $vlProduct->photo;
}

for ($item = 0; $item < count($id_product); $item++){ ?>
    <div class="catalog-view">
        <img src="/img/catalog/<?php echo $photo_product[$item]; ?>"
             class="catalog-photo" width="126px" height="126px" alt=""/> <br/>

        <div class="catalog-name">
            <a href="article?ware=<?php echo $id_product[$item]; ?>">
                <?php echo $name_product[$item]; ?>
            </a>
        </div>
    </div>
<?php }