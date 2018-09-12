<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Card as BaseCard;

class Card extends BaseCard
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['card_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaycomReceipts()
    {
        return $this->hasMany(PaycomReceipt::className(), ['card_id' => 'id']);
    }
}
