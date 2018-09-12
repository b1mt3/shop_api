<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Page as BasePage;

class Page extends BasePage
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageDatas()
    {
        return $this->hasMany(PageData::className(), ['page_id' => 'id']);
    }
}
