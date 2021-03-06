<?php

namespace frontend\modules\manager;

use Yii;
/**
 * api module definition class
 */
class Manager extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\api\controllers';

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
					'errorAction' => '/manager/main/error',
				]
			],
		]);

		$handler = $this->get('errorHandler');
		Yii::$app->set('errorHandler', $handler);
		$handler->register();
    }
}
