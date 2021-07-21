<?php
namespace frontend\controllers;

use Yii;
// use yii\helpers\Json;
// use yii\web\Controller;
// use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
// use yii\data\ArrayDataProvider;
// use common\models\User;
// use frontend\components\AnalyticsManager;
use frontend\models\WenetApp;
// use frontend\models\AppDeveloper;
// use frontend\models\BadgeDescriptor;
use frontend\models\AppBadge;
// use frontend\models\analytics\AnalyticDescription;

/**
 * Badge controller
 */
class BadgeController extends BaseController {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'create', 'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'create', 'delete',
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

    public function actionCreate($appId){
        $app = WenetApp::find()->where(["id" => $appId])->one();
        $transactionLabels = array_keys($app->taskType->details()->transactions);

        $model = new AppBadge;
        $model->creator_id = Yii::$app->user->id;
        $model->taskTypeId = $app->task_type_id;
        $model->app_id = $appId;

        if ($model->load(Yii::$app->request->post())) {
            // print_r(Yii::$app->request->post());
            // print_r($model);
            // exit();
            if ($model->save()) {
                return $this->redirect(['developer/details', 'id' => $appId, 'tab' => 'badges']);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('badge', 'Could not create badge.'));
            }
        }

        return $this->render('create', array(
            'app' => $app,
            'model' => $model,
            'transactionLabels' => $transactionLabels
        ));
    }

    // public function actionDelete($id) {
    //     $model = WenetApp::find()->where(["id" => $id])->one();
    //
    //     if($model->status != WenetApp::STATUS_DELETED){
    //         if($model->isOwner(Yii::$app->user->id)){
    //             $model->status = WenetApp::STATUS_DELETED;
    //             if ($model->save()) {
    //                 Yii::$app->session->setFlash('success', Yii::t('app', 'App successfully deleted.'));
    //             } else {
    //                 Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete app.'));
    //             }
    //             return $this->redirect(['index']);
    //         } else {
    //             return $this->render('/site/error', array(
    //                 'message' => Yii::t('common', 'You are not authorised to perform this action.'),
    //                 'name' => Yii::t('common', 'Error')
    //             ));
    //         }
    //     } else {
    //         Yii::$app->session->setFlash('error', Yii::t('app', 'The app you are trying to delete does not exist.'));
    //         return $this->redirect(['index']);
    //     }
    //
    // }

}
