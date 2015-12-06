<?php
/**
 * view request ajax
 */

if (count($events) > 0){

foreach ($events as $vlEvents) {
    // get date
    $bufDate = $vlEvents->date_news;
    // unix time
    $unixTime = strtotime($bufDate);
    // current date
    $date_news = date('d-m-Y',$unixTime);
    ?>

    <div class="events-date"> <?= $date_news; ?> </div>
    <div class="events-title">
        <a href="news?id=<?= $vlEvents->id_news; ?>">
            <?= $vlEvents->title_news ?>
        </a>
    </div>

<?php } } else{ ?>
    <div class="news-lack"> Нет новостей за выбранный период </div>
<?php }