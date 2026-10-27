<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use yii\base\Component;

class BaseConnector extends Component {

    /**
     * Initialise a cURL handle for the given url, resolving its host in PHP.
     *
     * On Ubuntu 22.04 (glibc >= 2.34) libcurl cannot start its resolver thread when the
     * container runs on a Docker engine older than 20.10.10 (its seccomp profile denies
     * clone3), failing with "getaddrinfo() thread failed to start". Resolving the host
     * with the blocking gethostbyname() and handing the address to cURL via CURLOPT_RESOLVE
     * avoids that thread; TLS verification still uses the hostname.
     *
     * @param string $url  The url where to perform the request
     * @return resource    The cURL handle, with CURLOPT_URL already set
     */
    public static function curlInit($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        $host = parse_url($url, PHP_URL_HOST);
        if ($host && !filter_var($host, FILTER_VALIDATE_IP)) {
            $ip = gethostbyname($host);
            if ($ip !== $host) {
                $port = parse_url($url, PHP_URL_PORT) ?: (parse_url($url, PHP_URL_SCHEME) === 'http' ? 80 : 443);
                curl_setopt($ch, CURLOPT_RESOLVE, [$host.':'.$port.':'.$ip]);
            }
        }

        return $ch;
    }

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
            $ch = self::curlInit($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if (\in_array($status, [200, 201, 202, 203, 204])) {
                return JSON::decode($result);
            } else {
                $log = 'Received error response with ['.$status.'] for GET query to ['.$url.']: '.($curlError ? '[cURL error: '.$curlError.'] ' : '').$result;
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
            $ch = self::curlInit($url);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            if ($body) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, JSON::encode($body));
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if (\in_array($status, [200, 201, 202, 203, 204])) {
                return Json::decode($result);
            } else {
                $log = 'Received error response with ['.$status.'] for POST query to ['.$url.']: '.($curlError ? '[cURL error: '.$curlError.'] ' : '').$result;
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
            $ch = self::curlInit($url);

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, JSON::encode($body));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if (\in_array($status, [200, 201, 202, 203, 204])) {
                return JSON::decode($result);
            } else {
                $log = 'Received error response with ['.$status.'] for PUT query to ['.$url.']: '.($curlError ? '[cURL error: '.$curlError.'] ' : '').$result;
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
            $ch = self::curlInit($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            $result = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if (\in_array($status, [200, 201, 202, 203, 204])) {
                return JSON::decode($result);
            } else {
                $log = 'Received error response with ['.$status.'] for DELETE query to ['.$url.']: '.($curlError ? '[cURL error: '.$curlError.'] ' : '').$result;
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
