<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use frontend\models\Profile;

class BaseController extends Controller {

    public function init(){
        parent::init();

        if(!Yii::$app->user->isGuest){
            try {
                $userProfile = $this->userProfile(Yii::$app->user->id);

                // check language
                $this->setLanguage($userProfile->locale);

                if(!$userProfile->isComplete()){
                    $content = Yii::t('profile', 'Remember to fill in your') .' '. Html::a( Yii::t('profile', 'profile'), ['/user/profile']) . '.';
                    Yii::$app->session->setFlash('error', $content);
                }

            } catch (\Exception $e) {
                Yii::error('Could not verify user profile completion for user [' .Yii::$app->user->id. ']: ' . $e, 'wenet.controller.base');
                throw new \Exception("Could not access user profile: $e", 1);
            }
        }
    }

    /**
     * Get the profile of a user.
     *
     * @param  string $userId The user id, optional: default to the currently logged in user.
     * @return Profile   The profile of the user
     * @throws Exception When the profile is not available
     */
    protected function userProfile($userId=NULL) {
        if (!$userId) {
            $userId = Yii::$app->user->id;
        }
        $userId = $userId.''; # Ensure the user id is a string

        $profile = NULL;

        try {
            $profile = $this->userProfileFromCache($userId);
            if ($profile === false) {
                Yii::debug("Getting profile of user [$userId] from service APIs", 'wenet.controller.base');
                $profile = Yii::$app->serviceApi->getUserProfile($userId);
                $this->updateCachedUserProfile($profile, Yii::$app->params['profile.cache.time.secs']);
            }
        } catch (\Exception $e) {
            Yii::error("User [$userId] profile not available: not from cache, nor from Service APIs: $e", 'wenet.controller.base');
            # TODO
        }

        if ($profile === NULL) {
            throw new \Exception("User [$userId] profile is not available", 1);
        }

        return $profile;
    }

    public function setLanguage($userLang){
        if(strpos($userLang, 'it', 0) !== false){
            Yii::$app->language = 'it-IT';
        } else if(strpos($userLang, 'mn', 0) !== false){
            Yii::$app->language = 'mn';
        } else if(strpos($userLang, 'es', 0) !== false){
            Yii::$app->language = 'es-ES';
        } else if(strpos($userLang, 'el', 0) !== false){
            Yii::$app->language = 'el-GR';
        }
       // } else if(strpos($userLang, 'da') !== false){
        //     Yii::$app->language = 'da';
        // }
        else {
            Yii::$app->language = Profile::DEFAULT_LANGUAGE;
        }
    }

    /**
     * Get the user profile from Redis Cache.
     *
     * @param  string $userId The user id
     * @return Profile|bool   The profile of the user (false if not present)
     */
    private function userProfileFromCache($userId) {
        Yii::debug("Getting profile of user [$userId] from cache", 'wenet.controller.base');
        $profile = Yii::$app->redis->get($userId.'');

        if ($profile !== false) {
            $profile = Profile::fromRepr(Json::decode($profile));
        } else {
            Yii::debug("Profile of user [$userId] is not cached", 'wenet.controller.base');
        }
        return $profile;
    }

    /**
     * Update the cached version of the user profile.
     *
     * @param  Profile $profile The profile to cache.
     * @param  integer $ttl     The time to live of the profile (in seconds), default to 3600 (1 hour)
     * @return boolean          Wheher the profile was correctly cached or not
     */
    protected function updateCachedUserProfile(Profile $profile, $ttl=3600) {
        Yii::debug("Caching profile for user [$profile->userId]", 'wenet.controller.base');
        $result = Yii::$app->redis->set($profile->userId, Json::encode($profile->toRepr()), $ttl);
        if (!$result) {
            Yii::warning("Could not cache profile for user [$profile->userId]", 'wenet.controller.base');
        }
        return $result;
    }

}
