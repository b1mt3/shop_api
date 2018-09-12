<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\ProductTypeHasCategory as BaseProductTypeHasCategory;

class ProductTypeHasCategory extends BaseProductTypeHasCategory
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_type_id' => 'Product Type ID',
            'category_id' => 'Category ID',
        ];
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
    public function getProductType()
    {
        return $this->hasOne(ProductType::className(), ['id' => 'product_type_id']);
    }
}
