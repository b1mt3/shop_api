<?php

namespace frontend\modules\manager\controllers;

use Yii;
use frontend\modules\manager\models\SearchIndex;
use frontend\modules\manager\models\Brand;
use frontend\modules\manager\models\Product;
use frontend\modules\manager\models\ProductItem;

class SearchController extends MainController
{
    public function actionIndex()
    {
        $params = $this->params;
        $searchResults = [];

        $SearchQuery = SearchIndex :: find()
                       ->with(['product'])
                       ->where(['LIKE', 'search_keywords', $params['keyword']])
                       ->all();
        if (!isset($SearchQuery)) return $this->returnResponse(8135);
        foreach ($SearchQuery as $value) {
            $BrandQuery = Brand :: find()
                          ->where(['id' => $value->product->brand_id])
                          ->one();
            $productItemQuery = ProductItem :: find()
                                ->where(['product_id' => $value->product->id])
                                ->all();
            $productItemCount = sizeof($productItemQuery);
            $searchResults[] = [
              'id' => $value->product->id,
              'name' => Yii::$app->gFunctions->translate($value->product, 'name', $this->language),
              'brand' => Yii::$app->gFunctions->translate($BrandQuery, 'name', $this->language),
              'image' => 'http://mag.spg.uz/images/product/thumb/' . $value->product->app_image,
              'productItems_count' => $productItemCount
            ];
        }
        return $this->returnResponse(200, ['list' => $searchResults]);
    }

    public function actionScan()
    {
        $params = $this->params;
        $response = [];

        $ScanQuery = ProductItem :: find()
                     ->with(['product', 'size', 'color'])
                     ->where(['bar_code' => $params['barcode']])
                     ->one();
        if (isset($ScanQuery))
        {
            foreach ($ScanQuery as $item)
            {
                $brandQuery = Product :: find()
                              ->with(['brand'])
                              ->where(['id' => $item->product->id])
                              ->one();
                if (empty($item->app_image)) $img = $brandQuery->app_image;
                else $img = $item->app_image;
                $response [] = [
                    'id' => $item->id,
                    'brand' => Yii::$app->gFunctions->translate($BrandQuery->brand, 'name', $this->language),
                    'image' => 'http://mag.spg.uz/images/product/thumb/' . $img,
                    'name' => Yii::$app->gFunctions->translate($item, 'name', $this->language),
                    'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language),
                    'color' => Yii::$app->gFunctions->translate($item->color, 'name', $this->language)
                ];
            }
            return $this->returnResponse(200, ['list' => $response]);
        }
        return $this->returnResponse(8134);
    }
}
