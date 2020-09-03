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
        <h1><?php echo $app->name; ?></h1>
        <p style="margin:20px 0;"><?php echo $app->description; ?></p>
        <p><strong><?php echo Yii::t('app', 'Creator'); ?>:</strong> <?php echo $app->getOwnerShortName(); ?></p>
        <?php
            if(count($app->associatedCategories) > 0){
                $categories = '<ul class="tags_list">';
                foreach ($app->associatedCategories as $category) {
                    $categories .= '<li>'.$category.'</li>';
                }
                $categories .= '</ul>';
                echo $categories;
            }

            // TODO show platforms icons + link where to download or use the service
        ?>
    </div>
</div>
