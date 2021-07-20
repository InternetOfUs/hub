<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use common\models\User;
use frontend\components\AnalyticsManager;
use frontend\models\WenetApp;
use frontend\models\AppDeveloper;
use frontend\models\BadgeDescriptor;
use frontend\models\analytics\AnalyticDescription;

/**
 * Developer controller
 */
class DeveloperController extends BaseController {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index', 'create', 'update', 'details',
                    'developers', 'delete-developer',
                    'delete',
                    'conversational-connector',
                    'disable-conversational-connector', 'enable-conversational-connector',
                    'disable-data-connector', 'enable-data-connector'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'update', 'details',
                            'developers', 'delete-developer',
                            'delete',
                            'conversational-connector',
                            'disable-conversational-connector', 'enable-conversational-connector',
                            'disable-data-connector', 'enable-data-connector'
                        ],
                        'allow' => !Yii::$app->user->isGuest && Yii::$app->user->getIdentity()->isDeveloper(),
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

    public function actionIndex(){
        $appDevelopers = AppDeveloper::find()->where(['user_id' => Yii::$app->user->id])->all();
        $userApps = [];
        foreach ($appDevelopers as $appDeveloper) {
            $app = $appDeveloper->app;
            if (\in_array($app->status, [WenetApp::STATUS_NOT_ACTIVE, WenetApp::STATUS_ACTIVE])) {
                $userApps[] = $app;
            }
        }

        $provider = new ArrayDataProvider([
            'allModels' => $userApps,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        return $this->render('index', array(
            'provider' => $provider
		));
    }

    public function actionDetails($id, $filter='7d', $tab='settings') {
		$app = WenetApp::find()->where(["id" => $id])->one();

        // $badge = new BadgeDescriptor(null, 'name', 'description', 'ask4help', 12, 'https://wenetbadgesimages.s3.amazonaws.com/curious_level_1.png', '1', null);
        // $result = Yii::$app->incentiveServer->createBadgeDescriptor($badge);
        //
        // print_r($result);
        //
        // $result = Yii::$app->incentiveServer->getBadgeDescriptor($result->id);
        //
        // print_r($result);
        // exit();

        if(!$app || $app->status == WenetApp::STATUS_DELETED){
            throw new NotFoundHttpException('The specified app cannot be found.');
		} else {
            if($app->isDeveloper()){
                $appDevelopers = AppDeveloper::find()->where(["app_id" => $id])->all();

                $analyticManager = new AnalyticsManager;
                $analyticManager->createAnalyticsIfMissing($app->id);
                $statsData = $analyticManager->prepareData($app->id, '1d');

                return $this->render('details', array(
                    'app' => $app,
                    'statsData' => $statsData,
                    'tab' => $tab,
                    'filter' => $filter,
                    'appDevelopers' => $appDevelopers,
                ));
            } else {
                return $this->render('/site/error', array(
                    'message' => Yii::t('common', 'You are not authorised to perform this action.'),
                    'name' => Yii::t('common', 'Error')
                ));
            }
		}

        return $this->render('details', array());
    }

    public function actionDevelopers($id){
		$app = WenetApp::find()->where(["id" => $id])->one();
        $appDeveloper = new AppDeveloper;

        $provider = new ArrayDataProvider([
            'allModels' => AppDeveloper::find()->where(['app_id' => $id])->all(),
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        if ($appDeveloper->load(Yii::$app->request->post())) {
            foreach ($appDeveloper->user_id as $user) {
                if(!AppDeveloper::find()->where(["user_id" => $user, "app_id" => $id])->one()){
                    $model = new AppDeveloper;
                    $model->app_id = $id;
                    $model->user_id = $user;
                    $model->save();
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Developers successfully added.'));
            return $this->redirect(['developers', 'id' => $id]);
        }

        if($app->isOwner(Yii::$app->user->id)){
            return $this->render('developers', array(
                'provider' => $provider,
                'app' => $app,
                'appDeveloper' => $appDeveloper
    		));
        } else {
            return $this->render('/site/error', array(
                'message' => Yii::t('common', 'You are not authorised to perform this action.'),
                'name' => Yii::t('common', 'Error')
            ));
        }
    }

    public function actionDeveloperList($app_id, $q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => []];

        if (!is_null($q)) {
            $sql = 'SELECT * FROM user WHERE developer = :developer AND ( user.email LIKE :emailQuery OR user.username LIKE :usernameQuery ) AND user.id NOT IN (SELECT user.id FROM user INNER JOIN app_developer ON app_developer.user_id = user.id WHERE app_id = :app_id )';
            $developers = User::findBySql($sql, [':emailQuery' => '%'.$q.'%', ':usernameQuery' => '%'.$q.'%', ':developer' => User::DEVELOPER, 'app_id' => $app_id])->all();
            $out['results'] = array_map(function($e){return ['email' => $e->email, 'id' => $e->id, 'username' => $e->username];}, $developers);
        }
        return $out;
    }

    public function actionDeleteDeveloper($app_id, $user_id) {
        $model = AppDeveloper::find()->where(['app_id' => $app_id, 'user_id' => $user_id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Developer successfully deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete developer.'));
        }
        return $this->redirect(['developers', 'id' => $app_id]);
    }

    public function actionCreate(){
        $model = new WenetApp;
        $model->owner_id = Yii::$app->user->id;
        $model->conversational_connector = WenetApp::NOT_ACTIVE_CONNECTOR;
        $model->data_connector = WenetApp::NOT_ACTIVE_CONNECTOR;


        if ($model->load(Yii::$app->request->post())) {
            if ($model->create()) {
                $appDeveloper = new AppDeveloper;
                $appDeveloper->app_id = $model->id;
                $appDeveloper->user_id = $model->owner_id;
                $appDeveloper->save();

                return $this->redirect(['oauth/create-oauth', 'id' => $model->id, 'skip' => true]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not create app.'));
            }
        }

        return $this->render('create', array(
            'model' => $model
        ));
    }

    public function actionUpdate($id) {
        $app = WenetApp::find()->where(["id" => $id])->one();
        if ($app->load(Yii::$app->request->post())) {
            if ($app->save()) {
                return $this->redirect(['details', "id" => $id]);
            }
        }
        if($app->isDeveloper()){
            return $this->render('update', ['app' => $app ]);
        } else {
            return $this->render('/site/error', array(
                'message' => Yii::t('common', 'You are not authorised to perform this action.'),
                'name' => Yii::t('common', 'Error')
            ));
        }
    }

    public function actionDelete($id) {
        $model = WenetApp::find()->where(["id" => $id])->one();

        if($model->status != WenetApp::STATUS_DELETED){
            if($model->isOwner(Yii::$app->user->id)){
                $model->status = WenetApp::STATUS_DELETED;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'App successfully deleted.'));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete app.'));
                }
                return $this->redirect(['index']);
            } else {
                return $this->render('/site/error', array(
                    'message' => Yii::t('common', 'You are not authorised to perform this action.'),
                    'name' => Yii::t('common', 'Error')
                ));
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'The app you are trying to delete does not exist.'));
            return $this->redirect(['index']);
        }

    }

    public function actionConversationalConnector($id){
        //  TODO check authorisation to perform the action
        $app = WenetApp::find()->where(["id" => $id])->one();
        $app->scenario = WenetApp::SCENARIO_CONVERSATIONAL;

        if ($app->load(Yii::$app->request->post())) {
            $app->conversational_connector = WenetApp::ACTIVE_CONNECTOR;

            if ($app->save()) {
                return $this->redirect(['/developer/details', "id" => $id]);
            }
        }
        return $this->render('conversational_connector', [
            'app' => $app
        ]);
    }

    public function actionDisableConversationalConnector($id) {
        //  TODO check authorisation to perform the action
        $app = WenetApp::find()->where(["id" => $id])->one();
        $app->conversational_connector = WenetApp::NOT_ACTIVE_CONNECTOR;

        // TODO check fattibilità

        $message = Yii::t('app', 'Connector succesfully disabled.');
        $alert_type = 'success';
        if($app->data_connector == WenetApp::NOT_ACTIVE_CONNECTOR && $app->status == WenetApp::STATUS_ACTIVE){
            $app->status = WenetApp::STATUS_NOT_ACTIVE;
            $message = Yii::t('app', 'Because a connector is required for the app to be live, the app has been automatically set as "In development" mode.');
            $alert_type = 'warning';
        }

        if ($app->save()) {
            return JSON::encode([
                'message' => $message,
                'alert_type' => $alert_type
            ]);
        } else {
            return JSON::encode([
                'message' => Yii::t('app', 'Error, please retry later.'),
                'alert_type' => 'danger'
            ]);
        }
    }

    public function actionEnableConversationalConnector($id) {
        //  TODO check authorisation to perform the action
        $app = WenetApp::find()->where(["id" => $id])->one();
        $app->conversational_connector = WenetApp::ACTIVE_CONNECTOR;

        // TODO check fattibilità

        if ($app->save()) {
            return JSON::encode([
                'message' => Yii::t('app', 'Connector succesfully enabled.'),
                'alert_type' => 'success'
            ]);
        } else {
            return JSON::encode([
                'message' => Yii::t('app', 'Error, please retry later.'),
                'alert_type' => 'danger'
            ]);
        }
    }

    public function actionDisableDataConnector($id) {
        //  TODO check authorisation to perform the action
        $app = WenetApp::find()->where(["id" => $id])->one();
        $app->data_connector = WenetApp::NOT_ACTIVE_CONNECTOR;

        $message = Yii::t('app', 'Connector succesfully disabled.');
        $alert_type = 'success';
        if($app->conversational_connector == WenetApp::NOT_ACTIVE_CONNECTOR && $app->status == WenetApp::STATUS_ACTIVE){
            $app->status = WenetApp::STATUS_NOT_ACTIVE;
            $message = Yii::t('app', 'Because a connector is required for the app to be live, the app has been automatically set as "In development" mode.');
            $alert_type = 'warning';
        }

        if ($app->save()) {
            return JSON::encode([
                'message' => $message,
                'alert_type' => $alert_type
            ]);
        } else {
            return JSON::encode([
                'message' => Yii::t('app', 'Error, please retry later.'),
                'alert_type' => 'danger'
            ]);
        }
    }

    public function actionEnableDataConnector($id) {
        //  TODO check authorisation to perform the action
        $app = WenetApp::find()->where(["id" => $id])->one();
        $app->data_connector = WenetApp::ACTIVE_CONNECTOR;

        if ($app->save()) {
            return JSON::encode([
                'message' => Yii::t('app', 'Connector succesfully enabled.'),
                'alert_type' => 'success'
            ]);
        } else {
            return JSON::encode([
                'message' => Yii::t('app', 'Error, please retry later.'),
                'alert_type' => 'danger'
            ]);
        }
    }

}
