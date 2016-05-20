<?php
/**
 * view widget news
 */

?>

<ul class="list-news">
<?php foreach ($news as $vlNews){ ?>
    <li> <a href="news?id=<?php echo $vlNews->id_news; ?>"> <?php echo $vlNews->title_news ?> </a> </li>
<?php } ?>
</ul>