<?php
/**
 * selection product
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Выбор продукта';

?>

<h1>Подбор товара</h1>

<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

<?= $form->field($model, 'name')->widget(\yii\jui\SliderInput::classname(), [
    'clientOptions' => [
        'min' => 1,
        'max' => 10,
        'values' => [0,100],
    ],
]) ?>

<?php ActiveForm::end(); ?>