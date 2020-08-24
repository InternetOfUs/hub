<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use frontend\models\AuthorisationForm;
use common\models\User;
use yii\helpers\Json;

/**
 * This is the model class for table "app".
 *
 * @property string $id
 * @property int $status
 * @property int $data_connector
 * @property int $conversational_connector
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

    const NOT_ACTIVE_CONNECTOR = 0;
    const ACTIVE_CONNECTOR = 1;

    const TAG_SOCIAL = 'social';
    const TAG_ASSISTANCE = 'assistance';

    const SCENARIO_CONVERSATIONAL = 'conversational';

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CONVERSATIONAL] = ['message_callback_url'];
        return $scenarios;
    }

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
            [['message_callback_url'], 'required', 'on' => self::SCENARIO_CONVERSATIONAL],

            [['id', 'token', 'name', 'status', 'owner_id'], 'required'],
            [['status', 'created_at', 'updated_at', 'owner_id', 'data_connector', 'conversational_connector'], 'integer'],
            [['description', 'message_callback_url', 'metadata'], 'string'],
            [['id'], 'string', 'max' => 128],
            [['name', 'token'], 'string', 'max' => 512],
            [['id'], 'unique'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
            [['status'], 'statusValidation'],
            ['associatedCategories', 'safe'],
        ];
    }

    public function statusValidation(){
        if($this->status == self::STATUS_ACTIVE){

            if($this->description == null || $this->associatedCategories == "" || !$this->hasSocialLogin() || ($this->conversational_connector == WenetApp::NOT_ACTIVE_CONNECTOR && $this->data_connector == WenetApp::NOT_ACTIVE_CONNECTOR)){
                if($this->description == null){
                    $this->addError('description', Yii::t('app', 'Description cannot be blank.'));
                }
                if($this->associatedCategories == ""){
                    $this->addError('associatedCategories', Yii::t('app', 'Select at least one tag.'));
                }
                if(!$this->hasSocialLogin() && $this->conversational_connector == WenetApp::NOT_ACTIVE_CONNECTOR && $this->data_connector == WenetApp::NOT_ACTIVE_CONNECTOR){
                    $this->addError('status', Yii::t('app', 'You should configure OAuth2 and enable at least one connector to go live with the app.'));
                } else if($this->hasSocialLogin() && $this->conversational_connector == WenetApp::NOT_ACTIVE_CONNECTOR && $this->data_connector == WenetApp::NOT_ACTIVE_CONNECTOR) {
                    $this->addError('status', Yii::t('app', 'You should enable at least one connector to go live with the app.'));
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
            'data_connector' => Yii::t('app', 'Data connector'),
            'conversational_connector' => Yii::t('app', 'Conversational connector'),
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

    public static function numberOfActiveApps() {
        return count(WenetApp::find()->where(['status' => self::STATUS_ACTIVE])->all());
    }

    public static function thereAreActiveApps() {
        return self::numberOfActiveApps() > 0;
    }

    public static function activeApps() {
        return WenetApp::find()->where(['status' => self::STATUS_ACTIVE])->all();
    }

    public function hasSocialLogin() {
        if ($this->getSocialLogin()) {
            return true;
        } else {
            return false;
        }
    }

    public function getSocialLogin() {
        $socialLogins = AppSocialLogin::find()->where(['app_id' => $this->id, 'status' => AppSocialLogin::STATUS_ACTIVE])->all();
        if (count($socialLogins) == 0) {
            return null;
        } else if (count($socialLogins) == 1) {
            return $socialLogins[0];
        } else {
            Yii::warning('App ['.$this->id.'] should not have more that one social logins configured');
            return $socialLogins[0];
        }
    }

    public function hasConversationalConnector() {
        $app = WenetApp::find()->where(['id' => $this->id, 'conversational_connector' => WenetApp::ACTIVE_CONNECTOR])->one();
        if($app){
            return true;
        } else {
            return false;
        }
    }

    public function hasWritePermit() {
        $socialLogin = AppSocialLogin::find()->where(['app_id' => $this->id, 'status' => AppSocialLogin::STATUS_ACTIVE])->one();
        $socialLogin->scope = json_decode($socialLogin->scope, true);

        $scopeToMerge = [];
        if ($scopeToMerge && isset($socialLogin->scope['scope'])) {
            $scopeToMerge = $socialLogin->scope['scope'];
        }
        $result = array_intersect($scopeToMerge, array_keys(AuthorisationForm::writeScope()));
        if(count($result) > 0){
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

    public function getOwnerShortName(){
        $ownerId = $this->owner->id;
        $owner = Yii::$app->serviceApi->getUserProfile($ownerId);

        $shortName = substr($owner->first_name, 0, 1) .'. '. $owner->last_name;
        return $shortName;
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
