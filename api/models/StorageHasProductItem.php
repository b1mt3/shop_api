<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\StorageHasProductItem as BaseStorageHasProductItem;

class StorageHasProductItem extends BaseStorageHasProductItem
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductItem()
    {
        return $this->hasOne(ProductItem::className(), ['id' => 'product_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorage()
    {
        return $this->hasOne(Storage::className(), ['id' => 'storage_id']);
    }
}
