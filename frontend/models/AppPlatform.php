<?php

namespace frontend\models;

use Yii;

class AppPlatform extends \yii\db\ActiveRecord {

    const TYPE_TELEGRAM = 'telegram';

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
    	];
    }

    public static function getPlatformTypes(){
        return [
            self::TYPE_TELEGRAM
        ];
    }

}
