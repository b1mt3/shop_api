<?php

namespace frontend\modules\manager\controllers;

use Yii;
use frontend\modules\manager\models\User;

class AuthController extends MainController
{
    public function actionTemptoken()
    {
    		$params = $this->params;
    		if(!isset($params['phone'])) {
      			return $this->returnResponse(8143);
    		}

    		if(!$params['phone']) {
    		    return $this->returnResponse(8143);
    		}

    		$phone = Yii::$app->sms->trimNumber($params['phone']);
    		$user = User::find()->where(['phone' => $phone, 'role' => 15])->one();
 		    if($user) {
			      if($user->is_active == 0) {
				        return $this->returnResponse(8102);
			      }
      		  if(!isset($params['app_data']['device_id'])) {
      			    return $this->returnResponse(8145);
      		  } elseif(trim($params['app_data']['device_id'])=='') {
      			    return $this->returnResponse(8145);
      		  }
    			  $oldAuth=Auth::find()->where(['device_id'=>$params['app_data']['device_id']])->all();
    			  foreach ($oldAuth as $item) {
    				  $item->delete();
    			  }
      			$newAuth=new Auth();
      			$newAuth->hash=md5(uniqid().time().$phone);
      			$newAuth->create_time=new \yii\db\Expression('NOW()');
      			$newAuth->user_id=$user->id;
      			$newAuth->attempt_count=0;
      			$newAuth->ip=Yii::$app->getRequest()->getUserIP();
      			$newAuth->device_id=$params['app_data']['device_id'];
      			if(isset($params['app_data']['device_token'])&&$params['app_data']['device_token']) {
      				  $newAuth->device_token=$params['app_data']['device_token'];
      			}
      			if(isset($params['app_data']['device_model'])&&$params['app_data']['device_model']) {
      				  $newAuth->device_model=$params['app_data']['device_model'];
      			}
      			if(isset($params['app_data']['device_type'])&&$params['app_data']['device_type']) {
      				  $newAuth->device_type=$params['app_data']['device_type'];
      			}
      			if(isset($params['app_data']['ios_mode']) && $params['app_data']['ios_mode'] && in_array($params['app_data']['ios_mode'], ['sandbox', 'production'])) {
      				  $newAuth->ios_mode=$params['app_data']['ios_mode'];
      			}
      			if($newAuth->save()) {
      				  return $this->returnResponse(200, ['token'=>$newAuth->hash]);
      			} else {
      				  return $this->returnResponse(8146);
      			  }
  		    } else {
  			      return $this->returnResponse(8100);
  	    }
    }

    public function actionToken()
    {
        $params=  $this->params;
    		if(!isset($params['token'])) {
    			return $this->returnResponse(8147);
    		}
    		if(!isset($params['pass_hash'])) {
    			return $this->returnResponse(8147);
    		}
    		$appAuth=  Auth::find()->where(['hash'=>$params['token']])->one();

    		if($appAuth==null) {
    			return $this->returnResponse(8148);
    		}
    		$user=$appAuth->user;
    		if($user->is_active!=1) {
    			return $this->returnResponse(8102);
    		}
    		$hash=hash_hmac('sha256', $user->pass_md5, $params['token']);
    		if($hash!=$params['pass_hash']) {
    			return $this->returnResponse(8118);
    		}
    		$oldApp=  App::find()->where(['device_id'=>$appAuth->device_id])->one();
    		if($oldApp) {
    			$oldApp->delete();
    		}
    		$app=new App;
    		$app->auth_token=md5(uniqid().time().$user->phone);
    		if($appAuth->device_id) {
    			$app->device_id=$appAuth->device_id;
    		}
    		if($appAuth->device_token) {
    			$app->device_token=$appAuth->device_token;
    		}
    		if($appAuth->device_model) {
    			$app->device_model=$appAuth->device_model;
    		}
    		if($appAuth->device_type && in_array($appAuth->device_type, ['android', 'ios'])) {
    			$app->device_type=$appAuth->device_type;
    		}
    		if($appAuth->ios_mode && in_array($appAuth->ios_mode, ['sandbox', 'production'])) {
    			$app->ios_mode=$appAuth->ios_mode;
    		}
    		$app->user_id=$user->id;
    		$app->create_time=new \yii\db\Expression('NOW()');
    		$app->last_visit=new \yii\db\Expression('NOW()');
    		$app->version= $this->version;
    		$app->source= 'password';
    		if($this->mode=='test') {
    			$app->test_mode=1;
    		} else {
    			$app->test_mode=0;
    		}

    		if($app->save()) {
    			$app->refresh();
    			$this->updateUserData($user, $app);
    			$appAuth->delete();
    			return $this->returnResponse(200, ['auth_token'=>$app->auth_token]);
    		} else {
    			return $this->returnResponse(8116);
    		}

    }
}
