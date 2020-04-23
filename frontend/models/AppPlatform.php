<?php

namespace frontend\models;

class AppPlatform extends \yii\db\ActiveRecord {

    const TYPE_TELEGRAM = 'telegram';

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

}
