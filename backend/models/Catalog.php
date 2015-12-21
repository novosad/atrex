<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%catalog}}".
 *
 * @property integer $id_catalog
 * @property string $catalog_name
 *
 * @property Section[] $sections
 */
class Catalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%catalog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catalog_name'], 'required'],
            [['catalog_name'], 'string', 'max' => 50],
            [['catalog_photo'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_catalog' => 'Id Catalog',
            'catalog_name' => 'Catalog Name',
            'catalog_photo' => 'Catalog Photo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Section::className(), ['catalog_id' => 'id_catalog']);
    }
}
