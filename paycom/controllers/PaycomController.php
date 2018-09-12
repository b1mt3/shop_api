<?php
namespace frontend\modules\paycom\controllers;
use Yii;
use yii\web\Request;
use frontend\modules\paycom\models\Payset;
use frontend\modules\paycom\models\Paydebug;
use frontend\modules\paycom\models\Order;
use frontend\modules\paycom\models\UzcardTransaction;
use yii\helpers\Json;

class PaycomController extends \yii\web\Controller
{
	public $debug=false; //debug requests (a.g. save all requests and all responses)
	
	public $enableCsrfValidation = false; //disable _csrf validation
	public $authenticateStatus=true; 
	public $accountName='order_id';
	
	
	
	
	private $_rpcId=null;
	private $_rpcMethod=null;
	private $_params=[];
	private $_headers=null;
	private $_request=null;
	
	
	
	
	public function actionIndex()
    {
		Yii::$app->response->format=\yii\web\Response::FORMAT_JSON; //set the return type of any action
		//Запрос должен быть отправлен методом HTTP POST
		if($_SERVER['REQUEST_METHOD'] !== 'POST') {
			return $this->returnError(-32300, 'Некорректный запрос'); 
		} 
		
		//Аутентифицируем запроса
		if($this->authenticateStatus) {
			$payset=  Payset::find()->where(['provider'=>'paycom'])->one();
			$request=new Request;
			$hasError=false;
			$authHeader = $request->getHeaders()->get('Authorization');
			$this->_headers=$authHeader;
			if($authHeader===NULL) {
				return $this->returnError(-32504, 'Недостаточно привилегий для выполнения метода.'); 
			} else {
				$headArr=  explode(' ', $authHeader);
				if(empty($headArr) || count($headArr)<2) {
					return $this->returnError(-32504, 'Недостаточно привилегий для выполнения метода.'); 
				} else {
					$name=trim($headArr[0]);
					$pass=trim($headArr[1]);
					if($name=='Basic' && $pass==$payset->password) {
						
					} else {
						return $this->returnError(-32504, 'Недостаточно привилегий для выполнения метода.'); 
					}
				}
			}
			if($hasError) {
				
				try {
					$requestObject = Json::decode(file_get_contents('php://input'), false);
				} catch (InvalidParamException $e) {
					$requestObject = null;
				}
				$id=null;
				if($requestObject) {
					if($requestObject->id) {
						$id=$requestObject->id;
					}
				}
				
				return $this->returnError(-32504, 'Недостаточно привилегий для выполнения метода.'); 
			}
		}
		//Инициализируем данных
		$data = json_decode(file_get_contents('php://input'));
		$this->_request=$data;
		if($data===NULL) {
			return $this->returnError(-32700, 'Ошибка Парсинга JSON. Запрос является не валидным JSON объектом.'); 
		}
		
		if(((!isset($data->jsonrpc)||$data->jsonrpc!="2.0")) || (!isset($data->id)) || (!isset($data->method))) {
			return $this->returnError(-32600, 'Передан неправильный JSON-RPC объект.'); 
		}
		if(!method_exists($this, $data->method)) {
			return $this->returnError(-32601, 'Запрашиваемый метод не найден. Поле data содержит запрашиваемый метод.');
		}
		$this->_rpcId=$data->id;
		$this->_rpcMethod=$data->method;
		if(isset($data->params)) {
			$this->_params=$data->params;
		}
		$methodName=$this->_rpcMethod;
		
		return $this->$methodName();
		
    }
	
	public function CheckPerformTransaction() {
		$id=  $this->_rpcId;
		$params=  $this->_params;
		$accountName=$this->accountName;
		if(isset($params->account->$accountName)) {
			$order=  Order::find()->where(['id'=>$params->account->$accountName, 'status'=>'ready'])->one();
			if($order) {
				if($order->is_paid) {
					return $this->returnError(-31052 , 'Заказ уже оплачен');
				}
				if($order->price*100!=$params->amount) {
					return $this->returnError(-31001 , 'Неверная сумма.');
				}
				return $this->returnResponse(['allow'=>true]);
			} else {
				return $this->returnError(-31051 , 'Заказ ненайден');
			}
		} else {
			return $this->returnError(-31050 , 'Неправильное название аккаунта');
		}
		
	}
	
