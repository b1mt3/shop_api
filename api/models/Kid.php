<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Kid as BaseKid;

class Kid extends BaseKid
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
