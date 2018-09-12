<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\UserHasProduct as BaseUserHasProduct;

class UserHasProduct extends BaseUserHasProduct
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
