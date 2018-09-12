<?php

namespace frontend\modules\api\controllers;

use Yii;
use frontend\modules\api\models\SearchIndex;
use frontend\modules\api\models\Brand;
use frontend\modules\api\models\ProductItem;

class SearchController extends MainController
{
    public function actionIndex()
    {
        $params = $this->params;
        $searchResults = [];

        $SearchQuery = SearchIndex :: find()
                       ->with(['product'])
                       ->where(['LIKE', 'search_keywords', $params['keyword']]);

        if (isset($params['sort']))
        {
            if ($params['sort'] == 'asc') $SearchQuery->orderBy('search_index.price ASC');
            if ($params['sort'] == 'desc')  $SearchQuery->orderBy('search_index.price DESC');
        }

        $SearchQuery = $SearchQuery->all();

        if (!isset($SearchQuery)) return $this->returnResponse(8135);
        foreach ($SearchQuery as $key => $value) {
            $BrandQuery = Brand :: find()
                          ->where(['id' => $value->product->brand_id])
                          ->one();
            if (!empty($value->product->app_image))
            {
                $searchResults[] = [
                  'id' => $value->product->id,
                  'name' => Yii::$app->gFunctions->translate($value->product, 'name', $this->language),
                  'brand' => Yii::$app->gFunctions->translate($BrandQuery, 'name', $this->language),
                  'price' => $value->product->min_price,
                  'image' => 'http://mag.spg.uz/images/product/thumb/' . $value->product->app_image
                ];
            }
            else {
                $searchResults[] = [
                  'id' => $value->product->id,
                  'name' => Yii::$app->gFunctions->translate($value->product, 'name', $this->language),
                  'brand' => Yii::$app->gFunctions->translate($BrandQuery, 'name', $this->language),
                  'price' => $value->product->min_price
                ];
            }
        }
        return $this->returnResponse(200, ['result' => $searchResults]);
    }

    public function actionScan()
    {
        $params = $this->params;

        $ScanQuery = ProductItem :: find()
                     ->with(['product'])
                     ->where(['bar_code' => $params['barcode']])
                     ->one();
        if (isset($ScanQuery)) return $this->returnResponse(200, ['product_id' => $ScanQuery->product->id]);
        else return $this->returnResponse(8134);
    }

    public function actionRepopulate()
    {
        $params = $this->params;
        $SearchQuery = SearchIndex :: find()->all();

        foreach ($SearchQuery as $key => $value)
        {
            $value->delete();
        }

        Yii :: $app->indexSearch->PopulateIndex($params);
    }
}
