<?php

namespace frontend\modules\api\models;

use Yii;

class Address extends \yii\db\ActiveRecord
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['id' => 'district_id']);
    }

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
        return $this->hasMany(Order::className(), ['address_id' => 'id']);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            foreach ($this->orders as $item)
            {
                $item->address_id=NULL;
                $item->update();
            }
            return true;
        }
        else
        {
          return false;
        }
    }

}
