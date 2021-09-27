<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use frontend\models\Community;

class ProfileManagerConnector extends PlatformConnector {

    public $baseUrl;

    public function createCommunity($appId) {
        $url = $this->baseUrl . '/communities';
        $community = Community::empty($appId);
        try {
            $result = $this->post($url, $this->authHeaders(), $community->toRepr());
            return Community::fromRepr($result);
        } catch (\Exception $e) {
            $log = "Something went wrong while creating new community: $e";
            Yii::error($log, 'wenet.connector.profileManager');
            throw $e;
        }
    }

    public function getCommunity($id, $appId) {
        $url = $this->baseUrl . "/communities/$id";
        try {
            $result = $this->get($url, $this->authHeaders());
            return Community::fromRepr($result);
        } catch (\Exception $e) {
            $log = "Something went wrong while getting community $id for app $appId: $e";
            Yii::error($log, 'wenet.connector.profileManager');
            throw $e;
        }
    }

    public function updateCommunity(Community $community) {
        $url = $this->baseUrl . "/communities/$community->id";
        try {
            $this->put($url, $this->authHeaders(), $community->toRepr());
        } catch (\Exception $e) {
            $log = "Something went wrong while updating community $community->id for app $community->appId: $e";
            Yii::error($log, 'wenet.connector.profileManager');
            throw $e;
        }
    }

}
