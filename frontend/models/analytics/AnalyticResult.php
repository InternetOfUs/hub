<?php

namespace frontend\models\analytics;

use Yii;


class AnalyticResult {

    public $count;
    public $items;
    public $type;

    function __construct($count, $items, $type) {
        $this->count = $count;
        $this->items = $items;
        $this->type = $type;
    }

    #
    # Parsers
    #

    public static function fromRepr($raw) {
        return new AnalyticResult(
            $raw['count'],
            $raw['items'],
            $raw['type'],
        );
    }
}
