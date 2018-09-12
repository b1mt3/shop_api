<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\RestaurantHasKitchen as BaseRestaurantHasKitchen;


class RestaurantHasKitchen extends BaseRestaurantHasKitchen
{
   

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKitchen()
    {
        return $this->hasOne(Kitchen::className(), ['id' => 'kitchen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }
}
