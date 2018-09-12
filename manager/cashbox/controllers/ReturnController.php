<?php

namespace frontend\modules\cashbox\controllers;

use Yii;
use common\models\Order;
use common\models\RetExReason;
use frontend\modules\cashbox\models\Product;
use frontend\modules\cashbox\models\ProductItem;
use frontend\modules\cashbox\models\ReturnItems;
use frontend\modules\cashbox\models\OrderProduct;

class ReturnController extends MainController
{
    public function actionIndex()
    {
        $params = $this->params;
        $toReturn_products = [];
        $reasons = [];

        $product_query = ProductItem :: find()
                         ->with(['size', 'color'])
                         ->where(['bar_code' => $params['bar_code']])
                         ->all();
        $reason_query = RetExReason :: find()
                        ->all();
        foreach ($reason_query as $value)
        {
            $reasons [] = [
                'id' => $value->id,
                'reason' => $value->reason_ru
            ];
        }
        foreach ($product_query as $item)
        {
            $clean_size = [
                'id' => $item->size->id,
                'name' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
            ];
            $clean_color = [
                'id' => $item->color->id,
                'name' => Yii::$app->gFunctions->translate($item->color, 'name', $this->language),
                'hex' => $item->color->hex,
                'hex2' => $item->color->hex2,
                'icon' => $item->color->icon,
                'border_hex' => $item->color->border_hex
            ];
            $toReturn_products [] = [
                'id' => $item->id,
                'name' => Yii::$app->gFunctions->translate($item, 'name', $this->language),
                'size' => $clean_size,
                'color' => $clean_color,
                'price' => $item->price
            ];
        }
        return $this->returnResponse(200, ['items' => $toReturn_products, 'reasons' => $reasons]);
    }

    public function actionConfirm()
    {
        $params = $this->params;

        return $this->returnResponse(200);
    }
}
