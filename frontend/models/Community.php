<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Json;


class Community extends Model {

    public $id;
    public $norms;
    public $appId;
    public $originalData;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'norms', 'appId'], 'required'],
            [['norms'], 'string'],
            [['norms'], 'normsValidation']
        ];
    }

    public function normsValidation(){
        if($this->norms == '' || $this->norms == null){
            $this->addError('norms', Yii::t('tasktype', 'Attributes should be defined'));
        } else {
            try {
                Json::decode($this->norms);
            } catch (\Exception $e) {
                $this->addError('norms', Yii::t('tasktype', 'Attributes should be a valid JSON list'));
            }
        }
    }

    public static function empty($appId) {
        $community = new Community();
        $community->id = null;
        $community->appId = $appId;
        $community->norms = Json::encode([]);
        $community->originalData = [
            'name' => null,
            'description' => null,
            'keywords' => [],
            'id' => null,
            'appId' => $appId,
            'members' => [],
            'socialPractices' => [],
            'taskTypeIds' => [],
            'norms' => [],
        ];
        return $community;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'norms' => Yii::t('app', 'Norms'),
        ];
    }

    public static function fromRepr($rawData) {
        $community = new Community();

        $community->originalData = $rawData;
        $community->norms = Json::encode($rawData['norms']);
        $community->id = $rawData['id'];
        $community->appId = $rawData['appId'];

        return $community;
    }

    public function toRepr() {
        $this->originalData['norms'] = Json::decode($this->norms);
        return $this->originalData;
    }

}