	public function CreateTransaction() {
		$id=  $this->_rpcId;
		$params=  $this->_params;
		$accountName=$this->accountName;
		if(isset($params->account->$accountName)) {
			$order=  Order::findOne($params->account->$accountName);
			if($order) {
				if($order->is_paid) {
					return $this->returnError(-31052 , 'Заказ уже оплачен');
				}
				if($order->price*100!=$params->amount) {
					return $this->returnError(-31001 , 'Неверная сумма.');
				}
				$paycomId=$params->id;
				$paycomTime=$params->time;
				$amount=$params->amount;
				$oldTrans=UzcardTransaction::find()->where(['order_id'=>$order->id])->one();
				if($oldTrans) {
					if($oldTrans->tran!=$paycomId) {
						return $this->returnError(-31063 , 'ID не совпадают');
					}
				}
				$tran=UzcardTransaction::find()->where(['tran'=>$paycomId, 'order_id'=>$order->id])->one();
				if($tran===null) {
					$tran=new UzcardTransaction;
					$tran->amount=$amount;
					$tran->tran=$paycomId;
					$tran->create_time=round(microtime(true)*1000);
					$tran->is_done=0;
					$tran->state=1;
					$tran->create_time_remote=(int)$paycomTime;
					$tran->order_id=$order->id;
					if(!$tran->save()) {
						return $this->returnError(-31008 , 'Невозможно выполнить данную операцию.');
					}
				} else {
//					return $this->returnError(-31060 , 'Уже создан.');
				}
				
				return $this->returnResponse(['create_time'=>$tran->create_time, 'transaction'=>(string)$tran->id, 'state'=>$tran->state]);
				
			} else {
				return $this->returnError(-31051 , 'Заказ ненайден');
			}
		} else {
			return $this->returnError(-31050 , 'Неправильное название аккаунта');
		}
	}
	
	public function PerformTransaction() {
		$params=  $this->_params;
		$paycomId=$params->id;
		$tran=UzcardTransaction::find()->where(['tran'=>$paycomId])->one();
		if($tran===null) {
			return $this->returnError(-31003 , 'Транзакция не найдена');
		}
		if($tran->state==-1 || $tran->state==-2) {
			return $this->returnError(-31008 , 'Невозможно выполнить данную операцию.');
		}
		$order=$tran->order;
		if($order===null) {
			return $this->returnError(-31054 , 'Нет связанных заказов');
		}
		if($order->is_paid) {
			if($tran->transaction_time && $tran->state==2) {
				return $this->returnResponse(['transaction'=>(string)$tran->id, 'perform_time'=>$tran->transaction_time, 'state'=>$tran->state]);
			}
			return $this->returnError(-31052 , 'Заказ уже оплачен');
		}
		$order->is_paid=1;
		if($order->save()) {
			$tran->state=2;
			$tran->transaction_time=round(microtime(true)*1000);
			$tran->save();
			return $this->returnResponse(['transaction'=>(string)$tran->id, 'perform_time'=>$tran->transaction_time, 'state'=>$tran->state]);
		} else {
			return $this->returnError(-31055 , 'Невозможно изменить статус оплаты заказа');
		}
	}
	
	public function CancelTransaction() {
		$params=  $this->_params;
		$paycomId=$params->id;
		$reason=$params->reason;
		$tran=UzcardTransaction::find()->where(['tran'=>$paycomId])->one();
		if($tran===null) {
			return $this->returnError(-31003 , 'Транзакция не найдена');
		}
		$order=$tran->order;
		if($order===null) {
			return $this->returnError(-31054 , 'Нет связанных заказов');
		}
//		if($order->is_paid==0) {
//			return $this->returnError(-31056 , 'Заказ еще не оплачен');
//		}
		if($order->status=='sent' || $order->status=='delivered') {
			return $this->returnError(-31007 , 'Невозможно отменить транзакцию, заказ выполнен. Товар или услуга предоставлена Потребителю в полном объеме.');
		}
		if($tran->state==-1 || $tran->state==-2) {
			return $this->returnResponse(['transaction'=>(string)$tran->id, 'cancel_time'=>$tran->cancel_time, 'state'=>$tran->state]);
		}
		
		if($tran->state==1 || $tran->state==2) {
			if($tran->state==1) {
				$tran->state=-1;
			}
			if($tran->state==2) {
				$tran->state=-2;
			}
			$tran->reject_reason=(int)$reason;
			$tran->cancel_time=round(microtime(true)*1000);
			$tran->save();
			$order->is_paid=0;
			$order->status='canceled';
//			$order->reject_time=time();
			$order->canceled_comment='Отклонен оплата от сервера Paycom. Время:'.date('Y-m-d H:i:s');
			if($order->save()) {
				return $this->returnResponse(['transaction'=>(string)$tran->id, 'cancel_time'=>$tran->cancel_time, 'state'=>$tran->state]);
			}
		}
		return $this->returnError(-31007 , 'Невозможно отменить транзакцию, заказ выполнен. Товар или услуга предоставлена Потребителю в полном объеме.');
	}
	
