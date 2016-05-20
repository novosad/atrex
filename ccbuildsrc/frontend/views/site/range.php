<?php
/**
 * Range view
 */

$dates = '1990-10-19';
// в unix время
$buf = strtotime($dates);

// в обычную дату
$meet = date('d-m-Y',$buf);

echo $meet;