<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%section}}".
 *
 * @property integer $id_section
 * @property integer $catalog_id
 * @property string $section_name
 * @property string $child
 *
 * @property Product[] $products
 * @property Catalog $catalog
 */
class Section extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%section}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catalog_id', 'section_name'], 'required'],
            [['catalog_id'], 'integer'],
            [['section_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_section' => 'Id Section',
            'catalog_id' => 'Catalog ID',
            'section_name' => 'Section Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['section_id' => 'id_section']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(Catalog::className(), ['id_catalog' => 'catalog_id']);
    }
}
