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

<div class="selection_view"></div>