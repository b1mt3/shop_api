<?php

namespace frontend\modules\api\controllers;

use Yii;
use frontend\modules\api\models\Request;
use frontend\modules\api\models\RequestSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends DefaultController
{
	
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'home', 'view', 'docs', 'statuses', 'test'],
                        'allow' => true,
                        'roles' => ['developer', '?', '@'],
//                        'roles' => ['developer'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
					
                ],
            ],
        ];
    }
	
	public $layout='main';
	public $defaultAction = 'home';
    

    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Request model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    

    /**
     * Deletes an existing Request model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Request model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionHome(){
		return $this->render('home', [
			
		]);
	}
	
	public function actionDocs(){
		return $this->render('docs', [
			
		]);
	}
	public function actionStatuses(){
		return $this->render('statuses', [
			
		]);
	}
	public function actionTest(){
		
	}
}
