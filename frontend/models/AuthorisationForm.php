<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\models\WenetApp;

class AuthorisationForm extends Model {

    public $appId;
    public $readScope = [];
    public $writeScope = [];

    public $userId;

    public $allowedScope;  # once the fom is submitted, it should be a list of scope keys

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['appId', 'scope', 'userId'], 'required'],
        ];
    }

    public static function scope() {
        return [
            'read' => self::readScope(),
            'write' => self::writeScope(),
        ];
    }

    private static function readScope() {
        return Profile::instance()->attributeLabels();;
    }

    private static function writeScope() {
        return [
            'write_feed' => Yii::t('write_feed', 'Write data feed'),  # TODO find better name
        ];
    }

    public function withCompleteScope() {
        $this->readScope = self::readScope();
        $this->writeScope = self::writeScope();
        return $this;
    }

    public function withSpecifiedScope($requestedScope) {
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
