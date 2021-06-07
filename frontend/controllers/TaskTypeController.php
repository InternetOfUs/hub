<?php
namespace frontend\controllers;

use Yii;
// use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use common\models\User;
use frontend\models\TaskType;
use frontend\models\TaskTypeDeveloper;
use yii\helpers\Json;

/**
 * Task type controller
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
                    'index', 'create', 'details', 'update', 'public',
                    'developers', 'delete-developer',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'details', 'update', 'public',
                            'developers', 'delete-developer',
                            'delete',
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

    public function actionCreate(){
        $model = new TaskType;
        $model->creator_id = Yii::$app->user->id;
        $model->public = TaskType::PRIVATE_TASK_TYPE;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $appLogicDeveloper = new TaskTypeDeveloper;
                $appLogicDeveloper->task_type_id = $model->id;
                $appLogicDeveloper->user_id = $model->creator_id;
                $appLogicDeveloper->save();

                return $this->redirect(['details', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('tasktype', 'Could not create new app logic.'));
            }
        }

        return $this->render('create', array(
            'model' => $model
        ));
    }

    public function actionDetails($id) {
		$taskType = TaskType::find()->where(["id" => $id])->one();
        $tasktypeDevelopers = TaskTypeDeveloper::find()->where(["task_type_id" => $id])->all();

        if(!$taskType){
            throw new NotFoundHttpException('The specified app logic cannot be found.');
		} else {
			return $this->render('details', array(
                'taskType' => $taskType,
                'tasktypeDevelopers' => $tasktypeDevelopers
            ));
		}
    }

    public function actionPublic($id) {
        // TODO controllo che questa action si possa fare
    }

    public function actionDevelopers($id){

        // TODO controllo che questa action si possa fare

		$tasktype = TaskType::find()->where(["id" => $id])->one();
        $tasktypeDeveloper = new TaskTypeDeveloper;

        $provider = new ArrayDataProvider([
            'allModels' => TaskTypeDeveloper::find()->where(['task_type_id' => $id])->all(),
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        if ($tasktypeDeveloper->load(Yii::$app->request->post())) {
            foreach ($tasktypeDeveloper->user_id as $user) {
                if(!TaskTypeDeveloper::find()->where(["user_id" => $user, "task_type_id" => $id])->one()){
                    $model = new TaskTypeDeveloper;
                    $model->task_type_id = $id;
                    $model->user_id = $user;
                    $model->save();
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Developers successfully added.'));
            return $this->redirect(['developers', 'id' => $id]);
        }

        return $this->render('developers', array(
            'provider' => $provider,
            'model' => $tasktype,
            'tasktypeDeveloper' => $tasktypeDeveloper
		));
    }

    public function actionDeveloperList($task_type_id, $q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'username' => '', 'email' => '']];

        if (!is_null($q)) {
            $sql = 'SELECT * FROM user WHERE developer = :developer AND ( user.email LIKE :emailQuery OR user.username LIKE :usernameQuery ) AND user.id NOT IN (SELECT user.id FROM user INNER JOIN task_type_developer ON task_type_developer.user_id = user.id WHERE task_type_id = :task_type_id )';
            $developers = User::findBySql($sql, [':emailQuery' => '%'.$q.'%', ':usernameQuery' => '%'.$q.'%', ':developer' => User::DEVELOPER, 'task_type_id' => $task_type_id])->all();
            $out['results'] = array_map(function($e){return ['email' => $e->email, 'id' => $e->id, 'username' => $e->username];}, $developers);
        }
        return $out;
    }

    public function actionDeleteDeveloper($task_type_id, $user_id) {

        // TODO controllo che questa action si possa fare

        $model = TaskTypeDeveloper::find()->where(['task_type_id' => $task_type_id, 'user_id' => $user_id])->one();
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Developer successfully deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete developer.'));
        }
        return $this->redirect(['developers', 'id' => $task_type_id]);
    }

    public function actionUpdate($id) {

        // TODO controllo che questa action si possa fare

        $taskType = TaskType::find()->where(["id" => $id])->one();
        if ($taskType->load(Yii::$app->request->post())) {
            if ($taskType->save()) {
                return $this->redirect(['details', "id" => $id]);
            }
        }
        return $this->render('update', ['taskType' => $taskType ]);
    }

    public function actionDelete($id) {

        // TODO controllo che questa action si possa fare

        $model = TaskType::find()->where(["id" => $id])->one();

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('tasktype', 'App logic successfully deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('tasktype', 'Could not delete app logic.'));
        }
        return $this->redirect(['index']);
    }

}
