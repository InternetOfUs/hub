<?php

namespace frontend\models\analytics;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "app_analytic".
 *
 * @property string $id
 * @property string $app_id
 * @property string $dimension
 * @property string $metric
 * @property string $timespan
 * @property int $created_at
 * @property int $updated_at
 */
class AppAnalytic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app_analytic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['id', 'app_id', 'dimension', 'metric', 'timespan'], 'string', 'max' => 128],
            [['id'], 'unique'],
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
            'dimension' => 'Dimension',
            'metric' => 'Metric',
            'timespan' => 'Timespan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
}
