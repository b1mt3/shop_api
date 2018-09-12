<?php

namespace frontend\modules\manager\models;

use Yii;
use common\models\Color as BaseColor;

class Color extends BaseColor
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductItems()
    {
        return $this->hasMany(ProductItem::className(), ['color_id' => 'id']);
    }
}
