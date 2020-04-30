<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\Profile;

/**
 * Profile controller
 */
class ProfileController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view', 'update'],
                'rules' => [
                    [
                        'actions' => ['view', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [],
            ],
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

    public function actionView(){
        return $this->render('view', array());
    }

    public function actionUpdate(){
        $model = Yii::$app->serviceApi->getUserProfile(Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->serviceApi->updateUserProfile($model)) {
                
            } else {

            }
        }

        return $this->render('form', array(
            'model' => $model
        ));
    }

}
