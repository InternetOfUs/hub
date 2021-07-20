<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "app_badge".
 *
 * @property int $id
 * @property int $creator_id
 * @property string $incentive_server_id
 * @property int $created_at
 * @property int $updated_at
 */
class AppBadge extends \yii\db\ActiveRecord
{
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
            [['creator_id'], 'required'],
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
            'creator_id' => 'Creator ID',
            'incentive_server_id' => 'Incentive Server ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
