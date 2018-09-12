<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\PageData as MainPageData;

class PageData extends MainPageData
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }
}
