<?php

namespace frontend\models;

use Yii;


class BadgeDescriptor {

    const TEST_IMAGE = 'https://upload.wikimedia.org/wikipedia/commons/3/33/Cartoon_space_rocket.png';

    public $id;
    public $name;
    public $description;
    public $message;
    public $taskTypeId;
    public $threshold;
    public $image;
    public $appId;
    public $label;

    function __construct($id, $name, $description, $message, $taskTypeId, $threshold, $image, $appId, $label=NULL) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->message = $message;
        $this->taskTypeId = $taskTypeId;
        $this->threshold = $threshold;
        $this->image = $image;
        $this->appId = $appId;
        $this->label = $label;
    }

    public function isTaskBadge() {
        return $this->label == NULL;
    }

    public function isTransactionBadge() {
        return !$this->isTaskBadge();
    }

    public static function fromRepr($rawData) {
        $label = NULL;
        if (array_key_exists('label', $rawData) && $rawData['label'] != "") {
            $label = $rawData['label'];
        }
        return new BadgeDescriptor(
            $rawData['id'],
            $rawData['name'],
            $rawData['description'],
            $rawData['message'],
            $rawData['taskTypeId'],
            $rawData['threshold'],
            $rawData['image'],
            $rawData['app'],
            $label
        );
    }

    public function toRepr() {
        $repr = [
            'name' => $this->name,
            'description' => $this->description,
            'taskTypeId' => ''.$this->taskTypeId,
            'threshold' => intval($this->threshold),
            'image' => $this->image,
            'app' => $this->appId,
            'messege' => $this->message,
        ];
        if ($this->id) {
            $repr['id'] = $this->id;
        }
        if ($this->label != '') {
            $repr['label'] = $this->label;
        } else {
            $repr['label'] = null;
        }

        if (in_array('env', Yii::$app->params) && Yii::$app->params['env'] == 'local') {
            $repr['image'] = self::TEST_IMAGE;
        }

        return $repr;
    }

    public function toUpdateRepr() {
        $repr = [
            'name' => $this->name,
            'description' => $this->description,
            'threshold' => intval($this->threshold),
            'image' => $this->image,
            'app' => $this->appId,
            'messege' => $this->message,
        ];
        if ($this->label) {
            $repr['label'] = $this->label;
        }

        if (in_array('env', Yii::$app->params) && Yii::$app->params['env'] == 'local') {
            $repr['image'] = self::TEST_IMAGE;
        }

        return $repr;
    }

}
