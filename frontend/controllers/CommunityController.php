<?php
namespace frontend\controllers;

use Yii;
// use yii\helpers\Json;
use yii\web\Controller;
// use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
// use yii\data\ArrayDataProvider;
// use common\models\User;
// use frontend\components\AnalyticsManager;
// use frontend\models\WenetApp;
// use frontend\models\AppDeveloper;
// use frontend\models\BadgeDescriptor;
// use frontend\models\AppBadge;
// use frontend\models\analytics\AnalyticDescription;

/**
 * Developer controller
 */
class CommunityController extends BaseController {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'update'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update'
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

    public function actionUpdate($id, $appId) {

        return $this->render('/community/update', array(
        ));
    }

}
