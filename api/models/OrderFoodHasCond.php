<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\OrderFoodHasCond as BaseOrderFoodHasCond;


class OrderFoodHasCond extends OrderFoodHasCond
{
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_food_id', 'cond_id'], 'required'],
            [['order_food_id', 'cond_id'], 'integer'],
            [['cond_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cond::className(), 'targetAttribute' => ['cond_id' => 'id']],
            [['order_food_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderFood::className(), 'targetAttribute' => ['order_food_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_food_id' => 'Order Food ID',
            'cond_id' => 'Cond ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCond()
    {
        return $this->hasOne(Cond::className(), ['id' => 'cond_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFood()
    {
        return $this->hasOne(OrderFood::className(), ['id' => 'order_food_id']);
    }
}
