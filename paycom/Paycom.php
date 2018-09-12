<?php

namespace frontend\modules\paycom;


use Yii;

/**
 * paycom module definition class
 */
class Paycom extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\paycom\controllers';

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
					'errorAction' => '/paycom/main/error',
				]
			],
		]);

		$handler = $this->get('errorHandler');
		Yii::$app->set('errorHandler', $handler);
		$handler->register();
        // custom initialization code goes here
    }
}
