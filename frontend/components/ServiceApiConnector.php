<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use yii\base\Component;
use frontend\models\Profile;

class ServiceApiConnector extends Component {

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

    public function getUserProfile($userId) {
        return new Profile();
    }

    public function updateUserProfile(Profile $profile) {
        
    }

    public function authHeaders() {
        return [
            'apikey: ' . $this->apikey,
        ];
    }

    private function post($url, $headers, $body=null) {
        $headers[] = 'Content-Type: application/json';
        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            if ($body) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, JSON::encode($body));
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status == 200 || $status == 201) {
                return $result;
            } else {
                $log = 'Received error response with ['.$status.'] for POST query to ['.$url.']: '.$result;
                Yii::warning($log);
                throw new \Exception($log);
            }
        } catch (\Exception $e) {
            $log = 'Something went wrong while running POST query to ['.$url.'] with body ' . JSON::encode($body);
            Yii::error($log);
            throw new \Exception($log, 0, $e);
        }
    }

}
