<?php

namespace frontend\modules\api\controllers;

use Yii;
use frontend\modules\api\models\NumberConfirm;
use frontend\modules\api\models\User;
use frontend\modules\api\models\App;
use frontend\modules\api\models\Auth;
use frontend\modules\api\models\Order;
use frontend\modules\api\models\Address;

/**
 * Default controller for the `api` module
 */
class AuthController extends MainController
{
    public function actionRegistration()
    {
    		$params= $this->params;
    		if(!isset($params['phone'])) {
    			return $this->returnResponse(8104);
    		} else {
    			if(!Yii::$app->gFunctions->checkPhoneNumber(\Yii::$app->sms->trimNumber($params['phone']))) {
    				return $this->returnResponse(8105);
    			} else {

    				$reqType='';
    				$phone = \Yii::$app->sms->trimNumber($params['phone']);
    				$existsUser=  User::find()->where(['phone'=>$phone])->one();
    				if($existsUser) {
    					return $this->returnResponse(8106);
    				}
    				if(isset($params['email'])&&Yii::$app->gFunctions->validateEmail($params['email'])) {
    					$existsUser=  User::find()->where(['email'=>$params['email']])->one();
    					if($existsUser) {
    						return $this->returnResponse(8107);
    					}

    				}
    				if(!isset($params['password'])) {
    					return $this->returnResponse(8108);
    				} elseif(trim($params['password'])=='') {
    					return $this->returnResponse(8108);
    				}


    				$model=  NumberConfirm::find()->where(['number'=>$phone])->orderBy('create_time DESC')->one();
    				if($model) {
    					$time=time()-strtotime($model->last_send_time);
    					if($this->resendAllowTime>$time && $time>=0) {
    						return $this->returnResponse(8126, ['time_left'=>$time]);
    					}
    					if($this->codeExpireTime<(time()-strtotime($model->last_send_time))) {
    						$model->is_used=1;
    						$model->save(false);
    						$model=null;
    					}
    				}
    				if($model===NULL) {
    					$model=new NumberConfirm;
    					$model->number=(string)$phone;
    					$model->create_time=new \yii\db\Expression('NOW()');
    					$model->last_send_time=new \yii\db\Expression('NOW()');
    					if($this->mode=='test') {
    						$model->code=666666;
    					} else {
    						$model->code= rand(111111, 999999);
    					}
    					$model->hash=md5(uniqid().time().$model->code);
    					$model->attempt_count=0;
    					$model->ip=Yii::$app->getRequest()->getUserIP();
    					$model->is_used=0;
    					if(isset($params['app_data']['device_id']) && $params['app_data']['device_id']) {
    						$model->device_id=$params['app_data']['device_id'];
    					}
    					if(isset($params['app_data']['device_token']) && $params['app_data']['device_token']) {
    						$model->device_token=$params['app_data']['device_token'];
    					}
    					if(isset($params['app_data']['device_model']) && $params['app_data']['device_model']) {
    						$model->device_model=$params['app_data']['device_model'];
    					}
    					if(isset($params['app_data']['device_model']) && $params['app_data']['device_model']) {
    						$model->device_model=$params['app_data']['device_model'];
    					}
    					if(isset($params['app_data']['device_type']) && $params['app_data']['device_type']) {
    						$model->device_type=$params['app_data']['device_type'];
    					}
    					if(isset($params['app_data']['ios_mode']) && $params['app_data']['ios_mode'] && in_array($params['app_data']['ios_mode'], ['sandbox', 'production'])) {
    						$model->ios_mode=$params['app_data']['ios_mode'];
    					}

    					if(isset($params['email']) && $params['email']) {
    						$model->email=$params['email'];
    					}
    					if(isset($params['password']) && $params['password']) {
    						$model->password=$params['password'];
    					}
    					if(isset($params['name']) && $params['name']) {
    						$model->username=$params['name'];
    					}
    					$model->type='reg';
              $model->is_used=(string)$model->is_used;
    					$model->save();
    					$model->refresh();
    				} else {
    					$time=time()-strtotime($model->last_send_time);
    					if($this->resendAllowTime>$time && $time>=0) {
    						return $this->returnResponse(8109, ['time_left'=>$time]);
    					}
    					if($this->codeExpireTime<(time()-strtotime($model->last_send_time))) {
    						return $this->returnResponse(8110);
    					}
    				}
    				if($this->mode!='test') {
    					$sendState=Yii::$app->sms->send($phone, $model->code, false);
    					if(!$sendState) {
    						return $this->returnResponse(8111);
    					} else {
    						$model->last_send_time=new \yii\db\Expression('NOW()');
    						$model->save();
    						$model->refresh();
    					}
    				}
    				return $this->returnResponse(200, ['token'=>$model->hash]);
    			}
    		}
    }