	public function CheckTransaction() {
		$params=  $this->_params;
		$paycomId=$params->id;
		$tran=UzcardTransaction::find()->where(['tran'=>$paycomId])->one();
		if($tran===null) {
			return $this->returnError(-31003 , 'Транзакция не найдена');
		}
		return $this->returnResponse([
			"create_time" =>  $tran->create_time,
			"perform_time" =>  (int)$tran->transaction_time,
			"cancel_time" =>  (int)$tran->cancel_time,
			"transaction" =>  (string)$tran->id,
			"state" => $tran->state,
			"reason" => $tran->reject_reason,
		]);
	}
	
	public function GetStatement() {
		$params=  $this->_params;
		$from=$params->from;
		$to=$params->to;
		$transactions=  UzkardTransaction::find()->where('create_time_remote>='.$from. ' AND '.'create_time_remote<='.$to)->orderBy('create_time_remote ASC')->all();
		
		if(empty($transactions)) {
			return $this->returnResponse([
				"transactions" => []
			]);
			
		} else {
			$retData=[];
			foreach ($transactions as $item) {
				$retData[]=[
					"id" =>  $item->tran,
					"time" =>  $item->create_time_remote,
					"amount" =>  $item->amount,
					"account" =>  [
						'order_id'=>$item->order->id,
					],
					"create_time" =>  $item->create_time_remote,
					"perform_time" =>  (int)$item->transaction_time,
					"cancel_time" =>  (int)$item->cancel_time,
					"transaction" =>  $item->id,
					"state" => $item->state,
					"reason" => $item->reject_reason,
					'receivers'=>[
						
					],
				];
			}
			
			return $this->returnResponse([
				"transactions" => $retData
			]);
		}
	}
	
	public function ChangePassword() {
		$params=  $this->_params;
		$password=$params->password;
		$payset=  Payset::find()->where(['provider'=>'paycom'])->one();
		$payset->password=  base64_encode("Paycom:".$password);
		if($payset->save()) {
			return $this->returnResponse(['success'=>true]);
		} else {
			return $this->returnResponse(['success'=>false]);
		}
	}
	
	
	/**
	 * 
	 * @param type $result
	 * @return type
	 */
	public function returnResponse($result) {
		$error=NULL;
		return $this->response($this->_rpcId, $error, $result);
	}


	/**
	 * 
	 * @param type $code
	 * @param type $text
	 * @param type $data
	 * @return type
	 */
	
	public function returnError($code, $text, $data=NULL) {
		$errArr=[
			'code'=>$code,
			'message'=>[
				'ru'=>$text,
				'uz'=>$text,
				'en'=>$text,
			],
			'data'=>$data
		];
		return $this->response($this->_rpcId, $errArr);
	}
	
	/**
	 * @param type $id
	 * @param type $error
	 * @param type $result
	 * @return type
	 */
	public function response($id, $error=null, $result=NULL){
		$debug=new Paydebug();
		$debug->type='uzcard';
		$debug->create_time=new \yii\db\Expression('NOW()');
		if($error) {
			$debug->status='error';
		} elseif($result) {
			$debug->status='success';
		}
		$debug->header=  $this->_headers;
		if($this->_request) {
			$debug->request= json_encode($this->_request);
		} else {
			$debug->request=NULL;
		}
		$debug->response=  json_encode(['error'=>$error, 'result'=>$result, 'id'=>$id]);
		$debug->save();
		return ['error'=>$error, 'result'=>$result, 'id'=>$id];
	}
	
	
}
