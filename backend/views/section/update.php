<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Section */

$this->title = 'Update Section: ' . ' ' . $model->id_section;
$this->params['breadcrumbs'][] = ['label' => 'Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_section, 'url' => ['view', 'id' => $model->id_section]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="section-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
