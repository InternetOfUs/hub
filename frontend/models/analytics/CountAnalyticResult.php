<?php

namespace frontend\models\analytics;

use Yii;


class CountAnalyticResult {

    public $count;
    public $type;

    function __construct($count, $type) {
        $this->count = $count;
        $this->type = $type;
    }

    #
    # Parsers
    #

    public static function fromRepr($raw) {
        return new CountAnalyticResult(
            $raw['count'],
            $raw['type'],
        );
    }
}
