<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Обратная связь';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <p>
        Для связи с нами просто заполните поля и отправьте.
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?php echo $form->field($model, 'name')->label('Имя') ?>

                <?php echo $form->field($model, 'email')->label('email') ?>

                <?php echo $form->field($model, 'subject')->label('Тема') ?>

                <?php echo $form->field($model, 'body')->textArea(['rows' => 6])->label('Сообщение') ?>

                <?php echo $form->field($model, 'verifyCode')->label('Введите код')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?php echo Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
