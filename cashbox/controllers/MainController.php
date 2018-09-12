<?php

namespace frontend\modules\cashbox\controllers;

use Yii;
use frontend\modules\cashbox\models\Request;

/**
 * Default controller for the `cashbox` module
 */
class MainController extends DefaultController
{

	public $enableCsrfValidation = false;

	public $reqDebug=[
		'id'=>0,
		'data'=>[
			'request'=>'',
			'request_data'=>'',
			'response'=>'',
			'response_data'=>'',
			'user'=>'',
			'version'=>'',
			'header_data'=>'',
		]
	];
	public $user=null;
	public $language='ru';
	public $version='v1';
	public $responseStatus=[];
	public $mode='release';
	public $params=null;
	public $app_token='';


	public function beforeAction($action) {

		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		if($this->serviceMode=='disabled') {
			header('content-type: application/json; charset=UTF-8');
			Yii::$app->response->statusCode = 497;
			echo json_encode($this->returnResponse(497)); exit;
		}
		$this->responseStatus=  $this->returnResponse(200);
		$this->parseHeader();
		$this->reqDebug['data']['request_data']=file_get_contents('php://input');
		$this->reqDebug['data']['request']=  Yii::$app->request->absoluteUrl;
		$this->debugWrite();
		if($this->serviceMode=='test' && $this->mode!='test') {
			header('content-type: application/json; charset=UTF-8');
			Yii::$app->response->statusCode = 497;
			echo json_encode($this->returnResponse(497)); exit;
		}
		if(isset($this->responseStatus['status'])&&$this->responseStatus['status']!==200) {
			header('content-type: application/json; charset=UTF-8');

			echo json_encode($this->returnResponse($this->responseStatus['status'])); exit;
		}
		$userData=  json_decode(file_get_contents('php://input'), true);
		if($userData && is_array($userData) && isset($userData['params'])) {
			$this->params=$userData['params'];
		}
		return parent::beforeAction($action);
	}
	public function afterAction($action, $result) {
		$this->reqDebug['data']['response_data']=  json_encode($result);
		$this->debugWrite();

		return parent::afterAction($action, $result);
	}

	public function debugWrite() {
		if($this->debugStatus){
			$reqDebug=  $this->reqDebug;
			if((int)$reqDebug['id']) {
				$reqModel=  Request::findOne((int)$reqDebug['id']);
				if($reqModel==null) {
					$reqModel=new Request;
				}
			} else {
				$reqModel=new Request;
			}
			$data=$reqDebug['data'];

			$reqModel->create_time=new \yii\db\Expression('NOW()');
			if(isset($data['request'])) {
				$reqModel->request=$data['request'];
			}
			if(isset($data['request_data'])) {
				$reqModel->request_data=$data['request_data'];
			}
			if(isset($data['response'])) {
				$reqModel->response=$data['response'];
			}
			if(isset($data['response_data'])) {
				$reqModel->response_data=$data['response_data'];
			}
			if(isset($data['user'])) {
				$reqModel->user=$data['user'];
			}
			if(isset($data['version'])) {
				$reqModel->version=$data['version'];
			}
			if(isset($data['header_data'])) {
				$reqModel->header_data=$data['header_data'];
			}
			if($reqModel->save()) {
				$this->reqDebug['id']=$reqModel->id;
			}
		}

	}

