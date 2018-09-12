<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\PayType as BasePayType;


class PayType extends BasePayType
{
   
	public function getImagePath() {
		if($this->image_min && file_exists(Yii::getAlias('@images').'/paytype/'.$this->image_min)) {
			return Yii::getAlias('@images').'/paytype/'.$this->image_min;
		} else {
			return '';
		}
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['pay_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasPayTypes()
    {
        return $this->hasMany(RestaurantHasPayType::className(), ['pay_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['id' => 'restaurant_id'])->viaTable('restaurant_has_pay_type', ['pay_type_id' => 'id']);
    }
}
