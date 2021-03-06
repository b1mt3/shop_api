<?php

namespace frontend\modules\manager\models;

use Yii;
use frontend\modules\manager\SearchIndex as BaseSearchIndex;

class SearchIndex extends BaseSearchIndex
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
