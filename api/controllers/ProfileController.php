<?php

namespace frontend\modules\api\controllers;

use common\models\Order;
use frontend\modules\api\models\District;
use frontend\modules\api\models\Address;
use frontend\modules\api\models\User;
use frontend\modules\api\models\Product;
use frontend\modules\api\models\ProductItem;
use frontend\modules\api\models\OrderProduct;
use frontend\modules\api\models\Kid;

use Yii;

class ProfileController extends MainController
{
    public function actionIndex()
    {
        $UserQuery = User :: find()
                     ->where(['id' => $this->user->id])
                     ->one();
        if (!isset($UserQuery)) return $this->returnResponse(8100);
        $UserInfo = [
            'firstName' => $UserQuery->user_name,
            'lastName' => $UserQuery->user_surname,
            'photo' => $UserQuery->getPhoto(),
            'points' => $UserQuery->ball
        ];
        return $this->returnResponse(200, ['profile' => $UserInfo]);
    }

    public function actionMyinfo()
    {
        $CurrentUserQuery = User :: find()
                            ->where(['id' => $this->user->id])
                            ->one();
        if (!isset($CurrentUserQuery)) return $this->returnResponse(8100);
        $CurrentUser = [
            'firstName' => $CurrentUserQuery->user_name,
            'lastName' => $CurrentUserQuery->user_surname,
            'phone' => $CurrentUserQuery->phone,
            'email' => $CurrentUserQuery->email,
            'bday' => $CurrentUserQuery->birth_date,
            'gender' => $CurrentUserQuery->gender
        ];
        return $this->returnResponse(200, ['currentInfo' => $CurrentUser]);
    }

    public function actionSave()
    {
        $params = $this->params;

        $CheckUserQuery = User :: find()
                          ->where(['id' => $this->user->id])
                          ->one();
        if (!isset($CheckUserQuery))
        {
            $NewUser = new User;
            $NewUser->phone = \Yii::$app->sms->trimNumber($params['phone']);
            if (isset($params['firstName'])) $NewUser->user_name = $params['firstName'];
            if (isset($params['lastName'])) $NewUser->user_surname = $params['lastName'];
            if (isset($params['email'])) $NewUser->email = $params['email'];
            if (isset($params['bday'])) $NewUser->birth_date = $params['bday'];
            if (isset($params['gender'])) $NewUser->gender = $params['gender'];
            $NewUser->create_time = new \yii\db\Expression('NOW()');
            $CheckUserQuery->role = (string)$CheckUserQuery->role;
            $CheckUserQuery->is_active = (string) $CheckUserQuery->is_active;
            $CheckUserQuery->test_mode = (string) $CheckUserQuery->test_mode;
            $CheckUserQuery->email_confirmed = (string) $CheckUserQuery->email_confirmed;
            $NewUser->save();

            return $this->returnResponse(200);
        }
        if (isset($params['firstName'])) $CheckUserQuery->user_name = $params['firstName'];
        if (isset($params['lastName'])) $CheckUserQuery->user_surname = $params['lastName'];
        if (isset($params['phone'])) $CheckUserQuery->phone = \Yii::$app->sms->trimNumber($params['phone']);
        if (isset($params['email'])) $CheckUserQuery->email = $params['email'];
        if (isset($params['bday'])) $CheckUserQuery->birth_date = $params['bday'];
        if (isset($params['gender'])) $CheckUserQuery->gender = $params['gender'];
        $CheckUserQuery->update_time = new \yii\db\Expression('NOW()');
        $CheckUserQuery->role = (string)$CheckUserQuery->role;
        $CheckUserQuery->is_active = (string) $CheckUserQuery->is_active;
        $CheckUserQuery->test_mode = (string) $CheckUserQuery->test_mode;
        $CheckUserQuery->email_confirmed = (string) $CheckUserQuery->email_confirmed;
        $CheckUserQuery->save();

        return $this->returnResponse(200);
    }

    public function actionMykids()
    {
        $params = $this->params;
        $kidList = [];

        $kidQuery = Kid :: find()
                    ->where(['user_id' => $this->user->id])
                    ->all();
        if (isset($kidQuery)) {
            foreach($kidQuery as $kid)
            {
                $start = date_create($kid->birth_date);
                $end = date_create();
                $kidList[] = [
                    'id' => $kid->id,
                    'name' => $kid->name,
                    'age' => date_diff($start, $end)->y,
                    'gender' => $kid->gender,
                    'bday' => $kid->birth_date
                ];
            }
            return $this->returnResponse(200, ['kids' => $kidList]);
        }
        else return $this->returnResponse(8136);
    }

    public function actionAddkid()
    {
        $params = $this->params;

        $kid = new Kid;
        $kid->name = $params['name'];
        $kid->birth_date = $params['bday'];
        $kid->gender = $params['gender'];
        $kid->user_id = $this->user->id;
        if ($kid->save()) return $this->returnResponse(200, ['kid_id' => $kid->id]);
        else return $this->returnResponse(8137);
    }

    public function actionEditkid()
    {
        $params = $this->params;

        $kidQuery = Kid :: find()
                    ->where(['user_id' => $this->user->id,'id' => $params['id']])
                    ->one();
        if (isset($params['name'])) {
            $kidQuery->name = $params['name'];
            if (!$kidQuery->save()) return $this->returnResponse(8137);
        }
        if (isset($params['bday'])) {
            $kidQuery->birth_date = $params['bday'];
            if (!$kidQuery->save()) return $this->returnResponse(8137);
        }
        if (isset($params['gender'])) {
            $kidQuery->gender = $params['gender'];
            if (!$kidQuery->save()) return $this->returnResponse(8137);
        }
        return $this->returnResponse(200);
    }

    public function actionRmkid()
    {
        $params = $this->params;

        $kidQuery = Kid :: find()
                    ->where(['user_id' => $this->user->id, 'id' => $params['id']])
                    ->one();
        if ($kidQuery->delete())
            return $this->returnResponse(200);
        else return $this->returnResponse(8138);
    }
}
