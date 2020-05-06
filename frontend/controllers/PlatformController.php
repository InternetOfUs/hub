<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\AppPlatformTelegram;
use frontend\models\UserAccountTelegram;
use frontend\models\WenetApp;
use frontend\models\AppPlatform;
use yii\db\Query;

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
                'only' => ['create-telegram', 'delete-telegram'],
                'rules' => [
                    [
                        'actions' => ['create-telegram', 'delete-telegram'],
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
        $model->status = AppPlatform::STATUS_ACTIVE;
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

    public function actionDeleteTelegram($id) {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $transactionOk = true;
        $appToDevMode = false;

        $model = AppPlatformTelegram::find()->where(["id" => $id])->one();
        $model->status = AppPlatform::STATUS_DELETED;

        $users = UserAccountTelegram::find()->where(['app_id' => $model->app_id, 'active' => UserAccountTelegram::ACTIVE ])->all();

        $app = WenetApp::find()->where(['id' => $model->app_id])->one();
        $appPlatforms = $app->platforms();
        if ($app->status == WenetApp::STATUS_ACTIVE && count($appPlatforms) == 1) {
            $app->status = WenetApp::STATUS_NOT_ACTIVE;
            $appToDevMode = true;
        }

        if (!$model->save()) {
            $transactionOk = false;
            // TODO
            // Yii::error('Could not create new Wenet APP', '');
        } else {
            $usersOk = true;
            foreach ($users as $user) {
                $user->active = UserAccountTelegram::NOT_ACTIVE;
                if(!$user->save()){
                    $usersOk = false;
                }
                if($usersOk == false){
                    $transactionOk = false;
                    break;
                }
            }

            if($appToDevMode){
                if(!$app->save()){
                    $transactionOk = false;
                    // TODO
                    // Yii::error('Could not create new Wenet APP', '');
                }
            }
        }
        
        if ($transactionOk) {
            if($appToDevMode){
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Because there are no platforms available for this app, the app has been automatically setted as "In development" mode.'));
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Platform successfully deleted.'));
            $transaction->commit();
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete platform.'));
            $transaction->rollback();
        }
        return $this->redirect(['/wenetapp/details-developer', 'id' => $model->app_id]);
    }

}
