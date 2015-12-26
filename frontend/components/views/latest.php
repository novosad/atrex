<?php
/**
 * view latest review
 */

foreach ($review as $vlReview) {
    $bf_date[] = $vlReview->review_date;
    $name_review[] = $vlReview->review_name;
    $product_review[] = $vlReview->product_id;
    $review_text[] = $vlReview->review;
}

// correct date view
foreach ($bf_date as $val_buf) {
    $unixTime = strtotime($val_buf);
    // current date
    $date_review[] = date('d-m-Y', $unixTime);
}

for ($rew = 0; $rew < count($product_review); $rew++) { ?>
    <div class="review-view">
        <div>
            <img src="/images/user.jpeg" width="96px" height="96px" alt=""/>
        </div>

        <div class="review-latest">
            <a href="article?ware=<?php echo $product_review[$rew]; ?>">
                <?php echo $review_text[$rew]; ?>
            </a>
<!--            <img src="/images/quote.gif" width="24px" height="24px" alt=""/>-->
        </div>

        <div class="date-latest">
            <?php echo $date_review[$rew]; ?>
        </div>
        <div class="name-latest">
            <?php echo $name_review[$rew]; ?>
        </div>

        <div class="clr"></div>
    </div>
<?php }