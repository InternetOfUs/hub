<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\WenetApp;
use frontend\models\Community;

/**
 * Communty controller
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
        $app = WenetApp::find()->where(["id" => $appId])->one();

        try {
            $community = \Yii::$app->profileManager->getCommunity($id, $appId);
        } catch (\Exception $e) {
            Yii::warning("Community $id (for app $appId) does not exist.", 'wenet.controller.community');
            return $this->render('/site/error', array(
                'message' => Yii::t('community', 'The community you are looking for does not exist.'),
                'name' => Yii::t('common', 'Error')
            ));
        }

        if ($community->load(Yii::$app->request->post()) && $community->validate()) {
            try {
                \Yii::$app->profileManager->updateCommunity($community);
            } catch (\Exception $e) {
                Yii::error("Error while updating community [$id] for app [$appId]: $e", 'wenet.controller.community');
                Yii::$app->session->setFlash('error', Yii::t('app', 'Something went wrong while updating the community.'));
            }
            return $this->redirect(['developer/details', 'id' => $appId]);
        }

        return $this->render('/community/update', array(
            'app' => $app,
            'community' => $community
        ));
    }

}
