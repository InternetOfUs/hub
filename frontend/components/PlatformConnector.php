<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use frontend\models\WenetApp;

class PlatformConnector extends BaseConnector {

    public $apikey;

    protected function authHeaders() {
        $headers = [];
        if ($this->apikey) {
            $headers[] = 'x-wenet-component-apikey: ' . $this->apikey;
        }
        return $headers;
    }

}
