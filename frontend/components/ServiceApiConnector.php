<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use yii\base\Component;

class ServiceApiConnector extends BaseConnector {

    public $baseUrl;
    public $apikey;

    public function initUserProfile($userId) {
        $url = $this->baseUrl . '/user/profile/' . $userId;
        try {
            $this->post($url, $this->authHeaders());
        } catch (\Exception $e) {
            $log = 'Something went wrong while initializing empty profile for user ['.$userId.']';
            Yii::error($log);
        }
    }

    public function authHeaders() {
        $headers = [];
        if ($this->apikey) {
            $headers[] = 'apikey: ' . $this->apikey;
        }
        return $headers;
    }

}
