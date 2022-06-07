<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'js/isotope.pkgd.min.js',
        'https://code.highcharts.com/highcharts.js',
        'https://code.highcharts.com/highcharts-more.js',
        'https://code.highcharts.com/modules/solid-gauge.js',
        'https://code.highcharts.com/modules/variable-pie.js',
        'https://www.google.com/recaptcha/api.js', //Google reCaptcha
    ];
    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'frontend\assets\FontAwesomeAsset',
    ];

    private $localCss = [
        'css/site.css',
        'css/responsive.css',
    ];

    private $localJs = [
        'js/basicGraphs.js'
    ];

    public function init() {
    	parent::init();

    	if (Yii::$app->request->isAjax || Yii::$app->request->isPjax) {
        	$this->js = [];
        	$this->css = [];
            return ;
    	}

        foreach ($this->localCss as $css) {
            $this->css[] = $css . '?v=' . Yii::$app->params['hub.version'];
        }

        foreach ($this->localJs as $js) {
            $this->js[] = $js . '?v=' . Yii::$app->params['hub.version'];
        }
	}
}
