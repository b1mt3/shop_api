<?php

namespace frontend\modules\cashbox\models;

use Yii;

use common\models\Userset as BaseUserset;


class Userset extends BaseUserset
{

	public function getSetData() {
		$notificationState=1;
		if(\Yii::$app->controller->app_token) {
				$appModel=  \frontend\modules\api\models\App::find()->where(['auth_token'=>\Yii::$app->controller->app_token])->one();
				if($appModel) {
					$notificationState=(int)$appModel->send_notification;
				}
			}
		return [
			'sms_notification'=>(int)  $this->sms_notification,
			'push_notification'=>$notificationState,
			'email_newsletters'=>(int)  $this->email_newsletters,
			'default_language'=> $this->default_language,
		];
	}

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
