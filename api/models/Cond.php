<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Cond as BaseCond;


class Cond extends BaseCond
{
   

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFood()
    {
        return $this->hasOne(Food::className(), ['id' => 'food_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFoodHasConds()
    {
        return $this->hasMany(OrderFoodHasCond::className(), ['cond_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFoods()
    {
        return $this->hasMany(OrderFood::className(), ['id' => 'order_food_id'])->viaTable('order_food_has_cond', ['cond_id' => 'id']);
    }
}
