<?php

namespace frontend\modules\cashbox;

use Yii;
/**
 * api module definition class
 */
class Cashbox extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\cashbox\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
		
		Yii::configure($this, [
			'components' => [
				'errorHandler' => [
					'class' => \yii\web\ErrorHandler::className(),
					'errorAction' => '/cashbox/main/error',
				]
			],
		]);

		$handler = $this->get('errorHandler');
		Yii::$app->set('errorHandler', $handler);
		$handler->register();
    }
}
