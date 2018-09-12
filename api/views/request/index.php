<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\api\models\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            [
					'attribute'=>'id',
					'headerOptions'=>['style'=>'width: 100px'],
				],
            'create_time',
            'request',
//            'request_data:ntext',
            'response',
            'user',
            'version',
            // 'response_data:ntext',

            ['class' => 'yii\grid\ActionColumn',
				'template' => '{view}',],
        ],
    ]); ?>
</div>
