<?php

namespace frontend\modules\cashbox\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
	
	public $debugStatus=true; //if true, all requests will be saved
	
	/*
	 * The mode of the service
	 * available values:
	 * disabled - service unavailable - retirns error 497
	 * test - test mode - works for only test apps
	 * enabled - release mode - for all apps
	 */
	public $serviceMode='enabled'; //if "disabled", will be returned error 497
	public $resendAllowTime=120; // time in after seconds which it will be allowed resend SMS to confirm;
	public $codeExpireTime=120; // expire time of code to confirm;
	public $newFlagPeriod=604800;//period during which after creation restaurant will be with "new" flag





	// return statuses
	public $statuses=[
		200=>[
			'ru'=>[
				'title'=>'',
				'message'=>'',
			],
			'en'=>[
				'title'=>'',
				'message'=>'',
			],
		],
		403=>[
			'ru'=>[
				'title'=>'Нет доступа',
				'message'=>'Нет доступа',
			],
			'en'=>[
				'title'=>'No access',
				'message'=>'No access',
			],
		],
		404=>[
			'ru'=>[
				'title'=>'Endpoint ненайден',
				'message'=>'Endpoint ненайден',
			],
			'en'=>[
				'title'=>'Endpoint does not found',
				'message'=>'Endpoint does not found',
			],
		],
		
		
		497=>[
			'ru'=>[
				'title'=>'Сервис временно недоступен',
				'message'=>'Сервис временно недоступен',
			],
			'en'=>[
				'title'=>'Service is temporarily unavailable',
				'message'=>'Service is temporarily unavailable',
			],
		],
		
		498=>[
			'ru'=>[
				'title'=>'Неизвестная ошибка',
				'message'=>'Неизвестная ошибка',
			],
			'en'=>[
				'title'=>'Unknown error',
				'message'=>'Unknown error',
			],
		],
		
		499=>[
			'ru'=>[
				'title'=>'Системная ошибка',
				'message'=>'Системная ошибка',
			],
			'en'=>[
				'title'=>'System error',
				'message'=>'System error',
			],
		],
		
		
	];
}
