<?php

namespace frontend\modules\api\controllers;

use common\models\Order;
use frontend\modules\api\models\District;
use frontend\modules\api\models\Product;
use frontend\modules\api\models\ProductItem;
use frontend\modules\api\models\OrderProduct;
use frontend\modules\api\models\Address;
use frontend\modules\api\models\Region;

use Yii;

class AddressController extends MainController
{
    public function actionIndex()
    {
        $params = $this->params;
        $toReturnAddr = [];
        $district = [];
        $allCount = 0;
        $pageSize = 0;

        if (isset($params['page'])&&((int)$params['page']>0)) {
    			$page=(int)$params['page'];
    		} else {
    			$page=1;
    		}

    		$pageSize=5;

        if (!$this->user->id) {
            $toReturnAddr = [];
            return $this->returnResponse(200, ['itemsCount' => (int)$allCount,
                'page' => $page,
                'itemsPerPage' => $pageSize,
                'addresses' => $toReturnAddr]);
        }
        $addressQuery = Address :: find()
                        ->with(['district'])
                        ->where(['user_id' => $this->user->id]);

        $offsetCount=0;
    		if($page>1) {
    			$offsetCount=($page-1)*$pageSize;
    		}

        $addressQuery->distinct(true);
        $allCount = $addressQuery->count();
        if ($offsetCount) $addressQuery->offset($offsetCount);
        $addressQuery->limit($pageSize);
        $addressModels = $addressQuery->all();
        if (!isset($addressModels)) { return $this->returnResponse(8125); }

        foreach ($addressModels as $item)
        {
            $regionQuery = District :: find()
                           -> with(['region'])
                           -> where(['id' => $item->district->id])
                           -> one();

            $district = [
                'id' => $item->district->id,
                'name' => Yii::$app->gFunctions->translate($regionQuery, 'name', $this->language)
            ];

            $region = [
                'id' => $regionQuery->region->id,
                'name' => Yii::$app->gFunctions->translate($regionQuery->region, 'name', $this->language),
                'min_cart_price' => $regionQuery->region->min_delivery_price,
                'delivery_price' => $regionQuery->region->delivery_price
            ];

            $toReturnAddr[] = [
                'id' => $item->id,
                'firstName' => $item->user_name,
                'lastName' => $item->user_surname,
                'region' => $region,
                'district' => $district,
                'street' => $item->street,
                'bld' => $item->building,
                'apt' => $item->apartment,
                'city' => $item->city,
                'zip' => $item->zipcode,
                'phone' => $item->user_phone,
                'default' => $item->is_default
            ];
        }
        return $this->returnResponse(200, ['itemsCount' => (int)$allCount, 'page' => $page, 'itemsPerPage' => $pageSize, 'addresses' => $toReturnAddr]);
    }

    public function actionCreateaddr()
    {
        $params = $this->params;

        $newAddr = new Address;
        $newAddr->user_id = $this->user->id;
        $newAddr->create_time = new \yii\db\Expression('NOW()');
        if (isset($params['firstName'])) $newAddr->user_name = $params['firstName'];
        if (isset($params['lastName'])) $newAddr->user_surname = $params['lastName'];
        if (isset($params['district_id'])) $newAddr->district_id = $params['district_id'];
        else {
            return $this->returnResponse(8158);
        }
        if (isset($params['street'])) $newAddr->street = $params['street'];
        if (isset($params['bld'])) $newAddr->building = $params['bld'];
        if (isset($params['apt'])) $newAddr->apartment = $params['apt'];
        if (isset($params['city'])) $newAddr->city = $params['city'];
        if (isset($params['zip'])) $newAddr->zipcode = $params['zip'];
        if (isset($params['phone'])) $newAddr->user_phone = $params['phone'];
        $newAddr->is_default = '1';
        $newAddr->save();

        return $this->returnResponse(200, ['addrID' => $newAddr->id]);
    }

    public function actionEditaddr()
    {
        $params = $this->params;

        $existAddrQuery = Address :: find ()
                          ->with(['district'])
                          ->where(['id' => $params['id'], 'user_id' => $this->user->id])
                          ->one();
        if (!isset($existAddrQuery)) { return $this->returnResponse(8125); }
        $existAddrQuery->update_time = new \yii\db\Expression('NOW()');
        if (isset($params['firstName'])) $existAddrQuery->user_name = $params['firstName'];
        if (isset($params['lastName'])) $existAddrQuery->user_surname = $params['lastName'];
        if (isset($params['district_id'])) $existAddrQuery->district_id = $params['district_id'];
        else {
            return $this->returnResponse(8158);
        }
        if (isset($params['street'])) $existAddrQuery->street = $params['street'];
        if (isset($params['bld'])) $existAddrQuery->building = $params['bld'];
        if (isset($params['apt'])) $existAddrQuery->apartment = $params['apt'];
        if (isset($params['region'])) $existAddrQuery->city = $params['region'];
        if (isset($params['city'])) $existAddrQuery->city = $params['city'];
        if (isset($params['zip'])) $existAddrQuery->zipcode = $params['zip'];
        if (isset($params['phone'])) $existAddrQuery->user_phone = $params['phone'];
        if (isset($params['default'])) $existAddrQuery->is_default = $params['default'];
        $existAddrQuery->save();

        return $this->returnResponse(200);
    }

    public function actionRmaddr()
    {
        $params = $this->params;

        $RmAddrQuery = Address :: find ()
                       ->where(['id' => $params['id'], 'user_id' => $this->user->id])
                       ->one();
        if (!isset($RmAddrQuery)) return $this->returnResponse(8131);
        $RmAddrQuery->delete();
        return $this->returnResponse(200);
    }

    public function actionGetdtr()
    {
        $params = $this->params;

        $dtrQuery = District :: find()
                    -> where(['region_id' => $params['region']])
                    -> all();
        $districts = [];
        foreach ($dtrQuery as $value)
        {
            $districts [] = [
                'id' => $value->id,
                'name' => Yii::$app->gFunctions->translate($value, 'name', $this->language),
            ];
        }
        return $this->returnResponse(200, ['list' => $districts]);
    }

    public function actionGetregion()
    {
        $displayRegion = [];
        $responseBody = [];
        $regionCityQuery = Region :: find()->all();
// region cycle
        foreach ($regionCityQuery as $region)
        {
// city cycle
            $displayRegion [] = [
                'id' => $region->id,
                'name' => Yii::$app->gFunctions->translate($region, 'name', $this->language),
                'min_cart_price' => $region->min_delivery_price,
                'delivery_price' => $region->delivery_price
            ];
        }

        return $this->returnResponse(200, ['list' => $displayRegion]);
    }
}
