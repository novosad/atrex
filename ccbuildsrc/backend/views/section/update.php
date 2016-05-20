<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Section */

$this->title = 'Update Section: ' . ' ' . $model->section_name;
$this->params['breadcrumbs'][] = ['label' => 'Раздел', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->section_name, 'url' => ['view', 'id' => $model->id_section]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="section-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>