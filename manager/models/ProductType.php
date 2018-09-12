<?php

namespace frontend\modules\manager\models;

use Yii;
use common\models\ProductType as BaseProductType;

class ProductType extends BaseProductType
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryFilters()
    {
        return $this->hasMany(CategoryFilter::className(), ['product_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['product_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTypeHasCategories()
    {
        return $this->hasMany(ProductTypeHasCategory::className(), ['product_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('product_type_has_category', ['product_type_id' => 'id']);
    }
}
