<?php

namespace frontend\modules\api\controllers;

use Yii;
use frontend\modules\api\models\UserHasProduct;

class FavouriteController extends MainController
{
    public function actionIndex()
    {
        $params = $this->params;
        $favItems = [];

        $favQuery = UserHasProduct :: find()
                    ->with(['product'])
                    ->where(['user_id' => $this->user->id])
                    ->all();
        if (isset($favQuery)) {
            foreach ($favQuery as $value)
            {
                if (($value->product['is_active'] == 1) && ($value->product['is_deleted'] == 0))
                $favItems[] = [
                    'product_id' => $value->product['id'],
                    'name' => Yii::$app->gFunctions->translate($value->product, 'name', $this->language),
                    'img' => $value->product->ImagePath(),
                    'price' => $value->product['min_price']
                ];
            }
            return $this->returnResponse(200, ['list' => $favItems]);
        }
        else return $this->returnResponse(8139);
    }

    public function actionAdd()
    {
        $params = $this->params;
        if (!isset($params)) return $this->returnResponse(8137);

        $favQuery = new UserHasProduct;
        $favQuery->user_id = $this->user->id;
        $favQuery->product_id = $params['id'];
        if ($favQuery->save()) return $this->returnResponse(200);
        else return $this->returnResponse(8149, $favQuery->errors);
    }

    public function actionRm()
    {
        $params = $this->params;
        if (!isset($params)) return $this->returnResponse(8137);

        $favQuery = UserHasProduct :: find()
                    ->with(['user', 'product'])
                    ->where(['user_id' => $this->user->id, 'product_id' => $params['id']])
                    ->one();
        if (!isset($favQuery)) return $this->returnResponse(8139);
        if ($favQuery->delete()) return $this->returnResponse(200);
        else return $this->returnResponse(8149, $favQuery->errors);

    }
}
