<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;

class KongConnector extends BaseConnector {

    public $baseUrl;
    public $provisionKey;
    // public $consumerId;

    const RESPONSE_TYPE = 'code';

    // public function createApplication($name, $clientId, $clientSecret, $redirectUri) {
    //     $url = $this->baseUrl . '/consumers/'.$this->consumerId.'/oauth2';
    //     $data = [
    //         'name' => $name,
    //         'client_id' => $clientId,
    //         'client_secret' => $clientSecret,
    //         'redirect_uris' => $redirectUri,
    //     ];
    //     try {
    //         $this->post($url, $this->authHeaders(), $data);
    //     } catch (\Exception $e) {
    //         $log = 'Something went wrong while initializing empty profile for user ['.$userId.']';
    //         Yii::error($log);
    //     }
    // }

    public function createAuthenticatedUser($appId, $userId, $scope) {
        $url = $this->baseUrl . '/oauth2/authorize';
        $data = [
            'client_id' => $appId,
            'response_type' => self::RESPONSE_TYPE,
            'scope' => $scope,
            'provision_key' => $this->provisionKey,
            'authenticated_userid' => $userId,
        ];
        try {
            $result = $this->post($url, $this->authHeaders(), $data);
            $response = Json::decode($result);
            if (isset($response['redirect_uri'])) {
                return $response['redirect_uri'];
            } else {
                return null;  # TODO throw exception
            }
        } catch (\Exception $e) {
            $log = 'Something went wrong while initializing empty profile for user ['.$userId.']';
            Yii::error($log);
        }
    }

    public function authHeaders() {
        $headers = [];
        // if ($this->apikey) {
        //     $headers[] = 'apikey: ' . $this->apikey;
        // }
        return $headers;
    }

}
