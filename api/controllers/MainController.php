<?php

namespace frontend\modules\api\controllers;

use Yii;
use frontend\modules\api\models\Request;

/**
 * Default controller for the `api` module
 */
class MainController extends DefaultController {

	public $enableCsrfValidation = false;
	public $reqDebug = [
		'id' => 0,
		'data' => [
			'request' => '',
			'request_data' => '',
			'response' => '',
			'response_data' => '',
			'user' => '',
			'version' => '',
			'header_data' => '',
		]
	];
	public $user = null;
	public $language = 'ru';
	public $version = 'v1';
	public $responseStatus = [];
	public $mode = 'release';
	public $params = null;
	public $app_token = '';

	public function beforeAction($action) {
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		if ($this->serviceMode == 'disabled') {
			header('content-type: application/json; charset=UTF-8');
			Yii::$app->response->statusCode = 8101;
			echo json_encode($this->returnResponse(8101));
			exit;
		}
		$this->responseStatus = $this->returnResponse(200);
		$this->parseHeader();
		$this->reqDebug['data']['request_data'] = file_get_contents('php://input');
		$this->reqDebug['data']['request'] = Yii::$app->request->absoluteUrl;
		$this->debugWrite();
		if ($this->serviceMode == 'test' && $this->mode != 'test') {
			header('content-type: application/json; charset=UTF-8');
			Yii::$app->response->statusCode = 8101;
			echo json_encode($this->returnResponse(8101));
			exit;
		}
		if (isset($this->responseStatus['status']) && $this->responseStatus['status'] !== 200) {
			header('content-type: application/json; charset=UTF-8');

			echo $this->responseStatus['status'];
			exit;
			echo json_encode($this->returnResponse($this->responseStatus['status']));
			exit;
		}
		$userData = json_decode(file_get_contents('php://input'), true);
		if ($userData && is_array($userData) && isset($userData['params'])) {
			$this->params = $userData['params'];
		}
		return parent::beforeAction($action);
	}

	public function afterAction($action, $result) {
		$this->reqDebug['data']['response_data'] = json_encode($result);
		$this->debugWrite();

		return parent::afterAction($action, $result);
	}

	public function debugWrite() {
		if ($this->debugStatus) {
			$reqDebug = $this->reqDebug;
			if ((int) $reqDebug['id']) {
				$reqModel = Request::findOne((int) $reqDebug['id']);
				if ($reqModel == null) {
					$reqModel = new Request;
				}
			} else {
				$reqModel = new Request;
			}
			$data = $reqDebug['data'];

			$reqModel->create_time = new \yii\db\Expression('NOW()');
			if (isset($data['request'])) {
				$reqModel->request = $data['request'];
			}
			if (isset($data['request_data'])) {
				$reqModel->request_data = $data['request_data'];
			}
			if (isset($data['response'])) {
				$reqModel->response = $data['response'];
			}
			if (isset($data['response_data'])) {
				$reqModel->response_data = $data['response_data'];
			}
			if (isset($data['user'])) {
				$reqModel->user = $data['user'];
			}
			if (isset($data['version'])) {
				$reqModel->version = $data['version'];
			}
			if (isset($data['header_data'])) {
				$reqModel->header_data = $data['header_data'];
			}
			if ($reqModel->save()) {
				$this->reqDebug['id'] = $reqModel->id;
			}
		}
	}

