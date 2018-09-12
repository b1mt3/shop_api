<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\RestaurantHasPayType as BaseRestaurantHasPayType;

class RestaurantHasPayType extends BaseRestaurantHasPayType
{
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayType()
    {
        return $this->hasOne(PayType::className(), ['id' => 'pay_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }
}
