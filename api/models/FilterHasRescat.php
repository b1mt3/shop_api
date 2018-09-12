<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\FilterHasRescat as BaseFilterHasRescat;


class FilterHasRescat extends BaseFilterHasRescat
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
    public function getRescat()
    {
        return $this->hasOne(Rescat::className(), ['id' => 'rescat_id']);
    }
}
