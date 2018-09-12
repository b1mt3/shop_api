<?php

namespace frontend\modules\api\controllers;

use Yii;
use common\models\Order;
use frontend\modules\api\models\RetExReason;
use frontend\modules\api\models\Product;
use frontend\modules\api\models\ProductItem;
use frontend\modules\api\models\ReturnTicket;
use frontend\modules\api\models\OrderProduct;
use frontend\modules\api\models\Storage;
use frontend\modules\api\models\StorageHasProductItem;
use frontend\modules\api\models\BallTranfer;
use frontend\modules\api\models\Returnrequest;

class ReturnController extends MainController
{
    public function actionIndex()
    {
        $params = $this->params;

        if (!$params || !isset($params)) return $this->returnResponse(8137);
        if (isset($params['order_id']))
        {
            foreach ($params['items'] as $item)
            {
                $orderProductQuery = OrderProduct :: find()
                                     -> with(['order'])
                                     -> where(['order_id' => $params['order_id']])
                                     -> andWhere(['product_item_id' => $item['id']])
                                     -> one();
                // проверка на наличие
                $requestCheckQuery = Returnrequest :: find()
                                       -> with(['returnTickets'])
                                       -> where(['order_id' => $params['order_id']])
                                       -> one();
                if ($requestCheckQuery) {
                    foreach($requestCheckQuery->returnTickets as $ticket) {
                        $productTicketCheckQuery = ReturnTicket :: find()
                              -> with(['orderProduct'])
                              -> where(['id' => $ticket->id])
                              -> one();
                        if ($productTicketCheckQuery) {
                            if ($productTicketCheckQuery->count >= $orderProductQuery->count) {
                                return $this->returnResponse(8159);
                            }
                        }
                    }
                }
                // оформление новой звявки
                $returnedItem = new ReturnTicket;
                $returnedItemRequest = new Returnrequest;
                $returnedItem->status = 'new';
                $returnedItemRequest->status = 'new';
                $returnedItemRequest->is_new = 1;
                $returnedItemRequest->create_time = new \yii\db\Expression('NOW()');
                $returnedItem->create_time = new \yii\db\Expression('NOW()');
                $query = ProductItem :: find()
                         ->with(['storageHasProductItems', 'product'])
                         ->where(['id' => $item['id']])
                         ->one();
                $productQuery = Product :: find()
                                -> with(['category'])
                                -> where(['id' => $query->product->id])
                                -> one();

                if (!isset($query)) { return $this->returnResponse(8123); }

                if ($productQuery->category->is_non_returnable) return $this->returnResponse(8152);
                $returnedItemRequest->order_id = $params['order_id'];
                $ReasonQuery = RetExReason :: find()
                               ->where(['id' => $params['reason']])
                               ->one();
                if (!isset($ReasonQuery)) { return $this->returnResponse(8124); }
                $returnedItem->ret_ex_reason_id = $ReasonQuery->id;
                $returnedItem->amount_paid = $item['itemCount'] * $orderProductQuery->price;
                $returnedItem->pay_type = 'cash';
                $returnedItem->status = 'new';
                $returnedItem->count = $item['itemCount'];
                $returnedItem->order_product_id = $orderProductQuery->id;
                if (!$returnedItemRequest->save()) return $returnedItemRequest->errors;
                else {
                    $returnedItemRequest->save();
                    $returnedItem->returnrequest_id = $returnedItemRequest->id;
                    if(!$returnedItem->save()) return $returnedItem->errors;
                    else $returnedItem->save();
                }

            }
            // Вычитание баллов за заказ в случае возврата
            $earnBall = BallTranfer :: find()
                          -> where(['order_id' => $params['order_id'], 'type' => 'earn'])
                          -> one();
            if (($earnBall) && (!empty($earnBall)))
            {
                $deductBall = new BallTranfer;
                $deductBall->create_time = new \yii\db\Expression('NOW()');
                $deductBall->add_type = 'removed';
                $deductBall->type = 'return';
                $deductBall->order_id = $params['order_id'];
                $deductBall->user_id = $this->user->id;
                $deductBall->ball = $earnBall->ball;
            }
            return $this->returnResponse(200);
        }
        else return $this->returnResponse(8144);
    }

    public function actionReasons()
    {
        $params = $this->params;
        $reason = [];

        $ReasonQuery = RetExReason :: find()
                       ->all();
        if (!isset($ReasonQuery)) { return $this->returnResponse(8127); }
        foreach ($ReasonQuery as $item)
        {
            $reason[] = [
                'id' => $item->id,
                'reason' => Yii::$app->gFunctions->translate($item, 'reason', $this->language),
                'position' => $item->position
            ];
        }
        return $this->returnResponse(200, $reason);
    }
}
