<?php
    use yii\helpers\Url;
    use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . Yii::t('common', 'My Apps');
    $this->params['breadcrumbs'][] = Yii::$app->user->identity->username;
    $this->params['breadcrumbs'][] = Yii::t('common', 'My Apps');

    $apps = Yii::$app->user->getIdentity()->apps;
?>

<?php if (count($apps) == 0) { ?>
    <div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <p style="text-align:center; font-weight:400;">
                <?php echo Yii::t('app', 'There are no apps to display.'); ?>
            </p>
        </div>
    </div>
<?php } else { ?>
    <div class="row apps_section">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    		<div class="userAppsContainer">
                <?php
                     foreach ($apps as $key => $app) {
                         // $availablePlatforms = [];
                         // if($app->hasPlatformTelegram()) {
                         //     $availablePlatforms[] = AppPlatform::TYPE_TELEGRAM;
                         // }
                         //
                         // $platformsContent = '';
                         // if(count($availablePlatforms) > 0){
                         //     $platformsContent .= '<ul class="platform_icons">';
                         //     foreach ($availablePlatforms as $key => $ap) {
                         //         $platformsContent .= '<li>';
                         //             $platformsContent .= '<div class="image_container" style="align-self: flex-end">';
                         //                 $platformsContent .= '<img src="'.Url::base().'/images/platforms/'.$ap.'.png" alt="'. Yii::t('title', 'platform icon') .'">';
                         //             $platformsContent .= '</div>';
                         //         $platformsContent .= '</li>';
                         //     }
                         //     $platformsContent .= '</ul>';
                         // } else {
                         //     $platformsContent .= '';
                         // }

                         $content = '<a href="'. Url::to(['/wenetapp/details', 'id' => $app->id, 'back' => 'profile']) .'" class="app user_apps">';
                             $content .= '<h2>'. $app->name .'</h2>';
                             $content .= '<p>'. $app->description .'</p>';
                             // $content .= $platformsContent;
                         $content .= '</a>';

                         echo $content;
                     }
                ?>
            </div>
    	</div>
	</div>
<?php } ?>
