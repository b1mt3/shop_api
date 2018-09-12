<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\OrderFeedback as BaseOrderFeedback;

class OrderFeedback extends BaseOrderFeedback
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
