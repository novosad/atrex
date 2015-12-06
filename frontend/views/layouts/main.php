<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use app\components\NewsWidget;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?> | МикроСОфт </title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="main">

    <div class="header">
        <div class="header_resize">
            <div class="menu_nav">
                <?php
                NavBar::begin([
                    'brandLabel' => 'МикроСофт',
                    'brandUrl' => Yii::$app->homeUrl,
                    'options' => [
                        'class' => 'navbar-inverse navbar-fixed-top',
                    ],
                ]);
                $menuItems = [
                    ['label' => 'Главная', 'url' => ['/site/index']],
                    ['label' => 'Каталог', 'url' => ['/site/catalog']],
                    ['label' => 'Новости', 'url' => ['/site/events']],
//                    ['label' => 'Контакты', 'url' => ['/site/']],
                    ['label' => 'Обратная связь', 'url' => ['/site/contact']],
                ];
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $menuItems,
                ]);
                NavBar::end();
                ?>
            </div>
            <div class="clr"></div>
            <div class="logo">
                <h1 class="mark"><a href="http://kirilka.esy.es/"> МикроСофт </a>
                    <small>Работать нужно так, чтобы последующие клиенты были друзьями предыдущих</small>
                </h1>
            </div>
            <div class="clr"></div>
            <div class="slider">
                <div id="coin-slider">
                    <!--                    <a href="#"><img src="/images/slide11.jpg"-->
                    <!--                                                          width="960" height="360" alt=""/>-->
                    <!--                        <span>За последнее десятилетие компьютеры заполнили жизнь практически каждого человека, теперь они есть почти в каждой семье.</span>-->
                    <!--                    </a>-->
                    <!--                    <a href="#"><img src="/images/slide44.jpg" width="960"-->
                    <!--                                                  height="360" alt=""/>-->
                    <!--                        <span>Для многих компьютерная техника - первый помощник в работе, а некоторые не могут представить свой досуг веселым без компьютера.</span>-->
                    <!--                    </a>-->
                    <a href="contact.html"><img src="/images/slide33.jpg" width="960"
                                                height="360" alt=""/>
                        <span>Компьютер с легкостью заменяет целый ряд устройств, так необходимых нам ежедневно.</span>
                    </a>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
    </div>
    <div class="content">
        <div class="content_resize">

            <div class="mainbar">

                <div class="wrap">
                    <?= Alert::widget() ?>
                    <?= $content ?>
                </div>
            </div>
            <div class="sidebar">
                <form class="sisea-search-form" id="formsearch" action="search-results.html" method="get">
   <span>
       <input type="text" name="search" id="search" class="search" value=""/>
   </span>
                    <input type="hidden" name="id" value="11"/>
                    <input type="submit" class="button_search" value=""/>
                </form>
                <div class="clr"></div>
                <div class="gadget">
                    <h2> Последние новости </h2>
                    <?= NewsWidget::widget(); ?>
                </div>
            </div>
            <div class="clr"></div>
        </div>
    </div>
    <div class="footer">
        <div class="footer_resize">
            &copy; МикроСофт <?= date('Y') ?>
            <div style="clear:both;"></div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
