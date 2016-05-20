<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%search_table}}".
 *
 * @property integer $id_search
 * @property string $name_search
 * @property string $type_search
 * @property integer $link_search
 */
class SearchTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%search_table}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_search', 'type_search', 'link_search'], 'required'],
            [['name_search'], 'string'],
            [['link_search'], 'integer'],
            [['type_search'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_search' => 'Id Search',
            'name_search' => 'Name Search',
            'type_search' => 'Type Search',
            'link_search' => 'Link Search',
        ];
    }
}
