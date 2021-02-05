<?php
namespace common\models;

use Yii;
use yii\helpers\Url;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use frontend\models\AppUser;
use frontend\models\WenetApp;
use frontend\components\Email;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property int $developer
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const NOT_DEVELOPER = 0;
    const DEVELOPER = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['developer'], 'required'],
            [['developer'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function isDeveloper(){
        return $this->developer == 1;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findUser($id) {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    public static function findByEmail($email) {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken() {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    public function sendRegistrationEmail() {
        $registrationLink = Url::to(['/user/verify-email', 'token' => $this->verification_token], true);

        $subject = Yii::t('signup', 'WeNet HUB - New account');

        $message = array();
        $message[] = '<h1 style="padding:0px; margin:10px 0px; font-family:\'Helvetica\'; font-size:30px; color:#00a3b6; font-weight: bolder;">WeNet HUB</h1><p style="margin:0px; font-family:\'Helvetica\'; font-size:14px; color:#666666;">';
            $message[] = Yii::t('signup', "Congratulations ") . '<b style="color:#222222;">' . $this->username . '</b>,';
            $message[] = Yii::t('signup', "you succefully created a new account on the WeNet HUB.");
            $message[] = '<br>';
            $message[] = Yii::t('signup', "Click here to activate it:");
            $message[] = '<a style="margin:10px 0; display:inline-block; font-weight:bold; text-decoration:none; border-radius:20px; text-align: center; color: #fff; background-color: #337ab7; padding: 10px 20px;" href="'.$registrationLink.'">'.Yii::t('signup', "Verify account").'</a>';
            $message[] = '<br>';
            $message[] = Yii::t('signup', "Best,");
            $message[] = Yii::t('signup', "The WeNet HUB Team");
        $message[] = '</p>';
        $body = implode("<br>", $message);

        Email::build()
            ->setSubject($subject)
            ->setFrom(Yii::$app->params['email.from'], Yii::$app->params['email.from.name'])
            ->setTo($this->email)
            ->setHtmlBody($body)
            ->send();
        return $this;
    }

    public function getApps() {
        $appsUser = AppUser::find()->where(['user_id' => $this->id])->all();

        $activeApps = [];
        foreach ($appsUser as $appUser) {
            $app = WenetApp::find()->where(['id' => $appUser->app_id])->one();
            if($app->status == WenetApp::STATUS_ACTIVE){
              $activeApps[] = $app;
            }
        }
        return $activeApps;
    }
}
