<?php

namespace frontend\models;

use Yii;
use yii\helpers\Url;
use yii\behaviors\TimestampBehavior;

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
            [['name', 'description', 'taskTypeId', 'threshold', 'image', 'creator_id', 'app_id'], 'required'],
            [['creator_id', 'created_at', 'updated_at'], 'integer'],
            [['threshold'], 'number'],
            [['incentive_server_id', 'label'], 'string', 'max' => 256],
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'label' => Yii::t('badge', 'Transaction label'),
            'id' => 'ID',
            'app_id' => 'App ID',
            'creator_id' => 'Creator ID',
            'incentive_server_id' => Yii::t('badge', 'Incentive Server ID'),
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function badgeFiles() {
        # Note: this configuration prevents the visualisation of image when developing locally.
        # Changing the value of $flag to true will allow to visualise imges within the browser
        # but will prevent a successful update of any modifications in the Incentive Server.
        # With both configurations, the incentive server will refuse image. In one case because https is missing,
        # in the other because the image does not actually exist.
        #
        # This problem can be solved by making sure that the parameter `env` is present in the params-local.php
        # of the project and is set to `local`.
        $flag = 'https';
        if (Yii::$app->params['env'] == 'local') {
            $flag = true;
        }
        $urls = [
            Url::toRoute('/images/badges/medals/first_question.png', $flag),
            Url::toRoute('/images/badges/medals/curious_level_1.png', $flag),
            Url::toRoute('/images/badges/medals/curious_level_2.png', $flag),
            Url::toRoute('/images/badges/medals/fisrt_answer.png', $flag),
            Url::toRoute('/images/badges/medals/helper_level_1.png', $flag),
            Url::toRoute('/images/badges/medals/helper_level_2.png', $flag),
            Url::toRoute('/images/badges/medals/first_good_answer.png', $flag),
            Url::toRoute('/images/badges/medals/good_answer_level_1.png', $flag),
            Url::toRoute('/images/badges/medals/good_answer_level_2.png', $flag),
        ];

        if (Yii::$app->params['env'] == 'local') {
            $urls[] = BadgeDescriptor::TEST_IMAGE;
        }

        return $urls;
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

    public static function getTransactionLabels($app){
        $transactionLabels = array_keys($app->taskType->details()->transactions);
        $labelData = [];
        foreach ($transactionLabels as $transactionLabel) {
            $labelData[$transactionLabel] = $transactionLabel;
        }
        return $labelData;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if($this->label === "" || $this->label === "null"){
                $this->label = null;
            }

            $descriptor = $this->details();

            if (!$this->incentive_server_id) {
                $descriptor = Yii::$app->incentiveServer->createBadgeDescriptor($descriptor);
                $this->incentive_server_id = $descriptor->id;
            } else {
                if (!Yii::$app->incentiveServer->updateBadgeDescriptor($descriptor)) {
                    return false;
                }
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


    public function getWenetApp() {
        return $this->hasOne(WenetApp::className(), ['id' => 'app_id']);
    }
}
