<?php

namespace frontend\modules\manager\models;

use Yii;
use common\models\Brand as BaseBrand;

class Brand extends BaseBrand
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id']);
    }
}
