<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Additive as BaseAdditive;


class Additive extends BaseAdditive
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
    public function getOrderFoodHasAdditives()
    {
        return $this->hasMany(OrderFoodHasAdditive::className(), ['additive_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFoods()
    {
        return $this->hasMany(OrderFood::className(), ['id' => 'order_food_id'])->viaTable('order_food_has_additive', ['additive_id' => 'id']);
    }
}
