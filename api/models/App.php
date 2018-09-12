<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\App as BaseApp;

/**
 * Description of Restaurant
 *
 * @author Firdaus
 */
class App extends BaseApp {
	
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
	
}
