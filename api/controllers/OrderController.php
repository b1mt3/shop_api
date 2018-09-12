<?php

namespace frontend\modules\api\controllers;

use frontend\modules\api\models\Order;
use frontend\modules\api\models\District;
use frontend\modules\api\models\Product;
use frontend\modules\api\models\ProductItem;
use frontend\modules\api\models\OrderProduct;
use frontend\modules\api\models\Address;
use frontend\modules\api\models\OrderFeedback;
use frontend\modules\api\models\ReturnTicket;
use frontend\modules\api\models\Returnrequest;

use Yii;

class OrderController extends MainController
{
    public function actionIndex()
    {
        $params = $this->params;
        $orders = [];
        $images = [];
        $display_img = [];
        $list = [];

        $list_query = Order :: find()
                      ->with(['orderProducts'])
                      ->where(['user_id' => $this->user->id])
                      ->all();
        foreach ($list_query as $value)
        {
            foreach ($value->orderProducts as $unitOrder)
            {
                $product_query = ProductItem :: find()
                                 ->with(['product'])
                                 ->where(['id' => $unitOrder])
                                 ->one();
                if (!empty($product_query->app_image))
                  $images [] = $product_query->ImagePath();
                else $images [] = $product_query->product->ImagePath();
            }

            if (sizeof($images) >= 3)
            {
                $n = 3;
                while ($n)
                {
                    $display_img [] = $images[--$n];
                }
                $orders [] =
                [
                    'id' => $value->id,
                    'createTime' => strtotime($value->create_time),
                    'status' => $value->status,
                    'images' => $display_img,
                    'imageCount' => sizeof($images)
                ];
            }
            else {
                $orders [] =
                [
                    'id' => $value->id,
                    'createTime' => strtotime($value->create_time),
                    'status' => $value->status,
                    'images' => $images,
                    'imageCount' => sizeof($images)
                ];
            }
            $images = [];
            $display_img = [];
        }
        $n = sizeof($orders);
        while ($n)
        {
            $list[] = $orders[--$n];
        }
        return $this->returnResponse(200, ['order_list' => $list]);
    }

    public function actionDetail()
    {
        $params = $this->params;
        $orderDetails = [];
        $orderItems = [];
        $returnList = [];
        $hasReturned = false;

        $order_query = Order :: find()
                       ->with(['orderProducts'])
                       ->where(['id' => $params['order_id']])
                       ->one();
        if (!isset($order_query)) return $this->returnResponse(8133);
        $addressQuery = Address :: find ()
                        ->with(['district'])
                        ->where(['id' => $order_query->address_id])
                        ->one();
        if (!isset($addressQuery))
        {
            $addressQuery = Address :: find ()
                            ->with(['district'])
                            ->where(['user_id' => $this->user->id, 'is_default' => 1])
                            ->one();
        }

        $regionQuery = District :: find()
                       -> with(['region'])
                       -> where(['id' => $addressQuery->district->id])
                       -> one();

        $dstObj = [
            'id' => $addressQuery->district->id,
            'name' => Yii::$app->gFunctions->translate($addressQuery->district, 'name', $this->language)
        ];
        $addressDetail = [
            'id' => $addressQuery->id,
            'firstName' => $addressQuery->user_name,
            'lastName' => $addressQuery->user_surname,
            'district' => $dstObj,
            'str' => $addressQuery->street,
            'bldg' => $addressQuery->building,
            'apt' => $addressQuery->apartment,
            'city'=> $addressQuery->city,
            'zip' => $addressQuery->zipcode,
            'phone' => $addressQuery->user_phone
        ];

        $returnedItemsQuery = Returnrequest :: find()
                              -> with(['returnTickets'])
                              -> where(['order_id' => $params['order_id']])
                              -> all();

        if (!empty($returnedItemsQuery)) {
            $hasReturned = true;
        }
        foreach ($order_query->orderProducts as $item)
        {
            $returnCount = 0;
            $product_query = ProductItem :: find()
                             ->with(['size', 'color', 'product'])
                             ->where(['id' => $item->product_item_id])
                             ->one();
            if (!empty($product_query->app_image))
                $image = $product_query->ImagePath();
            else $image = $product_query->product->ImagePath();
            foreach ($returnedItemsQuery as $value)
            {
                foreach ($value->returnTickets as $ticket) {
                    $isReturnedQuery = OrderProduct :: find()
                                      -> where(['id' => $ticket->order_product_id])
                                      -> one();
                    if ($isReturnedQuery->product_item_id == $product_query->id) $returnCount++;
                }
            }
            $orderItems [] = [
                'id' => $product_query->id,
                'image' => $image,
                'name' => Yii::$app->gFunctions->translate($product_query, 'name', $this->language),
                'price' => $product_query['price'],
                'color' => Yii::$app->gFunctions->translate($product_query->color, 'name', $this->language),
                'size' => Yii::$app->gFunctions->translate($product_query->size, 'name', $this->language),
                'itemCount' => $item->count,
                'returnCount' => $returnCount
            ];
        }
        $deliveryDate = date("Y-m-d", $order_query->delivery_time);
        $orderDetails = [
            'id' => $params['order_id'],
            'created' => strtotime($order_query->create_time),
            'status' => $order_query->status,
            'deliveryDate' => $deliveryDate,
            'payStatus' => $order_query->is_paid,
            'itemCount' => sizeof($orderItems),
            'address' => $addressDetail,
            'deliveryCharge' => $order_query->delivery_price,
            'orderSum' => $order_query->price,
            'finalPrice' => $order_query->final_price,
            'discountSum' => $order_query->discont_price,
            'pointSpent' => $order_query->ball_price,
            'pointEarned' => $order_query->earned_ball,
            'hasReturned' => $hasReturned,
            'items' => $orderItems
        ];

        return $this->returnResponse(200, $orderDetails);
    }

