<?php
    use yii\helpers\Url;

    $this->title = Yii::$app->name . ' | ' . $app->name;

    if(Yii::$app->request->get('back') == "index"){
        $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Apps'), 'url' => ['index']];
        $this->params['breadcrumbs'][] = $app->name;
    } else if(Yii::$app->request->get('back') == 'profile'){
        $this->params['breadcrumbs'][] = Yii::$app->user->identity->username;
        $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'My Apps'), 'url' => ['user/user-apps']];
        $this->params['breadcrumbs'][] = $app->name;
    }
?>

<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <div class="app_icon big_icon">
            <span><?php echo strtoupper($app->name[0]); ?></span>
        </div>
        <h1><?php echo $app->name; ?></h1>
        <p style="margin:20px 0;"><?php echo $app->description; ?></p>
        <p><strong><?php echo Yii::t('app', 'Creator'); ?>:</strong> <?php echo $app->getOwnerShortName(); ?></p>
        <p><strong><?php echo Yii::t('app', 'Available platforms (for download or direct use)'); ?>:</strong></p>
        <?php
            $activeSourceLinks = '<ul class="source_links_list details_view">';
            if($app->getActiveSourceLinksForApp()){
                $activeSourceLinks .= implode(array_map(function($sl)use ($app){
                    return '<li><a href="'.$app->allMetadata['source_links'][$sl].'" target="_blank"><img src="'.Url::base().'/images/platforms/'.$sl.'.png" alt="'.$sl." ". Yii::t('app', 'Source link image').'"></a></li>';
                }, $app->getActiveSourceLinksForApp()), '');
            }
            $activeSourceLinks .= '</ul>';
            echo $activeSourceLinks;

            if(count($app->associatedCategories) > 0){
                $categories = '<ul class="tags_list">';
                foreach ($app->associatedCategories as $category) {
                    $categories .= '<li>'.$category.'</li>';
                }
                $categories .= '</ul>';
                echo $categories;
            }
        ?>
    </div>
</div>
