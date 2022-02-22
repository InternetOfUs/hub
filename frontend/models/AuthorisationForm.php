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
            'id:read' => Yii::t('scope', 'ID'),
            'first_name:read' => Yii::t('scope', 'First name'),
            'last_name:read' => Yii::t('scope', 'Last name'),
            'id' => Yii::t('scope', 'ID: DEPRECATED'),
            'first_name' => Yii::t('scope', 'First name: DEPRECATED'),
            'last_name' => Yii::t('scope', 'Last name: DEPRECATED'),
            'conversations' => Yii::t('scope', 'Conversation logging: DEPRECATED'),
        ];
    }

    public static function readScope() {
        return [
            'middle_name:read' => Yii::t('scope', 'Middle name'),
            'prefix_name:read' => Yii::t('scope', 'Prefix name'),
            'suffix_name:read' => Yii::t('scope', 'Suffix name'),
            'birth_date:read' => Yii::t('scope', 'Birthdate'),
            'gender:read' => Yii::t('scope', 'Gender'),
            'email:read' => Yii::t('scope', 'Email'),
            'phone_number:read' => Yii::t('scope', 'Phone number'),
            'locale:read' => Yii::t('scope', 'Locale'),
            'avatar:read' => Yii::t('scope', 'Avatar'),
            'nationality:read' => Yii::t('scope', 'Nationality'),
            'occupation:read' => Yii::t('scope', 'Occupation'),
            'norms:read' => Yii::t('scope', 'Norms'),
            'activities:read' => Yii::t('scope', 'Activities'),
            'locations:read' => Yii::t('scope', 'Locations'),
            'relationships:read' => Yii::t('scope', 'Relationships'),
            'behaviours:read' => Yii::t('scope', 'Behaviours'),
            'materials:read' => Yii::t('scope', 'Materials'),
            'competences:read' => Yii::t('scope', 'Competences'),
            'materials:read' => Yii::t('scope', 'Materials'),
            'meanings:read' => Yii::t('scope', 'Meanings'),
            'middle_name' => Yii::t('scope', 'Middle name: DEPRECATED'),
            'prefix_name' => Yii::t('scope', 'Prefix name: DEPRECATED'),
            'suffix_name' => Yii::t('scope', 'Suffix name: DEPRECATED'),
            'birthdate' => Yii::t('scope', 'Birthdate: DEPRECATED'),
            'gender' => Yii::t('scope', 'Gender: DEPRECATED'),
            'nationality' => Yii::t('scope', 'Nationality: DEPRECATED'),
            'locale' => Yii::t('scope', 'Language: DEPRECATED'),
            'phone_number' => Yii::t('scope', 'Phone number: DEPRECATED'),
        ];
    }

    public static function writeScope() {
        return [
            'first_name:write' => Yii::t('scope', 'First name'),
            'last_name:write' => Yii::t('scope', 'Last name'),
            'middle_name:write' => Yii::t('scope', 'Middle name'),
            'prefix_name:write' => Yii::t('scope', 'Prefix name'),
            'suffix_name:write' => Yii::t('scope', 'Suffix name'),
            'birth_date:write' => Yii::t('scope', 'Birthdate'),
            'gender:write' => Yii::t('scope', 'Gender'),
            'email:write' => Yii::t('scope', 'Email'),
            'phone_number:write' => Yii::t('scope', 'Phone number'),
            'locale:write' => Yii::t('scope', 'Locale'),
            'avatar:write' => Yii::t('scope', 'Avatar'),
            'nationality:write' => Yii::t('scope', 'Nationality'),
            'occupation:write' => Yii::t('scope', 'Occupation'),
            'norms:write' => Yii::t('scope', 'Norms'),
            'activities:write' => Yii::t('scope', 'Activities'),
            'locations:write' => Yii::t('scope', 'Locations'),
            'relationships:write' => Yii::t('scope', 'Relationships'),
            'behaviours:write' => Yii::t('scope', 'Behaviours'),
            'materials:write' => Yii::t('scope', 'Materials'),
            'competences:write' => Yii::t('scope', 'Competences'),
            'meanings:write' => Yii::t('scope', 'Meanings'),
            'conversation:write' => Yii::t('scope', 'Conversation'),
            'data:write' => Yii::t('scope', 'Data'),
            'write_feed' => Yii::t('scope', 'Write data feed: DEPRECATED'),
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
