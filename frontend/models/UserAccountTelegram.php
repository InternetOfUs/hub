<?php
namespace frontend\models;
use Yii;
/**
 * This is the model class for table "user_account_telegram".
 *
 * @property int $id
 * @property int $user_id
 * @property string $app_id
 * @property int $telegram_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property App $app
 */
class UserAccountTelegram extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user_account_telegram';
    }
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'app_id', 'telegram_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'telegram_id', 'created_at', 'updated_at'], 'integer'],
            [['app_id'], 'string', 'max' => 128],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['app_id'], 'exist', 'skipOnError' => true, 'targetClass' => App::className(), 'targetAttribute' => ['app_id' => 'id']],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'app_id' => Yii::t('app', 'App ID'),
            'telegram_id' => Yii::t('app', 'Telegram ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    /**
     * Gets query for [[App]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApp() {
        return $this->hasOne(App::className(), ['id' => 'app_id']);
    }

}
