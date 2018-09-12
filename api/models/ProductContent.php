<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\ProductContent as BaseProductContent;

class ProductContent extends BaseProductContent
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
    public function getProductProperties()
    {
        return $this->hasMany(ProductProperty::className(), ['product_content_id' => 'id']);
    }
}
