<?php

namespace frontend\components;

use Yii;
use yii\helpers\Json;
use yii\base\Component;
use frontend\models\Profile;

class ServiceApiConnector extends PlatformConnector {

    public $baseUrl;

    /**
     * Initialise an empty user profile associated to the specified user id.
     *
     * @param  string $userId The user id to be associated with the new profile
     * @return bool           Whether the profile creation was successful or not
     */
    public function initUserProfile($userId) {
        $url = $this->baseUrl . '/user/profile/' . $userId;
        try {
            $this->post($url, $this->authHeaders());
            return true;
        } catch (\Exception $e) {
            $log = "Something went wrong while initializing empty profile for user [$userId]: $e";
            Yii::error($log, 'wenet.connector.service_api');
            return false;
        }
    }

    /**
     * Get the profile of a user.
     *
     * @param  string $userId The if of the user
     * @return Profile|null   The requested profile - null when not available
     * @throws \Exception     If the profile of the user can not be retrieved
     */
    public function getUserProfile($userId) {
        $url = $this->baseUrl . '/user/profile/' . $userId;
        try {
            $result = $this->get($url, $this->authHeaders());
            return Profile::fromRepr($result);
        } catch (\Exception $e) {
            $log = "Something went wrong while getting profile for user [$userId]: $e";
            Yii::error($log, 'wenet.connector.service_api');
            throw $e;
        }
    }

    /**
     * Update the profile of a user.
     *
     * @param  Profile $profile The profile with the updated information
     * @return boolean          Whether the update was successful or not
     */
    public function updateUserProfile(Profile $profile) {
        $url = $this->baseUrl . '/user/profile/' . $profile->userId;
        try {
            $this->put($url, $this->authHeaders(), $profile->toRepr());
            return true;
        } catch (\Exception $e) {
            $log = "Something went wrong while updating profile for user [$profile->userId]: $e";
            Yii::error($log, 'wenet.connector.service_api');
            return false;
        }
    }

}
