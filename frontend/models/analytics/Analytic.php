<?php

namespace frontend\models\analytics;

use Yii;


class Analytic {

    public $id;
    public $descriptor;
    public $result;

    function __construct($id, AnalyticDescription $descriptor, AnalyticResult $result) {
        $this->id = $id;
        $this->descriptor = $descriptor;
        $this->result = $result;
    }

    #
    # Parsers
    #

    public static function fromRepr($raw) {
        return new Analytic(
            $raw['staticId'],
            AnalyticDescription::fromRepr($raw['query']),
            AnalyticResult::fromRepr($raw['result'])
        );
    }
}
