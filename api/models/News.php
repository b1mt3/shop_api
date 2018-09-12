<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\News as BaseNews;

class News extends BaseNews
{
    public function getImagePath() {
        if($this->image && file_exists(Yii::getAlias('@images').'/news/'.$this->image)) {
          return \yii\helpers\Url::home(true).'images/news/'.$this->image;
        } else {
          return \yii\helpers\Url::home(true).'img/no_photo.png';
        }
      }
}
