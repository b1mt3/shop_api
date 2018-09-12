<?php

namespace frontend\modules\cashbox\models;

use Yii;
use common\models\Brand as MainBrand;

class Brand extends MainBrand
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id']);
    }
}
