<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Delivery as BaseDelivery;

class Delivery extends BaseDelivery
{
  
	

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryPrices()
    {
        return $this->hasMany(DeliveryPrice::className(), ['delivery_id' => 'id']);
    }
}
