<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Product as BaseProduct;

class Product extends BaseProduct
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducer()
    {
        return $this->hasOne(Producer::className(), ['id' => 'producer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductType()
    {
        return $this->hasOne(ProductType::className(), ['id' => 'product_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductHasAges()
    {
        return $this->hasMany(ProductHasAge::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAges()
    {
        return $this->hasMany(Age::className(), ['id' => 'age_id'])->viaTable('product_has_age', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductItems()
    {
        return $this->hasMany(ProductItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSearchIndices()
    {
        return $this->hasMany(SearchIndex::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasProducts()
    {
        return $this->hasMany(UserHasProduct::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_has_product', ['product_id' => 'id']);
    }

    public function ImagePath() {
    if($this->app_image && file_exists(Yii::getAlias('@images').'/product/thumb/'.$this->app_image)) {
      return \yii\helpers\Url::home(true).'images/product/thumb/'.$this->app_image;
    } elseif ($this->image && file_exists(Yii::getAlias('@images').'/product/thumb/'.$this->image)) {
      return \yii\helpers\Url::home(true).'images/product/thumb/'.$this->image;
    }
    else {
      return \yii\helpers\Url::home(true).'img/no_photo.png';
    }
  }

  public function getPrice() {
		$price='';
		$productContents=  $this->productItems;
		$priceArr=[];
		foreach ($productContents as $item) {
			$itemPrice=(int)$item->price;
			if($itemPrice) {
				if(!in_array($itemPrice, $priceArr)) {
					$priceArr[]=$itemPrice;
				}
			}
		}
		if(count($priceArr)>0) {
			if(count($priceArr)==1) {
				$price= Yii::$app->gFunctions->numberFormat($priceArr[0]).' '.Yii::t('front', 'sum');
			} else {
				$price=  Yii::$app->gFunctions->numberFormat(min($priceArr)).' - '.Yii::$app->gFunctions->numberFormat(max($priceArr)).' '.Yii::t('front', 'sum');
			}
		}
		return $price;
	}
}
