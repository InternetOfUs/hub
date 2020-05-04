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
            'app' => $app->id,
            'userId' => $platform,
            'platform' => $userId,
        ];

        try {
            $this->post($app->message_callback_url, [], $event);
        } catch (\Exception $e) {

        }
    }

}
