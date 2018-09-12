<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\RestaurantHasFoodCategory as BaseRestaurantHasFoodCategory;

/**
 * This is the model class for table "restaurant_has_food_category".
 *
 * @property integer $restaurant_id
 * @property integer $food_category_id
 *
 * @property FoodCategory $foodCategory
 * @property Restaurant $restaurant
 */
class RestaurantHasFoodCategory extends BaseRestaurantHasFoodCategory
{
    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoodCategory()
    {
        return $this->hasOne(FoodCategory::className(), ['id' => 'food_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }
}
