<?php

namespace frontend\modules\api\controllers;

use Yii;
use frontend\modules\api\models\ShopInfo;
use frontend\modules\api\models\PageData;
use frontend\modules\api\models\News;

class InfoController extends MainController
{
    public function actionShop()
    {
        $info = [];
        $shop = ShopInfo :: find()->one();
        $delivery = PageData :: find()
                 ->with(['page'])
                 ->where(['alias' => 'deliveryContent'])
                 ->one();
        $payment = PageData :: find()
                ->with(['page'])
                ->where(['alias' => 'paymentContent'])
                ->one();

        $userAgreement = PageData :: find()
                      -> with(['page'])
                      -> where(['alias' => 'ofertaContent'])
                      -> one();

        $returnStatement =  PageData :: find()
                      -> with(['page'])
                      -> where(['alias' => 'returnContent'])
                      -> one();

        $info = [
            'id' => $shop->id,
            'address' => Yii::$app->gFunctions->translate($shop, 'address', $this->language),
            'schedule' => $shop->schedule,
            'telephone' => $shop->telephone,
            'delivery_info' => Yii::$app->urlManager->createAbsoluteUrl(['/'.$delivery->page->url]),
            'payment_info' => Yii::$app->urlManager->createAbsoluteUrl(['/'.$payment->page->url]),
            'userAgreement' => Yii::$app->urlManager->createAbsoluteUrl(['/site/'.$userAgreement->page->url]),
            'returnAgreement' => Yii::$app->urlManager->createAbsoluteUrl(['/'.$returnStatement->page->url]),
            'support' => $shop->support,
            'longitude' => $shop->longitude,
            'latitude' => $shop->latitude,
            'image' => $shop->GetImagePath()
        ];
        return $this->returnResponse(200, ['shop_info' => $info]);
    }

    public function actionSizetable()
    {
        $info = [];

        $table = PageData :: find()
                 ->with(['page'])
                 ->where(['alias' => 'sizetableContent'])
                 ->one();
        $info = [
          'title' => Yii::$app->gFunctions->translate($table->page, 'title', $this->language),
          'url' => Yii::$app->urlManager->createAbsoluteUrl(['site/sizes', 'webview'=>1])
        ];
        return $this->returnResponse(200, ['size_info' => $info]);
    }
}
