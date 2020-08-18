<?php
    use yii\helpers\Url;
    use frontend\models\WenetApp;
    use frontend\models\AppPlatform;

    $this->title = Yii::$app->name . ' | ' . $app->name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Developer'), 'url' => ['developer/index']];
    $this->params['breadcrumbs'][] = $app->name;

    $showSocialLogin = false;
    if($app->hasSocialLogin()){
        $showSocialLogin = true;
        $socialLogin = $app->getSocialLogin();
    }
?>

<?php if(!$showSocialLogin){ ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="alert alert-warning" role="alert" style="margin-top:-15px;">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> WARNING - Applications require OAuth2 to go live!
            </div>
        </div>
    </div>
<?php } ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php if(!$showSocialLogin){ ?>
            <a href="<?= Url::to(['/oauth/create-oauth', 'id' => $app->id]); ?>" class="btn btn-warning pull-right" style="margin:0 0 0 0;">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                <?php echo Yii::t('app', 'configure OAuth2'); ?>
            </a>
        <?php } ?>
        <a href="<?= Url::to(['/developer/update', 'id' => $app->id]); ?>" class="btn btn-primary pull-right" style="margin: 0 5px 0 0;">
            <i class="fa fa-pencil" aria-hidden="true"></i>
            <?php echo Yii::t('common', 'edit app'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="app_icon big_icon">
            <span><?php echo strtoupper($app->name[0]); ?></span>
        </div>
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
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
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
            <h3><?php echo Yii::t('app', 'App Details'); ?></h3>
            <p><?php echo Yii::t('app', 'To authenticate requests, you will need to include the following parameters in the header of each call:'); ?></p>
            <table class="attribute_container">
                <tr>
                    <td><span>id:</span></td>
                    <td><pre><?php echo $app->id; ?></pre></td>
                </tr>
            </table>
            <table class="attribute_container">
                <tr>
                    <td><span>secret:</span></td>
                    <td><pre><?php echo $app->token; ?></pre></td>
                </tr>
            </table>
        </div>
    </div>
    <?php if($showSocialLogin){ ?>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="box_container">
                <h3>OAuth2 Settings</h3>
                <p>
                    <?php echo Yii::t('app', 'Callback Url');  # TODO use model label ?>:
                    <pre><?php echo $socialLogin->callback_url;?></pre>
                </p>
                <hr>
                <a href="<?= Url::to(['/oauth/delete-oauth', 'id' => $socialLogin->id]); ?>" class="btn delete_btn pull-right" title="<?php echo Yii::t('app', 'Detele OAuth'); ?>">
                    <i class="fa fa-trash"></i> <?php echo Yii::t('common', 'delete'); ?>
                </a>
                <a href="<?= Url::to(['/oauth/update-oauth', 'id' => $socialLogin->id]); ?>" style="margin-right:10px;" class="btn btn-primary pull-right" title="<?php echo Yii::t('common', 'edit'); ?>">
                    <i class="fa fa-pencil"></i> <?php echo Yii::t('common', 'edit'); ?>
                </a>
            </div>
        </div>
    <?php } ?>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <hr>
        <?php if(!$showTelegram){ ?>
            <a href="<?= Url::to(['/platform/create-telegram', 'id' => $app->id]); ?>" class="btn btn-primary telegram_color pull-right" style="margin:0 0 0 10px;">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <?php echo Yii::t('app', 'add Telegram'); ?>
            </a>
        <?php } ?>
        <?php if(!$showSocialLogin){ ?>
            <a href="<?= Url::to(['/platform/create-social-login', 'id' => $app->id]); ?>" class="btn btn-primary telegram_color pull-right" style="margin:0 0 0 10px;">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <?php echo Yii::t('app', 'add Wenet social login'); ?>
            </a>
        <?php } ?>
    </div>
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
                        <?php echo Yii::t('app', 'Bot Username'); # TODO use model label ?>:
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
        <?php if($showSocialLogin){ ?>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                <div class="box_container platform_container">
                    <h3>WeNet Social Login</h3>
                    <p>
                        <?php echo Yii::t('app', 'OAuth2 Callback Url');  # TODO use model label ?>:
                        <strong><?php echo $socialLogin->callback_url   ;?></strong>
                    </p>
                    <hr>
                    <a href="<?= Url::to(['/platform/delete-social-login', 'id' => $socialLogin->id]); ?>" class="btn delete_btn pull-right" title="<?php echo Yii::t('app', 'Detele platform'); ?>">
                        <i class="fa fa-trash"></i> <?php echo Yii::t('common', 'delete'); ?>
                    </a>
                    <a href="<?= Url::to(['/platform/update-social-login', 'id' => $socialLogin->id]); ?>" style="margin-right:10px;" class="btn btn-primary pull-right" title="<?php echo Yii::t('common', 'edit'); ?>">
                        <i class="fa fa-pencil"></i> <?php echo Yii::t('common', 'edit'); ?>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
