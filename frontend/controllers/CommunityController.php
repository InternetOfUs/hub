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

        $community = new Community;
        $community->norms = Json::encode([
            [
              "description"=> "Notify to all the participants that the task is closed.",
              "whenever"=> "is_received_do_transaction('close',Reason) and not(is_task_closed()) and get_profile_id(Me) and get_task_requester_id(RequesterId) and =(Me,RequesterId) and get_participants(Participants)",
              "thenceforth"=> "add_message_transaction() and close_task() and send_messages(Participants,'close',Reason)",
              "ontology"=> "get_participants(P) :- get_task_state_attribute(UserIds,'participants',[]), get_profile_id(Me), wenet_remove(P,Me,UserIds)."
            ]
        ]);

        return $this->render('/community/update', array(
            'app' => $app,
            'community' => $community
        ));
    }

}
