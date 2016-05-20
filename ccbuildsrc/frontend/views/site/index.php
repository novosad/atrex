<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\components\RandomWidget;
use app\components\LatestWidget;

$this->title = 'МикроСофт';
?>

<div class="random-product">
  <div class="random-title"> Случайные товары </div>
    <?php echo RandomWidget::widget(); ?>
</div>

<div class="clr"></div>

<div class="latest-review">
   <div class="latest-title"> Последние отзывы </div>
    <?php echo LatestWidget::widget(); ?>
</div>
