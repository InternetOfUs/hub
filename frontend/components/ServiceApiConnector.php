<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use yii\base\Component;
use frontend\models\Profile;

class ServiceApiConnector extends BaseConnector {

    public $baseUrl;
    public $apikey;

    public function initUserProfile($userId) {
        $url = $this->baseUrl . '/user/profile/' . $userId;
        try {
            $this->post($url, $this->authHeaders());
            return true;
        } catch (\Exception $e) {
            $log = 'Something went wrong while initializing empty profile for user ['.$userId.']';
            Yii::error($log);
            return false;
        }
    }

    public function getUserProfile($userId) {
        $url = $this->baseUrl . '/user/profile/' . $userId;
        try {
            $result = $this->get($url, $this->authHeaders());
            return Profile::fromRepr($result);
        } catch (\Exception $e) {
            $log = 'Something went wrong while getting profile for user ['.$userId.']';
            Yii::error($log);
            return null;
        }
    }

    public function updateUserProfile(Profile $profile) {
        $url = $this->baseUrl . '/user/profile/' . $profile->userId;
        try {
            $this->put($url, $this->authHeaders(), $profile->toRepr());
            return true;
        } catch (\Exception $e) {
            $log = 'Something went wrong while updating profile for user ['.$profile->userId.']';
            Yii::error($log);
            return false;
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
