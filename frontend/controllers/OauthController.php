<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\AuthorisationForm;
use frontend\models\SignupForm;
use frontend\models\WenetApp;

class OauthController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'signup', 'authorise'],
                'rules' => [
                    [
                        'actions' => ['login', 'signup'],
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

    private function verifyAppExistance($clientId) {
        if (WenetApp::findOne($client_id) == NULL) {
            # TODO error : provided client id is not valid
            # should render here error page
            exit();
        }
    }

    public function actionLogin($client_id, $scope=null) {
        $this->verifyAppExistance($client_id);

        // Yii::$app->kongConnector->createOAuthCredentials('1', 'secret', 'https://www.google.com');

        $this->layout = "easy.php";
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
                'client_id' => $client_id,
                'scope' => $scope
            ]);
        }
    }

    public function actionSignup($client_id, $scope=null) {
        $this->verifyAppExistance($client_id);

        $this->layout = "easy.php";
        $model = new SignupForm();
        $model->scenario = SignupForm::SCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            // TODO after fixed email send
            // Yii::$app->session->setFlash('success', Yii::t('signup', 'Thank you for registration. Please check your inbox for verification email.'));
            Yii::$app->session->setFlash('success', Yii::t('signup', 'Thank you for registration.'));
            return $this->redirect(['login', 'client_id' => $client_id, 'scope' => $scope]);
        }

        return $this->render('signup', [
            'model' => $model,
            'client_id' => $client_id,
            'scope' => $scope
        ]);
    }

    public function actionAuthorise($client_id, $scope=null) {
        $this->layout = "easy.php";
        $model = new AuthorisationForm();
        $model->appId = $client_id;
        $model->userId = Yii::$app->user->id;
        if (isset($scope)) {
            $model->withSpecifiedScope(explode(',', $scope));
        } else {
            $model->withCompleteScope();
        }

        if ($model->load(Yii::$app->request->post())) {
            if($model->allowedReadScope == "" || $model->allowedReadScope == null){
                $model->allowedReadScope = [];
            }
            if($model->allowedWriteScope == "" || $model->allowedWriteScope == null){
                $model->allowedWriteScope = [];
            }
            $allowedScope = array_merge(array_keys($model->publicScope()), $model->allowedReadScope, $model->allowedWriteScope);

            $redirectUri = Yii::$app->kongConnector->createAuthenticatedUser($model->appId, $model->userId, implode(' ', $allowedScope));
            if (isset($redirectUri)) {
                $this->redirect($redirectUri);
            } else {
                # TODO show error page - something went wrong during authorisation
                print_r('error');
                exit();
            }
        }

        return $this->render('authorise', [
            'model' => $model,
        ]);
    }
}
