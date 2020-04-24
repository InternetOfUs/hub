<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\AppPlatformTelegram;
use frontend\models\WenetApp;

/**
 * Platform controller
 */
class PlatformController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create-telegram'],
                'rules' => [
                    [
                        'actions' => ['create-telegram'],
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

    public function actionCreateTelegram($id){
        $app = WenetApp::find()->where(["id" => $id])->one();

        $model = new AppPlatformTelegram;
        $model->app_id = $id;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['/wenetapp/details-developer', 'id' => $id]);
            } else {
                // TODO
                // Yii::error('Could not create new Wenet APP', '');
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not add platform Telegram.'));
            }
        }

        return $this->render('create_telegram', array(
            'model' => $model,
            'app' => $app
        ));
    }

}
