<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\History as BaseHistory;


class History extends BaseHistory
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['order_id'], 'integer'],
            [['state', 'last_state'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state' => 'Состояние',
            'comment' => 'Комментарие',
            'create_time' => 'Дата добавления',
            'update_time' => 'Дата обновления',
            'last_state' => 'Предыдущее состояние',
            'order_id' => 'Заказ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
