<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\models\WenetApp;

class AuthorisationForm extends Model {

    public $appId;
    public $publicScope = [];
    public $readScope = [];
    public $writeScope = [];

    public $userId;

    public $allowedPublicScope;
    public $allowedWriteScope;
    public $allowedReadScope;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['appId', 'scope', 'userId'], 'required'],
            [['allowedPublicScope', 'allowedWriteScope', 'allowedReadScope'], 'safe']
        ];
    }

    public static function scope() {
        return [
            'public' => self::publicScope(),
            'read' => self::readScope(),
            'write' => self::writeScope(),
        ];
    }

    public static function publicScope() {
        return [
            'id' => Yii::t('write_feed', 'User ID'),
        ];
    }

    public static function readScope() {
        return Profile::instance()->attributeLabels();
    }

    public static function writeScope() {
        return [
            'write_feed' => Yii::t('write_feed', 'Write data feed'),  # TODO find better name
        ];
    }

    public function withCompleteScope() {
        $this->publicScope = self::publicScope();
        $this->readScope = self::readScope();
        $this->writeScope = self::writeScope();
        return $this;
    }

    public function withSpecifiedScope($requestedScope) {
        $this->publicScope = self::publicScope();
        foreach (self::readScope() as $permission => $label) {
            if (in_array($permission, $requestedScope)) {
                $this->readScope[$permission] = $label;
            }
        }
        foreach (self::writeScope() as $permission => $label) {
            if (in_array($permission, $requestedScope)) {
                $this->writeScope[$permission] = $label;
            }
        }
        return $this;
    }

    public function user() {
        return User::findOne($this->userId);
    }

    public function app() {
        return WenetApp::findOne($this->appId);
    }
}