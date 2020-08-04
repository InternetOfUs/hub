<?php

namespace frontend\models;

use Yii;

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
 * @property App $app
 */
class AppSocialLogin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_social_login';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['callback_url', 'scope', 'created_at', 'updated_at', 'status'], 'required'],
            [['callback_url', 'scope'], 'string'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['app_id'], 'string', 'max' => 128],
            [['app_id'], 'exist', 'skipOnError' => true, 'targetClass' => App::className(), 'targetAttribute' => ['app_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'callback_url' => 'Callback Url',
            'scope' => 'Scope',
            'app_id' => 'App ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[App]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApp()
    {
        return $this->hasOne(App::className(), ['id' => 'app_id']);
    }
}
