<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $username;
    public $email;
    public $password;
    public $password_repeat;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE_PASSWORD = 'update_password';

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['username', 'email', 'password', 'password_repeat'];
        $scenarios[self::SCENARIO_UPDATE_PASSWORD] = ['password', 'password_repeat'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['username', 'email'], 'trim'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'string', 'min' => 6],

            [['username', 'email', 'password', 'password_repeat'], 'required', 'on' => self::SCENARIO_CREATE],
            [['password', 'password_repeat'], 'required', 'on' => self::SCENARIO_UPDATE_PASSWORD],

            [['password', 'password_repeat'], 'checkPassword'],
        ];
    }

    public function attributeLabels() {
        return [
            'username' => Yii::t('common', 'Username'),
            'email' => Yii::t('common', 'Email'),
            'password' => Yii::t('common', 'Password'),
            'password_repeat' => Yii::t('signup', 'Repeat password'),

        ];
    }

    public function checkPassword($attribute, $params) {
        if($this->password == $this->password_repeat){
            return true;
        } else {
            $this->addError($attribute, Yii::t('signup', 'Password does not match.'));
            $this->addError('password_repeat', Yii::t('signup', 'Password does not match.'));
        }
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = User::STATUS_INACTIVE;
        $user->developer = User::NOT_DEVELOPER;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->generatePasswordResetToken();

        // TODO use trasactions
        if ($user->save()) {
            if (!Yii::$app->serviceApi->initUserProfile($user->id)) {
                $transaction->rollBack();
                return null;
            } else {
                $user->sendRegistrationEmail($user);
                $transaction->commit();
                return $user;
            }
        } else {
            return null;
        }
    }

    public function changePassword() {
        if (!$this->validate()) {
            return null;
        }

        $user = User::find()->where(['id' => Yii::$app->user->id])->one();
        $user->setPassword($this->password);

        if ($user->save()) {
            return $user;
        } else {
            return null;
        }
    }

}
