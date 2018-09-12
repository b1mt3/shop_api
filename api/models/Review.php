<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Review as BaseReview;

class Review extends BaseReview
{
   
	public function returnObjectData() {
		return [
			'id'=>  $this->id,
			'user_name'=>  $this->user_name,
			'text'=>  $this->comment,
			'stars'=>  $this->stars,
			'date'=> strtotime($this->create_time),
			'date_text'=>  Yii::$app->formatter->asDate($this->create_time, 'long'),
		];
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
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
