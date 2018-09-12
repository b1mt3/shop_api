<?php
namespace frontend\modules\cashbox\controllers;

use frontend\modules\cashbox\models\Order;
use frontend\modules\cashbox\models\Storage;
use frontend\modules\cashbox\models\StorageHasProductItem;
use frontend\modules\cashbox\models\Product;
use frontend\modules\cashbox\models\ProductItem;
use frontend\modules\cashbox\models\OrderProduct;
use frontend\modules\cashbox\models\SearchIndex;
use frontend\modules\cashbox\models\Brand;
use frontend\modules\cashbox\models\Producer;
use frontend\modules\cashbox\models\Color;
use frontend\modules\cashbox\models\Category;

use Yii;

class SearchController extends MainController
{
	// default search by barcode
		public function actionIndex() {
				$params = $this->params;
				$parentInfo = [];
				$childInfo = [];
				$storage_name = '';
				$itemStock = 0;

				$productDetail_query = ProductItem :: find()
                               ->with(['product', 'storages', 'storageHasProductItems', 'size', 'color'])
                               ->where(['bar_code' => $params['bar_code']])
                               ->all();
			  foreach ($productDetail_query as $item)
        {
						$CategBrand_query = Product :: find()
																->with(['producer', 'category', 'brand'])
																->where(['id' => $item->product->id])
																->one();
				    foreach ($item->storageHasProductItems as $storageStock)
						{
							  if ($storageStock->storage_id == 3)
								{
										if ($storageStock->count > 0)
										{
												$itemStock = $storageStock->count;
												foreach ($item->storages as $storage)
												{
														if ($storage->id == $storageStock->storage_id)
																$storage_name = Yii::$app->gFunctions->translate($storage, 'name', $this->language);
												}

												$colors = [
														'name' => Yii::$app->gFunctions->translate($item->color, 'name', $this->language),
														'hex' => $item->color->hex,
														'hex2' => $item->color->hex2
												];

												if (isset($item->app_image))
												{
														$parentInfo = [
																'id' => $CategBrand_query->id,
																'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																'price' => $CategBrand_query->min_price,
																'image' => 'http://mag.spg.uz/images/product/thumb/' . $item->app_image,
																'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																'bar_code' => $item->bar_code,
																'sku' => $item->sku_code,
																'stock_count' => $itemStock,
																'stock' => $storage_name,
																'color' => $colors,
																'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
														];
														$childInfo [] = [
																'id' => $item->id,
																'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																'price' => $item->price,
																'image' => 'http://mag.spg.uz/images/product/thumb/' . $item->app_image,
																'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																'bar_code' => $item->bar_code,
																'sku' => $item->sku_code,
																'stock_count' => $itemStock,
																'stock' => $storage_name,
																'color' => $colors,
																'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
														];
												}
												else {
														$parentInfo = [
																'id' => $CategBrand_query->id,
																'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																'price' => $CategBrand_query->min_price,
																'image' => 'http://mag.spg.uz/img/no_photo.png',
																'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																'bar_code' => $item->bar_code,
																'sku' => $item->sku_code,
																'stock_count' => $itemStock,
																'stock' => $storage_name,
																'color' => $colors,
																'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
														];
														$childInfo [] = [
																'id' => $item->id,
																'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																'price' => $item->price,
																'image' => 'http://mag.spg.uz/img/no_photo.png',
																'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																'bar_code' => $item->bar_code,
																'sku' => $item->sku_code,
																'stock_count' => $itemStock,
																'stock' => $storage_name,
																'color' => $colors,
																'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
														];
												}
										}
										else return $this->returnResponse(8129);
								}
								else continue;
						}
				}
			  return $this->returnResponse(200, ['parent' => $parentInfo, 'list' => $childInfo]);
		}

// search by SKU-code
		public function actionSku() {
			$params = $this->params;
			$parentInfo = [];
			$childInfo = [];
			$storage_name = '';
			$itemStock = 0;

			$productDetail_query = ProductItem :: find()
														 ->with(['product', 'storages', 'storageHasProductItems', 'size', 'color'])
														 ->where(['sku_code' => $params['sku_code']])
														 ->all();
			 foreach ($productDetail_query as $item)
	     {
						$CategBrand_query = Product :: find()
																->with(['producer', 'category', 'brand'])
																->where(['id' => $item->product->id])
																->one();
				    foreach ($item->storageHasProductItems as $storageStock)
						{
							  if ($storageStock->storage_id == 3)
								{
										if ($storageStock->count > 0)
										{
												$itemStock = $storageStock->count;
												foreach ($item->storages as $storage)
												{
														if ($storage->id == $storageStock->storage_id)
																$storage_name = Yii::$app->gFunctions->translate($storage, 'name', $this->language);
												}

												$colors = [
						//							 'id' => $item->color->id,
														'name' => Yii::$app->gFunctions->translate($item->color, 'name', $this->language),
														'hex' => $item->color->hex,
														'hex2' => $item->color->hex2
												];

												if (isset($item->app_image))
												{
														$parentInfo = [
																'id' => $CategBrand_query->id,
																'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																'price' => $CategBrand_query->min_price,
																'image' => 'http://mag.spg.uz/images/product/thumb/' . $item->app_image,
																'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																'bar_code' => $item->bar_code,
																'sku' => $item->sku_code,
																'stock_count' => $itemStock,
																'stock' => $storage_name,
																'color' => $colors,
																'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
														];
														$childInfo [] = [
																'id' => $item->id,
																'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																'price' => $item->price,
																'image' => 'http://mag.spg.uz/images/product/thumb/' . $item->app_image,
																'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																'bar_code' => $item->bar_code,
																'sku' => $item->sku_code,
																'stock_count' => $itemStock,
																'stock' => $storage_name,
																'color' => $colors,
																'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
														];
												}
												else {
														$parentInfo = [
																'id' => $CategBrand_query->id,
																'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																'price' => $CategBrand_query->min_price,
																'image' => 'http://mag.spg.uz/img/no_photo.png',
																'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																'bar_code' => $item->bar_code,
																'sku' => $item->sku_code,
																'stock_count' => $itemStock,
																'stock' => $storage_name,
																'color' => $colors,
																'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
														];
														$childInfo [] = [
																'id' => $item->id,
																'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																'price' => $item->price,
																'image' => 'http://mag.spg.uz/img/no_photo.png',
																'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																'bar_code' => $item->bar_code,
																'sku' => $item->sku_code,
																'stock_count' => $itemStock,
																'stock' => $storage_name,
																'color' => $colors,
																'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
														];
												}
										}
										else return $this->returnResponse(8129);
								}
								else continue;
						}
				}
			  return $this->returnResponse(200, ['parent' => $parentInfo, 'list' => $childInfo]);
		}

// checkout behaviour
    public function actionCheckout ()
		{
				$params = $this->params;
				$cart = new Order;
				$cart->user_id = $this->user->id;
				$cart->status = 'delivered';
				$cart->is_paid = '1';
				$cart->create_time = new \yii\db\Expression('NOW()');
				$cart->delivery_time = new \yii\db\Expression('NOW()');
				$cart->phone = $this->user->number;
				$cart->user_name = 'assistant';
				$cart->discount_price = 0;
				$cart->delivery_price = 8000;
				$cart->source = 'postbank';
				$cart->device_type = 'postbank';
				$cart->save();
				foreach ($params['cart'] as $cart_item)
				{
						$item = new OrderProduct;
						$item->order_id = $cart->id;
						$item->count = $cart_item['count'];
						$checkout_item = ProductItem :: find()
																 ->with(['product', 'storages', 'storageHasProductItems'])
																 ->where(['id' => $cart_item['id']])
																 ->one();
						if (!isset($checkout_item)) return $this->returnResponse(8122);
					  foreach ($checkout_item->storageHasProductItems as $itemStock)
						{
							  if ($itemStock->storage_id == 3)
								{
									  if ($itemStock->count >= $cart_item['count'])
										{
												$cart->price += $checkout_item->price * $cart_item['count'];
												$item->product_id = $checkout_item->product->id;
												$item->product_item_id = $cart_item['id'];
												$item->order_id = $cart->id;
												$itemStock->count -= $cart_item['count'];
												$item->save();
												$itemStock->update();
										}
	                  else return $this->returnResponse(8130, $itemStock->count);
							  }
								else continue;
						}
				}

		//  Проверка и вычет баллов
        if ($params['points'] && !empty($params['points']))
        {
            // проверка на переполнение
            if(($user->ball-$params['points']) < 0) return $this->returnResponse(8155);
            $cart->price -= $params['points'];
            $user->ball -= $params['points'];
            $ballEntry = new BallTransfer;
            $ballEntry->ball = $params['points'];
            $ballEntry->add_type = 'removed';
            $ballEntry->user_id = $this->user->id;
            $ballEntry->create_time = new \yii\db\Expression('NOW()');
            $cart->save();
            $ballEntry->order_id = $cart->id;
        }
    //  Добавление баллов
        else {
            $upperLim = BallSet :: find()
                        -> where(['is_active' => 1])
                        -> orderBy('position ASC')
                        -> all();
            foreach ($upperLim as $setting)
            {
                if (($cart->price <= $setting->max_sum) && ($cart_price->price >= $setting->min_sum))
                {
                    $user->ball += ($setting->percent/100)*$cart_price;
                    $ballEntry = new BallTransfer;
                    $ballEntry->ball = ($setting->percent/100)*$cart_price;
                    $ballEntry->add_type = 'added';
                    $ballEntry->type = 'earn';
                    $ballEntry->user_id = $this->user->id;
                    $ballEntry->create_time = new \yii\db\Expression('NOW()');
                    $cart->save();
                    $ballEntry->order_id = $cart->id;
                }
            }
        }

				if (isset($params['cash']) && isset($params['card']))
				{
					  $cart->pay_type_id = 2;
				}
				else if (isset($params['cash'])) { $cart->pay_type_id = 0; }
				else { $cart->pay_type_id = 1; }
				$cart->save();
				return $this->returnResponse(200, ['Order_ID' => $cart->id]);
		}