	public function parseHeader() {
		$request = new \yii\web\Request;

		$authHeader = $request->getHeaders()->get('Auth');
		$languageHeader = $request->getHeaders()->get('lang');
		$versionHeader = $request->getHeaders()->get('v');
		$modeHeader = $request->getHeaders()->get('mode');
		if ($modeHeader == 'test') {
			$this->mode = 'test';
		}

		if ($versionHeader) {
			$this->reqDebug['data']['version'] = $versionHeader;
			$this->version = $versionHeader;
		} else {
			$this->reqDebug['data']['version'] = 'v1';
			$this->version = 'v1';
		}

		if ($languageHeader && in_array($languageHeader, ['ru', 'uz'])) {
			$this->language = $languageHeader;
		}
		else $this->language = 'ru';
		$this->reqDebug['data']['header_data'] = json_encode(['lang' => $this->language, 'v' => $this->version, 'auth_token' => $authHeader, 'mode' => $modeHeader]);

		if ($authHeader) {
			$app = \frontend\modules\api\models\App::find()->where(['auth_token' => $authHeader])->one();
			if ($app === null) {
				$this->responseStatus = $this->returnResponse(8100);
				$this->reqDebug['data']['user'] = '(not found)';
			} else {
				if ($app->user->is_active != 1) {
					$this->responseStatus = $this->returnResponse(8102);
					$this->reqDebug['data']['user'] = '(blocked)';
				} else {
					$this->user = $app->user;
					$this->app_token = $authHeader;
					$this->reqDebug['data']['user'] = $this->user->phone . ': ' . $this->user->id;
				}
			}
		} else {
			$this->reqDebug['data']['user'] = '(without user)';
		}
		if ($languageHeader && in_array($languageHeader, ['ru', 'uz'])) {
			$this->language = $languageHeader;
		}
		else {
			if ($this->user) {
				$userSet = $this->user->userset;
				if ($userSet === null) {
					$userSet = $this->user->addUserSet();
				}
				if ($userSet->default_language && in_array($userSet->default_language, ["ru", "uz"])) {
					$this->language = $userSet->default_language;
				} else {
					$this->language = 'ru';
				}
			} else {
				$this->language = 'ru';
			}
		  \Yii::$app->language = $this->language;
	  }
	}

	public function returnResponse($code, $data = []) {
		if ($code != 200) {
			Yii::$app->response->statusCode = 500;
		}
		$ret = [];
		$lang = $this->language;
		$ret = $this->getErrorMessage($code, $data);

		$this->reqDebug['data']['response_data'] = json_encode($ret);
		if ($this->debugStatus) {
			$ret['req_id'] = $this->reqDebug['id'];
		}
		return $ret;
	}

	public function getErrorMessage($code, $data) {
		$lang = $this->language;
		if ($code == 200) {
			return [
				'status' => 200,
				'message' => '',
				'data' => (object) $data
			];
		}
		if ($code == 404) {
			return [
				'status' => 404,
				'message' => ($lang == 'uz') ? 'Topilmadi' : 'Эндпоинт ненайден',
				'data' => (object) $data,
			];
		}
		if ($code == 403) {
			return [
				'status' => 403,
				'message' => ($lang == 'uz') ? 'Sizda etarli huquqlar yo\'q' : 'У вас нет достаточных прав',
				'data' => (object) $data,
			];
		}
		// особая ошибка при отсуствии товара
		if ($code == 8129) {
			return [
				'status' => 8129,
				'message' => ($lang == 'uz') ? 'Do\'konning omborida noma\'lum tovar yo\'q edi'.'"'.$data.'"' : 'На складе магазина не осталось товара с названием '.'"'.$data.'"',
				'data' => (object) $data,
			];
		}

		$ret = [
			'status' => 8099,
			'message' => ($lang == 'uz') ? 'Nomalum xatolik' : 'Неизвестная ошибка',
			'data' => (object) $data,
		];
		$errMessModel = \frontend\modules\api\models\ErrorMessage::find()->where(['code' => $code])->one();
		if ($errMessModel) {
			$this->reqDebug['data']['response'] = ($code == 200) ? 'success 200' : 'error ' . $code;
			$messageVarName = 'message_' . $lang;
			$ret = [
				'status' => $code,
				'message' => $errMessModel->$messageVarName,
				'data' => (object) $data,
			];
		} else {
			$this->reqDebug['data']['response'] = 8099;
		}
		return $ret;
	}

	public function actionError() {

		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$exception = Yii::$app->errorHandler->exception;
		if (is_object($exception)) {
			if ($exception->statusCode == 404) {
				return $this->returnResponse(404);
			}
			if ($exception->statusCode == 403) {
				return $this->returnResponse(403);
			}
		}
		return $this->returnResponse(8103);
	}

	public function updateUserData($user = null, $app = null) {
		if ($app) {
			$app->last_visit = new \yii\db\Expression('NOW()');
			if ($this->mode == 'test') {
				$app->test_mode = 1;
			} else {
				$app->test_mode = 0;
			}
			$app->save(false);
		}
		if ($user) {
			$this->user = $user;
		}
	}

	public function actionTest() {
		echo 98754654654;
	}

}
