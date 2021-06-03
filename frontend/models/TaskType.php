<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
            [['creator_id', 'created_at', 'updated_at'], 'required'],
            [['task_manager_id'], 'string', 'max' => 256],
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

    public function getDetails($task_manager_id){
        return [
            'name' => 'Ask for Help',
            'description' => 'Ask a question into your community to helps you',
            'keywords' => ['question','answer','help'],
            'attributes' => [
                'kindOfAnswerer' => [
                    'type' => 'string',
                    'description' => 'The type of user shoud answer the question',
                    'enum' => ['different than me','similar to me','anyone']
                ]
            ],
            'transactions' => [
                'answerTransaction' => [
                    'type' => 'object',
                    'description' => 'Answer to a question',
                    'properties' => [
                        'answer' => [
                            'type' => 'string',
                            'description' => 'The answer to the question'
                        ]
                    ]
                ]
            ],
            'callbacks' => [
                'QuestionToAnswerMessage' => [
                    'description' => 'Question to answer',
                    'properties' => [
                        'taskId' => [
                            'type' => 'string',
                            'description' => 'The id of the task assiciated with the question'
                        ],
                        'question' => [
                            'type' => 'string',
                            'description' => 'The question to answer to'
                        ]
                    ]
                ]
            ],
            'norms' => [
                [
                    'whenever' => "is_received_created_task() and get_app_users_except_me(Unanswered) and get_community_state_attribute(Incentives,incentives,json(['Questions'=0])) and get_attribute(Questions,'Questions',0,Incentives)",
                    'thenceforth' => "add_created_transaction() and send_messages(Unanswered,'notifyNewQuestionAndAnswer',json([])) and wenet_math(NewQuestions,Questions + 1) and wenet_format(Action,'Questions {}',NewQuestions) and notify_incentive_server(Action,'') and put_community_state_attribute(incentives,json(['Questions'=NewQuestions]))",
                    'ontology' => null
                ]
            ]
        ];
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
}