		public function actionSearchbox()
		{
        $params = $this->params;
        $searchResults = [];

        $SearchQuery = SearchIndex :: find()
                       ->with(['product'])
                       ->where(['LIKE', 'search_keywords', $params['keyword']])
                       ->all();
        if (!isset($SearchQuery)) return $this->returnResponse(8135);

        foreach ($SearchQuery as $key => $value)
				{
						$productDetail_query = ProductItem :: find()
																	 ->with(['product', 'storages', 'storageHasProductItems', 'size', 'color'])
																	 ->where(['product_id' => $value['product_id']])
																	 ->all();
						foreach ($productDetail_query as $item)
						{
								$CategBrand_query = Product :: find()
																		->with(['producer', 'category', 'brand'])
																		->where(['id' => $item->product->id])
																		->one();
						    foreach ($item->storageHasProductItems as $storageStock)
								{
									  if ($storageStock->storage_id == 3)
									  {
												if ($storageStock->count > 0)
												{
														$itemStock = $storageStock->count;
														foreach ($item->storages as $storage)
														{
																if ($storage->id == $storageStock->storage_id)
																		$storage_name = Yii::$app->gFunctions->translate($storage, 'name', $this->language);
														}

														$colors = [
								//							 'id' => $item->color->id,
																'name' => Yii::$app->gFunctions->translate($item->color, 'name', $this->language),
																'hex' => $item->color->hex,
																'hex2' => $item->color->hex2
														];

														if (isset($item->app_image))
														{
																$parentInfo = [
																		'id' => $CategBrand_query->id,
																		'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																		'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																		'price' => $CategBrand_query->min_price,
																		'image' => 'http://mag.spg.uz/images/product/thumb/' . $item->app_image,
																		'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																		'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																		'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																		'bar_code' => $item->bar_code,
																		'sku' => $item->sku_code,
																		'stock_count' => $itemStock,
																		'stock' => $storage_name,
																		'color' => $colors,
																		'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
																];
																$childInfo [] = [
																		'id' => $item->id,
																		'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																		'price' => $item->price,
																		'image' => 'http://mag.spg.uz/images/product/thumb/' . $item->app_image,
																		'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																		'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																		'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																		'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																		'bar_code' => $item->bar_code,
																		'sku' => $item->sku_code,
																		'stock_count' => $itemStock,
																		'stock' => $storage_name,
																		'color' => $colors,
																		'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
																];
														}
														else {
																$parentInfo = [
																		'id' => $CategBrand_query->id,
																		'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																		'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																		'price' => $CategBrand_query->min_price,
																		'image' => 'http://mag.spg.uz/img/no_photo.png',
																		'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																		'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																		'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																		'bar_code' => $item->bar_code,
																		'sku' => $item->sku_code,
																		'stock_count' => $itemStock,
																		'stock' => $storage_name,
																		'color' => $colors,
																		'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
																];
																$childInfo [] = [
																		'id' => $item->id,
																		'name' => Yii::$app->gFunctions->translate($CategBrand_query, 'name', $this->language),
																		'price' => $item->price,
																		'image' => 'http://mag.spg.uz/img/no_photo.png',
																		'category' => Yii::$app->gFunctions->translate($CategBrand_query->category, 'name', $this->language),
																		'brand' => Yii::$app->gFunctions->translate($CategBrand_query->brand, 'name', $this->language),
																		'description' => Yii::$app->gFunctions->translate($CategBrand_query, 'short_description', $this->language),
																		'producer' => Yii::$app->gFunctions->translate($CategBrand_query->producer, 'name', $this->language),
																		'bar_code' => $item->bar_code,
																		'sku' => $item->sku_code,
																		'stock_count' => $itemStock,
																		'stock' => $storage_name,
																		'color' => $colors,
																		'size' => Yii::$app->gFunctions->translate($item->size, 'name', $this->language)
																];
													    }
													}
													else return $this->returnResponse(8129);
											}
											else continue;
									}
							}
						  $searchResults[] = [
								  'parent' => $parentInfo,
									'child' => $childInfo
							];
        }
        return $this->returnResponse(200, ['result' => $searchResults]);
		}

