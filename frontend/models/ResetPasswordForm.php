<?php
namespace frontend\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use common\models\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model {
    public $password;

    /**
     * @var \common\models\User
     */
    private $_user;
    public $password_repeat;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, $config = []) {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Password reset token cannot be blank.');
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException('Wrong password reset token.');
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['password', 'password_repeat'], 'required'],
            ['password', 'string', 'min' => 6],

            [['password', 'password_repeat'], 'checkPassword'],
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
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword() {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
