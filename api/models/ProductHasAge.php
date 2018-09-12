<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\ProductHasAge as BaseProductHasAge;


class ProductHasAge extends BaseProductHasAge
{
  

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAge()
    {
        return $this->hasOne(Age::className(), ['id' => 'age_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
