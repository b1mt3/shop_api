<?php

namespace frontend\modules\cashbox\models;

use Yii;
use common\models\Storage as BaseStorage;

class Storage extends BaseStorage
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStorageHasProductItems()
    {
        return $this->hasMany(StorageHasProductItem::className(), ['storage_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductItems()
    {
        return $this->hasMany(ProductItem::className(), ['id' => 'product_item_id'])->viaTable('storage_has_product_item', ['storage_id' => 'id']);
    }
}
