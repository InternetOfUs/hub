<?php
namespace frontend\models;

/**
 * TaskManager
 */
class TaskManager {

    public $name;
    public $description;
    public $keywords;
    public $attributes;
    public $transactions;
    public $callbacks;
    public $norms;

    function __construct($name, $description, $keywords, $attributes, $transactions, $callbacks, $norms) {
        $this->name = $name;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->attributes = $attributes;
        $this->transactions = $transactions;
        $this->callbacks = $callbacks;
        $this->norms = $norms;
    }

    /**
     * Create TaskManager instance from array representation
     *
     * @param  array $rawData The array taskManager representation
     * @return TaskManager          The TaskManager instance
     */
    public static function fromRepr($rawData) {
        return new TaskManager(
            $rawData['name'],
            $rawData['description'],
            $rawData['keywords'],
            $rawData['attributes'],
            $rawData['transactions'],
            $rawData['callbacks'],
            $rawData['norms']
        );
    }

}
