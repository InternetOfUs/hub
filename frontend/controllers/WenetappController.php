<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\WenetApp;
use frontend\models\AppPlatformTelegram;
use frontend\models\UserAccountTelegram;
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
                    'index', 'details', 'associate-user', 'disassociate-user',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'details', 'associate-user', 'disassociate-user',
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

    public function actionAssociateUser() {
        $data = Yii::$app->request->post();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($data['platform'] == 'telegram') {
            $account = UserAccountTelegram::find()->where([
                'app_id' => $data['appId'],
                'user_id' => $data['userId'],
                'telegram_id' => $data['platformId'],
            ])->one();

            if (!$account) {
                $account = new UserAccountTelegram();
                $account->user_id = $data['userId'];
                $account->app_id = $data['appId'];
                $account->telegram_id = $data['platformId'];
            }

            $account->active = UserAccountTelegram::ACTIVE;

            if ($account->save()) {
                $connector = new AppConnector();
                $connector->newUserForPlatform($account->app, 'telegram', Yii::$app->user->id);
                return [
                    'message' => 'saved',
                ];
            } else {
                Yii::warning('Could not save new telegram account');
                Yii::$app->response->statusCode = 400;
                return [
                    'message' => Yii::t('app', 'There is a problem with the Telegram login. Please retry later.'),
                ];
            }
        } else {
            Yii::warning('Unsupported platform provided');
            Yii::$app->response->statusCode = 400;
            return [
                'message' => 'unsupported platform',
            ];
        }
    }

    public function actionDisassociateUser() {
        $data = Yii::$app->request->post();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($data['platform'] == 'telegram') {
            $account = UserAccountTelegram::find()->where([
                'app_id' => $data['appId'],
                'user_id' => $data['userId'],
            ])->one();

            $account->active = UserAccountTelegram::NOT_ACTIVE;

            if ($account->save()) {
                return [
                    'message' => 'disabled',
                ];
            } else {
                Yii::warning('Could not disable telegram account');
                Yii::$app->response->statusCode = 400;
                return [
                    'message' => Yii::t('app', 'There is a problem with the Telegram logout. Please retry later.'),
                ];
            }
        } else {
            Yii::warning('Unsupported platform provided');
            Yii::$app->response->statusCode = 400;
            return [
                'message' => 'unsupported platform',
            ];
        }
    }

}
