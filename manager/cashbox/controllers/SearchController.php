<?php
namespace frontend\modules\cashbox\controllers;

use frontend\modules\cashbox\models\Product;
use frontend\modules\cashbox\models\ProductItem;
use common\models\Order;
use frontend\modules\cashbox\models\OrderProduct;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Yii;

class SearchController extends MainController
{
	// default search by barcode
		public function actionIndex() {
				$params = $this->params;
        $clean_resp = [];

				$search_result = Product::find()->where(['bar_code'=>$params['bar_code']])->all();
				if (!isset($search_result)) { return $this->returnResponse(9101); }
			  foreach ($search_result as $key => $value)
				{
					$productDetail_query = ProductItem :: find()
	                               ->with(['size', 'color'])
	                               ->where(['product_id' => $value->id])
	                               ->one();
					$sizes = [
						  'id' => $productDetail_query->size['id'],
							'name' => Yii::$app->gFunctions->translate($productDetail_query->size, 'name', $this->language)
					];
					$colors = [
						  'id' => $productDetail_query->color['id'],
							'name' => Yii::$app->gFunctions->translate($productDetail_query->color, 'name', $this->language),
							'hex' => $productDetail_query->color['hex'],
							'checkbox' => $productDetail_query->color['checkbox'],
							'hex2' => $productDetail_query->color['hex2'],
							'icon' => $productDetail_query->color['icon'],
							'border' => $productDetail_query->color['border_hex']
					];
					$images = $productDetail_query->image;
	        $clean_resp [] = [
	            'id' => $productDetail_query->id,
	            'name' => Yii::$app->gFunctions->translate($productDetail_query, 'name', $this->language),
	            'price' => $productDetail_query->price,
	            'description' => Yii::$app->gFunctions->translate($productDetail_query->product, 'description', $this->language),
							 //TODO 'image' =>
							 //TODO 'icon' =>
							 //TODO 'category' =>
							 //TODO 'brand' =>
	            'colors' => $colors,
	            'sizes' => $sizes
	        ];
				}
			  return $this->returnResponse(200, $clean_resp);
		}

// search by SKU-code
		public function actionSku() {
				$params = $this->params;
        $clean_resp = [];

				$search_result = Product::find()->where(['sku_code'=>$params['sku_code']]);
				if (!isset($search_result)) { return $this->returnResponse(9102); }
			  foreach ($search_result as $key => $value)
				{
					$sizes = [
						  'id' => $productDetail_query->size['id'],
							'name' => Yii::$app->gFunctions->translate($productDetail_query->size, 'name', $this->language)
					];
					$colors = [
						  'id' => $productDetail_query->color['id'],
							'name' => Yii::$app->gFunctions->translate($productDetail_query->color, 'name', $this->language),
							'hex' => $productDetail_query->color['hex'],
							'checkbox' => $productDetail_query->color['checkbox'],
							'hex2' => $productDetail_query->color['hex2'],
							'icon' => $productDetail_query->color['icon'],
							'border' => $productDetail_query->color['border_hex']
					];
	        $clean_resp = [
	            'id' => $productDetail_query->id,
	            'name' => Yii::$app->gFunctions->translate($productDetail_query, 'name', $this->language),
	            'price' => $productDetail_query->price,
	            'description' => Yii::$app->gFunctions->translate($productDetail_query->product, 'description', $this->language),
	            'colors' => $colors,
	            'sizes' => $sizes
	        ];
				}
			  return $this->returnResponse(200, $clean_resp);
		}

// checkout behaviour
    public function actionCheckout ()
		{
				$params = $this->params;
				$cart = new Order;
//				$cart->user_id = $this->user->id;
				$cart->status = "created";
				$cart->is_paid = 0;
				$cart->create_time = new \yii\db\Expression('NOW()');
//				$cart->phone = $this->user->phone;
				$cart->user_name = "assistant";
				$cart->discount_price = 0;
				$cart->delivery_price = 8000;
				$cart->source = "web";
				$cart->save();
				foreach ($params['cart'] as $cart_item)
				{
						$item = new OrderProduct;
						$item->order_id = $cart->id;
						$item->count = $cart_item['count'];
						$checkout_item = ProductItem :: find()
																 ->with(['product'])
																 ->where(['id' => $cart_item['id']])
																 ->one();
						if (!isset($checkout_item)) return $this->returnResponse(8122);
						$cart->price += $checkout_item->price;
						$item->product_id = $checkout_item->product->id;
						$item->product_item_id = $cart_item['id'];
				}
				if (isset($params['cash']) && isset($params['card']))
				{
					  $cart->pay_type_id = 2;
				}
				else if (isset($params['cash'])) { $cart->pay_type_id = 0; }
				else { $cart->pay_type_id = 1; }
				$cart->is_paid = 1;
				$cart->save();
				return $this->returnResponse(200, ['Order_ID' => $cart->id]);
		}
}
