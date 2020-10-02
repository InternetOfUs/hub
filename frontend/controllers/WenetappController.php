<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\WenetApp;
use frontend\models\AppDeveloper;
use frontend\models\AppUser;
use frontend\components\AppConnector;

/**
 * Wenetapp controller
 */
class WenetappController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index', 'details', 'json-details', 'developer-list', 'user-list',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'json-details', 'developer-list', 'user-list',
                        ],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => [
                            'index', 'details'
                        ],
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

    public function actionJsonDetails($appId) {
        $app = WenetApp::find()->where(['id' => $appId])->one();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($app) {
            return $app->toRepr();
        } else {
            Yii::$app->response->statusCode = 404;
            return new \stdClass();
        }
    }

    public function actionDeveloperList($appId) {
        $app = WenetApp::find()->where(['id' => $appId])->one();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($app) {
            $developers = AppDeveloper::find()->where(['app_id' => $appId])->all();
            return array_map(function($d) { return ''.$d->user_id; }, $developers);
        } else {
            Yii::$app->response->statusCode = 404;
            return new \stdClass();
        }
    }

    public function actionUserList($appId) {
        $app = WenetApp::find()->where(['id' => $appId])->one();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($app) {
            $users = AppUser::find()->where(['app_id' => $appId])->all();
            return array_map(function($d) { return ''.$d->user_id; }, $users);
        } else {
            Yii::$app->response->statusCode = 404;
            return new \stdClass();
        }
    }

    public function actionIndex($platforms=null, $tags=null) {
        // TODO remove platforms?
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
			return $this->render('details', array(
                'app' => $app
            ));
		}
	}

}
