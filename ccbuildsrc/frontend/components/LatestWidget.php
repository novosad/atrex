<?php
/**
 * widget latest review
 */

namespace app\components;

use yii\base\Widget;
use app\models\Review;

class LatestWidget extends Widget{
    public function run(){
        // query
        $review = Review::find()
            ->where(['=', 'review_moderation', 'yes'])
            ->orderBy('id_review DESC')
            ->limit(3)
            ->all();

        //render view
        return $this->render('latest',[
            'review' => $review,
        ]);
    }
}