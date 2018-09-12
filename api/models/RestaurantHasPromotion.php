<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\RestaurantHasPromotion as BaseRestaurantHasPromotion;


class RestaurantHasPromotion extends BaseRestaurantHasPromotion
{
   
  

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'restaurant_id' => 'Restaurant ID',
            'promotion_id' => 'Promotion ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotion()
    {
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }
}
