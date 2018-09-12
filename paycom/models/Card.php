<?php

namespace frontend\modules\paycom\models;

use Yii;

/**
 * This is the model class for table "card".
 *
 * @property int $id
 * @property string $name Название
 * @property string $number Номер
 * @property string $expire Дата истечения
 * @property string $token Токен
 * @property int $recurrent Повторное использование
 * @property int $verify Подтвержден
 * @property int $user_id
 *
 * @property User $user
 * @property Order[] $orders
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recurrent', 'verify', 'user_id'], 'integer'],
            [['user_id'], 'required'],
            [['name', 'token'], 'string', 'max' => 255],
            [['number'], 'string', 'max' => 16],
            [['expire'], 'string', 'max' => 5],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'number' => 'Номер',
            'expire' => 'Дата истечения',
            'token' => 'Токен',
            'recurrent' => 'Повторное использование',
            'verify' => 'Подтвержден',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['card_id' => 'id']);
    }
}
