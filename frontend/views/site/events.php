<?php
/**
 * View all news
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Все новости';

$this->registerJsFile(
    '/js/jquery.js'
);

$this->registerJsFile(
    '/js/mondi.js'
);

?>
<h1> Лента новостей </h1>

<?php $form = ActiveForm::begin(); ?>

<?php

// month
$params = [
    'prompt' => 'Выбор'
];
echo $form->field($model, 'name',[
    'options' => ['class' => 'box-month'],
    'inputOptions' => ['class' => 'form-control dynamic-month']
])->label('Месяц')->dropDownList($month, $params);

// years
$params = [
    'prompt' => 'Выбор'
];
echo $form->field($model, 'subject',[
    'options' => ['class' => 'box-years'],
    'inputOptions' => ['class' => 'form-control dynamic-years']
])->label('Год')->dropDownList($years, $params);

?>

<?php ActiveForm::end(); ?>

<div class="events"></div>