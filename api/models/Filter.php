<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\Filter as BaseFilter;


class Filter extends BaseFilter
{
   
	public function listData() {
		return [
			'id'=>  $this->id,
			'name'=>  Yii::$app->gFunctions->translate($this, 'name', Yii::$app->controller->language),
			'filter'=>  Restaurant::getFilterFromModel($this->id),
		];
	}
	




	/**
     * @return \yii\db\ActiveQuery
     */
    public function getFilterHasKitchens()
    {
        return $this->hasMany(FilterHasKitchen::className(), ['filter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKitchens()
    {
        return $this->hasMany(Kitchen::className(), ['id' => 'kitchen_id'])->viaTable('filter_has_kitchen', ['filter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFilterHasRescats()
    {
        return $this->hasMany(FilterHasRescat::className(), ['filter_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRescats()
    {
        return $this->hasMany(Rescat::className(), ['id' => 'rescat_id'])->viaTable('filter_has_rescat', ['filter_id' => 'id']);
    }
}
