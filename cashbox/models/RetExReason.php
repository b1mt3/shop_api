<?php

namespace frontend\modules\cashbox\models;

use common\models\RetExReason as BaseRetExReason;
use Yii;

class RetExReason extends BaseRetExReason
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturnItems()
    {
        return $this->hasMany(ReturnItems::className(), ['ret_ex_reason_id' => 'id']);
    }
}
