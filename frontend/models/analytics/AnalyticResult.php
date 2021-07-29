<?php

namespace frontend\models\analytics;

use Yii;


class AnalyticResult {

    const TYPE_COUNT = 'count';
    const TYPE_SEGMENTATION = 'segmentation';

    public $type;
    public $result;

    function __construct($type, $result) {
        $this->type = $type;
        $this->result = $result;
    }

    public static function types() {
        return [
            self::TYPE_COUNT,
            self::TYPE_SEGMENTATION,
        ];
    }

    #
    # Parsers
    #

    public static function fromRepr($raw) {
        $type = $raw['type'];
        if (!in_array($type, self::types())) {
            throw new \Exception("Can not build result for type [$type]", 1);
        }

        if ($type == self::TYPE_COUNT) {
            $result = $raw['count'];
        } else {
            $result = $raw['segments'];
        }

        return new self(
            $raw['type'],
            $result,
        );
    }

    public function content() {
        if ($this->isCount()) {
            return $this->count();
        } else if ($this->isSegmentation()) {
            return $this->segments();
        } else {
            throw new \Exception("No definition for content retrieval", 1);
        }
    }

    public function isCount() {
        return $this->type == self::TYPE_COUNT;
    }

    public function isSegmentation() {
        return $this->type == self::TYPE_SEGMENTATION;
    }

    public function segments() {
        if (!$this->isSegmentation()) {
            throw new \Exception("Can not extract segments from result of type [$this->type]", 1);
        }

        $segments = [];
        foreach ($this->result as $entry) {
            $segments[$entry['type']] = $entry['count'];
        }
        return $segments;
    }

    public function count() {
        if (!$this->isCount()) {
            throw new \Exception("Can not extract segments from result of type [$this->type]", 1);
        }
        return $this->result;
    }
}
