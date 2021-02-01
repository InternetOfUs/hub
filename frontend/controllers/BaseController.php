<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\Html;

class BaseController extends Controller {

    public function init(){
        parent::init();

        if(!Yii::$app->user->isGuest){

            // check profile
            $profileIsComplete = true;
            try {
                $userProfile = Yii::$app->serviceApi->getUserProfile(Yii::$app->user->id);

                // check language
                if($userProfile && $userProfile->locale){
                    $this->setLanguage($userProfile->locale);
                }

                $profileFieldsToCheck = [
                    'first_name',
                    'last_name',
                    'locale',
                    'gender'
                ];

                foreach ($profileFieldsToCheck as $profileFieldToCheck) {
                    if($userProfile[$profileFieldToCheck] == '' || $userProfile[$profileFieldToCheck] == null ){
                        $profileIsComplete = false;
                        break;
                    }
                }
            } catch (\Exception $e) {
                Yii::debug('Could not verify user profile completion for user [' .Yii::$app->user->id. ']: ' . $e, 'wenet.controller.base');
            }

            if(!$profileIsComplete){
                $content = Yii::t('profile', 'Remember to fill in your') .' '. Html::a( Yii::t('profile', 'profile'), ['/user/profile']) . '.';
                Yii::$app->session->setFlash('error', $content);
            }

        }
    }

    public function setLanguage($userLang){
        if(strpos($userLang, 'it_') !== false){
            Yii::$app->language = 'it-IT';
        }
        // else if(strpos($userLang, 'es_') !== false){
        //     Yii::$app->language = 'es-ES';
        // } else if(strpos($userLang, 'mn') !== false){
        //     Yii::$app->language = 'mn';
        // } else if(strpos($userLang, 'da') !== false){
        //     Yii::$app->language = 'da';
        // }
        else {
            Yii::$app->language = 'en-US';
        }
    }

}
