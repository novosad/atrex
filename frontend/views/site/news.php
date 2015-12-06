<?php

/**
 * View news
 */

$this->title = 'Новости';

foreach ($news as $vlNews){
    // get date
    $bufDate = $vlNews->date_news;
    // unix time
    $unixTime = strtotime($bufDate);
    // current date
    $date_news = date('d-m-Y',$unixTime);
    ?>
    <h1> <?= $vlNews->title_news; ?> </h1>

    <div class="news-photo">
        <img src="/img/news/<?= $vlNews->photo_news ?>" alt="" />
    </div>

    <div class="news-date">
        <?= $date_news ?>
    </div>

    <div class="news-article">
        <?= $vlNews->description_news ?>
    </div>

<?php } ?>