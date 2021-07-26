<?php

namespace frontend\models\analytics;

use Yii;


class AnalyticDescription {

    const TYPE_COUNT = 'count';
    const TYPE_SEGMENTATION = 'segmentation';

    public $appId;
    public $dimension;
    public $metric;
    public $type;

    public $timespan;

    function __construct($appId, $dimension, $metric, $timespan, $type) {
        $this->appId = $appId;
        $this->dimension = $dimension;
        $this->metric = $metric;
        $this->timespan = $timespan;
        $this->type = $type;
    }

    public static function count($appId, $dimension, $metric, $timespan) {
        return new AnalyticDescription($appId, $dimension, $metric, $timespan, AnalyticDescription::TYPE_COUNT);
    }

    public static function segmentation($appId, $dimension, $metric, $timespan) {
        return new AnalyticDescription($appId, $dimension, $metric, $timespan, AnalyticDescription::TYPE_SEGMENTATION);
    }

    public function isCount() {
        return $this->type == self::TYPE_COUNT;
    }

    public function isSegmentation() {
        return $this->type == self::TYPE_SEGMENTATION;
    }

    #
    # Parsers
    #

    public function toRepr() {
        return [
            'project' => $this->appId,
            'timespan' => $this->timespan,
            'type' => $this->type,
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

    public static function movingTimespan($period) {
        return [
           'type' => 'moving',
           'value' => $period,
       ];
    }

    public static function days($appId, $dimension, $metric, $days) {
        $timespan = AnalyticDescription::movingTimespan($days.'d');
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
