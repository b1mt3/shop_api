<?php

namespace frontend\modules\cashbox\models;

use Yii;
use common\models\OrderProduct as BaseOrderProduct;

class OrderProduct extends BaseOrderProduct
{
  /**
   * @return \yii\db\ActiveQuery
   */
  public function getOrder()
  {
      return $this->hasOne(Order::className(), ['id' => 'order_id']);
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
  public function getProductItem()
  {
      return $this->hasOne(ProductItem::className(), ['id' => 'product_item_id']);
  }
}
