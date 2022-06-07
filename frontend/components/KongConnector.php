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

    private function getConsumerCredentialsId($appId) {
        $url = $this->internalBaseUrl . '/consumers/'.self::consumerUsername($appId)."/oauth2";
        try {
            $result = $this->get($url, $this->authHeaders());
            $data = $result['data'];
            if(count($data) == 1){
                return $data[0]['id'];
            }
            else {
                $log = 'Received ['.count($data).'] credentials for app id ['.$appId.']';
                Yii::error($log, "wenent.connector.kong");
                throw new \Exception($log);
            }
           
        } catch (Exception $e) {
            $log = 'Something went wrong while retrieving the oauth2 credential id for comsumer ['. $appId.']';
            Yii::error($log);
            throw $e;
        }
    }

    private function getAllOauth2Tokens() {
        $url = $this->internalBaseUrl . '/oauth2_tokens';
        try {
            $result = $this->get($url, $this->authHeaders());
            return $result['data'];
        } catch (Exception $e) {
            $log = 'Something went wrong while retrieving the oauth2 tokens';
            Yii::error($log, 'wenent.connector.kong');
            throw $e;
        }
    }

    private function getAppOauth2Tokens($appId) {
        $consumer_id = $this->getConsumerCredentialsId($appId);
        $tokens = $this->getAllOauth2Tokens();
        return array_filter($tokens ,function($t) use($consumer_id) {
            return $t['credential']['id'] == $consumer_id;
        });
    }

    private function getAppAndUserOauth2Tokens($appId, $userId){
        $tokens = $this->getAppOauth2Tokens($appId);
        return array_filter($tokens, function($t) use($userId) {
            return $t['authenticated_userid'] == strval($userId);
        });
    }

    private function invalidateToken($token_id) {
        $url = $this->internalBaseUrl . '/oauth2_tokens/'.$token_id;
        try {
            $response = $this->delete($url, $this->authHeaders());
            return true;
        } catch (\Exception $e) {
            $log = "Something went wrong while invalidating the token with id [$token_id]: $e";
            Yii::error($log, 'wenent.connector.kong');
            return false;
        }
    }

    public function invalidateTokensForApp($appId) {
        $tokens = $this->getAppOauth2Tokens($appId);
        foreach ($tokens as $token) {
            $this->invalidateToken($token['id']);
        }
        return true;
    }

    public function invalidateTokenForAppAndUser($appId, $userId) {
        $tokens = $this->getAppAndUserOauth2Tokens($appId, $userId);
        foreach ($tokens as $token) {
            $this->invalidateToken($token['id']);
        }
        return true;
    }

    public function userHasValidTokenForApp($appId, $userId) {
        $tokens = $this->getAppAndUserOauth2Tokens($appId, $userId);
        return count($tokens) > 0;
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
