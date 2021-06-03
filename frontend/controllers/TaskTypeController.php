<?php
namespace frontend\controllers;

use Yii;
// use yii\web\Controller;
// use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
// use frontend\models\WenetApp;
// use common\models\User;
use frontend\models\TaskType;
// use yii\helpers\Json;

/**
 * Developer controller
 */
class TasktypeController extends BaseController {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index',
                    // 'create', 'update', 'details',
                    // 'developers', 'delete-developer',
                    // 'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            // 'create', 'update', 'details',
                            // 'developers', 'delete-developer',
                            // 'delete',
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
        $bdTaskTypes = TaskType::find()->where(['creator_id' => Yii::$app->user->id])->orWhere(['public' => TaskType::PUBLIC_TASK_TYPE])->all();

        $provider = new ArrayDataProvider([
            'allModels' => $bdTaskTypes,
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

    // public function actionDetails($id) {
	// 	$app = WenetApp::find()->where(["id" => $id])->one();
    //
    //     if(!$app || $app->status == WenetApp::STATUS_DELETED){
    //         throw new NotFoundHttpException('The specified app cannot be found.');
	// 	} else {
	// 		return $this->render('details', array(
    //             'app' => $app
    //         ));
	// 	}
    //
    //     return $this->render('details', array());
    // }
    //
    // public function actionDevelopers($id){
	// 	$app = WenetApp::find()->where(["id" => $id])->one();
    //     $appDeveloper = new AppDeveloper;
    //
    //     $provider = new ArrayDataProvider([
    //         'allModels' => AppDeveloper::find()->where(['app_id' => $id])->all(),
    //         'pagination' => [
    //             'pageSize' => 15,
    //         ],
    //         'sort' => [
    //             'attributes' => [],
    //         ],
    //     ]);
    //
    //     if ($appDeveloper->load(Yii::$app->request->post())) {
    //         foreach ($appDeveloper->user_id as $user) {
    //             if(!AppDeveloper::find()->where(["user_id" => $user, "app_id" => $id])->one()){
    //                 $model = new AppDeveloper;
    //                 $model->app_id = $id;
    //                 $model->user_id = $user;
    //                 $model->save();
    //             }
    //         }
    //         Yii::$app->session->setFlash('success', Yii::t('app', 'Developers successfully added.'));
    //         return $this->redirect(['developers', 'id' => $id]);
    //     }
    //
    //     return $this->render('developers', array(
    //         'provider' => $provider,
    //         'app' => $app,
    //         'appDeveloper' => $appDeveloper
	// 	));
    // }
    //
    // public function actionDeveloperList($app_id, $q = null, $id = null) {
    //     \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //     $out = ['results' => ['id' => '', 'username' => '', 'email' => '']];
    //
    //     if (!is_null($q)) {
    //         $sql = 'SELECT * FROM user WHERE developer = :developer AND ( user.email LIKE :emailQuery OR user.username LIKE :usernameQuery ) AND user.id NOT IN (SELECT user.id FROM user INNER JOIN app_developer ON app_developer.user_id = user.id WHERE app_id = :app_id )';
    //         $developers = User::findBySql($sql, [':emailQuery' => '%'.$q.'%', ':usernameQuery' => '%'.$q.'%', ':developer' => User::DEVELOPER, 'app_id' => $app_id])->all();
    //         $out['results'] = array_map(function($e){return ['email' => $e->email, 'id' => $e->id, 'username' => $e->username];}, $developers);
    //     }
    //     return $out;
    // }
    //
    // public function actionDeleteDeveloper($app_id, $user_id) {
    //     $model = AppDeveloper::find()->where(['app_id' => $app_id, 'user_id' => $user_id])->one();
    //     if ($model->delete()) {
    //         Yii::$app->session->setFlash('success', Yii::t('app', 'Developer successfully deleted.'));
    //     } else {
    //         Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete developer.'));
    //     }
    //     return $this->redirect(['developers', 'id' => $app_id]);
    // }
    //
    // public function actionCreate(){
    //     $model = new WenetApp;
    //     $model->owner_id = Yii::$app->user->id;
    //     $model->conversational_connector = WenetApp::NOT_ACTIVE_CONNECTOR;
    //     $model->data_connector = WenetApp::NOT_ACTIVE_CONNECTOR;
    //
    //
    //     if ($model->load(Yii::$app->request->post())) {
    //         if ($model->create()) {
    //             $appDeveloper = new AppDeveloper;
    //             $appDeveloper->app_id = $model->id;
    //             $appDeveloper->user_id = $model->owner_id;
    //             $appDeveloper->save();
    //
    //             return $this->redirect(['oauth/create-oauth', 'id' => $model->id, 'skip' => true]);
    //         } else {
    //             Yii::$app->session->setFlash('error', Yii::t('app', 'Could not create app.'));
    //         }
    //     }
    //
    //     return $this->render('create', array(
    //         'model' => $model
    //     ));
    // }
    //
    // public function actionUpdate($id) {
    //     $app = WenetApp::find()->where(["id" => $id])->one();
    //     if ($app->load(Yii::$app->request->post())) {
    //         if ($app->save()) {
    //             return $this->redirect(['details', "id" => $id]);
    //         }
    //     }
    //     return $this->render('update', ['app' => $app ]);
    // }
    //
    // public function actionDelete($id) {
    //     $model = WenetApp::find()->where(["id" => $id])->one();
    //     $model->status = WenetApp::STATUS_DELETED;
    //     if ($model->save()) {
    //         Yii::$app->session->setFlash('success', Yii::t('app', 'App successfully deleted.'));
    //     } else {
    //         Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete app.'));
    //     }
    //     return $this->redirect(['index']);
    // }

}
