<?php

namespace frontend\modules\cashbox\models;

use Yii;
use common\models\ReturnTicket as BaseReturnTicket;

class ReturnTicket extends BaseReturnTicket
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProduct()
    {
        return $this->hasOne(OrderProduct::className(), ['id' => 'order_product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRetExReason()
    {
        return $this->hasOne(RetExReason::className(), ['id' => 'ret_ex_reason_id']);
    }
}
