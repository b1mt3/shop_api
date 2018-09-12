<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\api\models\Request */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Запросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'create_time',
            'request',
			[
				'attribute'=>'request_data',
				'value'=>  '<pre>'.print_r(json_decode($model->request_data), true).'</pre>',
				'format'=>'html'
			],
            'response',
			[
				'attribute'=>'response_data',
				'value'=>  '<pre>'.print_r(json_decode($model->response_data), true).'</pre>',
				'format'=>'html'
			],
            'user',
            'version',
			[
				'attribute'=>'header_data',
				'value'=> '<pre>'.print_r(json_decode($model->header_data), true).'</pre>' ,
				'format'=>'html'
			],
        ],
    ]) ?>

</div>
