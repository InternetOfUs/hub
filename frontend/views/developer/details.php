<?php
    use yii\helpers\Url;
    use frontend\models\WenetApp;
    use frontend\models\AppPlatform;

    $this->title = Yii::$app->name . ' | ' . $app->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer'), 'url' => ['developer/index']];
    $this->params['breadcrumbs'][] = $app->name;

    $showTelegram = false;
    if($app->hasPlatformTelegram()){
        $showTelegram = true;
        $telegram = $app->getPlatformTelegram();
    }
?>

<?php if(!$showTelegram){ ?>
    <a href="<?= Url::to(['/platform/create-telegram', 'id' => $app->id]); ?>" class="btn btn-primary telegram_color pull-right" style="margin: -10px 0 20px 0;">
        <i class="fa fa-plus" aria-hidden="true"></i>
        <?php echo Yii::t('app', 'add Telegram'); ?>
    </a>
<?php } ?>
<a href="<?= Url::to(['/developer/update', 'id' => $app->id]); ?>" class="btn btn-primary pull-right" style="margin: -10px 5px 20px 0;">
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
                    echo '<span class="status_icon not_active"><i class="fa fa-pause-circle-o" aria-hidden="true"></i> '.Yii::t('app', 'In development').'</span>';
                } else if($app->status == WenetApp::STATUS_ACTIVE){
                    echo '<span class="status_icon active"><i class="fa fa-check-circle-o" aria-hidden="true"></i> '.Yii::t('app', 'Live').'</span>';
                }
            ?>
        </h1>
        <p style="margin:20px 0 0 0;"><?php echo $app->description; ?></p>
        <p style="margin:20px 0 0 0;">
            <?php echo Yii::t('app', 'Message Callback Url'); ?>:
            <strong><?php echo $app->message_callback_url;?></strong>
        </p>
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
            <h3><?php echo Yii::t('app', 'Data ingestion'); ?></h3>
            <p><?php echo Yii::t('app', 'To authenticate requests, you will need to include the following parameters in the header of each call:'); ?></p>
            <table class="attribute_container">
                <tr>
                    <td><span>id:</span></td>
                    <td><pre><?php echo $app->id; ?></pre></td>
                </tr>
            </table>
            <table class="attribute_container">
                <tr>
                    <td><span>token:</span></td>
                    <td><pre><?php echo $app->token; ?></pre></td>
                </tr>
            </table>
        </div>
    </div>
    <?php if(count($app->platforms()) >= 1){
        ?>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="box_container">
                <h3><?php echo Yii::t('app', 'Active users for platform'); ?></h3>
                <?php
                    $content = '<table class="active_users_platform">';
                    foreach ($app->platforms() as $platform) {
                        $content .= '<tr>';
                            $content .= '<td><p>'.AppPlatform::typeLabel($platform->type).'</p></td>';
                            if($platform->type == AppPlatform::TYPE_TELEGRAM){
                                $content .= '<td><p><strong>'.$app->numberOfActiveUserForTelegram().'</strong> '.Yii::t('common', 'active users').'</p></td>';
                            }
                        $content .= '</tr>';
                    }
                    $content .= '</table>';
                    echo $content;
                ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php if(count($app->platforms()) >= 1){ ?>
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <h2 style="margin-bottom: 15px; font-size:20px;"><?php echo Yii::t('app', 'Connected platforms'); ?></h2>
        </div>
    </div>
    <div class="row">
        <?php if($showTelegram){ ?>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                <div class="box_container platform_container">
                    <h3>Telegram</h3>
                    <p>
                        <?php echo Yii::t('app', 'Bot Username'); ?>:
                        <strong><?php echo $telegram->bot_username;?></strong>
                    </p>
                    <hr>
                    <p><?php echo Yii::t('app', 'Don\'t forget to send the /setdomain command to @Botfather to link {domain} domain to the bot.', [
                        'domain' => parse_url(Url::base('https'), PHP_URL_SCHEME) . '://' . parse_url(Url::base('https'), PHP_URL_HOST),
                    ]); ?></p>
                    <a class="normal_link" href="https://core.telegram.org/widgets/login#linking-your-domain-to-the-bot" target="_blank"><?php echo Yii::t('app', 'More info'); ?></a>
                    <hr>
                    <a href="<?= Url::to(['/platform/delete-telegram', 'id' => $telegram->id]); ?>" class="btn delete_btn pull-right" title="<?php echo Yii::t('app', 'Detele platform'); ?>">
                        <i class="fa fa-trash"></i> <?php echo Yii::t('common', 'delete'); ?>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
