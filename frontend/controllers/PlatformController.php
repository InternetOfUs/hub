<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\AppPlatformTelegram;
use frontend\models\AppSocialLogin;
use frontend\models\UserAccountTelegram;
use frontend\models\WenetApp;
use frontend\models\AppPlatform;
use frontend\models\AuthorisationForm;
use yii\db\Query;
use yii\helpers\Json;

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
                'only' => [
                    'create-telegram', 'delete-telegram',
                    'create-social-login', 'update-social-login', 'delete-social-login'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'create-telegram', 'delete-telegram',
                            'create-social-login', 'update-social-login', 'delete-social-login'
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

    public function actionCreateTelegram($id){
        $app = WenetApp::find()->where(["id" => $id])->one();

        $model = new AppPlatformTelegram;
        $model->app_id = $id;
        $model->status = AppPlatform::STATUS_ACTIVE;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['/developer/details', 'id' => $id]);
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
            Yii::error('Could not delete telegram platform', 'wenet.platform');
        } else {
            $usersOk = true;
            foreach ($users as $user) {
                $user->active = UserAccountTelegram::NOT_ACTIVE;
                if(!$user->save()){
                    Yii::error('Could not deactivate telegram account for user ['.$user->id.'] and app ['.$app->id.']', 'wenet.platform');
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
                    Yii::error('Could not put app ['.$app->id.'] in dev mode', 'wenet.platform');
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
        return $this->redirect(['/developer/details', 'id' => $model->app_id]);
    }

    public function actionCreateSocialLogin($id){
        $app = WenetApp::find()->where(["id" => $id])->one();

        $model = new AppSocialLogin;
        $model->app_id = $id;
        $model->status = AppPlatform::STATUS_ACTIVE;
        $model->scenario = AppSocialLogin::SCENARIO_CREATE;

        $post = Yii::$app->request->post();

        if ($model->load($post)) {
            // if($post['AppSocialLogin']['allowedPublicScope'] == "" || $post['AppSocialLogin']['allowedPublicScope'] == null){
            //     $model->allowedPublicScope = [];
            // } else {
            //     $model->allowedPublicScope = $post['AppSocialLogin']['allowedPublicScope'];
            // }
            //
            // if($post['AppSocialLogin']['allowedReadScope'] == "" || $post['AppSocialLogin']['allowedReadScope'] == null){
            //     $model->allowedReadScope = [];
            // } else {
            //     $model->allowedReadScope = $post['AppSocialLogin']['allowedReadScope'];
            // }
            //
            // if($post['AppSocialLogin']['allowedWriteScope'] == "" || $post['AppSocialLogin']['allowedWriteScope'] == null){
            //     $model->allowedWriteScope = [];
            // } else {
            //     $model->allowedWriteScope = $post['AppSocialLogin']['allowedWriteScope'];
            // }
            //
            // $model->scope = [
            //     'scope' => array_merge($model->allowedPublicScope, $model->allowedReadScope, $model->allowedWriteScope)
            // ];

            if (isset(Yii::$app->params['kong.ignore']) && Yii::$app->params['kong.ignore']) {
                $model->oauth_app_id = 'oauthId';
            } else {
                Yii::$app->kongConnector->createConsumer($app->id);
                $model->oauth_app_id = Yii::$app->kongConnector->createOAuthCredentials($app->id, $app->token, $model->callback_url);
            }
            if ($model->save()) {
                return $this->redirect(['/developer/details', 'id' => $id]);
            } else {
                // TODO
                // Yii::error('Could not create new Wenet APP', '');
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not add social login.'));
            }
        }

        return $this->render('create_social_login', array(
            'model' => $model,
            'app' => $app
        ));
    }

    public function actionUpdateSocialLogin($id){
        $model = AppSocialLogin::find()->where(["id" => $id])->one();
        $app = WenetApp::find()->where(["id" => $model->app_id])->one();

        // TODO gestire checkboxes!
        $model->scenario = AppSocialLogin::SCENARIO_UPDATE;

        // foreach ($model->scope['scope'] as $scopeItem) {
        //     if(in_array($scopeItem, array_keys(AuthorisationForm::writeScope()))){
        //         $model->allowedWriteScope[] = $scopeItem;
        //     } else if(in_array($scopeItem, array_keys(AuthorisationForm::readScope()))){
        //         $model->allowedReadScope[] = $scopeItem;
        //     }
        // }

        // print_r($model);
        // exit();


        if ($model->load(Yii::$app->request->post())) {
            // print_r($model);
            // exit();
            if (isset(Yii::$app->params['kong.ignore']) && Yii::$app->params['kong.ignore']) {
                $model->oauth_app_id = 'oauthId';
            } else {
                Yii::$app->kongConnector->deleteOAuthCredentials($app->id, $model->oauth_app_id);
                $model->oauth_app_id = Yii::$app->kongConnector->createOAuthCredentials($app->id, $app->token, $model->callback_url);
            }

            if ($model->save()) {
                return $this->redirect(['/developer/details', "id" => $model->app_id]);
            } else {
                print_r($model);
                exit();
            }
        }
        return $this->render('create_social_login', [
            'model' => $model,
            'app' => $app
        ]);
    }

    public function actionDeleteSocialLogin($id) {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $transactionOk = true;
        $appToDevMode = false;

        $model = AppSocialLogin::find()->where(["id" => $id])->one();
        $model->status = AppPlatform::STATUS_DELETED;

        $app = WenetApp::find()->where(['id' => $model->app_id])->one();
        $appPlatforms = $app->platforms();
        if ($app->status == WenetApp::STATUS_ACTIVE && count($appPlatforms) == 1) {
            $app->status = WenetApp::STATUS_NOT_ACTIVE;
            $appToDevMode = true;
        }

        if (!$model->save()) {
            $transactionOk = false;
            Yii::error('Could not delete social login', 'wenet.platform');
        } else {
            if($appToDevMode){
                if(!$app->save()){
                    $transactionOk = false;
                    Yii::error('Could not put app ['.$app->id.'] in dev mode', 'wenet.platform');
                }
            }
        }

        if ($transactionOk) {
            if($appToDevMode){
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Because there are no platforms available for this app, the app has been automatically setted as "In development" mode.'));
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Social login successfully deleted.'));
            $transaction->commit();

            // TODO includ ein transaction!
            if (isset(Yii::$app->params['kong.ignore']) && Yii::$app->params['kong.ignore']) {
                # nothing to do
            } else {
                Yii::$app->kongConnector->deleteConsumer($model->app_id);
            }

        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete social login.'));
            $transaction->rollback();
        }
        return $this->redirect(['/developer/details', 'id' => $model->app_id]);
    }

}
