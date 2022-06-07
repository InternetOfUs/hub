<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use frontend\models\AuthorisationForm;
use common\models\User;
use frontend\models\AppDeveloper;
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
 * @property string $image_url
 * @property int|null $task_type_id
 * @property string|null $privacy_policy_url
 * @property string|null $privacy_policy_text
 *
 * @property User $owner
 * @property TaskType $taskType
 * @property AppDeveloper[] $appDevelopers
 * @property User[] $users
 * @property AppSocialLogin[] $appSocialLogins
 */

class WenetApp extends \yii\db\ActiveRecord {

    public $allMetadata = [];
    public $associatedCategories = [];
    public $slFacebook = '';
    public $slTelegram = '';
    public $slAndroid = '';
    public $slIos = '';
    public $slWebApp = '';

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const NOT_ACTIVE_CONNECTOR = 0;
    const ACTIVE_CONNECTOR = 1;

    const TAG_SOCIAL = 'social';
    const TAG_ASSISTANCE = 'assistance';

    const SL_FACEBOOK = 'facebook';
    const SL_TELEGRAM = 'telegram';
    const SL_ANDROID = 'android';
    const SL_IOS = 'ios';
    const SL_WEB_APP = 'web_app';

    const SCENARIO_CONVERSATIONAL = 'conversational';

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CONVERSATIONAL] = ['message_callback_url', 'task_type_id'];
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
            [['message_callback_url', 'task_type_id'], 'required', 'on' => self::SCENARIO_CONVERSATIONAL],

            [['id', 'token', 'name', 'status', 'owner_id'], 'required'],
            [['status', 'created_at', 'updated_at', 'owner_id', 'data_connector', 'conversational_connector', 'task_type_id'], 'integer'],
            [['description', 'message_callback_url', 'metadata', 'image_url', 'privacy_policy_url', 'privacy_policy_text'], 'string'],
            [['id', 'community_id'], 'string', 'max' => 128],
            [['name', 'token'], 'string', 'max' => 512],
            [['id'], 'unique'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
            // [['task_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskType::className(), 'targetAttribute' => ['task_type_id' => 'id']],
            [['slFacebook', 'slTelegram', 'slAndroid', 'slIos', 'slWebApp', 'image_url'], 'linksValidation'],
            [['status'], 'statusValidation'],
            [['description', 'privacy_policy_text'], 'contentValidation'],
            [['message_callback_url', 'privacy_policy_url'], 'messageLinkValidation'],
            [['associatedCategories', 'slFacebook', 'slTelegram', 'slAndroid', 'slIos', 'slWebApp'], 'safe'],
        ];
    }

    public function toRepr() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'ownerId' => $this->owner_id,
            'image_url' => $this->image_url,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'metadata' => $this->allMetadata,
            'taskTypeId' => $this->task_type_id,
            'messageCallbackUrl' => $this->message_callback_url,
            'community_id' => $this->community_id,
            'privacyPolicyUrl' => $this->privacy_policy_url,
            'privacyPolicyText' => $this->privacy_policy_text,
        ];
    }

    public function contentValidation(){
        foreach ($this as $key => $value) {
            if(is_string($value)){
                if($key == 'description' || $key == 'privacy_policy_text'){
                    $this[$key] = strip_tags($value, '<b><i><br>');
                } else {
                    $this[$key] = strip_tags($value, '');
                }
            }
        }
    }

    public function statusValidation(){
        //  TODO to be updated with check of task_type_id?
        if($this->status == self::STATUS_ACTIVE){
            $ok = true;

            if($this->description == null){
                $this->addError('description', Yii::t('app', 'Description cannot be blank.'));
                $ok = false;
            }

            if($this->associatedCategories == ""){
                $this->addError('associatedCategories', Yii::t('app', 'Select at least one tag.'));
                $ok = false;
            }

            if($this->slFacebook == null && $this->slTelegram == null && $this->slAndroid == null && $this->slIos == null && $this->slWebApp == null){
                $this->addError('slFacebook', Yii::t('app', 'Set up at least one link.'));
                $ok = false;
            }

            if(!$this->hasSocialLogin() && $this->conversational_connector == WenetApp::NOT_ACTIVE_CONNECTOR && $this->data_connector == WenetApp::NOT_ACTIVE_CONNECTOR){
                $this->addError('status', Yii::t('app', 'You should configure OAuth2 and enable at least one connector to go live with the app.'));
                $ok = false;
            } else if($this->hasSocialLogin() && $this->conversational_connector == WenetApp::NOT_ACTIVE_CONNECTOR && $this->data_connector == WenetApp::NOT_ACTIVE_CONNECTOR) {
                $this->addError('status', Yii::t('app', 'You should enable at least one connector to go live with the app.'));
                $ok = false;
            }

            if($this->privacy_policy_url == null){
                $this->addError('privacy_policy_url', Yii::t('app', 'Privacy policy URL cannot be blank.'));
                $ok = false;
            }

            if($this->privacy_policy_text == null){
                $this->addError('privacy_policy_text', Yii::t('app', 'Privacy policy text cannot be blank.'));
                $ok = false;
            }

            return $ok;
        }
        return true;
    }

    public function linksValidation(){
        if($this->slFacebook != null && substr($this->slFacebook, 0, 4) != "http"){
            $this->addError('slFacebook', Yii::t('app', 'Link should be an absolute link (add http:// or https://).'));
        }
        if($this->slTelegram != null && substr($this->slTelegram, 0, 4) != "http"){
            $this->addError('slTelegram', Yii::t('app', 'Link should be an absolute link (add http:// or https://).'));
        }
        if($this->slAndroid != null && substr($this->slAndroid, 0, 4) != "http"){
            $this->addError('slAndroid', Yii::t('app', 'Link should be an absolute link (add http:// or https://).'));
        }
        if($this->slIos != null && substr($this->slIos, 0, 4) != "http"){
            $this->addError('slIos', Yii::t('app', 'Link should be an absolute link (add http:// or https://).'));
        }
        if($this->slWebApp != null && substr($this->slWebApp, 0, 4) != "http"){
            $this->addError('slWebApp', Yii::t('app', 'Link should be an absolute link (add http:// or https://).'));
        }
        if($this->image_url != null && substr($this->image_url, 0, 4) != "http"){
            $this->addError('image_url', Yii::t('app', 'Image url should be an absolute link (add http:// or https://).'));
        }
    }

    public function messageLinkValidation(){
        if($this->message_callback_url != null && substr($this->message_callback_url, 0, 4) != "http"){
            $this->addError('message_callback_url', Yii::t('app', 'Link should be an absolute link (add http:// or https://).'));
        }
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
            'slFacebook' => Yii::t('app', 'Facebook'),
            'slTelegram' => Yii::t('app', 'Telegram'),
            'slAndroid' => Yii::t('app', 'Android app'),
            'slIos' => Yii::t('app', 'iOS app'),
            'slWebApp' => Yii::t('app', 'Web app'),
            'associatedCategories' => Yii::t('app', 'Associated Categories'),
            'image_url' => Yii::t('app', 'App image URL'),
            'task_type_id' => Yii::t('app', 'App Logic'),
            'community_id' => Yii::t('app', 'Community ID'),
            'privacy_policy_url' => Yii::t('app', 'Privacy Policy URL'),
            'privacy_policy_text' => Yii::t('app', 'Privacy Policy Text'),
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
        //  TODO to be updated with check of task_type_id?
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
        if ($socialLogin->scope && isset($socialLogin->scope['scope']) && is_array($socialLogin->scope['scope'])) {
            $scopeToMerge = $socialLogin->scope['scope'];
        }
        $result = array_intersect($scopeToMerge, array_keys(AuthorisationForm::writeScope()));

        if(count($result) > 0){
            return true;
        } else {
            return false;
        }
    }

    public function hasActiveSourceLinksForApp() {
        if (count($this->getActiveSourceLinksForApp()) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getActiveSourceLinksForApp() {
        $app = WenetApp::find()->where(['id' => $this->id])->one();
        $app->metadata = json_decode($this->metadata, true);

        $activeSourceLinks = [];
        if (isset($app->metadata['source_links']) && is_array($app->metadata['source_links'])) {
            if($app->metadata['source_links'][self::SL_FACEBOOK] != null){$activeSourceLinks[] = self::SL_FACEBOOK;}
            if($app->metadata['source_links'][self::SL_TELEGRAM] != null){$activeSourceLinks[] = self::SL_TELEGRAM;}
            if($app->metadata['source_links'][self::SL_ANDROID] != null){$activeSourceLinks[] = self::SL_ANDROID;}
            if($app->metadata['source_links'][self::SL_IOS] != null){$activeSourceLinks[] = self::SL_IOS;}
            if($app->metadata['source_links'][self::SL_WEB_APP] != null){$activeSourceLinks[] = self::SL_WEB_APP;}
        }

        return $activeSourceLinks;
    }

    public static function sourceLinksLabel($label) {
        return self::sourceLinksLabels()[$label];
    }

    private static function sourceLinksLabels() {
        return [
    		self::SL_FACEBOOK => Yii::t('app', 'Facebook'),
    		self::SL_TELEGRAM => Yii::t('app', 'Telegram'),
    		self::SL_ANDROID => Yii::t('app', 'Android app'),
    		self::SL_IOS => Yii::t('app', 'iOS app'),
    		self::SL_WEB_APP => Yii::t('app', 'Web app'),
    	];
    }

    public static function getSourceLinks() {
        return [
    		self::SL_FACEBOOK,
    		self::SL_TELEGRAM,
    		self::SL_ANDROID,
    		self::SL_IOS,
    		self::SL_WEB_APP
    	];
    }

    public function afterFind() {
        if ($this->metadata) {
            $this->allMetadata = json_decode($this->metadata, true);

            if (isset($this->allMetadata['categories']) && is_array($this->allMetadata['categories'])) {
                $this->associatedCategories = $this->allMetadata['categories'];
            } else {
                $this->associatedCategories = [];
            }

            if (isset($this->allMetadata['source_links']) && is_array($this->allMetadata['source_links'])) {
                if($this->allMetadata['source_links'][self::SL_FACEBOOK] != null){$this->slFacebook = $this->allMetadata['source_links'][self::SL_FACEBOOK];}
                if($this->allMetadata['source_links'][self::SL_TELEGRAM] != null){$this->slTelegram = $this->allMetadata['source_links'][self::SL_TELEGRAM];}
                if($this->allMetadata['source_links'][self::SL_ANDROID] != null){$this->slAndroid = $this->allMetadata['source_links'][self::SL_ANDROID];}
                if($this->allMetadata['source_links'][self::SL_IOS] != null){$this->slIos = $this->allMetadata['source_links'][self::SL_IOS];}
                if($this->allMetadata['source_links'][self::SL_WEB_APP] != null){$this->slWebApp = $this->allMetadata['source_links'][self::SL_WEB_APP];}
            }
        } else {
            $this->associatedCategories = array();
        }
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if($this->associatedCategories == ""){
                $this->associatedCategories = [];
            }

            if($this->slFacebook == ""){ $this->slFacebook = null; }
            if($this->slTelegram == ""){ $this->slTelegram = null; }
            if($this->slAndroid == ""){ $this->slAndroid = null; }
            if($this->slIos == ""){ $this->slIos = null; }
            if($this->slWebApp == ""){ $this->slWebApp = null; }
            if($this->image_url == ""){ $this->image_url = null; }

            $this->metadata = [
                'categories' => $this->associatedCategories,
                'source_links' => [
                    self::SL_FACEBOOK => $this->slFacebook,
                    self::SL_TELEGRAM => $this->slTelegram,
                    self::SL_ANDROID => $this->slAndroid,
                    self::SL_IOS => $this->slIos,
                    self::SL_WEB_APP => $this->slWebApp
                ]
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
     * Gets query for [[TaskType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskType()
    {
        return $this->hasOne(TaskType::className(), ['id' => 'task_type_id']);
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner() {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    /**
     * Gets query for [[AppDevelopers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppDevelopers() {
        return $this->hasMany(AppDeveloper::className(), ['app_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('app_developer', ['app_id' => 'id']);
    }

    public function isOwner($user_id) {
        return WenetApp::find()->where(['id' => $this->id, 'owner_id' => $user_id])->one() != null;
    }

    public function isDeveloper($userId = null){
        if($userId == null){
            $userId = Yii::$app->user->id;
        }
        $allDevelopers = $this->appDevelopers;
        $devIds = [];
        foreach ($allDevelopers as $dev) {
            $devIds[] = $dev->user_id;
        }
        return in_array($userId, $devIds);
    }

    public function getOwnerShortName(){
        $ownerId = $this->owner->id;
        try {
            $owner = $this->userProfile($ownerId);
            $shortName = substr($owner->first_name, 0, 1) .'. '. $owner->last_name;
            return $shortName;
        } catch (\Exception $e) {
            Yii::warning('App owner short name is not available', 'wenet.model.app');
            return null;
        }
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