    public function actionConfirm()
    {
  		$params=  $this->params;
  		if(!isset($params['token'])) {
  			return $this->returnResponse(8112);
  		} elseif(!isset($params['code'])) {
  			return $this->returnResponse(8113);
  		} else {
  			$model=  NumberConfirm::find()->where(['hash'=>$params['token']])
            ->andWhere(['is_used' => 0])->one();
  			if(!$model) {
  				return $this->returnResponse(8114);
  			} else {
  				if($model->code!=$params['code']) {
  					return $this->returnResponse(8115);
  				} else {
  					$user=  User::find()->where(['phone'=>$model->number])->one();
  					if($user && $user->is_active==0) {
  						return $this->returnResponse(8102);
  					}
  					if($user) {
  						if($this->mode=='test' && $user->test_mode==0) {
  							$user->test_mode=1;
  							$user->save(false);
  						}
  						if($this->mode!='test' && $user->test_mode==1) {
  							$user->test_mode=0;
  							$user->save(false);
  						}
  						if($model->device_id) {
  							$oldApp=  App::find()->where(['device_id'=>$model->device_id])->one();
  							if($oldApp) {
  								$oldApp->delete();
  							}
  						}
  						$app=new App;
  						$app->auth_token=md5(uniqid().time().$user->phone);
  						if($model->device_id) {
  							$app->device_id=$model->device_id;
  						}
  						if($model->device_token) {
  							$app->device_token=$model->device_token;
  						}
  						if($model->device_model) {
  							$app->device_model=$model->device_model;
  						}
  						if($model->device_type && in_array($model->device_type, ['android', 'ios'])) {
  							$app->device_type=$model->device_type;
  						}
  						if($model->ios_mode && in_array($model->ios_mode, ['sandbox', 'production'])) {
  							$app->ios_mode=$model->ios_mode;
  						}

  						$app->user_id=$user->id;
  						$app->create_time=new \yii\db\Expression('NOW()');
  						$app->last_visit=new \yii\db\Expression('NOW()');
  						$app->version = $this->version;
  						$app->source = 'code';
  						if($this->mode=='test') {
  							$app->test_mode=1;
  						} else {
  							$app->test_mode=0;
  						}

  						$model->is_used=1;
  						$model->save(false);
              $app->test_mode = (string) $app->test_mode;
              $assignAddress = Address :: findOne(['user_phone' => $model->number, 'user_id' => NULL]);
              $assignAddress->user_id = $user->id;
              $assignAddress->save(false);
              if ($model->order_id != 0) {
                  $ballExpenseQuery = Order :: find()
                      -> where(['id' => $model->order_id])
                      -> one();
                  if (!$ballExpenseQuery) {
                      return $this->returnResponse(8157);
                  }
                  else {
                      if ($user->ball-$ballExpenseQuery->ball_price < 0) {
                          return $this->returnResponse(8155);
                      }
                      else {
                          $user->ball -= $ballExpenseQuery->ball_price;
                          $user->save();
                      }
                  }
                  $ballExpenseQuery->user_id = $user->id;
                  $ballExpenseQuery->status = 'created';
                  $ballExpenseQuery->update_time = new \yii\db\Expression('NOW()');
                  $ballExpenseQuery->user_name = $user->user_name.' '.$user->user_surname;
                  $ballExpenseQuery->save();
              }
  						if($app->save()) {
  							$app->refresh();
  							$this->updateUserData($user, $app);
  							return $this->returnResponse(200, ['auth_token'=>$app->auth_token, 'profile'=>$user->getProfileData()]);
  						} else {
                return $app->errors;
  							return $this->returnResponse(8116);
  						}
  					} else {
  						if($model->type=='reg') {
  							$password=$model->password;
  							$email=$model->email;
  						} else {
  							$password=  Yii::$app->gFunctions->generatePassword();
  							$email=NULL;
  						}
  						$newUser=new User();
  						$newUser->phone=$model->number;
  						$newUser->email=$email;
  						$newUser->create_time=new \yii\db\Expression('NOW()');
  						$newUser->role=  User::ROLE_CLIENT;
  						$newUser->is_active=1;
  						$newUser->user_name=$model->username;
  						if($this->mode=='test') {
  							$newUser->test_mode=1;
  						} else {
  							$newUser->test_mode=0;
  						}
  						$newUser->pass_md5=md5($password);
  						$newUser->email_confirmed=0;
  						$newUser->added_source='app';
  						$newUser->setPassword($password);
  						$newUser->generateAuthKey();
              $newUser->role = (string)$newUser->role;
              $newUser->is_active = (string)$newUser->is_active;
              $newUser->test_mode = (string)$newUser->test_mode;
              $newUser->email_confirmed = (string)$newUser->email_confirmed;
  						if($newUser->save()) {
  							$newUser->refresh();
                $assignAddress = Address :: find()
                                 -> where(['user_phone' => $model->number])
                                 -> one();
                if ($assignAddress) $assignAddress->user_id = $newUser->id;
  							if($model->device_id) {
  								$oldApp=  App::find()->where(['device_id'=>$model->device_id])->one();
  								if($oldApp) {
  									$oldApp->delete();
  								}
  							}
                if ($model->order_id != 0) {
                    $ballExpenseQuery = Order :: find()
                        -> where(['id' => $model->order_id])
                        -> one();
                    if (!$ballExpenseQuery) {
                        return $this->returnResponse(8157);
                    }
                    else {
                        if ($newUser->ball-$ballExpenseQuery->ball_price < 0) {
                            return $this->returnResponse(8155);
                        }
                        else {
                            $newUser->ball -= $ballExpenseQuery->ball_price;
                            $newUser->save();
                        }
                    }
                    $ballExpenseQuery->user_id = $newUser->id;
                    $ballExpenseQuery->status = 'created';
                    $ballExpenseQuery->update_time = new \yii\db\Expression('NOW()');
                    $ballExpenseQuery->user_name = $newUser->user_name.' '.$newUser->user_surname;
                    $ballExpenseQuery->save();
                }
  							$app=new App;
  							$app->auth_token=md5(uniqid().time().$newUser->phone);
  							if($model->device_id) {
  								$app->device_id=$model->device_id;
  							}
  							if($model->device_token) {
  								$app->device_token=$model->device_token;
  							}
  							if($model->device_model) {
  								$app->device_model=$model->device_model;
  							}
  							if($model->device_type && in_array($model->device_type, ['android', 'ios'])) {
  								$app->device_type=$model->device_type;
  							}
  							if($model->ios_mode && in_array($model->ios_mode, ['sandbox', 'production'])) {
  								$app->ios_mode=$model->ios_mode;
  							}
  							$app->user_id=$newUser->id;
  							$app->create_time=new \yii\db\Expression('NOW()');
  							$app->last_visit=new \yii\db\Expression('NOW()');
  							$app->version=  $this->version;
  							$app->source=  'code';
  							if($this->mode=='test') {
  								$app->test_mode=1;
  							} else {
  								$app->test_mode=0;
  							}
  							$model->is_used=1;
  							$model->password=NULL;
  							$model->save(false);

  							if($app->save()) {
  								$app->refresh();
  								$this->updateUserData($newUser, $app);
  								return $this->returnResponse(200, ['auth_token'=>$app->auth_token, 'profile'=>$newUser->getProfileData()]);
  							} else {
  								return $this->returnResponse(8116);
  							}
  						} else {
  							return $this->returnResponse(8117, $newUser->errors);
  						}
  					}
  				}
  			}
  		}
    }

