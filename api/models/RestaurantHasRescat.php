<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\RestaurantHasRescat as BaseRestaurantHasRescat;



class RestaurantHasRescat extends BaseRestaurantHasRescat
{
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRescat()
    {
        return $this->hasOne(Rescat::className(), ['id' => 'rescat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }
}
