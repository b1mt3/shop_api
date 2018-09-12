<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\SearchIndex as BaseSearchIndex;

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
