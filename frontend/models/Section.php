<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%section}}".
 *
 * @property integer $id_section
 * @property integer $catalog_id
 * @property string $section_name
 * @property string $section_photo
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
            [['catalog_id', 'section_name', 'section_photo'], 'required'],
            [['catalog_id'], 'integer'],
            [['section_name'], 'string', 'max' => 50],
            [['section_photo'], 'string', 'max' => 30]
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
            'section_photo' => 'Section Photo',
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
