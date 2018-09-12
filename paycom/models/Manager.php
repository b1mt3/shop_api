<?php

namespace frontend\modules\paycom\models;

use Yii;

/**
 * This is the model class for table "manager".
 *
 * @property int $id
 * @property int $user_id ID пользователя
 * @property string $last_connected Время последнего соединения
 * @property int $is_online В онлайне
 * @property int $number Порядковый номер менеджера
 * @property int $is_head Главный менеджер
 *
 * @property User $user
 * @property Order[] $orders
 */
class Manager extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'number'], 'required'],
            [['user_id', 'is_online', 'number', 'is_head'], 'integer'],
            [['last_connected'], 'safe'],
            [['number'], 'unique'],
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
            'user_id' => 'ID пользователя',
            'last_connected' => 'Время последнего соединения',
            'is_online' => 'В онлайне',
            'number' => 'Порядковый номер менеджера',
            'is_head' => 'Главный менеджер',
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
        return $this->hasMany(Order::className(), ['manager_id' => 'id']);
    }
}
