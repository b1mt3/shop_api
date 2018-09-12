<?php

namespace frontend\modules\api\controllers;

use Yii;
use frontend\modules\api\models\Card;
use frontend\modules\api\models\User;
use frontend\modules\api\models\Order;
use frontend\modules\api\models\PayType;

class PayController extends MainController
{

    public function actionGetappid()
    {
        $appId = Yii :: $app->paycom->appId;
        return $this->returnResponse(200, ['app_id' => $appId]);
    }

    public function actionAddcard()
    {
        $params = $this->params;

        $this->enableCsrfValidation = false;
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		$user = User::findOne($this->user->id);
    		if($params) {
      			$card = Card::find()
                    ->where(['number'=>$params['data']['result']['card']['number'],
                             'expire'=>$params['data']['result']['card']['expire'],
                             'user_id'=> $this->user->id])
                    ->one();
      			if($card) return $this->returnResponse(8140);
            else {
      				$newCard = new Card;
      				$newCard->number = $params['data']['result']['card']['number'];
      				$newCard->expire = $params['data']['result']['card']['expire'];
      				$newCard->token = $params['data']['result']['card']['token'];
      				$newCard->recurrent = (int)$params['data']['result']['card']['recurrent'];
      				$newCard->verify = (int)$params['data']['result']['card']['verify'];
      				$newCard->user_id = $this->user->id;
      				$newCard->save(false);
      				return $this->returnResponse(200, ['card_id' => $newCard->id]);
      			}
    		} else return $this->returnResponse(8137);
    }

    public function actionRmcard()
    {
        $params = $this->params;

        if (!$params) return $this->returnResponse(8137);
        $user = User::findOne($this->user->id);
        $card = Card :: find()
                     ->where(['id'=> $params['id']])
                     ->one();
        $card->delete();
        return $this->returnResponse(200);
    }

    public function actionViewcard()
    {
        $params = $this->params;
        $myCards = [];

        if ($params) return $this->returnResponse(8137);
        $userCards = Card :: find()
                     ->where(['user_id'=> $this->user->id])
                     ->all();
        if (!isset($userCards)) return $this->returnResponse(8142);
        foreach ($userCards as $card)
        {
            $myCards[] = [
                'id' => $card->id,
                'number' => $card->number,
                'expire' => $card->expire,
                'isDefault' => $card->is_default
            ];
        }
        return $this->returnResponse(200, ['cards' => $myCards]);
    }

    public function actionEditcard()
    {
        $params = $this->params;

        if (!$params) return $this->returnResponse(8137);
        $user = User::findOne($this->user->id);
        $card = Card :: find()
                ->where(['id'=> $params['id']])
                ->one();
        $defaultCard = Card :: find()
                       ->where(['is_default'=> 1, 'user_id' => $user->id])
                       ->one();
        $card->is_default = 1;
        $card->save();
        if (!isset($defaultCard)) return $this->returnResponse(200);
        else
        {
            $defaultCard->is_default = 0;
            $defaultCard->save();
            return $this->returnResponse(200);
        }
    }

    public function actionOnline()
    {
        $params = $this->params;

        $orderModel = Order :: find()
                      ->where(['id' => $params['order_id']])
                      ->one();
    		$user= User::findOne($this->user->id);
        if (!$user) return $this->returnResponse(8100);
        $card= Card::find()
                     ->where(['user_id'=>$user->id, 'id'=> $params['card_id']])
                     ->one();
    		if ($card) {
          if ($orderModel->card_id != $card->id) return $this->returnResponse(8160);
          elseif ($orderModel->user->id != $this->user->id) return $this->returnResponse(8161);
    			$orderModel->status='ready';
    			$orderModel->save();
    			$card = Card::findOne($orderModel->card_id);
          $receiptState=Yii::$app->paycom->createReceipts($orderModel);

      		if($receiptState['message']=='') {
      			$payState=Yii::$app->paycom->payReceipts($receiptState['receipt_id'], $card);
      			if($payState['message']=='') {
      				$payStat = true;
      			} else {
      				$payStat = false;
      			}
      		} else {
      			$payStat = false;
      		}
    			if($payStat===true) {
    				$orderModel->status='created';
    				$orderModel->is_paid='0';
            $payTypeQuery = PayType :: findOne(['alias' => 'online']);
            $orderModel->pay_type_id = $payTypeQuery->id;
    				$orderModel->save(false);
    				return $this->returnResponse(200);
    			} else {
            if ($payState['message']) return $this->returnResponse(8141, $payState['message']);
    				else return $this->returnResponse(8141, $receiptState['message']);
    			}
    		} else {
    			return $this->returnResponse(8142);
    		}
    }

    public function actionInvoice()
    {
        $params = $this->params;

        if (!$params['phone'] || !$params['order_id'])
          return $this->returnResponse(8137);

        $orderModel = Order :: find()
                      ->where(['id' => $params['order_id']])
                      ->one();
        $user = User :: findOne(['phone' => $params['phone']]);
        if ($orderModel->user_id != $user->id) return $this->returnResponse(8161);
        if (isset($orderModel)){
    			$orderModel->status='ready';
    			$orderModel->is_invoice = '1';
    			$orderModel->save();
          $receiptState=Yii::$app->paycom->createReceipts($orderModel, 'invoice');

      		if($receiptState['message']=='') {
      			$payState=Yii::$app->paycom->sendInvoice($receiptState['receipt_id'], $params['phone']);
      			if($payState['message']=='') {
      				$payStat = true;
      			} else {
      				$payStat = false;
      			}
      		} else {
      			$payStat = false;
      		}
    			if($payStat===true) {
    				$orderModel->status='created';
    				$orderModel->is_paid='0';
            $payTypeQuery = PayType :: findOne(['alias' => 'invoice']);
            $orderModel->pay_type_id = $payTypeQuery->id;
    				$orderModel->save(false);
    				return $this->returnResponse(200);
    			} else {
    				return $this->returnResponse(8143);
    			}
    		} else {
    			return $this->returnResponse(8137);
    		}
    }

    public function actionRetry()
    {
      $params = $this->params;

      if ($params['order_id'] && $params['paytype_id'])
      {
        $orderQuery = Order :: findOne(['id' => $params['order_id']]);
        switch($params['paytype_id'])
        {
          case 1:
            $orderQuery->status='created';
            $orderQuery->is_paid='0';
            $payTypeQuery = PayType :: findOne(['alias' => 'offline']);
            $orderQuery->pay_type_id = $payTypeQuery->id;
            $orderQuery->save(false);
            return $this->returnResponse(200);

          case 2:
            return $this->actionOnline();

          case 3:
            return $this->actionInvoice();
        }
      }
      return $this->returnResponse(8137);
    }
}
