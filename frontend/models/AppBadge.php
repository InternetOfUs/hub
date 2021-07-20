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
class AppBadge extends \yii\db\ActiveRecord
{

    public $name;
    public $description;
    public $taskTyperId;
    public $threshold;
    public $image;
    public $label;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_badge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['creator_id', 'app_id'], 'required'],
            [['creator_id', 'created_at', 'updated_at'], 'integer'],
            [['incentive_server_id'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'creator_id' => 'Creator ID',
            'incentive_server_id' => 'Incentive Server ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

    // public function beforeSave($insert) {
    //     if (parent::beforeSave($insert)) {
    //
    //         $taskTypeDetails = $this->details();
    //
    //         if (!$this->task_manager_id) {
    //             $id = Yii::$app->taskManager->createTaskType($taskTypeDetails);
    //             $this->task_manager_id = $id;
    //         } else {
    //             Yii::$app->taskManager->updateTaskType($taskTypeDetails);
    //         }
    //
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
    //
    // public function beforeDelete() {
    //      Yii::$app->taskManager->deleteTaskType($this->task_manager_id);
    //      return parent::beforeDelete();
    // }
    //
    // public function afterFind() {
    //
    //     parent::afterFind();
    //
    //     $details = Yii::$app->taskManager->getTaskType($this->task_manager_id);
    //
    //     $this->name = $details->name;
    //     $this->description = $details->description;
    //     $this->keywords = $details->keywords ? $details->keywords : [];
    //     $this->attributes = json_encode($details->attributes, JSON_FORCE_OBJECT);
    //     $this->transactions = json_encode($details->transactions, JSON_FORCE_OBJECT);
    //     $this->callbacks = json_encode($details->callbacks, JSON_FORCE_OBJECT);
    //     $this->norms = $details->norms ? JSON::encode($details->norms) : '[]';
    // }
}