	public function actionResend() {
		$params=  $this->params;
		if((!isset($params['token']))&&trim($params['token'])=='') {
			return $this->returnResponse(438);
		}
		$model=  NumberConfirm::find()->where(['hash'=>$params['token']])->one();
		if(!$model) {
			return $this->returnResponse(430);
		} else {

			$time=time()-strtotime($model->last_send_time);
			if($this->resendAllowTime>$time) {
				return $this->returnResponse(8109, ['time_left'=>$time]);
			}

			if($this->mode!='test') {
				$sendState=Yii::$app->sms->send($model->number, $model->code, false);
				if(!$sendState) {
					return $this->returnResponse(427);
				} else {
					$model->last_send_time=new \yii\db\Expression('NOW()');
					$model->save();
					$model->refresh();
				}
			}
			return $this->returnResponse(200, ['sent'=>1]);
		}
	}


	public function actionTemptoken()
  {
		$params=  $this->params;

		if(!$params['phone']) {
			return $this->returnResponse(8137);
		}

		$phone=\Yii::$app->sms->trimNumber($params['phone']);
		$user= User::find()->where(['phone'=>$phone])->one();
		if($user) {
			if($user->is_active==0) {
				return $this->returnResponse(8102);
			}
  		if(!isset($params['app_data']['device_id'])) {
  			return $this->returnResponse(8137);
  		} elseif(trim($params['app_data']['device_id'])=='') {
  			return $this->returnResponse(8137);
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
				return $this->returnResponse(8146, ['reason' => $newAuth->errors]);
			  }
  		} else {
  			return $this->returnResponse(8100);
  		}
    }