    public function actionFeedback()
    {
        $params = $this->params;

        $CheckQuery = OrderFeedback :: find()
                      ->where(['user_id' => $this->user->id, 'order_id' => $params['id']])
                      ->one();
        if(isset($CheckQuery)) return $this->returnResponse(8132);

        $OrderQuery = Order :: find()
                      ->where(['id' => $params['id']])
                      ->one();
        $NewFeedback = new OrderFeedback;
        $NewFeedback->feedback = $params['feed_text'];
        $NewFeedback->user_id = $this->user->id;
        $NewFeedback->order_id = $OrderQuery->id;
        $NewFeedback->save();

        return $this->returnResponse(200, ['feedBack_id' => $NewFeedback->id]);
    }

    public function actionReturnlist()
    {
        $params = $this->params;
        $output = [];
        $inlineItems = [];
        $totalPrice = 0;

        $returnList = Returnrequest :: find()
                           -> with(['returnTickets'])
                           -> where(['order_id' => $params['order_id']])
                           -> one();
        if (!$returnList) return $this->returnResponse(8156);

        else
        {
            $returnCount = sizeof($returnList->returnTickets);
            $ticketQuery = ReturnTicket :: find()
                -> with(['orderProduct'])
                -> where(['returnrequest_id' => $returnList->id])
                -> all();
            foreach($ticketQuery as $value)
            {
                $productDetailsQuery = ProductItem :: find()
                                       -> with (['color', 'size'])
                                       -> where(['id' => $value->orderProduct->product_item_id])
                                       -> one();

                $inlineItems[] = [
                    'id' => $value->orderProduct->product_item_id,
                    'name' => Yii::$app->gFunctions->translate($productDetailsQuery, 'name', $this->language),
                    'status' => $value->status,
                    'image' => $productDetailsQuery->ImagePath(),
                    'price' => $value->orderProduct->price,
                    'color' => Yii::$app->gFunctions->translate($productDetailsQuery->color, 'name', $this->language),
                    'size' => Yii::$app->gFunctions->translate($productDetailsQuery->size, 'name', $this->language),
                    'itemCount' => $value->count
                ];
                $totalPrice += $value->orderProduct->price*$value->count;
            }
            $output = [
                'returnCount' => $returnCount,
                'list' => $inlineItems,
                'totalPrice' => $totalPrice
            ];
            return $this->returnResponse(200, $output);
        }
    }

}
