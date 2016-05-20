<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id_product
 * @property integer $section_id
 * @property string $product_name
 * @property string $description
 * @property string $price
 * @property string $photo
 *
 * @property Section $section
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id', 'product_name', 'description', 'price', 'photo'], 'required'],
            [['section_id'], 'integer'],
            [['product_name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 225],
            [['price'], 'integer'],
            [['photo'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_product' => 'Id Product',
            'section_id' => 'Section ID',
            'product_name' => 'Product Name',
            'description' => 'Description',
            'price' => 'Price',
            'photo' => 'Photo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSection()
    {
        return $this->hasOne(Section::className(), ['id_section' => 'section_id']);
    }
}
