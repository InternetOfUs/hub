<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use frontend\models\Badge;

class IncentiveServerConnector extends PlatformConnector {

    public $baseUrl;

    /**
     * Get the list of all Badges that a user won.
     *
     * @param  string $appId  The application id
     * @param  string $userId The user id
     * @return array[Badge]   The Badge list
     */
    public function getBadgesForUser($appId, $userId) {
        $url = $this->baseUrl . "/incentive/apps/$appId/users/$userId";
        try {
            $response = $this->get($url, $this->authHeaders());
            return array_map(
                function($b){ return Badge::fromRepr($b); },
                $response['incentives']['badges']
            );
        } catch (\Exception $e) {
            $log = "Something went wrong while getting badge list for app [$appId] and user [$userId]: $e";
            Yii::error($log, 'wenent.connector.incentive_server');
            return null;
        }
    }

    /**
     * Get the list of all Badges associated to an app.
     *
     * @param  string $appId  The application id
     * @return array[Badge]   The Badge list
     */
    public function getBadgesForApp($appId) {
        $url = $this->baseUrl . "/badges/apps/$appId";
        try {
            $response = $this->get($url, $this->authHeaders());
            return array_map(
                function($b){ return Badge::fromRepr($b); },
                $response['badges']
            );
        } catch (\Exception $e) {
            $log = "Something went wrong while getting badge list for app [$appId]: $e";
            Yii::error($log, 'wenent.connector.incentive_server');
            return null;
        }
    }

}
