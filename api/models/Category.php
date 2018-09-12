<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Category as BaseCategory;

class Category extends \yii\db\ActiveRecord
{
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

    public function getIcon()
    {
        if ($this->icon && file_exists(Yii::getAlias('@images').'/category/'.$this->icon))
          return \yii\helpers\Url::home(true).'images/category/'.$this->icon;
        else return \yii\helpers\Url::home(true).'img/no_photo.png';
    }
}
