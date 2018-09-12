<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Auth as BaseAuth;

/**
 * Description of Restaurant
 *
 * @author Firdaus
 */
class Auth extends BaseAuth {
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
	
}
