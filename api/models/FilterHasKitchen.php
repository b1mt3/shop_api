<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\FilterHasKitchen as BaseFilterHasKitchen;


class FilterHasKitchen extends BaseFilterHasKitchen
{
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilter()
    {
        return $this->hasOne(Filter::className(), ['id' => 'filter_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKitchen()
    {
        return $this->hasOne(Kitchen::className(), ['id' => 'kitchen_id']);
    }
}
