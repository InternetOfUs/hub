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
class AppSocialLogin extends AppPlatform {

    public $allowedPublicScope;
    public $allowedWriteScope;
    public $allowedReadScope;

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
            [['callback_url', 'scope', 'status', 'oauth_app_id'], 'required'],
            [['callback_url'], 'string'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['app_id'], 'string', 'max' => 128],
            [['app_id'], 'exist', 'skipOnError' => true, 'targetClass' => WenetApp::className(), 'targetAttribute' => ['app_id' => 'id']],
            [['allowedPublicScope', 'allowedWriteScope', 'allowedReadScope'], 'safe']
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
        $this->type = self::TYPE_SOCIAL_LOGIN;

        if ($this->scope) {
            $this->scope = JSON::decode($this->scope);
        } else {
            $this->scope = array();
        }
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
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
