<?php

namespace frontend\modules\manager\controllers;

use Yii;
use frontend\modules\manager\models\Product;

class CatalogueController extends MainController
{
    public function actionView()
    {
        $response = [];
        $productQuery = Product :: find()
                        -> with(['productItems', 'brand'])
                        -> all();
        if (isset($productQuery))
        {
            foreach ($productQuery as $value)
            {
                $response [] = [
                    'id' => $value->id,
                    'brand' => Yii::$app->gFunctions->translate($value->brand, 'name', $this->language),
                    'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
                    'image' => 'http://mag.spg.uz/images/product/thumb/' . $value->app_image,
                    'productItems_count' => sizeof($value->productItems)
                ];
            }

            return $this->returnResponse(200, ['list' => $response]);
        }

        else return $this->returnResponse(8119);
    }

    public function actionDetailed()
    {
        $params = $this->params;
        $productResponse = [];
        $itemsResponse = [];

        $productQuery = Product :: find()
                        -> where(['id' => $params['id']])
                        -> with(['brand'])
                        -> one();

        $productItemQuery = ProductItem :: find()
                            -> where(['product_id' => $params['id']])
                            -> with(['color', 'size'])
                            -> all();

        foreach ($productItemQuery as $item)
        {
            if (!empty($item->app_image)) $img = $item->app_image;
            else $img = $productQuery->app_image;

            $colour = [
              'name' => Yii::$app->gFunctions->translate($item->color, 'name', $this->language)
              'hex' => $item->color->hex,
              'hex2' => $item->color->hex2
            ];

            $itemsResponse[] = [
                'id' => $item->id,
                'name' => Yii::$app->gFunctions->translate($item, 'name', $this->language),
                'image' => 'http://mag.spg.uz/images/product/thumb/' . $img,
                'brand' => Yii::$app->gFunctions->translate($productQuery->brand, 'name', $this->language),
                'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language),
                'color' => $colour
            ];
        }

        $productResponse = [
            'id' => $productQuery->id,
            'brand' => Yii::$app->gFunctions->translate($productQuery->brand, 'name', $this->language),
            'name' => Yii::$app->gFunctions->translate($productQuery, 'name', $this->language),
            'min_price' => $productQuery->min_price,
            'max_price' => $productQuery->max_price,
            'description' => Yii::$app->gFunctions->translate($productQuery, 'description', $this->language),
            'characteristics' => Yii::$app->gFunctions->translate($productQuery, 'characteristics', $this->language),
            'items_count' => sizeof($itemsResponse),
            'items' => $itemsResponse
        ];

        return $this->returnResponse(200, ['list' => $productResponse]);
    }
}
