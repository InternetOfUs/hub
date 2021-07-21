<?php
namespace frontend\models;


class BadgeDescriptor {

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
        if (array_key_exists('label', $rawData)) {
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
            'taskTypeId' => $this->taskTypeId,
            'threshold' => $this->threshold,
            'image' => $this->image,
            'app' => $this->appId,
        ];
        if ($this->id) {
            $repr['id'] = $this->id;
        }
        if ($this->label) {
            $repr['label'] = $this->label;
        }
        return $repr;
    }

    public function toUpdateRepr() {
        $repr = [
            'name' => $this->name,
            'description' => $this->description,
            'threshold' => $this->threshold,
            'image' => $this->image,
            'app' => $this->appId,
        ];
        return $repr;
    }

}
