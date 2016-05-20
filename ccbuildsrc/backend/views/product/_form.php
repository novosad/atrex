<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Section;
use app\models\Catalog;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=
    $form->field($model, 'section_id')->dropDownList(ArrayHelper::map(
        Section::find()->with('catalog')->all(), 'id_section', function ($items) {
        return $items->catalog->catalog_name . ' -> ' . $items->section_name;
    }
    ));
    ?>

    <?= $form->field($model, 'product_name')->label('Продукт')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->label('Описание')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->label('Цена')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photo')->label('Фото')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_product')->label('')->hiddenInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
