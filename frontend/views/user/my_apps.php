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

                         $activeSourceLinks = '';
                         if($app->hasActiveSourceLinksForApp()){
                             $activeSourceLinks .= '<ul class="source_links_list table_view">' . implode(array_map(function($sl){
                                 return '<li><img src="'.Url::base().'/images/platforms/'.$sl.'.png" alt="'.$sl." ". Yii::t('app', 'Source link image').'"></li>';
                             }, $app->getActiveSourceLinksForApp()), '') . '</ul>';
                         }

                         $content = '<a href="'. Url::to(['/wenetapp/user-app-details', 'id' => $app->id, 'back' => 'profile']) .'" class="app user_apps">';
                            $content .= '<div class="app_icon big_icon"><span>'.strtoupper($app->name[0]).'</span></div>';
                             $content .= '<h2>'. $app->name .'</h2>';
                             $content .= '<p>'. $app->description .'</p>';
                             $content .= $activeSourceLinks;
                         $content .= '</a>';

                         echo $content;
                     }
                ?>
            </div>
    	</div>
	</div>
<?php } ?>
