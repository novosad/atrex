<?php
/**
 * selection product
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Выбор продукта';

$this->registerJsFile(
    '/js/jquery.js'
);

$this->registerJsFile(
    '/js/selection.js'
);

?>

<h1>Подбор товара</h1>

Сумма: от <input type="text" name="start_select" id="sel_start" value="700000">
до <input type="text" name="finish_select" id="sel_finish" value="1300000"> Br.

Категория: <select name="choice" id="sel_choice">
    <option value="all"> Весь каталог</option>
    <?php
    foreach ($catalog as $keyCatalog => $vlCatalog) { ?>
        <option value="<?php echo $keyCatalog; ?>"> <?php echo $vlCatalog; ?> </option>
    <?php }
    ?>
</select>

<div class="selection_view"></div>