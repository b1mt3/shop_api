<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\UserHasPromotion as BaseUserHasPromotion;


class UserHasPromotion extends BaseUserHasPromotion
{
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromotion()
    {
        return $this->hasOne(Promotion::className(), ['id' => 'promotion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
