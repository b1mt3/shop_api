<?php

namespace frontend\modules\paycom\models;

use Yii;

/**
 * This is the model class for table "payset".
 *
 * @property int $id
 * @property string $password
 * @property string $provider
 */
class Payset extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payset';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password'], 'string', 'max' => 255],
            [['provider'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'Password',
            'provider' => 'Provider',
        ];
    }
}
