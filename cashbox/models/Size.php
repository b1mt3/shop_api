<?php

namespace frontend\modules\cashbox\models;

use Yii;
use common\models\Size as BaseSize;

class Size extends BaseSize
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductItems()
    {
        return $this->hasMany(ProductItem::className(), ['size_id' => 'id']);
    }
}
