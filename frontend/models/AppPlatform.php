<?php

namespace frontend\models;

use Yii;

class AppPlatform extends \yii\db\ActiveRecord {

    const TYPE_TELEGRAM = 'telegram';
    const TYPE_SOCIAL_LOGIN = 'social_login';

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public $type;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        throw new \Exception("Should be implemented", 1);
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        throw new \Exception("Should be implemented", 1);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        throw new \Exception("Should be implemented", 1);
    }

    /**
     * Gets query for [[App]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApp() {
        throw new \Exception("Should be implemented", 1);
    }

    function isTelegram() {
        return $this->type == self::TYPE_TELEGRAM;
    }

    public static function typeLabel($label) {
        return self::typeLabels()[$label];
    }

    private static function typeLabels() {
        return [
    		self::TYPE_TELEGRAM => Yii::t('app', 'Telegram'),
    		self::TYPE_SOCIAL_LOGIN => Yii::t('app', 'Social login'),
    	];
    }

    public static function getPlatformTypes(){
        return [
            self::TYPE_TELEGRAM,
            self::TYPE_SOCIAL_LOGIN
        ];
    }

}
