<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Review */

$this->title = 'Обновить отзыв: ' . ' ' . $model->id_review;
$this->params['breadcrumbs'][] = ['label' => 'Отзыв', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_review, 'url' => ['view', 'id' => $model->id_review]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="review-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
