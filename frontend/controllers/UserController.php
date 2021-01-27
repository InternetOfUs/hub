<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\UserException;
use common\models\LoginForm;
use common\models\User;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;

/**
 * User controller
 */
class UserController extends BaseController {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                        'login', 'logout', 'signup',
                        'account', 'profile', 'change-password',
                        'user-apps',
                        'request-password-reset', 'reset-password', 'resend-verification-email', 'verify-email'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'login', 'signup',
                            'request-password-reset', 'reset-password', 'resend-verification-email', 'verify-email'
                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'logout',
                            'account', 'profile', 'change-password',
                            'user-apps'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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

    public function actionAccount() {
        $params = ['errorGettingUserProfile' => false];
        if(Yii::$app->request->get('becomeDev') == 1){

            $model = Yii::$app->serviceApi->getUserProfile(Yii::$app->user->id);

            if (!$model) {
                $params['errorGettingUserProfile'] = true;
            } else {
                $params['model'] = $model;

                if($model->first_name != null && $model->last_name != null && $model->birthdate != null){
                    $user = User::find()->where(["id" => Yii::$app->user->id])->one();
                    $user->developer = User::DEVELOPER;

                    if ($user->save()) {
                        Yii::$app->session->setFlash('success',  Yii::t('user', 'Now you are a developer.'));
                        return $this->redirect(['account', $params]);
                    } else {
                        Yii::warning('Could not save user as developer');
                        Yii::$app->session->setFlash('error', Yii::t('user', 'There is a problem setting your account as developer. Please retry later.'));
                        return $this->redirect(['account', $params]);
                    }
                } else {
                    Yii::warning('Incomplete profile');
                    $content = Yii::t('user', 'Your profile is incomplete. Complete it ');
                    $content .= "<a style='color:#a94442; text-decoration:underline;' href='".Url::to(['/user/profile'])."'>".Yii::t('user', 'here')."</a>";
                    Yii::$app->session->setFlash('error', $content);
                    return $this->redirect(['account', $params]);
                }
            }
        }
        return $this->render('account', $params);
    }

    public function actionProfile(){
        $model = Yii::$app->serviceApi->getUserProfile(Yii::$app->user->id);
        $params = [];

        if (!$model) {
            $params = ['errorGettingUserProfile' => true];
        } else {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if (Yii::$app->serviceApi->updateUserProfile($model)) {
                    Yii::$app->session->setFlash('success', Yii::t('profile', 'Profile successfully updated.'));
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('profile', 'Could not update profile.'));
                }
            }
            $params = [
                'model' => $model,
                'errorGettingUserProfile' => false
            ];

        }
        return $this->render('profile', $params);

    }

    public function actionChangePassword(){
        $model = new SignupForm();
        $model->scenario = SignupForm::SCENARIO_UPDATE_PASSWORD;
        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', Yii::t('signup', 'Password successfully changed.'));
        }

        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }

    public function actionUserApps() {
        return $this->render('my_apps', array());
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            if(Yii::$app->user->getIdentity()->isDeveloper()){
                return $this->redirect(['developer/index']);
            } else {
                return $this->redirect(['wenetapp/index']);
            }
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(Yii::$app->user->getIdentity()->isDeveloper()){
                return $this->redirect(['developer/index']);
            } else {
                return $this->redirect(['wenetapp/index']);
            }
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        $model->scenario = SignupForm::SCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', Yii::t('signup', 'Thank you for registration. Please check your inbox for verification email.'));
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success',  Yii::t('reset', 'Check your email for further instructions.'));
                return $this->redirect(['login']);
            } else {
                Yii::$app->session->setFlash('error',  Yii::t('reset', 'Sorry, we are unable to reset password for the provided email address.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('reset', 'New password saved.'));

            return $this->redirect(['login']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail() {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('signup', 'Check your email for further instructions.'));
                return $this->redirect(['login']);
            }
            Yii::$app->session->setFlash('error', Yii::t('signup', 'Sorry, we are unable to resend verification email for the provided email address.'));
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token) {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', Yii::t('signup', 'Your email has been confirmed!'));
                return $this->redirect(['profile']);
            }
        }

        Yii::$app->session->setFlash('error', Yii::t('signup', 'Sorry, we are unable to verify your account with provided token.'));
        return $this->goHome();
    }

}
