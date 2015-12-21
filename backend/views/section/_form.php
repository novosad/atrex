<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Catalog;

/* @var $this yii\web\View */
/* @var $model app\models\Section */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="section-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'catalog_id')->textInput() ?>

    <?= $form->field($model, 'catalog_id')->dropDownList(
        ArrayHelper::map(Catalog::find()->all(), 'id_catalog', 'catalog_name'),
        ['prompt' => 'Выберите значение']
    ); ?>

    <?= $form->field($model, 'section_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'id_section')->hiddenInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
