<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "app_badge".
 *
 * @property int $id
 * @property string $app_id
 * @property int $creator_id
 * @property string $incentive_server_id
 * @property int $created_at
 * @property int $updated_at
 */
class AppBadge extends \yii\db\ActiveRecord {

    public $name;
    public $description;
    public $taskTypeId;
    public $threshold;
    public $image;
    public $label;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'app_badge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'description', 'taskTypeId', 'threshold', 'image', 'label', 'creator_id', 'app_id'], 'required'],
            [['creator_id', 'created_at', 'updated_at'], 'integer'],
            [['incentive_server_id'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'label' => Yii::t('badge', 'Transaction label'),
            'id' => 'ID',
            'app_id' => 'App ID',
            'creator_id' => 'Creator ID',
            'incentive_server_id' => 'Incentive Server ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function badgeFiles() {
        return [
            '/images/badges/medals/first_question.png' => '/images/badges/medals/first_question.png',
            '/images/badges/medals/curious_level_1.png' => '/images/badges/medals/curious_level_1.png',
            '/images/badges/medals/curious_level_2.png' => '/images/badges/medals/curious_level_2.png',
            '/images/badges/medals/fisrt_answer.png' => '/images/badges/medals/fisrt_answer.png',
            '/images/badges/medals/helper_level_1.png' => '/images/badges/medals/helper_level_1.png',
            '/images/badges/medals/helper_level_2.png' => '/images/badges/medals/helper_level_2.png',
            '/images/badges/medals/first_good_answer.png' => '/images/badges/medals/first_good_answer.png',
            '/images/badges/medals/good_answer_level_1.png' => '/images/badges/medals/good_answer_level_1.png',
            '/images/badges/medals/good_answer_level_2.png' => '/images/badges/medals/good_answer_level_2.png',
        ];
    }

    public function details() {
        $details = new BadgeDescriptor(
            $this->incentive_server_id,
            $this->name,
            $this->description,
            $this->taskTypeId,
            $this->threshold,
            $this->image,
            $this->app_id,
            $this->label
        );

        return $details;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $descriptor = $this->details();

            if (!$this->incentive_server_id) {
                // print_r($this);
                $descriptor = Yii::$app->incentiveServer->createBadgeDescriptor($descriptor);
                print_r($descriptor);
                exit();
                $this->incentive_server_id = $descriptor->id;
            } else {
                Yii::$app->incentiveServer->updateBadgeDescriptor($descriptor);
            }

            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete() {
         Yii::$app->incentiveServer->deleteBadgeDescriptor($this->incentive_server_id);
         return parent::beforeDelete();
    }

    public function afterFind() {

        parent::afterFind();

        $descriptor = Yii::$app->incentiveServer->getBadgeDescriptor($this->incentive_server_id);

        $this->name = $descriptor->name;
        $this->description = $descriptor->description;
        $this->taskTypeId = $descriptor->taskTypeId;
        $this->threshold = $descriptor->threshold;
        $this->image = $descriptor->image;
        $this->label = $descriptor->label;
    }
}
