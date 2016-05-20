<?php
/**
 * widget slider
 */

namespace app\components;

use yii\base\Widget;

class SliderWidget extends Widget
{
    public function run(){
        return $this->render('slider');
    }
}