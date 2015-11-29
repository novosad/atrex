<?php
/**
 * view widget news
 */

?>

<ul class="list-news">
<?php foreach ($news as $vlNews){ ?>
    <li> <a href="news?id=<?= $vlNews->id_news; ?>"> <?= $vlNews->title_news ?> </a> </li>
<?php } ?>
</ul>