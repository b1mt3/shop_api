<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Food as BaseFood;


class Food extends BaseFood
{
    public function returnObjectData() {
		return [
			'id'=>  $this->id,
			'name'=>  Yii::$app->gFunctions->translate($this, 'name', Yii::$app->controller->language),
			'image'=>  $this->getImagePath(),
			'flags'=> $this->returnFlags(),
			'price'=> $this->price,
			'price_text'=> $this->price.' '.Yii::t('app', 'KZT'),
			'short_description'=> Yii::$app->gFunctions->translate($this, 'short_description', Yii::$app->controller->language),
			'description'=> Yii::$app->gFunctions->translate($this, 'description', Yii::$app->controller->language),
			'additives'=>  $this->additivesList(),
			'cook_conditions'=>  $this->cookConditionsList()
		];
	}
	
	public function getImagePath() {
		if($this->app_image && file_exists(Yii::getAlias('@images').'/food/app/'.$this->app_image)) {
			return \yii\helpers\Url::home(true).'images/food/app/'.$this->app_image;
		} else {
			return \yii\helpers\Url::home(true).'images/appimages/dish_small2.png';
		}
	}
	
	public function returnFlags() {
		$flags=[];
		$currentTime=time();
		$createdTime=  strtotime($this->create_time);
		if(Yii::$app->controller->newFlagPeriod>($currentTime-$createdTime))  {
			$flags=[
				'type'=>'new',
				'text'=>Yii::t('app', 'New'),
			];
		}
		return (object)$flags;
	}
	
	public function additivesList() {
		$ret=[];
		$additives=  $this->additives;
		foreach ($additives as $item) {
			$ret[]=[
				'id'=>$item->id,
				'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
				'price'=>  $item->price,
				'price_text'=> $item->price.' '.Yii::t('app', 'KZT')
			];
		}
		return $ret;
	}
	
	public function cookConditionsList() {
		$ret=[];
		$conds=  $this->conds;
		foreach ($conds as $item) {
			$ret[]=[
				'id'=>$item->id,
				'name'=>Yii::$app->gFunctions->translate($item, 'name', Yii::$app->controller->language),
			];
		}
		return $ret;
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditives()
    {
        return $this->hasMany(Additive::className(), ['food_id' => 'id'])->where(['is_deleted'=>0]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConds()
    {
        return $this->hasMany(Cond::className(), ['food_id' => 'id']);
    }

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
    public function getRestaurantSet()
    {
        return $this->hasOne(RestaurantSet::className(), ['id' => 'restaurant_set_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoodHasIngredients()
    {
        return $this->hasMany(FoodHasIngredient::className(), ['food_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngredients()
    {
        return $this->hasMany(Ingredient::className(), ['id' => 'ingredient_id'])->viaTable('food_has_ingredient', ['food_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFoods()
    {
        return $this->hasMany(OrderFood::className(), ['food_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantHasFoods()
    {
        return $this->hasMany(RestaurantHasFood::className(), ['food_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['id' => 'restaurant_id'])->viaTable('restaurant_has_food', ['food_id' => 'id']);
    }
}
