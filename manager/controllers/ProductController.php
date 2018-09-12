<?php

namespace frontend\modules\manager\controllers;

use Yii;
use frontend\modules\manager\models\Product;
use frontend\modules\manager\models\Category;
use frontend\modules\manager\models\Brand;
use frontend\modules\manager\models\Producer;
use frontend\modules\manager\models\Size;
use frontend\modules\manager\models\ProductType;

class ProductController extends MainController
{
    public function actionGetcategory()
    {
        $categ_boy = [];
        $categ_girl = [];
        $params = $this->params;
        $boyProductTypes = ProductType :: find()
                        ->with(['categories', 'categories.childCategories', 'categories.childCategories.childCategories'])
                        ->where(['product_type.is_active' => 1, 'product_type.is_deleted' => 0, 'product_type.id' => 1])
                        ->orderBy('product_type.position ASC')
                        ->distinct(true)
                        ->one();

        $girlProductTypes = ProductType :: find()
                        ->with(['categories', 'categories.childCategories', 'categories.childCategories.childCategories'])
                        ->where(['product_type.is_active' => 1, 'product_type.is_deleted' => 0, 'product_type.id' => 2])
                        ->orderBy('product_type.position ASC')
                        ->distinct(true)
                        ->one();

        $partners = ProductType :: find()
                  ->with(['categories', 'categories.childCategories', 'categories.childCategories.childCategories'])
                  ->where(['product_type.is_active' => 1, 'product_type.is_deleted' => 0, 'product_type.id' => 3])
                  ->orderBy('product_type.position ASC')
                  ->distinct(true)
                  ->one();
        $boysArr=[];
        foreach ($boyProductTypes->categories as $key => $value)
        {
            $lvl3=[];
            $lvl4=[];
            if (($value->level == 0))
            {
              foreach ($value->childCategories as $subValue)
              {
                  foreach ($subValue->childCategories as $subSubValue)
                  {
                      $lvl4[]=[
                        'id' => $subSubValue->id,
                        'parent_id' => $subValue->id,
                        'name' => Yii::$app->gFunctions->translate($subSubValue, 'name', $this->language),
                        'pos' => $subSubValue->position
                      ];
                  }
                  $lvl3[] = [
                      'id' => $subValue->id,
                      'parent_id' => $value->id,
                      'name' => Yii::$app->gFunctions->translate($subValue, 'name', $this->language),
                      'pos' => $subValue->position,
                      'subcategory' => $lvl4
                  ];
              }
            }
            $boysArr[]=[
              'id' => $value->id,
              'type' => $boyProductTypes->id,
              'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
              'pos' => $value->position,
              'icon' => 'http://mag.spg.uz/images/category/' . $value->icon,
              'category'=>$lvl3
            ];
        }

        $girlsArr=[];
        foreach ($girlProductTypes->categories as $key => $value)
        {
            $lvl3=[];
            $lvl4=[];
            if (($value->level == 0))
            {
              foreach ($value->childCategories as $subValue)
              {
                  foreach ($subValue->childCategories as $subSubValue)
                  {
                      $lvl4[]=[
                        'id' => $subSubValue->id,
                        'parent_id' => $subValue->id,
                        'name' => Yii::$app->gFunctions->translate($subSubValue, 'name', $this->language),
                        'pos' => $subSubValue->position
                      ];
                  }
                  $lvl3[] = [
                      'id' => $subValue->id,
                      'parent_id' => $value->id,
                      'name' => Yii::$app->gFunctions->translate($subValue, 'name', $this->language),
                      'pos' => $subValue->position,
                      'subcategory' => $lvl4
                  ];
              }
            }
            $girlsArr[]=[
              'id' => $value->id,
              'type' => $girlProductTypes->id,
              'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
              'pos' => $value->position,
              'icon' => 'http://mag.spg.uz/images/category/' . $value->icon,
              'category'=>$lvl3
            ];
        }

        $partnersArr=[];
        foreach ($partners->categories as $key => $value)
        {
            $lvl3=[];
            $lvl4=[];
            if (($value->level == 0))
            {
              foreach ($value->childCategories as $subValue)
              {
                  foreach ($subValue->childCategories as $subSubValue)
                  {
                      $lvl4[]=[
                        'id' => $subSubValue->id,
                        'parent_id' => $subValue->id,
                        'name' => Yii::$app->gFunctions->translate($subSubValue, 'name', $this->language),
                        'pos' => $subSubValue->position
                      ];
                  }
                  $lvl3[] = [
                      'id' => $subValue->id,
                      'parent_id' => $value->id,
                      'name' => Yii::$app->gFunctions->translate($subValue, 'name', $this->language),
                      'pos' => $subValue->position,
                      'subcategory' => $lvl4
                  ];
              }
            }
            $partnersArr[]=[
              'id' => $value->id,
              'type' => $partners->id,
              'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
              'pos' => $value->position,
              'icon' => 'http://mag.spg.uz/images/category/' . $value->icon,
              'category'=>$lvl3
            ];
        }

        return $this->returnResponse(200, ['boys_categories' => $boysArr,
                                           'girls_categories' => $girlsArr,
                                           'partners' => $partnersArr]);
    }

