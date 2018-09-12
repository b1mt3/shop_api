<?php

namespace frontend\modules\api\controllers;

use frontend\modules\api\models\Product;
use frontend\modules\api\models\NumberConfirm;
use frontend\modules\api\models\ProductType;
use frontend\modules\api\models\ProductItem;
use frontend\modules\api\models\Category;
use frontend\modules\api\models\ProductTypeHasCategory;
use frontend\modules\api\models\Brand;
use frontend\modules\api\models\OrderProduct;
use frontend\modules\api\models\User;
use frontend\modules\api\models\Address;
use frontend\modules\api\models\Region;
use frontend\modules\api\models\News;
use frontend\modules\api\models\App;
use frontend\modules\api\models\District;
use frontend\modules\api\models\Promocode;
use frontend\modules\api\models\BallSet;
use frontend\modules\api\models\BallTranfer;
use frontend\modules\api\models\Auth;
use common\models\Order;
use common\models\PayType;
use common\models\ShopInfo;

use Yii;

class CatalogueController extends MainController
{
// PRIVATE METHODS
    private function numberConfirmCreate($params)
    {
        $model = new NumberConfirm;
        $model->number = (string)$phone;
        $model->create_time = new \yii\db\Expression('NOW()');
        $model->last_send_time = new \yii\db\Expression('NOW()');
        if($this->mode == 'test') {
          $model->code = 666666;
        } else {
          $model->code = rand(111111, 999999);
        }
        $model->hash = md5(uniqid().time().$model->code);
        $model->attempt_count = 0;
        $model->ip = Yii::$app->getRequest()->getUserIP();
        $model->is_used=0;
        if(isset($params['app_data']['device_id']) && $params['app_data']['device_id']) {
          $model->device_id=$params['app_data']['device_id'];
        }
        if(isset($params['app_data']['device_token']) && $params['app_data']['device_token']) {
          $model->device_token=$params['app_data']['device_token'];
        }
        if(isset($params['app_data']['device_model']) && $params['app_data']['device_model']) {
          $model->device_model=$params['app_data']['device_model'];
        }
        $model->type='auth';
        $model->save();
        $model->refresh();
        if($this->mode != 'test') {
            $sendState = Yii::$app->sms->send($phone, $model->code, false);
            if(!$sendState) {
              return 8111;
            } else {
              $model->last_send_time=new \yii\db\Expression('NOW()');
              $model->save();
              $model->refresh();
              return $model;
            }
        }
    }

    private function getDeliveryPrice($addressId)
    {
        $addressQuery = Address :: find()
                        -> with(['district'])
                        -> where(['id' => $addressId])
                        -> one();
        $districtQuery = District :: find()
                         -> with(['region'])
                         -> where(['id' => $addressQuery->district->id])
                         -> one();
        $regionQuery = Region :: find()
                       -> where(['id' => $districtQuery->region->id])
                       -> one();
        return $regionQuery->delivery_price;
    }

    private function getNews()
    {
      $toView = [];

      $news = News :: find()
              ->where(['is_active' => 1])
              ->all();
      foreach($news as $value)
      {
        $toView[] = [
            'id' => $value->id,
            'title' => Yii::$app->gFunctions->translate($value, 'title', $this->language),
            'url' => Yii::$app->urlManager->createAbsoluteUrl(['/news/view', "url"=>$value->url_key, "webview" => 1]),
            'description' =>  Yii::$app->gFunctions->translate($value, 'description', $this->language),
            'image' => $value->getImagePath()
        ];
      }

      return $toView;
    }

    private function getCategoryTree($type)
    {
      $categoryTree = [];

      $query = ProductType :: find()
                      ->with(['categories', 'categories.childCategories', 'categories.childCategories.childCategories'])
                      ->where(['product_type.is_active' => 1, 'product_type.is_deleted' => 0, 'product_type.id' => $type])
                      ->orderBy('product_type.position ASC')
                      ->distinct(true)
                      ->one();
      foreach ($query->categories as $key => $value)
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

        $categoryTree[] = [
          'id' => $value->id,
          'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
          'pos' => $value->position,
          'icon' => $value->getIcon(),
          'category'=> $lvl3
        ];
      }