  public function actionToken()
  {
        $params=  $this->params;
  	if(!isset($params['token'])) {
  		return $this->returnResponse(8137);
  	}
  	if(!isset($params['pass_hash'])) {
  		return $this->returnResponse(8137);
  	}
  	$appAuth = Auth::find()->where(['hash'=>$params['token']])->one();

  	if($appAuth==null) {
  		return $this->returnResponse(8150);
  	}
  	$user=$appAuth->user;
  	if($user->is_active!=1) {
  		return $this->returnResponse(8102);
  	}
  	$hash=hash_hmac('sha256', $user->pass_md5, $params['token']);
  	if($hash != $params['pass_hash']) {
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
  	$app->version=  $this->version;
  	$app->source=  'password';
  	if($this->mode=='test') {
  		$app->test_mode=1;
  	} else {
  		$app->test_mode=0;
  	}

  	if($app->save()) {
  		$app->refresh();
  		$this->updateUserData($user, $app);
  		$appAuth->delete();
  		return $this->returnResponse(200, ['auth_token'=>$app->auth_token, 'profile'=>$user->getProfileData()]);
  	} else {
  		return $this->returnResponse(8116);
  	}

  }

  public function actionRestore()
  {
      $params = $this->params;

  		if(!$params['phone']) {
  			return $this->returnResponse(8137);
  		}

  		$phone=\Yii::$app->sms->trimNumber($params['phone']);
  		$user= User::find()->where(['phone'=>$phone])->one();
      if ($user->is_active == 0) return $this->returnResponse(8102);
      $model =  NumberConfirm::find()->where(['number'=>$phone])->orderBy('create_time DESC')->one();
      if($model) {
        $time=time()-strtotime($model->last_send_time);
        if($this->resendAllowTime>$time && $time>=0) {
          return $this->returnResponse(8126, ['time_left'=>$this->resendAllowTime-$time]);
        }
        if($this->codeExpireTime<(time()-strtotime($model->last_send_time))) {
          $model->is_used=1;
          $model->save(false);
          $model=null;
        }
      }

      if($model===NULL) {
        $model=new NumberConfirm;
        $model->number=(string)$phone;
        $model->create_time=new \yii\db\Expression('NOW()');
        $model->last_send_time=new \yii\db\Expression('NOW()');
        if($this->mode=='test') {
          $model->code=666666;
        } else {
          $model->code= rand(111111, 999999);
        }
        $model->hash=md5(uniqid().time().$model->code);
        $model->attempt_count=0;
        $model->ip=Yii::$app->getRequest()->getUserIP();
        $model->is_used=0;
        if(isset($params['app_data']['device_id']) && $params['app_data']['device_id']) {
          $model->device_id=$params['app_data']['device_id'];
        }
        if(isset($params['app_data']['device_token']) && $params['app_data']['device_token']) {
          $model->device_token=$params['app_data']['device_token'];
        }
        if(isset($params['app_data']['device_model']) && $params['app_data']['device_model']) {
          $model->device_model=$params['app_data']['device_model'];
        }
        if(isset($params['app_data']['device_model']) && $params['app_data']['device_model']) {
          $model->device_model=$params['app_data']['device_model'];
        }
        if(isset($params['app_data']['device_type']) && $params['app_data']['device_type']) {
          $model->device_type=$params['app_data']['device_type'];
        }
        if(isset($params['app_data']['ios_mode']) && $params['app_data']['ios_mode'] && in_array($params['app_data']['ios_mode'], ['sandbox', 'production'])) {
          $model->ios_mode=$params['app_data']['ios_mode'];
        }
        $model->email = $user->email;
        $model->username = $user->user_name.' '.$user->user_surname;
        $model->type='restore';
        $model->is_used=(string)$model->is_used;
        $model->save();
        $model->refresh();
        if($this->mode!='test') {
          $sendState=Yii::$app->sms->send($phone, $model->code, false);
          if(!$sendState) {
            return $this->returnResponse(8111);
          } else {
            $model->last_send_time=new \yii\db\Expression('NOW()');
            $model->save();
            $model->refresh();
          }
        }
        return $this->returnResponse(200);
      }
  }

  public function actionChangepassword()
  {
      $params = $this->params;
      if(!isset($params['phone'])) {
        return $this->returnResponse(8104);
      }
      if(!isset($params['code'])) {
  			return $this->returnResponse(8113);
  		}
      else {
       $phone = \Yii::$app->sms->trimNumber($params['phone']);
       $fiveMinuteWindow = date("Y-m-d H:i:s", strtotime("-5 minutes"));
       $model =  NumberConfirm::find()->where(['number'=>$phone])
           -> andWhere(['is_used' => 0])
           -> andWhere('last_send_time >="'.$fiveMinuteWindow.'"')
           -> one();
       if(!$model) {
         return $this->returnResponse(8114);
       }
       else {
         if($model->code!=$params['code']) {
           return $this->returnResponse(8115);
         } else if ($model->type == 'restore') {
           $user=  User::find()->where(['phone'=>$model->number])->one();
           if($user && $user->is_active==0) {
             return $this->returnResponse(8102);
           }
           if($user) {
               if(isset($params['password'])) {
                  $model->password = $params['password'];
                  $user->pass_md5=md5($params['password']);
                  $user->setPassword($params['password']);
                  $user->generateAuthKey();
                  $user->save();
    							$app=new App;
    							$app->auth_token=md5(uniqid().time().$user->phone);
    							if($model->device_id) {
    								$app->device_id=$model->device_id;
    							}
    							if($model->device_token) {
    								$app->device_token=$model->device_token;
    							}
    							if($model->device_model) {
    								$app->device_model=$model->device_model;
    							}
    							if($model->device_type && in_array($model->device_type, ['android', 'ios'])) {
    								$app->device_type=$model->device_type;
    							}
    							if($model->ios_mode && in_array($model->ios_mode, ['sandbox', 'production'])) {
    								$app->ios_mode=$model->ios_mode;
    							}
    							$app->user_id=$user->id;
    							$app->create_time=new \yii\db\Expression('NOW()');
    							$app->last_visit=new \yii\db\Expression('NOW()');
    							$app->version=  $this->version;
    							$app->source=  'code';
    							if($this->mode=='test') {
    								$app->test_mode=1;
    							} else {
    								$app->test_mode=0;
    							}
    							$model->is_used=1;
    							$model->password=NULL;
    							$model->save(false);
    							if($app->save()) {
    								$app->refresh();
    								$this->updateUserData($user, $app);
    								return $this->returnResponse(200, ['auth_token'=>$app->auth_token]);
    							} else {
    								    return $this->returnResponse(8116);
                    }
                }
                else return $this->returnResponse(8108);
             }
           else return $this->returnResponse(8100);
         }
         else return $this->returnResponse(8105);
       }
    }
  }
    // public function actionFacebook() {}

    // public functon actionVkontakte() {}

}
