<?php

namespace frontend\modules\cashbox\controllers;

use Yii;
use frontend\modules\cashbox\models\User;
use frontend\modules\cashbox\models\Order;

class PayController extends MainController
{
    public function actionInvoice()
    {
        $params = $this->params;

        $orderModel = Order :: find()
                      ->where(['order_id' => $params['order_id']])
                      ->one();
        $phone = Yii::$app->sms->trimNumber($params['phone']);
        $user= User::find()
               ->where(['phone' => $phone])
               ->one();
        if (isset($orderModel)){
          $orderModel->status='ready';
          $orderModel->is_invoice=1;
          $orderModel->save();
          $receiptState=Yii::$app->paycom->createReceipts($orderModel, 'invoice');

          if($receiptState['message']=='') {
            $payState=Yii::$app->paycom->sendInvoice($receiptState['receipt_id'], Yii::$app->sms->trimNumber($this->phone));
            if($payState['message']=='') {
              $payStat = true;
            } else {
              $payStat = false;
            }
          } else {
            $payStat = false;
          }
          return ['message'=> Yii::t('front', 'Error on invoice creating')];
          if($payStat===true) {
            $orderModel->status='created';
            $orderModel->is_paid='0';
            $orderModel->save(false);
            return $this->returnResponse(200);
          } else {
            return $this->returnResponse(8143);
          }
        } else {
          return $this->returnResponse(8137);
        }
    }
}
