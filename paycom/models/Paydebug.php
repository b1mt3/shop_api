<?php

namespace frontend\modules\paycom\models;

use Yii;

/**
 * This is the model class for table "paydebug".
 *
 * @property int $id
 * @property string $type
 * @property string $create_time
 * @property string $status
 * @property string $header
 * @property string $request
 * @property string $response
 */
class Paydebug extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paydebug';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time'], 'safe'],
            [['request', 'response'], 'string'],
            [['type', 'status'], 'string', 'max' => 255],
            [['header'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'create_time' => 'Create Time',
            'status' => 'Status',
            'header' => 'Header',
            'request' => 'Request',
            'response' => 'Response',
        ];
    }
}
