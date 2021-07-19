<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "app_user".
 *
 * @property string $app_id
 * @property int $user_id
 * @property int $created_at
 *
 * @property WenetApp $app
 * @property User $user
 */
class AppUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_id', 'user_id'], 'required'],
            [['user_id', 'created_at'], 'integer'],
            [['app_id'], 'string', 'max' => 128],
            [['app_id', 'user_id'], 'unique', 'targetAttribute' => ['app_id', 'user_id']],
            // [['app_id'], 'exist', 'skipOnError' => true, 'targetClass' => App::className(), 'targetAttribute' => ['app_id' => 'id']],
            // [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'app_id' => 'App ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[App]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApp()
    {
        return $this->hasOne(WenetApp::className(), ['id' => 'app_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function listForApp($appId, $fromTs=NULL, $toTs=NULL) {
        if ($fromTs && $toTs) {
            $sql = 'select * from app_user where created_at >= :fromTs and created_at <= :toTs;';
            return AppUser::findBySql($sql, [':fromTs' => $fromTs, ':toTs' => $toTs])->all();
        } else {
            return AppUser::find()->where(['app_id' => $appId])->all();
        }
    }
}