    public function actionFilter()
		{
			  $params = $this->params;
				$brands = [];
				$producers = [];
				$colors = [];
				$pTypes = [];
				$pCatsBoys = [];
				$pCatsGirls = [];

				$catQuery = Category :: find()
				            ->with(['getProductTypeHasCategories'])
										->orderBy(['parent_id' => SORT_ASC])
				            ->all();
				foreach ($catQuery as $value)
				{
					  if ($value->getProductTypeHasCategories->product_type_id == 1)
						{
							  $pCatsBoys[] = [
									  'id' => $value->id,
										'parent' => $value->parent_id,
										'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
										'lvl' => $value->level,
										'pos' => $value->position
								];
						}

						else {
							  $pCatGirls[] = [
									  'id' => $value->id,
										'parent' => $value->parent_id,
										'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
										'lvl' => $value->level,
										'pos' => $value->position
								];
						}
				}

				$BrandQuery = Brand :: find ()
				              ->all();
        foreach ($BrandQuery as $value) {
						$brands [] = [
							  'id' => $value->id,
								'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language)
						];
				}

				$ProducerQuery = Producer :: find()
				                 ->all();
				foreach ($ProducerQuery as $value) {
						$producers[] = [
							  'id' => $value->id,
								'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language)
						];
				}

				$ColorQuery = Color :: find()
				              ->all();
				foreach ($ColorQuery as $value) {
					  $colors[] = [
							  'id' => $value->id,
								'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language)
						];
				}

				$pTypeQuery = ProductType :: find()
				             ->all();
				foreach ($pTypeQuery as $value) {
					  $pTypes[] = [
							  'id' => $value->id,
								'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language)
						];
				}

				return $this->returnResponse(200, ['brands' => $brands, 'colors' => $colors, 'producers' => $producers, 'genders' => $pTypes]);
		}

}
