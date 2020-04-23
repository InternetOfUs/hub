<?php
    use yii\helpers\Url;
    use frontend\models\WenetApp;

    $this->title = Yii::$app->name . ' | ' . $app->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer'), 'url' => ['index-developer']];
    $this->params['breadcrumbs'][] = $app->name;
?>

<!-- TODO -->
<a href="<?= Url::to('/wenetapp/create'); ?>" class="btn btn-primary pull-right" style="margin: -10px 0 20px 0;">
    <i class="fa fa-plus" aria-hidden="true"></i>
    <?php echo Yii::t('app', 'add platform'); ?>
</a>
<a href="<?= Url::to(['/wenetapp/update', 'id' => $app->id]); ?>" class="btn btn-primary pull-right" style="margin: -10px 5px 20px 0;">
    <i class="fa fa-pencil" aria-hidden="true"></i>
    <?php echo Yii::t('common', 'edit'); ?>
</a>

<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
        <h1>
            <?php echo $app->name; ?>
            <span> | </span>
            <?php
                if($app->status == WenetApp::STATUS_NOT_ACTIVE){
                    echo '<span class="status_icon"><i class="fa fa-pause-circle-o" aria-hidden="true"></i></span>';
                } else if($app->status == WenetApp::STATUS_ACTIVE){
                    echo '<span class="status_icon"><i class="fa fa-check-circle-o" aria-hidden="true"></i></span>';
                }
                echo '<span class="status_span"> '.Yii::t('app', 'status') . '</span>';
            ?>
        </h1>

        <p style="margin:20px 0 0 0;"><?php echo $app->description; ?></p>
        <?php
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
<div class="row" style="margin-top:20px;">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="box_container">
            <p>placeholder testo</p>
            <div class="attribute_container">
                <span>id:</span>
                <pre><?php echo $app->id; ?></pre>
            </div>
            <div class="attribute_container">
                <span>token:</span>
                <pre><?php echo $app->token; ?></pre>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="box_container">
            utenti
            <!-- TODO numero utenti attivi per piattaforma -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- TODO visualizzazione e btn per aggiungere piattaforme -->
        <!-- tabella non ha senso! tutti campi diversi -->

    </div>
</div>


<!-- aggiungere disclaimer per dominio da aggiungere aggiungere link https://core.telegram.org/widgets/login#linking-your-domain-to-the-bot -->
<!-- send the /setdomain command to @Botfather to link your website's domain to the bot. -->
