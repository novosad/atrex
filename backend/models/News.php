<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property integer $id_news
 * @property string $title_news
 * @property string $date_news
 * @property string $description_news
 * @property string $photo_news
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title_news', 'date_news', 'description_news', 'photo_news'], 'required'],
            [['date_news'], 'safe'],
            [['description_news'], 'string'],
            [['title_news'], 'string', 'max' => 200],
            [['photo_news'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_news' => 'Id News',
            'title_news' => 'Заголовок',
            'date_news' => 'Дата',
            'description_news' => 'Описание',
            'photo_news' => 'Фото',
        ];
    }
}
