<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\OrderFood as BaseOrderFood;


class OrderFood extends BaseOrderFood
{
    public function returnObjectData() {
		$food=  $this->food;
		return [
			'id'=>  $this->food_id,
			'name'=>  Yii::$app->gFunctions->translate($food, 'name', Yii::$app->controller->language),
			'image'=> $food->getImagePath(),
			'price'=> $food->price,
			'price_text'=> $food->price.' '.Yii::t('app', 'KZT'),
			'count'=> $this->count,
			'additives'=> $this->getAdditiveList(),
			'cook_conditions'=> $this->cookConditionsList(),
		];
	}
	
	public function getAdditiveList() {
		$ret=[];
		foreach ($this->additives as $item) {
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
			break;
		}
		return $ret;
	}
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'food_id'], 'required'],
            [['order_id', 'food_id', 'count', 'price'], 'integer'],
            [['food_id'], 'exist', 'skipOnError' => true, 'targetClass' => Food::className(), 'targetAttribute' => ['food_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'food_id' => 'Food ID',
            'count' => 'Count',
            'price' => 'Стоимость на одного',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFood()
    {
        return $this->hasOne(Food::className(), ['id' => 'food_id']);
    }

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
    public function getOrderFoodHasAdditives()
    {
        return $this->hasMany(OrderFoodHasAdditive::className(), ['order_food_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditives()
    {
        return $this->hasMany(Additive::className(), ['id' => 'additive_id'])->viaTable('order_food_has_additive', ['order_food_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderFoodHasConds()
    {
        return $this->hasMany(OrderFoodHasCond::className(), ['order_food_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConds()
    {
        return $this->hasMany(Cond::className(), ['id' => 'cond_id'])->viaTable('order_food_has_cond', ['order_food_id' => 'id']);
    }
}
