<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use frontend\models\WenetApp;
use yii\helpers\Json;

/**
 * This is the model class for table "app_social_login".
 *
 * @property int $id
 * @property string $callback_url
 * @property string $scope
 * @property string $app_id
 * @property int $created_at
 * @property int $updated_at
 * @property int $status
 *
 * @property WenetApp $app
 */
class AppSocialLogin extends \yii\db\ActiveRecord {

    public $allMetadata = [];
    public $allowedScopes = [];

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['callback_url', 'scope', 'allowedScopes', 'status', 'oauth_app_id'];
        $scenarios[self::SCENARIO_UPDATE] = ['callback_url', 'scope', 'allowedScopes', 'oauth_app_id'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'app_social_login';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['callback_url', 'status', 'oauth_app_id'], 'required', 'on' => self::SCENARIO_CREATE],
            [['callback_url'], 'required', 'on' => self::SCENARIO_UPDATE],

            [['callback_url'], 'string'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['app_id'], 'string', 'max' => 128],
            [['app_id'], 'exist', 'skipOnError' => true, 'targetClass' => WenetApp::className(), 'targetAttribute' => ['app_id' => 'id']],
            [['allowedScopes'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'callback_url' => Yii::t('app', 'Callback Url'),
            'scope' => Yii::t('app', 'Scope'),
            'app_id' => Yii::t('app', 'App ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'allowedScopes' => Yii::t('app', 'Permissions')
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    public function afterFind() {
        if ($this->scope) {
            $this->allMetadata = json_decode($this->scope, true);

            if (isset($this->allMetadata['scope']) && is_array($this->allMetadata['scope'])) {
                $this->allowedScopes = $this->allMetadata['scope'];
            } else {
                $this->allowedScopes = [];
            }
        } else {
            $this->allowedScopes = array();
        }
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if($this->allowedScopes == ""){
                $this->allowedScopes = [];
            }

            $this->scope = [
                'scope' => $this->allowedScopes,
            ];
            $this->scope = JSON::encode($this->scope);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Gets query for [[App]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApp() {
        return $this->hasOne(WenetApp::className(), ['id' => 'app_id']);
    }
}
