<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%review}}".
 *
 * @property integer $id_review
 * @property string $review_date
 * @property string $review_name
 * @property integer $product_id
 * @property string $review
 *
 * @property Product $product
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%review}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['review_date', 'review_name', 'product_id', 'review'], 'required'],
            [['review_date'], 'safe'],
            [['product_id'], 'integer'],
            [['review_name'], 'string', 'max' => 100],
            [['review'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_review' => 'Id Review',
            'review_date' => 'Review Date',
            'review_name' => 'Review Name',
            'product_id' => 'Product ID',
            'review' => 'Review',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id_product' => 'product_id']);
    }
}
