<?php


namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\User;
use frontend\components\Email;

class ResendVerificationEmailForm extends Model {
    /**
     * @var string
     */
    public $email;


    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_INACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    /**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail() {
        $user = User::findOne([
            'email' => $this->email,
            'status' => User::STATUS_INACTIVE
        ]);

        if ($user === null) {
            return false;
        }

        $verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['user/verify-email', 'token' => $user->verification_token]);

        $subject = Yii::t('reset', 'WeNet HUB - Verify your email');

        $message = array();
        $message[] = '<h1 style="padding:0px; margin:10px 0px; font-family:\'Helvetica\'; font-size:30px; color:#00a3b6; font-weight: bolder;">WeNet HUB</h1><p style="margin:0px; font-family:\'Helvetica\'; font-size:14px; color:#666666;">';
            $message[] = Yii::t('signup', "Hello ") . '<b style="color:#222222;">' . $user->username . '</b>,';
            $message[] = Yii::t('signup', "Follow the link below to verify your email:");
            $message[] = '<a style="margin:10px 0; display:inline-block; font-weight:bold; text-decoration:none; border-radius:20px; text-align: center; color: #fff; background-color: #337ab7; padding: 10px 20px;" href="'.$verifyLink.'">'.Yii::t('signup', "Verify account").'</a>';
            $message[] = '<br>';
            $message[] = Yii::t('signup', "Best,");
            $message[] = Yii::t('signup', "The WeNet HUB Team");
        $message[] = '</p>';
        $body = implode("<br>", $message);

        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['email.from']) # TODO set name of the sender
            ->setTo($this->email)
            ->setSubject($subject)
            ->setHtmlBody($body)
            ->send();

        return $this;
    }
}
