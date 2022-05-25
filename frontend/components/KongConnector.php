<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;

class KongConnector extends BaseConnector {

    public $internalBaseUrl;
    public $externalBaseUrl;
    public $provisionKey;

    const RESPONSE_TYPE = 'code';

    private static function consumerUsername($appId) {
        return 'app_' . $appId;
    }

    public function invalidateTokensForApp($appId) {
        // TODO should return true or false
        return true;
    }

    public function invalidateTokenForAppAndUser($appId, $userId) {
        // TODO should return true or false
        return true;
    }

    public function createConsumer($appId) {
        $url = $this->internalBaseUrl . '/consumers/';
        $data = [
            'username' => self::consumerUsername($appId),
        ];
        try {
            $response = $this->post($url, $this->authHeaders(), $data);
            if (isset($response['id'])) {
                return $response['id'];
            } else {
                return null;  # TODO throw exception
            }
        } catch (\Exception $e) {
            $log = 'Something went wrong while creating consumer for app ['. $appId.']';
            Yii::error($log);
            throw $e;
        }
    }

    public function deleteConsumer($appId) {
        $url = $this->internalBaseUrl . '/consumers/'.self::consumerUsername($appId);
        try {
            $this->delete($url, $this->authHeaders());
        } catch (\Exception $e) {
            $log = 'Something went wrong while deleting consumer for app ['.$appId.']';
            Yii::error($log);
        }
    }

    public function createOAuthCredentials($clientId, $clientSecret, $redirectUrl) {
        $url = $this->internalBaseUrl . '/consumers/' . self::consumerUsername($clientId) . '/oauth2';
        $data = [
            'name' => 'oauth2_' . $clientId,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uris' => [$redirectUrl],
        ];
        try {
            $response = $this->post($url, $this->authHeaders(), $data);
            if (isset($response['id'])) {
                return $response['id'];
            } else {
                return null;  # TODO throw exception
            }
        } catch (\Exception $e) {
            $log = 'Something went wrong while creating oauth2 credentials for app ['. $clientId.']';
            Yii::error($log);
            throw $e;
        }
    }

    public function deleteOAuthCredentials($appId, $id) {
        $url = $this->internalBaseUrl . '/consumers/' . self::consumerUsername($appId) . '/oauth2/'. $id;
        try {
            $this->delete($url, $this->authHeaders());
        } catch (\Exception $e) {
            $log = 'Something went wrong while deleting oauth credentials for ['.$id.']';
            Yii::error($log);
        }
    }

    public function createAuthenticatedUser($appId, $userId, $scope) {
        $url = $this->externalBaseUrl . '/oauth2/authorize';
        $data = [
            'client_id' => $appId,
            'response_type' => self::RESPONSE_TYPE,
            'scope' => $scope,
            'provision_key' => $this->provisionKey,
            'authenticated_userid' => ''.$userId,
        ];
        try {
            $response = $this->post($url, $this->authHeaders(), $data);
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
