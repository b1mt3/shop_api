<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Region as BaseRegion;

class Region extends BaseRegion
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistricts()
    {
        return $this->hasMany(District::className(), ['region_id' => 'id']);
    }
}
