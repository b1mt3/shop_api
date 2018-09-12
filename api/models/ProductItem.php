<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\ProductItem as BaseProductItem;

class ProductItem extends \yii\db\ActiveRecord
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['product_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorageHasProductItems()
    {
        return $this->hasMany(StorageHasProductItem::className(), ['product_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorages()
    {
        return $this->hasMany(Storage::className(), ['id' => 'storage_id'])->viaTable('storage_has_product_item', ['product_item_id' => 'id']);
    }

    public function ImagePath() {
		if ($this->app_image && file_exists(Yii::getAlias('@images') . '/images/product/thumb/' . $this->app_image)) {
			return \yii\helpers\Url::home(true) . 'images/product/thumb/' . $this->app_image;
		} elseif ($this->image && file_exists(Yii::getAlias('@images') . '/images/product/thumb/' . $this->image)) {
			return \yii\helpers\Url::home(true) . 'images/product/thumb/' . $this->image;
		} else {
			return \yii\helpers\Url::home(true) . 'img/no_photo.png';
		}
	}

}
