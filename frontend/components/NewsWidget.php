<?php
/**
 * widget news
 */

namespace app\components;

use yii\base\Widget;
use app\models\News;


class NewsWidget extends Widget
{

    public function run()
    {
        // create model
        $news = new News();

        // query
        $news = News::find()
            ->orderBy('date_news DESC')
            ->all();

        // render view
        return $this->render('news',[
            'news' => $news,
        ]);
    }
}