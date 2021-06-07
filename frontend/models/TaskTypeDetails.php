<?php
namespace frontend\models;

/**
 * A Badge that can be won by users while interacting with and using the WeNet applications.
 */
class TaskTypeDetails {

    public $id;
    public $name;
    public $description;
    public $keywords = [];
    public $attributes;
    public $transactions;
    public $callbacks;
    public $norms;

    function __construct($id, $name, $description, $keywords, $attributes, $transactions, $callbacks, $norms) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->attributes = $attributes;
        $this->transactions = $transactions;
        $this->callbacks = $callbacks;
        $this->norms = $norms;
    }

    public static function fromRepr($rawData) {
        // $keywords = [];
        // if ($rawData['keywords']) {
        //     $keywords = $rawData['keywords'];
        // }
        //
        // $attributes = [];
        // if ($rawData['attributes']) {
        //     $attributes = $rawData['attributes'];
        // }
        //
        // $transactions = [];
        // if ($rawData['transactions']) {
        //     $transactions = $rawData['transactions'];
        // }
        //
        // $callbacks = [];
        // if ($rawData['callbacks']) {
        //     $callbacks = $rawData['callbacks'];
        // }
        //
        // $norms = [];
        // if ($rawData['norms']) {
        //     $norms = $rawData['norms'];
        // }

        return new TaskTypeDetails(
            $rawData['id'],
            $rawData['name'],
            $rawData['description'],
            $rawData['keywords'],
            $rawData['attributes'],
            $rawData['transactions'],
            $rawData['callbacks'],
            $rawData['norms']
        );
    }

    public function toRepr() {
        $repr = [
            'name' => $this->name,
            'description' => $this->description,
            'keywords' => $this->keywords,
            'attributes' => $this->attributes,
            'transactions' => $this->transactions,
            'callbacks' => $this->callbacks,
            'norms' => $this->norms
        ];
        if ($this->id) {
            $repr['id'] = $this->id;
        }
        return $repr;
    }

}
