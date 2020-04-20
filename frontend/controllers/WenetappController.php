<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use frontend\models\WenetApp;

/**
 * Site controller
 */
class WenetappController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'details'],
                'rules' => [
                    [
                        'actions' => ['index', 'details'],
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

    public function actionIndex($platforms=null, $tags=null) {
        $activePlatformsList = array();
		$activeTagsList = array();

        if ($platforms != null) {
			$activePlatformsList = explode(';', $platforms);
			$activePlatformsList = array_map(function($e) { return 'platform__'.$e; }, $activePlatformsList);
		}

        if ($tags != null) {
			$activeTagsList = explode(';', $tags);
			$activeTagsList = array_map(function($e) { return 'tag__'.$e; }, $activeTagsList);
		}

        return $this->render('index', array(
			'activePlatformsList' => $activePlatformsList,
			'activeTagsList' => $activeTagsList,
		));
    }

    public function actionDetails($id) {
		$app = WenetApp::find()->where(["id" => $id])->one();

        if(!$app){
            throw new NotFoundHttpException('The specified app cannot be found.');
		} else {
            $telegramPlatforms = AppPlatformTelegram::find()->where(['app_id' => $app->id])->all();
			return $this->render('details', array(
                'app' => $app
            ));
		}
	}


}
