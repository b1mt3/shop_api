<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\RestaurantHasFood as BaseRestaurantHasFood;


class RestaurantHasFood extends BaseRestaurantHasFood
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
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }
}
