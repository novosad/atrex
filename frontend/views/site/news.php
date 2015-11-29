<?php

/**
 * View news
 */

$this->title = 'Новости';

foreach ($news as $vlNews){ ?>
    <h1> <?= $vlNews->title_news; ?> </h1>

    <div class="news-photo">
        <img src="/img/news/<?= $vlNews->photo_news ?>" alt="" />
    </div>

    <div class="news-date">
        <?= $dtNews ?>
    </div>

    <div class="news-article">
        <?= $vlNews->description_news ?>
    </div>

<?php } ?>