<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\FoodCategory as BaseFoodCategory;


class FoodCategory extends BaseFoodCategory
{
	
   
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoods()
    {
        return $this->hasMany(Food::className(), ['food_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasFoodCategories()
    {
        return $this->hasMany(RestaurantHasFoodCategory::className(), ['food_category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['id' => 'restaurant_id'])->viaTable('restaurant_has_food_category', ['food_category_id' => 'id']);
    }
}
