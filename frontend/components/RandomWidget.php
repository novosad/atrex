<?php
/**
 * widget random project
 */

namespace app\components;

use yii\base\Widget;
use app\models\Product;

class RandomWidget extends Widget{
    public function run(){
        // query
        $product = Product::find()
            ->orderBy('RAND()')
            ->limit(3)
            ->all();

        //render view
        return $this->render('random',[
            'product' => $product,
        ]);
    }
}