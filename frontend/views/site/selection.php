<?php
/**
 * selection product
 */

$this->title = 'Выбор продукта';

$this->registerJsFile(
    '/js/jquery.js'
);

$this->registerJsFile(
    '/js/jquery.ui-slider.js'
);

?>

<h1>Подбор товара</h1>

<div id="slider"></div>

<script type="text/javascript">
    jQuery("#slider").slider({
        min: 0,
        max: 1000,
        values: [0,1000],
        range: true
    });
</script>
