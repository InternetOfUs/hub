<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use frontend\models\WenetApp;

class AppConnector extends BaseConnector {

    public function newUserForPlatform(WenetApp $app, $platform, $userId) {
        $event = [
            'type' => 'event',
            'eventType' => 'newUserForPlatform',
            'app' => ''.$app->id,
            'platform' => $platform,
            'userId' => ''.$userId,
        ];

        try {
            $this->post($app->message_callback_url, [], $event);
            return true;
        } catch (\Exception $e) {
            Yii::error('Could not send event about newly activate platform ['.$platform.'] for user ['.$userId.'] and app ['.$app->id.']');
            return false;
        }
    }

}
