<?php

namespace frontend\modules\cashbox\models;

use Yii;
use common\models\Producer as BaseProducer;

class Producer extends BaseProducer
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['producer_id' => 'id']);
    }
}
