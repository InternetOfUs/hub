<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use yii\base\Component;

class BaseConnector extends Component {

    /**
     * HTTP GET request
     *
     * @param string $url     The url where to perform the request
     * @param array $headers  Optional, the headers to include in the request
     * @return array          The result of the GET request as an array
     */
    protected function get($url, $headers=NULL) {
        if (!$headers) {
            $headers = [];
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (\in_array($status, [200, 201, 202, 203, 204])) {
                return JSON::decode($result);
            } else {
                $log = 'Received error response with ['.$status.'] for GET query to ['.$url.']: '.$result;
                Yii::warning($log);
                throw new \Exception($log);
            }
        } catch (\Exception $e) {
            $log = 'Something went wrong while running GET query to ['.$url.']';
            Yii::error($log);
            throw $e;
        }
    }

    protected function post($url, $headers, $body=null) {
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

            if (\in_array($status, [200, 201, 202, 203, 204])) {
                return Json::decode($result);
            } else {
                $log = 'Received error response with ['.$status.'] for POST query to ['.$url.']: '.$result;
                Yii::warning($log);
                throw new \Exception($log);
            }
        } catch (\Exception $e) {
            $log = 'Something went wrong while running POST query to ['.$url.'] with body ' . JSON::encode($body);
            Yii::error($log);
            throw $e;
        }
    }

    protected function put($url, $headers, $body) {
        $headers[] = 'Content-Type: application/json';
        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, JSON::encode($body));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (\in_array($status, [200, 201, 202, 203, 204])) {
                return JSON::decode($result);
            } else {
                $log = 'Received error response with ['.$status.'] for PUT query to ['.$url.']: '.$result;
                Yii::warning($log);
                throw new \Exception($log);
            }
        } catch (\Exception $e) {
            $log = 'Something went wrong while running PUT query to ['.$url.'] with body ' . JSON::encode($body);
            Yii::error($log);
            throw $e;
        }
    }

    public function delete($url, $headers) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if (\in_array($status, [200, 201, 202, 203, 204])) {
                return JSON::decode($result);
            } else {
                $log = 'Received error response with ['.$status.'] for DELETE query to ['.$url.']: '.$result;
                Yii::warning($log);
                throw new \Exception($log);
            }
        } catch(\Exception $e) {
            $log = 'Something went wrong while running DELETE query to ['.$url.']';
            Yii::error($log);
            throw $e;
        }
    }

}
