<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\ShopInfo as BaseShopInfo;

class ShopInfo extends BaseShopInfo
{
  private function CheckImagePath($imgLabel)
  {
    $path = '';
    if($imgLabel && file_exists(Yii::getAlias('@images').'/shop/'.$imgLabel)) {
      $path = \yii\helpers\Url::home(true).'images/shop/'.$imgLabel;
    }
    else {
      $path = \yii\helpers\Url::home(true).'img/no_photo.png';
    }
    return $path;
  }


  public function GetImagePath()
  {
    $shopImages = [];

    $shopImages[] = $this->CheckImagePath($this->img1);
    $shopImages[] = $this->CheckImagePath($this->img2);
    $shopImages[] = $this->CheckImagePath($this->img3);

    return $shopImages;
  }
}