      return $categoryTree;
    }

// PUBLIC METHODS
    public function actionIndex ()
    {
        $clean_resp = [];
        $params = $this->params;

        if (isset($params['page'])&&((int)$params['page']>0)) {
    			$page=(int)$params['page'];
    		} else {
    			$page=1;
    		}

    		$pageSize=4;

        $prod_query = Product :: find()
                      ->with('brand')
                      ->andWhere(['is_active' => 1])
                      ->andWhere(['is_deleted' => 0]);

          $offsetCount=0;
      		if($page>1) {
      			$offsetCount=($page-1)*$pageSize;
      		}
    		$prod_query->distinct(true);
    		$allCount=$prod_query->count();
    		if($offsetCount) {
    			$prod_query->offset($offsetCount);
    		}
    		$prod_query->limit($pageSize);
    		$productModels=$prod_query->all();

        if (!isset($productModels)) { return $this->returnResponse(8119); }
        $brand_query = Brand :: find()->all();
        if (!isset($brand_query)) { return $this->returnResponse(8120); }
        foreach ($productModels as $value)
        {
            $clean_resp [] = [
                'id' => $value->id,
                'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
                'brand_name' => Yii::$app->gFunctions->translate($value->brand, 'name', $this->language),
                // Цена со скидкой
                'price' => $value->min_price - ($value->sale_percentage/100)*$value->min_price,
                'image_link' => $value->ImagePath()
            ];
        }

        $newsList = $this->getNews();

        return $this->returnResponse(200, ['itemsCount' => (int)$allCount, 'page' => $page, 'itemsPerPage' => $pageSize, 'products'=>$clean_resp, 'news'=>$newsList]);
    }

    public function actionCategory ()
    {
        $params = $this->params;
        $boysArr = [];
        $girlsArr = [];
        $partnersArr = [];

        $boysArr = $this->getCategoryTree(1);

        $girlsArr = $this->getCategoryTree(2);

        $partnersArr = $this->getCategoryTree(3);

        return $this->returnResponse(200, ['boys_categories' => $boysArr,
                                           'girls_categories' => $girlsArr,
                                           'partners' => $partnersArr]);
    }

    public function actionSubcategory ()
    {
        $params = $this->params;
        $subcatArr = [];

        $subcat_query = Category :: find()
                        ->with(['products'])
                        ->where(['parent_id'=>$params['id'], 'is_active' => 1, 'is_deleted' => 0])
                        ->all();
        if (!isset($subcat_query)) { return $this->returnResponse(8121); }
        foreach ($subcat_query as $value)
        {
          $subcat_products = [];
          foreach ($value->products as $item)
          {
            $subcat_products [] = [
              'id' => $item->id,
              'name' => Yii::$app->gFunctions->translate($item, 'name', $this->language),
              // Цена со скидкой
              'price' => $item->min_price - ($item->sale_percentage/100)*$item->min_price,
              'image_link' => $item->ImagePath()
            ];
          }
          $subcatArr [] = [
              'id' => $value->id,
              'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
              'pos' => $value->position,
              'products' => $subcat_products
          ];
        }

        return $this->returnResponse(200, ['list' => $subcatArr]);
    }

    public function actionSelection ()
    {
        $params = $this->params;
        $products = [];
        $images = [];
        $stepSave = [];
        $key = 0;
        $isFavourite = 0;

        $productDetail_query = ProductItem :: find()
                               ->with(['product', 'size', 'color'])
                               ->where(['product_id' => $params['id']])
                               ->all();
        $productQuery = Product :: find()
                        ->with(['userHasProducts'])
                        ->where(['id' => $params['id']])
                        ->one();
        if (!empty($productQuery->userHasProducts))
        {
            foreach ($productQuery->userHasProducts as $product)
            {
                if ($product->user_id == $this->user->id) {
                    $isFavourite = 1;
                    break;
                }
                else continue;
            }
        }

        $images[] = $productQuery->ImagePath();

        foreach ($productDetail_query as $value)
        {
            $suitableSizes = [];
            $colors = [];
            if ($prevVal->color->id == $value->color->id) continue;
            else
            {
                foreach ($productDetail_query as $product)
                {
                    if ($product->color->id == $value->color->id)
                    $suitableSizes[] = [
                        'id' => $product->size->id,
                        'name' => Yii::$app->gFunctions->translate($product->size , 'name', $this->language)
                    ];
                }
                $colors = [
                    'id' => $value->color->id,
                    'name' => Yii::$app->gFunctions->translate($value->color , 'name', $this->language),
                    'hex' => $value->color->hex,
                    'hex2' => $value->color->hex2,
                    'icon' => $value->color->icon,
                    'border_hex' => $value->color->border_hex,
                    'suitableSizes' => $suitableSizes
                ];
                if (!empty($value->app_image)) {
                    $images[] = $value->ImagePath();
                }
                // else $images[] = 'http://mag.spg.uz/images/product/thumb/' . $value->product->app_image;
                $stepSave[$key] = $colors;
                $key++;
                $prevVal = $value;
            }
        }

        foreach ($productDetail_query as $value)
        {
            $products = [
                'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
                'share_url' => Yii::$app->urlManager->createAbsoluteUrl(['/catalog/view', 'url'=>$value->product->url_key]),
                'price' => $value->price - ($value->sale_percentage/100)*$value->price,
                'oldPrice' => $value->price,
                'is_fav' => $isFavourite,
                'description' => Yii::$app->gFunctions->translate($value->product, 'description', $this->language),
                'characts' => Yii::$app->gFunctions->translate($value->product, 'characteristics', $this->language),
                'images' => $images,
                'availableColors' => $stepSave
            ];
            return $this->returnResponse(200, $products);
        }
    }

    public function actionPicked()
    {
        $params = $this->params;
        $PickedProduct = [];
        $outOfStock = false;
        $outOfStockList = '';
        $turn = 0;

        $PickedProductQuery = ProductItem :: find()
                              ->with(['product', 'size', 'color', 'storageHasProductItems'])
                              ->where(['product_id' => $params['product_id']])
                              ->all();

        $productQuery = Product :: find()
                        -> with (['category'])
                        -> where (['id' => $params['product_id']])
                        -> one();
        // проверка на наличие на складе
        foreach ($PickedProductQuery as $item) {
            if (($item->color['id'] == $params['color_id']) && ($item->size['id'] == $params['size_id'])) {
                foreach ($item->storageHasProductItems as $stock)
                {
                    $pName = Yii::$app->gFunctions->translate($item, 'name', $this->language);
                    if (($stock->storage_id == 1) && ($stock->count <= 0)) {
                        $outOfStock = true;
                        if ($turn == 0) {
                            $outOfStockList = $pName;
                            $turn += 1;
                        }
                        elseif ($turn == sizeof($failproofCart)-1) {
                            $outOfStockList = $outOfStockList.', '.$pName;
                            $turn += 1;
                        }
                        else {
                            $outOfStockList = $outOfStockList.', '.$pName;
                            $turn += 1;
                        }
                    }
                }
            }
        }
        if ($outOfStock) {
            return $this->returnResponse(8129, $outOfStockList);
        }

        // заполнение списка вохможных пар ЦВЕТ--РАЗМЕР
        foreach ($PickedProductQuery as $item)
        {
            if (($item->color['id'] == $params['color_id']) && ($item->size['id'] == $params['size_id']))
            {
                if (empty($item->app_image)) $img = $item->product->app_image;
                else $img = $item->app_image;
                $PickedProduct = [
                    'id' => $item->id,
                    'name' => Yii::$app->gFunctions->translate($item, 'name', $this->language),
                    'share_url' => Yii::$app->urlManager->createAbsoluteUrl(['/catalog/view', 'url' => $item->product->url_key]),
                    'price' => $item->price - ($item->sale_percentage/100)*$item->price,
                    'oldPrice' => $item->price,
                    'description' => Yii::$app->gFunctions->translate($item->product, 'description', $this->language),
                    'image' => $item->ImagePath(),
                    'not_returnable' => $productQuery->category->is_non_returnable
                ];
            }
            else continue;
        }
        return $this->returnResponse(200, $PickedProduct);
    }

    public function actionPaytype()
    {
        $PaytypeQuery = PayType :: find ()
                        ->all();
        $payTypes = [];
        foreach ($PaytypeQuery as $value)
        {
            $paytypes [] = [
                'id' => $value->id,
                'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language)
            ];
        }

        $shop = ShopInfo :: find()->one();
        $shopAddress = [
            'address' => Yii::$app->gFunctions->translate($shop, 'address', $this->language),
            'schedule' => $shop->schedule,
            'telephone' => $shop->telephone,
            'longitude' => $shop->longitude,
            'latitude' => $shop->latitude
        ];

        if (!isset($this->user->id)) $myAddr = NULL;
        else {
            $addressQuery = Address :: find()
                            ->with(['district'])
                            ->where(['user_id' => $this->user->id, 'is_default' => 1])
                            ->one();

            $regionQuery = District :: find()
                           -> with(['region'])
                           -> where(['id' => $addressQuery->district->id])
                           -> one();

            $district = [
                'id' => $addressQuery->district->id,
                'name' => ($addressQuery->district)?Yii::$app->gFunctions->translate($addressQuery->district, 'name', $this->language):''
            ];
            $region = [
                'id' => $regionQuery->region->id,
                'name' => Yii::$app->gFunctions->translate($regionQuery->region, 'name', $this->language),
                'min_cart_price' => $regionQuery->region->min_delivery_price,
                'delivery_price' => $regionQuery->region->delivery_price
            ];

            $myAddr = [
                'id' => $addressQuery->id,
                'firstName' => $addressQuery->user_name,
                'lastName' => $addressQuery->user_surname,
                'region' => $region,
                'district' => $district,
                'street' => $addressQuery->street,
                'bld' => $addressQuery->building,
                'apt' => $addressQuery->apartment,
                'city' => $addressQuery->city,
                'zip' => $addressQuery->zipcode,
                'phone' => $addressQuery->user_phone
            ];
        }

        return $this->returnResponse(200, ['pay_types' => $paytypes, 'shop_info' => $shopAddress, 'my_addr' => $myAddr, 'points' => $this->user->ball]);
    }

    public function actionViewpromo()
    {
        $params = $this->params;

        $promo = Promocode :: find()
                 -> where(['code' => $params['promo']])
                 -> one();
        if ($promo && !empty($promo))
        {
            $addressQuery = Address :: find()
                            -> with(['district'])
                            -> where(['id' => $params['addr_id']])
                            -> one();
            $districtQuery = District :: find()
                             -> with(['region'])
                             -> where(['id' => $addressQuery->district->id])
                             -> one();
            $regionQuery = Region :: find()
                           -> where(['id' => $districtQuery->region->id])
                           -> one();
            return $this->returnResponse(200, ['rate' => $promo->percent, 'cartPrice' => (int)$regionQuery->min_delivery_price]);
        }
        else return $this->returnResponse(8153);
    }

    public function actionCheckout()
    {
        $params = $this->params;
        $failproofCart = [];
        $sendState = 0;
        $token = '';
        $outOfStock = false;
        $outOfStockList = '';
        $cartOverflow = false;
        $cartOverflowList = '';
        $turn = 0;

        $cart = new Order;
        $cart->is_paid = 0;
        $deviceTypeQuery = App :: find()
                           ->where(['auth_token' => $this->app_token])->one();
        // назначение адресной информации
        if ($params['delivery'] == 'courier') {
          if ($params['addr_id'] && $params['addr_id'] != 0) {
              $cart->address_id = $params['addr_id'];
              $BaseAddress = Address :: find()->with(['district'])
                             ->where(['id' => $params['addr_id']])->one();
              if (empty($BaseAddress)) return $this->returnResponse(8151);
              $deliveryRegion = District :: find()-> with(['region'])
                                -> where (['id' => $BaseAddress->district->id])
                                -> one();
          }
          else {
              $BaseAddress = new Address;
              if ($this->user->id) $BaseAddress->user_id = $this->user->id;
              $BaseAddress->create_time = new \yii\db\Expression('NOW()');
              $BaseAddress->user_phone = $params['phone'];
              $BaseAddress->user_name = $params['firstName'];
              $BaseAddress->user_surname = $params['lastName'];
              if (isset($params['addressInfo']['district_id'])) $BaseAddress->district_id = $params['district_id'];
              else {
                  return $this->returnResponse(8158);
              }
              if (isset($params['addressInfo']['street'])) $BaseAddress->street = $params['street'];
              if (isset($params['addressInfo']['bld'])) $BaseAddress->building = $params['bld'];
              if (isset($params['addressInfo']['apt'])) $BaseAddress->apartment = $params['apt'];
              if (isset($params['addressInfo']['city'])) $BaseAddress->city = $params['city'];
              if (isset($params['addressInfo']['zip'])) $BaseAddress->zipcode = $params['zip'];
              $BaseAddress->is_default = '1';
              $BaseAddress->save(false);
          }
          $delivery = new Address;
          if ($BaseAddress->user_id) $delivery->user_id = $BaseAddress->user_id;
          $delivery->create_time = $BaseAddress->create_time;
          $delivery->text = $BaseAddress->text;
          $delivery->district_id = $BaseAddress->district_id;
          $delivery->street = $BaseAddress->street;
          $delivery->building = $BaseAddress->building;
          $delivery->apartment = $BaseAddress->apartment;
          $delivery->zipcode = $BaseAddress->zipcode;
          $delivery->city = $BaseAddress->city;
          $delivery->name = $BaseAddress->name;
          $delivery->user_name = $BaseAddress->user_name;
          $delivery->user_surname = $BaseAddress->user_surname;
          $delivery->user_phone = $BaseAddress->user_phone;

          $addressData = [
              'user_name'=>$delivery->user_name,
              'user_surname'=>$delivery->user_surname,
              'phone'=>$delivery->user_phone,
              'city'=>$delivery->city,
              'district_id'=>$delivery->district_id,
              'street'=>$delivery->street,
              'building'=>$delivery->building,
              'apartment'=>$delivery->apartment,
              'zipcode'=>$delivery->zipcode
          ];
          $cart->address_data = json_encode($addressData);
          $cart->delivery_price = $this->getDeliveryPrice($params['addr_id']);
        }
        elseif ($params['pickup']) {
          $cart->delivery_price = 0;
          $cart->address_data = '';
          $cart->address_id = 0;
        }
        $cart->device_type = $deviceTypeQuery->device_type;
        $cart->create_time = new \yii\db\Expression('NOW()');
        $cart->delivery_method = $params['delivery'];
        $cart->source = "app";
        // заполнение корзины
        foreach ($params['cart'] as $key => $value)
        {
            $failproofCart[$key] = [
                'id' => $value['id'],
                'count' => $value['count']
            ];
            foreach ($params['cart'] as $item)
            {
                if ($value->id == $item)
                  $failproofCart[$key]->count += $item->count;
            }
        }
        $cart->save();

        // Буферная корзина для учета товара, наличествующего на складе
        foreach ($failproofCart as $cart_item)
        {
            $checkout_item = ProductItem :: find()
                                 ->with(['product', 'storageHasProductItems'])
                                 ->where(['id' => $cart_item['id']])->one();
            $pName = Yii::$app->gFunctions->translate($checkout_item, 'name', $this->language);
            if (!isset($checkout_item)) return $this->returnResponse(8122);
            foreach ($checkout_item->storageHasProductItems as $storage)
            {
                if (($storage->storage_id == 1) && ($storage->count <= 0) ) {
                  $outOfStock = true;
                  if ($turn == 0) {
                      $outOfStockList = $pName;
                      $turn += 1;
                  }
                  elseif ($turn == sizeof($failproofCart)-1) {
                      $outOfStockList = $outOfStockList.', '.$pName;
                      $turn += 1;
                  }
                  else {
                    $outOfStockList = $outOfStockList.', '.$pName;
                    $turn += 1;
                  }
               }
               if (($storage->storage_id == 1) && ($storage->count > 0) && ($storage->count-$cart_item['count'] <= 0))
               {
                 $cartOverflow = true;
                 if ($turn == 0) {
                     $cartOverflowList = $pName.' ('.$storage->count.')';
                     $turn += 1;
                 }
                 elseif ($turn == sizeof($failproofCart)-1) {
                     $cartOverflowList = $cartOverflowList.', '.$pName.' ('.$storage->count.')';
                     break;
                 }
                 else {
                   $cartOverflowList = $cartOverflowList.', '.$pName.' ('.$storage->count.')';
                   $turn += 1;
                 }
               }
            }
        }
        if ($outOfStock) {
          $cart->status = 'not_ready';
          $cart->save();
          return $this->returnResponse(8129, $outOfStockList);
        }
        if ($cartOverflow) {
          $cartItems = OrderProduct :: find()
                       -> where(['order_id' => $cart->id])
                       -> all();
          foreach ($cartItems as $item) $item->delete();
          $cart->delete();
          return $this->returnResponse(8162, $cartOverflowList);
        }
        foreach ($failproofCart as $cart_item)
        {
            $checkout_item = ProductItem :: find()
                                 ->with(['product', 'storageHasProductItems'])
                                 ->where(['id' => $cart_item['id']])->one();
            $item = new OrderProduct;
            $item->order_id = $cart->id;
            $item->price = $checkout_item->price - ($checkout_item->sale_percentage/100)*$checkout_item->price;
            $item->count = $cart_item['count'];
            $cart->price += $checkout_item->price * $cart_item['count'];
            $item->product_id = $checkout_item->product->id;
            $item->product_item_id = $cart_item['id'];
            $item->save();
            $cart->save();
            foreach ($checkout_item->storageHasProductItems as $storage)
            {
                if ($storage->storage_id == 1)
                {
                    $storage->count -= $cart_item['count'];
                    $storage->update();
                }
            }
        }
        $cart->final_price = $cart->price;
        $cart->save();
        //  Проверка и назначение скидки по промо-коду
        if ($params['promo'])
        {
            $currentTime = date("Y-m-d H:i:s");
            $promoDiscount = Promocode :: find()
                             -> where(['code' => $params['promo']])
                             -> andWhere(['has_used' => 0])
                             -> one();
            if (empty($promoDiscount) || ($promoDiscount->end_time < $currentTime)) {
              $cartItems = OrderProduct :: find()
                           -> where(['order_id' => $cart->id])
                           -> all();
              foreach ($cartItems as $item) $item->delete();
              $cart->delete();

              return $this->returnResponse(8153);
            }
            $cart->discont_price = $cart->price*($promoDiscount->percent/100);
            $cart->final_price -= $cart->price*($promoDiscount->percent/100);
            if ($cart->price < $deliveryRegion->region->min_delivery_price) {
              return $this->returnResponse(8154);
            }
            $promoDiscount->has_used = '1';
            $promoDiscount->used_time = date("Y-m-d H:i:s");
            $cart->promocode = $promoDiscount->code;
            $cart->save();
            $promoDiscount->save();
        }
        // авторизация -- регистрация
        $user = User::findOne($this->user->id);
        if (!$user)
        {
          $cart->status = 'not_confirmed';
          $cart->phone = $params['phone'];
          if(!Yii::$app->gFunctions->checkPhoneNumber(\Yii::$app->sms->trimNumber($params['phone']))) {
            return $this->returnResponse(8105);
          } else {
            $reqType='';
            $phone = \Yii::$app->sms->trimNumber($params['phone']);
            $existsUser=  User::find()->where(['phone'=>$phone])->one();
            if($existsUser) {
                // авторизация
                if($existsUser->is_active==0) {
          				return $this->returnResponse(8102);
          			}
            		if(!isset($params['app_data']['device_id'])) {
            			return $this->returnResponse(8137);
            		} elseif(trim($params['app_data']['device_id'])=='') {
            			return $this->returnResponse(8137);
            		}
          			$oldAuth=Auth::find()->where(['device_id'=>$params['app_data']['device_id']])->all();
          			foreach ($oldAuth as $item) {
          				$item->delete();
          			}
                $model=new NumberConfirm;
                $model->number=(string)$phone;
                $model->create_time=new \yii\db\Expression('NOW()');
                $model->last_send_time=new \yii\db\Expression('NOW()');
                if($this->mode=='test') {
                  $model->code=666666;
                } else {
                  $model->code= rand(111111, 999999);
                }
                $model->hash=md5(uniqid().time().$model->code);
                $model->attempt_count=0;
                $model->ip=Yii::$app->getRequest()->getUserIP();
                $model->is_used=0;
                if(isset($params['app_data']['device_id']) && $params['app_data']['device_id']) {
                  $model->device_id=$params['app_data']['device_id'];
                }
                if(isset($params['app_data']['device_token']) && $params['app_data']['device_token']) {
                  $model->device_token=$params['app_data']['device_token'];
                }
                if(isset($params['app_data']['device_model']) && $params['app_data']['device_model']) {
                  $model->device_model=$params['app_data']['device_model'];
                }
                if(isset($params['app_data']['device_model']) && $params['app_data']['device_model']) {
                  $model->device_model=$params['app_data']['device_model'];
                }
                if(isset($params['app_data']['device_type']) && $params['app_data']['device_type']) {
                  $model->device_type=$params['app_data']['device_type'];
                }
                if(isset($params['app_data']['ios_mode']) && $params['app_data']['ios_mode'] && in_array($params['app_data']['ios_mode'], ['sandbox', 'production'])) {
                  $model->ios_mode=$params['app_data']['ios_mode'];
                }

                if(isset($params['firstName']) || $params['lastName']) {
                  $model->username=$params['firstName'].' '.$params['lastName'];
                }
                $model->type='auth';
                $model->is_used=(string)$model->is_used;
                $token = $model->hash;
                if ($cart->save()) $model->order_id = $cart->id;
                else return $this->returnResponse(8122, $cart->errors);
                $model->save();
                $model->refresh();
                $user = $existsUser;
            }
            else
            {
              $model=  NumberConfirm::find()->where(['number'=>$phone])->orderBy('create_time DESC')->one();
              if($model) {
                $time=time()-strtotime($model->last_send_time);
                if($this->resendAllowTime>$time && $time>=0) {
                  return $this->returnResponse(8126, ['time_left'=>$this->resendAllowTime-$time]);
                }
                if($this->codeExpireTime<(time()-strtotime($model->last_send_time))) {
                  $model->is_used=0;
                  $model->save(false);
                  $model=null;
                }
              }
              if($model===NULL) {
                $model=new NumberConfirm;
                $model->number=(string)$phone;
                $model->create_time=new \yii\db\Expression('NOW()');
                $model->last_send_time=new \yii\db\Expression('NOW()');
                if($this->mode=='test') {
                  $model->code=666666;
                } else {
                  $model->code= rand(111111, 999999);
                }
                $model->hash=md5(uniqid().time().$model->code);
                $model->attempt_count=0;
                $model->ip=Yii::$app->getRequest()->getUserIP();
                $model->is_used=0;
                if(isset($params['app_data']['device_id']) && $params['app_data']['device_id']) {
                  $model->device_id=$params['app_data']['device_id'];
                }
                if(isset($params['app_data']['device_token']) && $params['app_data']['device_token']) {
                  $model->device_token=$params['app_data']['device_token'];
                }
                if(isset($params['app_data']['device_model']) && $params['app_data']['device_model']) {
                  $model->device_model=$params['app_data']['device_model'];
                }
                if(isset($params['app_data']['device_model']) && $params['app_data']['device_model']) {
                  $model->device_model=$params['app_data']['device_model'];
                }
                if(isset($params['app_data']['device_type']) && $params['app_data']['device_type']) {
                  $model->device_type=$params['app_data']['device_type'];
                }
                if(isset($params['app_data']['ios_mode']) && $params['app_data']['ios_mode'] && in_array($params['app_data']['ios_mode'], ['sandbox', 'production'])) {
                  $model->ios_mode=$params['app_data']['ios_mode'];
                }

                if(isset($params['firstName']) || $params['lastName']) {
                  $model->username=$params['firstName'].' '.$params['lastName'];
                }
                $model->type='reg';
                $model->is_used=(string)$model->is_used;
                $token = $model->hash;
                if ($cart->save()) $model->order_id = $cart->id;
                else return $this->returnResponse(8122, $cart->errors);
                $model->save();
                $model->refresh();
                $user = NULL;
              } else {
                $time=time()-strtotime($model->last_send_time);
                if($this->resendAllowTime>$time && $time>=0) {
                  return $this->returnResponse(8109, ['time_left'=>$time]);
                }
                if($this->codeExpireTime<(time()-strtotime($model->last_send_time))) {
                  return $this->returnResponse(8110);
                }
              }
            }
            if($this->mode!='test') {
              $sendState=Yii::$app->sms->send($phone, $model->code, false);
              if(!$sendState) {
                return $this->returnResponse(8111);
              } else {
                $model->last_send_time=new \yii\db\Expression('NOW()');
                $model->save();
                $model->refresh();
              }
            }
          }
        }
        else {
            $cart->user_id = $this->user->id;
            $cart->status = "created";
            $cart->phone = $this->user->phone;
            $cart->user_name = $this->user->user_name.' '.$this->user->user_surname;
            if (!$cart->save()) return $this->returnResponse(8122, $cart->errors);
            else $cart->save();
        }
        //  Проверка и вычет баллов
        if ($params['points'] && !empty($params['points']))
        {
            // проверка на переполнение
            if(($user->ball-$params['points']) < 0) {
              $cartItems = OrderProduct :: find()
                           -> where(['order_id' => $cart->id])
                           -> all();
              foreach ($cartItems as $item) $item->delete();
              $cart->delete();
              return $this->returnResponse(8155);
            }
            $cart->final_price -= $params['points'];
            if ($user != NULL) {
                $user->ball -= $params['points'];
                $user->save();
            }
            $cart->ball_price = $params['points'];
            $ballEntry = new BallTranfer;
            $ballEntry->ball = $params['points'];
            $ballEntry->add_type = 'removed';
            $ballEntry->user_id = $this->user->id;
            $ballEntry->create_time = new \yii\db\Expression('NOW()');
            $cart->save();
            $ballEntry->order_id = $cart->id;
            $ballEntry->save();
        }
        //  Добавление баллов
        $upperLim = BallSet :: find()
                    -> where(['is_active' => 1])
                    -> orderBy('position ASC')
                    -> all();
        foreach ($upperLim as $setting)
        {
            if ((!$setting->max_sum && ($cart->price >= $setting->min_sum)) || ($cart->price <= $setting->max_sum)
             && ($cart->price >= $setting->min_sum))
            {
                $cart->earned_ball = ($setting->percent/100)*$cart->price;
            }
        }
        if (!isset($params['pay_type'])) {
          $cartItems = OrderProduct :: find()
                       -> where(['order_id' => $cart->id])
                       -> all();
          foreach ($cartItems as $item) $item->delete();
          $cart->delete();
          return $this->returnResponse(8137);
        }
        if ($params['pay_type'] == 1) $cart->is_paid = 1;
        $cart->pay_type_id = $params['pay_type'];
        $cart->save();
        return $this->returnResponse(200, ['Order_ID' => $cart->id, 'sms_sent' => $sendState, 'token' => $token]);
    }

    public function actionDeliveryprice()
    {
        $params = $this->params;
        $deliveryPrice = [];

        $addressQuery = Address :: find()
                        -> with(['district'])
                        -> where(['id' => $params['addr_id']])
                        -> one();
        $districtQuery = District :: find()
                         -> with(['region'])
                         -> where(['id' => $addressQuery->district->id])
                         -> one();
        $regionQuery = Region :: find()
                       -> where(['id' => $districtQuery->region->id])
                       -> one();
        $deliveryPrice = [
          'price' => $regionQuery->delivery_price,
          'min_price' => $regionQuery->min_delivery_price
        ];

        return $this->returnResponse(200, $deliveryPrice);
    }

}
