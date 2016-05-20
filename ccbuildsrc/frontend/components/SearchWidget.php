<?php
/**
 * widget search
 */

namespace app\components;

use yii\base\Widget;

class SearchWidget extends Widget
{
    public function  run()
    {
        return $this->render('search');
    }
}