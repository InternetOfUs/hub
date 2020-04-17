<?php

namespace frontend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "app".
 *
 * @property string $id
 * @property int $status
 * @property string $name
 * @property string|null $description
 * @property string $token
 * @property string|null $message_callback_url
 * @property string $metadata
 * @property int $created_at
 * @property int $updated_at
 * @property int $owner_id
 *
 * @property User $owner
 */
class WenetApp extends \yii\db\ActiveRecord {

    public $allMetadata = [];
    public $associatedCategories = [];

    const STATUS_CREATED = 0;
    const STATUS_ACTIVE = 1;

    const TAG_SOCIAL = 'social';
    const TAG_ASSISTANCE = 'assistance';

    const PLATFORM_TELEGRAM = 'telegram';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'metadata', 'created_at', 'updated_at', 'owner_id'], 'required'],
            [['status', 'created_at', 'updated_at', 'owner_id'], 'integer'],
            [['description', 'message_callback_url', 'metadata'], 'string'],
            [['id'], 'string', 'max' => 128],
            [['name', 'token'], 'string', 'max' => 512],
            [['id'], 'unique'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'status' => Yii::t('app', 'Status'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'token' => Yii::t('app', 'Token'),
            'message_callback_url' => Yii::t('app', 'Message Callback Url'),
            'metadata' => Yii::t('app', 'Metadata'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'owner_id' => Yii::t('app', 'Owner ID'),
        ];
    }

    public static function label($label) {
        return self::labels()[$label];
    }

    public static function labels() {
        return [
    		self::TAG_SOCIAL => Yii::t('app', 'Social'),
    		self::TAG_ASSISTANCE => Yii::t('app', 'Assistance'),
    		self::PLATFORM_TELEGRAM => Yii::t('app', 'Telegram'),
    	];
    }

    public static function getPlatforms(){
        return [
            self::PLATFORM_TELEGRAM
        ];
    }

    public static function getTags(){
        return [
            self::TAG_SOCIAL,
            self::TAG_ASSISTANCE
        ];
    }

    public static function numberOfActiveApps() {
        return count(WenetApp::find()->where(['status' => self::STATUS_ACTIVE])->all());
    }

    public static function thereAreActiveApps() {
        return self::numberOfActiveApps() > 0;
    }

    public static function activeApps() {
        return WenetApp::find()->where(['status' => self::STATUS_ACTIVE])->all();
    }

    public static function hasPlatformTelegram($id) {
        return AppPlatformTelegram::find()->where(['app_id' => $id])->all();
    }

    public function afterFind() {
        if ($this->metadata) {
            $this->allMetadata = json_decode($this->metadata, true);

            if (isset($this->allMetadata['categories']) && is_array($this->allMetadata['categories'])) {
                $this->associatedCategories = $this->allMetadata['categories'];
            } else {
                $this->associatedCategories = [];
            }
        } else {
            $this->associatedCategories = array();
        }
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner() {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

}
