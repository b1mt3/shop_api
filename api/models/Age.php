<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Age as BaseAge;

class Age extends BaseAge
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductHasAges()
    {
        return $this->hasMany(ProductHasAge::className(), ['age_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_has_age', ['age_id' => 'id']);
    }
}
