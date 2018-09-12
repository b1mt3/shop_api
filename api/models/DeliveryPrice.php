<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\DeliveryPrice as BaseDeliveryPrice;

class DeliveryPrice extends BaseDeliveryPrice
{
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['id' => 'delivery_id']);
    }
}
