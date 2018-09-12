<?php

namespace frontend\modules\api\models;

use Yii;
use common\models\ErrorMessage as BaseErrorMessage;

class ErrorMessage extends BaseErrorMessage
{
  public static function getDb() {
       return Yii::$app->get('db_spg'); // second database
  }
}
