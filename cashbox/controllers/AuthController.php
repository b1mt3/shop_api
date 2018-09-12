<?php

namespace frontend\modules\cashbox\controllers;

use frontend\modules\cashbox\models\Seller;

class AuthController extends MainController
{
    public function actionIndex()
    {
        $params = $this->params;

        if (!isset($params) || !$params) return $this->returnResponse(8137);

        $user = Seller :: find()
                ->where(['number' => $params['number']])
                ->one();
        if (!isset($user)) return $this->returnResponse(8100);
    		$user->auth_token = md5(uniqid().time().$params['number']);
    		$user->create_time = new \yii\db\Expression('NOW()');
    		$user->last_visit = new \yii\db\Expression('NOW()');

    		if($user->save()) {
    			$user->refresh();
    			return $this->returnResponse(200, ['auth_token' => $user->auth_token, 'seller_name' => $user->name]);
    		} else {
    			return $this->returnResponse(8116);
    		}
    }

    public function actionLogout()
    {
        $params = $this->params;
        $request=new \yii\web\Request;

        $authHeader = $request->getHeaders()->get('Auth');
        $app = Seller :: find()
               ->where(['auth_token' => $authHeader])
               ->one();
        if ($app)
        {
            $app->auth_token = "";
            $app->save();
            return $this->returnResponse(200);
        }
        return $this->returnResponse(8100);
    }
}
