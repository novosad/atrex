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
 * @property string $review_moderation
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
            [['review_date', 'review_name', 'product_id', 'review', 'review_moderation'], 'required'],
            [['review_date'], 'safe'],
            [['product_id'], 'integer'],
            [['review_moderation'], 'string'],
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
            'review_date' => 'Дата',
            'review_name' => 'Пользователь',
            'product_id' => 'Product ID',
            'review' => 'Отзыв',
            'review_moderation' => 'Модерация',
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
