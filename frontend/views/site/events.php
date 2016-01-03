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

//$this->registerJsFile(
//    '/js/mondi.js'
//);

$this->registerJsFile(
    '/js/events.js'
);

?>
<h1> Лента новостей </h1>

<?php $form = ActiveForm::begin(); ?>

<?php

// month
$params = [
    'prompt' => 'Выбор'
];
echo $form->field($model, 'name', [
    'options' => ['class' => 'box-month'],
    'inputOptions' => ['class' => 'form-control dynamic-month']
])->label('Месяц')->dropDownList($month, $params);

// years
$params = [
    'prompt' => 'Выбор'
];
echo $form->field($model, 'subject', [
    'options' => ['class' => 'box-years'],
    'inputOptions' => ['class' => 'form-control dynamic-years']
])->label('Год')->dropDownList($years, $params);

?>

<?php ActiveForm::end(); ?>

<div class="all-news">
    <p><b>Все новости:</b></p>
    <?php
    foreach ($all_news as $vlEvents) {
        // get date
        $bufDate = $vlEvents->date_news;
        // unix time
        $unixTime = strtotime($bufDate);
        // current date
        $date_news = date('d-m-Y', $unixTime);
        ?>

        <div class="events-date"> <?php echo $date_news; ?> </div>
        <div class="events-title">
            <a href="news?id=<?php echo $vlEvents->id_news; ?>">
                <?php echo $vlEvents->title_news ?>
            </a>
        </div>

    <?php } ?>
</div>

<div class="events"></div>