<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use frontend\models\analytics\Analytic;
use frontend\models\analytics\AnalyticDescription;

class LoggingComponentConnector extends PlatformConnector {

    public $baseUrl;

    public function createAnalytic(AnalyticDescription $descriptor) {
        $url = $this->baseUrl . '/analytic';
        try {
            $result = $this->post($url, $this->authHeaders(), $descriptor->toRepr());
            return $result['staticId'];
        } catch (\Exception $e) {
            $log = "Something went wrong while creating new analytic: $e";
            Yii::error($log, 'wenet.connector.logging_component');
            throw $e;
        }
    }

    public function getResult($appId, $analyticId) {
        $url = $this->baseUrl . '/analytic?project='.$appId.'&staticId='.$analyticId;
        try {
            $result = $this->get($url, $this->authHeaders());
            return Analytic::fromRepr($result);
        } catch (\Exception $e) {
            $log = "Something went wrong while creating new analytic: $e";
            Yii::error($log, 'wenet.connector.logging_component');
            throw $e;
        }
    }

}
