<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\OrderFoodHasAdditive as BaseOrderFoodHasAdditive;


class OrderFoodHasAdditive extends BaseOrderFoodHasAdditive
{
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_food_id', 'additive_id'], 'required'],
            [['order_food_id', 'additive_id', 'count', 'price'], 'integer'],
            [['additive_id'], 'exist', 'skipOnError' => true, 'targetClass' => Additive::className(), 'targetAttribute' => ['additive_id' => 'id']],
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
            'additive_id' => 'Additive ID',
            'count' => 'Количество',
            'price' => 'Стоимость на одного',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditive()
    {
        return $this->hasOne(Additive::className(), ['id' => 'additive_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFood()
    {
        return $this->hasOne(OrderFood::className(), ['id' => 'order_food_id']);
    }
}