	public function parseHeader() {
		$request=new \yii\web\Request;

		$authHeader = $request->getHeaders()->get('Auth');
		$languageHeader = $request->getHeaders()->get('lang');
		$versionHeader = $request->getHeaders()->get('v');
		$modeHeader = $request->getHeaders()->get('mode');
		if($modeHeader=='test') {
			$this->mode='test';
		}

		if($versionHeader) {
			$this->reqDebug['data']['version']=$versionHeader;
			$this->version=$versionHeader;
		} else {
			$this->reqDebug['data']['version']='v1';
			$this->version='v1';
		}

		$this->reqDebug['data']['header_data']=json_encode(['lang'=>$this->language, 'v'=>$this->version, 'auth_token'=>$authHeader, 'mode'=>$modeHeader]);

		if($authHeader){
			$app = \frontend\modules\cashbox\models\Seller::find()->where(['auth_token'=>$authHeader])->one();
			if($app===null) {
				$this->responseStatus=  $this->returnResponse(8100);
				$this->reqDebug['data']['user']='(not found)';
			} else {
				if($app->is_active!=1) {
					$this->responseStatus=  $this->returnResponse(422);
					$this->reqDebug['data']['user']='(blocked)';
				} else {
					$this->user=$app;
						$this->app_token=$authHeader;
					$this->reqDebug['data']['user']=$this->user->number.': '.$this->user->id;
				}
			}
		} else {
			if (Yii :: $app->controller->id == 'auth' && Yii :: $app->controller->action->id == 'index') {
          $this->reqDebug['data']['user']='(without user)';
			}
			else
			 {
				  $this->reqDebug['data']['user']='(without user)';
				// $this->reqDebug['data']['user']='(not found)';
			  // $this->responseStatus=  $this->returnResponse(8100);
			 }
		}

		if($languageHeader && in_array($languageHeader, ['ru', 'uz'])) {
			$this->language=$languageHeader;
		} else {
				$this->language='ru';
			}
		\Yii::$app->language=$this->language;

	}

	public function returnResponse($code, $data=[]) {
		if($code!=200) {
			Yii::$app->response->statusCode = 497;
		}
		$ret=[];
		$lang=  $this->language;
		$ret=  $this->getErrorMessage($code, $data);

		$this->reqDebug['data']['response_data']=  json_encode($ret);
		if($this->debugStatus) {
			$ret['req_id']=  $this->reqDebug['id'];
		}
		return $ret;
	}

	public function getErrorMessage($code, $data) {
		$lang=  $this->language;
		if ($code == 200) {
			return [
				'status' => 200,
				'message' => '',
				'data' => (object) $data,
			];
		}
		$ret= [
			'status'=>498,
			'message'=> ($lang=='uz')?'Nomalum xatolik':'Неизвестная ошибка',
			'data'=>(object)$data,
		];
		$errMessModel= \frontend\modules\cashbox\models\ErrorMessage::find()->where(['code'=>$code])->one();
		if($errMessModel) {
			$this->reqDebug['data']['response']=($code==200)?'success 200':'error '.$code;
			$messageVarName='message_'.$lang;
			$ret= [
				'status'=>$code,
				'message'=>  $errMessModel->$messageVarName,
				'data'=>(object)$data,
			];
		} else {
			$this->reqDebug['data']['response']=498;
		}
		return $ret;
	}



	public function actionError()
	{

		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$exception = Yii::$app->errorHandler->exception;
		if(is_object($exception)) {
			if($exception->statusCode==404) {
				return $this->returnResponse(404);
			}
			if($exception->statusCode==403) {
				return $this->returnResponse(403);
			}
		}
		return $this->returnResponse(499);

	}



	public function updateUserData($user=null, $app=null) {
		if($app) {
			$app->last_visit=new \yii\db\Expression('NOW()');
			if($this->mode=='test') {
				$app->test_mode=1;
			} else {
				$app->test_mode=0;
			}
			$app->save(false);
		}
		if($user) {
			$this->user=$user;
		}
	}

	public function actionTest() {
//		return \Yii::$app->geoLoc->getDistances(
//			[['lat'=>43.306366,'lon'=> 76.930717], ['lat'=>43.106366,'lon'=> 76.930717], ['lat'=>43.506366,'lon'=> 76.830717]],['lat'=>43.180786,'lon'=>76.862739]
//			, 'uz');
////		$basket=new \frontend\components\ShoppingCart([], 'api');
//		return $basket;
		return [md5('test'), hash_hmac('sha256', md5('test'), 'b73da6b778504a771c31ea13c1509809')];
	}

}
