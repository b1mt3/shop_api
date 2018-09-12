<?php

namespace frontend\modules\cashbox\controllers;

use Yii;
use common\models\Order;
use frontend\modules\cashbox\models\RetExReason;
use frontend\modules\cashbox\models\Product;
use frontend\modules\cashbox\models\ProductItem;
use frontend\modules\cashbox\models\ReturnTicket;
use frontend\modules\cashbox\models\OrderProduct;
use frontend\modules\cashbox\models\Storage;
use frontend\modules\cashbox\models\StorageHasProductItem;

class ReturnController extends MainController
{
    public function actionIndex()
    {
        $params = $this->params;
        $orderedItems = [];
        $order = Order :: find()
                 ->with(['orderProducts', 'orderProducts.product', 'orderProducts.product.brand',
                         'orderProducts.product.producer', 'orderProducts.product.category',
                         'orderProducts.productItem', 'orderProducts.productItem.size', 'orderProducts.productItem.color',
                         'orderProducts.productItem.storages', 'orderProducts.productItem.storageHasProductItems'])
                 ->where(['id' => $params['order_id']])
                 ->one();

        foreach($order->orderProducts as $item)
        {
            foreach ($item->productItem->storageHasProductItems as $stock)
            {
                if ($stock->storage_id == 3) $itemStock = $stock->count;
            }

            foreach ($item->productItem->storages as $storage)
            {
                if ($storage->id == 3) $storage_name = Yii::$app->gFunctions->translate($storage, 'name', $this->language);
            }

            $colors = [
              'name' => Yii::$app->gFunctions->translate($item->productItem->color, 'name', $this->language),
              'hex' => $item->productItem->color->hex,
              'hex2' => $item->productItem->color->hex2
            ];

            $orderedItems[] = [
              'id' => $item->productItem->id,
              'name' => Yii::$app->gFunctions->translate($item->productItem, 'name', $this->language),
              'price' => $item->price,
              'sum_price' => $itemStock * $item->price,
              'image' => 'http://mag.spg.uz/images/product/thumb/' . $item->productItem->app_image,
              'category' => Yii::$app->gFunctions->translate($item->product->category, 'name', $this->language),
              'brand' => Yii::$app->gFunctions->translate($item->product->brand, 'name', $this->language),
              'description' => Yii::$app->gFunctions->translate($item->product, 'description', $this->language),
              'producer' => Yii::$app->gFunctions->translate($item->product->producer, 'name', $this->language),
              'bar_code' => $item->productItem->bar_code,
              'sku' => $item->productItem->sku_code,
              'stock_count' => $itemStock,
              'stock' => $storage_name,
              'color' => $colors,
              'size' => Yii::$app->gFunctions->translate($item->productItem->size, 'name', $this->language)
            ];
        }

        return $this->returnResponse(200, $orderedItems);
    }

    public function actionExecute()
    {
        $params = $this->params;

        if (isset($params['order_id']))
        {
            foreach ($params['items'] as $item)
            {
                $orderProductQuery = OrderProduct :: find()
                                     ->with(['order'])
                                     ->where(['order_id' => $params['order_id']])
                                     ->one();
                $ReturnedItem = new ReturnTicket;
                $ReturnedItem->create_time = new \yii\db\Expression('NOW()');
                $query = ProductItem :: find()
                         ->with(['storageHasProductItems'])
                         ->where(['id' => $item['id']])
                         ->one();
                if (!isset($query)) { return $this->returnResponse(8123); }
                $ReturnedItem->product_item_id = $orderProductQuery->product_item_id;
                $ReasonQuery = RetExReason :: find()
                               ->where(['id' => $params['reason']])
                               ->one();
                if (!isset($ReasonQuery)) { return $this->returnResponse(8124); }
                $ReturnedItem->ret_ex_reason_id = $ReasonQuery->id;
                $ReturnedItem->amount_paid = $item['count'] * $orderProductQuery->price;
                $ReturnedItem->pay_type = $params['pay_type'];
                $ReturnedItem->is_approved = '0';
                $ReturnedItem->source = 'postbank';
                $ReturnedItem->save();
            }
            // Вычитание баллов за заказ в случае возврата
            $earnBall = BallTransfer :: find()
                          -> where(['order_id' => $params['order_id'], 'type' => 'earn'])
                          -> one();
            if (($earnBall) && (!empty($earnBall)))
            {
                $deductBall = new BallTransfer;
                $deductBall->create_time = new \yii\db\Expression('NOW()');
                $deductBall->add_type = 'removed';
                $deductBall->type = 'return';
                $deductBall->order_id = $params['order_id'];
                $deductBall->user_id = $orderProductQuery->order->user_id;
                $deductBall->ball = $earnBall->ball;
            }
            return $this->returnResponse(200);
        }
        return $this->returnResponse(9103);
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
