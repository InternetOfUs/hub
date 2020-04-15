<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
// use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class AppController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index', 'details'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [],
            // ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionDetails($id, $preview=FALSE) {
		// $app = Resource::model()->findByPk($id);
        $app = [];
		if(!$app){
            throw new NotFoundHttpException('The specified app cannot be found.');
		// } else if(!$app->active && !$preview){
		// 	$this->render('app/not_active');
		} else {
			$this->render('app/details', array('app' => $app));
		}
	}


}
