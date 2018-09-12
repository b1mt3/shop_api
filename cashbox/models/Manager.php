<?php

namespace frontend\modules\cashbox\models;

use Yii;

class Manager extends \yii\db\ActiveRecord
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
        return $this->hasMany(Order::className(), ['manager_id' => 'id']);
    }
}
