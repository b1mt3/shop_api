<?php

namespace frontend\modules\manager\models;

use Yii;
use common\models\Category as BaseCategory;

class Category extends BaseCategory
{

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryFilters()
    {
        return $this->hasMany(CategoryFilter::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTypeHasCategories()
    {
        return $this->hasMany(ProductTypeHasCategory::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTypes()
    {
        return $this->hasMany(ProductType::className(), ['id' => 'product_type_id'])->viaTable('product_type_has_category', ['category_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id'])->orderBy('position DESC');
    }
}
