<?php

namespace frontend\models;

use Yii;


class BadgeDescriptor {

    const TEST_IMAGE = 'https://upload.wikimedia.org/wikipedia/commons/3/33/Cartoon_space_rocket.png';

    public $id;
    public $name;
    public $description;
    public $taskTypeId;
    public $threshold;
    public $image;
    public $appId;
    public $label;

    function __construct($id, $name, $description, $taskTypeId, $threshold, $image, $appId, $label=NULL) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
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
            // TODO should make sure that an https image is present, else an error is given by the incentive server
            'image' => $this->image,
            'app' => $this->appId,
        ];
        if ($this->id) {
            $repr['id'] = $this->id;
        }
        if ($this->label != '') {
            $repr['label'] = $this->label;
        } else {
            $repr['label'] = null;
        }

        if (Yii::$app->params['env'] == 'local') {
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
        ];
        if ($this->label) {
            $repr['label'] = $this->label;
        }

        if (Yii::$app->params['env'] == 'local') {
            $repr['image'] = self::TEST_IMAGE;
        }

        return $repr;
    }

}
