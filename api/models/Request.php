<?php

namespace frontend\modules\api\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property integer $id
 * @property string $create_time
 * @property string $request
 * @property string $request_data
 * @property string $response
 * @property string $response_data
 * @property string $user
 * @property string $version
 * @property string $header_data
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time'], 'safe'],
            [['request_data', 'response_data', 'header_data'], 'string'],
            [['request', 'response', 'user'], 'string', 'max' => 255],
            [['version'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'create_time' => 'Дата запроса',
            'request' => 'Запрос',
            'request_data' => 'Данные запроса',
            'response' => 'Ответ',
            'response_data' => 'Данные ответа',
            'user' => 'Позьзователь',
            'version' => 'Версия',
            'header_data' => 'Headers',
        ];
    }
}