    public function actionGetbrand()
    {
        $response = [];
        $query = Brand :: find()
                 -> where (['is_deleted' => 0])
                 -> all();

        foreach ($query as $value)
        {
            $response [] = [
                'id' => $value->id,
                'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language)
            ];
        }

        return $this->returnResponse(200, ['list' => $response]);
    }

    public function actionGetsize()
    {
        $sizeResponse = [];
        $sizeQuery = Size :: find()
                     -> all();
        foreach ($sizeQuery as $value)
        {
            $sizeResponse [] = [
                'id' => $value->id,
                'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language)
            ];
        }

        return $this->returnResponse(200, ['list' => $sizeResponse]);
    }

    public function actionGetcolor()
    {
        $colorResponse = [];

        $colorQuery = Color :: find()
                      -> all();
        foreach ($colorQuery as $value)
        {
            $colorResponse[] = [
                'id' => $value->id,
                'hex' => $value->hex,
                'hex2' => $value->hex2,
                'border_hex' => $value->border_hex,
                'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language)
            ];
        }

        return $this->returnResponse(200, ['list' => $colorResponse]);
    }

    public function actionGetproducer()
    {
        $response = [];
        $query = Producer :: find()
                 -> where (['is_deleted' => 0])
                 -> all();

        foreach ($query as $value)
        {
            $response [] = [
                'id' => $value->id,
                'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language)
            ];
        }

        return $this->returnResponse(200, ['list' => $response]);
    }

    public function actionCreate()
    {
        $response = [];
        $params = $this->params;
        if (!$params || !isset($params)) return $this->returnResponse(8137);

        $newProduct = new Product;
        $newProduct->name_ru = $params['name_ru'];
        $newProduct->name_uz = $params['name_uz'];
        $newProduct->url_key = $params['url_key'];
        $newProduct->brand_id = $params['brand_id'];
        $newProduct->category_id = $params['category_id'];
        $newProduct->producer_id = $params['producer_id'];
        $newProduct->product_type_id = $params['type'];
        if (isset($params['is_active'])) $newProduct->is_active = $params['is_active'];
        if (isset($params['discount'])) $newProduct->sale_percentage = $params['discount'];
        $newProduct->description_ru = $params['description_ru'];
        if (isset($params['description_uz'])) $newProduct->description_uz = $params['description_uz'];
        if (isset($params['characteristics_ru'])) $newProduct->characteristics_ru = $params['characteristics_ru'];
        if (isset($params['characteristics_uz'])) $newProduct->characteristics_uz = $params['characteristics_uz'];

        if (isset($params['image']))
        {
            $data = base64_decode($params['image']);
            $path = 'images/product/thumb/' . uniqid() . '.png';
            $upload = file_put_contents($path, $data);
        }
        if (isset($params['app_image']))
        {
            $data = base64_decode($params['image']);
            $path = 'images/product/thumb/' . uniqid() . '.png';
            $upload = file_put_contents($path, $data);
        }

        if ($newProduct->save())
        {
            $categoryName = Category :: find()
                            -> where(['id' => $newProduct->category_id])
                            -> one();
            $brandName = Brand :: find()
                         -> where(['id' => $newProduct->brand_id])
                         -> one();
            $producerName = Producer :: find()
                            -> where(['id' => $newProduct->producer_id])
                            -> one();
            $response = [
                'id' => $newProduct->id,
                'image' => 'http://mag.spg.uz/images/product/thumb' . $newProduct->app_image,
                'name' => Yii::$app->gFunctions->translate($newProduct, 'name', $this->language),
                'category' => Yii::$app->gFunctions->translate($categoryName, 'name', $this->language),
                'brand' => Yii::$app->gFunctions->translate($brandName, 'name', $this->language),
                'producer' => Yii::$app->gFunctions->translate($producerName, 'name', $this->language),

            ];
            return $this->returnResponse(200, $newProduct->id);
        }
        else return $this->returnResponse(8149, ['error' => $newProduct->errors()]);

    }


    public function actionAdd()
    {
      $params = $this->params;
      if (!$params || !isset($params)) return $this->returnResponse(8137);

      $newItem = new ProductItem;
      $newItem->name_ru = $params['name_ru'];
      if (isset($params['name_uz'])) $newItem->name_ru = $params['name_ru'];
      $newItem->is_active = $params['is_active'];
      if (isset($params['is_deleted'])) $newItem->is_deleted = $params['is_deleted'];
      else $newItem->is_deleted = '0';
      $newItem->price = $params['price'];
      if (isset($params['old_price'])) $newItem->old_price = $params['old_price'];
      else $newItem->old_price = $newItem->price;
      $newItem->bar_code = $params['barcode'];
      if (isset($params['sku'])) $newItem->sku_code = $params['sku'];
      $newItem->size_id = $params['size'];
      $newItem->color_id = $params['color'];
      if (isset($params['image'])) $newItem->image = $params['image'];
      if (isset($params['app_image'])) $newItem->app_image = $params['app_image'];
    }
}
