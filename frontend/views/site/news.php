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
    <h1> <?php echo $vlNews->title_news; ?> </h1>

    <div class="news-photo">
        <img src="/img/news/<?php echo $vlNews->photo_news ?>" alt="" />
    </div>

    <div class="news-date">
        <?php echo $date_news ?>
    </div>

    <div class="news-article">
        <?php echo $vlNews->description_news ?>
    </div>

<?php } ?>