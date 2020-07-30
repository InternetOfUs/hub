<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\AuthorisationForm;

class OauthController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'authorise'],
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['authorise'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // 'logout' => ['post'],
                ],
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

    public function actionLogin($client_id, $scope=null) {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['oauth/authorise', 'client_id' => $client_id, 'scope' => $scope]);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['oauth/authorise', 'client_id' => $client_id, 'scope' => $scope]);
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionAuthorise($client_id, $scope=null) {
        $model = new AuthorisationForm();
        $model->appId = $client_id;
        if (isset($scope)) {
            $model->withSpecifiedScope(explode(',', $scope));
        } else {
            $model->withCompleteScope();
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->allowedScope = ['phone'];  # TODO remove, only here for testing
            $redirectUri = Yii::$app->kongConnector->createAuthenticatedUser($model->appId, $model->userId, implode(',', $model->allowedScope));
            if (isset($redirectUri)) {
                $this->redirect($redirectUri);
            } else {
                # TODO show error page - something went wrong during authorisation
                print('error');
                exit();
            }
        }

        return $this->render('authorise', [
            'model' => $model,
        ]);
    }
}
