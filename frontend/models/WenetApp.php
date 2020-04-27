<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\User;
use yii\helpers\Json;

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

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const TAG_SOCIAL = 'social';
    const TAG_ASSISTANCE = 'assistance';

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
            [['id', 'token', 'name', 'status', 'owner_id', 'associatedCategories'], 'required'],
            [['status', 'created_at', 'updated_at', 'owner_id'], 'integer'],
            [['description', 'message_callback_url', 'metadata'], 'string'],
            [['id'], 'string', 'max' => 128],
            [['name', 'token'], 'string', 'max' => 512],
            [['id'], 'unique'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
            [['status'], 'statusValidation']
        ];
    }

    public function statusValidation(){
        if($this->status == self::STATUS_ACTIVE){
            if($this->description == null || $this->message_callback_url == null || count($this->platforms()) == 0){

                if($this->description == null){
                    $this->addError('description', Yii::t('app', 'Description cannot be blank.'));
                }
                if($this->message_callback_url == null){
                    $this->addError('message_callback_url', Yii::t('app', 'Message Callback Url cannot be blank.'));
                }
                if(count($this->platforms()) == 0){
                    $this->addError('status', Yii::t('app', 'You should enable at least one platform to go live with the app.'));
                }
                return false;
            }
        }
        return true;
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
            'categories' => Yii::t('app', 'Categories'),
            'platforms' => Yii::t('app', 'Platforms'),
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

    public static function tagLabel($label) {
        return self::tagLabels()[$label];
    }

    private static function tagLabels() {
        return [
    		self::TAG_SOCIAL => Yii::t('app', 'Social'),
    		self::TAG_ASSISTANCE => Yii::t('app', 'Assistance'),
    	];
    }

    public static function getTags(){
        return [
            self::TAG_SOCIAL,
            self::TAG_ASSISTANCE
        ];
    }

    public static function tagsWithLabels() {
        $tagData = [];
        foreach (self::getTags() as $tag) {
            $tagData[$tag] = self::tagLabel($tag);
        }
        return $tagData;
    }

    public function numberOfActiveUserForTelegram() {
        return count(UserAccountTelegram::find()->where([
            'app_id' => $this->id,
            'active' => UserAccountTelegram::ACTIVE
        ])->all());
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

    public function platforms() {
        $platforms = [];
        $telegramPlatform = $this->getPlatformTelegram();
        if ($telegramPlatform) {
            $platforms[] = $telegramPlatform;
        }
        return $platforms;
    }

    public function hasPlatformTelegram() {
        if ($this->getPlatformTelegram()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the enabled telegram platform, if defined.
     * @return AppPlatformTelegram|null
     */
    public function getPlatformTelegram() {
        $telegramPlatforms = AppPlatformTelegram::find()->where(['app_id' => $this->id, 'status' => AppPlatform::STATUS_ACTIVE])->all();
        if (count($telegramPlatforms) == 0) {
            return null;
        } else if (count($telegramPlatforms) == 1) {
            return $telegramPlatforms[0];
        } else {
            Yii::warning('App ['.$this->id.'] should not have more that one telegram platform configured');
            return $telegramPlatforms[0];
        }
    }

    public function getTelegramUser() {
        $userId = Yii::$app->user->id;
        $telegramUser =  UserAccountTelegram::find()->where([
            'app_id' => $this->id,
            'user_id' => $userId
        ])->one();

        if($telegramUser){
            return $telegramUser;
        } else {
            return null;
        }
    }


    public function telegramUserIsActive() {
        $user = $this->getTelegramUser();
        if ($user !== null && $user->active == UserAccountTelegram::ACTIVE) {
            return true;
        } else {
            return false;
        }
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

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $this->metadata = [
                'categories' => $this->associatedCategories,
            ];
            $this->metadata = JSON::encode($this->metadata);

            if ($this->message_callback_url == '') {
                $this->message_callback_url = null;
            }
            if ($this->description == '') {
                $this->description = null;
            }

            return true;
        } else {
            return false;
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

    public function getEnabledPlatforms() {
        return $this->hasMany(UserAccountTelegram::className(), ['app_id' => 'id']);
    }

    public function create() {
        $this->id = self::generateRandomString(10);
        $this->token = self::generateRandomString(20);
        $this->owner_id = Yii::$app->user->id;
        $this->status = self::STATUS_NOT_ACTIVE;
        return $this->save();
    }

    private static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
