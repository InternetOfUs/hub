<?php

namespace frontend\models\analytics;

use Yii;


class AnalyticDescription {

    public $appId;
    public $dimension;
    public $metric;

    public $timespan;

    function __construct($appId, $dimension, $metric, $timespan) {
        $this->appId = $appId;
        $this->dimension = $dimension;
        $this->metric = $metric;
        $this->timespan = $timespan;
    }

    #
    # Parsers
    #

    public function toRepr() {
        return [
            'project' => $this->appId,
            'timespan' => $this->timespan,
            'type' => 'analytic',
            'dimension' => $this->dimension,
            'metric' => $this->metric
        ];
    }

    public static function fromRepr($raw) {
        return new AnalyticDescription(
            $raw['project'],
            $raw['timespan'],
            $raw['type'],
            $raw['dimension'],
            $raw['metric'],
        );
    }

    #
    # Builders
    #

    public static function defaultTimespan($period) {
        return [
           'type' => 'moving',
           'value' => $period,
       ];
    }

    public static function days($appId, $dimension, $metric, $days) {
        $timespan = AnalyticDescription::defaultTimespan($days.'d');
        return new AnalyticDescription($appId, $dimension, $metric, $timespan);
    }

    public static function weeks($app, $dimension, $metric, $weeks) {
        $timespan = [
            'type' => 'default',
            'value' => $weeks.'w'
        ];
        return new AnalyticDescription($appId, $dimension, $metric, $timespan);
    }

    public static function months($app, $dimension, $metric, $months) {
        $timespan = [
            'type' => 'default',
            'value' => $months.'m'
        ];
        return new AnalyticDescription($appId, $dimension, $metric, $timespan);
    }

    public static function years($app, $dimension, $metric, $years) {
        $timespan = [
            'type' => 'default',
            'value' => $years.'y'
        ];
        return new AnalyticDescription($appId, $dimension, $metric, $timespan);
    }

    // public static function custom($app, $dimension, $metric, $startDt, $endDt) {
    //
    // }
}
