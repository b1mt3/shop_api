<?php

namespace frontend\modules\cashbox\models;

use common\models\ReturnItems as MainReturnItems;
use Yii;

class ReturnItems extends MainReturnItems
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductItem()
    {
        return $this->hasOne(ProductItem::className(), ['id' => 'product_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRetExReason()
    {
        return $this->hasOne(RetExReason::className(), ['id' => 'ret_ex_reason_id']);
    }
}
