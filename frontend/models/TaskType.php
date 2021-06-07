<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\User;
use yii\helpers\Json;

/**
 * This is the model class for table 'task_type'.
 *
 * @property int $id
 * @property int $public
 * @property int $creator_id
 * @property string $task_manager_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $creator
 * @property TaskTypeDeveloper[] $taskTypeDevelopers
 */
class TaskType extends \yii\db\ActiveRecord {

    const PRIVATE_TASK_TYPE = 0;
    const PUBLIC_TASK_TYPE = 1;

    public $name;
    public $description;
    public $keywords = [];
    public $attributes;
    public $transactions;
    public $callbacks;
    public $norms;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'task_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['public', 'creator_id', 'created_at', 'updated_at'], 'integer'],
            [['creator_id', 'name', 'description'], 'required'],
            [['task_manager_id'], 'string', 'max' => 256],
            [['attributes', 'transactions', 'norms', 'callbacks'], 'string'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
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
            'id' => 'ID',
            'public' => 'Public',
            'creator_id' => 'Creator ID',
            'task_manager_id' => 'Task Manager ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function isCreator($user_id) {
        // TODO fix
        return TaskType::find()->where(['id' => $this->id, 'creator_id' => $user_id])->one();
    }

    /**
     * Gets query for [[Creator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreator() {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * Gets query for [[TaskTypeDevelopers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskTypeDevelopers() {
        return $this->hasMany(TaskTypeDeveloper::className(), ['task_type_id' => 'id']);
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {

            $attributes = new \stdClass();
            if ($this->attributes && $this->attributes != '' && $this->attributes != '{}' && $this->attributes != '[]' && is_string($this->attributes)) {
                $attributes = JSON::encode($this->attributes);
            }
            $transactions = new \stdClass();
            if ($this->transactions && $this->transactions != '' && $this->transactions != '{}' && $this->transactions != '[]' && is_string($this->transactions)) {
                $transactions = JSON::decode($this->transactions);
            }
            $callbacks = new \stdClass();
            if ($this->callbacks && $this->callbacks != '' && $this->callbacks != '{}' && $this->callbacks != '[]' && is_string($this->callbacks)) {
                $callbacks = JSON::decode($this->callbacks);
            }
            $norms = [];
            if ($this->norms && $this->norms != '' && is_string($this->norms)) {
                $norms = JSON::decode($this->norms);
            }

            $taskTypeDetails = new TaskTypeDetails(
                $this->task_manager_id,
                $this->name,
                $this->description,
                [],
                $attributes,
                $transactions,
                $callbacks,
                $norms
            );

            if (!$this->task_manager_id) {
                $id = Yii::$app->taskManager->createTaskType($taskTypeDetails);
                $this->task_manager_id = $id;
            } else {
                Yii::$app->taskManager->updateTaskType($taskTypeDetails);
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterFind() {

        $details = Yii::$app->taskManager->getTaskType($this->task_manager_id);

        $this->name = $details->name;
        $this->description = $details->description;
        $this->keywords = $details->keywords ? $details->keywords : [];
        $this->attributes = json_encode($details->attributes, JSON_FORCE_OBJECT);
        $this->transactions = json_encode($details->transactions, JSON_FORCE_OBJECT);
        $this->callbacks = json_encode($details->callbacks, JSON_FORCE_OBJECT);
        $this->norms = JSON::encode($details->norms);
    }
}
